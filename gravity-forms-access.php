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
            add_action( 'init', [ $this, 'load_form_view' ] );
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

            add_role( 'Viewer', 'Viewer', $capabilities );
            add_role( 'argenta-flats-north-little-rock-ar', 'Argenta Flats - North Little Rock, AR', $capabilities );
            add_role( 'brentwood-apartments–conway-ar', 'Brentwood Apartments – Conway. AR', $capabilities );
            add_role( 'glenrock-apartments–conway-ar', 'Glenrock Apartments – Conway, AR', $capabilities );
            add_role( 'helen-street–conway-ar', 'Helen Street – Conway, AR', $capabilities );
            add_role( 'jlofts–conway-ar', 'Jlofts – Conway, AR', $capabilities );
            add_role( 'kyle-kourt–conway-ar', 'Kyle Kourt – Conway, AR', $capabilities );
            add_role( 'lasley-historic-flats–conway-ar', 'Lasley Historic Flats – Conway, AR', $capabilities );
            add_role( 'robinson-court–conway-ar', 'Robinson Court – Conway, AR', $capabilities );
            add_role( 'row-houses-at-hendrix-village–conway-ar', 'Row Houses at Hendrix Village – Conway, AR', $capabilities );
            add_role( 'robins-square-apartments–conway-ar', 'Robins Square Apartments – Conway, AR', $capabilities );
            add_role( 'st-james-park–conway-ar', 'St. James Park – Conway, AR', $capabilities );
            add_role( 'centerstone-conway-ar', 'Centerstone - Conway, AR', $capabilities );
        }

        function get_required_form_entries()
        {

            $form_id = '1';
            $search_criteria = array(
                'status'        => 'active',
                'field_filters' => array(
                    'mode' => 'any',
                    array(
                        'key'   => '2',
                        'value' => $this->get_current_user_role()
                    ),
                )
            );

            return $search_criteria;
        }

        /**
         * Undocumented function
         *
         * @return Current_User_Role
         */
        function get_current_user_role() {

            // Get current User Role
            $user = wp_get_current_user();
            return $user;

        }

        /**
         * Loads the hook for specific Role
         *
         * @return void
         */
        function load_form_view() {

            $user = $this->get_current_user_role();

            if ( $user !== 'administrator' ) {
                add_filter( 'gform_search_criteria_entry_list_1', [ $this, 'get_required_form_entries' ], 10, 2);
            } else if ( $user == 'administrator' ) {
                return;
            }
        }
    }

    $gravity_forms_access = new Gravity_Forms_Access();
    $gravity_forms_access->initialize_hook();

endif;
