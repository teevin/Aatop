<?php
/**
 * Plugin Name: Aatop
 * Description: Description
 * Author: Kevin Tsvigu
 * Version: 1.0.0
 * License: GPL2
 */

/*
    Copyright (C) Year  Author  Email

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

define('PLUGIN_NAME_VERSION', '1.0.0');

function activate_aatop()
{
    require_once plugin_dir_path(__FILE__) . 'classes/initialise_aatop.php';
    AatopInit::aatop_activate();
}

function deactivate_aatop()
{
    require_once plugin_dir_path(__FILE__) . 'classes/initialise_aatop.php';
    AatopInit::aatop_deactivate();
}

function uninstall_aatop()
{
    require_once plugin_dir_path(__FILE__) . 'classes/initialise_aatop.php';
    AatopInit::aatop_uninstall();
}

function aatop_admin_menu()
{
    require_once plugin_dir_path(__FILE__) . 'classes/initialise_aatop.php';
    require_once plugin_dir_path(__FILE__) . 'classes/adminhtml.php';
    AatopInit::add_aatop_admin_menu();
}

register_activation_hook(__FILE__, 'activate_aatop');
add_action('admin_menu', 'aatop_admin_menu');
register_deactivation_hook(__FILE__, 'deactivate_aatop');
register_uninstall_hook(__FILE__, 'uninstall_aatop');
