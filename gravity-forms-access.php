<?php
/*
* Plugin Name: Gravity Forms Access
* Description: This plugin restricts the access of users for Gravity forms records on the basis of From Entries
* Author: Asfand Yar Ali Khan
* Version: 1.0.0
* Author URI: http://www.640square.com/
*/

if ( !defined ( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// Get Data from here
// http://localhost/jason/wp-json/gf/v2/entries

if ( ! class_exists( 'Gravity_Forms_Access' )) :
    class Gravity_Forms_Access {

        function initialize_hook() {
            add_action( 'init', [ $this, 'create_user_role' ] );
            add_action( 'init', [ $this, 'get_current_user_role' ] );
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

        /**
         * Gets Get Current User Role and 
         * displays entries
         *
         * @return void
         */
        function get_current_user_role() {
            // Get current User Role
            $user = wp_get_current_user()->roles[0];
            if ( $user !== 'administrator' ) {
                add_filter( 'gform_search_criteria_entry_list_1', [ $this, 'get_required_form_entries' ], 10, 2 );
            } else if ( $user == 'administrator' ) {
                return;
            }
        }

        function get_required_form_entries() {

            $form_id = '1';
            $search_criteria = array(
                'status'        => 'active',
                'field_filters' => array(
                    'mode' => 'any',
                    array(
                        'key'   => '2',
                        'value' => 'B'
                    ),
                )
            );

            return $search_criteria;
        }

    }

    $gravity_forms_access = new Gravity_Forms_Access();
    $gravity_forms_access->initialize_hook();

endif;