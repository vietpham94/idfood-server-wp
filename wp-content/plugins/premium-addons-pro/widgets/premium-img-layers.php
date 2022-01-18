<?php

/**
 * Class: Premium_Img_Layers
 * Name: Image Layers
 * Slug: premium-img-layers-addon
 */
namespace PremiumAddonsPro\Widgets;

// Elementor Classes.
use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Repeater;
use Elementor\Controls_Manager;
use Elementor\Control_Media;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;

// PremiumAddons Classes.
use PremiumAddons\Includes\Helper_Functions;
use PremiumAddons\Includes\Premium_Template_Tags;

if( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class Premium_Img_Layers
 */
class Premium_Img_Layers extends Widget_Base {
    
    private $templateInstance; 
    
    public function getTemplateInstance() {
        return $this->templateInstance = Premium_Template_Tags::getInstance();
    }
    
    public function get_name() {
        return 'premium-img-layers-addon';
    }

    public function get_title() {
        return sprintf( '%1$s %2$s', Helper_Functions::get_prefix(), __('Image Layers', 'premium-addons-pro') );
    }

    public function get_icon() {
        return 'pa-pro-image-layers';
    }

    public function get_categories() {
        return ['premium-elements'];
    }

    public function get_script_depends() {
        return [
            'parallaxmouse-js',
            'tweenmax-js',
            'tilt-js',
            'elementor-waypoints',
            'anime-js',
            'lottie-js',
            'premium-pro-js'
        ];
    }

    public function is_reload_preview_required() {
        return true;
    }
    
    public function get_custom_help_url() {
		return 'https://www.youtube.com/watch?v=D3INxWw_jKI&list=PLLpZVOYpMtTArB4hrlpSnDJB36D2sdoTv';
	}
    
    /**
	 * Register Image Comparison controls.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
    protected function _register_controls() {
        
        $this->start_controls_section('premium_img_layers_content',
            [
                'label'         => __('Layers','premium-addons-pro'),
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control('media_type',
			[
				'label'     => __( 'Media Type', 'premium-addons-pro' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
                    'image'         => __( 'Image', 'premium-addons-pro' ),
                    'animation'     => __( 'Lottie Animation', 'premium-addons-pro' )
                ],
                'default'   => 'image'
			]
		);
        
        $repeater->add_control('premium_img_layers_image',
           [
                'label'         => __( 'Upload Image', 'premium-addons-pro' ),
                'type'          => Controls_Manager::MEDIA,
                'dynamic'       => [ 'active' => true ],
                'default'       => [
                    'url'	=> Utils::get_placeholder_image_src(),
                ],
                'condition'		=> [
                	'media_type'	=> 'image'
            	]
            ]
        );
        
        $repeater->add_control('lottie_url', 
            [
                'label'             => __( 'Animation JSON URL', 'premium-addons-pro' ),
                'type'              => Controls_Manager::TEXT,
                'dynamic'           => [ 'active' => true ],
                'description'       => 'Get JSON code URL from <a href="https://lottiefiles.com/" target="_blank">here</a>',
                'label_block'       => true,
                'condition'		=> [
                	'media_type'	=> 'animation'
            	]
            ]
        );

        $repeater->add_control('lottie_loop',
            [
                'label'         => __('Loop','premium-addons-pro'),
                'type'          => Controls_Manager::SWITCHER,
                'return_value'  => 'true',
                'default'       => 'true',
                'condition'		=> [
                    'media_type'	=> 'animation',
                    'lottie_url!'   => ''
            	]
            ]
        );

        $repeater->add_control('lottie_reverse',
            [
                'label'         => __('Reverse','premium-addons-pro'),
                'type'          => Controls_Manager::SWITCHER,
                'return_value'  => 'true',
                'condition'		=> [
                    'media_type'	=> 'animation',
                    'lottie_url!'   => ''
            	]
            ]
        );

        $repeater->add_control('lottie_hover',
            [
                'label'         => __('Only Play on Hover','premium-addons-pro'),
                'type'          => Controls_Manager::SWITCHER,
                'return_value'  => 'true',
                'condition'		=> [
                    'media_type'	=> 'animation',
                    'lottie_url!'   => ''
            	]
            ]
        );

        $repeater->add_control('lottie_renderer', 
            [
                'label'         => __('Render As', 'premium-addons-pro'),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'svg'   => __('SVG', 'premium-addons-pro'),
                    'canvas'  => __('Canvas', 'premium-addons-pro'),
                ],
                'default'       => 'svg',
                'render_type'   => 'template',
                'label_block'   => true,
                'condition'		=> [
                    'media_type'	=> 'animation',
                    'lottie_url!'   => ''
            	]
            ]
        );

        $repeater->add_control('render_notice', 
            [
                'raw'               => __('Set render type to canvas if you\'re having performance issues on the page.', 'premium-addons-pro'),
                'type'              => Controls_Manager::RAW_HTML,
                'content_classes'   => 'elementor-panel-alert elementor-panel-alert-info',
                'condition'		=> [
                    'media_type'	=> 'animation',
                    'lottie_url!'   => ''
            	]
            ] 
        );
        
        $repeater->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'          => 'thumbnail',
                'default'       => 'full',
                'condition'		=> [
                    'media_type'	=> 'image',
            	]
			]
		);

        $repeater->add_control('premium_img_layers_position',
            [
                'label'         => __('Position', 'premium-addons-pro'),
                'type'          => Controls_Manager::HIDDEN,
                'options'       => [
                    'relative'      => __('Relative','premium-addons-pro'),
                    'absolute'      => __('Absolute','premium-addons-pro'),
                ]
            ]
        );
        
        $repeater->add_responsive_control('premium_img_layers_hor_position',
            [
                'label'         => __('Horizontal Offset','premium-addons-pro'),
                'type'          => Controls_Manager::SLIDER,
                'description'   => __('Mousemove Interactivity works only with pixels', 'premium-addons-pro'),
                'size_units'    => ['px', '%'],
                'range'         => [
                    'px'    => [
                        'min'   => -200, 
                        'max'   => 300,
                    ],
                    '%'    => [
                        'min'   => -50, 
                        'max'   => 100,
                    ],
                ],
                'selectors'     => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.absolute' => 'left: {{SIZE}}{{UNIT}};'
                ],
            ]
        );
        
        $repeater->add_responsive_control('premium_img_layers_ver_position',
            [
                'label'         => __('Vertical Offset','premium-addons-pro'),
                'description'   => __('Mousemove Interactivity works only with pixels', 'premium-addons-pro'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%'],
                'range'         => [
                    'px'    => [
                        'min'   => -200, 
                        'max'   => 300,
                    ],
                    '%'    => [
                        'min'   => -50, 
                        'max'   => 100,
                    ],
                ],
                'selectors'     => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.absolute' => 'top: {{SIZE}}{{UNIT}};'
                ]
            ]
        );
        
        $repeater->add_responsive_control('premium_img_layers_width',
            [
                'label'         => __('Width','premium-addons-pro'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%', "vw"],
                'range' => [
					'px' => [
						'max' => 1000,
						'step' => 1,
					],
					'%' => [
						'max' => 100,
						'step' => 1,
					],
				],
                'selectors'     => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'width: {{SIZE}}{{UNIT}};'
                ],
                'separator'     => 'after',
            ]
        );
        
        $repeater->add_control('blend_mode',
			[
				'label'     => __( 'Blend Mode', 'premium-addons-pro' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					''              => __( 'Normal', 'premium-addons-pro' ),
					'multiply'      => 'Multiply',
					'screen'        => 'Screen',
					'overlay'       => 'Overlay',
					'darken'        => 'Darken',
					'lighten'       => 'Lighten',
					'color-dodge'   => 'Color Dodge',
					'saturation'    => 'Saturation',
					'color'         => 'Color',
					'luminosity'    => 'Luminosity',
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'mix-blend-mode: {{VALUE}}',
				],
			]
		);

        $repeater->add_control('premium_img_layers_link_switcher',
            [
                'label'         => __('Link','premium-addons-pro'),
                'type'          => Controls_Manager::SWITCHER,
            ]
        );
        
        $repeater->add_control('premium_img_layers_link_selection', 
            [
                'label'         => __('Link Type', 'premium-addons-pro'),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'url'   => __('URL', 'premium-addons-pro'),
                    'link'  => __('Existing Page', 'premium-addons-pro'),
                ],
                'default'       => 'url',
                'label_block'   => true,
                'condition'		=> [
                	'premium_img_layers_link_switcher'	=> 'yes'
            	]
        	]
        );
        
        $repeater->add_control('premium_img_layers_link',
            [
                'label'         => __('Link', 'premium-addons-pro'),
                'type'          => Controls_Manager::URL,
                'dynamic'       => [ 'active' => true ],
                'default'       => [
                    'url'   => '#',
                ],
                'placeholder'   => 'https://premiumaddons.com/',
                'label_block'   => true,
                'separator'     => 'after',
                'condition'     => [
                	'premium_img_layers_link_switcher'	=> 'yes',
                    'premium_img_layers_link_selection' => 'url'
                ]
            ]
        );
        
        $repeater->add_control('premium_img_layers_existing_link',
            [
                'label'         => __('Existing Page', 'premium-addons-pro'),
                'type'          => Controls_Manager::SELECT2,
                'options'       => $this->getTemplateInstance()->get_all_posts(),
                'condition'     => [
                	'premium_img_layers_link_switcher'	=> 'yes',
                    'premium_img_layers_link_selection' => 'link',
                ],
                'multiple'      => false,
                'separator'     => 'after',
                'label_block'   => true,
            ]
        );

        $repeater->add_control('premium_img_layers_rotate',
            [
                'label'         => __('Rotate', 'premium-addons-pro'),
                'type'          => Controls_Manager::SWITCHER,
            ]
        );

        $repeater->add_control('premium_img_layers_rotatex',
            [
                'label'         => __('Degrees', 'premium-addons-pro'),
                'type'          => Controls_Manager::NUMBER,
                'description'   => __('Set rotation value in degrees', 'premium-addons-pro'),
                'min'           => -180,
                'max'           => 180,
                'condition'     => [
                    'premium_img_layers_rotate'   => 'yes'
                ],
                'separator'     => 'after',
                'selectors'     => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => '-webkit-transform: rotate({{VALUE}}deg); -moz-transform: rotate({{VALUE}}deg); -o-transform: rotate({{VALUE}}deg); transform: rotate({{VALUE}}deg);'
                ],
            ]
        );
        
        $repeater->add_control('premium_img_layers_animation_switcher',
            [
                'label'        => __('Animation','premium-addons-pro'),
                'type'         => Controls_Manager::SWITCHER,
            ]
        );
        
        $repeater->add_control('premium_img_layers_animation',
			[
				'label'         => __( 'Entrance Animation', 'premium-addons-pro' ),
				'type'          => Controls_Manager::ANIMATION,
				'default'       => '',
				'label_block'   => true,
                'frontend_available' => true,
                'condition'     => [
                    'premium_img_layers_animation_switcher' => 'yes'
                ],
			]
		);
        
        $repeater->add_control('premium_img_layers_animation_duration',
			[
				'label'         => __( 'Animation Duration', 'premium-addons-pro' ),
				'type'          => Controls_Manager::SELECT,
				'default'       => '',
				'options'       => [
					'slow' => __( 'Slow', 'premium-addons-pro' ),
					''     => __( 'Normal', 'premium-addons-pro' ),
					'fast' => __( 'Fast', 'premium-addons-pro' ),
				],
                'condition'     => [
                    'premium_img_layers_animation_switcher' => 'yes',
					'premium_img_layers_animation!'    => '',
				],
			]
		);
        
        $repeater->add_control('premium_img_layers_animation_delay',
			[
				'label'         => __( 'Animation Delay', 'premium-addons-pro' ) . ' (s)',
				'type'          => Controls_Manager::NUMBER,
				'default'       => '',
				'min'           => 0,
				'step'          => 0.1,
				'condition'     => [
                    'premium_img_layers_animation_switcher' => 'yes',
					'premium_img_layers_animation!'    => '',
				],
				'frontend_available' => true,
                'selectors'     => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.animated' => '-webkit-animation-delay:{{VALUE}}s; -moz-animation-delay: {{VALUE}}s; -o-animation-delay: {{VALUE}}s; animation-delay: {{VALUE}}s;'
                ]
			]
		);
        
        $repeater->add_control('premium_img_layers_mouse',
            [
                'label'         => __('Mousemove Interactivity', 'premium-addons-pro'),
                'type'          => Controls_Manager::SWITCHER,
                'description'   => __('Enable or disable mousemove interaction','premium-addons-pro'),
            ]
        );
        
        $repeater->add_control('premium_img_layers_mouse_type', 
            [
                'label'         => __('Interactivity Style', 'premium-addons-pro'),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'parallax'      => __('Mouse Parallax', 'premium-addons-pro'),
                    'tilt'          => __('Tilt', 'premium-addons-pro'),
                ],
                'default'       => 'parallax',
                'label_block'   => true,
                'condition'		=> [
                    'premium_img_layers_mouse'	=> 'yes'
                ]
            ]
        );
        
        $repeater->add_control('premium_img_layers_mouse_reverse',
            [
                'label'         => __('Reverse Direction', 'premium-addons-pro'),
                'type'          => Controls_Manager::SWITCHER,
                'condition'     => [
                    'premium_img_layers_mouse' => 'yes',
                    'premium_img_layers_mouse_type' => 'parallax'
                ]
            ]
        );

        $repeater->add_control('premium_img_layers_mouse_initial',
            [
                'label'         => __('Back To Initial Position', 'premium-addons-pro'),
                'type'          => Controls_Manager::SWITCHER,
                'description'   => __('Enable this to get back to initial position when mouse leaves the widget.', 'premium-addons-pro'),
                'condition'     => [
                    'premium_img_layers_mouse' => 'yes',
                    'premium_img_layers_mouse_type' => 'parallax'
                ]
            ]
        );
        
        $repeater->add_control('premium_img_layers_rate',
            [
                'label'         => __('Rate', 'premium-addons-pro'),
                'type'          => Controls_Manager::NUMBER,
                'default'       => -10,
                'min'           => -20,
                'max'           => 20,
                'step'          => 1,
                'description'   => __('Choose the movement rate for the layer image, default: -10','premium-addons-pro'),
                'separator'     => 'after',
                'condition'     => [
                    'premium_img_layers_mouse' => 'yes',
                    'premium_img_layers_mouse_type' => 'parallax'
                ]
            ]
        );
        
        $repeater->add_control('premium_img_layers_scroll_effects',
            [
                'label'         => __('Scroll Effects', 'premium-addons-pro'),
                'type'          => Controls_Manager::SWITCHER,
                'condition'     => [
                    'premium_img_layers_float_effects!'    => 'yes'
                ]
            ]
        );
        
        $conditions = array(
            'premium_img_layers_scroll_effects' => 'yes'
        );
    
        $repeater->add_control('premium_img_layers_opacity',
            [
                'label'         => __('Scroll Fade', 'premium-addons-pro'),
                'type'          => Controls_Manager::SWITCHER,
                'condition'     => $conditions
            ]
        );
        
        $repeater->add_control('premium_img_layers_opacity_effect',
			[
				'label'         => __( 'Direction', 'premium-addons-pro' ),
				'type'          => Controls_Manager::SELECT,
				'options'       => [
					'down'  => __( 'Fade In', 'premium-addons-pro' ),
					'up'    => __( 'Fade Out', 'premium-addons-pro' ),
				],
                'default'       => 'down',
				'condition'     => array_merge( $conditions, [
					'premium_img_layers_opacity'     => 'yes'
				] ) ,
			]
		);
        
        $repeater->add_control('premium_img_layers_opacity_level',
			[
				'label'         => __( 'Opacity Level', 'premium-addons-pro' ),
				'type'          => Controls_Manager::SLIDER,
				'default' => [
                    'size' => 10,
                ],
                'range' => [
                    'px' => [
                        'max' => 10,
                        'step' => 0.1,
                    ],
                ],
				'condition'     => array_merge( $conditions, [
					'premium_img_layers_opacity'     => 'yes'
				] ) ,
			]
		);
        
        $repeater->add_control('premium_img_layers_opacity_view',
			[
				'label'         => __( 'Viewport', 'premium-addons-pro' ),
				'type'          => Controls_Manager::SLIDER,
				'default' => [
                    'sizes' => [
                        'start' => 0,
                        'end' => 100,
                    ],
                    'unit' => '%',
                ],
                'labels' => [
                    __( 'Bottom', 'premium-addons-pro' ),
                    __( 'Top', 'premium-addons-pro' ),
                ],
                'scales' => 1,
                'handles' => 'range',
                'condition'     => array_merge( $conditions, [
					'premium_img_layers_opacity'     => 'yes'
				] ) ,
			]
		);
        
        $repeater->add_control('premium_img_layers_vscroll',
            [
                'label'         => __('Vertical Parallax', 'premium-addons-pro'),
                'type'          => Controls_Manager::SWITCHER,
                'condition'     => $conditions
            ]
        );
        
        $repeater->add_control('premium_img_layers_vscroll_direction',
			[
				'label'         => __( 'Direction', 'premium-addons-pro' ),
				'type'          => Controls_Manager::SELECT,
				'options'       => [
					'up'    => __( 'Up', 'premium-addons-pro' ),
					'down'  => __( 'Down', 'premium-addons-pro' ),
				],
                'default'       => 'down',
				'condition'     => array_merge( $conditions, [
					'premium_img_layers_vscroll'     => 'yes'
				] ) ,
			]
		);
        
        $repeater->add_control('premium_img_layers_vscroll_speed',
			[
				'label'         => __( 'Speed', 'premium-addons-pro' ),
				'type'          => Controls_Manager::SLIDER,
				'default' => [
                    'size' => 4,
                ],
                'range' => [
                    'px' => [
                        'max' => 10,
                        'step' => 0.1,
                    ],
                ],
				'condition'     => array_merge( $conditions, [
					'premium_img_layers_vscroll'     => 'yes'
				] ) ,
			]
		);
        
        $repeater->add_control('premium_img_layers_vscroll_view',
			[
				'label'         => __( 'Viewport', 'premium-addons-pro' ),
				'type'          => Controls_Manager::SLIDER,
				'default' => [
                    'sizes' => [
                        'start' => 0,
                        'end' => 100,
                    ],
                    'unit' => '%',
                ],
                'labels' => [
                    __( 'Bottom', 'premium-addons-pro' ),
                    __( 'Top', 'premium-addons-pro' ),
                ],
                'scales' => 1,
                'handles' => 'range',
                'condition'     => array_merge( $conditions, [
					'premium_img_layers_vscroll'     => 'yes'
				] ) ,
			]
		);
        
        $repeater->add_control('premium_img_layers_hscroll',
            [
                'label'         => __('Horizontal Parallax', 'premium-addons-pro'),
                'type'          => Controls_Manager::SWITCHER,
                'condition'     => $conditions
            ]
        );
        
        $repeater->add_control('premium_img_layers_hscroll_direction',
			[
				'label'         => __( 'Direction', 'premium-addons-pro' ),
				'type'          => Controls_Manager::SELECT,
				'options'       => [
					'up'    => __( 'To Left', 'premium-addons-pro' ),
					'down'  => __( 'To Right', 'premium-addons-pro' ),
				],
                'default'       => 'down',
				'condition'     => array_merge( $conditions, [
					'premium_img_layers_hscroll'     => 'yes'
				] ) ,
			]
		);
        
        $repeater->add_control('premium_img_layers_hscroll_speed',
			[
				'label'         => __( 'Speed', 'premium-addons-pro' ),
				'type'          => Controls_Manager::SLIDER,
				'default' => [
                    'size' => 4,
                ],
                'range' => [
                    'px' => [
                        'max' => 10,
                        'step' => 0.1,
                    ],
                ],
				'condition'     => array_merge( $conditions, [
					'premium_img_layers_hscroll'     => 'yes'
				] ) ,
			]
		);
        
        $repeater->add_control('premium_img_layers_hscroll_view',
			[
				'label'         => __( 'Viewport', 'premium-addons-pro' ),
				'type'          => Controls_Manager::SLIDER,
				'default' => [
                    'sizes' => [
                        'start' => 0,
                        'end' => 100,
                    ],
                    'unit' => '%',
                ],
                'labels' => [
                    __( 'Bottom', 'premium-addons-pro' ),
                    __( 'Top', 'premium-addons-pro' ),
                ],
                'scales' => 1,
                'handles' => 'range',
                'condition'     => array_merge( $conditions, [
					'premium_img_layers_hscroll'     => 'yes'
				] ) ,
			]
		);
        
        $repeater->add_control('premium_img_layers_blur',
            [
                'label'         => __('Blur', 'premium-addons-pro'),
                'type'          => Controls_Manager::SWITCHER,
                'condition'     => $conditions
            ]
        );
        
        $repeater->add_control('premium_img_layers_blur_effect',
			[
				'label'         => __( 'Direction', 'premium-addons-pro' ),
				'type'          => Controls_Manager::SELECT,
				'options'       => [
					'down'  => __( 'Decrease Blur', 'premium-addons-pro' ),
					'up'    => __( 'Increase Blur', 'premium-addons-pro' ),
				],
                'default'       => 'down',
				'condition'     => array_merge( $conditions, [
					'premium_img_layers_blur'     => 'yes'
				] ) ,
			]
		);
        
        $repeater->add_control('premium_img_layers_blur_level',
			[
				'label'         => __( 'Blur Level', 'premium-addons-pro' ),
				'type'          => Controls_Manager::SLIDER,
				'default' => [
                    'size' => 10,
                ],
                'range' => [
                    'px' => [
                        'max' => 10,
                        'step' => 0.1,
                    ],
                ],
				'condition'     => array_merge( $conditions, [
					'premium_img_layers_blur'     => 'yes'
				] ) ,
			]
		);
        
        $repeater->add_control('premium_img_layers_blur_view',
			[
				'label'         => __( 'Viewport', 'premium-addons-pro' ),
				'type'          => Controls_Manager::SLIDER,
				'default' => [
                    'sizes' => [
                        'start' => 0,
                        'end' => 100,
                    ],
                    'unit' => '%',
                ],
                'labels' => [
                    __( 'Bottom', 'premium-addons-pro' ),
                    __( 'Top', 'premium-addons-pro' ),
                ],
                'scales' => 1,
                'handles' => 'range',
                'condition'     => array_merge( $conditions, [
					'premium_img_layers_blur'     => 'yes'
				] ) ,
			]
		);
        
        $repeater->add_control('premium_img_layers_rscroll',
            [
                'label'         => __('Rotate', 'premium-addons-pro'),
                'type'          => Controls_Manager::SWITCHER,
                'condition'     => $conditions
            ]
        );
        
        $repeater->add_control('premium_img_layers_rscroll_direction',
			[
				'label'         => __( 'Direction', 'premium-addons-pro' ),
				'type'          => Controls_Manager::SELECT,
				'options'       => [
					'up'    => __( 'Counter Clockwise', 'premium-addons-pro' ),
					'down'  => __( 'Clockwise', 'premium-addons-pro' ),
				],
                'default'       => 'down',
				'condition'     => array_merge( $conditions, [
					'premium_img_layers_rscroll'     => 'yes'
				] ) ,
			]
		);
        
        $repeater->add_control('premium_img_layers_rscroll_speed',
			[
				'label'         => __( 'Speed', 'premium-addons-pro' ),
				'type'          => Controls_Manager::SLIDER,
				'default' => [
                    'size' => 3,
                ],
                'range' => [
                    'px' => [
                        'max' => 10,
                        'step' => 0.1,
                    ],
                ],
				'condition'     => array_merge( $conditions, [
					'premium_img_layers_rscroll'     => 'yes'
				] ) ,
			]
		);
        
        $repeater->add_control('premium_img_layers_rscroll_view',
			[
				'label'         => __( 'Viewport', 'premium-addons-pro' ),
				'type'          => Controls_Manager::SLIDER,
				'default' => [
                    'sizes' => [
                        'start' => 0,
                        'end' => 100,
                    ],
                    'unit' => '%',
                ],
                'labels' => [
                    __( 'Bottom', 'premium-addons-pro' ),
                    __( 'Top', 'premium-addons-pro' ),
                ],
                'scales' => 1,
                'handles' => 'range',
                'condition'     => array_merge( $conditions, [
					'premium_img_layers_rscroll'     => 'yes'
				] ) ,
			]
		);
        
        $repeater->add_control('premium_img_layers_scale',
            [
                'label'         => __('Scale', 'premium-addons-pro'),
                'type'          => Controls_Manager::SWITCHER,
                'condition'     => $conditions
            ]
        );
        
        $repeater->add_control('premium_img_layers_scale_direction',
			[
				'label'         => __( 'Scale', 'premium-addons-pro' ),
				'type'          => Controls_Manager::SELECT,
				'options'       => [
					'up'    => __( 'Shrink', 'premium-addons-pro' ),
					'down'  => __( 'Scale', 'premium-addons-pro' ),
				],
                'default'       => 'down',
				'condition'     => array_merge( $conditions, [
					'premium_img_layers_scale'     => 'yes'
				] ) ,
			]
		);
        
        $repeater->add_control('premium_img_layers_scale_speed',
			[
				'label'         => __( 'Speed', 'premium-addons-pro' ),
				'type'          => Controls_Manager::SLIDER,
				'default' => [
                    'size' => 3,
                ],
                'range' => [
                    'px' => [
                        'max' => 10,
                        'step' => 0.1,
                    ],
                ],
				'condition'     => array_merge( $conditions, [
					'premium_img_layers_scale'     => 'yes'
				] ) ,
			]
		);
        
        $repeater->add_control('premium_img_layers_scale_view',
			[
				'label'         => __( 'Viewport', 'premium-addons-pro' ),
				'type'          => Controls_Manager::SLIDER,
				'default' => [
                    'sizes' => [
                        'start' => 0,
                        'end' => 100,
                    ],
                    'unit' => '%',
                ],
                'labels' => [
                    __( 'Bottom', 'premium-addons-pro' ),
                    __( 'Top', 'premium-addons-pro' ),
                ],
                'scales' => 1,
                'handles' => 'range',
                'condition'     => array_merge( $conditions, [
					'premium_img_layers_scale'     => 'yes'
				] ) ,
			]
		);
        
        $repeater->add_control('premium_img_layers_gray',
            [
                'label'         => __('Gray Scale', 'premium-addons-pro'),
                'type'          => Controls_Manager::SWITCHER,
                'condition'     => $conditions
            ]
        );
        
        $repeater->add_control('premium_img_layers_gray_effect',
			[
				'label'         => __( 'Effect', 'premium-addons-pro' ),
				'type'          => Controls_Manager::SELECT,
				'options'       => [
					'up'    => __( 'Increase', 'premium-addons-pro' ),
					'down'  => __( 'Decrease', 'premium-addons-pro' ),
				],
                'default'       => 'down',
				'condition'     => array_merge( $conditions, [
					'premium_img_layers_gray'     => 'yes'
				] ) ,
			]
		);
        
        $repeater->add_control('premium_img_layers_gray_level',
			[
				'label'         => __( 'Speed', 'premium-addons-pro' ),
				'type'          => Controls_Manager::SLIDER,
				'default' => [
                    'size' => 10,
                ],
                'range' => [
                    'px' => [
                        'max' => 10,
                        'step' => 0.1,
                    ],
                ],
				'condition'     => array_merge( $conditions, [
					'premium_img_layers_gray'     => 'yes'
				] ) ,
			]
		);
        
        $repeater->add_control('premium_img_layers_gray_view',
			[
				'label'         => __( 'Viewport', 'premium-addons-pro' ),
				'type'          => Controls_Manager::SLIDER,
				'default' => [
                    'sizes' => [
                        'start' => 0,
                        'end' => 100,
                    ],
                    'unit' => '%',
                ],
                'labels' => [
                    __( 'Bottom', 'premium-addons-pro' ),
                    __( 'Top', 'premium-addons-pro' ),
                ],
                'scales' => 1,
                'handles' => 'range',
                'condition'     => array_merge( $conditions, [
					'premium_img_layers_gray'     => 'yes'
				] ) ,
			]
		);
        
        $repeater->add_responsive_control('premium_img_layerstransform_origin_x',
            [
                'label' => __( 'X Anchor Point', 'premium-addons-pro' ),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'center',
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'premium-addons-pro' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'premium-addons-pro' ),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'premium-addons-pro' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'conditions'    => [
                    'terms' => [
                        [
                            'name' => 'premium_img_layers_scroll_effects',
                            'value' => 'yes',
                        ],
                        [
                            'relation'      =>  'or',
                            'terms'         => [
                                [
                                    'name'  =>  'premium_img_layers_rscroll',
                                    'value'  => 'yes'
                                ],
                                [
                                    'name'  =>  'premium_img_layers_scale',
                                    'value'  => 'yes'
                                ]
                            ],
                        ]
                    ]
                ],
                'label_block' => false,
                'toggle' => false,
                'render_type' => 'ui',
            ]
        );

		$repeater->add_responsive_control('premium_img_layerstransform_origin_y',
            [
                'label' => __( 'Y Anchor Point', 'premium-addons-pro' ),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'center',
                'options' => [
                    'top' => [
                        'title' => __( 'Top', 'premium-addons-pro' ),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'premium-addons-pro' ),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'bottom' => [
                        'title' => __( 'Bottom', 'premium-addons-pro' ),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'conditions'    => [
                    'terms' => [
                        [
                            'name' => 'premium_img_layers_scroll_effects',
                            'value' => 'yes',
                        ],
                        [
                            'relation'      =>  'or',
                            'terms'         => [
                                [
                                    'name'  =>  'premium_img_layers_rscroll',
                                    'value'  => 'yes'
                                ],
                                [
                                    'name'  =>  'premium_img_layers_scale',
                                    'value'  => 'yes'
                                ]
                            ],
                        ]
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.premium-img-layers-list-item' => 'transform-origin: {{premium_img_layerstransform_origin_x.VALUE}} {{VALUE}}',
                ],
                'label_block' => false,
                'toggle' => false
            ]
		);
        
        $repeater->add_control('premium_img_layers_float_effects',
            [
                'label'         => __('Floating Effects', 'premium-addons-pro'),
                'type'          => Controls_Manager::SWITCHER,
                'condition'     => [
                    'premium_img_layers_scroll_effects!' => 'yes'
                ]
            ]
        );
        
        $float_conditions = array(
            'premium_img_layers_float_effects' => 'yes'
        );
        
        $repeater->add_control('premium_img_layers_translate_float',
            [
                'label'         => __( 'Translate', 'premium-addons-pro' ),
                'type'          => Controls_Manager::SWITCHER,
                'frontend_available' => true,
                'condition'     => $float_conditions
            ]
        );
        
        $repeater->add_control('premium_img_layers_translatex',
			[
				'label'         => __( 'Translate X', 'premium-addons-pro' ),
				'type'          => Controls_Manager::SLIDER,
				'default' => [
                    'sizes' => [
                        'start' => -5,
                        'end' => 5,
                    ],
                    'unit' => 'px',
                ],
                'range' => [
                    'px' => [
                        'min' => -100,
                        'max' => 100,
                    ]
                ],
                'labels' => [
                    __( 'From', 'premium-addons-pro' ),
                    __( 'To', 'premium-addons-pro' ),
                ],
                'scales' => 1,
                'handles' => 'range',
                'condition'     => array_merge( $float_conditions, [
					'premium_img_layers_translate_float'     => 'yes'
				] )
			]
		);
        
        $repeater->add_control('premium_img_layers_translatey',
			[
				'label'         => __( 'Translate Y', 'premium-addons-pro' ),
				'type'          => Controls_Manager::SLIDER,
                'default' => [
                    'sizes' => [
                        'start' => -5,
                        'end' => 5,
                    ],
                    'unit' => 'px',
                ],
                'range' => [
                    'px' => [
                        'min' => -100,
                        'max' => 100,
                    ]
                ],
                'labels' => [
                    __( 'From', 'premium-addons-pro' ),
                    __( 'To', 'premium-addons-pro' ),
                ],
                'scales'    => 1,
                'handles'   => 'range',
                'condition' => array_merge( $float_conditions, [
					'premium_img_layers_translate_float'     => 'yes'
				] )
			]
		);
        
        $repeater->add_control('premium_img_layers_translate_speed',
			[
				'label'         => __( 'Speed', 'premium-addons-pro' ),
				'type'          => Controls_Manager::SLIDER,
				'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 10,
                        'step' => 0.1
                    ]
                ],
                'default' => [
                    'size' => 1,
                ],
				'condition' => array_merge( $float_conditions, [
					'premium_img_layers_translate_float'     => 'yes'
				] )
			]
		);
        
        $repeater->add_control('premium_img_layers_translate_rotate',
            [
                'label'         => __( 'Rotate', 'premium-addons-pro' ),
                'type'          => Controls_Manager::SWITCHER,
                'frontend_available' => true,
                'condition'     => $float_conditions
            ]
        );
        
        $repeater->add_control('premium_img_layers_float_rotatex',
			[
				'label'         => __( 'Rotate X', 'premium-addons-pro' ),
				'type'          => Controls_Manager::SLIDER,
				'default' => [
                    'sizes' => [
                        'start' => 0,
                        'end' => 45,
                    ],
                    'unit' => 'px',
                ],
                'range' => [
                    'px' => [
                        'min' => -180,
                        'max' => 180,
                    ]
                ],
                'labels' => [
                    __( 'From', 'premium-addons-pro' ),
                    __( 'To', 'premium-addons-pro' ),
                ],
                'scales' => 1,
                'handles' => 'range',
                'condition'     => array_merge( $float_conditions, [
					'premium_img_layers_translate_rotate'     => 'yes'
				] )
			]
		);
        
        $repeater->add_control('premium_img_layers_float_rotatey',
			[
				'label'         => __( 'Rotate Y', 'premium-addons-pro' ),
				'type'          => Controls_Manager::SLIDER,
				'default' => [
                    'sizes' => [
                        'start' => 0,
                        'end' => 45,
                    ],
                    'unit' => 'px',
                ],
                'range' => [
                    'px' => [
                        'min' => -180,
                        'max' => 180,
                    ]
                ],
                'labels' => [
                    __( 'From', 'premium-addons-pro' ),
                    __( 'To', 'premium-addons-pro' ),
                ],
                'scales' => 1,
                'handles' => 'range',
                'condition'     => array_merge( $float_conditions, [
					'premium_img_layers_translate_rotate'     => 'yes'
				] )
			]
		);
        
        $repeater->add_control('premium_img_layers_float_rotatez',
			[
				'label'         => __( 'Rotate Z', 'premium-addons-pro' ),
				'type'          => Controls_Manager::SLIDER,
                'default' => [
                    'sizes' => [
                        'start' => 0,
                        'end' => 45,
                    ],
                    'unit' => 'px',
                ],
                'range' => [
                    'px' => [
                        'min' => -180,
                        'max' => 180,
                    ]
                ],
                'labels' => [
                    __( 'From', 'premium-addons-pro' ),
                    __( 'To', 'premium-addons-pro' ),
                ],
                'scales'    => 1,
                'handles'   => 'range',
                'condition' => array_merge( $float_conditions, [
					'premium_img_layers_translate_rotate'     => 'yes'
				] )
			]
		);
        
        $repeater->add_control('premium_img_layers_rotate_speed',
			[
				'label'         => __( 'Speed', 'premium-addons-pro' ),
				'type'          => Controls_Manager::SLIDER,
				'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 10,
                        'step' => 0.1
                    ]
                ],
                'default' => [
                    'size' => 1,
                ],
				'condition' => array_merge( $float_conditions, [
					'premium_img_layers_translate_rotate'     => 'yes'
				] )
			]
		);
        
        $repeater->add_control('premium_img_layers_opacity_float',
            [
                'label'         => __( 'Opacity', 'premium-addons-pro' ),
                'type'          => Controls_Manager::SWITCHER,
                'frontend_available' => true,
                'condition'     => $float_conditions
            ]
        );

        $repeater->add_control('premium_img_layers_opacity_value',
			[
				'label'         => __( 'Value', 'premium-addons-pro' ),
				'type'          => Controls_Manager::SLIDER,
				'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1,
                        'step' => 0.1
                    ]
                ],
                'default' => [
                    'size' => 0.5,
                ],
				'condition' => array_merge( $float_conditions, [
					'premium_img_layers_opacity_float'     => 'yes'
				] )
			]
		);

        $repeater->add_control('premium_img_layers_opacity_speed',
			[
				'label'         => __( 'Speed', 'premium-addons-pro' ),
				'type'          => Controls_Manager::SLIDER,
				'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 10,
                        'step' => 0.1
                    ]
                ],
                'default' => [
                    'size' => 1,
                ],
				'condition' => array_merge( $float_conditions, [
					'premium_img_layers_opacity_float'     => 'yes'
				] )
			]
        );
        
        $repeater->add_control('premium_img_layers_zindex',
            [
                'label'         => __('z-index','premium-addons-pro'),
                'type'          => Controls_Manager::NUMBER,
                'default'       => 1,
                'selectors'     => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.premium-img-layers-list-item' => 'z-index: {{VALUE}};'
                    ],
                ]
            );
        
        $repeater->add_control('premium_img_layers_class', 
            [
                'label'         => __('CSS Classes','premium-addons-pro'),
                'type'          => Controls_Manager::TEXT,
                'description'   => __('Separate class with spaces','premium-addons-pro'),
            ]
        );
        
        $this->add_control('premium_img_layers_images_repeater',
			[
				'type'          => Controls_Manager::REPEATER,
				'fields'        => $repeater->get_controls(),
			]
		);
        
        $this->add_control('premium_parallax_layers_devices',
            [
                'label'             => __('Apply Scroll Effects On', 'premium-addons-pro'),
                'type'              => Controls_Manager::SELECT2,
                'options'           => [
                    'desktop'   => __('Desktop','premium-addons-pro'),
                    'tablet'    => __('Tablet','premium-addons-pro'),
                    'mobile'    => __('Mobile','premium-addons-pro'),
                ],
                'default'           => [ 'desktop', 'tablet', 'mobile' ],
                'multiple'          => true,
                'label_block'       => true
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_img_layers_container',
            [
                'label'         => __('Container', 'premium-addons-pro'),
            ]
        );
        
        $this->add_responsive_control('premium_img_layers_height',
            [
                'label'         => __('Minimum Height','premium-addons-pro'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', "em", "vh"],
                'range'         => [
                    'px'    => [
                        'min'   => 1, 
                        'max'   => 800,
                    ],
                    'em'    => [
                        'min'   => 1, 
                        'max'   => 80,
                    ],
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-img-layers-wrapper' => 'min-height: {{SIZE}}{{UNIT}}'
                ],
            ]
        );
        
        $this->add_responsive_control('premium_img_layers_overflow',
            [
                'label'         => __('Overflow', 'premium-addons-pro'),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'auto'          => __('Auto','premium-addons-pro'),
                    'visible'       => __('Visible','premium-addons-pro'),
                    'hidden'        => __('Hidden','premium-addons-pro'),
                    'scroll'        => __('Scroll','premium-addons-pro'),
                ],
                'default'       => 'visible',
                'selectors'     => [
                    '{{WRAPPER}} .premium-img-layers-wrapper'   => 'overflow: {{VALUE}}'
                ]
            ]
        );
        
        $this->end_controls_section();

        $this->start_controls_section('section_pa_docs',
            [
                'label'         => __('Helpful Documentations', 'premium-addons-pro'),
            ]
        );
        
        $docs = [
            'https://premiumaddons.com/docs/premium-image-layers-widget/' => 'Getting started »',
            'https://premiumaddons.com/docs/how-to-speed-up-elementor-pages-with-many-lottie-animations/' => 'How to speed up pages with many Lottie animations »',
            'https://premiumaddons.com/docs/customize-elementor-lottie-widget/' => 'How to Customize Lottie Animations »'
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
        
        $this->start_controls_section('premium_img_layers_images_style',
            [
                'label'         => __('Image', 'premium-addons-pro'),
                'tab'           => Controls_Manager::TAB_STYLE,
            ]
            );
        
        $this->add_control('premium_img_layers_images_background',
            [
                'label'         => __('Background Color', 'premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .premium-img-layers-list-item .premium-img-layers-image'  => 'background-color: {{VALUE}};',
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(), 
            [
                'name'          => 'premium_img_layers_images_border',
                'selector'      => '{{WRAPPER}} .premium-img-layers-list-item .premium-img-layers-image'
            ]
        );
        
        $this->add_responsive_control('premium_img_layers_images_border_radius',
            [
                'label'         => __('Border Radius', 'premium-addons-pro'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-img-layers-list-item .premium-img-layers-image' => 'border-top-left-radius: {{TOP}}{{UNIT}}; border-top-right-radius: {{RIGHT}}{{UNIT}}; border-bottom-right-radius: {{BOTTOM}}{{UNIT}}; border-bottom-left-radius: {{LEFT}}{{UNIT}};'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'          => 'premium_img_layers_images_shadow',
                'selector'      => '{{WRAPPER}} .premium-img-layers-list-item .premium-img-layers-image'
            ]
        );
        
        $this->add_responsive_control('premium_img_layers_margin',
            [
                'label'         => __('Margin', 'premium-addons-pro'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-img-layers-list-item .premium-img-layers-image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );
        
        $this->add_responsive_control('premium_img_layers_padding',
            [
                'label'         => __('Padding', 'premium-addons-pro'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-img-layers-list-item .premium-img-layers-image' => 'padding:  {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_img_layers_container_style',
            [
                'label'         => __('Container', 'premium-addons-pro'),
                'tab'           => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control('premium_img_layers_container_background',
            [
                'label'         => __('Background Color', 'premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .premium-img-layers-wrapper'  => 'background-color: {{VALUE}};',
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(), 
            [
                'name'          => 'premium_img_layers_container_border',
                'selector'      => '{{WRAPPER}} .premium-img-layers-wrapper'
            ]
        );
        
        $this->add_responsive_control('premium_img_layers_container_radius',
            [
                'label'         => __('Border Radius', 'premium-addons-pro'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-img-layers-wrapper' => 'border-top-left-radius: {{TOP}}{{UNIT}}; border-top-right-radius: {{RIGHT}}{{UNIT}}; border-bottom-right-radius: {{BOTTOM}}{{UNIT}}; border-bottom-left-radius: {{LEFT}}{{UNIT}};'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'          => 'premium_img_layers_container_shadow',
                'selector'      => '{{WRAPPER}} .premium-img-layers-wrapper'
            ]
        );
        
        $this->add_responsive_control('premium_img_layers_container_margin',
            [
                'label'         => __('Margin', 'premium-addons-pro'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-img-layers-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );
        
        $this->add_responsive_control('premium_img_layers_container_padding',
            [
                'label'         => __('Padding', 'premium-addons-pro'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-img-layers-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );
        
        $this->end_controls_section();
        
    }
    
    /**
	 * Render Image Layers widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
    protected function render() {

        $settings = $this->get_settings_for_display();
        
        $this->add_render_attribute( 'container', [
            'id'        => 'premium-img-layers-wrapper',
            'class'     => 'premium-img-layers-wrapper',
        ]);
        
        $scroll_effects = isset( $settings['premium_parallax_layers_devices'] ) ? $settings['premium_parallax_layers_devices'] : array();
        
        $this->add_render_attribute( 'container', 'data-devices', wp_json_encode( $scroll_effects ) );
        
        
    ?>

    <div <?php echo $this->get_render_attribute_string('container'); ?>>
        <ul class="premium-img-layers-list-wrapper">
            <?php $animation_arr = array();
            foreach( $settings['premium_img_layers_images_repeater'] as $index => $image ) :
                array_push( $animation_arr, $image['premium_img_layers_animation_switcher'] );
                if( 'yes' == $animation_arr[ $index ] ) {
                    $animation_class = $image['premium_img_layers_animation'];
                    if( '' != $image['premium_img_layers_animation_duration'] ) {
                        $animation_dur = 'animated-' . $image['premium_img_layers_animation_duration'];
                    } else {
                        $animation_dur = 'animated-';
                    }
                } else {
                        $animation_class = '';
                        $animation_dur = '';
                }

                $list_item_key = 'img_layer_' . $index;

                $position = ! empty ( $image['premium_img_layers_position'] ) ? $image['premium_img_layers_position'] : 'absolute';

                $this->add_render_attribute( $list_item_key, 'class',
                    [
                        'premium-img-layers-list-item',
                        $position,
                        esc_attr($image['premium_img_layers_class']),
                        'elementor-repeater-item-' . $image['_id']
                    ]
                );

                $this->add_render_attribute( $list_item_key, 'data-layer-animation',
                    [
                        $animation_class,
                        $animation_dur,
                    ]
                );

                if( 'yes' === $image['premium_img_layers_float_effects'] ) {
                    
                    $this->add_render_attribute( $list_item_key, 'data-float', 'true' );
                    
                    if( 'yes' == $image['premium_img_layers_translate_float'] ) {
                        
                        $this->add_render_attribute( $list_item_key, 'data-float-translate', 'true' );
                        
                        $this->add_render_attribute( $list_item_key, 'data-floatx-start', $image['premium_img_layers_translatex']['sizes']['start'] );
                        $this->add_render_attribute( $list_item_key, 'data-floatx-end', $image['premium_img_layers_translatex']['sizes']['end'] );
                        
                        $this->add_render_attribute( $list_item_key, 'data-floaty-start', $image['premium_img_layers_translatey']['sizes']['start'] );
                        $this->add_render_attribute( $list_item_key, 'data-floaty-end', $image['premium_img_layers_translatey']['sizes']['end'] );
                        
                        $this->add_render_attribute( $list_item_key, 'data-float-translate-speed', $image['premium_img_layers_translate_speed']['size'] );
                        
                    }
                    
                    if( 'yes' == $image['premium_img_layers_translate_rotate'] ) {
                        
                        $this->add_render_attribute( $list_item_key, 'data-float-rotate', 'true' );
                        
                        $this->add_render_attribute( $list_item_key, 'data-rotatex-start', $image['premium_img_layers_float_rotatex']['sizes']['start'] );
                        $this->add_render_attribute( $list_item_key, 'data-rotatex-end', $image['premium_img_layers_float_rotatex']['sizes']['end'] );
                        
                        $this->add_render_attribute( $list_item_key, 'data-rotatey-start', $image['premium_img_layers_float_rotatey']['sizes']['start'] );
                        $this->add_render_attribute( $list_item_key, 'data-rotatey-end', $image['premium_img_layers_float_rotatey']['sizes']['end'] );
                        
                        $this->add_render_attribute( $list_item_key, 'data-rotatez-start', $image['premium_img_layers_float_rotatez']['sizes']['start'] );
                        $this->add_render_attribute( $list_item_key, 'data-rotatez-end', $image['premium_img_layers_float_rotatez']['sizes']['end'] );
                        
                        $this->add_render_attribute( $list_item_key, 'data-float-rotate-speed', $image['premium_img_layers_rotate_speed']['size'] );
                        
                    }

                    if( 'yes' == $image['premium_img_layers_opacity_float'] ) {
                        
                        $this->add_render_attribute( $list_item_key, 'data-float-opacity', 'true' );
                        
                        $this->add_render_attribute( $list_item_key, 'data-float-opacity-value', $image['premium_img_layers_opacity_value']['size'] );

                        $this->add_render_attribute( $list_item_key, 'data-float-opacity-speed', $image['premium_img_layers_opacity_speed']['size'] );
                        
                    }
                    
                } elseif( 'yes' === $image['premium_img_layers_scroll_effects'] ) {

                    $this->add_render_attribute( $list_item_key, 'data-scrolls', 'true' );

                    if( 'yes' === $image['premium_img_layers_vscroll'] ) {

                        $speed = ! empty ( $image['premium_img_layers_vscroll_speed']['size'] ) ? $image['premium_img_layers_vscroll_speed']['size'] : 4;

                        $this->add_render_attribute( $list_item_key, 'data-vscroll', 'true' );

                        $this->add_render_attribute( $list_item_key, 'data-vscroll-speed', $speed );

                        $this->add_render_attribute( $list_item_key, 'data-vscroll-dir', $image['premium_img_layers_vscroll_direction'] );

                        $this->add_render_attribute( $list_item_key, 'data-vscroll-start', $image['premium_img_layers_vscroll_view']['sizes']['start'] );
                        $this->add_render_attribute( $list_item_key, 'data-vscroll-end', $image['premium_img_layers_vscroll_view']['sizes']['end'] );

                    }

                    if( 'yes' == $image['premium_img_layers_hscroll'] ) {

                        $speed = ! empty ( $image['premium_img_layers_hscroll_speed']['size'] ) ? $image['premium_img_layers_hscroll_speed']['size'] : 4;

                        $this->add_render_attribute( $list_item_key, 'data-hscroll', 'true' );

                        $this->add_render_attribute( $list_item_key, 'data-hscroll-speed', $speed );

                        $this->add_render_attribute( $list_item_key, 'data-hscroll-dir', $image['premium_img_layers_hscroll_direction'] );

                        $this->add_render_attribute( $list_item_key, 'data-hscroll-start', $image['premium_img_layers_hscroll_view']['sizes']['start'] );
                        $this->add_render_attribute( $list_item_key, 'data-hscroll-end', $image['premium_img_layers_hscroll_view']['sizes']['end'] );

                    }

                    if( 'yes' == $image['premium_img_layers_opacity'] ) {

                        $level = ! empty ( $image['premium_img_layers_opacity_level']['size'] ) ? $image['premium_img_layers_opacity_level']['size'] : 10;

                        $this->add_render_attribute( $list_item_key, 'data-oscroll', 'true' );

                        $this->add_render_attribute( $list_item_key, 'data-oscroll-level', $level );

                        $this->add_render_attribute( $list_item_key, 'data-oscroll-effect', $image['premium_img_layers_opacity_effect'] );

                        $this->add_render_attribute( $list_item_key, 'data-oscroll-start', $image['premium_img_layers_opacity_view']['sizes']['start'] );
                        $this->add_render_attribute( $list_item_key, 'data-oscroll-end', $image['premium_img_layers_opacity_view']['sizes']['end'] );

                    }

                    if( 'yes' == $image['premium_img_layers_blur'] ) {

                        $level = ! empty ( $image['premium_img_layers_blur_level']['size'] ) ? $image['premium_img_layers_blur_level']['size'] : 10;

                        $this->add_render_attribute( $list_item_key, 'data-bscroll', 'true' );

                        $this->add_render_attribute( $list_item_key, 'data-bscroll-level', $level );

                        $this->add_render_attribute( $list_item_key, 'data-bscroll-effect', $image['premium_img_layers_blur_effect'] );

                        $this->add_render_attribute( $list_item_key, 'data-bscroll-start', $image['premium_img_layers_blur_view']['sizes']['start'] );
                        $this->add_render_attribute( $list_item_key, 'data-bscroll-end', $image['premium_img_layers_blur_view']['sizes']['end'] );

                    }

                    if( 'yes' == $image['premium_img_layers_rscroll'] ) {

                        $speed = ! empty ( $image['premium_img_layers_rscroll_speed']['size'] ) ? $image['premium_img_layers_rscroll_speed']['size'] : 3;

                        $this->add_render_attribute( $list_item_key, 'data-rscroll', 'true' );

                        $this->add_render_attribute( $list_item_key, 'data-rscroll-speed', $speed );

                        $this->add_render_attribute( $list_item_key, 'data-rscroll-dir', $image['premium_img_layers_rscroll_direction'] );

                        $this->add_render_attribute( $list_item_key, 'data-rscroll-start', $image['premium_img_layers_rscroll_view']['sizes']['start'] );
                        $this->add_render_attribute( $list_item_key, 'data-rscroll-end', $image['premium_img_layers_rscroll_view']['sizes']['end'] );

                    }

                    if( 'yes' == $image['premium_img_layers_scale'] ) {

                        $speed = ! empty ( $image['premium_img_layers_scale_speed']['size'] ) ? $image['premium_img_layers_scale_speed']['size'] : 3;

                        $this->add_render_attribute( $list_item_key, 'data-scale', 'true' );

                        $this->add_render_attribute( $list_item_key, 'data-scale-speed', $speed );

                        $this->add_render_attribute( $list_item_key, 'data-scale-dir', $image['premium_img_layers_scale_direction'] );

                        $this->add_render_attribute( $list_item_key, 'data-scale-start', $image['premium_img_layers_scale_view']['sizes']['start'] );
                        $this->add_render_attribute( $list_item_key, 'data-scale-end', $image['premium_img_layers_scale_view']['sizes']['end'] );

                    }

                    if( 'yes' == $image['premium_img_layers_gray'] ) {

                        $level = ! empty ( $image['premium_img_layers_gray_level']['size'] ) ? $image['premium_img_layers_gray_level']['size'] : 10;

                        $this->add_render_attribute( $list_item_key, 'data-gscale', 'true' );

                        $this->add_render_attribute( $list_item_key, 'data-gscale-level', $level );

                        $this->add_render_attribute( $list_item_key, 'data-gscale-effect', $image['premium_img_layers_gray_effect'] );

                        $this->add_render_attribute( $list_item_key, 'data-gscale-start', $image['premium_img_layers_gray_view']['sizes']['start'] );
                        $this->add_render_attribute( $list_item_key, 'data-gscale-end', $image['premium_img_layers_gray_view']['sizes']['end'] );

                    }

                }

                if( 'yes' == $image['premium_img_layers_mouse'] ) {

                    $this->add_render_attribute( $list_item_key, 'data-' . $image['premium_img_layers_mouse_type'], 'true' );

                    if( 'parallax' === $image['premium_img_layers_mouse_type'] ) {

                        if( 'yes' === $image['premium_img_layers_mouse_reverse'] ) {
                            $this->add_render_attribute( $list_item_key, 'data-mparallax-reverse', 'true' );
                        }

                        if( 'yes' === $image['premium_img_layers_mouse_initial'] ) {
                            $this->add_render_attribute( $list_item_key, 'data-mparallax-init', 'true' );
                        }
                        
                    }

                    $this->add_render_attribute( $list_item_key, 'data-rate', ! empty( $image['premium_img_layers_rate'] ) ? $image['premium_img_layers_rate'] : -10  );

                }

                if( 'url' == $image['premium_img_layers_link_selection'] ){
                    $image_url = $image['premium_img_layers_link']['url'];
                } else {
                    $image_url = get_permalink($image['premium_img_layers_existing_link']);
                }

                $list_item_link = 'img_link_' . $index;
                if( 'yes' == $image['premium_img_layers_link_switcher'] ) {
                    $this->add_render_attribute( $list_item_link, 'class', 'premium-img-layers-link' );

                    $this->add_render_attribute( $list_item_link, 'href', $image_url );

                    if( ! empty( $image['premium_img_layers_link']['is_external'] ) ) {
                        $this->add_render_attribute( $list_item_link, 'target', '_blank' );
                    }
                    if( ! empty( $image['premium_img_layers_link']['nofollow'] ) ) {
                        $this->add_render_attribute( $list_item_link, 'rel', 'nofollow' );
                    }
                }

                if( 'animation' === $image['media_type'] ) {

                    $this->add_render_attribute( $list_item_key, [
                        'class' => 'premium-lottie-animation',
                        'data-lottie-url' => $image['lottie_url'],
                        'data-lottie-loop' => $image['lottie_loop'],
                        'data-lottie-reverse' => $image['lottie_reverse'],
                        'data-lottie-hover' => $image['lottie_hover'],
                        'data-lottie-render' => $image['lottie_renderer'],
                    ]);
                    
                }

            ?>

                <li <?php echo $this->get_render_attribute_string( $list_item_key ); ?>>
                    <?php
                        if( 'image'=== $image['media_type'] ) {
                            $image_src = $image['premium_img_layers_image'];

                            $image_src_size = Group_Control_Image_Size::get_attachment_image_src( $image_src['id'], 'thumbnail', $image );

                            if( empty( $image_src_size ) ) : $image_src_size = $image_src['url']; else: $image_src_size = $image_src_size; endif;

                            $alt    = Control_Media::get_image_alt( $image['premium_img_layers_image'] );

                    ?>
                        <img src="<?php echo $image_src_size; ?>" class="premium-img-layers-image" alt="<?php echo esc_attr( $alt ); ?>">
                    <?php } ?>
                        <?php if( $image['premium_img_layers_link_switcher'] === 'yes' ) : ?>
                            <a <?php echo $this->get_render_attribute_string ( $list_item_link ); ?>></a>
                        <?php endif; ?>

                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <?php }

    /**
	 * Render Image Layers widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
    protected function content_template() {
        
        ?>

        <#
        
            view.addRenderAttribute( 'container', {
                'id': 'premium-img-layers-wrapper',
                'class': 'premium-img-layers-wrapper',
                'data-devices': JSON.stringify( settings.premium_parallax_layers_devices )
            });
            
        
        #>

        <div {{{ view.getRenderAttributeString('container') }}}>
            <ul class="premium-img-layers-list-wrapper">
                
            <# var animationClass, animationDur, listItemKey, imageUrl, animationArr = [];
            
            _.each( settings.premium_img_layers_images_repeater, function( image, index ) {
            
                animationArr.push( image.premium_img_layers_animation_switcher );
                
                if( 'yes' == animationArr[index] ) {

                    animationClass = image.premium_img_layers_animation;

                    if( '' != image.premium_img_layers_animation_duration ) {

                        animationDur = 'animated-' + image.premium_img_layers_animation_duration;

                    } else {
                        animationDur = 'animated-';
                    }
                } else {

                        animationClass = '';

                        animationDur = '';

                }
                
                listItemKey = 'img_layer_' + index;

                var position = '' !== image.premium_img_layers_position ? image.premium_img_layers_position : 'absolute';
                
                view.addRenderAttribute( listItemKey, 'class',
                    [
                        'premium-img-layers-list-item',
                        position,
                        image.premium_img_layers_class,
                        'elementor-repeater-item-' + image._id
                    ]
                );

                view.addRenderAttribute( listItemKey, 'data-layer-animation',
                    [
                        animationClass,
                        animationDur,
                    ]
                );
                
                if( 'yes' == image.premium_img_layers_mouse ) {
                
                    var rate = '' != image.premium_img_layers_rate ? image.premium_img_layers_rate : -10;
                    
                    view.addRenderAttribute( listItemKey, 'data-' + image.premium_img_layers_mouse_type , 'true' );
                    
                    if( 'parallax' === image.premium_img_layers_mouse_type ) {

                        if( 'yes' === image.premium_img_layers_mouse_reverse ) {
                            view.addRenderAttribute( listItemKey, 'data-mparallax-reverse', 'true' );
                        }

                        if( 'yes' === image.premium_img_layers_mouse_initial ) {
                            view.addRenderAttribute( listItemKey, 'data-mparallax-init', 'true' );
                        }

                    }
                        
                    
                    view.addRenderAttribute( listItemKey, 'data-rate', rate );
                
                }
                
                if( 'yes' === image.premium_img_layers_float_effects ) {
                    
                    view.addRenderAttribute( listItemKey, 'data-float', 'true' );
                    
                    if( 'yes' == image.premium_img_layers_translate_float ) {
                    
                        view.addRenderAttribute( listItemKey, 'data-float-translate', 'true' );
                        
                        view.addRenderAttribute( listItemKey, 'data-floatx-start', image.premium_img_layers_translatex.sizes.start );
                        view.addRenderAttribute( listItemKey, 'data-floatx-end', image.premium_img_layers_translatex.sizes.end );
                        
                        view.addRenderAttribute( listItemKey, 'data-floaty-start', image.premium_img_layers_translatey.sizes.start );
                        view.addRenderAttribute( listItemKey, 'data-floaty-end', image.premium_img_layers_translatey.sizes.end );
                        
                        view.addRenderAttribute( listItemKey, 'data-float-translate-speed', image.premium_img_layers_translate_speed.size );
                        
                    }
                    
                    if( 'yes' == image.premium_img_layers_translate_rotate ) {
                        
                        view.addRenderAttribute( listItemKey, 'data-float-rotate', 'true' );
                        
                        view.addRenderAttribute( listItemKey, 'data-rotatex-start', image.premium_img_layers_float_rotatex.sizes.start );
                        view.addRenderAttribute( listItemKey, 'data-rotatex-end', image.premium_img_layers_float_rotatex.sizes.end );
                        
                        view.addRenderAttribute( listItemKey, 'data-rotatey-start', image.premium_img_layers_float_rotatey.sizes.start );
                        view.addRenderAttribute( listItemKey, 'data-rotatey-end', image.premium_img_layers_float_rotatey.sizes.end );
                        
                        view.addRenderAttribute( listItemKey, 'data-rotatez-start', image.premium_img_layers_float_rotatez.sizes.start );
                        view.addRenderAttribute( listItemKey, 'data-rotatez-end', image.premium_img_layers_float_rotatez.sizes.end );
                        
                        view.addRenderAttribute( listItemKey, 'data-float-rotate-speed', image.premium_img_layers_rotate_speed.size );
                        
                    }

                    if( 'yes' === image.premium_img_layers_opacity_float ) {
                        
                        view.addRenderAttribute( listItemKey, 'data-float-opacity', 'true' );
                        
                        view.addRenderAttribute( listItemKey, 'data-float-opacity-value', image.premium_img_layers_opacity_value.size );

                        view.addRenderAttribute( listItemKey, 'data-float-opacity-speed', image.premium_img_layers_opacity_speed.size );
                        
                    }
                    
                } else if( 'yes' == image.premium_img_layers_scroll_effects ) {
                
                    view.addRenderAttribute( listItemKey, 'data-scrolls', 'true' );
                
                    if( 'yes' == image.premium_img_layers_vscroll ) {

                        var speed = '' !== image.premium_img_layers_vscroll_speed.size ? image.premium_img_layers_vscroll_speed.size : 4;

                        view.addRenderAttribute( listItemKey, 'data-vscroll', 'true' );

                        view.addRenderAttribute( listItemKey, 'data-vscroll-speed', speed );

                        view.addRenderAttribute( listItemKey, 'data-vscroll-dir', image.premium_img_layers_vscroll_direction );

                        view.addRenderAttribute( listItemKey, 'data-vscroll-start', image.premium_img_layers_vscroll_view.sizes.start );

                        view.addRenderAttribute( listItemKey, 'data-vscroll-end', image.premium_img_layers_vscroll_view.sizes.end );

                    }

                    if( 'yes' == image.premium_img_layers_hscroll ) {

                        var speed = '' !== image.premium_img_layers_hscroll_speed.size ? image.premium_img_layers_hscroll_speed.size : 4;

                        view.addRenderAttribute( listItemKey, 'data-hscroll', 'true' );

                        view.addRenderAttribute( listItemKey, 'data-hscroll-speed', speed );

                        view.addRenderAttribute( listItemKey, 'data-hscroll-dir', image.premium_img_layers_hscroll_direction );

                        view.addRenderAttribute( listItemKey, 'data-hscroll-start', image.premium_img_layers_hscroll_view.sizes.start );

                        view.addRenderAttribute( listItemKey, 'data-hscroll-end', image.premium_img_layers_hscroll_view.sizes.end );

                    }

                    if( 'yes' == image.premium_img_layers_opacity ) {

                        var level = '' !== image.premium_img_layers_opacity_level.size ? image.premium_img_layers_opacity_level.size : 4;

                        view.addRenderAttribute( listItemKey, 'data-oscroll', 'true' );

                        view.addRenderAttribute( listItemKey, 'data-oscroll-level', level );

                        view.addRenderAttribute( listItemKey, 'data-oscroll-effect', image.premium_img_layers_opacity_effect );

                        view.addRenderAttribute( listItemKey, 'data-oscroll-start', image.premium_img_layers_opacity_view.sizes.start );

                        view.addRenderAttribute( listItemKey, 'data-oscroll-end', image.premium_img_layers_opacity_view.sizes.end );

                    }
                    
                    if( 'yes' == image.premium_img_layers_blur ) {

                        var level = '' !== image.premium_img_layers_blur_level.size ? image.premium_img_layers_blur_level.size : 4;

                        view.addRenderAttribute( listItemKey, 'data-bscroll', 'true' );

                        view.addRenderAttribute( listItemKey, 'data-bscroll-level', level );

                        view.addRenderAttribute( listItemKey, 'data-bscroll-effect', image.premium_img_layers_blur_effect );

                        view.addRenderAttribute( listItemKey, 'data-bscroll-start', image.premium_img_layers_blur_view.sizes.start );

                        view.addRenderAttribute( listItemKey, 'data-bscroll-end', image.premium_img_layers_blur_view.sizes.end );

                    }
                    
                    if( 'yes' == image.premium_img_layers_rscroll ) {

                        var speed = '' !== image.premium_img_layers_rscroll_speed.size ? image.premium_img_layers_rscroll_speed.size : 3;

                        view.addRenderAttribute( listItemKey, 'data-rscroll', 'true' );

                        view.addRenderAttribute( listItemKey, 'data-rscroll-speed', speed );

                        view.addRenderAttribute( listItemKey, 'data-rscroll-dir', image.premium_img_layers_rscroll_direction );

                        view.addRenderAttribute( listItemKey, 'data-rscroll-start', image.premium_img_layers_rscroll_view.sizes.start );

                        view.addRenderAttribute( listItemKey, 'data-rscroll-end', image.premium_img_layers_rscroll_view.sizes.end );

                    }
                    
                    if( 'yes' == image.premium_img_layers_scale ) {

                        var speed = '' !== image.premium_img_layers_scale_speed.size ? image.premium_img_layers_scale_speed.size : 3;

                        view.addRenderAttribute( listItemKey, 'data-scale', 'true' );

                        view.addRenderAttribute( listItemKey, 'data-scale-speed', speed );

                        view.addRenderAttribute( listItemKey, 'data-scale-dir', image.premium_img_layers_scale_direction );

                        view.addRenderAttribute( listItemKey, 'data-scale-start', image.premium_img_layers_scale_view.sizes.start );

                        view.addRenderAttribute( listItemKey, 'data-scale-end', image.premium_img_layers_scale_view.sizes.end );

                    }
                    
                    if( 'yes' == image.premium_img_layers_gray ) {

                        var level = '' !== image.premium_img_layers_gray_level.size ? image.premium_img_layers_gray_level.size : 10;

                        view.addRenderAttribute( listItemKey, 'data-gscale', 'true' );

                        view.addRenderAttribute( listItemKey, 'data-gscale-level', level );

                        view.addRenderAttribute( listItemKey, 'data-gscale-effect', image.premium_img_layers_gray_effect );

                        view.addRenderAttribute( listItemKey, 'data-gscale-start', image.premium_img_layers_gray_view.sizes.start );

                        view.addRenderAttribute( listItemKey, 'data-gscale-end', image.premium_img_layers_gray_view.sizes.end );

                    }
                
                }
                
                if( 'url' == image.premium_img_layers_link_selection ) {

                    imageUrl = image.premium_img_layers_link.url;

                } else {

                    imageUrl = image.premium_img_layers_existing_link;

                } 
                
                var imageObj = {
                    id: image.premium_img_layers_image.id,
                    url: image.premium_img_layers_image.url,
                    size: image.thumbnail_size,
                    dimension: image.thumbnail_custom_dimension,
                    model: view.getEditModel()
                },
                
                image_url = elementor.imagesManager.getImageUrl( imageObj );

                if( 'animation' === image.media_type ) {

                    view.addRenderAttribute( listItemKey, {
                        'class':  'premium-lottie-animation',
                        'data-lottie-url': image.lottie_url,
                        'data-lottie-loop': image.lottie_loop,
                        'data-lottie-reverse': image.lottie_reverse,
                        'data-lottie-hover': image.lottie_hover,
                        'data-lottie-render': image.lottie_renderer,
                    });

                }
                
                #>
                
                <li {{{ view.getRenderAttributeString(listItemKey) }}}>
                    <# if( 'image'=== image.media_type ) { #>
                        <img src="{{ image_url }}" class="premium-img-layers-image">
                    <# } #>
                    <# if( 'yes' == image.premium_img_layers_link_switcher ) { #>
                        <a class="premium-img-layers-link" href="{{ imageUrl }}"></a>
                    <# } #>
                </li>
                
            <# } );
                
            #>
                
            </ul>
        </div>

        <?php
    }

}