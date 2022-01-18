<?php

/**
 * Class: Premium_Hscroll
 * Name: Horizontal Scroll
 * Slug: premium-hscroll
 */
namespace PremiumAddonsPro\Widgets;

// Elementor Classes.
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;

// PremiumAddons Classes.
use PremiumAddons\Includes\Helper_Functions;
use PremiumAddons\Includes\Premium_Template_Tags;

if( ! defined('ABSPATH') ) exit(); // If this file is called directly, abort.

/**
 * Class Premium_Hscroll
 */
class Premium_Hscroll extends Widget_Base {
    
    public function getTemplateInstance() {
		return $this->templateInstance = Premium_Template_Tags::getInstance();
	}
    
    public function get_name() {
        return 'premium-hscroll';
    }

    public function get_title() {
        return sprintf( '%1$s %2$s', Helper_Functions::get_prefix(), __('Horizontal Scroll', 'premium-addons-pro') );
    }
    
    public function get_icon() {
        return 'pa-pro-horizontal-scroll'; 
    }
    
    public function get_categories() {
        return [ 'premium-elements' ];
    }
    
    public function get_style_depends() {
        return [
            'premium-addons'
        ];
    }
    
    public function get_script_depends() {
        return [
            'tweenmax-js',
            'pa-gsap',
            'elementor-waypoints',
            'papro-hscroll'
        ];
    }
    
    public function is_reload_preview_required() {
        return true;
    }
    
    public function get_keywords() {
        return [ 'slider', 'full', 'scene' ];
    }
    
    public function get_custom_help_url() {
		return 'https://premiumaddons.com/support/';
	}
    
    /**
	 * Register Premium Horizontal controls.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
    protected function _register_controls() {
        
        $this->start_controls_section('content_templates',
            [
                'label'         => __('Content', 'premium-addons-pro'),
            ]
        );
        
        $this->add_control('notices', 
            [
                'raw'               => __('<p>Important:</p><ul><li>Please make sure that "Stretch Section" option is disabled for sections below.</li></ul>', 'premium-addons-pro'),
                'type'              => Controls_Manager::RAW_HTML,
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
            ] 
        );
        
        $temp_repeater = new REPEATER();
        
        $temp_repeater->add_control('template_type',
            [
                'label'         => __('Content Type', 'premium-addons-pro'),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'template'      => __('Elementor Template', 'premium-addons-pro'),
                    'id'            => __('Section ID', 'premium-addons-pro'),
                ],
                'default'       => 'id',
            ]
        );
        
        $temp_repeater->add_control('section_template',
            [
                'label'			=> __( 'Elementor Template', 'premium-addons-pro' ),
                'type'          => Controls_Manager::SELECT2,
                'options'       => $this->getTemplateInstance()->get_elementor_page_list(),
                'multiple'      => false,
                'label_block'   => true,
                'condition'     => [
                    'template_type' => 'template'
                ]
            ]
        );
        
        $temp_repeater->add_control('anchor_id',
            [
                'label'			=> __( 'Anchor ID', 'premium-addons-pro' ),
                'type'          => Controls_Manager::TEXT,
                'description'   => __('This ID will be used to anchor your links to this slide', 'premium-addons-pro'),
                'dynamic'       => [ 'active' => true ],
                'condition'     => [
                    'template_type' => 'template'
                ]
            ]
        );
        
        $temp_repeater->add_control('section_id',
            [
                'label'			=> __( 'Section ID', 'premium-addons-pro' ),
                'type'          => Controls_Manager::TEXT,
                'dynamic'       => [ 'active' => true ],
                'condition'     => [
                    'template_type' => 'id'
                ]
            ]
        );
        
        $temp_repeater->add_control('scroll_bg_transition', 
            [
                'label'         => __('Scroll Background Transition', 'premium-addons-pro'),
                'type'          => Controls_Manager::SWITCHER,
            ]
        );
        
        $temp_repeater->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'              => 'scroll_bg',
                'types'             => [ 'classic' ],
                'selector'          => '{{WRAPPER}} {{CURRENT_ITEM}}',
                'condition'     => [
                    'scroll_bg_transition' => 'yes'
                ]
            ]
        );
        
        $this->add_control('section_repeater',
           [
               'label'          => __( 'Sections', 'premium-addons-pro' ),
               'type'           => Controls_Manager::REPEATER,
               'fields'         => $temp_repeater->get_controls(),
               'title_field'    => '{{{ "template" === template_type ? section_template : section_id }}}',
               'prevent_empty'  => false
           ]
        );
        
        $this->add_control('scroll_bg_speed',
            [
                'label'         => __('Background Transition Speed (sec)', 'premium-addons-pro'),
                'type'          => Controls_Manager::SLIDER,
                'range'         => [
					'px' => [
						'min' => 0,
						'max' => 3,
                        'step'=> 0.1
					],
				],
                'selectors'     => [
                    '{{WRAPPER}} .premium-hscroll-bg-layer' => 'transition-duration: {{SIZE}}s;'
                ]
            ]
        );


        $this->add_control('fixed_template',
            [
                'label'			=> __( 'Fixed Content Template', 'premium-addons-pro' ),
                'type'          => Controls_Manager::SELECT2,
                'options'       => $this->getTemplateInstance()->get_elementor_page_list(),
                'separator'     => 'before',
                'label_block'   => true,
                'multiple'      => false,
            ]
        );
        
        $this->add_responsive_control('fixed_content_voffset',
            [
                'label'         => __('Vertical Offset', 'premium-addons-pro'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', 'em', '%'],
                'range'         => [
                    'px'    => [
                        'min'   => 0,
                        'max'   => 600,
                    ],
                    'em'    => [
                        'min'   => 0,
                        'max'   => 50,
                    ]
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-hscroll-fixed-content' => 'top: {{SIZE}}{{UNIT}}'
                ],
                'condition'     => [
                    'fixed_template!'   => ''
                ]
            ]
        );

        $this->add_responsive_control('fixed_content_hoffset',
            [
                'label'         => __('Horizontal Offset', 'premium-addons-pro'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', 'em', '%'],
                'range'         => [
                    'px'    => [
                        'min'   => 0,
                        'max'   => 600,
                    ],
                    'em'    => [
                        'min'   => 0,
                        'max'   => 50,
                    ]
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-hscroll-fixed-content' => 'left: {{SIZE}}{{UNIT}}'
                ],
                'condition'     => [
                    'fixed_template!'   => ''
                ]
            ]
        );

        $this->add_control('fixed_content_zindex',
            [
                'label'         => __('Z-index', 'premium-addons-pro'),
                'type'          => Controls_Manager::NUMBER,
                'default'       => 1,
                'selectors'     => [
                    '{{WRAPPER}} .premium-hscroll-fixed-content' => 'z-index: {{VALUE}}'
                ],
                'condition'     => [
                    'fixed_template!'   => ''
                ]
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('advanced_settings',
            [
                'label'         => __('Advanced Settings', 'premium-addons-pro')
            ]
        );
        
        $this->add_responsive_control('slides',
            [
                'label'         => __('Number of Slides in Viewport', 'premium-addons-pro'),
                'type'          => Controls_Manager::SLIDER,
                'description'   => __('Select the number of slides to appear in your browser viewport. For example, 1.5 means half of the next slide will appear on viewport', 'premium-addons-pro'),
                'range'         => [
                    'px'    => [
                        'min'  => 1,
                        'step'  => 0.1
                    ]
                ],
                'default'       => [
                    'size'  => 1
                ],
                'tablet_default' => [
					'size' => 0.5,
				],
                'mobile_default' => [
					'size' => 0.5,
				],
            ]
        );
        
        $this->add_responsive_control('distance',
            [
                'label'         => __('Scroll Distance Beyond Last Slide', 'premium-addons-pro'),
                'type'          => Controls_Manager::SLIDER,
                'description'   => __('Set value in pixels for the scroll distance after last slide before scroll down to next section', 'premium-addons-pro'),
                'range'         => [
                    'px'    => [
                        'min'  => 0,
                        'max'  => 300,
                    ]
                ],
                'default'       => [
                    'size'  => 0
                ],
            ]
        );
        
        $this->add_responsive_control('trigger_offset',
            [
                'label'         => __('Offset (PX)', 'premium-addons-pro'),
                'type'          => Controls_Manager::SLIDER,
                'description'   => __('Offset at which the horizontal scroll is triggered', 'premium-addons-pro'),
                'range'         => [
                    'px'    => [
                        'min'   => 0,
                        'max'   => 600,
                    ]
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-hscroll-sections-wrap' => 'padding-top: {{SIZE}}px'
                ],
            ]
        );
        
        $this->add_control('scroll_effect',
            [
                'label'         => __('Scroll Type', 'premium-addons-pro'),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'normal'=> __('Normal', 'premium-addons-pro'),
                    'snap'  => __('Snappy', 'premium-addons-pro'),
                ],
                'default'       => 'normal',
            ]
        );
        
        $this->add_control('disable_snap', 
            [
                'label'         => __('Disable Snappy Effect on Touch Devices', 'premium-addons-pro'),
                'type'          => Controls_Manager::SWITCHER,
                'return_value'  => 'true',
                'default'       => 'true',
                'condition'     => [
                    'scroll_effect' => 'snap'
                ]
            ]
        );
        
        $this->add_responsive_control('scroll_speed',
            [
                'label'         => __('Decrease Scroll Speed by', 'premium-addons-pro'),
                'type'          => Controls_Manager::NUMBER,
                'description'   => __('For example, 2 means that scene scroll speed will be decreased to half', 'premium-addons-pro'),
                'min'           => 1,
                'default'       => 1,
                'conditions'    => [
                    'relation'      =>  'or',
                    'terms'         => [
                        [
                            'name'  =>  'scroll_effect',
                            'value'  => 'normal'
                        ],
                        [
                            'name'  =>  'disable_snap',
                            'value'  => 'true'
                        ]
                    ],  
                ],
            ]
        );
        
        $this->add_control('progress_bar', 
            [
                'label'         => __('Progress Bar', 'premium-addons-pro'),
                'type'          => Controls_Manager::SWITCHER,
                'return_value'  => 'true'
            ]
        );
        
        $this->add_responsive_control('progress_offset_left',
            [
                'label'         => __('Progress Bar Left Posiion (PX)', 'premium-addons-pro'),
                'type'          => Controls_Manager::SLIDER,
                'range'         => [
                    'px'    => [
                        'min'   => 0,
                        'max'   => 200,
                    ]
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-hscroll-progress' => 'left: {{SIZE}}px'
                ],
                'condition'     => [
                    'progress_bar'  => 'true'
                ]
            ]
        );
        
        $this->add_responsive_control('progress_offset_bottom',
            [
                'label'         => __('Progress Bar Bottom Posiion (PX)', 'premium-addons-pro'),
                'type'          => Controls_Manager::SLIDER,
                'range'         => [
                    'px'    => [
                        'min'   => 0,
                        'max'   => 200,
                    ]
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-hscroll-progress' => 'bottom: {{SIZE}}px'
                ],
                'condition'     => [
                    'progress_bar'  => 'true'
                ]
            ]
        );
        
        $this->add_control('opacity_transition',
            [
                'label'         => __('Opacity Scroll Effect', 'premium-addons-pro'),
                'type'          => Controls_Manager::SWITCHER,
                'return_value'  => 'true',
                'separator'     => 'before',
                'default'       => 'true',
                'condition'     => [
                    'entrance_animation!'   => 'true',
                    'rtl_mode!'              => 'true'
                ]
            ]
        );
        
        $this->add_control('entrance_animation', 
            [
                'label'         => __('Trigger Entrance Animations on Scroll', 'premium-addons-pro'),
                'description'   => __('This option will trigger entrance animations for inner widgets each time you scroll to a slide', 'premium-addons-pro'),
                'type'          => Controls_Manager::SWITCHER,
                'return_value'  => 'true',
                'condition'     => [
                    'scroll_effect'         => 'snap',
                    'opacity_transition!'   => 'true',
                    'rtl_mode!'              => 'true'
                ]
                
            ]
        );
        
        $this->add_control('keyboard_scroll', 
            [
                'label'         => __('Keyboard Scrolling', 'premium-addons-pro'),
                'description'   => __('Enable or disable scrolling slides using Keyboard', 'premium-addons-pro'),
                'type'          => Controls_Manager::SWITCHER,
                'return_value'  => 'true',
                'default'       => 'true',
                'separator'     => 'before'
            ]
        );

        $this->add_control('rtl_mode', 
            [
                'label'         => __('RTL Mode', 'premium-addons-pro'),
                'description'   => __('Enable this option to change scroll direction to RTL', 'premium-addons-pro'),
                'type'          => Controls_Manager::SWITCHER,
                'return_value'  => 'true',
                'prefix_class'  => 'premium-hscroll-rtl-',
                'render_type'   => 'template'
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('navigation',
            [
                'label'         => __('Navigation', 'premium-addons-pro')
            ]
        );
        
        $this->add_control('nav_dots', 
            [
                'label'         => __('Navigation Dots', 'premium-addons-pro'),
                'type'          => Controls_Manager::SWITCHER,
                'return_value'  => 'true',
                'default'       => 'true'
            ]
        );
        
//        $this->add_control('nav_dots_responsive', 
//            [
//                'label'         => __('Show Navigation Dots on Tablet/Mobile devices', 'premium-addons-pro'),
//                'type'          => Controls_Manager::SWITCHER,
//                'return_value'  => 'true',
//                'default'       => 'true'
//            ]
//        );
        
        $this->add_control('nav_dots_position', 
            [
                'label'         => __('Navigation Dots Position', 'premium-addons-pro'),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'bottom'=> __('Bottom', 'premium-addons-pro'),
                    'left'  => __('Left', 'premium-addons-pro'),
                    'right' => __('Right', 'premium-addons-pro'),
                ],
                'default'       => 'bottom',
                'prefix_class'  => 'premium-hscroll-dots-',
                'condition'    => [
                    'nav_dots'      =>  'true',
                ],
            ]
        );
        
        $this->add_responsive_control('nav_dots_offset',
            [
                'label'         => __('Dots Offset', 'premium-addons-pro'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => [ 'px', 'em', '%' ],
                'range'         => [
                    'px'    => [
                        'min'       => 5, 
                        'max'       => 100,
                    ],
                    'em'    => [
                        'min'       => 1, 
                        'max'       => 10,
                    ],
                ],
                'condition'    => [
                    'nav_dots'      =>  'true',
                ],
                'selectors'     => [
                    '{{WRAPPER}}.premium-hscroll-dots-bottom .premium-hscroll-nav' => 'bottom: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}}.premium-hscroll-dots-left .premium-hscroll-nav' => 'left: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}}.premium-hscroll-dots-right .premium-hscroll-nav' => 'right: {{SIZE}}{{UNIT}}',
                ]
            ]
        );
        
        $this->add_control('tooltips',
            [
                'label'         => __('Tooltips', 'premium-addons-pro'),
                'type'          => Controls_Manager::SWITCHER,
                'return_value'  => 'true',
                'condition'    => [
                    'nav_dots'      =>  'true',
                ],
            ]
        );
        
        $this->add_control('dots_tooltips',
            [
                'label'         => __('Dots Tooltips Text', 'premium-addons-pro'),
                'type'          => Controls_Manager::TEXT,
                'dynamic'       => [ 'active' => true ],
                'description'   => __('Add text for each navigation dot separated by \',\'','premium-addons-pro'),
                'label_block'   => 'true',
                'condition'     => [
                    'nav_dots'      => 'true',
                    'tooltips'      => 'true'
                ]
            ]
        );
        
        $this->add_control('nav_arrows',
            [
                'label'         => __('Navigation Arrows', 'premium-addons-pro'),
                'type'          => Controls_Manager::SWITCHER,
                'return_value'  => 'true',
                'default'       => 'true',
                'separator'     => 'before'
            ]
        );
        
//        $this->add_control('nav_arrows_responsive', 
//            [
//                'label'         => __('Show Navigation Arrows on Table/Mobile devices', 'premium-addons-pro'),
//                'type'          => Controls_Manager::SWITCHER,
//                'return_value'  => 'true',
//                'default'       => 'true'
//            ]
//        );
        
        $this->add_control('nav_arrow_left',
		  	[
		     	'label'         => __( 'Left Arrow Icon', 'premium-addons-pro' ),
		     	'type'          => Controls_Manager::ICONS,
                'default'       => [
                    'library'       => 'fa-solid',
                    'value'         => 'fas fa-angle-left',
                ],
                'condition'    => [
                    'nav_arrows'      =>  'true',
                ]
		  	]
		);
        
        $this->add_control('nav_arrow_right',
		  	[
		     	'label'         => __( 'Right Arrow Icon', 'premium-addons-pro' ),
		     	'type'          => Controls_Manager::ICONS,
                'default'       => [
                    'library'       => 'fa-solid',
                    'value'         => 'fas fa-angle-right',
                ],
                'condition'    => [
                    'nav_arrows'      =>  'true'
                ]
		  	]
		);
        
        $this->add_responsive_control('carousel_arrows_pos',
            [
                'label'         => __('Arrows Position', 'premium-addons-pro'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => [ 'px', 'em' ],
                'range'         => [
                    'px'    => [
                        'min'       => -100, 
                        'max'       => 100,
                    ],
                    'em'    => [
                        'min'       => -10, 
                        'max'       => 10,
                    ],
                ],
                'condition'    => [
                    'nav_arrows'      =>  'true',
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-hscroll-arrow-right' => 'right: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .premium-hscroll-arrow-left' => 'left: {{SIZE}}{{UNIT}}',
                ]
            ]
        );
        
        $this->add_control('loop', 
            [
                'label'         => __('Loop', 'premium-addons-pro'),
                'type'          => Controls_Manager::SWITCHER,
                'return_value'  => 'true',
                'condition'     => [
                    'scroll_effect' => 'normal',
                    'nav_arrows'    => 'true',
                ]
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('pagination',
            [
                'label'         => __('Pagination Numbers', 'premium-addons-pro')
            ]
        );
        
        $this->add_control('pagination_number',
            [
                'label'         => __('Enable Pagination Number', 'premium-addons-pro'),
                'type'          => Controls_Manager::SWITCHER,
                'return_value'  => 'true',
                'default'       => 'true',
            ]
        );
        
        $this->add_responsive_control('pagination_hor',
            [
                'label'         => __('Horizontal Offset', 'premium-addons-pro'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => [ 'px', 'em', '%' ],
                'range'         => [
                    'px'    => [
                        'min'   => 0,
                        'max'   => 300
                    ],
                    'em'    => [
                        'min'   => 0,
                        'max'   => 30
                    ]
                ],
                'condition'		=> [
					'pagination_number' => 'true'
				],
                'selectors'     => [
                    '{{WRAPPER}} .premium-hscroll-pagination' => 'left: {{SIZE}}{{UNIT}}',
                ]
            ]
        );
        
        $this->add_responsive_control('pagination_ver',
            [
                'label'         => __('Vertical Offset', 'premium-addons-pro'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => [ 'px', 'em', '%' ],
                'range'         => [
                    'px'    => [
                        'min'   => 0,
                        'max'   => 300
                    ],
                    'em'    => [
                        'min'   => 0,
                        'max'   => 30
                    ]
                ],
                'condition'		=> [
					'pagination_number' => 'true'
				],
                'selectors'     => [
                    '{{WRAPPER}} .premium-hscroll-pagination' => 'bottom: {{SIZE}}{{UNIT}}',
                ]
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('responsive',
            [
                'label'         => __('Responsive Settings', 'premium-addons-pro')
            ]
        );
        
        $this->add_control('override_columns',
            [
                'label'         => __('Put Columns Next to Each Other', 'premium-addons-pro'),
                'type'          => Controls_Manager::SWITCHER,
                'description'   => __('This option will force the columns to be positioned next to each other on small screens'),
                'prefix_class'  => 'premium-hscroll-force-',
                'return_value'  => 'true',
                'default'       => 'true',
            ]
        );

        $this->end_controls_section();
        
        $this->start_controls_section('section_pa_docs',
            [
                'label'         => __('Helpful Documentations', 'premium-addons-pro'),
            ]
        );

        $docs = [
            'https://premiumaddons.com/docs/horizontal-scroll-widget-tutorial' => 'Getting started »',
            'https://premiumaddons.com/docs/how-to-play-pause-a-soundtrack-using-premium-button-widget/' => 'How to Play/Pause a Soundtrack Using Premium Button Widget »',
            'https://www.youtube.com/watch?v=4HqT_3s-ZXg' => 'Check the video tutorial »'
        ];

        $doc_index = 1;
        foreach( $docs as $url => $title ) {

            $doc_url = Helper_Functions::get_campaign_link( $url, 'editor-page', 'wp-editor', 'get-support' ); 

            $this->add_control('doc_' . $doc_index,
                [
                    'type'            => Controls_Manager::RAW_HTML,
                    'raw'             => sprintf(  '<a href="%s" target="_blank">%s</a>', $doc_url , __( $title, 'premium-addons-for-elementor' ) ),
                    'content_classes' => 'editor-pa-doc',
                ]
            );

            $doc_index++;

        }

        $this->end_controls_section();
        
        $this->start_controls_section('nav_dots_style',
            [
                'label'         => __('Navigation Dots', 'premium-addons-pro'),
                'tab'           => Controls_Manager::TAB_STYLE,
                'condition'    => [
                    'nav_dots'      =>  'true',
                ],
            ]
        );
        
        $this->add_responsive_control('dots_size',
            [
                'label'         => __('Size', 'premium-addons-pro'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', 'em'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-hscroll-nav-dot' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}'
                ]
            ]
        );
        
        $this->add_control('dot_color',
            [
                'label'         => __('Color', 'premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .premium-hscroll-nav-dot' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .premium-hscroll-carousel-icon'  => 'background-color: {{VALUE}}; color: {{VALUE}}'
                ]
            ]
        );
        
        $this->add_control('active_color',
            [
                'label'         => __('Active Color', 'premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .premium-hscroll-nav-item.active .premium-hscroll-nav-dot' => 'background-color: {{VALUE}}'
                ]
            ]
        );
        
        $this->add_control('dot_border_color',
            [
                'label'         => __('Border Color', 'premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .premium-hscroll-nav-item .premium-hscroll-nav-dot' => 'border-color: {{VALUE}}',
                ]
            ]
        );
        
        $this->add_control('active_border_color',
            [
                'label'         => __('Active Border Color', 'premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .premium-hscroll-nav-item.active .premium-hscroll-nav-dot' => 'border-color: {{VALUE}}',
                ]
            ]
        );
        
        $this->add_responsive_control('dot_border_radius',
            [
                'label'         => __('Border Radius', 'premium-addons-pro'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-hscroll-nav-item .premium-hscroll-nav-dot' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}'
                ],
            ]
        );
        
        $this->add_control('tooltips_heading',
			[
				'label'			=> __( 'Tooltips', 'premium-addons-pro' ),
				'type'			=> Controls_Manager::HEADING,
                'condition'     => [
                    'tooltips'      => 'true'
                ]
			]
		);
        
        $this->add_responsive_control('tooltip_spacing',
            [
                'label'         => __('Spacing', 'premium-addons-pro'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', 'em'],
                'selectors'     => [
                    '{{WRAPPER}}.premium-hscroll-dots-bottom .premium-hscroll-nav-tooltip' => 'bottom: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}}.premium-hscroll-dots-left .premium-hscroll-nav-tooltip' => 'left: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}}.premium-hscroll-dots-right .premium-hscroll-nav-tooltip' => 'right: {{SIZE}}{{UNIT}}',
                ],
                'condition'     => [
                    'tooltips'      => 'true'
                ]
            ]
        );
        
        $this->add_control('tooltip_color',
            [
                'label'         => __('Color', 'premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .premium-hscroll-nav-tooltip' => 'color: {{VALUE}}'
                ],
                'condition'     => [
                    'tooltips'      => 'true'
                ]
            ]
        );
        
        $this->add_control('tooltip_background_color',
            [
                'label'         => __('Background Color', 'premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .premium-hscroll-nav-tooltip' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}}.premium-hscroll-dots-left .premium-hscroll-nav-tooltip::after' => 'border-right-color: {{VALUE}}',
                    '{{WRAPPER}}.premium-hscroll-dots-right .premium-hscroll-nav-tooltip::after' => 'border-left-color: {{VALUE}}',
                ],
                'condition'     => [
                    'tooltips'      => 'true'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'          => 'tooltip_typography',
                'selector'      => '{{WRAPPER}} .premium-hscroll-nav-tooltip',
                'condition'     => [
                    'tooltips'      => 'true'
                ]
            ]
        );
        
        $this->add_responsive_control('tooltip_border_radius',
            [
                'label'         => __('Border Radius', 'premium-addons-pro'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-hscroll-nav-tooltip' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}'
                ],
                'condition'     => [
                    'tooltips'      => 'true'
                ]
            ]
        );
        
        $this->add_responsive_control('tooltip_padding',
            [
                'label'         => __('Padding', 'premium-addons-pro'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-hscroll-nav-tooltip' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}'
                ],
                'condition'     => [
                    'tooltips'      => 'true'
                ]
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('nav_arrows_style',
            [
                'label'         => __('Navigation Arrows', 'premium-addons-pro'),
                'tab'           => Controls_Manager::TAB_STYLE,
                'condition'    => [
                    'nav_arrows'      =>  'true',
                ]
            ]
        );
        
        $this->add_responsive_control('arrow_size',
            [
                'label'         => __('Size', 'premium-addons-pro'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%' ,'em'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-hscroll-wrap-icon' => 'font-size: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .premium-hscroll-wrap-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}'
                ]
            ]
        );
        
        $this->add_control('arrow_color',
            [
                'label'         => __('Color', 'premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .premium-hscroll-arrow i' => 'color: {{VALUE}}',
                ]
            ]
        );
        
        $this->add_control('arrow_hover_color',
            [
                'label'         => __('Hover Color', 'premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .premium-hscroll-arrow:hover i' => 'color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control('arrow_background',
            [
                'label'         => __('Background Color', 'premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .premium-hscroll-wrap-icon' => 'background-color: {{VALUE}}',
                ]
            ]
        );
        
        $this->add_control('arrow_hover_background',
            [
                'label'         => __('Hover Background Color', 'premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .premium-hscroll-wrap-icon:hover' => 'background-color: {{VALUE}}',
                ]
            ]
        );
        
        $this->add_control('arrow_border_radius',
            [
                'label'         => __('Border Radius', 'premium-addons-pro'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', '%' ,'em'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-hscroll-wrap-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}'
                ]
            ]
        );
        
        $this->add_control('arrow_padding',
            [
                'label'         => __('Padding', 'premium-addons-pro'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%' ,'em'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-hscroll-wrap-icon' => 'padding: {{SIZE}}{{UNIT}}'
                ]
            ]
        );

        $this->end_controls_section();
        
        $this->start_controls_section('progress_style',
            [
                'label'         => __('Progress Bar', 'premium-addons-pro'),
                'tab'           => Controls_Manager::TAB_STYLE,
                'condition'     => [
                    'progress_bar'  => 'true'
                ]
            ]
        );
        
        $this->add_control('progress_color',
            [
                'label'         => __('Progress Color', 'premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .premium-hscroll-progress-line' => 'background-color: {{VALUE}}',
                ]
            ]
        );
        
        $this->add_control('progress_background_color',
            [
                'label'         => __('Background Color', 'premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .premium-hscroll-progress' => 'background-color: {{VALUE}}',
                ]
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('pagination_style',
            [
                'label'         => __('Pagination Numbers', 'premium-addons-pro'),
                'tab'           => Controls_Manager::TAB_STYLE,
                'condition'     => [
                    'pagination_number'  => 'true'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'          => 'pagination_typography',
                'selector'      => '{{WRAPPER}} .premium-hscroll-pagination span',
            ]
        );

        $this->add_control('pagination_spacing',
            [
                'label'         => __('Spacing Between', 'premium-addons-pro'),
                'type'          => Controls_Manager::SLIDER,
                'range'         => [
                    'px'    => [
                        'min'   => 0,
                        'max'   => 50
                    ]
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-hscroll-total-slides:before'  => 'margin: 0 {{SIZE}}px'
                ],
            ]
        );
        
        $this->add_control('pagination_numbers_current_color',
            [
                'label'         => __('Current Slide Color', 'premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .premium-hscroll-current-slide' => 'color: {{VALUE}}',
                ]
            ]
        );
        
        $this->add_control('pagination_numbers_sep_color',
            [
                'label'         => __('Separator Color', 'premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .premium-hscroll-total-slides:before' => 'color: {{VALUE}}',
                ]
            ]
        );
        
        $this->add_control('pagination_numbers_total_color',
            [
                'label'         => __('Total Slides Color', 'premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'separator'     => 'after',
                'selectors'     => [
                    '{{WRAPPER}} .premium-hscroll-total-slides' => 'color: {{VALUE}}',
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'              => 'pagination_background',
                'types'             => [ 'classic' , 'gradient' ],
                'selector'          => '{{WRAPPER}} .premium-hscroll-pagination',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(), 
            [
                'name'          => 'pagination_border',
                'selector'      => '{{WRAPPER}} .premium-hscroll-pagination',
            ]
        );

        $this->add_control('pagination_radius',
            [
                'label'         => __('Border Radius', 'premium-addons-pro'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', '%' ,'em'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-hscroll-pagination' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}'
                ]
            ]
        );
        
        $this->add_responsive_control('pagination_padding',
            [
                'label'         => __('Padding', 'premium-addons-pro'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-hscroll-pagination' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
       
        $this->end_controls_section();
        
        $this->start_controls_section('container',
            [
                'label'         => __('Container', 'premium-addons-pro'),
                'tab'           => Controls_Manager::TAB_STYLE,
                
            ]
        );
        
        $this->add_control('container_background',
            [
                'label'         => __('Background Color', 'premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .premium-hscroll-outer-wrap' => 'background-color: {{VALUE}}',
                ]
            ]
        );
        
        $this->add_responsive_control('container_padding',
            [
                'label'         => __('Padding', 'premium-addons-pro'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-hscroll-outer-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
    }
    
    /**
	 * Render Horizontal Scroll widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
    protected function render() {
        
        $settings = $this->get_settings_for_display();
        
        $id = $this->get_id();
        
        $this->add_render_attribute( 'wrap', 'id', 'premium-hscroll-wrap-' . $id );
        $this->add_render_attribute( 'wrap', 'class', 'premium-hscroll-wrap' );
        
        if( 'true' !== $settings['nav_arrows'] )
            $this->add_render_attribute( 'wrap', 'class', 'premium-hscroll-arrows-hidden' );
        
        if( 'true' !== $settings['nav_dots'] )
            $this->add_render_attribute( 'wrap', 'class', 'premium-hscroll-dots-hidden' );
        
        
        $this->add_render_attribute( 'scroller_wrap', 'id', 'premium-hscroll-scroller-wrap-' . $id );
        $this->add_render_attribute( 'scroller_wrap', 'class', 'premium-hscroll-scroller-wrap' );
        $this->add_render_attribute( 'scroller_wrap', 'data-progress', 'bottom' );
       
        
        $this->add_render_attribute( 'progress_wrap', 'class', 'premium-hscroll-progress' );
        if( 'true' !== $settings['progress_bar'] )
            $this->add_render_attribute( 'progress_wrap', 'class', 'premium-hscroll-progress-hidden' );
        
        $this->add_render_attribute( 'progress', 'id', 'premium-hscroll-progress-line-' . $id );
        $this->add_render_attribute( 'progress', 'class', 'premium-hscroll-progress-line' );
        
        $templates = $settings['section_repeater'];
        
        $count = count( $templates );
        
        $snap = 'snap' === $settings['scroll_effect'] ? true : false;
        
        $disable_snap = false;
        
        if( 'snap' === $settings['scroll_effect'] ) {
            if( 'true' === $settings['disable_snap'] ) {
                $disable_snap = true;
            }
        }
        
        $opacity    = 'true' === $settings['opacity_transition'] ? true : false;
        
        $pagination = 'true' === $settings['pagination_number'] ? true : false;
        
        $loop       = 'true' === $settings['loop'] ? true : false;
        
        if( 'true' === $settings['tooltips'] ) {
            $tooltips   = explode(',', $settings['dots_tooltips'] );
        }
        
        $entrance   = 'true' === $settings['entrance_animation'] ? true : false;
        
        $keyboard   = 'true' === $settings['keyboard_scroll'] ? true: false;
        
        $slides     = ! empty ( $settings['slides']['size'] ) ? floatval( $settings['slides']['size'] ) : 1;
        
        $distance   = ! empty ( $settings['distance']['size'] ) ? floatval( $settings['distance']['size'] ) : 0;
        
        $speed      = ! empty ( $settings['scroll_speed'] ) ? intval( $settings['scroll_speed'] ) : 1;
        
        $hscroll_settings = [
            'id'            => $id,
            'templates'     => $templates,
            'slides'        => $slides,
            'slides_tablet' => empty( $settings['slides_tablet']['size'] ) ? $slides :  floatval( $settings['slides_tablet']['size'] ),
            'slides_mobile' => empty( $settings['slides_mobile']['size'] ) ? $slides :  floatval( $settings['slides_mobile']['size'] ),
            'distance'      => $distance,
            'distance_tablet' => empty( $settings['distance_tablet']['size'] ) ? $slides :  floatval( $settings['distance_tablet']['size'] ),
            'distance_mobile' => empty( $settings['distance_mobile']['size'] ) ? $slides :  floatval( $settings['distance_mobile']['size'] ),
            'snap'          => intval( $snap ),
            'disableSnap'   => intval( $disable_snap ),
            'speed'         => $speed,
            'speed_tablet'  => empty( $settings['scroll_speed_tablet'] ) ? $speed : intval( $settings['scroll_speed_tablet'] ),
            'speed_mobile'  => empty( $settings['scroll_speed_mobile'] ) ? $speed : intval( $settings['scroll_speed_mobile'] ),
            'opacity'       => intval( $opacity ),
            'loop'          => intval( $loop ),
            'enternace'     => intval( $entrance ),
            'keyboard'      => intval( $keyboard ),
            'pagination'    => intval( $pagination ),
            'rtl'           => $settings['rtl_mode'],
            'arrows'        => 'true' === esc_html( $settings['nav_arrows'] ) ? true : false,
            'dots'          => 'true' === esc_html( $settings['nav_dots'] ) ? true : false,
        ];
        
        //Fix warning trying to access array offset with value null
        if( 'true' === $settings['nav_arrows'] ) {
            $hscroll_settings['leftArrow']  = esc_html( $settings['nav_arrow_left']['value'] );
            $hscroll_settings['rightArrow'] = esc_html( $settings['nav_arrow_right']['value'] );
        }
        
        
        $this->add_render_attribute( 'spacer', 'id', 'premium-hscroll-spacer-' . $id );
        $this->add_render_attribute( 'spacer', 'class', 'premium-hscroll-spacer' );
        
        $this->add_render_attribute( 'nav', 'class', 'premium-hscroll-nav' );
    
    ?>
    <div class="premium-hscroll-outer-wrap">
        <div <?php echo $this->get_render_attribute_string( 'spacer' ); ?>></div>
        <div <?php echo $this->get_render_attribute_string( 'wrap' ); ?> data-settings='<?php echo wp_json_encode( $hscroll_settings ); ?>'>
                <?php foreach( $templates as $index => $section ) :
                    
                    if( 'yes' === $section['scroll_bg_transition'] ) {
                        $list_item_key = 'premium_hscroll_bg_layer_' . $index;
                        
                        $this->add_render_attribute( $list_item_key , array(
                            'class'         => [
                                'premium-hscroll-bg-layer',
                                'elementor-repeater-item-' . $section['_id'],
                            ],
                            'data-layer'    => $index
                        ));
                        if( 0 === $index ) {
                            $this->add_render_attribute( $list_item_key , 'class',  'premium-hscroll-layer-active' );
                        }
                        
                        ?>
                        <div <?php echo $this->get_render_attribute_string( $list_item_key ); ?>></div>
                    <?php 
                    }
                endforeach;

                ?>
            <?php if( ! empty( $settings['fixed_template'] ) ) : ?>
                <div class="premium-hscroll-fixed-content">
                    <?php 
                        $template_title = $settings['fixed_template'];
                        echo $this->getTemplateInstance()->get_template_content( $template_title );
                    ?>
                </div>
            <?php endif;
            if( 0 !== $count ) : ?>
                <div class="premium-hscroll-arrow premium-hscroll-arrow-left">
                    <div class="premium-hscroll-wrap-icon">
                        <?php Icons_Manager::render_icon( $settings['nav_arrow_left'], [ 'class' => 'premium-hscroll-prev', 'aria-hidden' => 'true' ] );
                        ?>
                    </div>
                </div>
            <?php endif; ?>
            <div class="premium-hscroll-slider">
                <div <?php echo $this->get_render_attribute_string('scroller_wrap'); ?>>
                    <div class="premium-hscroll-sections-wrap" data-scroll-opacity="<?php echo $opacity; ?>">
                        <?php foreach( $templates as $index => $section ) :
                            $this->add_render_attribute('section_' . $index, array(
                                'id'    => 'section_' . $id . $index,
                                'class' => 'premium-hscroll-temp'
                            ));
                            if( 'id' === $section['template_type'] ) {
                                $this->add_render_attribute('section_' . $index, array(
                                    'data-section'      => $section['section_id']
                                ));
                            } else {
                                if( ! empty( $section['anchor_id'] ) ) {
                                    $this->add_render_attribute('section_' . $index, array(
                                        'data-section'      => $section['anchor_id']
                                    ));
                                }
                            }
                            if( $opacity ) {
                                if( 0 !== $index && ! $settings['rtl_mode'] ) {
                                    $this->add_render_attribute('section_' . $index, 'class', 'premium-hscroll-hide' );
                                } elseif( $count - 1 !== $index && $settings['rtl_mode'] ) {
                                    $this->add_render_attribute('section_' . $index, 'class', 'premium-hscroll-hide' );
                                }
                                    
                            }
                        ?>
                        <div <?php echo $this->get_render_attribute_string( 'section_' . $index ); ?>>
                            <?php
                                if( 'template' === $section['template_type'] ) {
                                    $template_title = $section['section_template'];
                                    echo $this->getTemplateInstance()->get_template_content( $template_title );
                                }
                            ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <div <?php echo $this->get_render_attribute_string('progress_wrap'); ?>>
                        <div <?php echo $this->get_render_attribute_string('progress'); ?>></div>
                    </div>
                </div>
            </div>
            <?php if( 0 !== $count ) : ?>
                <div class="premium-hscroll-arrow premium-hscroll-arrow-right">
                    <div class="premium-hscroll-wrap-icon">
                        <?php Icons_Manager::render_icon( $settings['nav_arrow_right'], [ 'class' => 'premium-hscroll-next', 'aria-hidden' => 'true' ] );
                                ?>
                    </div>
                </div>
            
                <div <?php echo $this->get_render_attribute_string('nav'); ?>>
                    <ul class="premium-hscroll-nav-list dots">
                        <?php foreach( $templates as $index => $section ) :
                            $this->add_render_attribute( 'item_' . $index, array(
                                'class'         => 'premium-hscroll-nav-item',
                                'data-slide'    => 'section_'  . $id . $index
                            ) );
                        ?>
                            <li <?php echo $this->get_render_attribute_string( 'item_' . $index ); ?>>
                                <span class="premium-hscroll-nav-dot"></span>
                                <?php if( 'true' === $settings['tooltips'] && ! empty( $tooltips[ $index ] ) ) : ?> 
                                    <span class="premium-hscroll-nav-tooltip"><?php echo esc_html( $tooltips[ $index ] ); ?></span>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif;
            if( 0 !== $count && $settings['pagination_number'] ) : ?>
                <div class="premium-hscroll-pagination">
                    <span class="premium-hscroll-page-item premium-hscroll-current-slide">01</span>
                    <span class="premium-hscroll-page-item premium-hscroll-total-slides"><?php echo $count > 9 ? $count : sprintf('0%s', $count ); ?></span>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php }
}