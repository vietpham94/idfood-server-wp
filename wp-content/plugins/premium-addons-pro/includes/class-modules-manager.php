<?php

/**
 * PAPRO Manager.
 */
namespace PremiumAddonsPro\Includes;

use PremiumAddonsPro\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

final class Manager {

	/**
	 * @var Module_Base[]
	 */
	private $modules = [];
    
    /**
	 * Require Files.
	 *
	 * @since 1.6.1
     * @access public
     * 
     * @return void
	 */
    public function require_files() {
        require PREMIUM_PRO_ADDONS_PATH . 'base/module-base.php';
    }
    
    /**
	 * Register Modules.
	 *
	 * @since 1.6.1
     * @access public
     * 
     * @return void
	 */
    public function register_modules() {
        
        $modules = [
			'premium-section-parallax',
            'premium-section-particles',
            'premium-section-gradient',
			'premium-section-kenburns',
			'premium-section-lottie'
		];

		foreach ( $modules as $module_name ) {
			$class_name = str_replace( '-', ' ', $module_name );

			$class_name = str_replace( ' ', '', ucwords( $class_name ) );
			
			$class_name = 'PremiumAddonsPro\\Modules\\' . $class_name . '\Module';
            
			/** @var Module_Base $class_name */
            
			if ( $class_name::is_active() ) {                
				$this->modules[ $module_name ] = $class_name::instance();
			}
		}
        
    }

	public function __construct() {
        
        $this->require_files();
        $this->register_modules();
        
	}

}