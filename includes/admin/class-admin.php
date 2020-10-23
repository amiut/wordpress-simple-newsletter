<?php
/**
 * Admin Controller
 *
 * @author Dornaweb
 * @contribute Am!n <dornaweb.com>
 */

namespace DW_NLITE\Admin;

class Admin
{
    public static $newsletter_object = null;

    public static function init()
    {
        add_action( 'admin_menu', [__CLASS__, 'admin_menu'], 9 );
    }

    public static function admin_menu() {
        $hook = add_menu_page(__('Newsletter list', 'dwnlite'), __('Newsletter', 'dwnlite'), 'manage_options', 'dwnlite-newsletter', [__CLASS__,'newsletter_page'], 'dashicons-email', 90);
        add_action( "load-$hook", [__CLASS__, 'screen_option' ] );
    }

    public static function newsletter_page() {
        include DW_NLITE_ABSPATH . '/templates/admin/newsletter.php';
    }

    public static function screen_option() {
        $option = 'per_page';
		$args   = [
			'label'   => 'Customers',
			'default' => 5,
			'option'  => 'customers_per_page'
		];

		add_screen_option( $option, $args );

		self::$newsletter_object = new \DW_NLITE\News_Letter_List();
    }
}
