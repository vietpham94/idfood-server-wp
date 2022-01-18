<?php

/**
 * Class: Premium_Iconbox
 * Name: Icon Box 
 * Slug: premium-addon-icon-box
 */
namespace PremiumAddonsPro\Widgets;

// Elementor Classes.
use Elementor\Icons_Manager;
use Elementor\Control_Media;
use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Image_Size;

// PremiumAddons Classes.
use PremiumAddons\Includes\Helper_Functions;
use PremiumAddons\Includes\Premium_Template_Tags;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

/**
 * Class Premium_Iconbox
 */
class Premium_Iconbox extends Widget_Base {

    public function getTemplateInstance() {
        return $this->templateInstance = Premium_Template_Tags::getInstance();
    }

    public function get_name() {
        return 'premium-addon-icon-box';
    }

    public function get_title() {
        return sprintf( '%1$s %2$s', Helper_Functions::get_prefix(), __('Icon Box', 'premium-addons-pro') );
    }

    public function get_icon() {
        return 'pa-pro-icon-box';
    }

    public function get_categories() {
        return [ 'premium-elements' ];
    }
    
    public function get_script_depends() {
        return [
            'tilt-js',
            'lottie-js',
            'premium-pro-js'
        ];
    }

    public function get_custom_help_url() {
		return 'https://premiumaddons.com/support/';
	}
    
    /**
	 * Register Icon Box controls.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
    protected function _register_controls() {

        
        $this->start_controls_section('section_general',
            [
                'label'        => __('General', 'premium-addons-pro'),
            ]
        );
        
        $this->add_control('mouse_tilt',
            [
                'label'         => __('Enable Mouse Tilt', 'premium-addons-pro'),
                'type'          => Controls_Manager::SWITCHER,
                'return_value'  => 'true'
            ]
        );
        
        $this->add_control('mouse_tilt_rev',
            [
                'label'         => __('Reverse', 'premium-addons-pro'),
                'type'          => Controls_Manager::SWITCHER,
                'return_value'  => 'true',
                'condition'     => [
                    'mouse_tilt'    => 'true'
                ]
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_icon_box_icon',
            [
                'label'        => __('Icon', 'premium-addons-pro'),
            ]
        );
        
        $this->add_control('premium_icon_box_selector',
            [
                'label'        => __('Icon Type','premium-addons-pro'),
                'type'         => Controls_Manager::SELECT,
                'default'      => 'font-awesome-icon',
                'options'      =>[
                    'font-awesome-icon' => __('Font Awesome Icon','premium-addons-pro'),
                    'custom-image'      => __('Custom Image','premium-addons-pro'),
                    'animation'         => __('Lottie Animation', 'premium-addons-pro')
                ],
            ]
        );

        $this->add_control('premium_icon_box_font_updated',
            [
                'label'             => __('Icon','premium-addons-pro'),
                'type'              => Controls_Manager::ICONS,
                'fa4compatibility'  => 'premium_icon_box_font',
                'default' => [
                    'value'     => 'fas fa-star',
                    'library'   => 'fa-solid',
                ],
                'condition'    => [
                    'premium_icon_box_selector'         => 'font-awesome-icon'
                ]
            ]
        );
        
        $this->add_control('premium_icon_box_custom_image',
            [
                'label'        => __('Custom Image','premium-addons-pro'),
                'type'         => Controls_Manager::MEDIA,
                'dynamic'       => [ 'active' => true ],
                'default'      => [
                    'url'   => Utils::get_placeholder_image_src()
                ],
                'condition'    => [
                    'premium_icon_box_selector'         => 'custom-image'
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
                    'premium_icon_box_selector'         => 'animation'
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
                    'premium_icon_box_selector'         => 'animation'
                ]
            ]
        );

        $this->add_control('lottie_reverse',
            [
                'label'         => __('Reverse','premium-addons-pro'),
                'type'          => Controls_Manager::SWITCHER,
                'return_value'  => 'true',
                'condition'     => [
                    'premium_icon_box_selector'         => 'animation'
                ]
            ]
        );
        
        $this->add_responsive_control('premium_icon_box_font_size',
            [
                'label'         => __('Size','premium-addons-pro'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', "em"],
                'range'         => [
                    'px'    => [
                        'min'   => 1, 
                        'max'   => 300,
                    ],
                    'em'    => [
                        'min'   => 1, 
                        'max'   => 30,
                    ],
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-icon-box-icon-container i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .premium-icon-box-icon-container .premium-icon-box-animation, {{WRAPPER}} div:not(.premium-lottie-animation) svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
                ],
                'condition'     => [
                    'premium_icon_box_selector!'         => 'custom-image'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name'          => 'premium_icon_box_image_size', 
                'default'       => 'full',
                'condition'     => [
                    'premium_icon_box_selector'         => 'custom-image'
                ]
            ]
        );
        
        $this->add_control('premium_icon_box_hover',
            [
                'label'        => __('Hover Animation','premuim_elementor'),
                'type'         => Controls_Manager::SELECT,
                'options'      => [
                    'none'                  => __('None','premium-addons-pro'),
                    'hvr-pulse-grow'        => __('Pulse','premium-addons-pro'),
                    'rotate'                => __('Rotate','premium-addons-pro'),
                    'hvr-buzz'              => __('Buzz','premium-addons-pro'),
                    'd-rotate'              => __('3D Rotate','premium-addons-pro'),
                    'hvr-float-shadow'      => __('Drop Shadow','premium-addons-pro'),
                    'hvr-wobble-vertical'   => __('Wobble Vertical','premium-addons-pro')
                ],
                'default'       => 'none',
            ]
        );

        $this->add_control('premium_icon_box_icon_flex_pos',
            [
                'label'         => __( 'Icon Position', 'premium-addons-pro' ),
                'type'          => Controls_Manager::CHOOSE,
                'default'       => 'top',
                'options'       => [
                    'left' => [
                        'title' => __( 'Left', 'premium-addons-pro' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'top' => [
                        'title' => __( 'Top', 'premium-addons-pro' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'premium-addons-pro' ),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'default'       => 'top',
                'toggle'        => false,
            ]
        );

        $this->add_control('premium_icon_box_icon_flex_ver_pos',
            [
                'label'         => __( 'Vertical Alignment', 'premium-addons-pro' ),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'top'   => __( 'Top', 'premium-addons-pro' ),
                    'middle'=> __( 'Middle', 'premium-addons-pro' ),
                    'bottom'=> __( 'Bottom', 'premium-addons-pro' ),
                ],
                'default'       => 'top',
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_icon_box_title_section',
            [
                'label'        => __('Title', 'premium-addons-pro'),
            ]
        );
        
        $this->add_control('premium_icon_box_title_switcher',
            [
                'label'         => __('Title', 'premium-addons-pro'),
                'type'          => Controls_Manager::SWITCHER,
                'default'       => 'yes'
            ]
        );
        
        $this->add_control('premium_icon_box_title', 
            [
                'label'         => __('Title','premium-addons-pro'),
                'type'          => Controls_Manager::TEXT,
                'dynamic'       => [ 'active' => true ],
                'default'       =>'Premium Icon Box',
                'condition'     => [
                    'premium_icon_box_title_switcher'    => 'yes'
                ]
            ]
        );
        
        $this->add_control('premium_icon_box_title_heading', 
            [
                'label'         => __('Title Heading','premium-addons-pro'),
                'type'          => Controls_Manager::SELECT,
                'default'       =>'h3',
                'options'       =>[
                    'h1'    => 'H1',
                    'h2'    => 'H2',
                    'h3'    => 'H3',
                    'h4'    => 'H4',
                    'h5'    => 'H5',
                    'h6'    => 'H6',
                ],
                'condition'     => [
                    'premium_icon_box_title_switcher'    => 'yes'
                ]
            ]
        );

        $this->add_control('premium_icon_box_label', 
            [
                'label'         => __('Label','premium-addons-pro'),
                'type'          => Controls_Manager::TEXT,
                'dynamic'       => [ 'active' => true ],
                'condition'     => [
                    'premium_icon_box_title_switcher'    => 'yes'
                ]
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_icon_box_desc',
            [
                'label'         => __('Description', 'premium-addons-pro'),
            ]
        );
        
        $this->add_control('premium_icon_box_desc_switcher',
            [
                'label'         => __('Description', 'premium-addons-pro'),
                'type'          => Controls_Manager::SWITCHER,
                'default'       => 'yes'
            ]
        );

        
        $this->add_control('premium_icon_box_content',
            [
                'label'         => __('Content','premium-addons-pro'),
                'type'          => Controls_Manager::WYSIWYG,
                'dynamic'           => [ 'active' => true ],
                'default'       => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
                'condition'     => [
                    'premium_icon_box_desc_switcher'    => 'yes'
                ]
            ]
        );

        $this->add_responsive_control('premium_icon_box_align',
            [
                'label'         => __('Alignment','premuim_elementor'),
                'type'          => Controls_Manager::CHOOSE,
                'options'       => [
                    'left'      => [
                        'title'=> __( 'Left', 'premium-addons-pro' ),
                        'icon' => 'fa fa-align-left',   
                    ],
                    'center'     => [
                        'title'=> __( 'Center', 'premium-addons-pro' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right'     => [
                        'title'=> __( 'Right', 'premium-addons-pro' ),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'default'       => 'center',
                'selectors'     => [
                    '{{WRAPPER}} .premium-icon-box-container-in' => 'text-align: {{VALUE}};',
                ],
                'render_type' => 'template'
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_icon_box_link_section',
            [
                'label'        => __('Link', 'premium-addons-pro'),
            ]
        );
        
        $this->add_control('premium_icon_box_link_switcher',
            [
                'label'         => __('Link', 'premium-addons-pro'),
                'type'          => Controls_Manager::SWITCHER,
                'default'       => 'yes'
            ]
        );

        $this->add_control('box_link',
            [
                'label'         => __('Whole Box Link', 'premium-addons-pro'),
                'type'          => Controls_Manager::SWITCHER,
                'condition'     => [
                    'premium_icon_box_link_switcher'    => 'yes',
                ]
            ]
        );

        $this->add_control('keep_text_link',
            [
                'label'         => __('Keep Button Link', 'premium-addons-pro'),
                'type'          => Controls_Manager::SWITCHER,
                'prefix_class'  => 'premium-icon-box-whole-text-',
                'condition'     => [
                    'premium_icon_box_link_switcher'    => 'yes',
                    'box_link'  => 'yes'
                ],
                'render_type'   => 'template'
            ]
        );

        $this->add_control('premium_icon_box_link_text_switcher',
            [
                'label'         => __('Text', 'premium-addons-pro'),
                'type'          => Controls_Manager::SWITCHER,
                'default'       => 'yes',
                'conditions'    => [
                    'terms' => [
                        [
                            'name' => 'premium_icon_box_link_switcher',
                            'value' => 'yes',
                        ],
                        [
                            'relation'      =>  'or',
                            'terms'         => [
                                [
                                    'name'  =>  'box_link',
                                    'value'  => ''
                                ],
                                [
                                    'name'  =>  'keep_text_link',
                                    'value'  => 'yes'
                                ]
                            ],
                        ]
                    ]
                ],
            ]
        );
        
        $this->add_control('premium_icon_box_more_text', 
            [
                'label'         => __('Text','premium-addons-pro'),
                'type'          => Controls_Manager::TEXT,
                'dynamic'       => [ 'active' => true ],
                'default'       => 'Click Here',
                'conditions'    => [
                    'terms' => [
                        [
                            'name' => 'premium_icon_box_link_switcher',
                            'value' => 'yes',
                        ],
                        [
                            'name' => 'premium_icon_box_link_text_switcher',
                            'value' => 'yes',
                        ],
                        [
                            'relation'      =>  'or',
                            'terms'         => [
                                [
                                    'name'  =>  'box_link',
                                    'value'  => ''
                                ],
                                [
                                    'name'  =>  'keep_text_link',
                                    'value'  => 'yes'
                                ]
                            ],
                        ]
                    ]
                ],
            ]
        );

        $this->add_control('premium_icon_box_icon_text_pos',
            [
                'label'         => __( 'Position', 'premium-addons-pro' ),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'left'   => __( 'Left', 'premium-addons-pro' ),
                    'right'=> __( 'Right', 'premium-addons-pro' ),
                    'bottom'=> __( 'Bottom', 'premium-addons-pro' ),
                ],
                'default'       => 'bottom',
                'conditions'    => [
                    'terms' => [
                        [
                            'name' => 'premium_icon_box_link_switcher',
                            'value' => 'yes',
                        ],
                        [
                            'relation'      =>  'or',
                            'terms'         => [
                                [
                                    'name'  =>  'box_link',
                                    'value'  => ''
                                ],
                                [
                                    'name'  =>  'keep_text_link',
                                    'value'  => 'yes'
                                ]
                            ],
                        ],
                        [
                            'relation'      =>  'or',
                            'terms'         => [
                                [
                                    'name'  =>  'premium_icon_box_link_text_switcher',
                                    'value'  => 'yes'
                                ],
                                [
                                    'name'  =>  'premium_icon_box_link_icon_switcher',
                                    'value'  => 'yes'
                                ]
                            ],
                        ]
                    ]
                ],
            ]
        );

        

        $this->add_control('premium_icon_box_icon_text_ver_pos',
            [
                'label'         => __( 'Vertical Alignment', 'premium-addons-pro' ),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'top'   => __( 'Top', 'premium-addons-pro' ),
                    'middle'=> __( 'Middle', 'premium-addons-pro' ),
                    'bottom'=> __( 'Bottom', 'premium-addons-pro' ),
                ],
                'default'       => 'top',
                'conditions'    => [
                    'terms' => [
                        [
                            'name' => 'premium_icon_box_link_switcher',
                            'value' => 'yes',
                        ],
                        [
                            'relation'      =>  'or',
                            'terms'         => [
                                [
                                    'name'  =>  'box_link',
                                    'value'  => ''
                                ],
                                [
                                    'name'  =>  'keep_text_link',
                                    'value'  => 'yes'
                                ]
                            ],
                        ],
                        [
                            'relation'      =>  'or',
                            'terms'         => [
                                [
                                    'name'  =>  'premium_icon_box_icon_text_pos',
                                    'value'  => 'right'
                                ],
                                [
                                    'name'  =>  'premium_icon_box_icon_text_pos',
                                    'value'  => 'left'
                                ]
                            ],
                        ],
                        [
                            'relation'      =>  'or',
                            'terms'         => [
                                [
                                    'name'  =>  'premium_icon_box_link_text_switcher',
                                    'value'  => 'yes'
                                ],
                                [
                                    'name'  =>  'premium_icon_box_link_icon_switcher',
                                    'value'  => 'yes'
                                ]
                            ],
                        ]
                    ]
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-icon-box-more' => 'display:flex !important;',
                ],
                'render_type' => 'template'
            ]
        );
        
        $this->add_control('premium_icon_box_link_icon_switcher',
            [
                'label'         => __('Icon', 'premium-addons-pro'),
                'type'          => Controls_Manager::SWITCHER,
                'conditions'    => [
                    'terms' => [
                        [
                            'name' => 'premium_icon_box_link_switcher',
                            'value' => 'yes',
                        ],
                        [
                            'relation'      =>  'or',
                            'terms'         => [
                                [
                                    'name'  =>  'box_link',
                                    'value'  => ''
                                ],
                                [
                                    'name'  =>  'keep_text_link',
                                    'value'  => 'yes'
                                ]
                            ],
                        ]
                    ]
                ],
            ]
        );
        
        $this->add_control('premium_icon_box_more_icon_updated',
            [
                'label'         => __('Icon','premium-addons-pro'),
                'type'          => Controls_Manager::ICONS,
                'fa4compatibility'  => 'premium_icon_box_more_icon',
                'default' => [
                    'value'     => 'fas fa-link',
                    'library'   => 'fa-solid',
                ],
                'conditions'    => [
                    'terms' => [
                        [
                            'name' => 'premium_icon_box_link_switcher',
                            'value' => 'yes',
                        ],
                        [
                            'name' => 'premium_icon_box_link_icon_switcher',
                            'value' => 'yes',
                        ],
                        [
                            'relation'      =>  'or',
                            'terms'         => [
                                [
                                    'name'  =>  'box_link',
                                    'value'  => ''
                                ],
                                [
                                    'name'  =>  'keep_text_link',
                                    'value'  => 'yes'
                                ]
                            ],
                        ]
                    ]
                ],
            ]
        );

        $this->add_control('premium_icon_box_icon_position', 
            [
                'label'         => __('Position', 'premium-addons-pro'),
                'type'          => Controls_Manager::SELECT,
                'default'       => 'after',
                'options'       => [
                    'before'        => __('Before', 'premium-addons-pro'),
                    'after'         => __('After', 'premium-addons-pro'),
                ],
                'conditions'    => [
                    'terms' => [
                        [
                            'name' => 'premium_icon_box_link_switcher',
                            'value' => 'yes',
                        ],
                        [
                            'name' => 'premium_icon_box_link_icon_switcher',
                            'value' => 'yes',
                        ],
                        [
                            'relation'      =>  'or',
                            'terms'         => [
                                [
                                    'name'  =>  'box_link',
                                    'value'  => ''
                                ],
                                [
                                    'name'  =>  'keep_text_link',
                                    'value'  => 'yes'
                                ]
                            ],
                        ]
                    ]
                ],
                'label_block'   => true,
            ]
        );
    
        $this->add_responsive_control('premium_icon_box_link_icon_size',
            [
                'label'         => __('Size','premium-addons-pro'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', "em"],
                'range'         => [
                    'px'    => [
                        'min'   => 1, 
                        'max'   => 100,
                    ],
                    'em'    => [
                        'min'   => 1, 
                        'max'   => 15,
                    ],
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-icon-box-link i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .premium-icon-box-link svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
                ],
                'conditions'    => [
                    'terms' => [
                        [
                            'name' => 'premium_icon_box_link_switcher',
                            'value' => 'yes',
                        ],
                        [
                            'name' => 'premium_icon_box_link_icon_switcher',
                            'value' => 'yes',
                        ],
                        [
                            'relation'      =>  'or',
                            'terms'         => [
                                [
                                    'name'  =>  'box_link',
                                    'value'  => ''
                                ],
                                [
                                    'name'  =>  'keep_text_link',
                                    'value'  => 'yes'
                                ]
                            ],
                        ]
                    ]
                ],
            ]
        );

        $this->add_control('premium_icon_box_link_selection', 
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
                    'premium_icon_box_link_switcher'    => 'yes'
                ],
            ]
        );

        $this->add_control('premium_icon_box_link',
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
                    'premium_icon_box_link_selection' => 'url',
                    'premium_icon_box_link_switcher'    => 'yes'
                ]
            ]
        );
        
        $this->add_control('premium_icon_box_existing_link',
            [
                'label'         => __('Existing Page', 'premium-addons-pro'),
                'type'          => Controls_Manager::SELECT2,
                'options'       => $this->getTemplateInstance()->get_all_posts(),
                'multiple'      => false,
                'condition'     => [
                    'premium_icon_box_link_selection'     => 'link',
                    'premium_icon_box_link_switcher'    => 'yes'
                ],
                'label_block'   => true,
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_icon_box_back_icon_section',
            [
                'label'        => __('Back Icon', 'premium-addons-pro'),
            ]
        );
        
        $this->add_control('premium_icon_box_back_icon_switcher',
            [
                'label'         => __('Show Back Icon', 'premium-addons-pro'),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => 'Show',
                'label_hide'    => 'Hide',
                'default'       => 'yes'
            ]
        );
        
        $this->add_responsive_control('premium_icon_box_back_icon_hor',
            [
                'label'         => __('Horizontal Offset','premium-addons-pro'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', "em" , '%'],
                'range'         => [
                    'px'    => [
                        'min'   => 1, 
                        'max'   => 300,
                    ],
                    'em'    => [
                        'min'   => 1, 
                        'max'   => 30,
                    ],
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-icon-box-big' => 'right: {{SIZE}}{{UNIT}};',
                ],
                'condition'     => [
                    'premium_icon_box_back_icon_switcher'         => 'yes'
                ]
            ]
        );
        
        $this->add_responsive_control('premium_icon_box_back_icon_ver',
            [
                'label'         => __('Vertical Offset','premium-addons-pro'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', "em" , '%'],
                'range'         => [
                    'px'    => [
                        'min'   => 1, 
                        'max'   => 300,
                    ],
                    'em'    => [
                        'min'   => 1, 
                        'max'   => 30,
                    ],
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-icon-box-big'     => 'bottom: {{SIZE}}{{UNIT}};',
                ],
                'condition'     => [
                    'premium_icon_box_back_icon_switcher'   => 'yes'
                ]
            ]
        );
        
        $this->add_responsive_control('premium_icon_box_back_icon_opacity',
            [
                'label'         => __('Opacity','premium-addons-pro'),
                'type'          => Controls_Manager::SLIDER,
                'range'         => [
                    'px'    => [
                        'min'   => 0, 
                        'max'   => 1,
                        'step'  => 0.1
                    ],
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-icon-box-big' => 'opacity: {{SIZE}};',
                ],
                'condition'     => [
                    'premium_icon_box_back_icon_switcher'         => 'yes'
                ]
            ]
        );

        $this->add_responsive_control('premium_icon_box_back_icon_rotate',
            [
                'label'         => __('Rotate', 'premium-addons-pro'),
                'type'          => Controls_Manager::NUMBER,
                'description'   => __('Set rotation value in degrees', 'premium-addons-pro'),
                'min'           => -180,
                'max'           => 180,
                'selectors'     => [
                    '{{WRAPPER}} .premium-icon-box-big' => '-webkit-transform: rotate({{VALUE}}deg); -moz-transform: rotate({{VALUE}}deg); -o-transform: rotate({{VALUE}}deg); transform: rotate({{VALUE}}deg);'
                ],
                'condition'     => [
                    'premium_icon_box_back_icon_switcher'   => 'yes'
                ]
            ]
        );
        
        $this->add_control('premium_icon_box_back_icon_hover',
            [
                'label'         => __('Hover Effect ', 'premium-addons-pro'),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => 'Show',
                'label_hide'    => 'Hide',
                'default'       => 'yes',
                'condition'     => [
                    'premium_icon_box_back_icon_switcher'   => 'yes'
                ]
            ]
        );
        
        $this->end_controls_section();

        $this->start_controls_section('section_pa_docs',
            [
                'label'         => __('Helpful Documentations', 'premium-addons-for-elementor'),
            ]
        );

        $doc1_url = Helper_Functions::get_campaign_link( 'https://premiumaddons.com/docs/icon-box-widget-tutorial/', 'editor-page', 'wp-editor', 'get-support' ); 

        $this->add_control('doc_1',
            [
                'type'            => Controls_Manager::RAW_HTML,
                'raw'             => sprintf(  '<a href="%s" target="_blank">%s</a>', $doc1_url ,__( 'Getting started »', 'premium-addons-for-elementor' ) ),
                'content_classes' => 'editor-pa-doc',
            ]
        );

        $this->end_controls_section();
        
        /*Start Icon Style*/
        $this->start_controls_section('premium_icon_box_icon_style',
            [
                'label'         => __('Icon', 'premium-addons-pro'),
                'tab'           => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->start_controls_tabs('premium_icon_box_style_tabs');
        
        $this->start_controls_tab('premium_icon_box_style_normal',
            [
                'label'             => __('Normal', 'premium-addons-pro'),
            ]
        );
        
        $this->add_control('premium_icon_box_icon_color',
            [
                'label'         => __('Color','premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'      => Scheme_Color::get_type(),
                    'value'     => Scheme_Color::COLOR_2,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-icon-box-icon' => 'color:{{VALUE}};'
                ],
                'condition'     => [
                    'premium_icon_box_selector'             => 'font-awesome-icon'
                ]
            ]
        );
        
        $this->add_control('premium_icon_box_background_normal',
            [
                'label'         => __('Background Color', 'premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .premium-icon-box-icon, {{WRAPPER}} .premium-icon-box-custom-image, {{WRAPPER}} .premium-icon-box-animation'  => 'background-color: {{VALUE}};',
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(), 
            [
                'name'          => 'premium_icon_box_border_normal',
                'selector'      => '{{WRAPPER}} .premium-icon-box-icon, {{WRAPPER}} .premium-icon-box-custom-image, {{WRAPPER}} .premium-icon-box-animation',
            ]
        );
        
        $this->add_control('premium_icon_box_border_radius_normal',
            [
                'label'         => __('Border Radius', 'premium-addons-pro'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%' ,'em'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-icon-box-icon, {{WRAPPER}} .premium-icon-box-custom-image, {{WRAPPER}} .premium-icon-box-animation' => 'border-radius: {{SIZE}}{{UNIT}};'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'label'         => __('Shadow','premium-addons-pro'),
                'name'          => 'premium_icon_box_icon_shadow_normal',
                'selector'      => '{{WRAPPER}} .premium-icon-box-icon',
                'condition'     => [
                    'premium_icon_box_selector'         => 'font-awesome-icon'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'label'         => __('Shadow','premium-addons-pro'),
                'name'          => 'premium_icon_box_image_shadow_normal',
                'selector'      => '{{WRAPPER}} .premium-icon-box-custom-image',
                'condition'     => [
                    'premium_icon_box_selector'         => 'custom-image'
                ]
            ]
        );
        
        $this->add_responsive_control('premium_icon_box_margin_normal',
            [
                'label'         => __('Margin', 'premium-addons-pro'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-icon-box-icon, {{WRAPPER}} .premium-icon-box-custom-image, {{WRAPPER}} .premium-icon-box-animation' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );
        
        $this->add_responsive_control('premium_icon_box_padding_normal',
            [
                'label'         => __('Padding', 'premium-addons-pro'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-icon-box-icon, {{WRAPPER}} .premium-icon-box-custom-image, {{WRAPPER}} .premium-icon-box-animation' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );
        
        $this->end_controls_tab();
        
        $this->start_controls_tab('premium_icon_box_style_hover',
            [
                'label'         => __('Hover', 'premium-addons-pro'),
            ]
        );
        
        $this->add_control('premium_icon_box_icon_color_hover',
            [
                'label'         => __('Color', 'premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-icon-box-container-out:hover .premium-icon-box-icon'   => 'color: {{VALUE}};',
                ],
                'condition'     => [
                    'premium_icon_box_selector'         => 'font-awesome-icon'
                ]
            ]
        );
        
        $this->add_control('premium_icon_box_background_hover',
            [
                'label'             => __('Background Color', 'premium-addons-pro'),
                'type'              => Controls_Manager::COLOR,
                'selectors'          => [
                    '{{WRAPPER}} .premium-icon-box-container-out:hover .premium-icon-box-icon, {{WRAPPER}} .premium-icon-box-container-out:hover .premium-icon-box-custom-image, {{WRAPPER}} .premium-icon-box-container-out:hover .premium-icon-box-animation' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(), 
            [
                'name'          => 'premium_icon_box_border_hover',
                'selector'      => '{{WRAPPER}} .premium-icon-box-container-out:hover .premium-icon-box-icon, {{WRAPPER}} .premium-icon-box-container-out:hover .premium-icon-box-custom-image, {{WRAPPER}} .premium-icon-box-container-out:hover .premium-icon-box-animation',
            ]
        );
        
        $this->add_control('premium_icon_box_border_radius_hover',
            [
                'label'         => __('Border Radius', 'premium-addons-pro'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%' ,'em'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-icon-box-container-out:hover .premium-icon-box-icon, {{WRAPPER}} .premium-icon-box-container-out:hover .premium-icon-box-custom-image, {{WRAPPER}} .premium-icon-box-container-out:hover .premium-icon-box-animation' => 'border-radius: {{SIZE}}{{UNIT}};'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'label'         => __('Shadow','premium-addons-pro'),
                'name'          => 'premium_icon_box_icon_shadow_hover',
                'selector'      => '{{WRAPPER}} .premium-icon-box-container-out:hover .premium-icon-box-icon',
                'condition'     => [
                    'premium_icon_box_selector'         => 'font-awesome-icon'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'label'         => __('Shadow','premium-addons-pro'),
                'name'          => 'premium_icon_box_image_shadow_hover',
                'selector'      => '{{WRAPPER}} .premium-icon-box-container-out:hover .premium-icon-box-custom-image',
                'condition'     => [
                    'premium_icon_box_selector'         => 'custom-image'
                ]
            ]
        );
        
        $this->add_responsive_control('premium_icon_box_margin_hover',
            [
                'label'         => __('Margin', 'premium-addons-pro'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-icon-box-container-out:hover .premium-icon-box-icon, {{WRAPPER}} .premium-icon-box-container-out:hover .premium-icon-box-custom-image, {{WRAPPER}} .premium-icon-box-container-out:hover .premium-icon-box-animation' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );
        
        $this->add_responsive_control('premium_icon_box_padding_hover',
            [
                'label'         => __('Padding', 'premium-addons-pro'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-icon-box-container-out:hover .premium-icon-box-icon, {{WRAPPER}} .premium-icon-box-container-out:hover .premium-icon-box-custom-image, {{WRAPPER}} .premium-icon-box-container-out:hover .premium-icon-box-animation' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        $this->end_controls_section();
    
        $this->start_controls_section('premium_icon_box_title_style',
            [
                'label'         => __('Title', 'premium-addons-pro'),
                'tab'           => Controls_Manager::TAB_STYLE,
                'condition'     => [
                    'premium_icon_box_title_switcher'    => 'yes'
                ]
            ]
        );     
        
        $this->add_control('premium_icon_box_title_color',
            [
                'label'         => __('Text Color','premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'          => Scheme_Color::get_type(),
                    'value'         => Scheme_Color::COLOR_2,
                ],
               'selectors'      => [
                    '{{WRAPPER}} .premium-icon-box-title'  => 'color:{{VALUE}};'
                ]
            ]
        );
        
        $this->add_control('premium_icon_box_title_color_hover',
            [
                'label'         => __('Text Hover Color','premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'          => Scheme_Color::get_type(),
                    'value'         => Scheme_Color::COLOR_2,
                ],
               'selectors'     => [
                    '{{WRAPPER}} .premium-icon-box-container-out:hover .premium-icon-box-title'  => 'color:{{VALUE}}'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'          => 'title_typography',
                'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                'selector'      => '{{WRAPPER}} .premium-icon-box-title',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'          => 'premium_icon_box_title_shadow',
                'selector'      => '{{WRAPPER}} .premium-icon-box-title',
            ]
        );
        
        $this->add_control('premium_icon_box_title_background',
            [
                'label'         => __('Background Color', 'premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .premium-icon-box-title-container'  => 'background-color: {{VALUE}};',
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(), 
            [
                'name'          => 'premium_icon_box_title_border',
                'selector'      => '{{WRAPPER}} .premium-icon-box-title-container',
            ]
        );
        
        $this->add_control('premium_icon_box_title_border_radius',
            [
                'label'         => __('Border Radius', 'premium-addons-pro'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%' ,'em'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-icon-box-title-container' => 'border-radius: {{SIZE}}{{UNIT}};'
                ]
            ]
        );
        
        $this->add_responsive_control('premium_icon_box_title_margin',
            [
                'label'         => __('Margin', 'premium-addons-pro'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-icon-box-title-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );
        
        $this->add_responsive_control('premium_icon_box_title_padding',
            [
                'label'         => __('Padding', 'premium-addons-pro'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-icon-box-title-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );

        $this->add_control('heading',
            [
                'label'         => __('Label', 'premium-addons-pro'),
                'type'          => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $this->add_control('premium_icon_box_label_color',
            [
                'label'         => __('Color','premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'          => Scheme_Color::get_type(),
                    'value'         => Scheme_Color::COLOR_2,
                ],
               'selectors'      => [
                    '{{WRAPPER}} .premium-icon-box-label'  => 'color:{{VALUE}};'
               ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'          => 'label_typography',
                'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                'selector'      => '{{WRAPPER}} .premium-icon-box-label',
            ]
        );

        $this->add_responsive_control('premium_icon_box_label_margin',
            [
                'label'         => __('Margin', 'premium-addons-pro'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-icon-box-label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_icon_box_content_style',
            [
                'label'         => __('Description', 'premium-addons-pro'),
                'tab'           => Controls_Manager::TAB_STYLE,
                'condition'     => [
                    'premium_icon_box_desc_switcher'    => 'yes'
                ]
            ]
        );
        
        $this->add_control('premium_icon_box_content_color',
            [
                'label'         => __('Text Color','premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'          => Scheme_Color::get_type(),
                    'value'         => Scheme_Color::COLOR_2,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-icon-box-content-wrap'  => 'color: {{VALUE}};'
                ]
            ]
        );
        
        $this->add_control('premium_icon_box_content_color_hover',
            [
                'label'         => __('Text Hover Color','premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'          => Scheme_Color::get_type(),
                    'value'         => Scheme_Color::COLOR_2,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-icon-box-container-out:hover .premium-icon-box-content-wrap'  => 'color:{{VALUE}};'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'          => 'content_typography',
                'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                'selector'      => '{{WRAPPER}} .premium-icon-box-content-wrap',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'          => 'premium_icon_box_content_shadow',
                'selector'      => '{{WRAPPER}} .premium-icon-box-content-wrap',
            ]
        );
        
        $this->add_control('premium_icon_box_content_background',
            [
                'label'         => __('Background Color', 'premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .premium-icon-box-content'  => 'background-color: {{VALUE}};',
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(), 
            [
                'name'          => 'premium_icon_box_content_border',
                'selector'      => '{{WRAPPER}} .premium-icon-box-content',
            ]
        );
    
        $this->add_control('premium_icon_box_content_border_radius',
            [
                'label'         => __('Border Radius', 'premium-addons-pro'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%' ,'em'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-icon-box-content' => 'border-radius: {{SIZE}}{{UNIT}};'
                ]
            ]
        );
        
        $this->add_responsive_control('premium_icon_box_content_margin',
            [
                'label'         => __('Margin', 'premium-addons-pro'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-icon-box-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );
        
        $this->add_responsive_control('premium_icon_box_content_padding',
            [
                'label'         => __('Padding', 'premium-addons-pro'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-icon-box-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_icon_box_more_style',
            [
                'label'         => __('Link', 'premium-addons-pro'),
                'tab'           => Controls_Manager::TAB_STYLE,
                'conditions'    => [
                    'terms' => [
                        [
                            'name' => 'premium_icon_box_link_switcher',
                            'value' => 'yes',
                        ],
                        [
                            'relation'      =>  'or',
                            'terms'         => [
                                [
                                    'name'  =>  'box_link',
                                    'value'  => ''
                                ],
                                [
                                    'name'  =>  'keep_text_link',
                                    'value'  => 'yes'
                                ]
                            ],
                        ]
                    ]
                ],
            ]
        );
       
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'          => 'read_more_typography',
                'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                'selector'      => '{{WRAPPER}} .premium-icon-box-link',
                'condition'     => [
                    'premium_icon_box_link_text_switcher'   => 'yes',
                    'premium_icon_box_link_switcher'    => 'yes'
                ]
            ]
        );
        
        $this->start_controls_tabs('premium_icon_box_link_style_tabs');
        
        $this->start_controls_tab('premium_icon_box_link_style_normal',
            [
                'label'         => __('Normal', 'premium-addons-pro'),
            ]
        );
        
        $this->add_control('premium_icon_box_link_color',
            [
                'label'         => __('Text Color','premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'      => Scheme_Color::get_type(),
                    'value'     => Scheme_Color::COLOR_2,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-icon-box-link' => 'color:{{VALUE}};'
                ],  
            ]
        );
        
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'          => 'premium_icon_box_link_shadow_normal',
                'selector'      => '{{WRAPPER}} .premium-icon-box-link',
            ]
        );
        
        $this->add_control('premium_icon_box_link_background_normal',
            [
                'label'         => __('Background Color', 'premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .premium-icon-box-more'  => 'background-color: {{VALUE}};',
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(), 
            [
                'name'          => 'premium_icon_box_link_border_normal',
                'selector'      => '{{WRAPPER}} .premium-icon-box-more',
            ]
        );
        
        $this->add_control('premium_icon_box_link_border_radius_normal',
            [
                'label'         => __('Border Radius', 'premium-addons-pro'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%' ,'em'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-icon-box-more' => 'border-radius: {{SIZE}}{{UNIT}};'
                ]
            ]
        );
        
        $this->add_responsive_control('premium_icon_box_link_margin_normal',
            [
                'label'         => __('Margin', 'premium-addons-pro'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-icon-box-more' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );
        
        $this->add_responsive_control('premium_icon_box_link_padding_normal',
            [
                'label'         => __('Padding', 'premium-addons-pro'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-icon-box-more' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );
        
        $this->end_controls_tab();
        
        $this->start_controls_tab('premium_icon_box_link_style_hover',
            [
                'label'         => __('Hover', 'premium-addons-pro'),
            ]
        );
        
        $this->add_control('premium_icon_box_link_color_hover',
            [
                'label'         => __('Text Hover Color', 'premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'selectors'     => [
                    '{{WRAPPER}}.premium-icon-box-whole-text-yes:hover .premium-icon-box-link, {{WRAPPER}} .premium-icon-box-more:hover .premium-icon-box-link'   => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'          => 'premium_icon_box_link_shadow_hover',
                'selector'      => '{{WRAPPER}}.premium-icon-box-whole-text-yes:hover .premium-icon-box-more, {{WRAPPER}} .premium-icon-box-more:hover .premium-icon-box-link',
            ]
        );
        
        $this->add_control('premium_icon_box_link_background_hover',
            [
                'label'         => __('Background Color', 'premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}}.premium-icon-box-whole-text-yes:hover .premium-icon-box-more, {{WRAPPER}} .premium-icon-box-more:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(), 
            [
                'name'          => 'premium_icon_box_link_border_hover',
                'selector'      => '{{WRAPPER}}.premium-icon-box-whole-text-yes:hover .premium-icon-box-more, {{WRAPPER}} .premium-icon-box-more:hover',
            ]
        );
            
        $this->add_control('premium_icon_box_link_border_radius_hover',
            [
                'label'         => __('Border Radius', 'premium-addons-pro'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%' ,'em'],
                'selectors'     => [
                    '{{WRAPPER}}.premium-icon-box-whole-text-yes:hover .premium-icon-box-more, {{WRAPPER}} .premium-icon-box-more:hover' => 'border-radius: {{SIZE}}{{UNIT}};'
                ]
            ]
        );
        
        $this->add_responsive_control('premium_icon_box_link_margin_hover',
            [
                'label'         => __('Margin', 'premium-addons-pro'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}}.premium-icon-box-whole-text-yes:hover .premium-icon-box-more, {{WRAPPER}} .premium-icon-box-more:hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );
        
        $this->add_responsive_control('premium_icon_box_link_padding_hover',
            [
                'label'         => __('Padding', 'premium-addons-pro'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}}.premium-icon-box-whole-text-yes:hover .premium-icon-box-more, {{WRAPPER}} .premium-icon-box-more:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        $this->end_controls_section();
    
        $this->start_controls_section('premium_icon_box_inner_container_style',
            [
                'label'         => __('Inner Container', 'premium-addons-pro'),
                'tab'           => Controls_Manager::TAB_STYLE
            ]
        );
        
        $this->start_controls_tabs('premium_icon_box_inner_container_style_tabs');
        
        $this->start_controls_tab('premium_icon_box_inner_container_style_normal',
            [
                'label'         => __('Normal', 'premium-addons-pro'),
            ]
        );
        
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'          => 'premium_icon_box_inner_container_background',
                'types'         => [ 'classic' , 'gradient' ],
                'selector'      => '{{WRAPPER}} .premium-icon-box-container-in',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(), 
            [
                'name'          => 'premium_icon_box_inner_container_box_border',
                'selector'      => '{{WRAPPER}} .premium-icon-box-container-in',
            ]
        );
        
        $this->add_control('premium_icon_box_inner_container_border_radius',
            [
                'label'         => __('Border Radius', 'premium-addons-pro'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%' ,'em'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-icon-box-container-in' => 'border-radius: {{SIZE}}{{UNIT}};'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'label'         => __('Box Shadow','premium-addons-pro'),
                'name'          => 'premium_icon_box_inner_container_box_shadow',
                'selector'      => '{{WRAPPER}} .premium-icon-box-container-in',
            ]
        );
        
        $this->add_responsive_control('premium_icon_box_inner_container_margin',
            [
                'label'         => __('Margin', 'premium-addons-pro'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-icon-box-container-in' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );
        
        $this->add_responsive_control('premium_icon_box_inner_container_padding',
            [
                'label'         => __('Padding', 'premium-addons-pro'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-icon-box-container-in' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );
        
        $this->end_controls_tab();

        $this->start_controls_tab('premium_icon_box_inner_container_style_hover',
            [
                'label'         => __('Hover', 'premium-addons-pro'),
            ]
        );
        
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'          => 'premium_icon_box_inner_container_background_hover',
                'types'         => [ 'classic' , 'gradient' ],
                'selector'      => '{{WRAPPER}} .premium-icon-box-container-out:hover .premium-icon-box-container-in',
                ]
            );
        
        $this->add_group_control(
            Group_Control_Border::get_type(), 
            [
                'name'          => 'premium_icon_box_inner_container_border_hover',
                'selector'      => '{{WRAPPER}} .premium-icon-box-container-out:hover .premium-icon-box-container-in',
            ]
        );
        
        $this->add_control('premium_icon_box_inner_container_hover',
            [
                'label'         => __('Border Radius', 'premium-addons-pro'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', 'em' , '%' ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-icon-box-container-out:hover .premium-icon-box-container-in' => 'border-radius: {{SIZE}}{{UNIT}};'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'label'         => __('Box Shadow','premium-addons-pro'),
                'name'          => 'premium_icon_box_inner_container_box_shadow_hover',
                'selector'      => '{{WRAPPER}} .premium-icon-box-container-out:hover .premium-icon-box-container-in',
            ]
        );
        
        $this->add_responsive_control('premium_icon_box_inner_container_margin_hover',
            [
                'label'         => __('Margin', 'premium-addons-pro'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-icon-box-container-out:hover .premium-icon-box-container-in' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );
        
        $this->add_responsive_control('premium_icon_box_inner_container_padding_hover',
            [
                'label'         => __('Padding', 'premium-addons-pro'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-icon-box-container-out:hover .premium-icon-box-container-in' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        $this->end_controls_section();
    
        $this->start_controls_section('premium_icon_box_outer_container_style',
            [
                'label'         => __('Outer Container', 'premium-addons-pro'),
                'tab'           => Controls_Manager::TAB_STYLE
            ]
        );
        
        $this->add_control('premium_icon_box_back_icon', 
            [
                'label'         => __('Back Icon Color','premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-icon-box-big i'  => 'color: {{VALUE}};'
                ],
                'condition'     => [
                    'premium_icon_box_back_icon_switcher'   => 'yes',
                    'premium_icon_box_selector'             => 'font-awesome-icon'
                ]
            ]
        );
        
        $this->start_controls_tabs('premium_icon_box_outer_container_style_tabs');
        
        $this->start_controls_tab('premium_icon_box_outer_container_style_normal',
            [
                'label'         => __('Normal', 'premium-addons-pro'),
            ]
        );
        
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'          => 'premium_icon_box_outer_container_background',
                'types'         => [ 'classic' , 'gradient' ],
                'selector'      => '{{WRAPPER}} .premium-icon-box-container-out',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(), 
            [
                'name'          => 'premium_icon_box_outer_container_box_border',
                'selector'      => '{{WRAPPER}} .premium-icon-box-container-out',
            ]
        );
        
        $this->add_control('premium_icon_box_outer_container_border_radius',
            [
                'label'         => __('Border Radius', 'premium-addons-pro'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%' ,'em'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-icon-box-container-out' => 'border-radius: {{SIZE}}{{UNIT}};'
                ]
            ]
        );
    
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'label'         => __('Box Shadow','premium-addons-pro'),
                'name'          => 'premium_icon_box_outer_container_box_shadow',
                'selector'      => '{{WRAPPER}} .premium-icon-box-container-out',
            ]
        );
        
        $this->add_responsive_control('premium_icon_box_outer_container_margin',
            [
                'label'         => __('Margin', 'premium-addons-pro'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-icon-box-container-out' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );
        
        $this->add_responsive_control('premium_icon_box_outer_container_padding',
            [
                'label'         => __('Padding', 'premium-addons-pro'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-icon-box-container-out' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );
        
        $this->end_controls_tab();

        $this->start_controls_tab('premium_icon_box_outer_container_style_hover',
            [
                'label'         => __('Hover', 'premium-addons-pro'),
            ]
        );
        
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'          => 'premium_icon_box_outer_container_background_hover',
                'types'         => [ 'classic' , 'gradient' ],
                'selector'      => '{{WRAPPER}} .premium-icon-box-container-out:hover',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(), 
            [
                'name'          => 'premium_icon_box_outer_container_border_hover',
                'selector'      => '{{WRAPPER}} .premium-icon-box-container-out:hover',
            ]
        );
        
        $this->add_control('premium_icon_box_outer_container_hover',
            [
                'label'         => __('Border Radius', 'premium-addons-pro'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', 'em' , '%' ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-icon-box-container-out:hover' => 'border-radius: {{SIZE}}{{UNIT}};'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'label'         => __('Box Shadow','premium-addons-pro'),
                'name'          => 'premium_icon_box_outer_container_box_shadow_hover',
                'selector'      => '{{WRAPPER}} .premium-icon-box-container-out:hover',
            ]
        );
        
        $this->add_responsive_control('premium_icon_box_outer_container_margin_hover',
            [
                'label'         => __('Margin', 'premium-addons-pro'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-icon-box-container-out:hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );
        
        $this->add_responsive_control('premium_icon_box_outer_container_padding_hover',
            [
                'label'         => __('Padding', 'premium-addons-pro'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-icon-box-container-out:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
       
       $this->end_controls_section();
    }
    
    /**
	 * Render Icon Box widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
    protected function render() {
        
        $settings   = $this->get_settings_for_display();

        $title      = $settings['premium_icon_box_title'];
        
        $heading    = $settings['premium_icon_box_title_heading'];
        
        $this->add_render_attribute('premium_icon_box_title','class','premium-icon-box-title');
        $this->add_inline_editing_attributes('premium_icon_box_title', 'basic');

        $this->add_render_attribute('premium_icon_box_content','class','premium-icon-box-content-wrap');
        $this->add_inline_editing_attributes('premium_icon_box_content', 'advanced');
        
        $this->add_render_attribute('premium_icon_box_label','class','premium-icon-box-label');
        $this->add_inline_editing_attributes('premium_icon_box_label', 'basic');

        
        $icon_position  = $settings['premium_icon_box_icon_position'];

        $icon_hover     = ( $settings['premium_icon_box_back_icon_hover'] === 'yes') ? 'premium-icon-box-big-hover' : '';

        $flex_pos        = 'premium-icon-box-flex-'.$settings['premium_icon_box_icon_flex_pos'];

        $flex_ver_pos    = 'premium-icon-box-flex-ver-'.$settings['premium_icon_box_icon_flex_ver_pos'];

        $icon_box_url = 'url' == $settings['premium_icon_box_link_selection'] ? $settings['premium_icon_box_link']['url'] : get_permalink( $settings['premium_icon_box_existing_link'] );
        
        $icon_type = $settings['premium_icon_box_selector'];

        $text_ver_pos    = $settings['premium_icon_box_icon_text_pos'] !== "bottom" ? 'premium-icon-box-flex-ver-'.$settings['premium_icon_box_icon_text_ver_pos'] : "";

        
        if( 'font-awesome-icon' === $icon_type ) {
            if ( ! empty ( $settings['premium_icon_box_font'] ) ) {
                $this->add_render_attribute( 'icon', 'class', array(
                    $settings['premium_icon_box_font'],
                    'premium-icon-box-icon'
                ));
                $this->add_render_attribute( 'icon', 'aria-hidden', 'true' );
            }
        
            $migrated = isset( $settings['__fa4_migrated']['premium_icon_box_font_updated'] );
            $is_new = empty( $settings['premium_icon_box_font'] ) && Icons_Manager::is_migration_allowed();
            
        } elseif( 'custom-image' === $icon_type ) {

            if( ! empty( $settings['premium_icon_box_custom_image']['url'] ) ) {

                $image_custom = $settings['premium_icon_box_custom_image'];

                $image_url_main = Group_Control_Image_Size::get_attachment_image_src( $image_custom['id'], 'premium_icon_box_image_size', $settings );

                $image_url_main = empty( $image_url_main ) ? $image_custom['url'] : $image_url_main;

                $alt = esc_attr( Control_Media::get_image_alt( $image_custom ) );

                $this->add_render_attribute( 'icon_box_img', [
                    'class' => [
                        'premium-icon-box-custom-image',
                        $settings['premium_icon_box_hover']
                    ],
                    'alt' => $alt,
                    'src' => $image_url_main
                ]);

            }
        } else {
            $this->add_render_attribute( 'icon_box_lottie', [
                'class' => [
                    'premium-icon-box-animation',
                    'premium-lottie-animation',
                    $settings['premium_icon_box_hover']
                ],
                'data-lottie-url' => $settings['lottie_url'],
                'data-lottie-loop' => $settings['lottie_loop'],
                'data-lottie-reverse' => $settings['lottie_reverse']
            ]);
        }
        
        if( 'yes' == $settings['premium_icon_box_link_switcher'] ) {
            if ( ! empty ( $settings['premium_icon_box_more_icon'] ) ) {
                $this->add_render_attribute( 'more_icon', 'class', array(
                    'premium-icon-box-more-icon',
                    $settings['premium_icon_box_more_icon']
                ));
                
                $this->add_render_attribute( 'more_icon', 'aria-hidden', 'true' );
            }
        
            $read_migrated = isset( $settings['__fa4_migrated']['premium_icon_box_more_icon_updated'] );
            $read_new = empty( $settings['premium_icon_box_more_icon'] ) && Icons_Manager::is_migration_allowed();
        }
        
        $this->add_render_attribute( 'box', 'class', 'premium-icon-box-container-out' );
        
        if( 'true' === $settings['mouse_tilt'] ) {
            $this->add_render_attribute( 'box', 'data-box-tilt', 'true' );
            if( 'true' === $settings['mouse_tilt_rev'] ) {
                $this->add_render_attribute( 'box', 'data-box-tilt-reverse', 'true' );
            }
        }

        $this->add_render_attribute( 'outer_wrap', 'class' , [
            'premium-icon-box-content-outer-wrap',
            'premium-icon-box-cta-' . $settings['premium_icon_box_icon_text_pos'],
            ]
        );
        $this->add_render_attribute( 'title_wrap', 'class' , [
            'premium-icon-box-title-container',
            'premium-icon-box-title-' . $settings['premium_icon_box_align'],
            ]
        );
        
    ?>

    <div <?php echo $this->get_render_attribute_string('box'); ?>>
        <div class="premium-icon-box-container-in <?php echo esc_attr( $flex_pos ) . ' ' . esc_attr( $flex_ver_pos ); ?>" >
            <div class="premium-icon-box-icon-container">
                <?php if( 'font-awesome-icon' === $icon_type ) :
                    if ( $is_new || $migrated ) :
                        Icons_Manager::render_icon( $settings['premium_icon_box_font_updated'], [ 'class' =>  array(
                            'premium-icon-box-icon',
                            $settings['premium_icon_box_hover']
                        ), 'aria-hidden'   => 'true' ]);
                    else: ?>
                        <i <?php echo $this->get_render_attribute_string( 'icon' ); ?>></i>
                    <?php endif; ?>
                <?php elseif( 'custom-image' === $icon_type ): ?>
                    <img <?php echo $this->get_render_attribute_string('icon_box_img'); ?>>
                <?php else: ?>
                    <div <?php echo $this->get_render_attribute_string( 'icon_box_lottie' ); ?>></div>
                <?php endif;?>
            </div>
            <div <?php echo $this->get_render_attribute_string( 'outer_wrap' ); ?>>
            <?php if ('yes' == $settings['premium_icon_box_title_switcher'] || 'yes' === $settings['premium_icon_box_desc_switcher'] ): ?>
                <div class="premium-icon-box-text-wrap">
                <?php if( 'yes' == $settings['premium_icon_box_title_switcher'] && ! empty( $title ) ) : ?>
                    <div <?php echo $this->get_render_attribute_string('title_wrap'); ?>>
                        <<?php echo esc_attr($heading) . ' ' . $this->get_render_attribute_string('premium_icon_box_title'); ?>><?php echo $settings['premium_icon_box_title']; ?></<?php echo esc_attr( $heading ); ?>>
                        <span <?php echo $this->get_render_attribute_string('premium_icon_box_label'); ?> ><?php echo esc_attr( $settings['premium_icon_box_label'] )?></span>
                    </div>
                <?php endif;?>

                <?php if( 'yes' == $settings['premium_icon_box_desc_switcher'] && !empty($settings['premium_icon_box_content'] ) ) : ?>
                    <div class="premium-icon-box-content">
                        <div <?php echo $this->get_render_attribute_string('premium_icon_box_content'); ?>>
                            <?php echo $settings['premium_icon_box_content']; ?>
                        </div>
                    </div>
                <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if( 'yes' === $settings['premium_icon_box_link_switcher'] && ( 'yes' !== $settings['box_link'] || 'yes' === $settings['keep_text_link'] ) ) : ?>
                <?php if( $settings['premium_icon_box_link_text_switcher'] === 'yes' || $settings['premium_icon_box_link_icon_switcher'] === 'yes' ) : ?>
                    <div class="premium-icon-box-more <?php echo  esc_attr( $text_ver_pos ) ; ?>">
                        <a class="premium-icon-box-link" <?php if( ! empty( $icon_box_url ) ) : ?> href="<?php echo $icon_box_url; ?>"<?php endif;?><?php if( ! empty( $settings['premium_icon_box_link']['is_external'] ) ) : ?> target="_blank" <?php endif; ?><?php if( !empty($settings['premium_icon_box_link']['nofollow'] ) ) : ?> rel="nofollow" <?php endif; ?>>
                            <?php if( $icon_position === 'before' && $settings['premium_icon_box_link_icon_switcher'] === 'yes' ) :
                                if ( $read_new || $read_migrated ) :
                                    Icons_Manager::render_icon( $settings['premium_icon_box_more_icon_updated'], ['class' => 'premium-icon-box-more-icon', 'aria-hidden' => 'true' ] );
                                    else: ?>
                                    <i <?php echo $this->get_render_attribute_string( 'more_icon' ); ?>></i>
                                <?php endif;
                            endif;
                            if( $settings['premium_icon_box_link_text_switcher'] === 'yes' ) : echo esc_html( $settings['premium_icon_box_more_text'] ); endif;
                            if( $icon_position === 'after' && $settings['premium_icon_box_link_icon_switcher'] === 'yes' ) :
                                if ( $read_new || $read_migrated ) :
                                    Icons_Manager::render_icon( $settings['premium_icon_box_more_icon_updated'], ['class' => 'premium-icon-box-more-icon', 'aria-hidden' => 'true' ] );
                                    else: ?>
                                    <i <?php echo $this->get_render_attribute_string( 'more_icon' ); ?>></i>
                                <?php endif;
                            endif ?>
                        </a>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
            </div>    
        </div>

        <?php if( $settings['premium_icon_box_back_icon_switcher'] === 'yes' ) : ?>
            <div class="premium-icon-box-big <?php echo $icon_hover; ?>">
                <?php if( 'font-awesome-icon' === $icon_type ) :
                    if ( $is_new || $migrated ) :
                        Icons_Manager::render_icon( $settings['premium_icon_box_font_updated'], [ 'aria-hidden'   => 'true' ]);
                    else: ?>
                        <i class="premium-icon-box-icon-big <?php echo $settings['premium_icon_box_font']; ?>"></i>
                    <?php endif; ?>
                <?php elseif( 'custom-image' === $icon_type ): ?>
                    <img class="premium-icon-box-icon-big" src="<?php echo  esc_attr( $image_url_main ); ?>">
                <?php else: ?>
                    <div <?php echo $this->get_render_attribute_string( 'icon_box_lottie' ); ?>></div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        <?php if( 'yes' === $settings['box_link'] ) { ?>
            <a class="premium-icon-box-whole-link" href="<?php echo $icon_box_url; ?>" <?php if( ! empty( $settings['premium_icon_box_link']['is_external'] ) ) : ?> target="_blank" <?php endif; ?><?php if( ! empty( $settings['premium_icon_box_link']['nofollow'] ) ) : ?> rel="nofollow" <?php endif; ?>></a>
        <?php } ?>
    </div>
    <?php
    }
    
    /**
	 * Render Icon Box output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
    protected function content_template() {
        ?>
        <#
        
            var title = settings.premium_icon_box_title,
                titleTag = settings.premium_icon_box_title_heading,
                iconFont = settings.premium_icon_box_font,
                iconPosition = settings.premium_icon_box_icon_position,                
                iconHover = 'yes' === settings.premium_icon_box_back_icon_hover ? 'premium-icon-box-big-hover' : '',
                flexPosition = 'premium-icon-box-flex-'  + settings.premium_icon_box_icon_flex_pos,
                flexVerPosition = 'premium-icon-box-flex-ver-' + settings.premium_icon_box_icon_flex_ver_pos,
                boxUrl = 'url' == settings.premium_icon_box_link_selection ? settings.premium_icon_box_link.url : settings.premium_icon_box_existing_link,
                iconType = settings.premium_icon_box_selector;
                textVerPosition = settings.premium_icon_box_icon_text_pos !== "bottom" ? 'premium-icon-box-flex-ver-' + settings.premium_icon_box_icon_text_ver_pos : "";

                

                if ( 'font-awesome-icon' === iconType ) {
                
                    var iconHTML = elementor.helpers.renderIcon( view, settings.premium_icon_box_font_updated, {
                        'class': [ 'premium-icon-box-icon', settings.premium_icon_box_hover ],
                        'aria-hidden': true
                        }, 'i' , 'object' ),
                        migrated = elementor.helpers.isIconMigrated( settings, 'premium_icon_box_font_updated' );
                        
                    var backIconHtml = elementor.helpers.renderIcon( view, settings.premium_icon_box_font_updated, {
                        'class': 'premium-icon-box-icon', 'aria-hidden': true }, 'i' , 'object' );
                
                } else if( 'custom-image' === iconType ) {
                
                    if( settings.premium_icon_box_custom_image.url ){
                    
                        var image = {
                            id: settings.premium_icon_box_custom_image.id,
                            url: settings.premium_icon_box_custom_image.url,
                            size: settings.premium_icon_box_image_size_size,
                            dimension: settings.premium_icon_box_image_size_custom_dimension,
                            model: view.getEditModel()
                        };
                        
                        var image_url = elementor.imagesManager.getImageUrl( image );

                        view.addRenderAttribute( 'icon_box_img', {
                            'class': [
                                'premium-icon-box-custom-image',
                                settings.premium_icon_box_hover
                            ],
                            'src': image_url,
                        });
                    
                    }
                } else {
                    
                    view.addRenderAttribute( 'icon_box_lottie', {
                        'class': [
                            'premium-icon-box-animation',
                            'premium-lottie-animation',
                            settings.premium_icon_box_hover
                        ],
                        'data-lottie-url': settings.lottie_url,
                        'data-lottie-loop': settings.lottie_loop,
                        'data-lottie-reverse': settings.lottie_reverse
                    });
                }
                
                view.addRenderAttribute('premium_icon_box_title', 'class', 'premium-icon-box-title' );
                
                view.addInlineEditingAttributes('premium_icon_box_title', 'basic');

                view.addRenderAttribute('premium_icon_box_label', 'class', 'premium-icon-box-label' );

                view.addInlineEditingAttributes('premium_icon_box_label', 'basic');

                
                view.addRenderAttribute('premium_icon_box_content', 'class', 'premium-icon-box-content-wrap' );
                
                view.addInlineEditingAttributes('premium_icon_box_content', 'advanced');
                
                if( 'yes' === settings.premium_icon_box_link_switcher ) {
                
                    var moreIconHTML = elementor.helpers.renderIcon( view, settings.premium_icon_box_more_icon_updated, { 'class': 'premium-icon-box-more-icon', 'aria-hidden': true
                        }, 'i' , 'object' ),
                        moreMigrated = elementor.helpers.isIconMigrated( settings, 'premium_icon_box_more_icon_updated' );
                
                }
                
                view.addRenderAttribute( 'box', 'class', 'premium-icon-box-container-out' );
                
                if( 'true' === settings.mouse_tilt ) {
                    view.addRenderAttribute( 'box', 'data-box-tilt', 'true' );
                    if( 'true' === settings.mouse_tilt_rev ) {
                        view.addRenderAttribute( 'box', 'data-box-tilt-reverse', 'true' );
                    }
                }

                view.addRenderAttribute( 'outer_wrap','class', [
                    'premium-icon-box-content-outer-wrap',
                    'premium-icon-box-cta-'+settings.premium_icon_box_icon_text_pos
                    ]
                );

                view.addRenderAttribute( 'title_wrap','class', [
                    'premium-icon-box-title-container',
                    'premium-icon-box-title-'+settings.premium_icon_box_align
                    ]
                );
          
        #>
        
        <div {{{ view.getRenderAttributeString('box') }}}>
            <div class="premium-icon-box-container-in {{ flexPosition }} {{ flexVerPosition }} ">
                <div class="premium-icon-box-icon-container">
                    <# if( 'font-awesome-icon' == iconType ) {
                        if ( iconHTML && iconHTML.rendered && ( ! settings.premium_icon_box_font || migrated ) ) { #>
                            {{{ iconHTML.value }}}
                        <# } else { #>
                            <i class="premium-icon-box-icon {{ settings.premium_icon_box_font }} {{ settings.premium_icon_box_hover }}"></i>
                        <# } #>
                    <# } else if( 'custom-image' === iconType ) { #>
                        <img {{{ view.getRenderAttributeString('icon_box_img') }}}>
                    <# } else { #>
                        <div {{{ view.getRenderAttributeString('icon_box_lottie') }}}></div>
                    <# } #>
                </div>
                <div {{{ view.getRenderAttributeString('outer_wrap') }}}>
                    <# if( 'yes' == settings.premium_icon_box_title_switcher || 'yes' == settings.premium_icon_box_desc_switcher ) { #>
                        <div class="premium-icon-box-text-wrap">
                        <# if( 'yes' == settings.premium_icon_box_title_switcher && '' != title ) { #>
                            <div {{{ view.getRenderAttributeString('title_wrap') }}}>
                                <{{{titleTag}}} {{{ view.getRenderAttributeString('premium_icon_box_title') }}}>{{{ settings.premium_icon_box_title }}}</{{{titleTag}}}>
                                <span {{{ view.getRenderAttributeString('premium_icon_box_label') }}} >{{{ settings.premium_icon_box_label }}}</span>
                            </div>
                        <# } #>

                        <# if( 'yes' == settings.premium_icon_box_desc_switcher && '' != settings.premium_icon_box_content ) { #>
                            <div class= "premium-icon-box-content">
                                <div {{{ view.getRenderAttributeString('premium_icon_box_content') }}}>
                                    {{{ settings.premium_icon_box_content }}}
                                </div>
                            </div>
                        <# } #>
                        </div>
                    <# } #>

                    <# if( 'yes' == settings.premium_icon_box_link_switcher && ('yes' !== settings.box_link || 'yes' === settings.keep_text_link ) ) { #>
                        <# if( 'yes' == settings.premium_icon_box_link_text_switcher || 'yes' == settings.premium_icon_box_link_icon_switcher ) {
                            if( '' != settings.premium_icon_box_more_text || '' != settings.premium_icon_box_more_icon ) { #>
                                <div class="premium-icon-box-more {{ textVerPosition }}">
                                    <a class="premium-icon-box-link" href="{{ boxUrl }}">
                                        <# if( 'before' == iconPosition  && 'yes' == settings.premium_icon_box_link_icon_switcher ) {
                                            if ( moreIconHTML && moreIconHTML.rendered && ( ! settings.premium_icon_box_more_icon || moreMigrated ) ) { #>
                                                {{{ moreIconHTML.value }}}
                                            <# } else { #>
                                                <i class="premium-icon-box-more-icon {{ settings.premium_icon_box_more_icon }}" aria-hidden="true"></i>
                                            <# }
                                        }
                                        if( 'yes' == settings.premium_icon_box_link_text_switcher ) { #> 
                                            {{{ settings.premium_icon_box_more_text }}} 
                                        <# }
                                        if( 'after' == iconPosition  && 'yes' == settings.premium_icon_box_link_icon_switcher ) {
                                            if ( moreIconHTML && moreIconHTML.rendered && ( ! settings.premium_icon_box_more_icon || moreMigrated ) ) { #>
                                                {{{ moreIconHTML.value }}}
                                            <# } else { #>
                                                <i class="premium-icon-box-more-icon {{ settings.premium_icon_box_more_icon }}" aria-hidden="true"></i>
                                            <# }
                                        } #>
                                    </a>
                                </div>
                            <# } #>
                        <# } #>
                    <# } #>
                </div>
            </div>
            <# if ( 'yes' == settings.premium_icon_box_back_icon_switcher ) { #>
                <div class="premium-icon-box-big {{ iconHover }}">
                    <# if( 'font-awesome-icon' === iconType ) {
                        if ( backIconHtml && backIconHtml.rendered && ( ! settings.premium_icon_box_font || migrated ) ) { #>
                            {{{ backIconHtml.value }}}
                        <# } else { #>
                            <i class="premium-icon-box-icon-big {{ settings.premium_icon_box_font }}"></i>
                        <# } #>
                    <# } else if ( 'custom-image' === iconType ) { #>
                        <img class="premium-icon-box-icon-big" src="{{ image_url }}">
                    <# } else { #>
                        <div {{{ view.getRenderAttributeString('icon_box_lottie') }}}></div>
                    <# } #>
                </div>
            <# } #>
            <# if( 'yes' === settings.box_link ) { #>
                <a class="premium-icon-box-whole-link" href="{{ boxUrl }}"></a>
            <# } #>
        </div>
        
        <?php
    }
}