<?php

/**
 * Class: Premium_Tabs
 * Name: Tabs
 * Slug: premium-addon-tabs
 */
namespace PremiumAddonsPro\Widgets;

// Elementor Classes.
use Elementor\Icons_Manager;
use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Repeater;
use Elementor\Controls_Manager;
use Elementor\Control_Media;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;

// PremiumAddons Classes.
use PremiumAddons\Includes\Helper_Functions;
use PremiumAddons\Includes\Premium_Template_Tags;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Class Premium_Tabs
 */
class Premium_Tabs extends Widget_Base {

    public function getTemplateInstance() {
        return $this->templateInstance = Premium_Template_Tags::getInstance();
    }

    public function get_name() {
        return 'premium-addon-tabs';
    }

    public function get_title() {
        return sprintf( '%1$s %2$s', Helper_Functions::get_prefix(), __('Tabs', 'premium-addons-pro') );
    }

    public function get_icon() {
        return 'pa-pro-tabs';
    }

    public function is_reload_preview_required() {
        return true;
    }

    public function get_script_depends() {
        return [
            'tabs-slick',
            'premium-pro-js',
            'lottie-js',
        ];
    }

    public function get_categories() {
        return [ 'premium-elements' ];
    }

    public function get_custom_help_url() {
        return 'https://premiumaddons.com/support/';
    }

    /**
	 * Register Tabs controls.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
    protected function _register_controls() {

        $this->start_controls_section('premium_tabs',
            [
                'label'         => __('Tabs', 'premium-addons-pro'),
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control('premium_tabs_icon_switcher',
            [
                'label'         => __( 'Icon', 'premium-addons-pro' ),
                'type'          => Controls_Manager::SWITCHER,
                'default'       => 'yes',  
            ]
        );

        $repeater->add_control('icon_type',
            [
                'label'			=> __( 'Icon Type', 'premium-addons-pro' ),
                'type' 			=> Controls_Manager::SELECT,
                'options'		=> [
                    'icon'          => __('Icon', 'premium-addons-pro'),
                    'image'         => __('Image', 'premium-addons-pro'),
                    'animation'     => __('Lottie Animation', 'premium-addons-pro')
                ],
                'default'		=> 'icon',
                'condition'     => [
                    'premium_tabs_icon_switcher' => 'yes'
                ]
            ]
        );

        $repeater->add_control('premium_tabs_icon_updated',
            [
                'label'         => __( 'Icon', 'premium-addons-pro' ),
                'type'          => Controls_Manager::ICONS,
                'fa4compatibility'  => 'premium_tabs_icon',
                'default'       => [
                    'value'     => 'fas fa-star',
                    'library'   => 'fa-solid',
                ],
                'label_block'   => true,
                'condition'     => [
                    'premium_tabs_icon_switcher' => 'yes',
                    'icon_type'                  => 'icon'
                ]
            ]
        );

        $repeater->add_control('image_upload',
            [
                'label'			=> __( 'Upload Image', 'premium-addons-pro' ),
                'type' 			=> Controls_Manager::MEDIA,
                'default'		=> [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'condition'			=> [
                    'premium_tabs_icon_switcher' => 'yes',
                    'icon_type'                  => 'image'
                ],
            ]
        );
        
        $repeater->add_control('lottie_url', 
            [
                'label'             => __( 'Animation JSON URL', 'premium-addons-pro' ),
                'type'              => Controls_Manager::TEXT,
                'dynamic'           => [ 'active' => true ],
                'description'       => 'Get JSON code URL from <a href="https://lottiefiles.com/" target="_blank">here</a>',
                'label_block'       => true,
                'condition'         => [
                    'icon_type'         => 'animation',
                ]
            ]
        );

        $repeater->add_control('lottie_loop',
            [
                'label'         => __('Loop','premium-addons-pro'),
                'type'          => Controls_Manager::SWITCHER,
                'return_value'  => 'true',
                'default'       => 'true',
                'condition'     => [
                    'icon_type'     => 'animation',
                ]
            ]
        );

        $repeater->add_control('lottie_reverse',
            [
                'label'         => __('Reverse','premium-addons-pro'),
                'type'          => Controls_Manager::SWITCHER,
                'return_value'  => 'true',
                'condition'     => [
                    'icon_type'     => 'animation',
                ]
            ]
        );

        $repeater->add_control('premium_tabs_title',
            [
                'label'         => __( 'Title', 'premium-addons-pro' ),
                'default'       => __('Title','premium-addons-pro'),
                'type'          => Controls_Manager::TEXT,
                'dynamic'        => [ 'active' => true ],
                'label_block'   => true,
            ]
        );

        $repeater->add_control('premium_tabs_content', 
            [
                'label'         => __('Content to Show', 'premium-addons-pro'),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'text_editor'           => __('Text Editor', 'premium-addons-pro'),
                    'elementor_templates'   => __('Elementor Template', 'premium-addons-pro'),
                ],
                'default'       => 'text_editor'
            ]
        );

        $repeater->add_control('premium_tabs_content_text',
            [ 
                'type'          => Controls_Manager::WYSIWYG,
                'default'       => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
                'label_block'   => true,
                'dynamic'       => [ 'active' => true ],
                'condition'     => [
                    'premium_tabs_content'  => 'text_editor',
                ],
            ]
        );
        
        $repeater->add_control('premium_tabs_content_temp',
            [
                'label'         => __( 'Elementor Template', 'premium-addons-pro' ),
                'description'   => __( 'Elementor Template is a template which you can choose from Elementor library. Each template will be shown in content', 'premium-addons-pro' ),
                'type'          => Controls_Manager::SELECT2,
                'options'       => $this->getTemplateInstance()->get_elementor_page_list(),
                'label_block'   => true,
                'condition'     => [
                    'premium_tabs_content'  => 'elementor_templates',
                ],
            ]
        );

        $repeater->add_control('custom_tab_navigation',
            [
                'label'			=> __( 'Custom Navigation Element Selector', 'premium-addons-pro' ),
                'type'			=> Controls_Manager::TEXT,
                'label_block'	=> true,
                'description'	=> __( 'Use this to add an element selector to be used to navigate to this tab. For example #tab-1', 'premium-addons-for-elementor' ),
            ]
        );

        $this->add_control('premium_tabs_repeater',
            [
                'label'          => __( 'Tabs', 'premium-addons-pro' ),
                'type'           => Controls_Manager::REPEATER,
                'fields'         => array_values( $repeater->get_controls() ),
                'title_field'    => '{{{ premium_tabs_title }}}',
            ]
        );

        $this->add_control('default_tab_index', 
            [
                'label'     => __('Default Tab Index', 'premium-addons-pro'),
                'type'      => Controls_Manager::NUMBER,
                'description'=> __('Set to -1 if you don\'t any tab to be active by default.','premium-addons-pro'),
                'default'   => 0,
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section('premium_tab_additional_settings',
            [
                'label'         => __('Additional Settings', 'premium-addons-pro'),
            ]
        );

        $this->add_control('premium_tab_type',
            [
                'label'         => __('Tabs Type', 'premium-addons-pro'),
                'type'          => Controls_Manager::SELECT,
                'default'       => 'horizontal',
                'options'       => [
                    'horizontal' => __('Horizontal','premium-addons-pro'),
                    'vertical'   => __('Vertical','premium-addons-pro'),
                ],
                'label_block'   => true,
            ]
        );

        $this->add_control('layout',
            [
                'label'         => __('Tabs Layout', 'premium-addons-pro'),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'wrap' => __('Auto','premium-addons-pro'),
                    'nowrap'   => __('Fixed','premium-addons-pro'),
                ],
                'default'       => 'wrap',
                'label_block'   => true,
                'selectors'     => [
                    '{{WRAPPER}} .premium-tabs-nav-list' => 'flex-wrap: {{VALUE}}'
                ],
                'condition'     => [
                    'premium_tab_type'  => 'horizontal'
                ],
            ]
        );

        $this->add_control('premium_tab_style_selected',
            [
                'label'         => __('Tabs Style', 'premium-addons-pro'),
                'type'          => Controls_Manager::SELECT,
                'default'       => 'style1',
                'options'       => [
                    'style1'        => __('Arrow Pointer', 'premium-addons-pro'),
                    'style2'        => __('Circled', 'premium-addons-pro'),
                    'style3'        => __('Flipped', 'premium-addons-pro'),
                    'style4'        => __('Folded', 'premium-addons-pro'),
                ],
                'label_block'   => true,
            ]
        );

        $this->add_control('carousel_tabs',
            [
                'label'         => __( 'Carousel Tabs', 'premium-addons-pro' ),
                'type'          => Controls_Manager::SWITCHER,
                'return_value'  => 'true',
                'condition'     => [
                    'premium_tab_type'  => 'horizontal'
                ],
            ]
        );

        $this->add_control('carousel_tabs_devices',
            [
                'label'             => __('Apply Carousel Tabs On', 'premium-addons-pro'),
                'type'              => Controls_Manager::SELECT2,
                'options'           => [
                    'desktop'   => __('Desktop','premium-addons-pro'),
                    'tablet'    => __('Tablet','premium-addons-pro'),
                    'mobile'    => __('Mobile','premium-addons-pro'),
                ],
                'default'           => [ 'desktop', 'tablet', 'mobile' ],
                'multiple'          => true,
                'label_block'       => true,
                'condition'     => [
                    'premium_tab_type'  => 'horizontal',
                    'carousel_tabs'     => 'true'
                ],
            ]
        );

        $this->add_control('tabs_notice_1',
            [
                'raw'               => __('Please note it\'s recommend to set carousel tabs to an odd value', 'premium-addons-pro'),
                'type'              => Controls_Manager::RAW_HTML,
                'content_classes'   => 'elementor-panel-alert elementor-panel-alert-info',
                'condition'     => [
                    'premium_tab_type'  => 'horizontal',
                    'carousel_tabs'     => 'true'
                ],
            ] 
        );

        $this->add_responsive_control('tabs_number', 
            [
                'label'     => __('Tabs To Show', 'premium-addons-pro'),
                'type'      => Controls_Manager::NUMBER,
                'desktop_default'   => 5,
                'tablet_default'    => 3,
                'mobile_default'    => 1,
                'condition'     => [
                    'premium_tab_type'  => 'horizontal',
                    'carousel_tabs'     => 'true'
                ],
            ]
        );

        $this->add_responsive_control('slides_spacing',
			[
				'label' 		=> __( 'Slides\' Spacing', 'premium-addons-pro' ),
                'description'   => __('Use this option to change carousel tabs width in pixels (px)', 'premium-addons-pro'),
				'type'			=> Controls_Manager::NUMBER,
                'default'		=> '15',
                'condition'     => [
                    'premium_tab_type'  => 'horizontal',
                    'carousel_tabs'     => 'true'
                ],
			]
		);

        $this->add_control('tabs_notice_2', 
            [
                'raw'               => __('Make sure that tabs to show is set to a smaller value than the number of tabs.', 'premium-addons-pro'),
                'type'              => Controls_Manager::RAW_HTML,
                'content_classes'   => 'elementor-panel-alert elementor-panel-alert-info',
                'condition'     => [
                    'premium_tab_type'  => 'horizontal',
                    'carousel_tabs'     => 'true'
                ],
            ] 
        );

        $this->end_controls_section();

        $this->start_controls_section('section_pa_docs',
            [
                'label'         => __('Helpful Documentations', 'premium-addons-pro'),
            ]
        );

        $docs = [
            'https://premiumaddons.com/docs/tabs-widget-tutorial/' => 'Getting started »',
            'https://premiumaddons.com/docs/elementor-tabs-custom-navigation/' => 'How to navigate through tabs using any element on your page »',
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

        $this->start_controls_section('premium_tabs_style',
            [
                'label'         => __('Tab', 'premium-addons-pro'),
                'tab'           => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control('premium_tabs_content_width',
            [
                'label'          => __('Tabs Sections Width (%)','premium-addons-pro'),
                'type'          => Controls_Manager::SLIDER,
                'condition'     => [
                    'premium_tab_type'  => 'vertical'
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-tabs .premium-content-wrap.premium-tabs-vertical' => 'width: {{SIZE}}%;'
                ]
            ]
        );

        $this->start_controls_tabs( 'premium_tab_style' );
        
        $this->start_controls_tab('premium_tab_style_normal',
            [
                'label'             => __('Normal', 'premium-addons-pro'),
            ]
            );
        
        $this->add_control('premium_tab_active_border_color',
            [
                'label'         => __('Border Color', 'premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'scheme' => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_3,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-tabs-style-iconbox ul.premium-tabs-horizontal li::after, {{WRAPPER}} .premium-tabs-style-iconbox ul.premium-tabs-vertical li::after, {{WRAPPER}} .premium-tabs-style-flip ul.premium-tabs-horizontal li::after, {{WRAPPER}} .premium-tabs-style-flip ul.premium-tabs-vertical li::after' => 'background-color: {{VALUE}};',
                ],
                'condition'      => [
                    'premium_tab_style_selected' => [ 'style1', 'style3' ]
                ]
            ]
        );
        
    $this->add_control('premium_tab_background_color',
        [
            'label'         => __('Background Color', 'premium-addons-pro'),
            'type'          => Controls_Manager::COLOR,
            'scheme' => [
                'type'  => Scheme_Color::get_type(),
                'value' => Scheme_Color::COLOR_1,
            ],
            'selectors'     => [
                '{{WRAPPER}} .premium-tabs-style-iconbox .premium-tabs-nav ul li a,{{WRAPPER}} .premium-tabs-style-circle .premium-tabs-nav ul li a,{{WRAPPER}} .premium-tabs-style-flip .premium-tabs-nav li, {{WRAPPER}} .premium-tabs-style-tzoid .premium-tabs-nav ul li a::after' => 'background-color: {{VALUE}};',
            ],
        ]
    );
        
        $this->add_group_control(
            Group_Control_Border::get_type(), 
            [
                'name'          => 'premium_tab_border',
                'selector'      => '{{WRAPPER}} .premium-tabs .premium-tab-link',
            ]
        );
        
        $this->add_control('premium_tab_border_radius',
            [
                'label'         => __('Border Radius', 'premium-addons-pro'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%' ,'em'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-tabs .premium-tab-link' => 'border-radius: {{SIZE}}{{UNIT}};'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'          => 'premium_tab_tab_box_shadow',
                'selector'      => '{{WRAPPER}} .premium-tabs .premium-tab-link',
            ]
        );

        $this->add_responsive_control('premium_tab_margin',
            [
                'label'         => __('Margin', 'premium-addons-pro'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => [ 'px', 'em', '%' ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-tabs li.premium-tabs-nav-list-item .premium-tab-link, {{WRAPPER}} .premium-tabs-style-tzoid .premium-tabs-nav .premium-tabs-nav-list.premium-tabs-horizontal li:first-child .premium-tab-link' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ]
            ]
        );
        
        $this->add_responsive_control('premium_tab_padding',
                [
                    'label'         => __('Padding', 'premium-addons-pro'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => [ 'px', 'em', '%' ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-tabs .premium-tab-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                        ]
                    ]
                );

        $this->end_controls_tab();

        $this->start_controls_tab('premium_tab_style_hover',
            [
                'label'             => __('Hover', 'premium-addons-pro'),
            ]
        );
        
        $this->add_control('premium_tab_hover_background_color',
            [
                'label'         => __('Background Color', 'premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'scheme' => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'condition'      => [
                    'premium_tab_style_selected!' => ['style2', 'style3'],
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-tabs-style-iconbox .premium-tabs-nav ul li a:hover,{{WRAPPER}} .premium-tabs-style-tzoid .premium-tabs-nav ul li a:hover:after' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .premium-tabs-style-iconbox .premium-tabs-nav ul.premium-tabs-horizontal li.tab-current a:hover:after'    => 'border-top-color: {{VALUE}}',
                    '{{WRAPPER}} .premium-tabs-style-iconbox .premium-tabs-nav ul.premium-tabs-vertical li.tab-current a:hover:after'    => 'border-left-color: {{VALUE}}'
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(), 
                [
                    'name'          => 'premium_tab_hover_border',
                    'selector'      => '{{WRAPPER}} .premium-tabs .premium-tab-link:hover',
                    ]
                );
        
        $this->add_control('premium_tab_hover_border_radius',
            [
                'label'         => __('Border Radius', 'premium-addons-pro'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%' ,'em'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-tabs .premium-tab-link:hover' => 'border-radius: {{SIZE}}{{UNIT}};'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
                [
                    'name'          => 'premium_tab_hover_box_shadow',
                    'selector'      => '{{WRAPPER}} .premium-tabs .premium-tab-link:hover',
                ]
                );

        $this->add_responsive_control('premium_tab_hover_margin',
            [
                'label'         => __('Margin', 'premium-addons-pro'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => [ 'px', 'em', '%' ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-tabs .premium-tab-link:hover, {{WRAPPER}} .premium-tabs-style-tzoid .premium-tabs-nav .premium-tabs-nav-list.premium-tabs-horizontal li:first-child .premium-tab-link:hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ]
            ]
        );
        
        $this->add_responsive_control('premium_tab_hover_padding',
            [
                'label'         => __('Padding', 'premium-addons-pro'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => [ 'px', 'em', '%' ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-tabs .premium-tab-link:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ]
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab('premium_tab_style_active',
            [
                'label'             => __('Active', 'premium-addons-pro'),
            ]
        );

        $this->add_control('premium_tab_active_background_color',
            [
                'label'         => __('Background Active Color', 'premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'scheme' => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_3,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-tabs-style-iconbox .premium-tabs-nav ul li.tab-current a,{{WRAPPER}} .premium-tabs-style-circle .premium-tabs-nav ul li.tab-current a,{{WRAPPER}} .premium-tabs-style-flip .premium-tabs-nav li.tab-current a::after, {{WRAPPER}} .premium-tabs-style-tzoid .premium-tabs-nav ul li.tab-current a::after' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .premium-tabs-style-iconbox .premium-tabs-nav ul.premium-tabs-horizontal li.tab-current a:hover:after'    => 'border-top-color: {{VALUE}}',
                    '{{WRAPPER}} .premium-tabs-style-iconbox .premium-tabs-nav ul.premium-tabs-vertical li.tab-current a:hover:after'  => 'border-left-color: {{VALUE}};'
                ],
            ]
        );

        $this->add_control('premium_tab_active_arrow_color',
            [
                'label'          => __('Arrow Color', 'premium-addons-pro'),
                'type'           => Controls_Manager::COLOR,
                'scheme'         => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'selectors'      => [
                    '{{WRAPPER}} .premium-tabs-style-iconbox .premium-tabs-nav ul.premium-tabs-horizontal li.tab-current a::after' => 'border-top-color: {{VALUE}};',
                ],
                'condition'      => [
                    'premium_tab_style_selected' => 'style1',
                    'premium_tab_type'           => 'horizontal'
                ]
            ]
        );
        
        $this->add_control('premium_tab_active_arrow_color_vertical',
            [
                'label'          => __('Arrow Color', 'premium-addons-pro'),
                'type'           => Controls_Manager::COLOR,
                'scheme'         => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'selectors'      => [
                    '{{WRAPPER}} .premium-tabs-style-iconbox .premium-tabs-nav ul.premium-tabs-vertical li.tab-current a::after' => 'border-left-color: {{VALUE}};',
                ],
                'condition'      => [
                    'premium_tab_style_selected' => 'style1',
                    'premium_tab_type'           => 'vertical'
                ]
            ]
        );
        
        $this->add_responsive_control('premium_tab_active_circle_color',
            [
                'label'          => __('Circle Size', 'premium-addons-pro'),
                'type'           => Controls_Manager::SLIDER,
                'size_units'     => ['px','em','%'],
                'range'          => [
                    'px' => [
                        'min' => 1,
                        'max' => 200,
                    ],
                ],
                'default'        => [
                    'unit'   => 'px',
                    'size'   => 75
                ],
                'selectors'      => [
                    '{{WRAPPER}} .premium-tabs-style-circle .premium-tabs-nav li::before' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
                'condition'      => [
                    'premium_tab_style_selected' => 'style2'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(), 
            [
                'name'              => 'premium_tab_active_circle_border',
                'selector'          => '{{WRAPPER}} .premium-tabs-style-circle .premium-tabs-nav li::before',
                'condition'      => [
                    'premium_tab_style_selected' => 'style2'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'          => 'premium_tab_tab_box_shadow_active',
                'selector'      => '{{WRAPPER}} .premium-tabs .tab-current .premium-tab-link',
            ]
        );

        $this->add_responsive_control('premium_tab_active_margin',
            [
                'label'         => __('Margin', 'premium-addons-pro'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => [ 'px', 'em', '%' ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-tabs .tab-current .premium-tab-link, {{WRAPPER}} .premium-tabs-style-tzoid .premium-tabs-nav .premium-tabs-nav-list.premium-tabs-horizontal li.tab-current:first-child .premium-tab-link' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ]
            ]
        );
        
        $this->add_responsive_control('premium_tab_active_padding',
            [
                'label'         => __('Padding', 'premium-addons-pro'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => [ 'px', 'em', '%' ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-tabs .tab-current .premium-tab-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ]
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();

        $this->end_controls_section();
        
        $this->start_controls_section('premium_tabs_icons_style_section',
            [
                'label'    => __('Icon', 'premium-addons-pro'),
                'tab'        => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control('premium_tab_icon_color',
            [
                'label'         => __('Color', 'premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'scheme' => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-tabs .premium-title-icon' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control('premium_tab_hover_icon_color',
            [
                'label'         => __('Hover Color', 'premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'scheme' => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-tabs .premium-tabs-nav-list-item:hover .premium-title-icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control('premium_tab_active_icon_color',
            [
                'label'         => __('Active Color', 'premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'scheme' => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-tabs .tab-current .premium-title-icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control('premium_tab_icon_size',
            [
                'label' => __( 'Size', 'premium-addons-pro' ),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .premium-tabs .premium-title-icon' => 'font-size: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .premium-tab-link svg, {{WRAPPER}} .premium-tab-link img'  => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(), 
            [
                'name'          => 'premium_tab_icon_border',
                'selector'      => '{{WRAPPER}} .premium-tabs .premium-title-icon , {{WRAPPER}} .premium-lottie-animation',
            ]
        );
        
        $this->add_control('premium_tab_icon_border_radius',
            [
                'label'         => __('Border Radius', 'premium-addons-pro'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%' ,'em'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-tabs .premium-title-icon, {{WRAPPER}} .premium-lottie-animation' => 'border-radius: {{SIZE}}{{UNIT}}'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'label'         => __('Icon Shadow','premium-addons-pro'),
                'name'          => 'premium_tab_icon_shadow',
                'selector'      => '{{WRAPPER}}  .premium-tabs .premium-title-icon',
            ]
        );
        
        $this->add_responsive_control('premium_tab_icon_margin',
            [
                'label'         => __('Margin', 'premium-addons-pro'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-tabs .premium-title-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]      
        );
        
        $this->add_responsive_control('premium_tab_icon_padding',
            [
                'label'         => __('Padding', 'premium-addons-pro'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-tabs .premium-title-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]      
        );
        
        $this->end_controls_section();

        $this->start_controls_section('premium_tabs_titles_style_section',
            [
                'label'         => __('Title', 'premium-addons-pro'),
                'tab'           => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control('premium_tab_title_color',
            [
                'label'         => __('Color', 'premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'scheme' => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-tabs .premium-tab-title' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_control('premium_tab_hover_title_color',
            [
                'label'         => __('Hover Color', 'premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'scheme' => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-tabs .premium-tabs-nav-list-item:hover .premium-tab-title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control('premium_tab_active_title_color',
            [
                'label'         => __('Active Color', 'premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'scheme' => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-tabs .tab-current .premium-tab-title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'premium_tab_title_typography',
                'selector' => '{{WRAPPER}} .premium-tabs .premium-tab-title',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(), 
                [
                    'name'          => 'premium_tab_title_border',
                    'selector'      => '{{WRAPPER}} .premium-tabs .premium-tab-title',
                    'condition'      => [
                        'premium_tab_style_selected!' => 'style2'
                        ]
                    ]
                );
        
        /*Icon Border Radius*/
        $this->add_control('premium_tab_title_border_radius',
                [
                    'label'         => __('Border Radius', 'premium-addons-pro'),
                    'type'          => Controls_Manager::SLIDER,
                    'size_units'    => ['px', '%' ,'em'],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-tabs .premium-tab-title' => 'border-radius: {{SIZE}}{{UNIT}}'
                    ],
                    'condition'      => [
                        'premium_tab_style_selected!' => 'style2'
                        ]
                ]
                );
        
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
                [
                    'label'         => __('Shadow','premium-addons-pro'),
                    'name'          => 'premium_tab_title_shadow',
                    'selector'      => '{{WRAPPER}}  .premium-tabs .premium-tab-title',
                ]
                );
        
        /*Icon Margin*/
        $this->add_responsive_control('premium_tab_title_margin',
                [
                    'label'         => __('Margin', 'premium-addons-pro'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => ['px', 'em', '%'],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-tabs .premium-tab-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}'
                ]
            ]      
        );
        
        $this->add_responsive_control('premium_tab_title_padding',
                [
                    'label'         => __('Padding', 'premium-addons-pro'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => ['px', 'em', '%'],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-tabs .premium-tab-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}'
                ]
            ]      
        );

        $this->end_controls_section();

        $this->start_controls_section('premium_tabs_style_descriptions_section',
            [
                'label'         => __('Description', 'premium-addons-pro'),
                'tab'           => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control('premium_tab_description_color',
            [
                'label'         => __('Color', 'premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'scheme' => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-content-wrap-inner' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'premium_tab_description_typography',
                'selector' => '{{WRAPPER}} .premium-tab-content',
            ]
        );
        
        $this->add_control('premium_tab_description_background',
            [
                'label'         => __('Background Color', 'premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .premium-tab-content' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(), 
                [
                    'name'          => 'premium_tab_description_border',
                    'selector'      => '{{WRAPPER}} .premium-tab-content',
                ]
                );
        
        /*Button Border Radius*/
        $this->add_control('premium_tab_description_border_radius',
                [
                    'label'         => __('Border Radius', 'premium-addons-pro'),
                    'type'          => Controls_Manager::SLIDER,
                    'size_units'    => ['px', '%' ,'em'],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-tab-content' => 'border-radius: {{SIZE}}{{UNIT}};'
                    ]
                ]
                );
        
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
                [
                    'label'         => __('Shadow','premium-addons-pro'),
                    'name'          => 'premium_tab_description_shadow',
                    'selector'      => '{{WRAPPER}} .premium-tab-content',
                    ]
                );
        
        /*Button Shadow*/
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
                [
                    'name'          => 'premium_tab_description_box_shadow',
                    'selector'      => '{{WRAPPER}} .premium-tab-content',
                ]
                );
        
        $this->add_responsive_control('premium_tab_description_margin',
                [
                    'label'         => __('Margin', 'premium-addons-pro'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => [ 'px', 'em', '%' ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-tab-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                        ]
                    ]
                );
        
        /*First Padding*/
        $this->add_responsive_control('premium_tab_description_padding',
                [
                    'label'         => __('Padding', 'premium-addons-pro'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => [ 'px', 'em', '%' ],
                    'default'       => [
                        'unit'  => 'px',
                        'top'   => 10,
                        'right' => 10,
                        'bottom'=> 10,
                        'left'  => 10,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-tab-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                        ]
                    ]
                );

        $this->end_controls_section();
        
        $this->start_controls_section('premium_tab_container_style',
            [
                'label'         => __('Container', 'premium-addons-pro'),
                'tab'           => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control('premium_tab_container_background_color',
            [
                'label'         => __('Background Color', 'premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .premium-tabs-container' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(), 
                [
                    'name'          => 'premium_tab_container_border',
                    'selector'      => '{{WRAPPER}} .premium-tabs-container',
                ]
                );
        
        /*Button Border Radius*/
        $this->add_control('premium_tab_container_border_radius',
                [
                    'label'         => __('Border Radius', 'premium-addons-pro'),
                    'type'          => Controls_Manager::SLIDER,
                    'size_units'    => ['px', '%' ,'em'],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-tabs-container' => 'border-radius: {{SIZE}}{{UNIT}};'
                    ]
                ]
                );
        
        /*Button Shadow*/
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
                [
                    'name'          => 'premium_tab_container_shadow',
                    'selector'      => '{{WRAPPER}} .premium-tabs-container',
                ]
                );
        
        $this->add_responsive_control('premium_tab_container_margin',
            [
                'label'         => __('Margin', 'premium-addons-pro'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => [ 'px', 'em', '%' ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-tabs-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ]
            ]
        );
        
        $this->add_responsive_control('premium_tab_container_padding',
            [
                'label'         => __('Padding', 'premium-addons-pro'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => [ 'px', 'em', '%' ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-tabs-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ]
            ]
        );

        $this->end_controls_section();
    
    }

    /**
     * Render Tabs output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render() {

        $settings = $this->get_settings_for_display();
        
        $id = $this->get_id();
        
        $tabs  = $settings['premium_tabs_repeater'];
        
        $custom_navigation = array();

        foreach( $tabs as $tab ) {
            array_push( $custom_navigation, $tab['custom_tab_navigation'] );
        }

        $style = $settings['premium_tab_style_selected'];
        
        switch ($style){
            case 'style1' :
                $section_style = 'iconbox';
                $html_elem = 'p';
                break;
            case 'style2' :
                $section_style = 'circle';
                $html_elem = 'p';
                break;
            case 'style3' :
                $section_style = 'flip';
                $html_elem = 'span';
                break;
            case 'style4' :
                $section_style = 'tzoid';
                $html_elem = 'span';
                break;
        }
        
        $direction = 'premium-tabs-' . $settings['premium_tab_type'];
        
        $tabs_settings = [
            'id'            => '#premium-tabs-' . $id,
            'carousel'      => $settings['carousel_tabs'],
            'slides'        => $settings['tabs_number'],
            'slides_tab'    => ! empty( $settings['tabs_number_tablet'] ) ? $settings['tabs_number_tablet'] : $settings['tabs_number'],
            'slides_mob'    => ! empty( $settings['tabs_number_mobile'] ) ? $settings['tabs_number_mobile'] : $settings['tabs_number'],
            'spacing'       => $settings['slides_spacing'],
            'spacing_tab'   => ! empty( $settings['slides_spacing_tablet'] ) ? $settings['slides_spacing_tablet'] : $settings['slides_spacing'],
            'spacing_mob'   => ! empty( $settings['slides_spacing_mobile'] ) ? $settings['slides_spacing_mobile'] : $settings['slides_spacing'],
            'start'         => intval( $settings['default_tab_index'] ),
            'navigation'    => $custom_navigation
        ];

        
        if( $settings['carousel_tabs'] ) {
            $this->add_render_attribute( 'tabs', 'class', 'elementor-invisible' ); 
            $tabs_settings['carousel_devices'] = $settings['carousel_tabs_devices'];
        }

        $this->add_render_attribute('tabs', [
            'class' => 'premium-tabs-container',
            'data-settings' => wp_json_encode( $tabs_settings )
        ]);

        $this->add_render_attribute('tabs-wrap', [
            'id' => 'premium-tabs-' . $id,
            'class' => [
                'premium-tabs',
                'premium-tabs-style-' . $section_style,
                $direction
            ]
        ]);
        
        $this->add_render_attribute( 'tabs-nav', 'class', [ 'premium-tabs-nav-list', $direction ] );
        
        $this->add_render_attribute( 'tabs-content', 'class', [ 'premium-content-wrap', $direction ] );
        
        $this->add_render_attribute( 'premium_tabs_title', 'class', 'premium-tab-title' );
        
        ?>
            <div <?php echo $this->get_render_attribute_string('tabs'); ?>>
                <section class="premium-tabs-section">
                    <div <?php echo $this->get_render_attribute_string('tabs-wrap'); ?>>
                        <div class="premium-tabs-nav">
                            <ul <?php echo $this->get_render_attribute_string('tabs-nav'); ?>>
                                <?php foreach ( $tabs as $index => $tab ) {

                                    if( $tab['premium_tabs_icon_switcher'] === 'yes' ) {

                                        $icon_key = 'tab_icon_' . $index;

                                        if( $tab['icon_type'] === 'icon' ) {

                                            $icon_migrated = isset( $tab['__fa4_migrated']['premium_tabs_icon_updated'] );
                                            $icon_new = empty( $tab['premium_tabs_icon'] ) && Icons_Manager::is_migration_allowed();

                                        } elseif ( $tab['icon_type'] === 'image' ) {

                                            $src        = $tab['image_upload']['url'];

                                            $alt        = Control_Media::get_image_alt( $tab['image_upload'] );

                                            $this->add_render_attribute( $icon_key, [
                                                'class' => 'premium-title-icon',
                                                'src'   => $src,
                                                'alt'   => $alt
                                            ]);

                                        } else {

                                            $this->add_render_attribute( $icon_key, [
                                                'class' => [
                                                    'premium-title-icon',
                                                    'premium-lottie-animation'
                                                ],
                                                'data-lottie-url' => $tab['lottie_url'],
                                                'data-lottie-loop' => $tab['lottie_loop'],
                                                'data-lottie-reverse' => $tab['lottie_reverse']
                                            ]);
                                        }

                                    }
                                    
                                ?>
                                    <li class="premium-tabs-nav-list-item" data-list-index="<?php echo $index; ?>">
                                        <a class="premium-tab-link" href="#section-<?php echo $section_style . '-'. $index . '-'. esc_attr( $this->get_id() ); ?>">

                                            <?php if ( $tab['premium_tabs_icon_switcher'] === 'yes' ) {
                                                    if( $tab['icon_type'] === 'icon' ) {
                                                        if ( $icon_new || $icon_migrated ) {
                                                            Icons_Manager::render_icon( $tab['premium_tabs_icon_updated'], [ 'class' => 'premium-title-icon', 'aria-hidden' => 'true' ] );
                                                        } else { ?>
                                                            <i class="premium-title-icon <?php echo $tab['premium_tabs_icon']; ?>"></i>
                                                        <?php }
                                                    } elseif($tab['icon_type'] === 'image' ) { ?>
                                                        <img <?php echo $this->get_render_attribute_string( $icon_key ); ?>>
                                                    <?php }
                                                        else{ ?>
                                                        <div <?php echo $this->get_render_attribute_string( $icon_key ); ?>></div>
                                                    <?php }
                                                }

                                                if( ! empty( $tab['premium_tabs_title'] ) ) {
                                                    echo '<' . $html_elem . ' ' . $this->get_render_attribute_string( 'premium_tabs_title' ) . '>' . $tab['premium_tabs_title'] . '</' . $html_elem . '>';
                                                }
                                            ?>
                                        </a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>

                        <div <?php echo $this->get_render_attribute_string('tabs-content'); ?>>
                            <?php foreach ( $tabs as $index => $tab ) :
                            
                                $tab_content_setting_key = $this->get_repeater_setting_key( 'premium_tabs_content_text', 'premium_tabs_repeater', $index );

                                $this->add_inline_editing_attributes( $tab_content_setting_key, 'advanced' );
                                
                            ?>

                                <section id="section-<?php echo $section_style . '-' . $index . '-'. esc_attr($this->get_id()); ?>" class="premium-tabs-content-section">
                                    <div class="premium-tab-content">
                                        <div class="premium-content-wrap-inner">
                                        <?php if( $tab['premium_tabs_content'] === 'text_editor' ) { ?>
                                            <div <?php echo $this->get_render_attribute_string( $tab_content_setting_key ); ?>>
                                                <?php echo $this->parse_text_editor( $tab['premium_tabs_content_text'] ); ?>
                                            </div>
                                        <?php } else {
                                            $template = $tab['premium_tabs_content_temp'];
                                            echo $this->getTemplateInstance()->get_template_content( $template );
                                        }
                                        ?>
                                        </div>
                                    </div>
                                </section>

                            <?php endforeach; ?>
                        </div>

                        <div class="premium-clearfix"></div>
                    </div>
                </section>
            </div>
        <?php
    }

}
