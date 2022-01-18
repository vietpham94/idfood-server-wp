<?php

/**
 * Class: Premium_Image_Accordion
 * Name: Image Accordion
 * Slug: premium-image-accordion
 */
namespace PremiumAddonsPro\Widgets;

// Elementor Classes.
use Elementor\Icons_Manager;
use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Repeater;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Css_Filter;

// PremiumAddons Classes.
use PremiumAddons\Includes\Helper_Functions;
use PremiumAddons\Includes\Premium_Template_Tags;

if( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class Premium_Image_Accordion
 */
class Premium_Image_Accordion extends Widget_Base {
    
    public function getTemplateInstance() {
        return $this->templateInstance = Premium_Template_Tags::getInstance();
    }

    public function get_name() {
        return 'premium-image-accordion';
    }

    public function get_title() {
        return sprintf( '%1$s %2$s', Helper_Functions::get_prefix(), __('Image Accordion', 'premium-addons-pro') );
    }

    public function get_script_depends() {
        return [
            'lottie-js',
            'premium-pro-js'
        ];
    }

    public function get_icon() {
        return 'pa-pro-image-accordion';
    }
    
    public function get_categories() {
        return [
            'premium-elements'
        ];
    }
    
    public function get_keywords() {
		return [ 'image', 'photo', 'visual', 'slide' ];
	}
    
    public function get_custom_help_url() {
		return 'https://premiumaddons.com/support/';
	}

    /**
	 * Register Image Accordion controls.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
    protected function _register_controls() {
        
        $this->start_controls_section('content',
            [
                'label'     => __('Accordion','premium-addons-pro'),
            ]
        );

        $accordion_repeater = new Repeater();
        
        $accordion_repeater->add_control('image', 
            [
                'label'     => __( 'Upload Image', 'premium-addons-pro' ),
                'type'      => Controls_Manager::MEDIA,
                'dynamic'   => [ 'active' => true ],
                'default'   => [
                    'url'       => Utils::get_placeholder_image_src(),
                ],
                'selectors'     => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}::before, {{WRAPPER}} {{CURRENT_ITEM}} .premium-accordion-background' => 'background-image: url("{{URL}}");',
                ],
            ]
        );
        
        $accordion_repeater->add_responsive_control('image_size',
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
                     '{{WRAPPER}} {{CURRENT_ITEM}}::before, {{WRAPPER}} {{CURRENT_ITEM}} .premium-accordion-background' => 'background-size: {{VALUE}}',
                 ],
            ]
        );
        
        $accordion_repeater->add_responsive_control('image_size_custom', [
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
                'image_size'    => 'custom'
            ],
			'selectors'     => [
				'{{WRAPPER}} {{CURRENT_ITEM}}::before, {{WRAPPER}} {{CURRENT_ITEM}} .premium-accordion-background' => 'background-size: {{SIZE}}{{UNIT}} auto',

			]
        ]);
        

        $accordion_repeater->add_responsive_control('image_position',
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
                'selectors'     => [
                     '{{WRAPPER}} {{CURRENT_ITEM}}::before, {{WRAPPER}} {{CURRENT_ITEM}} .premium-accordion-background' => 'background-position: {{VALUE}}',
                 ],
            ]
        );
        
        $accordion_repeater->add_responsive_control('image_repeat',
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
                'selectors'     => [
                     '{{WRAPPER}} {{CURRENT_ITEM}}::before, {{WRAPPER}} {{CURRENT_ITEM}} .premium-accordion-background' => 'background-repeat: {{VALUE}}',
                 ],
            ]
        );

        
        $accordion_repeater->add_control('content_switcher',
            [
                'label'     => __( 'Content', 'premium-addons-pro' ),
                'type'      => Controls_Manager::SWITCHER,
            ]
        );
        
        $condition = [ 'content_switcher'   => 'yes' ];

        $accordion_repeater->add_control('icon_switcher',
            [
                'label'     => __( 'Icon', 'premium-addons-pro' ),
                'type'      => Controls_Manager::SWITCHER,
                'condition' => array_merge( [], $condition )
            ]
        );

        $accordion_repeater->add_control('icon_type',
            [
                'label'			=> __( 'Icon Type', 'premium-addons-pro' ),
                'type' 			=> Controls_Manager::SELECT,
                'options'		=> [
                    'icon'          => __('Icon', 'premium-addons-pro'),
                    'animation'     => __('Lottie Animation', 'premium-addons-pro'),
                ],
                'default'		=> 'icon',
                'condition'     => [
                    'icon_switcher'   => 'yes',
                ]
            ]
        );

        $accordion_repeater->add_control('icon_updated',
            [
                'label'     => __( 'Icon', 'premium-addons-pro' ),
                'type'              => Controls_Manager::ICONS,
                'fa4compatibility'  => 'icon',
                'label_block'=> true,
                'default' => [
                    'value'     => 'fas fa-star',
                    'library'   => 'fa-solid',
                ],
                'condition' => array_merge( [
                    'icon_switcher' => 'yes',
                    'icon_type'     => 'icon'
                ], $condition )
            ]
        );

        $accordion_repeater->add_control('lottie_url', 
            [
                'label'             => __( 'Animation JSON URL', 'premium-addons-pro' ),
                'type'              => Controls_Manager::TEXT,
                'dynamic'           => [ 'active' => true ],
                'description'       => 'Get JSON code URL from <a href="https://lottiefiles.com/" target="_blank">here</a>',
                'label_block'       => true,
                'condition' => array_merge( [
                    'icon_switcher' => 'yes',
                    'icon_type'     => 'animation'
                ], $condition )
            ]
        );

        $accordion_repeater->add_control('lottie_loop',
            [
                'label'         => __('Loop','premium-addons-pro'),
                'type'          => Controls_Manager::SWITCHER,
                'return_value'  => 'true',
                'default'       => 'true',
                'condition' => array_merge( [
                    'icon_switcher' => 'yes',
                    'icon_type'     => 'animation'
                ], $condition )
            ]
        );

        $accordion_repeater->add_control('lottie_reverse',
            [
                'label'         => __('Reverse','premium-addons-pro'),
                'type'          => Controls_Manager::SWITCHER,
                'return_value'  => 'true',
                'condition' => array_merge( [
                    'icon_switcher' => 'yes',
                    'icon_type'     => 'animation'
                ], $condition )
            ]
        );

        $accordion_repeater->add_control('image_title', 
            [
                'label'     => __( 'Title', 'premium-addons-pro' ),
                'type'      => Controls_Manager::TEXT,
                'dynamic'   => [ 'active' => true ],
                'condition' => array_merge( [], $condition )
            ]
        );
            
        $accordion_repeater->add_control('image_desc', 
            [
                'label'     => __( 'Description', 'premium-addons-pro' ),
                'type'      => Controls_Manager::TEXTAREA,
                'dynamic'   => [ 'active' => true ],
                'condition' => array_merge( [], $condition )
            ]
        );
        
        $accordion_repeater->add_control('custom_position',
            [
                'label'     => __('Custom Position','premium-addons-pro'),
                'type'      => Controls_Manager::SWITCHER,
                'condition' => array_merge( [], $condition )
            ]
        );
        
        $accordion_repeater->add_responsive_control('hor_offset',
            [
                'label'         => __('Horizontal Offset', 'premium-addons-pro'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => [ 'px', 'em', '%' ],
                'range'         => [
                    'px'    => [
                        'min'   => 0,
                        'max'   => 400
                    ]
                ],
                'label_block'   => true,
                'selectors'     => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .premium-accordion-content' => 'position: absolute; left: {{SIZE}}{{UNIT}}',
                ],
                'condition'     => [
                    'custom_position'   => 'yes'
                ]
            ]
        );
        
        $accordion_repeater->add_responsive_control('ver_offset',
            [
                'label'         => __('Vertical Offset', 'premium-addons-pro'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => [ 'px', 'em', '%' ],
                'range'         => [
                    'px'    => [
                        'min'   => 0,
                        'max'   => 400
                    ]
                ],
                'label_block'   => true,
                'selectors'     => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .premium-accordion-content' => 'position: absolute; top: {{SIZE}}{{UNIT}}',
                ],
                'condition'     => [
                    'custom_position'   => 'yes'
                ]
            ]
        );

        $accordion_repeater->add_control('link_switcher',
            [
                'label'     => __('Link','premium-addons-pro'),
                'type'      => Controls_Manager::SWITCHER,
                'description'=> __('Add a custom link or select an existing page link','premium-addons-pro'),
                'condition' => array_merge( [], $condition )
            ]
        );

        $accordion_repeater->add_control('link_type', 
            [
                'label'     => __('Link Type', 'premium-addons-pro'),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    'url'   => __('URL', 'premium-addons-pro'),
                    'link'  => __('Existing Page', 'premium-addons-pro'),
                ],
                'default'   => 'url',
                'label_block'=> true,
                'condition'	=> array_merge( [
                    'link_switcher' => 'yes'
                ], $condition )
            ]
        );

        $accordion_repeater->add_control('link',
            [
                'label'         => __('Link', 'premium-addons-pro'),
                'type'          => Controls_Manager::URL,
                'placeholder'   => 'https://premiumaddons.com/',
                'dynamic'       => [ 'active' => true ],
                'label_block'   => true,
                'condition'	=> array_merge( [
                    'link_switcher' => 'yes',
                    'link_type' => 'url'
                ], $condition )
            ]
        );
        
        $accordion_repeater->add_control('existing_link',
            [
                'label'         => __('Existing Page', 'premium-addons-pro'),
                'type'          => Controls_Manager::SELECT2,
                'options'       => $this->getTemplateInstance()->get_all_posts(),
                'condition'	=> array_merge( [
                    'link_switcher' => 'yes',
                    'link_type' => 'link'
                ], $condition ),
                'label_block'   => true,
            ]
        );  

        $accordion_repeater->add_control('link_title',
            [
                'label'         => __('Link Title', 'premium-addons-pro'),
                'type'          => Controls_Manager::TEXT,
                'dynamic'       => [ 'active' => true ],
                'condition'	=> array_merge( [
                    'link_switcher' => 'yes',
                ], $condition ),
                'label_block'   => true
            ]
        );

        $accordion_repeater->add_control('link_whole',
            [
                'label'         => __( 'Whole Image Link', 'premium-addons-pro' ),
                'type'          => Controls_Manager::SWITCHER,
                'condition'	=> array_merge( [
                    'link_switcher' => 'yes',
                ], $condition ),
            ]
        );

        $this->add_control('image_content',
            [
                'label' => __( 'Images', 'premium-addons-pro' ),
                'type' => Controls_Manager::REPEATER,
                'default' => [
                    [
                        'image_title'   => 'Image #1',
                    ],
                    [
                        'image_title'   => 'Image #2',
                    ],
                ],
                'fields' => $accordion_repeater->get_controls(),
                'title_field'   => '{{{ image_title }}}',
            ]
        );
        
        $this->end_controls_section();

        $this->start_controls_section('display_settings',
            [
                'label'         => __('Display Options', 'premium-addons-pro'),
            ]
        );
        
        $this->add_control('default_active',
            [
                'label'        => __('Hovered By Default Index', 'premium-addons-pro'),
                'type'         => Controls_Manager::NUMBER,
                'description'  => __('Set the index for the image to be hovered by default on page load, index starts from 1', 'premium-addons-pro')
            ]
        );
        
        $this->add_control('direction_type',
            [
                'label'        => __('Direction', 'premium-addons-pro'),
                'type'         => Controls_Manager::SELECT,
                'default'      => 'horizontal',
                'options'      => [
                    'horizontal'    => __('Horizontal','premium-addons-pro'),
                    'vertical'      => __('Vertical','premium-addons-pro'),
                ],
                'label_block'  => true,
            ]
        );
        
        $this->add_control('skew',
            [
                'label'     => __( 'Skew Images', 'premium-addons-pro' ),
                'type'      => Controls_Manager::SWITCHER,
                'return_value'  => 'true',
                'condition' => [
                    'direction_type'    => 'horizontal'
                ]
            ]
        );
        
        $this->add_control('skew_direction',
            [
                'label'        => __('Skew Direction', 'premium-addons-pro'),
                'type'         => Controls_Manager::SELECT,
                'default'      => 'right',
                'options'      => [
                    'right'     => __('Right','premium-addons-pro'),
                    'left'      => __('Left','premium-addons-pro'),
                ],
                'label_block'  => true,
                'condition' => [
                    'direction_type'    => 'horizontal',
                    'skew'              => 'true'
                ]
            ]
        );
        
        $this->add_responsive_control('height',
            [
                'label'         => __('Image Height', 'premium-addons-pro'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => [ 'px', 'em', 'vh' ],
                'range'         => [
                    'px'    => [
                        'min'   => 0,
                        'max'   => 1000
                    ]
                ],
                'label_block'   => true,
                'selectors'     => [
                    '{{WRAPPER}} .premium-accordion-section .premium-accordion-li' => 'height: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        
        $this->add_responsive_control('content_position',
            [
                'label'         => __('Content Vertical Position', 'premium-addons-pro'),
                'type'          => Controls_Manager::CHOOSE,
                'options'           => [
                    'flex-start'    => [
                        'title' => __( 'Top', 'premium-addons-pro' ),
                        'icon'  => 'fa fa-arrow-circle-up',
                    ],
                    'center' => [
                        'title' => __( 'Middle', 'premium-addons-pro' ),
                        'icon'  => 'fa fa-align-center',
                    ],
                    'flex-end' => [
                        'title' => __( 'Bottom', 'premium-addons-pro' ),
                        'icon'  => 'fa fa-arrow-circle-down',
                    ],
                ],
                'toggle'        => false,
                'default'       => 'center',
                'selectors'     => [
                    '{{WRAPPER}} .premium-accordion-section .premium-accordion-overlay-wrap' => 'align-items: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control('content_align',
            [
                'label'         => __( 'Content Alignment', 'premium-addons-pro' ),
                'type'          => Controls_Manager::CHOOSE,
                'options'       => [
                    'left'      => [
                        'title'=> __( 'Left', 'premium-addons-pro' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center'    => [
                        'title'=> __( 'Center', 'premium-addons-pro' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right'     => [
                        'title'=> __( 'Right', 'premium-addons-pro' ),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'selectors_dictionary'  => [
                    'left'      => 'flex-start',
                    'center'    => 'center',
                    'right'     => 'flex-end',
                ],
                'default'       => 'center',
                'toggle'        => false,
                'render_type'   => 'template',
                'selectors'     => [
                    '{{WRAPPER}} .premium-accordion-section .premium-accordion-overlay-wrap' => 'justify-content: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control('hide_description_thresold',
            [
                'label'        => __('Hide Description Below Width (PX)', 'premium-addons-pro'),
                'type'         => Controls_Manager::NUMBER,
                'description'  => __('Set screen width below which the description will be hidden', 'premium-addons-pro')
            ]
        );

        $this->end_controls_section();
        
        $this->start_controls_section('image_style',
            [
                'label'         => __('Images', 'premium-addons-pro'),
                'tab'           => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control('overlay_background',
            [
                'label'         => __('Overlay Color', 'premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .premium-accordion-overlay-wrap'  => 'background-color: {{VALUE}};'
                ],
            ]
        );
        
        $this->add_control('overlay_hover_background',
            [
                'label'         => __('Overlay Hover Color', 'premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .premium-accordion-li:hover .premium-accordion-overlay-wrap'  => 'background-color: {{VALUE}};'
                ],
            ]
        );

        $this->start_controls_tabs('images_tabs');

        $this->start_controls_tab('image_tab_normal',
            [
                'label'    => __('Normal', 'premium-addons-pro'),
            ]
        );
        
        $this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'css_filters_normal',
				'selector' => '{{WRAPPER}} .premium-accordion-ul li.premium-accordion-li',
			]
		);
        
        $this->end_controls_tab();

        $this->start_controls_tab('image_tab_hover',
            [
                'label'         => __('Hover', 'premium-addons-pro'),
            ]
        );
        
        $this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'css_filters_hover',
				'selector' => '{{WRAPPER}} .premium-accordion-ul:hover li.premium-accordion-li:hover',
			]
		);
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        $this->end_controls_section();

        $this->start_controls_section('style_settings',
            [
                'label'         => __('Content', 'premium-addons-pro'),
                'tab'           => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('icons_active_tabs');

        $this->start_controls_tab('icons_style_section',
            [
                'label'    => __('Icon', 'premium-addons-pro'),
            ]
        );

        $this->add_responsive_control('icon_size',
            [
                'label'         => __('Size', 'premium-addons-pro'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', 'em'],
                'range'         => [
                    'px'    => [
                        'min'   => 0,
                        'max'   => 500
                    ],
                    'em'    => [
                        'min'   => 0,
                        'max'   => 20
                    ]
                ],
                'selectors'     => [
                    '{{WRAPPER}} i.premium-accordion-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} svg.premium-accordion-icon, {{WRAPPER}} .premium-lottie-animation'    => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}'
                ]
            ]
        );

        $this->add_control('icon_color',
            [
                'label'         => __('Color', 'premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'scheme' => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-accordion-section .premium-accordion-icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control('icon_hover_color',
            [
                'label'         => __('Hover Color', 'premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'scheme' => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-accordion-icon:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control('icon_background_color',
            [
                'label'         => __('Background Color', 'premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .premium-accordion-icon' => 'background-color: {{VALUE}};',
                ],
            ]
        );
                
        $this->add_control('icon_background_hover_color',
            [
                'label'         => __('Background Hover Color ', 'premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .premium-accordion-icon:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'          => 'icon_shadow',
                'selector'      => '{{WRAPPER}} .premium-accordion-icon'
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(), 
            [
                'name'          => 'icon_border',
                'selector'      => '{{WRAPPER}} .premium-accordion-section .premium-accordion-icon'
            ]
        );
              
        $this->add_control('icon_border_radius',
            [
                'label'         => __('Border Radius', 'premium-addons-pro'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%' ,'em'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-accordion-section .premium-accordion-icon' => 'border-radius: {{SIZE}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control('icon_margin',
            [
                'label'         => __('Margin', 'premium-addons-pro'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-accordion-section .premium-accordion-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control('icon_padding',
            [
                'label'         => __('Padding', 'premium-addons-pro'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-accordion-section .premium-accordion-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );
    
        $this->end_controls_tab();

        $this->start_controls_tab('titles_style_section',
            [
                'label'         => __('Title', 'premium-addons-pro'),
            ]
        );

        $this->add_control('title_color',
            [
                'label'         => __('Color', 'premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'scheme' => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-accordion-section .premium-accordion-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .premium-accordion-section .premium-accordion-title',
            ]
        );
                    
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'          => 'title_shadow',
                'selector'      => '{{WRAPPER}} .premium-accordion-section .premium-accordion-title',
            ]
        );
                             
        $this->add_responsive_control('title_margin',
            [
                'label'         => __('Margin', 'premium-addons-pro'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-accordion-section .premium-accordion-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );
                    
        $this->add_responsive_control('title_padding',
            [
                'label'         => __('Padding', 'premium-addons-pro'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-accordion-section .premium-accordion-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab('descriptions_style_section',
            [
                'label'         => __('Description', 'premium-addons-pro'),
            ]
        );

        $this->add_control('description_color',
            [
                'label'         => __('Color', 'premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'scheme' => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-accordion-section .premium-accordion-description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .premium-accordion-section .premium-accordion-description',
            ]
        );
    
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'          => 'description_shadow',
                'selector'      => '{{WRAPPER}} .premium-accordion-section .premium-accordion-description',
            ]
        );

        $this->add_responsive_control('description_margin',
            [
                'label'         => __('Margin', 'premium-addons-pro'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => [ 'px', 'em', '%' ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-accordion-section .premium-accordion-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ]
            ]
        );
        
        $this->add_responsive_control('description_padding',
            [
                'label'         => __('Padding', 'premium-addons-pro'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => [ 'px', 'em', '%' ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-accordion-section .premium-accordion-description' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ]
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_section();

        $this->start_controls_section('Link_style',
            [
                'label'         => __('Link', 'premium-addons-pro'),
                'tab'           => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control('link_color',
            [
                'label'         => __('Color', 'premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'scheme' => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-accordion-section .premium-accordion-item-link-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control('link_hover_color',
            [
                'label'         => __('Hover Color', 'premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'scheme' => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-accordion-section .premium-accordion-item-link-title:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'link_typography',
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .premium-accordion-section .premium-accordion-item-link-title',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section('container_style',
            [
                'label'         => __('Container', 'premium-addons-pro'),
                'tab'           => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(), 
            [
                'name'          => 'container_border',
                'selector'      => '{{WRAPPER}} .premium-accordion-section',
            ]
        );

        $this->add_control('container_border_radius',
            [
                'label'         => __('Border Radius', 'premium-addons-pro'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%' ,'em'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-accordion-section' => 'border-radius: {{SIZE}}{{UNIT}};'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'          => 'container_shadow',
                'selector'      => '{{WRAPPER}} .premium-accordion-section',
            ]
        );

        $this->add_responsive_control('container_margin',
            [
                'label'         => __('Margin', 'premium-addons-pro'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => [ 'px', 'em', '%' ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-accordion-section' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ]
            ]
        );

        $this->end_controls_section();

    }
    
    /**
	 * Render Image Accordion widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
    protected function render() {
        
        $settings           = $this->get_settings_for_display();
        
        $id                 = $this->get_id();
        
        $accordion_settings = [
            'hide_desc'     => $settings['hide_description_thresold'],
        ];
        
        $direction = 'premium-accordion-' . $settings['direction_type'];
        
        $this->add_render_attribute('accordion', 'class', 'premium-accordion-section');
        
        if( $settings['skew'] && 'horizontal' === $settings['direction_type'] ) {
            $this->add_render_attribute('accordion', 'class', 'premium-accordion-skew' );
            $this->add_render_attribute('accordion', 'data-skew', $settings['skew_direction'] );
        }
        
        $this->add_render_attribute('accordion', 'id', 'premium-accordion-section-' . $id );
        
        $this->add_render_attribute('accordion', 'data-settings', wp_json_encode( $accordion_settings ) );
       
        $this->add_render_attribute('accordion_wrap', 'class', $direction );
        
        $this->add_render_attribute('accordion_list', 'class', [
            'premium-accordion-ul',
            'premium-accordion-' . $settings['content_position']
        ] );
       
        $this->add_render_attribute( 'content', 'class', [ 'premium-accordion-content', 'premium-accordion-' . $settings['content_align'] ] );
        
        ?>
           <div class="premium-accordion-container">
                <div <?php echo $this->get_render_attribute_string('accordion'); ?>>
                    <div <?php echo $this->get_render_attribute_string('accordion_wrap'); ?>>
                        <ul <?php echo $this->get_render_attribute_string('accordion_list'); ?>>
                            <?php foreach ( $settings['image_content'] as $index => $item ) :
                                
                                $title       = $this->get_repeater_setting_key('image_title','image_content', $index);

                                $description = $this->get_repeater_setting_key('image_desc','image_content', $index);

                                $this->add_render_attribute($title, 'class', 'premium-accordion-title');

                                $this->add_inline_editing_attributes($title,'none');

                                $this->add_render_attribute($description, 'class', 'premium-accordion-description');

                                $this->add_inline_editing_attributes( $description, 'basic' );

                                $item_link = 'link_' . $index;

                                $separator_link_type = $item['link_type'];

                                $link_url = ( 'url' ===  $separator_link_type ) ? $item['link']['url'] : get_permalink( $item['existing_link'] );

                                if ( $item['link_switcher'] === 'yes' ) {
                                    
                                    $this->add_render_attribute( $item_link, 'class', 'premium-accordion-item-link' );

                                    if( ! empty( $item['link']['is_external'] ) ) {
                                        $this->add_render_attribute( $item_link, 'target', "_blank" );
                                    }

                                    if( ! empty( $item['link']['nofollow'] ) ) {
                                        $this->add_render_attribute( $item_link, 'rel',  "nofollow" );
                                    }

                                    if( ! empty( $item['link_title'] ) ) {
                                        $this->add_render_attribute( $item_link, 'title', $item['link_title'] );
                                    }

                                    if( ! empty( $item['link']['url'] ) || ! empty( $item['existing_link'] ) ) {
                                        $this->add_render_attribute( $item_link, 'href',  $link_url );
                                    }
                                }
                                
                                $list_item_key = 'img_index_' . $index;
                                
                                $this->add_render_attribute( $list_item_key, 'class',
                                    [
                                        'premium-accordion-li',
                                        'elementor-repeater-item-' . $item['_id']
                                    ]
                                );
                                
                                if ( ! empty( $settings['default_active'] ) && ( $settings['default_active'] - 1 )=== $index ) {
                                    
                                    $this->add_render_attribute( $list_item_key, 'class', 'premium-accordion-li-active' );
                                    
                                }

                                if ( $item['content_switcher'] === 'yes' && $item['icon_switcher'] === 'yes') {
                                    if( $item['icon_type'] === 'animation' ) {

                                        $lottie_key = 'icon_lottie_' . $index;

                                        $this->add_render_attribute( $lottie_key, [
                                            'class' => [
                                                'premium-accordion-icon',
                                                'premium-lottie-animation'
                                            ],
                                            'data-lottie-url' => $item['lottie_url'],
                                            'data-lottie-loop' => $item['lottie_loop'],
                                            'data-lottie-reverse' => $item['lottie_reverse']
                                        ]);
                                    }
                                }
                                
                                ?>
                            
                                <li <?php echo $this->get_render_attribute_string( $list_item_key ); ?>>
                                    <?php if ( ! $settings['skew'] || 'vertical' === $settings['direction_type'] ) : ?>
                                        <div class="premium-accordion-background"></div>
                                    <?php endif; ?>
                                    <?php if ( $item['link_switcher'] === 'yes' && $item['link_whole'] === 'yes' ) : ?>
                                        <a <?php echo $this->get_render_attribute_string ( $item_link ); ?>></a>
                                   <?php endif?>
                                   
                                    <div class="premium-accordion-overlay-wrap">
                                        <?php if ( $item['content_switcher'] === 'yes' ) : ?>
                                        <div <?php echo $this->get_render_attribute_string('content'); ?>>

                                            <?php if ( $item['icon_switcher'] === 'yes' ) :
                                                if ( $item['icon_type'] === 'icon' ) :

                                                    $icon_migrated = isset( $item['__fa4_migrated']['icon_updated'] );
                                                    $icon_new = empty( $item['icon'] ) && Icons_Manager::is_migration_allowed();
                                                    
                                                    if ( $icon_new || $icon_migrated ) :
                                                        Icons_Manager::render_icon( $item['icon_updated'], [ 'class' => 'premium-accordion-icon', 'aria-hidden' => 'true' ] );
                                                    else: ?>
                                                        <i class="<?php echo $item['icon']; ?>"></i>
                                                    <?php endif; ?>
                                                <?php else : ?>
                                                    <div <?php echo $this->get_render_attribute_string( $lottie_key ); ?>></div>    
                                                <?php endif ?> 
                                            <?php endif; ?>

                                            <?php if(! empty($item['image_title'])) : ?>
                                                <h3 <?php echo $this->get_render_attribute_string( $title ); ?>>
                                                    <?php echo $item['image_title'] ?>
                                                </h3>
                                            <?php endif ?>
                                            <?php if( ! empty( $item['image_desc'] ) ) : ?>
                                                <div <?php echo $this->get_render_attribute_string ( $description ); ?>><?php echo $item['image_desc']; ?></div>
                                            <?php endif ?> 
                                            <?php if ( $item['link_switcher'] === 'yes'  && $item['link_whole'] !== 'yes' ) : ?>
                                                <a class="premium-accordion-item-link-title" <?php echo $this->get_render_attribute_string ( $item_link ); ?>>
                                            <?php echo $item['link_title']; ?>
                                                </a>
                                            <?php endif;?>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        <?php
    }
    
    /**
	 * Render Image Accordion widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
    protected function content_template() {
        
        ?>
        <#
        
            var accordionSetting = {};
            
                accordionSetting.hide_desc = settings.hide_description_thresold;
                
            var direction = 'premium-accordion-' + settings.direction_type;
            
            view.addRenderAttribute('accordion', 'class', 'premium-accordion-section');
            view.addRenderAttribute('accordion', 'id', 'premium-accordion-section-'+ view.getIDInt() );
            view.addRenderAttribute('accordion', 'data-settings', JSON.stringify( accordionSetting ) );
            
            if( settings.skew && 'horizontal' === settings.direction_type ) {
                view.addRenderAttribute('accordion', 'class', 'premium-accordion-skew' );
                view.addRenderAttribute('accordion', 'data-skew', settings.skew_direction );
            }
            
            view.addRenderAttribute('accordion_wrap', 'class', direction );
            
            view.addRenderAttribute('accordion_list', 'class', [
                'premium-accordion-ul',
                'premium-accordion-' + settings.content_position
            ] );
            
            view.addRenderAttribute('content', 'class', [ 'premium-accordion-content', 'premium-accordion-' + settings.content_align ] );
            
        #>
            <div class="premium-accordion-container">
                <div {{{ view.getRenderAttributeString( 'accordion' ) }}}>
                    <div {{{ view.getRenderAttributeString( 'accordion_wrap' ) }}}>
                        <ul {{{ view.getRenderAttributeString( 'accordion_list' ) }}}>
                            <# 
                            _.each( settings.image_content, function( item , index ) { 
                                
                                var title       = view.getRepeaterSettingKey( 'image_title', 'image_content', index );
                                var description = view.getRepeaterSettingKey( 'image_desc', 'image_content', index );
                                
                                view.addRenderAttribute( title, 'class', 'premium-accordion-title');
                                view.addInlineEditingAttributes( title,'none' );
                                
                                view.addRenderAttribute(description, 'class', 'premium-accordion-description');
		                        view.addInlineEditingAttributes(description, 'basic' );
                                
                                var itemLink = 'link_' + index;
                                var separatorLinkType, linkUrl, linkTitle;
                                
                                separatorLinkType = item.link_type;
                                linkTitle = item.link_title;
                                linkUrl= 'url' ===  separatorLinkType  ? item.link.url : item.existing_link;
                                
                                if( 'yes' === item.link_switcher ) {
                                    view.addRenderAttribute(itemLink, 'class', 'premium-accordion-item-link');
                                    if( '' != item.link.is_external ) {
                                        view.addRenderAttribute(itemLink, 'target', '_blank');
                                    }
                                    if( '' != item.link.nofollow ) {
                                        view.addRenderAttribute(itemLink, 'rel', 'nofollow');
                                    }
                                    if( '' != item.link_title ) {
                                        view.addRenderAttribute(itemLink, 'title', linkTitle);
                                    }
                                    if( ('' != item.link.url) || ('' != item.existing_link) ) {
                                        view.addRenderAttribute(itemLink, 'href', linkUrl);
                                    }
                                }
                            
                                var listItemKey = 'img_index_' + index;
                                
                                view.addRenderAttribute( listItemKey, 'class',
                                    [ 
                                        'premium-accordion-li' ,
                                        'elementor-repeater-item-' + item._id
                                    ]
                                );
                                
                                if ( '' !== settings.default_active && ( settings.default_active - 1 ) === index ) {
                                    
                                    view.addRenderAttribute( listItemKey, 'class', 'premium-accordion-li-active' );
                                    
                                }

                                var imageObj = {
                                    id       : item.image.id,
                                    url      : item.image.url,
                                    size     : item.thumbnail_size,
                                    dimension: item.thumbnail_custom_dimension,
                                    model    : view.getEditModel()
                                };
                                
                               var imageUrl  = elementor.imagesManager.getImageUrl( imageObj );

                               if ( item.content_switcher === 'yes' && item.icon_switcher === 'yes' ) {
                                    if( item.icon_type === 'animation' ) {

                                        var lottieKey = 'icon_lottie_' + index;

                                        view.addRenderAttribute( lottieKey, 'class', [ 'premium-accordion-icon', 'premium-lottie-animation' ] );

                                        view.addRenderAttribute( lottieKey, 'data-lottie-url', item.lottie_url );
                                        view.addRenderAttribute( lottieKey, 'data-lottie-loop', item.lottie_loop );
                                        view.addRenderAttribute( lottieKey, 'data-lottie-reverse', item.lottie_reverse );

                                    }
                                }
                               
                            #>
                                <li {{{ view.getRenderAttributeString( listItemKey ) }}}>
                                    <# if( ! settings.skew || 'vertical' === settings.direction_type ) { #>
                                        <div class="premium-accordion-background"></div>
                                    <# } #>
                                    <# if( item.link_switcher === 'yes' && item.link_whole === 'yes' ) { #>
                                        <a {{{ view.getRenderAttributeString( itemLink ) }}}></a>
                                    <# } #>
                                    
                                    <div class="premium-accordion-overlay-wrap">
                                        <# if( item.content_switcher === 'yes' ) { #>
                                        <div {{{ view.getRenderAttributeString( 'content' ) }}}>

                                        <# if( item.icon_switcher === 'yes' ) {
                                            if( item.icon_type === 'icon' ) {

                                                var listIconHTML = elementor.helpers.renderIcon( view, item.icon_updated, { 'class': 'premium-accordion-icon', 'aria-hidden': true }, 'i' , 'object' ),
                                                    listIconMigrated = elementor.helpers.isIconMigrated( item, 'icon_updated' );

                                                if ( listIconHTML && listIconHTML.rendered && ( ! item.icon || listIconMigrated ) ) { #>
                                                    {{{ listIconHTML.value }}}
                                                <# } else { #>
                                                    <i class="premium-accordion-icon {{ item.icon }}" aria-hidden="true"></i>
                                                <# } #>
                                            <# } else { #>
                                                <div {{{ view.getRenderAttributeString( lottieKey ) }}}></div>
                                            <# } #>
                                        <# } #>

                                        <# if( '' != item.image_title ) { #>
                                           <h3 {{{ view.getRenderAttributeString( title ) }}} >{{{item.image_title}}}</h3>
                                        <# } #>   
                                        <# if( '' != item.image_desc ) { #>
                                            <div  {{{ view.getRenderAttributeString( description ) }}}>{{{item.image_desc}}}</div> 
                                        <# } #>
                                        <# if( 'yes' === item.link_switcher && 'yes' !== item.link_whole ) { #>
                                            <a class="premium-accordion-item-link-title" {{{ view.getRenderAttributeString( itemLink ) }}}>
                                                {{{item.link_title}}}
                                            </a>
                                        <# } #>
                                        </div>
                                        <# } #>
                                    </div>
                                    
                                </li>     
                            <# }) #>
                        </ul>
                    </div>
                </div>
            </div>      
        <?php
    }
}
