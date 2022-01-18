<?php

/**
 * Class: Module
 * Name: Section Particles
 * Slug: premium-particles
 */

namespace PremiumAddonsPro\Modules\PremiumSectionParticles;

use Elementor\Controls_Manager;

use PremiumAddons\Admin\Includes\Admin_Helper;
use PremiumAddons\Includes\Helper_Functions;
use PremiumAddonsPro\Base\Module_Base;

if( ! defined( 'ABSPATH' ) ) exit;

class Module extends Module_Base {
    
    public function __construct() {
        
        parent::__construct();

        $modules = Admin_Helper::get_enabled_elements();
        
        //Checks if Section Particles is enabled
        $particles = $modules['premium-particles'];

        if( ! $particles ) {
            return;
        }
            
        //Enqueue the required JS file
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        
        //Register Controls inside Section Layout tab
        add_action( 'elementor/element/section/section_layout/after_section_end',array( $this,'register_controls' ), 10 );
        
        //insert data before section rendering
        add_action( 'elementor/frontend/section/before_render',array( $this,'before_render' ), 10, 1 );
        
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
                        if ( $scope.hasClass( "premium-particles-yes" ) ) {
                            var id = $scope.data("id");
                            window.scopes_array[ id ] = $scope;
                        }
                        if(elementorFrontend.isEditMode()){		
                            var url = papro_addons.particles_url;
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
                    if ( jQuery.find( ".premium-particles-yes" ).length < 1 ) {
                    
                        return;
                    }
                    var url = papro_addons.particles_url;
                    
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
	 * Register Particles controls.
	 *
	 * @since 1.0.0
	 * @access public
	 * @param object $element for current element.
	 * @param object $section_id for section ID.
	 * @param array  $args for section args.
	 */
    public function register_controls( $element ) {
        
        $element->start_controls_section('section_premium_particles',
            [
                'label'         => sprintf( '<i class="pa-extension-icon pa-dash-icon"></i> %s', __('Particles', 'premium-addons-pro') ),
                'tab'           => Controls_Manager::TAB_LAYOUT
            ]
        );
        
        $element->add_control('premium_particles_switcher',
            [
                'label'         => __( 'Enable Particles', 'premium-addons-pro' ),
                'type'          => Controls_Manager::SWITCHER,
                'return_value'  => 'yes',
                'prefix_class'  => 'premium-particles-',
                'render_type'	=> 'template'
            ]
        );
        
        $element->add_control('premium_particles_zindex',
            [
                'label'         => __( 'Z-index', 'premium-addons-pro' ),
                'type'          => Controls_Manager::NUMBER,
                'default'       => 0
            ]
        );

        $element->add_control('premium_particles_custom_style',
            [
                'label'         => __( 'Custom Style', 'premium-addons-pro' ),
                'type'          => Controls_Manager::CODE,
                'description'   => __( 'Particles has been updated with many new features. You can now generate the JSON config from <a href="https://premiumaddons.com/docs/how-to-use-tsparticles-in-elementor-particles-section-addon/?utm_source=pa-dashboard&utm_medium=pa-editor&utm_campaign=pa-plugin" target="_blank">here</a> or <a href="http://vincentgarreau.com/particles.js/#default" target="_blank">here</a>', 'premium-addons-pro' ),
                'render_type' => 'template',
            ]
        );

        $element->add_control('particles_background_notice',
            [
                'raw'           => __( 'Kindly, be noted that you will need to add a background as particles JSON code doesn\'t include a background color', 'premium-addons-pro' ),
                'type'          => Controls_Manager::RAW_HTML,
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
            ]
        );

        $element->add_control('premium_particles_responsive',
            [
                'label'             => __('Apply Particles On', 'premium-addons-pro'),
                'type'              => Controls_Manager::SELECT2,
                'options'           => [
                    'desktop'   => __('Desktop','premium-addons-pro'),
                    'tablet'    => __('Tablet','premium-addons-pro'),
                    'mobile'    => __('Mobile','premium-addons-pro'),
                ],
                'default'           => [ 'desktop', 'tablet', 'mobile' ],
                'multiple'          => true,
                'label_block'       => true
            ]);
        
        $element->end_controls_section();
        
    }
    
    public function before_render( $element ) {
        
        $data               = $element->get_data();
        
        $type               = $data['elType'];
        
        $settings           = $element->get_settings_for_display();
        
        $zindex             = ! empty( $settings['premium_particles_zindex'] ) ? $settings['premium_particles_zindex'] : 0;
        
        if( 'section' === $type && 'yes' === $settings['premium_particles_switcher'] ) {
            
            if( ! empty( $settings['premium_particles_custom_style'] ) ) {
                
                $particles_settings = [
                    'zindex'    => $zindex,
                    'style'     => $settings['premium_particles_custom_style'],
                    'responsive'=> $settings['premium_particles_responsive']
                ];
                
                $element->add_render_attribute( '_wrapper', [
                    'data-particles-style'   => $particles_settings['style'],
                    'data-particles-zindex'  => $particles_settings['zindex'],
                    'data-particles-devices' => $particles_settings['responsive']
                ]);
                
            }
        }
    }
}