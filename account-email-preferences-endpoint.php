<?php

/*
Plugin Name: Newsletter - Woocommerce Account Preferences
Author: Anton Roug
Version: 1.0
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.txt
Text Domain: woocommerce
Domain Path: /languages/
*/

//include NEWSLETTER_INCLUDES_DIR

class Newsletter_Account_Endpoint {

	static $endpoint = 'email-subscriptions';
	static $instance;

	/**
	 * @return Newsletter_Account_Endpoint
	 */
	static function instance() {
			if (self::$instance == null) {
					self::$instance = new Newsletter_Account_Endpoint();
			}
			return self::$instance;
	}

	/**
	 * Plugin actions.
	 */
	public function __construct() {
		// Actions used to insert a new endpoint in the WordPress.
		add_action( 'init', array( $this, 'add_endpoints' ), 100);
		add_filter( 'query_vars', array( $this, 'add_query_vars' ), 0 );

		// Change the My Account page title.
		add_filter( 'the_title', array( $this, 'endpoint_title' ) );

		// Insering your new tab/page into the My Account page.
		add_filter( 'woocommerce_account_menu_items', array( $this, 'new_menu_items' ) );
		// add processing from this class
		add_action( 'woocommerce_account_' . self::$endpoint .  '_endpoint', array( $this, 'endpoint_content' ));
		add_action( 'woocommerce_account_dashboard', array($this, 'hook_woocommerce_account_dashboard'));
		add_action( 'woocommerce_before_customer_login_form', array($this, 'hook_woocommerce_account_login'));
	}

	/**
	 * Register new endpoint to use inside My Account page.
	 *
	 * @see https://developer.wordpress.org/reference/functions/add_rewrite_endpoint/
	 */
	public function add_endpoints() {
		add_rewrite_endpoint( self::$endpoint, EP_ROOT | EP_PAGES );
	}

	/**
	 * Add new query var.
	 *
	 * @param array $vars
	 * @return array
	 */
	public function add_query_vars( $vars ) {
		$vars[self::$endpoint] = self::$endpoint;
		return $vars;
	}

	/**
	 * Set endpoint title.
	 *
	 * @param string $title
	 * @return string
	 */
	public function endpoint_title( $title ) {
		global $wp_query;

		$is_endpoint = isset( $wp_query->query_vars[ self::$endpoint ] );

		if ( $is_endpoint && ! is_admin() && is_main_query() && in_the_loop() && is_account_page() ) {
			$title = __('Email Subscriptions', 'woocommerce' );

			remove_filter( 'the_title', array( $this, 'endpoint_title' ) );
		}

		return $title;
	}

	/**
	 * Insert the new endpoint into the My Account menu.
	 *
	 * @param array $items
	 * @return array
	 */
	public function new_menu_items( $items ) {
		// Remove the logout menu item.
		$logout = $items['customer-logout'];
		unset( $items['customer-logout'] );
		// Insert your custom endpoint.
		$items[ self::$endpoint] = __( 'Email Subscriptions', 'woocommerce' );

		// Insert back the logout item.
		$items['customer-logout'] = $logout;

		return $items;
	}

	/**
	 * Endpoint HTML content.
	 */
	public function endpoint_content() {
		include_once( 'account-newsletter-logged-in.php' );
	}

	public function hook_woocommerce_account_dashboard() {
		// example TNP profile url:account/?nm=profile&nk=1-fa4290063e&nek=1-36064d5113
		if (!isset($_GET['nm'])) return;

		if (isset($_GET['nek'])) {
		 	$nek = '&nek=' . $_GET['nek'];
	 	}
		else {
		 	$nek = '';
		}
		wp_redirect(site_url('/account/' . self::$endpoint .'?nm='. $_GET['nm'] .'&nk='. $_GET['nk'] . $nek));
		exit();
	}

	public function hook_woocommerce_account_login() {
		if (!isset($_GET['nm'])) return;
		switch ($_GET['nm']) {
			case 'unsubscription':
			case 'unsubscribed':
			case 'unsubscribe':
				include_once( 'account-newsletter-logged-out-cancel.php' );
				break;
			case 'reactivated':
			case 'confirmed':
			case 'confirmation':
			case 'profile':
				include_once( 'account-newsletter-logged-out-profile.php' );
				break;
			default:
				printf(__FILE__,__LINE__," Unrecognized nm %s\n", $_GET['nm']);
		}
	}

	/**
	 * Plugin install action.
	 * Flush rewrite rules to make our custom endpoint available.
	 */
	public static function install() {
		flush_rewrite_rules();
	}
}

Newsletter_Account_Endpoint::instance();

register_activation_hook( __FILE__, array( 'Newsletter_Account_Endpoint', 'install' ) );
