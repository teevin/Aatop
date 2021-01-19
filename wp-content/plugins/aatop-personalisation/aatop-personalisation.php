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
function aatop_cookies()
{
   // $_GET[‘fwp_zoeken’]
    $post_tags = [];
    $search_tags = [];
    $data = [];
    $post_type = get_post_type();
    if (!isset($_COOKIE['id'])) {
        $data["identified"] = hash('md5', uniqid(time()));
        setCookie('id', $data["identified"]);
    } else {
        $data["identified"] = $_COOKIE['id'];
    }
    require_once plugin_dir_path(__FILE__) . 'classes/tracking.php';
    $tracking  = new AatopTracking($data["identified"]);
    if (isset($_GET['s'])) {
        $search_tags[] = $_GET['s'];
        if(count($search_tags) > 0) {
            $data["type"]  = "search";
            $data["tags"]  = $search_tags;
            $tracking::saveData($data);
        }
    }

    if ($post_type == 'vacature') {
        $post = get_post();
        $tags = get_the_terms($post, 'aatopvacancy_autocomplete');
        if (is_array($tags)) {
            foreach ($tags as $tag) {
                $post_tags[] = $tag->name;
            }
        }
        if(count($post_tags) > 0 && !isset($_GET['s'])) {
            $data["type"]  = "vacature";
            $data["tags"]  = $post_tags;
            $tracking::saveData($data);
        }
    }
    //2d631a2905d13a19172575a579f7fa23
    $hits = $tracking::autocomplete_tags($_COOKIE['id']);//$_COOKIE['id']
    // var_dump($hits);
}

/**Test functions */
function register_vacature()
{
    register_post_type(
        'vacature',
        [
        'labels'              => [
        'name'               => 'Vacatures',
        'singular_name'      => 'Vacature',
        'all_items'          => 'Alle Vacatures',
        'add_new'            => 'Nieuwe Vacature',
        'add_new_item'       => 'Nieuwe Vacature',
        'edit'               => 'Vacature bewerken',
        'edit_item'          => 'Vacature bewerken',
        'new_item'           => 'Nieuwe Vacature',
        'view'               => 'Vacature bekijken',
        'view_item'          => 'Vacatures bezoeken',
        'search_items'       => 'Zoek Vacature',
        'not_found'          => 'Geen Vacature gevonden',
        'not_found_in_trash' => 'Geen Vacature gevonden in de prullenbak',
        ],
        'public'              => true,
        'show_in_rest'  => true,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'capability_type'     => 'post',
        'hierarchical'        => true,
        'exclude_from_search' => false,
        'query_var'           => true,
        'rewrite'             => true,
        'enable_search'  => true,
        'menu_position'       => 41,
        'menu_icon'           => 'dashicons-format-aside',
        'supports'            => [ 'title', 'editor' ],
    
        ]
    );
}

function create_aatopvacancy_autocomplete()
{
    $labels = [
    'name' => _x('Autocompletes', 'taxonomy general name'),
    'singular_name' => _x('Autocomplete', 'taxonomy singular name'),
    'search_items' =>  __('Search Autocompletes'),
    'all_items' => __('All Autocompletes'),
    'parent_item' => __('Parent Autocomplete'),
    'parent_item_colon' => __('Parent Autocomplete:'),
    'edit_item' => __('Edit Autocomplete'),
    'update_item' => __('Update Autocomplete'),
    'add_new_item' => __('Add New Autocomplete'),
    'new_item_name' => __('New Autocomplete Name'),
    'menu_name' => __('Autocompletes'),
    ];
    register_taxonomy(
        'aatopvacancy_autocomplete',
        ['vacature'],
        [
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'show_in_rest' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'enable_search' => true,
        'rewrite' => [ 'slug' => 'autocomplete' ],
        ]
    );
}
add_action('init', 'register_vacature');
add_action('init', 'create_aatopvacancy_autocomplete');
/**
 * End Test
 */
add_action('wp_head', 'aatop_cookies', 1);
register_activation_hook(__FILE__, 'activate_aatop');
add_action('admin_menu', 'aatop_admin_menu');
register_deactivation_hook(__FILE__, 'deactivate_aatop');
register_uninstall_hook(__FILE__, 'uninstall_aatop');
