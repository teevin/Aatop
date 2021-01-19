<?php
/**
 * @since 1.0.0
 *
 * @package Plugin
 */

class AatopInit
{
    public static function aatop_activate()
    {
        // self::add_aatop_admin_menu();
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $table_session = $wpdb->prefix . 'aatop_session';
        $sql_session = "CREATE TABLE $table_session (
            id int(9) NOT NULL AUTO_INCREMENT,
            created_at timestamp NOT NULL,
            identified varchar(255) NOT NULL,
            email_optin varchar(255),
            email_paused_at varchar(255),
            email_paused_period varchar(255),
            PRIMARY KEY id (id),
            UNIQUE (identified)
        ) $charset_collate;";

        $table_visitors = $wpdb->prefix . 'aatop_visitors';
        $sql_visitor = "CREATE TABLE $table_visitors (
            id int(9) NOT NULL AUTO_INCREMENT,
            session_id varchar(255) NOT NULL,
            type enum('vacature','search') DEFAULT 'search' NOT NULL,
            tags text NOT NULL,
            created_at timestamp NOT NULL,
            PRIMARY KEY id (id),
            UNIQUE KEY tag (session_id)
        ) $charset_collate;";
    
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        if ($wpdb->get_var("SHOW TABLES LIKE '$table_session'") != $table_session) {
            dbDelta($sql_visitor);
        }

        if ($wpdb->get_var("SHOW TABLES LIKE '$sql_session'") != $sql_session) {
            dbDelta($sql_session);
        }
    }

    public static function aatop_deactivate()
    {
        $menu_slug = sanitize_key('aatop-personalisation');
        $menu_slug_posts = sanitize_key('aatop-post');
        $menu_slug_visitors = sanitize_key('aatop-visitors');
        remove_submenu_page($menu_slug_posts, $menu_slug_visitors);
        remove_menu_page($menu_slug);
    }

    public static function aatop_uninstall()
    {
        $table_session = $wpdb->prefix . 'aatop_session';
        $sql_visitor ="DROP TABLE IF EXISTS $sql_visitor";
        $wpdb->query($sql_visitor);
        $table_visitors = $wpdb->prefix . 'aatop_visitors';
        $sql_visitor ="DROP TABLE IF EXISTS $table_visitors";
        $wpdb->query($sql_visitor);
    }
    
    public static function add_aatop_admin_menu()
    {
        if (!current_user_can('manage_options'))  {
            wp_die( __('You do not have sufficient permissions to access this page.') );
        }
        $top_menu = 'Aatop Personalisation';
        $page_title = 'Aatop menu';
        $menu_slug = sanitize_key('aatop-personalisation');
        $function = 'aatop_admin_menu_html';
        $capability = 'manage_options';
        $icon_url = '';
        $position = 1;
        $sub_menu_posts = 'Posts';
        $sub_menu_visitors = 'Visitors';
        $menu_slug_posts = sanitize_key('aatop-post');
        $menu_slug_visitors = sanitize_key('aatop-visitors');
        $function_posts = 'aatop_admin_menu_post_html';
        $function_visitors = 'aatop_admin_menu_visitors_html';
        add_menu_page($page_title, $top_menu, $capability, $menu_slug, $function, $icon_url, $position);
        // add_submenu_page($menu_slug,$page_title,$sub_menu_posts,$capability,$menu_slug_posts,$function_posts);
        // add_submenu_page($menu_slug,$page_title,$sub_menu_visitors,$capability,$menu_slug_visitors,$function_visitors);
    }

}
