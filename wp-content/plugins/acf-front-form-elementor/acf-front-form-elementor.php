<?php
namespace ACF_FF_Elementor;

/**
 * The plugin bootstrap file
 *
 * @link              https://mouradarifi@bitbucket.org/armediacg/acf-front-form.git
 * @since             1.0.0
 * @package           ACF_FRONT_FORM_Elementor
 *
 * @wordpress-plugin
 * Plugin Name:       ACF Front Form for Elementor
 * Plugin URI:        https://mouradarifi@bitbucket.org/armediacg/acf-front-form.git
 * Description:       Use Advanced Custom Fields Front Form with Elementor.
 * Version:           2.0.0
 * Author:            Mourad Arifi
 * Author URI:        https://about.me/mouradarifi
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       acf-front-form-elementor
 * Domain Path:       /lang
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

 /**
 * Enable ACF 5 early access
 * Requires at least version ACF 4.4.12 to work
 */
if ( ! defined( 'ACF_EARLY_ACCESS' ) ) define( 'ACF_EARLY_ACCESS', '5' );

/**
 * Where to hook acf_head function
 */
if ( ! defined( 'ACF_HEAD_HOOK' ) ) define( 'ACF_HEAD_HOOK', 'init' );



/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
//define( 'ACF_ELEMENTS', '1.0.0' );

if ( ! class_exists( 'ACF_Front_Form_Elementor' )) :

class ACF_Front_Form_Elementor{

    /**
     * Plugin name
     * 
     * @var string
     */
    protected $plugin_name;

    /**
     * Version
     * 
     * @var string
     */
    protected $version;

    /**
     * Settings
     * 
     * @var array
     */
    var $settings;

    /**
     * Constructor
     */
    function __construct() {
        
        $this->plugin_name  = 'acf-front-form-elementor';
        $this->version      = '2.0.0';
        $this->settings     = array();

    }
    
    public function Init(){
        
        $this->settings = array(

            'file'				=> __FILE__,
			'basename'			=> plugin_basename( __FILE__ ),
			'path'				=> plugin_dir_path( __FILE__ ),
			'url'				=> plugin_dir_url( __FILE__ ),
        );
        
        // constants
		$this->define( 'ACF_FF_EL', true );
		$this->define( 'ACF_FF_EL_VERSION', $this->version );
        $this->define( 'ACF_FF_EL_PATH', $this->settings['path'] );
        //
        
        //
        $this->includes();
        //
        $this->set_locale();
        //
        $this->init_front_form_shortcodes();
        //
        $this->add_actions();
    }

    /**
	 * Register shortcodes and Visual Composer integration used in this plugin
	 *
	 * @return void
	 */
	protected function init_front_form_shortcodes(){
        
        $admin_settings = [
            'basename'      => plugin_basename( __FILE__ ),
			'plugin_file'	=> $this->plugin_name . '.php' ,
			'display'		=> __('ACF Front Form Elementor', $this->plugin_name),
			'display_settings'	=> __('ACF Front Form Elementor Settings', $this->plugin_name),
            'doc_uri'	=> 'http://acf-front-form-elementor-docs.readthedocs.io/en/',
            //'overwrite_edit_link' => false,
        ];
        
		$shortcodes = \Acf_Front_Form_Base::Inst( $this->plugin_name, $this->version, $admin_settings );
		//
		$shortcodes->Init();
    }
    
    /**
     * Add plugin actions
     *
     * @return void
     */
    private function add_actions()
    {
        // Add New Elementor Categories
        add_action( 'elementor/elements/categories_registered', array( $this, 'add_elementor_category' ) );

        // Register Widget Scripts
        //add_action( 'elementor/frontend/after_register_scripts', array( $this, 'register_widget_scripts' ) );

        // Register Widget Styles
        //add_action( 'elementor/frontend/after_enqueue_styles', array( $this, 'register_widget_styles' ) );

        // Register New Widgets
        add_action( 'elementor/widgets/widgets_registered', array( $this, 'on_widgets_registered' ) );
        //
        // Register custom styles
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
    }
    public function enqueue_styles(){
        
        wp_enqueue_style( $this->plugin_name . '-acf-ff', plugin_dir_url( __FILE__ ) . 'public/css/acf-front-form-elementor.css', [], $this->version, 'all' );
    }
    /**
     * Adds the category ACF Front Form for all plugin widgets
     */
    public function add_elementor_category()
    {
        \Elementor\Plugin::instance()->elements_manager->add_category( 
            'acf-front-form-elementor',
            [
                'title' => __( 'ACF Front Form', 'acf-front-form-elementor' ),
            ], 1 );
    }

    /**
     * Register ACF Element Form Widget
     * 
     * @since 1.0.0
     * @return void
     */
    public function on_widgets_registered()
    {
        /**
         * Site Elements
         */
        require_once __DIR__ . '/includes/widgets/acf-element-form.php';

        $this->register_widgets();
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
		 * The class responsible for acf front form shortcodes
		 */
        require_once __DIR__ . '/includes/acf-front-form-common/class-acf_front_form-base.php';
        
        /**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once __DIR__ . '/includes/class-acf-front-form-elementor-i18n.php';
    }
    /**
	 * Define the locale for this plugin for internationalization.
	 *
	 * @since    1.0.0
	 * @access   protected
	 */
	protected function set_locale() {

		$plugin_i18n = new \ACF_FRONT_FORM_Elementor_i18n();

		add_action( 'plugins_loaded', [ $plugin_i18n, 'load_plugin_textdomain' ] );

	}
    
    protected function register_widgets(){
        // Site Elements
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \ACF_FF_Elementor\Widgets\ACF_Element_Form() );
    }



    function define( $name, $value = true ) {
		
		if( !defined($name) ) {
			define( $name, $value );
		}
		
	}
}

function acf_ff_elementor() {
	
	// globals
	global $acf_ff_el;
	
	// Init
	if( !isset($acf_ff_el) ) {
		$acf_ff_el = new ACF_Front_Form_Elementor();
		$acf_ff_el->Init();
	}
	
	// return
	return $acf_ff_el;
	
}


// Init
acf_ff_elementor();

endif;
