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

class Gravity_Forms_Access {

    function register_hooks() {
        add_action( 'init', [$this, 'create_user_role'] );
    }

    function create_user_role() {
        $capabilities = array(
            'read'  => true
        );

        add_role( 'viewer', 'Viewer', $capabilities );
    }

}

$gravity_forms_access = new Gravity_Forms_Access();
$gravity_forms_access->register_hooks();