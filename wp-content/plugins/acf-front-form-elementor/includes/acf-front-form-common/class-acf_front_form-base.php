<?php

/**
 * The plugin bootstrap file
 *
 * @link              https://mouradarifi@bitbucket.org/armediacg/acf-front-form.git
 * @since             1.0.0
 * @package           ACF_FRONT_FORM
 *
 * @wordpress-plugin
 * Plugin Name:       ACF Front Form
 * Plugin URI:        https://mouradarifi@bitbucket.org/armediacg/acf-front-form.git
 * Description:       Use Advanced Custom Fields Front Form.
 * Version:           1.3.0
 * Author:            Mourad Arifi
 * Author URI:        https://about.me/mouradarifi
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       acf-front-form
 * Domain Path:       /lang
 */

/**
 * The shortcode plugin class.
 *
 * This is used to define shortcode.
 *
 * @since      1.3.0
 * @package    Acf_front_form
 * @subpackage Acf_front_form/includes
 * @author     Mourad Arifi <arifi.armedia@gmail.com>
 */

/**
 * Plugin base name.
 */
if ( ! defined( 'ACF_FRONT_FORM_NAME' ) ) define( 'ACF_FRONT_FORM_NAME', 'acf_front_form' );

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
if ( ! defined( 'ACF_FRONT_FORM_VERSION' ) ) define( 'ACF_FRONT_FORM_VERSION', '1.3.0' );

/**
 * Enable ACF 5 early access
 * Requires at least version ACF 4.4.12 to work
 */
if ( ! defined( 'ACF_EARLY_ACCESS' ) ) define( 'ACF_EARLY_ACCESS', '5' );

/**
 * Plugin debug
 */
if ( ! defined( 'ACF_FF_DEBUG' ) ) define( 'ACF_FF_DEBUG', false );

/**
 * Main base class
 */
if ( ! class_exists( 'Acf_Front_Form_Base' )) :
class Acf_Front_Form_Base {

    /**
	 * The ID of this plugin.
	 *
	 * @since    1.3.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.3.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
    private $version;

    /**
     * Array of shortcod classes
     *
     * @var array
     */
    private $shortcode_classes;

    /**
     * Plugin settings
     * 
     * 'basename'       => plugin_basename( __FILE__ ),
     * 'plugin_file'	=> $this->plugin_name . '.php' ,
     * 'display'		=> __('ACF Front Form Elementor', $this->plugin_name),
     * 'display_settings'	=> __('ACF Front Form Elementor Settings', $this->plugin_name),
     * 'doc_uri'        => 'http://acf-front-form-elementor-docs.readthedocs.io/en/',
     * 
     * @var array
     */
    private $admin_settings;

    /**
	 * Initialize the class and set its properties.
	 *
	 * @since   1.3.0
	 * @param   string      $plugin_name    The name of the plugin.
	 * @param   string      $version        The version of this plugin.
     * @param   array       $admin_settings The settings of main plugin.
	 */
	public function __construct( $plugin_name, $version, $admin_settings = null ) {

		$this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->shortcode_classes = [];
        $this->admin_settings = [];

        if ( $admin_settings != null ){

            $this->admin_settings = $admin_settings;

        }else{

            $this->admin_settings = [
                'basename'      => plugin_basename( __FILE__ ),
                'plugin_file'	=> $this->plugin_name . '.php' ,
                'display'		=> 'ACF Front Form',
                'display_settings'	=> 'ACF Front Form Settings',
                'doc_uri'	=> 'https://acf-front-form-elementor-docs.readthedocs.io/en/latest/src/add-form.html#using-shortcode',
            ];

        }
    }
    /**
     * Singleton
     * 
     * @since 1.3.0
     */
    public static function Inst( $plugin_name, $version, $admin_settings = null ){

        static $inst = null;
        if ( null === $inst ){
            $inst = new Acf_Front_Form_Base( $plugin_name, $version, $admin_settings );
        }
        return $inst;
    }

    public function Init(){

        $this->includes();

        $this->shortcode_classes[] = [
            'class' => \Acf_Front_Form_Shortcode::Inst(),
            'function'  => 'acf_front_form_shortcode'
        ];

        //
        $this->define_shortcodes();
        //
        $this->define_admin_hooks();
        //
        $this->define_public_hooks();
        //
        $this->add_actions();
        //
        $this->add_filters();

    }

    /**
     * Include Widgets Files
     *
     * @since 1.0.0
     * @access private
     */
    private function includes()
    {
        /**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
        require_once __DIR__ . '/admin/class-acf_front_form-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once __DIR__ . '/public/class-acf_front_form-public.php';

        /**
		 * The class responsible for defining all shortcodes
		 */
        require_once __DIR__ . '/includes/class-acf_front_form-shortcode.php';
        
        /**
		 * The class responsible for defining all filter
		 */
		require_once __DIR__ . '/includes/class-acf_front_form-filters.php';

    }
    /**
	 * Register shortcodes
	 *
	 * @return void
	 */
	protected function define_shortcodes(){
        
        foreach ($this->shortcode_classes as $value) {

            foreach ( $value['class']->get_bartags() as $key => $fn) {
                add_shortcode( $key, [ $value['class'], $fn ] );
            }
        }
    }

    /**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 */
	protected function define_admin_hooks() {

		$plugin_admin = new \Acf_Front_Form_Admin( $this->plugin_name, $this->version, $this->admin_settings );

		//
		add_action( 'admin_menu', [ $plugin_admin, 'acf_front_form_menu' ] );
		
		// Add Settings link to the plugin
		$plugin_basename = $this->admin_settings['basename'];
		//
		add_filter( 'plugin_action_links_' . $plugin_basename, [ $plugin_admin, 'add_action_links' ] );
		add_filter( 'plugin_row_meta', [ $plugin_admin, 'add_row_meta' ], 10, 2 );

		// Save/Update plugin options
		add_action('admin_init', [ $plugin_admin, 'options_update'] );

    }

    /**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 */
	protected function define_public_hooks() {

        $plugin_public = new \Acf_Front_Form_Public( $this->plugin_name, $this->version );
        
        $plugin_public->Init();
    
    }

    /**
     * Add plugin actions
     *
     * @return void
     */
    protected function add_actions(){
        
    }
    /**
     * Add plugin filters
     *
     * @return string
     */
    protected function add_filters(){
        
        $filters = \Acf_Front_Form_Filters::Inst( $this->admin_settings );

        $filters->Init();

    }
}

endif;
