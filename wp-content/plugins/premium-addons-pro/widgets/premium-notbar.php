<?php

/**
 * Class: Premium_Notbar
 * Name: Alert Box
 * Slug: premium-notbar
 */
namespace PremiumAddonsPro\Widgets;

// Elementor Classes.
use Elementor\Icons_Manager;
use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Plugin;
use Elementor\Controls_Manager;
use Elementor\Control_Media;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Core\Responsive\Responsive;

// PremiumAddons Classes.
use PremiumAddons\Includes\Helper_Functions;
use PremiumAddons\Includes\Premium_Template_Tags;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

/**
 * Class Premium_Notbar
 */
class Premium_Notbar extends Widget_Base {
    protected  $templateInstance;
    
    public function getTemplateInstance(){
        return $this->templateInstance = Premium_Template_Tags::getInstance();
    }
    
    public function get_name() {
        return 'premium-notbar';
    }

    public function get_title() {
        return sprintf( '%1$s %2$s', Helper_Functions::get_prefix(), __('Alert Box', 'premium-addons-pro') );
    }

    public function get_icon() {
        return 'pa-pro-notification-bar';
    }
    
    public function is_reload_preview_required() {
        return true;
    }
    
    public function get_script_depends() {
        return [
            'lottie-js',
            'premium-pro-js'
        ];
    }

    public function get_categories() {
        return [ 'premium-elements' ];
    }
    
    public function get_keywords() {
		return [ 'notification', 'bar', 'popup' ];
	}
    
    public function get_custom_help_url() {
		return 'https://premiumaddons.com/support/';
	}

    /**
	 * Register Alert Box controls.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
    protected function _register_controls() {

        $this->start_controls_section('premium_notbar_general_section',
            [
                'label'         => __('Bar', 'premium-addons-pro'),
            ]
        );
        
        $this->add_control('premium_notbar_position',
            [
                'label'         => __('Position', 'premium-addons-pro'),
                'type'          => Controls_Manager::CHOOSE,
                'options'           => [
                    'top'    => [
                        'title' => __( 'Top', 'premium-addons-pro' ),
                        'icon'  => 'fa fa-arrow-circle-up',
                    ],
                    'bottom' => [
                        'title' => __( 'Bottom', 'premium-addons-pro' ),
                        'icon'  => 'fa fa-arrow-circle-down',
                    ],
                    'middle' => [
                        'title' => __( 'Middle', 'premium-addons-pro' ),
                        'icon'  => 'fa fa-align-center',
                    ],
                    'float' => [
                        'title' => __( 'Custom', 'premium-addons-pro' ),
                        'icon'  => 'fa fa-align-justify',
                    ],
                ],
                'default'       => 'float',
                'toggle'        => false,
            ]
        );
        
        $this->add_responsive_control('premium_notbar_float_pos',
            [
                'label'         => __('Vertical Offset (%)', 'premium-addons-pro'),
                'type'          => Controls_Manager::SLIDER,
                'default'       => [
                    'size'  => 10,
                    'unit'  => '%'
                ],
                'condition'     => [
                    'premium_notbar_position'    => 'float',
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-notbar'   => 'top: {{SIZE}}%;'
                ]
            ]
        );
        
        $this->add_control('premium_notbar_top_select',
            [
                'label'         => __('Layout', 'premium-addons-pro'),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'fixed'  => __('Fixed','premium-addons-pro'),
                    'relative' => __('Relative','premium-addons-pro'),
                ],
                'default'       => 'relative',
                'condition'     => [
                    'premium_notbar_position'    => 'top',
                ],
                'label_block'   => true,
            ]
        );
        
        $this->add_control('premium_notbar_width',
            [
                'label'         => __('Width', 'premium-addons-pro'),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'wide'  => __('Full Width','premium-addons-pro'),
                    'boxed' => __('Boxed','premium-addons-pro'),
                ],
                'default'       => 'boxed',
                'label_block'   => true,
            ]
        );

        $this->add_control('premium_notbar_direction',
            [
                'label'         => __('Direction', 'premium-addons-pro'),
                'type'          => Controls_Manager::CHOOSE,
                'options'           => [
                    'row'       => [
                        'title'     => __( 'LTR', 'premium-addons-pro' ),
                        'icon'      => 'fa fa-arrow-circle-right',
                    ],
                    'row-reverse' => [
                        'title'     => __( 'RTL', 'premium-addons-pro' ),
                        'icon'      => 'fa fa-arrow-circle-left',
                    ],
                ],
                'default'       => 'row',
                'selectors'     => [
                    '{{WRAPPER}} .premium-notbar-content-wrapper, {{WRAPPER}} .premium-notbar-icon-text-container'    => '-webkit-flex-direction: {{VALUE}}; flex-direction: {{VALUE}};'
                ],
                'condition'     => [
                    'premium_notbar_content_type'    => 'editor',
                ],
                'toggle'        => false
            ]
        );
        
        $this->add_control('premium_notbar_close_heading',
            [
                'label'         => __('Button', 'premium-addons-pro'),
                'type'          => Controls_Manager::HEADING,
                'separator'     => 'before'
            ]
        );
        
        $this->add_control('premium_notbar_close_hor_position',
            [
                'label'         => __('Horizontal Position', 'premium-addons-pro'),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'row'           => __( 'After', 'premium-addons-pro' ),
                    'row-reverse'   => __( 'Before', 'premium-addons-pro' ),
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-notbar-content-wrapper'    => '-webkit-flex-direction: {{VALUE}}; flex-direction: {{VALUE}};'
                ],
                'default'       => 'row',
                'label_block'   => true,
            ]
        );
        
        $this->add_control('premium_notbar_close_ver_position',
            [
                'label'         => __('Vertical Position', 'premium-addons-pro'),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'flex-start'    => __( 'Top', 'premium-addons-pro' ),
                    'center'        => __( 'Middle', 'premium-addons-pro' ),
                    'flex-end'      => __( 'Bottom', 'premium-addons-pro' ),
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-notbar-content-wrapper'    => 'align-items: {{VALUE}};'
                ],
                'default'       => 'center',
                'label_block'   => true,
                'separator'     => 'after'
            ]
        );

        $this->add_control('enable_background_overlay',
            [
                'label'         => __('Overlay Background', 'premium-addons-pro'),
                'type'          => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control('background_overlay_notice', 
            [
                'raw'               => __('Please note that Overlay Background works only on the frontend', 'premium-addons-pro'),
                'type'              => Controls_Manager::RAW_HTML,
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                'condition'         => [
                    'enable_background_overlay' => 'yes'
                ]
            ] 
        );
        
        $this->add_control('premium_notbar_index',
            [
                'label'         => __('z-index', 'premium-addons-pro'),
                'description'   => __('Set a z-index for the notification bar, default is: 9999','premium-addons-pro'),
                'type'          => Controls_Manager::NUMBER,    
                'min'           => 0,
                'max'           => 9999,
                'selectors'     => [
                    '#premium-notbar-{{ID}}'   => 'z-index: {{VALUE}};'
                ]
            ]
        );

        $this->end_controls_section();
        
        $this->start_controls_section('premium_notbar_content',
            [
                'label'             => __('Content', 'premium-addons-pro'),
            ]
        );
        
        $this->add_control('premium_notbar_content_type',
            [
                'label'         => __('Content to Show', 'premium-addons-pro'),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'editor'        => __('Text Editor', 'premium-addons-pro'),
                    'template'      => __('Elementor Template', 'premium-addons-pro'),
                ],
                'default'       => 'editor',
                'label_block'   => true
            ]
        );
        
        $this->add_control('premium_notbar_content_temp',
            [
                'label'         => __( 'Content', 'premium-addons-pro' ),
                'description'   => __( 'Elementor Template is a template which you can choose from Elementor Templates library', 'premium-addons-pro' ),
                'type' => Controls_Manager::SELECT2,
                'options' => $this->getTemplateInstance()->get_elementor_page_list(),
                'condition'     => [
                    'premium_notbar_content_type'    => 'template',
                ],
                'label_block'   => true,
            ]
        );
        
        $this->add_responsive_control('premium_notbar_temp_width',
            [
                'label'         => __('Content Width (%)', 'premium-addons-pro'),
                'type'          => Controls_Manager::SLIDER,
                'condition'     => [
                    'premium_notbar_content_type'    => 'template',
                ],
                'selectors'     => [
                    '#premium-notbar-{{ID}} .premium-notbar-content-wrapper'   => 'width: {{SIZE}}%;'
                ]
            ]
        );
        
        $this->add_control('premium_notbar_icon_switcher',
            [
                'label'         => __('Icon', 'premium-addons-pro'),
                'type'          => Controls_Manager::SWITCHER,
                'default'       => 'yes',
                'condition'     => [
                    'premium_notbar_content_type'    => 'editor',
                ],
            ]
        );
        
        $this->add_control('premium_notbar_icon_selector',
            [
                'label'        => __('Icon Type','premium-addons-pro'),
                'type'         => Controls_Manager::SELECT,
                'default'      => 'font-awesome-icon',
                'options'      =>[
                    'font-awesome-icon' => __('Font Awesome Icon','premium-addons-pro'),
                    'custom-image'      => __('Custom Image','premium-addons-pro'),
                    'animation'         => __('Lottie Animation','premium-addons-pro'),
                ],
                'condition'     => [
                    'premium_notbar_content_type'    => 'editor',
                    'premium_notbar_icon_switcher'    => 'yes',
                ]
            ]
        );
        
        $this->add_control('premium_notbar_icon_updated',
            [
                'label'             => __('Icon','premium-addons-pro'),
                'type'              => Controls_Manager::ICONS,
                'fa4compatibility'  => 'premium_notbar_icon',
                'default' => [
                    'value'     => 'fas fa-exclamation-circle',
                    'library'   => 'fa-solid',
                ],
                'condition'         => [
                    'premium_notbar_icon_switcher'  => 'yes',
                    'premium_notbar_content_type'   => 'editor',
                    'premium_notbar_icon_selector'  => 'font-awesome-icon'
                ]
            ]
        );
        
        $this->add_control('premium_notbar_custom_image',
            [
                'label'        => __('Custom Image','premium-addons-pro'),
                'type'         => Controls_Manager::MEDIA,
                'dynamic'       => [ 'active' => true ],
                'default'      => [
                    'url'	=> Utils::get_placeholder_image_src()
                ],
                'condition'    => [
                    'premium_notbar_icon_switcher'  => 'yes',
                    'premium_notbar_content_type'   => 'editor',
                    'premium_notbar_icon_selector'  => 'custom-image'
                ]
            ]
        );

        $this->add_control('lottie_url', 
            [
                'label'             => __( 'Animation JSON URL', 'premium-addons-pro' ),
                'type'              => Controls_Manager::TEXT,
                'dynamic'           => [ 'active' => true ],
                'description'       => 'Get JSON code URL from <a href="https://lottiefiles.com/" target="_blank">here</a>',
                'label_block'       => true,
                'condition'     => [
                    'premium_notbar_icon_switcher'  => 'yes',
                    'premium_notbar_content_type'   => 'editor',
                    'premium_notbar_icon_selector'  => 'animation'
                ]
            ]
        );

        $this->add_control('lottie_loop',
            [
                'label'         => __('Loop','premium-addons-pro'),
                'type'          => Controls_Manager::SWITCHER,
                'return_value'  => 'true',
                'default'       => 'true',
                'condition'     => [
                    'premium_notbar_icon_switcher'  => 'yes',
                    'premium_notbar_content_type'   => 'editor',
                    'premium_notbar_icon_selector'  => 'animation'
                ]
            ]
        );

        $this->add_control('lottie_reverse',
            [
                'label'         => __('Reverse','premium-addons-pro'),
                'type'          => Controls_Manager::SWITCHER,
                'return_value'  => 'true',
                'condition'     => [
                    'premium_notbar_icon_switcher'  => 'yes',
                    'premium_notbar_content_type'   => 'editor',
                    'premium_notbar_icon_selector'  => 'animation'
                ]
            ]
        );
        
        $this->add_control('premium_notbar_text',
            [
                'type'          => Controls_Manager::WYSIWYG,
                'dynamic'       => [ 'active' => true ],
                'default'       => 'Morbi vel neque a est hendrerit laoreet in quis massa.',
                'condition'     => [
                    'premium_notbar_content_type'    => 'editor',
                ],
                'show_label'    => false,
            ]
        );
        
        $this->add_control('premium_notbar_close_text',
                [
                    'label'         => __('Button Text', 'premium-addons-pro'),
                    'type'          => Controls_Manager::TEXT,
                    'dynamic'       => [ 'active' => true ],
                    'default'       => 'x',
                ]
            );
        
        $this->add_control('premium_notbar_link_switcher',
            [
                'label'         => __('Link', 'premium-addons-pro'),
                'type'          => Controls_Manager::SWITCHER,
            ]
        );
        
        $this->add_control('premium_notbar_link_selection', 
            [
                'label'         => __('Link Type', 'premium-addons-pro'),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'url'   => __('URL', 'premium-addons-pro'),
                    'link'  => __('Existing Page', 'premium-addons-pro'),
                ],
                'default'       => 'url',
                'label_block'   => true,
                'condition'     => [
                    'premium_notbar_link_switcher'    => 'yes'
                ]
            ]
        );

        $this->add_control('premium_notbar_link',
            [
                'label'         => __('Link', 'premium-addons-pro'),
                'type'          => Controls_Manager::URL,
                'dynamic'       => [ 'active' => true ],
                'default'       => [
                    'url'   => '#',
                ],
                'placeholder'   => 'https://premiumaddons.com/',
                'label_block'   => true,
                'condition'     => [
                    'premium_notbar_link_switcher'    => 'yes',
                    'premium_notbar_link_selection' => 'url'
                ]
            ]
        );
        
        $this->add_control('premium_notbar_existing_link',
            [
                'label'         => __('Existing Page', 'premium-addons-pro'),
                'type'          => Controls_Manager::SELECT2,
                'options'       => $this->getTemplateInstance()->get_all_posts(),
                'multiple'      => false,
                'condition'     => [
                    'premium_notbar_link_switcher'    => 'yes',
                    'premium_notbar_link_selection' => 'link'
                ],
                'label_block'   => true,
            ]
        );
        
        $this->add_responsive_control('premium_notbar_text_align',
            [
                'label'             => __( 'Alignment', 'premium-addons-pro' ),
                'type'              => Controls_Manager::CHOOSE,
                'options'           => [
                    'left'    => [
                        'title' => __( 'Left', 'premium-addons-pro' ),
                        'icon'  => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'premium-addons-pro' ),
                        'icon'  => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'premium-addons-pro' ),
                        'icon'  => 'fa fa-align-right',
                    ],
                ],
                'condition'     => [
                    'premium_notbar_content_type'    => 'editor',
                ],
                'selectors'         => [
                    '#premium-notbar-{{ID}} .premium-notbar-icon-text-container' => 'justify-content: {{VALUE}}; text-align: {{VALUE}};',
                ],
                'default' => 'left',
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_notbar_advanced',
            [
                'label'     => __('Advanced Settings', 'premium-addons-pro'),
            ]
        );

        $this->add_control('premium_notbar_cookies',
            [
                'label'         => __('Use Cookies', 'premium-addons-pro'),
                'type'          => Controls_Manager::SWITCHER,
                'description'   => __('This option will use cookies to remember user action', 'premium-addons-pro')
            ]);
        
        $this->add_control('premium_notbar_interval',
            [
                'label'         => __('Expiration Time', 'premium-addons-pro'),
                'type'          => Controls_Manager::NUMBER,
                'default'       => 1,
                'min'           => 0,
                'title'         => __('How much time before removing cookie, set the value in hours, default is: 1 hour', 'premium-addons-pro'),
                'condition'     => [
                    'premium_notbar_cookies'    => 'yes'
                ]
            ]
        );

        $this->add_control('premium_notbar_responsive_switcher',
            [
                'label'         => __('Responsive Controls', 'premium-addons-pro'),
                'type'          => Controls_Manager::SWITCHER,
                'description'   => __('This options will hide the notification bar below a specific screen size', 'premium-addons-pro')
            ]);
        
        $this->add_responsive_control('premium_notbar_height',
                [
                    'label'         => __('Height', 'premium-addons-pro'),
                    'type'          => Controls_Manager::SLIDER,
                    'size_units'    => ['px', 'em', 'vh'],
                    'selectors'     => [
                        '#premium-notbar-{{ID}} .premium-notbar-icon-text-container' => 'height: {{SIZE}}{{UNIT}};'
                        ]
                    ]
                );
        
        $this->add_responsive_control('premium_notbar_overflow',
                [
                    'label'         => __('Overflow', 'premium-addons-pro'),
                    'type'          => Controls_Manager::SELECT,
                    'options'       => [
                        'visible'       => __('Show', 'premium-addons-pro'),
                        'scroll'        => __('Scroll', 'premium-addons-pro'),
                        'auto'          => __('Auto', 'premium-addons-pro'),
                    ],
                    'label_block'   => true,
                    'default'       => 'auto',
                    'selectors'     => [
                        '#premium-notbar-{{ID}} .premium-notbar-icon-text-container' => 'overflow-y: {{VALUE}};'
                        ]
                    ]
                );
        
        $this->add_control('premium_notbar_hide_tabs',
            [
                'label'         => __('Hide on Tablets', 'premium-addons-pro'),
                'type'          => Controls_Manager::SWITCHER,
                'description'   => __('Hide Notification Bar below Elementor\'s Tablet Breakpoint ', 'premium-addons-pro'),
                'condition'     => [
                    'premium_notbar_responsive_switcher'    => 'yes'
                ],
            ]
        );
        
        $this->add_control('premium_notbar_hide_mobs',
            [
                'label'         => __('Hide on Mobiles', 'premium-addons-pro'),
                'type'          => Controls_Manager::SWITCHER,
                'description'   => __('Hide Notification Bar below Elementor\'s Mobile Breakpoint ', 'premium-addons-pro'),
                'condition'     => [
                    'premium_notbar_responsive_switcher'    => 'yes'
                ],
            ]
        );
        
        $this->end_controls_section();

        $this->start_controls_section('premium_notbar_style',
            [
                'label'             => __('Bar','premium-addons-pro'),
                'tab'               => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control('premium_notbar_background',
            [
                'label'             => __('Background Color', 'premium-addons-pro'),
                'type'              => Controls_Manager::COLOR,
                'scheme'            => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors'         => [
                    '#premium-notbar-{{ID}}'   => 'background-color: {{VALUE}};'
                ]
            ]
        );

        $this->add_control('background_overlay',
            [
                'label'             => __('Overlay Background Color', 'premium-addons-pro'),
                'type'              => Controls_Manager::COLOR,
                'condition'     => [
                    'enable_background_overlay' => 'yes'
                ],
                'selectors'         => [
                    '#premium-notbar-outer-container-{{ID}} .premium-notbar-background-overlay'   => 'background-color: {{VALUE}};'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
                [
                    'name'              => 'premium_notbar_border',
                    'selector'          => '#premium-notbar-{{ID}}',
                    ]
                );
        
        $this->add_control('premium_notbar_border_radius',
                [
                    'label'         => __('Border Radius', 'premium-addons-pro'),
                    'type'          => Controls_Manager::SLIDER,
                    'size_units'    => ['px', '%', 'em'],
                    'selectors'     => [
                        '#premium-notbar-{{ID}}' => 'border-radius: {{SIZE}}{{UNIT}};'
                        ]
                    ]
                );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
                [
                    'name'              => 'premium_notbar_shadow',
                    'selector'          => '#premium-notbar-{{ID}}',
                    ]
                );
        
        $this->add_responsive_control('premium_notbar_margin',
                [
                    'label'         => __('Margin', 'premium-addons-pro'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => [ 'px', 'em', '%' ],
                    'selectors'     => [
                        '#premium-notbar-{{ID}}' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                        ]
                    ]
                );
        
        $this->add_responsive_control('premium_notbar_padding',
                [
                    'label'         => __('Padding', 'premium-addons-pro'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => [ 'px', 'em', '%' ],
                    'selectors'     => [
                        '#premium-notbar-{{ID}}' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                        ]
                    ]
                );
        
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_notbar_icon_style',
            [
                'label'             => __('Icon','premium-addons-pro'),
                'tab'               => Controls_Manager::TAB_STYLE,
                'condition'         => [
                    'premium_notbar_icon_switcher'  => 'yes',
                    'premium_notbar_content_type'    => 'editor',
                ]
            ]
        );
        
        $this->add_control('premium_notbar_icon_color',
            [
                'label'             => __('Color', 'premium-addons-pro'),
                'type'              => Controls_Manager::COLOR,
                'scheme'            => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'selectors'         => [
                    '#premium-notbar-{{ID}} .premium-notbar-icon'   => 'color: {{VALUE}};'
                ],
                'condition'         => [
                    'premium_notbar_icon_switcher'  => 'yes',
                    'premium_notbar_content_type'   => 'editor',
                    'premium_notbar_icon_selector'  => 'font-awesome-icon'
                ],
            ]
        );
        
        $this->add_control('premium_notbar_icon_hover_color',
            [
                'label'             => __('Hover Color', 'premium-addons-pro'),
                'type'              => Controls_Manager::COLOR,
                'scheme'            => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'selectors'         => [
                    '#premium-notbar-{{ID}}:hover .premium-notbar-icon'   => 'color: {{VALUE}};'
                ],
                'condition'         => [
                    'premium_notbar_icon_switcher'  => 'yes',
                    'premium_notbar_content_type'   => 'editor',
                    'premium_notbar_icon_selector'  => 'font-awesome-icon'
                ],
            ]
        );
        
        $this->add_responsive_control('premium_notbar_icon_size',
            [
                'label'         => __('Size', 'premium-addons-pro'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', 'em'],
                'selectors'     => [
                    '#premium-notbar-{{ID}} .premium-notbar-icon' => 'font-size: {{SIZE}}px;',
                    '#premium-notbar-{{ID}} .premium-notbar-custom-image, #premium-notbar-{{ID}} .premium-notbar-icon-wrap svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}'
                ]
            ]
        );
        
        $this->add_control('premium_notbar_icon_backcolor',
            [
                'label'             => __('Background Color', 'premium-addons-pro'),
                'type'              => Controls_Manager::COLOR,
                'selectors'         => [
                    '#premium-notbar-{{ID}} .premium-notbar-icon, #premium-notbar-{{ID}} .premium-notbar-custom-image, #premium-notbar-{{ID}} .premium-notbar-icon-lottie'   => 'background-color: {{VALUE}};'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'              => 'premium_notbar_icon_border',
                'selector'          => '#premium-notbar-{{ID}} .premium-notbar-icon,#premium-notbar-{{ID}} .premium-notbar-custom-image, #premium-notbar-{{ID}} .premium-notbar-icon-lottie'
            ]
        );
        
        $this->add_control('premium_notbar_icon_border_radius',
            [
                'label'         => __('Border Radius', 'premium-addons-pro'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%', 'em'],
                'selectors'     => [
                    '#premium-notbar-{{ID}} .premium-notbar-icon, #premium-notbar-{{ID}} .premium-notbar-custom-image, #premium-notbar-{{ID}} .premium-notbar-icon-lottie' => 'border-radius: {{SIZE}}{{UNIT}};'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'label'             => __('Shadow', 'premium-addons-pro'),
                'name'              => 'premium_notbar_icon_shadow',
                'selector'          => '#premium-notbar-{{ID}} .premium-notbar-icon',
                'condition'         => [
                    'premium_notbar_icon_switcher'  => 'yes',
                    'premium_notbar_content_type'   => 'editor',
                    'premium_notbar_icon_selector'  => 'font-awesome-icon'
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'              => 'premium_notbar_img_shadow',
                'selector'          => '#premium-notbar-{{ID}} .premium-notbar-custom-image, #premium-notbar-{{ID}} .premium-notbar-icon-lottie',
                'condition'         => [
                    'premium_notbar_icon_switcher'  => 'yes',
                    'premium_notbar_content_type'   => 'editor',
                    'premium_notbar_icon_selector!'  => 'font-awesome-icon'
                ],
            ]
        );
        
        $this->add_responsive_control('premium_notbar_icon_margin',
            [
                'label'         => __('Margin', 'premium-addons-pro'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => [ 'px', 'em', '%' ],
                'selectors'     => [
                    '#premium-notbar-{{ID}} .premium-notbar-icon , #premium-notbar-{{ID}} .premium-notbar-custom-image, #premium-notbar-{{ID}} .premium-notbar-icon-lottie' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ]
            ]
        );
        
        $this->add_responsive_control('premium_notbar_icon_padding',
            [
                'label'         => __('Padding', 'premium-addons-pro'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => [ 'px', 'em', '%' ],
                'selectors'     => [
                    '#premium-notbar-{{ID}} .premium-notbar-icon, #premium-notbar-{{ID}} .premium-notbar-custom-image, #premium-notbar-{{ID}} .premium-notbar-icon-lottie' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ]
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_notbar_text_style',
            [
                'label'             => __('Text','premium-addons-pro'),
                'tab'               => Controls_Manager::TAB_STYLE,
                'condition'         => [
                    'premium_notbar_content_type'   => 'editor'
                ]
            ]);
        
        $this->add_control('premium_notbar_text_color',
            [
                'label'             => __('Color', 'premium-addons-pro'),
                'type'              => Controls_Manager::COLOR,
                'scheme'            => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'selectors'         => [
                    '#premium-notbar-{{ID}} .premium-notbar-text'   => 'color: {{VALUE}};'
                ]
            ]);
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
                [
                    'name'              => 'premium_notbar_text_typo',
                    'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                    'selector'          => '#premium-notbar-{{ID}} .premium-notbar-text',
            ]);
        
        $this->add_control('premium_notbar_text_backcolor',
            [
                'label'             => __('Background Color', 'premium-addons-pro'),
                'type'              => Controls_Manager::COLOR,
                'selectors'         => [
                    '#premium-notbar-{{ID}} .premium-notbar-content-wrapper .premium-notbar-text'   => 'background-color: {{VALUE}};'
                ]
            ]);
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
                [
                    'name'              => 'premium_notbar_text_border',
                    'selector'          => '#premium-notbar-{{ID}} .premium-notbar-content-wrapper .premium-notbar-text',
                    ]
                );
        
        $this->add_control('premium_notbar_text_border_radius',
                [
                    'label'         => __('Border Radius', 'premium-addons-pro'),
                    'type'          => Controls_Manager::SLIDER,
                    'size_units'    => ['px', '%', 'em'],
                    'selectors'     => [
                        '#premium-notbar-{{ID}} .premium-notbar-content-wrapper .premium-notbar-text' => 'border-radius: {{SIZE}}{{UNIT}};'
                        ]
                    ]
                );
        
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
                [
                    'label'             => __('Shadow','premium-addons-pro'),
                    'name'              => 'premium_notbar_text_shadow',
                    'selector'          => '#premium-notbar-{{ID}} .premium-notbar-text .premium-notbar-text',
                    ]
                );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
                [
                    'name'              => 'premium_notbar_text_box_shadow',
                    'selector'          => '#premium-notbar-{{ID}} .premium-notbar-content-wrapper .premium-notbar-text',
                    ]
                );
        
        $this->add_responsive_control('premium_notbar_text_margin',
                [
                    'label'         => __('Margin', 'premium-addons-pro'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => [ 'px', 'em', '%' ],
                    'selectors'     => [
                        '#premium-notbar-{{ID}} .premium-notbar-content-wrapper .premium-notbar-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                        ]
                    ]
                );
        
        $this->add_responsive_control('premium_notbar_text_padding',
                [
                    'label'         => __('Padding', 'premium-addons-pro'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => [ 'px', 'em', '%' ],
                    'selectors'     => [
                        '#premium-notbar-{{ID}} .premium-notbar-content-wrapper .premium-notbar-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                        ]
                    ]
                );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_notbar_close_style',
            [
                'label'             => __('Button','premium-addons-pro'),
                'tab'               => Controls_Manager::TAB_STYLE,
            ]);
        
        $this->add_control('premium_notbar_close_color',
            [
                'label'             => __('Color', 'premium-addons-pro'),
                'type'              => Controls_Manager::COLOR,
                'scheme'            => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'selectors'         => [
                    '#premium-notbar-{{ID}} .premium-notbar-close'   => 'color: {{VALUE}};'
                ]
            ]);
        
        $this->add_control('premium_notbar_close_hover_color',
            [
                'label'             => __('Hover Color', 'premium-addons-pro'),
                'type'              => Controls_Manager::COLOR,
                'scheme'            => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'selectors'         => [
                    '#premium-notbar-{{ID}} .premium-notbar-close:hover'   => 'color: {{VALUE}};'
                ]
            ]);
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
                [
                    'name'              => 'premium_notbar_close_typo',
                    'scheme'            => Scheme_Typography::TYPOGRAPHY_1,
                    'selector'          => '#premium-notbar-{{ID}} .premium-notbar-close',
            ]);
        
        $this->add_control('premium_notbar_close_backcolor',
            [
                'label'             => __('Background Color', 'premium-addons-pro'),
                'type'              => Controls_Manager::COLOR,
                'scheme'            => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors'         => [
                    '#premium-notbar-{{ID}} .premium-notbar-close'   => 'background-color: {{VALUE}};'
                ]
            ]);
        
        $this->add_control('premium_notbar_close_backcolor_hover',
            [
                'label'             => __('Hover Background Color', 'premium-addons-pro'),
                'type'              => Controls_Manager::COLOR,
                'scheme'            => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors'         => [
                    '#premium-notbar-{{ID}} .premium-notbar-close:hover'   => 'background-color: {{VALUE}};'
                ]
            ]);
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
                [
                    'name'              => 'premium_notbar_close_border',
                    'selector'          => '#premium-notbar-{{ID}} .premium-notbar-close',
                    ]
                );
        
        $this->add_control('premium_notbar_close_border_radius',
                [
                    'label'         => __('Border Radius', 'premium-addons-pro'),
                    'type'          => Controls_Manager::SLIDER,
                    'size_units'    => ['px', '%', 'em'],
                    'selectors'     => [
                        '#premium-notbar-{{ID}} .premium-notbar-close' => 'border-radius: {{SIZE}}{{UNIT}};'
                        ]
                    ]
                );
        
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
                [
                    'label'             => __('Shadow','premium-addons-pro'),
                    'name'              => 'premium_notbar_close_shadow',
                    'selector'          => '#premium-notbar-{{ID}} .premium-notbar-close',
                    ]
                );
        
        $this->add_responsive_control('premium_notbar_close_margin',
                [
                    'label'         => __('Margin', 'premium-addons-pro'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => [ 'px', 'em', '%' ],
                    'selectors'     => [
                        '#premium-notbar-{{ID}} .premium-notbar-close' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                        ]
                    ]
                );
        
        $this->add_responsive_control('premium_notbar_close_padding',
                [
                    'label'         => __('Padding', 'premium-addons-pro'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => [ 'px', 'em', '%' ],
                    'selectors'     => [
                        '#premium-notbar-{{ID}} .premium-notbar-close' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                        ]
                    ]
                );
        
        $this->end_controls_section();
    }
    
    /**
     * returns the responsive style based on Elementor's Breakpoints
	 * @access protected
	 * @return string
	 */
    protected function get_notbar_responsive_style() {
        
        $breakpoints = Responsive::get_breakpoints();
        $style = '<style>';
        $style .= '@media ( max-width: ' . $breakpoints['md'] . 'px ) {';
        $style .= '.premium-notbar-content-wrapper, .premium-notbar-icon-text-container {';
        $style .= 'flex-direction: column !important; -moz-flex-direction: column !important; -webkit-flex-direction: column !important;';
        $style .= '}';
        $style .= '}';
        $style .= '</style>';
        
        return $style;
        
    }

    /**
	 * Render Alert Box widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
    protected function render() {
        
        $settings       = $this->get_settings_for_display();
        
        $id             = $this->get_id();
        
        $icon_type      = $settings['premium_notbar_icon_selector'];

        $bar_position   = $settings['premium_notbar_position'];
        
        $bar_layout     = 'premium-notbar-' . $settings['premium_notbar_top_select'];
        
        $bar_width      = $settings['premium_notbar_width'];
        
        $content_type   = $settings['premium_notbar_content_type'];

        if( 'template' === $content_type ) {
            $template       = $settings['premium_notbar_content_temp'];
        }
        
        if( $bar_position != 'top' ) {
            $this->add_render_attribute( 'wrap', 'class', 'premium-notbar-' . $bar_position );
            $this->add_render_attribute( 'button', 'class', 'premium-notbar-' . $bar_position );
        } elseif ( $bar_position == 'top' && is_user_logged_in() ) {
            $this->add_render_attribute( 'wrap', 'class', 'premium-notbar-edit-top' . $bar_layout  );
            $this->add_render_attribute( 'button', 'class', 'premium-notbar-edit-top' );
        } else {
            $this->add_render_attribute( 'wrap', 'class', [ 'premium-notbar-top' , $bar_layout ]);
            $this->add_render_attribute( 'button', 'class', 'premium-notbar-top' );
        }
        
        $this->add_render_attribute( 'button', 'type', 'button' );
        
        $this->add_render_attribute( 'button', 'id', 'premium-notbar-close-' . $id );
        
        $this->add_render_attribute( 'button', 'class', 'premium-notbar-close' );
        
        if( 'yes' === $settings['premium_notbar_link_switcher']  ) {
            
            if( $settings['premium_notbar_link_selection'] == 'url' ){
                $button_url = $settings['premium_notbar_link']['url'];
            } else {
                $button_url = get_permalink( $settings['premium_notbar_existing_link'] );
            }
            
            if( ! empty( $button_url ) ) {
        
                $this->add_render_attribute( 'button', 'href', $button_url );
            
                if( ! empty( $settings['premium_notbar_link']['is_external'] ) )
                    $this->add_render_attribute( 'button', 'target', '_blank' );
            
                if( ! empty( $settings['premium_notbar_link']['nofollow'] ) )
                    $this->add_render_attribute( 'button', 'rel', 'nofollow' );
            }
        }
        
        
        if( 'font-awesome-icon' === $icon_type ) {

            if( ! empty( $settings['premium_notbar_icon'] ) ) {
                $this->add_render_attribute( 'icon', [
                    'class' => [
                        'premium-notbar-icon',
                        $settings['premium_notbar_icon']
                    ],
                    'aria-hidden' => 'true'
                ]);
                
            }

            $migrated = isset( $settings['__fa4_migrated']['premium_notbar_icon_updated'] );
            $is_new = empty( $settings['premium_notbar_icon'] ) && Icons_Manager::is_migration_allowed();

        } elseif( 'custom-image' === $icon_type ) {

            $src        = $settings['premium_notbar_custom_image']['url'];

            $alt        = Control_Media::get_image_alt( $settings['premium_notbar_custom_image'] );

            $this->add_render_attribute( 'image',
                [
                    'class' => 'premium-notbar-custom-image',
                    'src'   => $src,
                    'alt'   => $alt
                ]
            );

        } else {

            $this->add_render_attribute( 'alert_lottie', [
                'class' => [
                    'premium-notbar-icon-lottie',
                    'premium-lottie-animation'
                ],
                'data-lottie-url' => $settings['lottie_url'],
                'data-lottie-loop' => $settings['lottie_loop'],
                'data-lottie-reverse' => $settings['lottie_reverse']
            ]);

        }

        $bar_settings   = [
            'layout'    => $bar_width,
            'location'  => $bar_position,
            'position'  => $bar_layout,
            'varPos'    => !empty( $settings['premium_notbar_float_pos'] ) ? $settings['premium_notbar_float_pos'] : '10%',
            'responsive'=> ( $settings['premium_notbar_responsive_switcher'] == 'yes' ) ? true : false,
            'hideTabs'  => ( $settings['premium_notbar_hide_tabs'] == 'yes' ) ? true: false,
            'tabSize'   => ( $settings['premium_notbar_hide_tabs'] == 'yes' ) ? Responsive::get_breakpoints()['lg'] : Responsive::get_breakpoints()['lg'],
            'hideMobs'  => ( $settings['premium_notbar_hide_mobs'] == 'yes' ) ? true: false,
            'mobSize'   => ( $settings['premium_notbar_hide_mobs'] == 'yes' ) ? Responsive::get_breakpoints()['md'] : Responsive::get_breakpoints()['md'],
            'cookies'   => ( $settings['premium_notbar_cookies'] == 'yes' ) ? true : false,
            'interval'  => ! empty( $settings['premium_notbar_interval'] ) ? $settings['premium_notbar_interval'] : 1,
            'id'        => $id,
            'link'      => $settings['premium_notbar_link_switcher']
        ];

        $this->add_render_attribute( 'premium_notbar_text', 'class', 'premium-notbar-text' );
        
        $this->add_render_attribute( 'alert', [
            'id' => 'premium-notbar-outer-container-' . $id,
            'class' => [
                'premium-notbar-outer-container',
                'premium-notbar-' . $settings['premium_notbar_content_type']
            ],
            'data-settings' => wp_json_encode( $bar_settings )
        ] );
        
        $this->add_render_attribute( 'wrap', [
            'id' => 'premium-notbar-' . $id,
            'class' => [
                'premium-notbar',
                'premium-notbar-' . $bar_width,
            ]
        ] );
        
    ?>

    <div <?php echo $this->get_render_attribute_string('alert'); ?>>
    
        <?php if( ! Plugin::instance()->editor->is_edit_mode() && 'yes' === $settings['enable_background_overlay'] ) : ?>
            <div class="premium-notbar-background-overlay"></div>
        <?php endif; ?>
        <div <?php echo $this->get_render_attribute_string('wrap'); ?>>
            <div class="premium-notbar-content-wrapper">
                <div class="premium-notbar-icon-text-container">
                    <?php if($settings['premium_notbar_icon_switcher'] === 'yes' && $settings['premium_notbar_content_type'] == 'editor' ) : ?>
                        <div class="premium-notbar-icon-wrap">
                            <?php if( $icon_type === 'font-awesome-icon' ) :
                                if ( $is_new || $migrated ) :
                                    Icons_Manager::render_icon( $settings['premium_notbar_icon_updated'], [ 'class' => 'premium-notbar-icon', 'aria-hidden' => 'true' ] );
                                else: ?>
                                    <i <?php echo $this->get_render_attribute_string( 'icon' ); ?>></i>
                                <?php endif;
                            elseif( 'custom-image'  === $icon_type ): ?>
                                <img <?php echo $this->get_render_attribute_string( 'image' ); ?>>
                            <?php else: ?>
                                <div <?php echo $this->get_render_attribute_string( 'alert_lottie' ); ?>></div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <?php if( 'editor' === $content_type ) : ?>
                        <span <?php echo $this->get_render_attribute_string('premium_notbar_text'); ?>>
                            <?php echo $settings['premium_notbar_text']; ?>
                        </span>
                    <?php else:
                        echo $this->getTemplateInstance()->get_template_content( $template );
                    endif; ?>
                </div>
                <div class="premium-notbar-button-wrap">
                    <a <?php echo $this->get_render_attribute_string('button'); ?>>
                        <?php echo esc_html( $settings['premium_notbar_close_text'] ); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <?php echo $this->get_notbar_responsive_style();
    }
}