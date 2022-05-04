<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       #
 * @since      1.0.0
 *
 * @package    Devsitewarning
 * @subpackage Devsitewarning/public/partials
 */

?>

<div class="devsite-warning" style="<?php switch ($warning_position_1) {
                                        case 'topleft':
                                            echo "top: 2rem; left: 2rem;";
                                            break;
                                        case 'topright':
                                            echo "top: 2rem; right: 2rem;";
                                            break;
                                        case 'bottomleft':
                                            echo "bottom: 2rem; left: 2rem;";
                                            break;
                                        case 'bottomright':
                                            echo "bottom: 2rem; right: 2rem;";
                                            break;
                                        default:
                                            echo "bottom: 2rem; left: 2rem;";
                                            break;
                                    } ?>">
    <div class="devsite-warning__txt">
        <?php
        // Check Variable is set.
        if ($best_guess) {
            echo $best_guess;
        }
        ?>
        Development Site
    </div>
    <div class="devsite-warning__bg"></div>
</div>