<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       #
 * @since      1.0.0
 *
 * @package    Devsitewarning
 * @subpackage Devsitewarning/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Devsitewarning
 * @subpackage Devsitewarning/admin
 * @author     Jonny Jones <#>
 */
class Devsitewarning_Admin
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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/devsitewarning-admin.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/devsitewarning-admin.js', array('jquery'), $this->version, false);
	}
}



class DevsiteChecker
{
	private $devsite_checker_options;

	public function __construct()
	{
		add_action('admin_menu', array($this, 'devsite_checker_add_plugin_page'));
		add_action('admin_init', array($this, 'devsite_checker_page_init'));
	}

	public function devsite_checker_add_plugin_page()
	{
		add_options_page(
			'Devsite Checker', // page_title
			'Devsite Checker', // menu_title
			'manage_options', // capability
			'devsite-checker', // menu_slug
			array($this, 'devsite_checker_create_admin_page') // function
		);
	}

	public function devsite_checker_create_admin_page()
	{
		$this->devsite_checker_options = get_option('devsite_checker_option_name'); ?>

		<div class="wrap">
			<h2>Devsite Checker</h2>
			<p></p>
			<?php settings_errors(); ?>

			<form method="post" action="options.php">
				<?php
				settings_fields('devsite_checker_option_group');
				do_settings_sections('devsite-checker-admin');
				submit_button();
				?>
			</form>
		</div>
	<?php }

	public function devsite_checker_page_init()
	{
		register_setting(
			'devsite_checker_option_group', // option_group
			'devsite_checker_option_name', // option_name
			array($this, 'devsite_checker_sanitize') // sanitize_callback
		);

		add_settings_section(
			'devsite_checker_setting_section', // id
			'Settings', // title
			array($this, 'devsite_checker_section_info'), // callback
			'devsite-checker-admin' // page
		);

		add_settings_field(
			'live_site_url_0', // id
			'Live site URL', // title
			array($this, 'live_site_url_0_callback'), // callback
			'devsite-checker-admin', // page
			'devsite_checker_setting_section' // section
		);

		add_settings_field(
			'warning_position_1', // id
			'Warning Position', // title
			array($this, 'warning_position_1_callback'), // callback
			'devsite-checker-admin', // page
			'devsite_checker_setting_section' // section
		);
	}

	public function devsite_checker_sanitize($input)
	{
		$sanitary_values = array();
		if (isset($input['live_site_url_0'])) {
			$sanitary_values['live_site_url_0'] = sanitize_text_field($input['live_site_url_0']);
		}

		if (isset($input['warning_position_1'])) {
			$sanitary_values['warning_position_1'] = $input['warning_position_1'];
		}

		return $sanitary_values;
	}

	public function devsite_checker_section_info()
	{
	}

	public function live_site_url_0_callback()
	{
		printf(
			'<input class="regular-text" type="text" name="devsite_checker_option_name[live_site_url_0]" id="live_site_url_0" value="%s">',
			isset($this->devsite_checker_options['live_site_url_0']) ? esc_attr($this->devsite_checker_options['live_site_url_0']) : ''
		);
	}

	public function warning_position_1_callback()
	{
	?> <select name="devsite_checker_option_name[warning_position_1]" id="warning_position_1">
			<?php $selected = (isset($this->devsite_checker_options['warning_position_1']) && $this->devsite_checker_options['warning_position_1'] === 'bottomleft') ? 'selected' : ''; ?>
			<option value="bottomleft" <?php echo $selected; ?>>Bottom Left</option>
			<?php $selected = (isset($this->devsite_checker_options['warning_position_1']) && $this->devsite_checker_options['warning_position_1'] === 'bottomright') ? 'selected' : ''; ?>
			<option value="bottomright" <?php echo $selected; ?>>Bottom Right</option>
			<?php $selected = (isset($this->devsite_checker_options['warning_position_1']) && $this->devsite_checker_options['warning_position_1'] === 'topleft') ? 'selected' : ''; ?>
			<option value="topleft" <?php echo $selected; ?>>Top Left</option>
			<?php $selected = (isset($this->devsite_checker_options['warning_position_1']) && $this->devsite_checker_options['warning_position_1'] === 'topright') ? 'selected' : ''; ?>
			<option value="topright" <?php echo $selected; ?>>Top Right</option>
		</select> <?php
				}
			}
			if (is_admin())
				$devsite_checker = new DevsiteChecker();

/* 
 * Retrieve these value with:
 * $devsite_checker_options = get_option( 'devsite_checker_option_name' ); // Array of All Options
 * $live_site_url_0 = $devsite_checker_options['live_site_url_0']; // Live site URL
 * $warning_position_1 = $devsite_checker_options['warning_position_1']; // Warning Position
 */
