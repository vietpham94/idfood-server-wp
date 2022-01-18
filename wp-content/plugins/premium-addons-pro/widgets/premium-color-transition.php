<?php

/**
 * Class: Premium_Color_Transition
 * Name: Color Transition
 * Slug: premium-color-transition
 */
namespace PremiumAddonsPro\Widgets;

// Elementor Classes.
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;

// PremiumAddons Classes.
use PremiumAddons\Includes\Helper_Functions;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

/**
 * Class Premium_Color_Transition
 */
class Premium_Color_Transition extends Widget_Base {

    public function get_name() {
        return 'premium-color-transition';
    }

    public function get_title() {
        return sprintf( '%1$s %2$s', Helper_Functions::get_prefix(), __( 'Background Transition', 'premium-addons-pro' ) );
	}
    
    public function get_icon() {
        return 'pa-pro-color-transition';
    }

    public function get_script_depends() {
        return [
            'elementor-waypoints',
            'premium-pro-js'
        ];
    }

    public function get_categories() {
        return [ 'premium-elements' ];
    }
    
    public function is_reload_preview_required() {
        return true;
    }
    
    public function get_keywords() {
        return [ 'color', 'scroll', 'background' ];
    }
    
    public function get_custom_help_url() {
		return 'https://premiumaddons.com/support/';
	}

    /**
	 * Register Background Transition controls.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
    protected function _register_controls() {

        $this->start_controls_section('sections',
            [
                'label'             => __('Content', 'premium-addons-pro'),
            ]
        );

        $id_repeater = new REPEATER();
        
        $id_repeater->add_control('section_id',
            [
                'label'             => __( 'CSS ID', 'premium-addons-pro' ),
                'type'              => Controls_Manager::TEXT,
                'dynamic'           => [ 'active' => true ],
            ]
        );

        $id_repeater->start_controls_tabs('colors');
        
        $id_repeater->start_controls_tab('scroll_down',
            [
                'label'             => sprintf('<i class="fa fa-arrow-down premium-editor-icon"></i>%s', __('Scroll Down', 'premium-addons-pro')),
            ]
        );
        
        $id_repeater->add_control('scroll_down_type', 
            [
                'label'         => __('Type', 'premium-addons-pro'),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'color' => __('Color', 'premium-addons-pro'),
                    'image' => __('Image', 'premium-addons-pro')
                ],
                'default'       => 'color',
            ]
        );
        
        $id_repeater->add_control('scroll_down_doc',
			[
				'raw'             => __( 'This color is applied while scrolling down', 'premium-addons-pro' ),
                'type'            => Controls_Manager::RAW_HTML,
                'content_classes' => 'editor-pa-doc',
			]
		);

        $id_repeater->add_control('down_color',
            [
                'label'             => __( 'Select Color', 'premium-addons-pro' ),
                'type'              => Controls_Manager::COLOR,
                'redner_type'       => 'template',
                'selectors'         => [
                    '#premium-color-transition-{{ID}} {{CURRENT_ITEM}}[data-direction="down"], #premium-color-transition-{{ID}} {{CURRENT_ITEM}}[data-direction="up"]' => 'background: {{VALUE}}'
                ],
                'condition'         => [
                    'scroll_down_type'  => 'color'
                ]
            ]
        );
        
        $id_repeater->add_control('down_image',
            [
                'label'         => __( 'Image', 'premium-addons-pro' ),
                'type'          => Controls_Manager::MEDIA,
                'dynamic'       => [ 'active' => true ],
                'label_block'   => true,
                'selectors'         => [
                    '#premium-color-transition-{{ID}} {{CURRENT_ITEM}}[data-direction="down"], #premium-color-transition-{{ID}} {{CURRENT_ITEM}}[data-direction="up"]' => 'background: transparent; background-image: url("{{URL}}")'
                ],
                'condition'         => [
                    'scroll_down_type'  => 'image'
                ]
            ]
        );
        
        $id_repeater->add_responsive_control('down_image_size',
            [
                'label'     => __('Size', 'premium-addons-pro'),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    'auto' => __( 'Auto', 'premium-addons-pro' ),
                    'contain' => __( 'Contain', 'premium-addons-pro' ),
                    'cover' => __( 'Cover', 'premium-addons-pro' ),
                    'custom' => __( 'Custom', 'premium-addons-pro' ),
                ],
                'default'   => 'auto',
                'label_block'=> true,
                'condition'     => [
                    'scroll_down_type'    => 'image',
                ],
                'selectors'     => [
                     '#premium-color-transition-{{ID}} {{CURRENT_ITEM}}[data-direction="down"], #premium-color-transition-{{ID}} {{CURRENT_ITEM}}[data-direction="up"]' => 'background-size: {{VALUE}}',
                ],
            ]
        );
        
        $id_repeater->add_responsive_control('down_image_size_custom',
            [
                'label'         => __( 'Width', 'premium-addons-pro' ),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => [ 'px', 'em', '%', 'vw' ],
                'range'         => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'vw' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default'       => [
                    'size' => 100,
                    'unit' => '%',
                ],
                'condition'     => [
                    'scroll_down_type'    => 'image',
                    'down_image_size'    => 'custom'
                ],
                'selectors'     => [
                    '#premium-color-transition-{{ID}} {{CURRENT_ITEM}}[data-direction="down"], #premium-color-transition-{{ID}} {{CURRENT_ITEM}}[data-direction="up"]' => 'background-size: {{SIZE}}{{UNIT}} auto',

    			]
            ]
        );
        

        $id_repeater->add_responsive_control('down_image_position',
            [
                'label'     => __('Position', 'premium-addons-pro'),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    'center center' => __( 'Center Center','premium-addons-pro' ),
                    'center left' => __( 'Center Left', 'premium-addons-pro' ),
                    'center right' => __( 'Center Right', 'premium-addons-pro' ),
                    'top center' => __( 'Top Center', 'premium-addons-pro' ),
                    'top left' => __( 'Top Left', 'premium-addons-pro' ),
                    'top right' => __( 'Top Right', 'premium-addons-pro' ),
                    'bottom center' => __( 'Bottom Center', 'premium-addons-pro' ),
                    'bottom left' => __( 'Bottom Left', 'premium-addons-pro' ),
                    'bottom right' => __( 'Bottom Right', 'premium-addons-pro' ),
                ],
                'default'   => 'center center',
                'label_block'=> true,
                'condition'     => [
                    'scroll_down_type'    => 'image'
                ],
                'selectors'     => [
                     '#premium-color-transition-{{ID}} {{CURRENT_ITEM}}[data-direction="down"], #premium-color-transition-{{ID}} {{CURRENT_ITEM}}[data-direction="up"]' => 'background-position: {{VALUE}}',
                ],
            ]
        );
        
        $id_repeater->add_responsive_control('down_image_repeat',
            [
                'label'     => __('Repeat', 'premium-addons-pro'),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    'repeat'    => __( 'Repeat', 'premium-addons-pro' ),
                    'no-repeat' => __( 'No-repeat', 'premium-addons-pro' ),
                    'repeat-x'  => __( 'Repeat-x', 'premium-addons-pro' ),
                    'repeat-y'  => __( 'Repeat-y', 'premium-addons-pro' ),
                ],
                'default'   => 'repeat',
                'label_block'=> true,
                'condition'     => [
                    'scroll_down_type'    => 'image'
                ],
                'selectors'     => [
                     '#premium-color-transition-{{ID}} {{CURRENT_ITEM}}[data-direction="down"], #premium-color-transition-{{ID}} {{CURRENT_ITEM}}[data-direction="up"]' => 'background-repeat: {{VALUE}}',
                ],
            ]
        );
        
        $id_repeater->end_controls_tab();

        $id_repeater->start_controls_tab('scroll_up',
            [
                'label'             => sprintf('<i class="fa fa-arrow-up premium-editor-icon"></i>%s', __('Scroll Up', 'premium-addons-pro')),
            ]
        );
        
        $id_repeater->add_control('scroll_up_doc',
			[
				'raw'             => __( 'This color is applied while scrolling up', 'premium-addons-pro' ),
                'type'            => Controls_Manager::RAW_HTML,
                'content_classes' => 'editor-pa-doc',
			]
		);
        
        $id_repeater->add_control('scroll_up_type', 
            [
                'label'         => __('Type', 'premium-addons-pro'),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'color' => __('Color', 'premium-addons-pro'),
                    'image' => __('Image', 'premium-addons-pro')
                ],
                'default'       => 'color',
        	]
        );

        $id_repeater->add_control('up_color',
            [
                'label'             => __( 'Select Color', 'premium-addons-pro' ),
                'type'              => Controls_Manager::COLOR,
                'selectors'         => [
                    '#premium-color-transition-{{ID}} {{CURRENT_ITEM}}[data-direction="up"]' => 'background: {{VALUE}}'
                ],
                'condition'         => [
                    'scroll_up_type'  => 'color'
                ]
            ]
        );
        
        $id_repeater->add_control('up_image',
            [
                'label'         => __( 'Image', 'premium-addons-pro' ),
                'type'          => Controls_Manager::MEDIA,
                'dynamic'       => [ 'active' => true ],
                'label_block'   => true,
                'selectors'     => [
                    '#premium-color-transition-{{ID}} {{CURRENT_ITEM}}[data-direction="up"]' => 'background: transparent; background-image: url("{{URL}}")'
                ],
                'condition'         => [
                    'scroll_up_type'  => 'image'
                ]
            ]
        );
        
        $id_repeater->add_responsive_control('up_image_size',
            [
                'label'     => __('Size', 'premium-addons-pro'),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    'auto' => __( 'Auto', 'premium-addons-pro' ),
                    'contain' => __( 'Contain', 'premium-addons-pro' ),
                    'cover' => __( 'Cover', 'premium-addons-pro' ),
                    'custom' => __( 'Custom', 'premium-addons-pro' ),
                ],
                'default'   => 'auto',
                'label_block'=> true,
                'selectors'     => [
                     '#premium-color-transition-{{ID}} {{CURRENT_ITEM}}[data-direction="up"]' => 'background-size: {{VALUE}}',
                ],
                'condition'         => [
                    'scroll_up_type'  => 'image'
                ]
            ]
        );
        
        $id_repeater->add_responsive_control('up_image_size_custom',
            [
                'label'         => __( 'Width', 'premium-addons-pro' ),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => [ 'px', 'em', '%', 'vw' ],
                'range'         => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'vw' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default'       => [
                    'size' => 100,
                    'unit' => '%',
                ],
                'condition'     => [
                    'scroll_up_type'    => 'image',
                    'up_image_size'    => 'custom'
                ],
                'selectors'     => [
                    '#premium-color-transition-{{ID}} {{CURRENT_ITEM}}[data-direction="up"]' => 'background-size: {{SIZE}}{{UNIT}} auto',

    			]
            ]
        );
        

        $id_repeater->add_responsive_control('up_image_position',
            [
                'label'     => __('Position', 'premium-addons-pro'),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    'center center' => __( 'Center Center','premium-addons-pro' ),
                    'center left' => __( 'Center Left', 'premium-addons-pro' ),
                    'center right' => __( 'Center Right', 'premium-addons-pro' ),
                    'top center' => __( 'Top Center', 'premium-addons-pro' ),
                    'top left' => __( 'Top Left', 'premium-addons-pro' ),
                    'top right' => __( 'Top Right', 'premium-addons-pro' ),
                    'bottom center' => __( 'Bottom Center', 'premium-addons-pro' ),
                    'bottom left' => __( 'Bottom Left', 'premium-addons-pro' ),
                    'bottom right' => __( 'Bottom Right', 'premium-addons-pro' ),
                ],
                'default'   => 'center center',
                'label_block' => true,
                'condition'     => [
                    'scroll_up_type'    => 'image'
                ],
                'selectors'     => [
                     '#premium-color-transition-{{ID}} {{CURRENT_ITEM}}[data-direction="up"]' => 'background-position: {{VALUE}}',
                ],
            ]
        );
        
        $id_repeater->add_responsive_control('up_image_repeat',
            [
                'label'     => __('Repeat', 'premium-addons-pro'),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    'repeat'    => __( 'Repeat', 'premium-addons-pro' ),
                    'no-repeat' => __( 'No-repeat', 'premium-addons-pro' ),
                    'repeat-x'  => __( 'Repeat-x', 'premium-addons-pro' ),
                    'repeat-y'  => __( 'Repeat-y', 'premium-addons-pro' ),
                ],
                'default'   => 'repeat',
                'label_block'=> true,
                'condition'     => [
                    'scroll_up_type'    => 'image'
                ],
                'selectors'     => [
                     '#premium-color-transition-{{ID}} {{CURRENT_ITEM}}[data-direction="up"]' => 'background-repeat: {{VALUE}}',
                ],
            ]
        );
        
        $id_repeater->end_controls_tab();

        $id_repeater->end_controls_tabs();

        $this->add_control('id_repeater',
           [
               'label'              => __( 'Elements', 'premium-addons-pro' ),
               'type'               => Controls_Manager::REPEATER,
               'fields'             => $id_repeater->get_controls(),
               'title_field'        => '{{{ section_id }}}'
           ]
        );

        $this->end_controls_section();

        $this->start_controls_section('advanced',
            [
                'label'             => __('Advanced Settings', 'premium-addons-pro'),
            ]
        );

        $this->add_control('gradient',
            [
                'label'         => __('Change Colors As Gradient','premium-addons-pro'),
                'type'          => Controls_Manager::SWITCHER,
                'description'   => __('This option works if only colors are used', 'premium-addons-pro'),
                'return_value'  => 'true',
                'default'       => 'true',
            ]
        );
            
        $this->add_responsive_control('duration',
            [
                'label'         => __('Transition Duration (sec)', 'premium-addons-pro'),
                'type'          => Controls_Manager::SLIDER,
                'range'         => [
                    'px'    => [
                        'min'   => 0,
                        'max'   => 3,
                        'step'  => 0.1
                    ]
                ],
                'selectors'     => [
                    '#premium-color-transition-{{ID}} .premium-color-transition-layer'  => 'transition-duration: {{SIZE}}s'
                ]
            ]
        );
        
        $this->add_responsive_control('offset',
            [
                'label'         => __('Offset (PX)', 'premium-addons-pro'),
                'type'          => Controls_Manager::NUMBER,
                'description'   => __('Distance between the top of viewport and top of the element, default: 30', 'premium-addons-pro'),
                'min'           => 0,
                'default'       => 30
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section('section_pa_docs',
            [
                'label'         => __('Helpful Documentations', 'premium-addons-for-elementor'),
            ]
        );

        $doc1_url = Helper_Functions::get_campaign_link( 'https://premiumaddons.com/docs/background-transition-widget-tutorial/', 'editor-page', 'wp-editor', 'get-support' ); 

        $this->add_control('doc_1',
            [
                'type'            => Controls_Manager::RAW_HTML,
                'raw'             => sprintf(  '<a href="%s" target="_blank">%s</a>', $doc1_url ,__( 'Getting started »', 'premium-addons-for-elementor' ) ),
                'content_classes' => 'editor-pa-doc',
            ]
        );

        $this->end_controls_section();

    }

    /**
	 * Render Background Transition output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.7.1
	 * @access protected
	 */
    protected function render() {
        
        $settings   = $this->get_settings_for_display();

        $repeater   = $settings['id_repeater'];
        
        $elements   = array();

        $down_colors= array();

        $up_colors  = array();
        
        $items_ids  = array();
        
        foreach( $repeater as $element ) {

            array_push( $elements, $element['section_id'] );
            
            array_push( $items_ids, $element['_id'] );
            
            if( 'image' === $element['scroll_down_type'] && ! empty( $element['down_image']['url'] ) ) {

                $element['down_background'] = $element['down_image']['url'];
                
            } else {
                $element['down_background'] = $element['down_color'];
            }

            if( 'image' === $element['scroll_up_type'] && ! empty( $element['up_image']['url'] ) ) {
                
                $element['up_background'] = $element['up_image']['url'];
                
            } else {
                $element['up_background'] = $element['up_color'];
            }

            array_push( $down_colors, $element['down_background'] );

            array_push( $up_colors, $element['up_background'] );

        }

        $widget_settings = [
            'elements'      => $elements,
            'down_colors'   => $down_colors,
            'up_colors'     => $up_colors,
            'offset'        => $settings['offset'],
            'offset_tablet' => $settings['offset_tablet'],
            'offset_mobile' => $settings['offset_mobile'],
            'itemsIDs'      => $items_ids,
            'id'            => $this->get_id(),
            'gradient'      => $settings['gradient']
        ];

        $this->add_render_attribute( "container", "class", "premium-scroll-background" );

        $this->add_render_attribute( "container", "data-settings", wp_json_encode( $widget_settings ) );        
        
    ?>
    
        <div <?php echo $this->get_render_attribute_string( "container" ); ?>></div>

    <?php
    }
    
    
}