<?php
/*
* Plugin Name: Gravity Forms Access
* Description: This plugin restricts the access of users for Gravity forms records on the basis of From Entries
* Author: Jason Sandefer
* Version: 1.0.0
* Author URI: http://www.640square.com/
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if ( ! class_exists( 'Gravity_Forms_Access' )) :
    class Gravity_Forms_Access {

        function register_hooks() {
            add_action( 'init', [$this, 'create_user_role'] );
        }

        /**
         * Creates Role in WordPress Core
         *
         * @return void
         */
        function create_user_role() {

            $capabilities = array(
                'edit_dashboard'            => true,
                'manage_links'              => true,
                'read'                      => true,
                'gravityforms_view_entries' => true
            );

            add_role( 'viewer', 'Viewer', $capabilities );
        }

    }

    $gravity_forms_access = new Gravity_Forms_Access();
    $gravity_forms_access->register_hooks();

endif;