<?php
/**
 * PDFGEN main class
 *
 * @package DW_NLITE
 * @since   1.0
 */

namespace DW_NLITE;

defined('ABSPATH') || exit;

/**
 * DW_NLITE main class
 */
final class App
{
	/**
	 * Plugin version.
	 *
	 * @var string
	 */
    public $version = '1.0';

    /**
     * Plugin instance.
     *
     * @since 1.0
     * @var null|DW_NLITE\App
     */
    public static $instance = null;

    /**
     * Plugin API.
     *
     * @since 1.0
     * @var WP_PDFGEN\API\API
     */
    public $api = '';

    /**
     * Return the plugin instance.
     *
     * @return DW_NLITE
     */
    public static function instance() {
        if ( ! self::$instance ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * DW_NLITE constructor.
     */
    private function __construct() {
        add_action('init', [$this, 'i18n']);
        add_action('wp_ajax_dw_nlite_user_subscribe', [$this, 'ajax_subscriber_handler']);
        add_action('wp_ajax_nopriv_dw_nlite_user_subscribe', [$this, 'ajax_subscriber_handler']);
        add_action('wp_ajax_dw_nlite_export_data', [$this, 'export_data']);

        $this->define_constants();
        $this->init();
        $this->includes();
    }

    /**
     * Make Translatable
     *
     */
    public function i18n() {
        load_plugin_textdomain( 'dwnlite', false, dirname( plugin_basename( DW_NLITE_FILE ) ) . "/languages" );
    }

    /**
     * Include required files
     *
     */
    public function includes() {
        include DW_NLITE_ABSPATH . 'includes/functions.php';
    }

    /**
     * Define constant if not already set.
     *
	 * @param string      $name  Constant name.
	 * @param string|bool $value Constant value.
	 */
	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
    }

    /**
     * Define constants
     */
    public function define_constants() {
		$this->define('DW_NLITE_ABSPATH', dirname(DW_NLITE_FILE) . '/');
		$this->define('DW_NLITE_PLUGIN_BASENAME', plugin_basename(DW_NLITE_FILE));
		$this->define('DW_NLITE_BOOKING_VERSION', $this->version);
		$this->define('DW_NLITE_PLUGIN_URL', $this->plugin_url());
		$this->define('DW_NLITE_API_TEST_MODE', true);
    }

	/**
	 * Get the plugin url.
	 *
	 * @return string
	 */
	public function plugin_url() {
		return untrailingslashit(plugins_url('/', DW_NLITE_FILE));
    }

    /**
     * Do initial stuff
     */
    public function init() {
        // Install
        register_activation_hook(DW_NLITE_FILE, ['DW_NLITE\\Install', 'install']);

        // Post types
        Post_Types::init();

        Admin\Admin::init();        

        // Add scripts and styles
        add_action('wp_enqueue_scripts', [$this, 'public_dependencies']);
        add_action('admin_enqueue_scripts', [$this, 'admin_dependencies']);
    }

    /**
     * Register scripts and styles for public area
     */
    public function public_dependencies() {

    }

    /**
     * Register scripts and styles for admin area
     */
    public function admin_dependencies() {

    }

    public function ajax_subscriber_handler() {
        header('Content-type: application/json; charset = utf-8');
        
        $email = ! empty($_POST['email']) ? sanitize_text_field($_POST['email']) : false;
        $name = ! empty($_POST['name']) ? sanitize_text_field($_POST['name']) : false;
        $phone_number = ! empty($_POST['phone_number']) ? sanitize_text_field($_POST['phone_number']) : false;

        if (! wp_verify_nonce($_POST['_wpnonce'], 'dwnlite_nonce')) {
            wp_send_json_error([
                'message' => __('Invalid request', 'dwnlite')
            ]);
        }

        if (! $email && ! $name && ! $phone_number) {
            wp_send_json_error([
                'message' => __('Please Fill in newsletter form', 'dwnlite')
            ]);
        }

        $subscriber = new Subscriber();

        if ($email && ! is_email($email)) {
            wp_send_json_error([
                'message' => __('Your email address is invalid', 'dwnlite')
            ]);
            
        } else {
            if ($subscriber->find_by_email($email)->exists()) {
                wp_send_json_error([
                    'message' => __('You have already subscribed to our newsletter', 'dwnlite')
                ]);
            }

        }

        if (! empty($phone_number) && $subsriber->find_by_email($email)->exists()) {
            wp_send_json_error([
                'message' => __('You have already subscribed to our newsletter', 'dwnlite')
            ]);

        }

        $subscriber->set_name($name)->set_email($email)->set_phone_number($phone_number)->save();
        wp_send_json_success([
            'message' => __('You have successfully registered to our newsletter', 'dwnlite')
        ]);

        wp_die();
    }


    public function export_data() {
        if (! current_user_can('manage_options') && ! wp_verify_nonce($_POST['ex_nonce'], 'dwnlite_export_data')) {
            wp_safe_redirect(admin_url('admin.php?page=dwnlite-newsletter'));
        }

        $filename = 'dwnlite-data-' . date('j-n-Y-h-i-s') . ".csv";
        $fields = ! empty($_POST['fields']) ? sanitize_text_field($_POST['fields']) : 'all';

        if ($fields != 'all' && in_array($fields, ['name', 'email', 'phone_number'])) {
            $fields = [$fields];
        }

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Disposition: attachment; filename="'. $filename .'"');

        $f = fopen('php://output', 'w');

        $results = dwnlite_get_subscribed_users($fields);

        fputcsv( $f, array_keys( $results['0'] ) );

        foreach ($results as $line) {
            fputcsv($f, $line, ';');
        }

        fclose($f);
        exit;
    }
}
