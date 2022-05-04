<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       #
 * @since      1.0.0
 *
 * @package    Devsitewarning
 * @subpackage Devsitewarning/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Devsitewarning
 * @subpackage Devsitewarning/public
 * @author     Jonny Jones <#>
 */
class Devsitewarning_Public
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

		/**
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Devsitewarning_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Devsitewarning_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/devsitewarning-public.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{

		/**
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Devsitewarning_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Devsitewarning_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/devsitewarning-public.js', array('jquery'), $this->version, false);
	}
}


// Check user is admin
function devsitewarning_admin_checker()
{
	return current_user_can('manage_options');
}


function devsite_Warning()
{
	// Get the plugin options
	$devsite_checker_options = get_option('devsite_checker_option_name'); // Array of All Options
	$warning_position_1 = $devsite_checker_options['warning_position_1']; // Warning Position
	$live_site_url_0 = $devsite_checker_options['live_site_url_0']; // Live site URL

	$best_guess = '';

	// Get current site url
	$siteurl = $_SERVER['HTTP_HOST'];
	// By default variable is set to false (not live)
	$is_live = false;
	// Now lets compare the URL defined in the plugin options to the current URL
	$compare_url = strcmp($siteurl, $live_site_url_0);
	if ($compare_url === 0) {
		// If "$siteurl" is the same as "$live_site_url_0 (defined in the options)"
		// Set the variable to be true
		$is_live = true;
	}

	// If the site is live
	if ($is_live === true) {
		// If user is an admin
		if (devsitewarning_admin_checker()) {
			// Display our live warning
			require(plugin_dir_path(__FILE__) . 'partials/devsitewarning-live_warning.php');
		}
	}

	// If the current url doesn't match the URL defined in the setting page we can assume it's a dev site
	if ($is_live == false) {
		// Lets quickly check it's not got a .local domain first
		$local = strpos($_SERVER['HTTP_HOST'], '.local');
		if ($local == true) {
			// Show local warning
			require(plugin_dir_path(__FILE__) . 'partials/devsitewarning-local_warning.php');
		} else {
			// Otherwise we know it's a dev site.
			require(plugin_dir_path(__FILE__) . 'partials/devsitewarning-dev_warning.php');
		}
	}

	// Okay, we don't have anything in our liveurl field so lets try and work out if it's a dev site or not
	if (empty($live_site_url_0)) {
		$je = strpos($_SERVER['HTTP_HOST'], '.je');
		$com = strpos($_SERVER['HTTP_HOST'], '.com');
		$couk = strpos($_SERVER['HTTP_HOST'], '.co.uk');
		$orgje = strpos($_SERVER['HTTP_HOST'], '.org.je');
		$gg = strpos($_SERVER['HTTP_HOST'], '.gg');
		$fr = strpos($_SERVER['HTTP_HOST'], '.fr');

		$best_guess = 'Best Guess:';

		$live_domain_check = $je || $com || $couk || $orgje || $gg || $fr;

		// If primary domain is a live domain
		if ($live_domain_check == true) {
			// Create Live warning
			require(plugin_dir_path(__FILE__) . 'partials/devsitewarning-live_warning.php');
		}

		// If primary domain is a .local domain
		if ($local == true) {
			// Show local warning
			require(plugin_dir_path(__FILE__) . 'partials/devsitewarning-local_warning.php');
		}

		$wpengine_dev = strpos($_SERVER['HTTP_HOST'], '.wpengine.com');
		// If primary domain is a .wpengine domain
		if ($wpengine_dev == true) {
			// Show local warning
			require(plugin_dir_path(__FILE__) . 'partials/devsitewarning-dev_warning.php');
		}
	}
}

// Call our function in the footer
add_action('wp_footer', 'devsite_Warning');
