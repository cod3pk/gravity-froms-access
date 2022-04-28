<?php
/*
* Plugin Name: Gravity Forms Access
* Description: This plugin restricts the access of users for Gravity forms records on the basis of From Entries
* Author: Asfand Yar Ali Khan
* Version: 1.0.0
* Author URI: http://www.640square.com/
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!class_exists('Gravity_Forms_Access')) :
    class Gravity_Forms_Access
    {

        function initialize_hook()
        {

            add_action('init', [$this, 'create_user_role']);
            add_action('init', [$this, 'load_form_view']);
        }

        /**
         * Creates Role in WordPress Core
         *
         * @return void
         */
        function create_user_role()
        {

            $capabilities = array(
                'edit_dashboard'            => true,
                'manage_links'              => true,
                'read'                      => true,
                'gravityforms_view_entries' => true
            );

            add_role('Viewer', 'Viewer', $capabilities);
            add_role('Argenta Flats - North Little Rock, AR', 'Argenta Flats - North Little Rock, AR', $capabilities);
            add_role('Brentwood Apartments – Conway. AR', 'Brentwood Apartments – Conway. AR', $capabilities);
            add_role('Glenrock Apartments – Conway, AR', 'Glenrock Apartments – Conway, AR', $capabilities);
            add_role('Helen Street – Conway, AR', 'Helen Street – Conway, AR', $capabilities);
            add_role('Jlofts – Conway, AR', 'Jlofts – Conway, AR', $capabilities);
            add_role('Kyle Kourt – Conway, AR', 'Kyle Kourt – Conway, AR', $capabilities);
            add_role('Lasley Historic Flats – Conway, AR', 'Lasley Historic Flats – Conway, AR', $capabilities);
            add_role('Robinson Court – Conway, AR', 'Robinson Court – Conway, AR', $capabilities);
            add_role('Row Houses at Hendrix Village – Conway, AR', 'Row Houses at Hendrix Village – Conway, AR', $capabilities);
            add_role('Robins Square Apartments – Conway, AR', 'Robins Square Apartments – Conway, AR', $capabilities);
            add_role('St. James Park – Conway, AR', 'St. James Park – Conway, AR', $capabilities);
            add_role('Centerstone - Conway, AR', 'Centerstone - Conway, AR', $capabilities);
            add_role('ONLINEAPP-ENGAGE', 'ONLINEAPP-ENGAGE', $capabilities);
        }

        function return_gf_search()
        {
            if ($this->get_current_user_role() == 'ONLINEAPP-ENGAGE') {
                $search = array(
                    'St. James Park – Conway, AR',
                    'Robins Square Apartments – Conway, AR',
                    'Row Houses at Hendrix Village – Conway, AR',
                    'Robinson Court – Conway, AR',
                    'Lasley Historic Flats – Conway, AR',
                    'Kyle Kourt – Conway, AR',
                    'Jlofts – Conway, AR',
                    'Helen Street – Conway, AR'
                );
                return array(
                    'key'   => '23',
                    'operator' => 'in',
                    'value' => $search
                );

            } else {
                return array(
                    'key'   => '23',
                    'value' => $this->get_current_user_role()
                );
            }
        }

        /**
         * Search Criteria - Returns Entries Query
         *
         * @return Array
         */
        function get_required_form_entries()
        {

            $search_criteria = array(
                'status'        => 'active',
                'field_filters' => array(
                    'mode' => 'any',
                    $this->return_gf_search(),
                    array(
                        'key'   => '23',
                        'operator' => 'in',
                        'value' => $this->return_gf_search()
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
        function get_current_user_role()
        {

            // Get current User Role
            $user = wp_get_current_user()->roles[0];
            return $user;
        }

        function redirect_to_form()
        {
            $url = admin_url("admin.php?page=gf_entries&id=9");
            wp_redirect($url);
        }

        function remove_menu_pages_gf()
        {
            remove_menu_page('index.php');
            remove_menu_page('profile.php');
            remove_menu_page('users.php');
            remove_menu_page('esign-ninja-about');
            remove_menu_page('vc-welcome');
        }

        function remove_gf_css()
        {
            echo "<script> document.querySelector('.gform-form-toolbar').style.display = 'none'; </script>";
            echo "<script> document.querySelector('#entry_search_container').style.display = 'none'; </script>";
        }


        /**
         * Loads the hook for specific Role
         *
         * @return void
         */
        function load_form_view()
        {

            $user = $this->get_current_user_role();

            if ($user !== 'administrator') {
                add_action('admin_init', [$this, 'remove_menu_pages_gf']);
                add_filter('gform_search_criteria_entry_list_9', [$this, 'get_required_form_entries'], 10, 2);
                add_action('profile_personal_options', [$this, 'redirect_to_form']);
                add_action('admin_init', [$this, 'remove_gf_css']);
            } else if ($user == 'administrator') {

                return;
            }
        }
    }

    $gravity_forms_access = new Gravity_Forms_Access();
    $gravity_forms_access->initialize_hook();

endif;
