<?php
/**
 * Plugin Name: Artisan
 * Plugin URI: https://profiles.wordpress.org/micaore/
 * Description: Artisan
 * Author: Micaore
 * Author URI: https://www.micaore.com/
 * Version: 0.0.1
 * License: GPL2+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.txt
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * ArtisanBlocks class
 *
 * @class ArtisanBlocks The class that holds the entire ArtisanBlocks plugin
 */

class ArtisanBlocks {

    /**
     * Plugin version
     *
     * @var string
     */
    public $version = '0.0.1';

    /**
     * The single instance of the class.
     */
    protected static $_instance = null;

    /**
     * Constructor for the ArtisanBlocks class
     *
     * Sets up all the appropriate hooks and actions
     * within our plugin.
     *
     * @uses is_admin()
     * @uses add_action()
     */
    public function __construct() {
        $this->define_constants();

        register_activation_hook( __FILE__, array( $this, 'activate' ) );

        if ( ! $this->is_gutenberg_active() ) {
        	$this->dependency_error();
        	return;
        }

        add_action( 'plugins_loaded', array( $this, 'init_plugin' ) );
    }

    /**
     * Initializes the ArtisanBlocks() class
     *
     * Checks for an existing ArtisanBlocks() instance
     * and if it doesn't find one, creates it.
     */
    public static function instance() {

        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Define the constants
     *
     * @return void
     */
    public function define_constants() {
        define( 'ARTISANBLOCKS_VERSION', $this->version );
        define( 'ARTISANBLOCKS_ROOT_FILE', __FILE__ );
        define( 'ARTISANBLOCKS_ROOT_PATH', plugin_dir_path( __FILE__ ) );
		define( 'ARTISANBLOCKS_DIR_URL', plugin_dir_url(__FILE__) );
    }

    /**
     * Initialize the plugin
     *
     * @return void
     */
    public function init_plugin() {
        $this->includes();
    }

    /**
     * Check if Gutenberg is active
     *
     * @since 0.0.1
     *
     * @return boolean
     */
    public function is_gutenberg_active() {
    	return function_exists( 'register_block_type' );
    }

    /**
     * Placeholder for activation function
     */
    public function activate() {
    	$installed = get_option( 'artisanblocks_installed' );

    	if ( ! $installed ) {
    	    update_option( 'artisanblocks_installed', time() );
    	}

    	update_option( 'artisanblocks_version', ARTISANBLOCKS_VERSION );
    }

    /**
     * Include the required files
     *
     * @return void
     */
    public function includes() {

    	require_once __DIR__ . '/includes/Blocks.php';

    	new ArtisanBlocks\Blocks();
    }

    /**
     * Admin notice for no EDD or WC
     */
    public function dependency_error() {

        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        echo '<div class="notice notice-error">';
        echo '<p>Artisan Blocks requires Gutenberg plugin installed or WordPress 5.0.</p>';
        echo '</div>';
    }

} // ArtisanBlocks

ArtisanBlocks::instance();
