<?php

/**
 * Class: Module
 * Name: Section Animated Gradient
 * Slug: premium-gradient
 */

namespace PremiumAddonsPro\Modules\PremiumSectionGradient;

use Elementor\Repeater;
use Elementor\Controls_Manager;

use PremiumAddons\Admin\Includes\Admin_Helper;
use PremiumAddons\Includes\Helper_Functions;
use PremiumAddonsPro\Base\Module_Base;

if( ! defined( 'ABSPATH' ) ) exit;

class Module extends Module_Base {
    
    public function __construct() {
        
        parent::__construct();
        
        $modules = Admin_Helper::get_enabled_elements();
        
        //Checks if Section Gradient is enabled
        $gradient = $modules['premium-gradient'];

        if( ! $gradient ) {
            return;
        }
            
        //Enqueue the required JS file
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        
        //Creates Premium Animated Gradient tab at the end of section layout tab
        add_action( 'elementor/element/section/section_layout/after_section_end',array( $this,'register_controls'), 10 );
        
        //insert data before section rendering
        add_action( 'elementor/frontend/section/before_render', array( $this,'before_render') );

        
    }
    
    /**
	 * Enqueue scripts.
	 *
	 * Registers required dependencies for the extension and enqueues them.
	 *
	 * @since 1.6.5
	 * @access public
	 */
	public function enqueue_scripts() {
        
        if ( ( true === \Elementor\Plugin::$instance->db->is_built_with_elementor( get_the_ID() ) ) || ( function_exists( 'elementor_location_exits' ) && ( elementor_location_exits( 'archive', true ) || elementor_location_exits( 'single', true ) ) ) ) {
            wp_add_inline_script(
                'elementor-frontend',
                'window.scopes_array = {};
                window.backend = 0;
                jQuery( window ).on( "elementor/frontend/init", function() {
                    elementorFrontend.hooks.addAction( "frontend/element_ready/section", function( $scope, $ ){
                        if ( "undefined" == typeof $scope ) {
                                return;
                        }
                        if ( $scope.hasClass( "premium-gradient-yes" ) ) {
                            var id = $scope.data("id");
                            window.scopes_array[ id ] = $scope;
                        }
                        if(elementorFrontend.isEditMode()){		
                            var url = papro_addons.gradient_url;
                            jQuery.cachedAssets = function( url, options ) {
                                // Allow user to set any option except for dataType, cache, and url.
                                options = jQuery.extend( options || {}, {
                                    dataType: "script",
                                    cache: true,
                                    url: url
                                });
                                // Return the jqXHR object so we can chain callbacks.
                                return jQuery.ajax( options );
                            };
                            jQuery.cachedAssets( url );
                            window.backend = 1;
                        }
                    });
                });
                jQuery(document).ready(function(){
                    if ( jQuery.find( ".premium-gradient-yes" ).length < 1 ) {
                        return;
                    }
                    
                    var url = papro_addons.gradient_url;
                    
                    jQuery.cachedAssets = function( url, options ) {
                        // Allow user to set any option except for dataType, cache, and url.
                        options = jQuery.extend( options || {}, {
                            dataType: "script",
                            cache: true,
                            url: url
                        });
                        
                        // Return the jqXHR object so we can chain callbacks.
                        return jQuery.ajax( options );
                    };
                    jQuery.cachedAssets( url );
                });	'
            );
        }
	}
    
    /**
	 * Register Animated Gradient controls.
	 *
	 * @since 1.0.0
	 * @access public
	 * @param object $element for current element.
	 */
    public function register_controls( $element ) {
        
        $element->start_controls_section('section_premium_gradient',
            [
                'label'         => sprintf( '<i class="pa-extension-icon pa-dash-icon"></i> %s', __('Animated Gradient', 'premium-addons-pro') ),
                'tab'           => Controls_Manager::TAB_LAYOUT
            ]
        );
        
        $element->add_control('premium_gradient_update',
            [
               'label'          => '<div class="elementor-update-preview" style="background-color: #fff;"><div class="elementor-update-preview-title">Update changes to page</div><div class="elementor-update-preview-button-wrapper"><button class="elementor-update-preview-button elementor-button elementor-button-success">Apply</button></div></div>',
                'type'          => Controls_Manager::RAW_HTML
            ]
        );
        
        $element->add_control('premium_gradient_switcher',
            [
				'label'         => __( 'Enable Animated Gradient', 'premium-addons-pro' ),
				'type'          => Controls_Manager::SWITCHER,
                'return_value'  => 'yes',
                'prefix_class'  => 'premium-gradient-',
			]
		);
		
        $repeater = new Repeater();
		
        $element->add_control('premium_gradient_notice',
            [
                'raw'           => __( 'NOTICE: Please remove Elementor\'s background image/gradient for this section', 'premium-addons-pro' ),
                'type'          => Controls_Manager::RAW_HTML,
            ]
        );
        
        $repeater->add_control('premium_gradient_colors',
			[
				'label'         => __( 'Select Color', 'premium-addons-pro' ),
				'type'          => Controls_Manager::COLOR,
			]
		);
        
        $element->add_control('premium_gradient_colors_repeater',
            [
                'type'          => Controls_Manager::REPEATER,
                'fields'        => $repeater->get_controls(),
                'title_field'   => '{{{ premium_gradient_colors }}}'
            ]
		);
		
		$element->add_control('premium_gradient_speed',
            [
                'label'         => __('Animation Speed (sec)', 'premium-addons-pro'),
                'type'          => Controls_Manager::NUMBER,
                'min'			=> 1,
                'selectors'     => [
                    '{{WRAPPER}}.premium-gradient-yes' => 'animation-duration: {{VALUE}}s;'
                ]
            ]
        );

        $element->add_control('premium_gradient_angle', 
			[
				'label'         => __( 'Gradient Angle (degrees)', 'premium-addons-pro' ),
				'type'          => Controls_Manager::NUMBER,
                'default'       => -45,
                'min'           => -180,
                'max'           => 180,
			]
		);
        
        $element->end_controls_section();
        
    }
    
    public function before_render( $element ) {
        
		$data 		= $element->get_data();
        
        $type       = $data['elType'];
        
        $settings   = $element->get_settings_for_display();
        
		if( 'section' === $type && 'yes' === $settings['premium_gradient_switcher'] && isset( $settings['premium_gradient_colors_repeater'] ) ) {
            
            $grad_angle         = ! empty( $settings['premium_gradient_angle'] ) ? $settings['premium_gradient_angle'] : -45;
            
            $colors             = array();
            
            foreach( $settings['premium_gradient_colors_repeater'] as $color ) {
                
                array_push( $colors, $color );
                
            }
            
            $gradient_settings = [
                'angle'     => $grad_angle,
                'colors'    => $colors
            ];
            
            $element->add_render_attribute( '_wrapper','data-gradient', wp_json_encode( $gradient_settings ) );
        }
	}    
}