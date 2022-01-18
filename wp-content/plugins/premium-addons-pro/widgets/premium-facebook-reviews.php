<?php 

/**
 * Class: Premium_Facebook_Reviews_Widget
 * Name: Facebook Reviews
 * Slug: premium-facebook-reviews
 */
namespace PremiumAddonsPro\Widgets;

// Elementor Classes.
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Text_Shadow;

// PremiumAddons Classes.
use PremiumAddons\Includes\Helper_Functions;

if( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class Premium_Facebook_Reviews
 */
class Premium_Facebook_Reviews extends Widget_Base {

    public function get_name() {
        return 'premium-facebook-reviews';
    }

    public function get_title() {
        return sprintf( '%1$s %2$s', Helper_Functions::get_prefix(), __('Facebook Reviews', 'premium-addons-pro') );
    }
    
    public function get_icon() {
        return 'pa-pro-facebook-reviews';
    }
    
    public function get_categories() {
        return ['premium-elements'];
    }
    
    public function get_style_depends() {
        return [
            'font-awesome-5-all',
            'premium-addons'
        ];
    }
    
    public function get_script_depends() {
        return [
            'jquery-slick',
            'isotope-js',
            'premium-pro-js'
        ];
    }

    public function is_reload_preview_required() {
        return true;
    }
    
    public function get_custom_help_url() {
		return 'https://premiumaddons.com/support/';
	}
    
    /**
	 * Register Facebook Reviews controls.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
    protected function _register_controls() {

        $this->start_controls_section('general',
            [
                'label'         => __( 'Access Credentials', 'premium-addons-pro' )
            ]
        );

        $this->add_control('facebook_login',
            [
                'type'  => Controls_Manager::RAW_HTML,
                'raw'   => '<form onsubmit="connectFbInit(this);" action="javascript:void(0);" data-type="reviews"><input type="submit" value="Log in with Facebook" class="elementor-button" style="background-color: #3b5998; color: #fff;"></form>',
                'label_block' => true,
            ]
        );
        
        $this->add_control('login_notice',
			[
				'raw' => '<strong>' . __( 'Please note!', 'premium-addons-pro' ) . '</strong> ' . __( 'You need to select only one page per widget.', 'premium-addons-pro' ),
				'type' => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
				'render_type' => 'ui'
			]
		);

        $this->add_control('page_name',
            [
                'label'         => __('Page Name', 'premium-addons-pro'),
                'default'       => 'leap13',
                'dynamic'       => [ 'active' => true ],
                'type'          => Controls_Manager::TEXT,
            ]
        );

        $this->add_control('page_id',
            [
                'label'         => __('Page ID', 'premium-addons-pro'),
                'default'       => '734199906647993',
                'dynamic'       => [ 'active' => true ],
                'type'          => Controls_Manager::TEXT,
            ]
        );
        
        $this->add_control('page_access',
            [
                'label'         => __('Page Access Token', 'premium-addons-pro'),
                'dynamic'       => [ 'active' => true ],
                'type'          => Controls_Manager::TEXTAREA
            ]
        );

        $this->end_controls_section();
        
        $this->start_controls_section('content',
            [
                'label'         => __( 'Display Options', 'premium-addons-pro' )
            ]
        );

        $this->add_control(
			'skin_type',
			array(
				'label'        => __( 'Skin', 'premium-addons-pro' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'default',
				'options'      => array(
					'default' => __( 'Classic', 'premium-addons-pro' ),
					'card'   => __( 'Cards', 'premium-addons-pro' ),
				),
				'render_type'  => 'template',
				'prefix_class' => 'premium-social-reviews-',
			)
		);
        
        $this->start_controls_tabs('display_tabs');
        
        $this->start_controls_tab('page_tab',
            [
                'label'         => __('Page', 'premium-addons-pro'),
                'condition'     => [
                    'page_info'  => 'yes'
                ]
            ]
        );
        
        $this->add_control('page_custom_image_switch',
            [
                'label'         => __('Replace Page Image', 'premium-addons-pro'),
                'type'          => Controls_Manager::SWITCHER,
                'condition'     => [
                    'page_info'  => 'yes'
                ]
            ]
        );
        
        $this->add_control('page_custom_image',
           [
                'label'         => __( 'Upload Image', 'premium-addons-pro' ),
                'type'          => Controls_Manager::MEDIA,
                'dynamic'       => [ 'active' => true ],
                'condition'     => [
                    'page_info'  => 'yes',
                    'page_custom_image_switch'  => 'yes'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name'          => 'thumbnail',
                'default'       => 'full',
                'condition'     => [
                    'page_info'                     => 'yes',
                    'page_custom_image_switch'      => 'yes'
                ],
            ]
        );
        
        $this->add_control('page_display',
            [
                'label'         => __( 'Display', 'premium-addons-pro' ),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'inline'        => __('Inline', 'premium-addons-pro'),
                    'block'         => __('Block', 'premium-addons-pro'),
                ],
                'render_type'   => 'ui',
                'default'       => 'block',
                'condition'         => [
                    'page_info'  => 'yes'
                ]
            ]
        );
        
        $this->add_responsive_control('page_image_align',
            [
                'label'         => __( 'Image Alignment', 'premium-addons-pro' ),
                'type'          => Controls_Manager::CHOOSE,
                'options'       => [
                    'flex-start'          => [
                        'title'=> __( 'Top', 'premium-addons-pro' ),
                        'icon' => 'fa fa-long-arrow-up',
                    ],
                    'center'        => [
                        'title'=> __( 'Center', 'premium-addons-pro' ),
                        'icon' => 'fa fa-align-justify',
                    ],
                    'flex-end'         => [
                        'title'=> __( 'Bottom', 'premium-addons-pro' ),
                        'icon' => 'fa fa-long-arrow-down',
                    ],
                ],
                'default'       => 'center',
                'toggle'        => false,
                'selectors'     => [
                    '{{WRAPPER}} .premium-fb-rev-page-inner .premium-fb-rev-page-left' => 'align-self: {{VALUE}};',
                    ],
                'condition'     => [
                    'page_display'   => 'inline'
                ]
            ]
        );
        
        $this->add_responsive_control('place_text_align',
            [
                'label'         => __( 'Text Alignment', 'premium-addons-pro' ),
                'type'          => Controls_Manager::CHOOSE,
                'options'       => [
                    'flex-start'          => [
                        'title'=> __( 'Top', 'premium-addons-pro' ),
                        'icon' => 'fa fa-long-arrow-up',
                    ],
                    'center'        => [
                        'title'=> __( 'Center', 'premium-addons-pro' ),
                        'icon' => 'fa fa-align-justify',
                    ],
                    'flex-end'         => [
                        'title'=> __( 'Bottom', 'premium-addons-pro' ),
                        'icon' => 'fa fa-long-arrow-down',
                    ],
                ],
                'default'       => 'center',
                'toggle'        => false,
                'selectors'     => [
                    '{{WRAPPER}} .premium-fb-rev-page-inner .premium-fb-rev-page-right' => 'align-self: {{VALUE}};',
                    ],
                'condition'     => [
                    'page_display'   => 'inline'
                ]
            ]
        );
        
        $this->add_control('page_dir',
            [
                'label'         => __( 'Direction', 'premium-addons-pro' ),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'rtl'   => 'RTL',
                    'ltr'   => 'LTR',
                ],
                'default'       => 'ltr',
                'prefix_class'  => 'premium-reviews-src-',
                'frontend_available' => true,
                'condition' => [
                    'page_display'   => 'inline'
                ]
            ]
        );
        
        $this->add_responsive_control('page_align',
            [
                'label'         => __( 'Page Alignment', 'premium-addons-pro' ),
                'type'          => Controls_Manager::CHOOSE,
                'options'       => [
                    'left'          => [
                        'title'=> __( 'Left', 'premium-addons-pro' ),
                        'icon' => 'fa fa-align-left',
                        ],
                    'center'        => [
                        'title'=> __( 'Center', 'premium-addons-pro' ),
                        'icon' => 'fa fa-align-center',
                        ],
                    'right'         => [
                        'title'=> __( 'Right', 'premium-addons-pro' ),
                        'icon' => 'fa fa-align-right',
                        ],
                    ],
                'default'       => 'center',
                'toggle'        => false,
                'selectors'     => [
                    '{{WRAPPER}} .premium-fb-rev-container .premium-fb-rev-page' => 'text-align: {{VALUE}};',
                    ],
                'condition'     => [
                    'page_info'   => 'yes'
                ]
            ]
        );
        
        $this->end_controls_tab();
        
        $this->start_controls_tab('reviews_tab',
            [
                'label'         => __('Reviews', 'premium-addons-pro'),
            ]
        );
        
        
        $this->add_responsive_control('reviews_columns',
            [
                'label'             => __('Reviews/Row', 'premium-addons-pro'),
                'type'              => Controls_Manager::SELECT,
                'options'           => [
                    '100%'  => __('1 Column', 'premium-addons-pro'),
                    '50%'   => __('2 Columns', 'premium-addons-pro'),
                    '33.33%'=> __('3 Columns', 'premium-addons-pro'),
                    '25%'   => __('4 Columns', 'premium-addons-pro'),
                    '20%'   => __('5 Columns', 'premium-addons-pro'),
                    '16.667%'=> __('6 Columns', 'premium-addons-pro'),
                ],
                'default'           => '33.33%',
                'render_type'       => 'template',
                'selectors'         => [
                    '{{WRAPPER}} .premium-fb-rev-review-wrap' => 'width: {{VALUE}}'
                ]
            ]
        );
        
        $this->add_control('reviews_display',
            [
                'label'         => __( 'Display', 'premium-addons-pro' ),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'inline'        => __('Inline', 'premium-addons-pro'),
                    'block'         => __('Block', 'premium-addons-pro'),
                ],
                'default'       => 'block',
                'render_type'   => 'ui',
            ]
        );
        
        $this->add_responsive_control('reviews_image_align',
            [
                'label'         => __( 'Image Alignment', 'premium-addons-pro' ),
                'type'          => Controls_Manager::CHOOSE,
                'options'       => [
                    'flex-start'          => [
                        'title'=> __( 'Top', 'premium-addons-pro' ),
                        'icon' => 'fa fa-long-arrow-up',
                    ],
                    'center'        => [
                        'title'=> __( 'Center', 'premium-addons-pro' ),
                        'icon' => 'fa fa-align-justify',
                    ],
                    'flex-end'         => [
                        'title'=> __( 'Bottom', 'premium-addons-pro' ),
                        'icon' => 'fa fa-long-arrow-down',
                    ],
                ],
                'default'       => 'flex-start',
                'toggle'        => false,
                'selectors'     => [
                    '{{WRAPPER}} .premium-fb-rev-review-inner .premium-fb-rev-content-left' => 'align-self: {{VALUE}};',
                ],
                'condition'     => [
                    'reviews_display'  => 'inline',
                    'skin_type'        => 'default'
                ]
            ]
        );
        
        $this->add_responsive_control('reviews_text_align',
            [
                'label'         => __( 'Text Alignment', 'premium-addons-pro' ),
                'type'          => Controls_Manager::CHOOSE,
                'options'       => [
                    'flex-start'          => [
                        'title'=> __( 'Top', 'premium-addons-pro' ),
                        'icon' => 'fa fa-long-arrow-up',
                    ],
                    'center'        => [
                        'title'=> __( 'Center', 'premium-addons-pro' ),
                        'icon' => 'fa fa-align-justify',
                    ],
                    'flex-end'         => [
                        'title'=> __( 'Bottom', 'premium-addons-pro' ),
                        'icon' => 'fa fa-long-arrow-down',
                    ],
                ],
                'default'       => 'center',
                'toggle'        => false,
                'selectors'     => [
                    '{{WRAPPER}} .premium-fb-rev-review-inner .premium-fb-rev-content-right' => 'align-self: {{VALUE}};',
                    ],
                'condition'     => [
                    'reviews_display' => 'inline',
                    'skin_type'       => 'default'
                ]
            ]
        );
        
        $this->add_control('reviews_style',
            [
                'label'         => __( 'Layout', 'premium-addons-pro' ),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'even'          => __('Even', 'premium-addons-pro'),
                    'masonry'       => __('Masonry', 'premium-addons-pro'),
                ],
                'default'       => 'masonry',
                'condition'     => [
                    'reviews_columns!'  => '100%'
                ]
            ]
        );
        
        $this->add_control('reviews_dir',
            [
                'label'         => __( 'Direction', 'premium-addons-pro' ),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'rtl'           => 'RTL',
                    'ltr'           => 'LTR',
                ],
                'default'       => 'ltr',
                'prefix_class'  => 'premium-reviews-',
                'frontend_available' => true,
                'condition'     => [
                    'reviews_display' => 'inline',
                    'skin_type'       => 'default'
                ]
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
                    'justify'   => [
                        'title'=> __( 'Justify', 'premium-addons-pro' ),
                        'icon' => 'fa fa-align-justify',
                    ],
                ],
                'default'       => 'center',
                'toggle'        => false,
                'condition'     => [
                    'reviews_display'   => 'block'
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-fb-rev-container .premium-fb-rev-review' => 'text-align: {{VALUE}};',
                    ],
                ]
            );
        
        //Condition already added to JS code handling masonry layout, so we don't need this anymore.
        // $this->add_control('reviews_carousel_notice', 
        //     [
        //         'raw'               => __('Kindly, be noted that Carousel option only works with Even Layout', 'premium-addons-pro'),
        //         'type'              => Controls_Manager::RAW_HTML,
        //         'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
        //         'condition'     => [
        //             'reviews_columns!'  => '100%'
        //         ]
        //     ] 
        // );
        
        $this->add_control('reviews_carousel',
            [
                'label'         => __('Carousel', 'premium-addons-pro'),
                'type'          => Controls_Manager::SWITCHER
            ]
        );
        
        $this->add_control('carousel_play',
            [
                'label'         => __('Auto Play', 'premium-addons-pro'),
                'type'          => Controls_Manager::SWITCHER,
                'condition'     => [
                    'reviews_carousel'  => 'yes'
                ]
            ]
        );
        
        $this->add_control('carousel_autoplay_speed',
			[
				'label'			=> __( 'Autoplay Speed', 'premium-addons-pro' ),
				'description'	=> __( 'Autoplay Speed means at which time the next slide should come. Set a value in milliseconds (ms)', 'premium-addons-pro' ),
				'type'			=> Controls_Manager::NUMBER,
				'default'		=> 5000,
				'condition'		=> [
					'reviews_carousel' => 'yes',
                    'carousel_play' => 'yes',
				],
			]
		);
        
        $this->add_responsive_control('carousel_arrows_pos',
            [
                'label'         => __('Arrows Position', 'premium-addons-pro'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', "em"],
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
                'condition'		=> [
					'reviews_carousel' => 'yes'
				],
                'selectors'     => [
                    '{{WRAPPER}} .premium-fb-rev-reviews a.carousel-arrow.carousel-next' => 'right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .premium-fb-rev-reviews a.carousel-arrow.carousel-prev' => 'left: {{SIZE}}{{UNIT}};',
                ]
            ]
        );
        
        $this->add_control('carousel_rtl',
            [
                'label'         => __('RTL Mode', 'premium-addons-pro'),
                'description'   => __('Recommended for right to left Sites', 'premium-addons-pro'),
                'type'          => Controls_Manager::SWITCHER,
                'condition'     => [
                    'reviews_carousel'  => 'yes'
                ]
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        $this->end_controls_section();
        
        $this->start_controls_section('adv',
            [
                'label'         => __( 'Advanced Settings', 'premium-addons-pro' )
            ]
        );
        
        $this->add_control('page_info',
            [
                'label'         => __('Page Info', 'premium-addons-pro'),
                'type'          => Controls_Manager::SWITCHER,
                'default'       => 'yes'
            ]
        );
        
        $this->add_control('page_rate',
            [
                'label'         => __('Page Rate', 'premium-addons-pro'),
                'type'          => Controls_Manager::SWITCHER,
                'default'       => 'yes',
                'condition'     => [
                    'page_info'  => 'yes'
                ],
            ]
        );
        
        $this->add_control('text',
            [
                'label'         => __('Show Review Text', 'premium-addons-pro'),
                'type'          => Controls_Manager::SWITCHER,
                'default'       => 'yes',
            ]
        );
        
        $this->add_control('stars',
            [
                'label'         => __('Show Stars', 'premium-addons-pro'),
                'type'          => Controls_Manager::SWITCHER,
                'default'       => 'yes'
            ]
        );
        
        $this->add_control('date',
            [
                'label'         => __('Show Date', 'premium-addons-pro'),
                'type'          => Controls_Manager::SWITCHER,
                'default'       => 'yes'
            ]
        );
        
        $this->add_control('date_position',
            [
                'label'         => __( 'Date Position', 'premium-addons-pro' ),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'column'            => __('Above Stars', 'premium-addons-pro'),
                    'column-reverse'    => __('Below Stars', 'premium-addons-pro'),
                ],
                'default'       => 'column',
                'condition'     => [
                    'stars' => 'yes',
                    'date'  => 'yes'
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-fb-rev-info'  => 'flex-direction: {{VALUE}}'
                ]
            ]
        );
        
        $this->add_control('date_format',
            [
                'label'         => __( 'Date Format', 'premium-addons-pro' ),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'd/m/Y' => 'DD/MM/YYYY',
                    'm/d/Y' => 'MM/DD/YYYY'
                ],
                'default'       => 'd/m/Y',
                'condition'         => [
                    'date'  => 'yes'
                ]
            ]
        );

        $this->add_control('filter',
            [
                'label'         => __('Filter', 'premium-addons-pro'),
                'type'          => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control('filter_min',
            [
                'label'         => __( 'Min Stars', 'premium-addons-pro' ),
                'type'          => Controls_Manager::NUMBER,
                'min'           => 1,
                'max'           => 5,
                'condition'     => [
                    'filter' => 'yes'
                ],
            ]
        );

        $this->add_control('filter_max',
            [
                'label'         => __( 'Max Stars', 'premium-addons-pro' ),
                'type'          => Controls_Manager::NUMBER,
                'min'           => 1,
                'max'           => 5,
                'condition'     => [
                    'filter' => 'yes'
                ],
            ]
        );
        
        $this->add_control('limit',
            [
                'label'         => __('Reviews Limit', 'premium-addons-pro'),
                'type'          => Controls_Manager::SWITCHER,
            ]
        );
        
        $this->add_control('limit_num',
            [
                'label'         => __( 'Number of Reviews', 'premium-addons-pro' ),
                'type'          => Controls_Manager::NUMBER,
                'min'           => 0,
                'description'   => __( 'Set a number of reviews to retrieve', 'premium-addons-pro' ),
                'condition'     => [
                    'limit' => 'yes'
                ],
            ]
        );
        
        $this->add_control('words_num',
            [
                'label'         => __( 'Review Words Length', 'premium-addons-pro' ),
                'type'          => Controls_Manager::NUMBER,
                'min'           => 1,
            ]
        );

        $this->add_control('readmore',
			[
				'label'		=> __( 'Read More Text', 'premium-addons-pro' ),
				'type'		=> Controls_Manager::TEXT,
                'default'   => __( 'Read More »', 'premium-addons-pro' ),
                'condition' => [
                    'words_num!'   => ''
                ]
			]
		);
        
        $this->add_control('schema',
            [
                'label'         => __('Rating Schema', 'premium-addons-pro'),
                'type'          => Controls_Manager::SWITCHER
            ]
        );
        
        $this->add_control('schema_type',
            [
                'label'         => __( 'Schema Type', 'premium-addons-pro' ),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'Place'        => __( 'Place', 'premium-addons-pro' ),
                    'Organization' => __( 'Organization', 'premium-addons-pro' ),
                    'Service'      => __( 'Service', 'premium-addons-pro' ),
                ],
                'default'       => 'Place',
                'condition'         => [
                    'schema'    => 'yes'
                ]
            ]
        );
        
        $this->add_control('schema_doc',
            [
                'type'            => Controls_Manager::RAW_HTML,
                'raw'             => __( 'Enabling schema improves SEO as it helps to list star ratings in search engine results.', 'premium-addons-pro' ),
                'content_classes' => 'editor-pa-doc',
                'condition'         => [
                    'schema'    => 'yes'
                ]
            ]
        );
        
        $this->add_control('reload',
            [
                'label'         => __( 'Reload Reviews Once Every', 'premium-addons-pro' ),
                'type'          => Controls_Manager::SELECT,
                'options'       => array(
                    'hour'  => __( 'Hour', 'premium-addons-pro' ),
                    'day'   => __( 'Day', 'premium-addons-pro' ),
                    'week'  => __( 'Week', 'premium-addons-pro' ),
                    'month' => __( 'Month', 'premium-addons-pro' ),
                    'year'  => __( 'Year', 'premium-addons-pro' ),
                ),
                'default'       => 'day',
            ]
        );
        
        $this->end_controls_section();

        $this->start_controls_section('section_pa_docs',
            [
                'label'         => __('Helpful Documentations', 'premium-addons-pro'),
            ]
        );

        $docs = [
            'https://premiumaddons.com/docs/facebook-reviews-widget-tutorial/' => 'Getting started »',
            'https://www.youtube.com/watch?v=zl-OFo3IFd8' => 'Check the video tutorial »',
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
        
        $this->start_controls_section('images',
            [
                'label'         => __('Images', 'premium-addons-pro'),
                'tab'           => Controls_Manager::TAB_STYLE,
                ]
            );
        
        $this->start_controls_tabs('images_tabs');
        
        $this->start_controls_tab('page_img_tab',
            [
                'label'         => __('Page', 'premium-addons-pro'),
                'condition'     => [
                    'page_info'  => 'yes'
                ]
            ]
        );
        
        $this->add_responsive_control('page_image_size',
            [
                'label'         => __('Size', 'premium-addons-pro'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', "em"],
                'range'         => [
                    'px'    => [
                        'min'       => 1, 
                        'max'       => 200,
                    ],
                ],
                'default'       => [
                    'unit'  => 'px',
                    'size'  => 60
                ],
                'condition'     => [
                    'page_custom_image_switch!'    => 'yes',
                    'page_info'  => 'yes'
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-fb-rev-page-inner .premium-fb-rev-img' => 'width: {{SIZE}}{{UNIT}};'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(), 
            [
                'name'          => 'page_image_border',
                'selector'      => '{{WRAPPER}} .premium-fb-rev-page-inner .premium-fb-rev-img',
                'condition'     => [
                    'page_info'  => 'yes'
                ],
            ]
        );
        
        $this->add_control('page_image_border_radius',
            [
                'label'         => __('Border Radius', 'premium-addons-pro'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%' ,'em'],
                'condition'     => [
                    'page_info'  => 'yes'
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-fb-rev-page-inner .premium-fb-rev-img' => 'border-radius: {{SIZE}}{{UNIT}};'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'label'         => __('Shadow','premium-addons-pro'),
                'name'          => 'page_image_shadow',
                'selector'      => '{{WRAPPER}} .premium-fb-rev-page-inner .premium-fb-rev-img',
                'condition'     => [
                    'page_info'  => 'yes'
                ],
            ]
        );
        
        $this->add_responsive_control('page_image_margin',
            [
                'label'         => __('Margin', 'premium-addons-pro'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => [ 'px', 'em', '%' ],
                'condition'     => [
                    'page_info'  => 'yes'
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-fb-rev-page-inner .premium-fb-rev-page-left' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ]
            ]
        );
        
        $this->end_controls_tab();
        
        $this->start_controls_tab('img_tab',
            [
                'label'         => __('Review', 'premium-addons-pro'),
            ]
        );
        
        $this->add_responsive_control('image_size',
            [
                'label'         => __('Size', 'premium-addons-pro'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', "em"],
                'range'         => [
                    'px'    => [
                        'min'   => 1, 
                        'max'   => 200,
                    ],
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-fb-rev-review-inner .premium-fb-rev-img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(), 
            [
                'name'          => 'reviewer_image_border',
                'selector'      => '{{WRAPPER}} .premium-fb-rev-review-inner .premium-fb-rev-img',
            ]
        );
        
        $this->add_control('reviewer_image_border_radius',
            [
                'label'         => __('Border Radius', 'premium-addons-pro'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%' ,'em'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-fb-rev-review-inner .premium-fb-rev-img' => 'border-radius: {{SIZE}}{{UNIT}};'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
                [
                    'label'     => __('Shadow','premium-addons-pro'),
                    'name'      => 'image_shadow',
                    'selector'  => '{{WRAPPER}} .premium-fb-rev-review-inner .premium-fb-rev-img',
                ]
            );
        
        $this->add_responsive_control('image_margin',
                [
                    'label'     => __('Margin', 'premium-addons-pro'),
                    'type'      => Controls_Manager::DIMENSIONS,
                    'size_units'=> [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .premium-fb-rev-review-inner .premium-fb-rev-content-left' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                    ]
                ]
            );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        $this->end_controls_section();
        
        $this->start_controls_section('page',
            [
                'label'         => __('Page Info', 'premium-addons-pro'),
                'tab'           => Controls_Manager::TAB_STYLE,
                'condition'     => [
                    'page_info'  => 'yes'
                ]
            ]
        );
        
        $this->start_controls_tabs('page_info_tabs');
        
        $this->start_controls_tab('page_container',
            [
                'label'         => __('Container', 'premium-addons-pro'),
            ]
        );
        
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'          => 'page_container_background',
                'types'         => [ 'classic' , 'gradient' ],
                'selector'      => '{{WRAPPER}} .premium-fb-rev-page',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(), 
            [
                'name'          => 'page_container_border',
                'selector'      => '{{WRAPPER}} .premium-fb-rev-page',
            ]
        );
        
        $this->add_control('page_container_border_radius',
            [
                'label'         => __('Border Radius', 'premium-addons-pro'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%' ,'em'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-fb-rev-page' => 'border-radius: {{SIZE}}{{UNIT}};'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'          => 'page_container_shadow',
                'selector'      => '{{WRAPPER}} .premium-fb-rev-page',
            ]
        );
        
        $this->add_responsive_control('page_container_margin',
                [
                    'label'      => __('Margin', 'premium-addons-pro'),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors'  => [
                        '{{WRAPPER}} .premium-fb-rev-page' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                    ]
                ]
            );
        
        $this->add_responsive_control('page_container_padding',
            [
                'label'         => __('Padding', 'premium-addons-pro'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => [ 'px', 'em', '%' ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-fb-rev-page' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ]
            ]
        );
        
        $this->end_controls_tab();
        
        $this->start_controls_tab('page_link',
            [
                'label'         => __('Name', 'premium-addons-pro'),
            ]
            );
        
        $this->add_control('page_color',
            [
                'label'         => __('Text Color', 'premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-fb-rev-page-link' => 'color: {{VALUE}};'
                ]
            ]
        );
        
        $this->add_control('page_hover_color',
            [
                'label'         => __('Hover Color', 'premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-fb-rev-page-link:hover' => 'color: {{VALUE}};'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'          => 'page_typo',
                'selector'      => '{{WRAPPER}} .premium-fb-rev-page-link',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'          => 'page_shadow',
                'selector'      => '{{WRAPPER}} .premium-fb-rev-page-link',
            ]
        );
        
        $this->add_responsive_control('page_margin',
                [
                    'label'     => __('Margin', 'premium-addons-pro'),
                    'type'      => Controls_Manager::DIMENSIONS,
                    'size_units'=> [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .premium-fb-rev-page-link-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ]
            ]
        );
        
        $this->end_controls_tab();
        
        $this->start_controls_tab('page_rate_link',
            [
                'label'        => __('Rate', 'premium-addons-pro'),
            ]
        );
        
        $this->add_control('page_star_size',
            [
                'label'         => __('Star Size', 'premium-addons-pro'),
                'type'          => Controls_Manager::NUMBER,
                'min'           => 1,
                'max'           => 50,
                'condition'     => [
                    'stars'         =>  'yes'
                ]
            ]
        );
        
        $this->add_control('page_fill',
            [
                'label'         => __('Star Color', 'premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'default'       => '#ffab40',
                'condition'     => [
                    'stars'         =>  'yes'
                ]
            ]
        );
        
        $this->add_control('page_empty',
            [
                'label'         => __('Empty Star Color', 'premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'condition'     => [
                    'stars'         =>  'yes'
                ]
            ]
        );
        
        $this->add_control('page_rate_color',
            [
                'label'        => __('Text Color', 'premium-addons-pro'),
                'type'         => Controls_Manager::COLOR,
                'scheme'       => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'condition'    => [
                    'page_rate'  => 'yes'
                ],
                'selectors'    => [
                    '{{WRAPPER}} .premium-fb-rev-page-rating' => 'color: {{VALUE}};'
                ]
            ]
            );

        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'      => 'page_rate_typo',
                    'selector'  => '{{WRAPPER}} .premium-fb-rev-page-rating',
                    'condition'    => [
                            'page_rate'  => 'yes'
                        ]
                    ]
                );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'          => 'page_rate_shadow',
                'selector'      => '{{WRAPPER}} .premium-fb-rev-page-rating',
                'condition'    => [
                    'page_rate'  => 'yes'
                ]
            ]
        );
        
        $this->add_responsive_control('page_rate_margin',
            [
                'label'     => __('Margin', 'premium-addons-pro'),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units'=> [ 'px', 'em', '%' ],
                'selectors' => [
                '{{WRAPPER}} .premium-fb-rev-page-rating-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
                'condition'    => [
                    'page_rate'  => 'yes'
                ]
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        $this->end_controls_section();
        
        $this->start_controls_section('review_container',
            [
                'label'             => __('Review', 'premium-addons-pro'),
                'tab'               => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control('reviews_star_size',
            [
                'label'         => __('Star Size', 'premium-addons-pro'),
                'type'          => Controls_Manager::NUMBER,
                'min'           => 1,
                'max'           => 50,
            ]
        );
        
        $this->add_control('reviews_fill',
            [
                'label'         => __('Star Color', 'premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'default'       => '#ffab40',
            ]
        );
        
        $this->add_control('reviews_empty',
            [
                'label'         => __('Empty Star Color', 'premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'              => 'review_container_background',
                'types'             => [ 'classic' , 'gradient' ],
                'selector'          => '{{WRAPPER}} .premium-fb-rev-review',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(), 
            [
                'name'          => 'review_container_border',
                'selector'      => '{{WRAPPER}} .premium-fb-rev-review',
            ]
        );
        
        $this->add_control('review_container_border_radius',
            [
                'label'         => __('Border Radius', 'premium-addons-pro'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%' ,'em'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-fb-rev-review' => 'border-radius: {{SIZE}}{{UNIT}};'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'          => 'review_container_box_shadow',
                'selector'      => '{{WRAPPER}} .premium-fb-rev-review',
            ]
        );
        
        $this->add_responsive_control('reviews_gap',
            [
                'label'         => __('Margin', 'premium-addons-pro'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', '%', "em"],
                'condition'     => [
                    'reviews_columns!'   => '100%'
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-fb-rev-review-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );

        $this->add_responsive_control('review_container_padding',
            [
                'label'         => __('Padding', 'premium-addons-pro'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-fb-rev-review' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]      
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('reviewer',
            [
                'label'         => __('Reviewer Name', 'premium-addons-pro'),
                'tab'           => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control('reviewer_color',
            [
                'label'         => __('Color', 'premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-fb-rev-reviewer-link' => 'color: {{VALUE}};'
                ]
            ]
        );
        
        $this->add_control('reviewer_hover_color',
            [
                'label'         => __('Hover Color', 'premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-fb-rev-reviewer-link:hover' => 'color: {{VALUE}};'
                ]
            ]
            );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'          => 'reviewer_typo',
                'selector'      => '{{WRAPPER}} .premium-fb-rev-reviewer-link',
            ]
        );
        
        $this->add_responsive_control('reviewer_margin',
            [
                'label'         => __('Margin', 'premium-addons-pro'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => [ 'px', 'em', '%' ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-fb-rev-reviewer-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ]
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('date_style',
            [
                'label'         => __('Date', 'premium-addons-pro'),
                'tab'           => Controls_Manager::TAB_STYLE,
                'condition'     => [
                    'date'   => 'yes'
                ]
            ]
        );
        
        $this->add_control('date_color',
            [
                'label'         => __('Color', 'premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-fb-rev-time .premium-fb-rev-time-text' => 'color: {{VALUE}};'
                ]
            ]
        );
        
        $this->add_control('reviewer_date_color_hover',
            [
                'label'         => __('Hover Color', 'premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-fb-rev-time .premium-fb-rev-time-text:hover' => 'color: {{VALUE}};'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'          => 'date_typo',
                'selector'      => '{{WRAPPER}} .premium-fb-rev-time .premium-fb-rev-time-text',
            ]
        );
        
        $this->add_responsive_control('date_margin',
            [
                'label'         => __('Margin', 'premium-addons-pro'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => [ 'px', 'em', '%' ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-fb-rev-time' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ]
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('reviewer_txt',
            [
                'label'         => __('Review Text', 'premium-addons-pro'),
                'tab'           => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control('reviewer_txt_color',
            [
                'label'         => __('Color', 'premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-fb-rev-text' => 'color: {{VALUE}};'
                ]
            ]
        );
        
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'      => 'reviewer_txt_typo',
                    'selector'  => '{{WRAPPER}} .premium-fb-rev-text',
                    ]
                );

        $this->add_responsive_control('reviewer_txt_margin',
            [
                'label'         => __('Margin', 'premium-addons-pro'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => [ 'px', 'em', '%' ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-fb-rev-text-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ]
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('readmore_style',
            [
                'label'         => __('Readmore Text', 'premium-addons-pro'),
                'tab'           => Controls_Manager::TAB_STYLE,
                'condition'     => [
                    'words_num!'   => ''
                ]
            ]
        );
        
        $this->add_control('readmore_color',
            [
                'label'         => __('Color', 'premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-fb-rev-readmore' => 'color: {{VALUE}};'
                ]
            ]
        );
        
        $this->add_control('readmore_hover_color',
            [
                'label'         => __('Hover Color', 'premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-fb-rev-readmore:hover' => 'color: {{VALUE}};'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'          => 'readmore_typo',
                'selector'      => '{{WRAPPER}} .premium-fb-rev-readmore',
            ]
        );
        
        $this->add_responsive_control('readmore_margin',
            [
                'label'         => __('Margin', 'premium-addons-pro'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => [ 'px', 'em', '%' ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-fb-rev-readmore' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ]
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('container',
            [
                'label'         => __('Container', 'premium-addons-pro'),
                'tab'           => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control('container_width',
            [
                'label'         => __('Max Width', 'premium-addons-pro'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%' ,'em'],
                'range'         => [
                    'px'    => [
                        'min'   => 1,
                        'max'   => 300,
                    ]
                ],
                'selectors'     => [
                    '{{WRAPPER}} .elementor-widget-container' => 'max-width: {{SIZE}}{{UNIT}};'
                ]
            ]
        );
        
        $this->add_responsive_control('container_align',
            [
                'label'         => __( 'Alignment', 'premium-addons-pro' ),
                'type'          => Controls_Manager::CHOOSE,
                'options'       => [
                    'flex-start'      => [
                        'title'=> __( 'Left', 'premium-addons-pro' ),
                        'icon' => 'fa fa-align-left',
                        ],
                    'center'    => [
                        'title'=> __( 'Center', 'premium-addons-pro' ),
                        'icon' => 'fa fa-align-center',
                        ],
                    'flex-end'     => [
                        'title'=> __( 'Right', 'premium-addons-pro' ),
                        'icon' => 'fa fa-align-right',
                        ],
                    ],
                'default'       => 'center',
                'selectors'     => [
                    '{{WRAPPER}}' => 'justify-content: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'          => 'container_background',
                'types'         => [ 'classic' , 'gradient' ],
                'selector'      => '{{WRAPPER}} .premium-fb-rev-container',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(), 
            [
                'name'          => 'container_border',
                'selector'      => '{{WRAPPER}} .premium-fb-rev-container',
            ]
        );
        
        $this->add_control('container_border_radius',
            [
                'label'         => __('Border Radius', 'premium-addons-pro'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%' ,'em'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-fb-rev-container' => 'border-radius: {{SIZE}}{{UNIT}};'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'          => 'container_box_shadow',
                'selector'      => '{{WRAPPER}} .premium-fb-rev-container',
            ]
        );
        
        $this->add_responsive_control('container_margin',
                [
                    'label'      => __('Margin', 'premium-addons-pro'),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors'  => [
                        '{{WRAPPER}} .premium-fb-rev-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]      
        );
        
        $this->add_responsive_control('container_padding',
            [
                'label'         => __('Padding', 'premium-addons-pro'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-fb-rev-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]      
        );

        $this->end_controls_section();
        
        $this->start_controls_section('carousel_style',
            [
                'label'         => __('Carousel', 'premium-addons-pro'),
                'tab'           => Controls_Manager::TAB_STYLE,
                'condition'     => [
                    'reviews_carousel'  => 'yes'
                ]
            ]
        );
        
        $this->add_control('arrow_color',
            [
                'label'         => __('Color', 'premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-fb-rev-container .slick-arrow' => 'color: {{VALUE}};',
                ]
            ]
        );

        $this->add_responsive_control('arrow_size',
            [
                'label'         => __('Size', 'premium-addons-pro'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%' ,'em'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-fb-rev-container .slick-arrow i' => 'font-size: {{SIZE}}{{UNIT}};'
                ]
            ]
        );
        
        $this->add_control('arrow_background',
            [
                'label'         => __('Background Color', 'premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-fb-rev-container .slick-arrow' => 'background-color: {{VALUE}};',
                ]
            ]
        );
        
        $this->add_control('arrow_border_radius',
            [
                'label'         => __('Border Radius', 'premium-addons-pro'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%' ,'em'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-fb-rev-container .slick-arrow' => 'border-radius: {{SIZE}}{{UNIT}};'
                ]
            ]
        );

        $this->add_control('arrow_padding',
            [
                'label'         => __('Padding', 'premium-addons-pro'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%' ,'em'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-fb-rev-container .slick-arrow' => 'padding: {{SIZE}}{{UNIT}};'
                ]
            ]
        );
        
        $this->end_controls_section();
    }
    
    /**
	 * Render Facebook Reviews widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
    protected function render() {
        
        $settings = $this->get_settings_for_display();

        $id         = $this->get_id();
        
        $page_name = $settings['page_name'];
        
        $page_id = $settings['page_id'];
        
        $page_access = $settings['page_access'];
        
        $transient  = $settings['reload'];
        
        if( 'Invalid License Key' === $page_access ) { ?>
            <div class="premium-error-notice">
                <?php echo __('Please activate your license to get the access token','premium-addons-pro'); ?>
            </div>
        <?php 
            return;
        } elseif ( empty( $page_id ) || empty( $page_access ) ) {
            
            ?>
            <div class="premium-error-notice">
                <?php echo __('Please fill the required fields: Page ID & Page Access Token','premium-addons-pro'); ?>
            </div>
            <?php
            return;
        }
        
        $transient_name = sprintf( 'papro_reviews_%s_%s', $page_id, $id );

        do_action( 'papro_reviews_transient', $transient_name, $settings );

        $response = get_transient( $transient_name );
        
        if ( false === $response ) {
            
            sleep( 2 );
            $response = premium_fb_rev_api_rating( $page_id, $page_access );
            
            if ( is_wp_error( $response ) ) {
				$error_message = $response->get_error_message();
            ?>
				<div class="premium-error-notice">
                    <?php echo sprintf( __('Something went wrong: %s','premium-addons-pro'), $error_message ); ?>
                </div>
            <?php
                return;
			}
            
            $response_data      = $response[ 'data' ];

            $response_json      = rplg_json_decode( $response_data );
            
            $response           = $response_json;
            
            $expire_time = Helper_Functions::transient_expire( $transient );
            
			set_transient( $transient_name, $response, $expire_time );
        }
        
        if ( ! property_exists( $response, 'data' ) ) { ?>
            <div class="premium-error-notice">
                <?php echo __('Something went wrong. It seems like the page you selected does not have any reviews','premium-addons-pro'); ?>
            </div>
            <?php delete_transient( $transient_name );
            return false;
        }
        
        $reviews = $response->data;
        
        $rating = 0;
        if( count( $reviews ) > 0 ) {
            foreach ( $reviews as $review ) {
                $review_rating = isset( $review->rating ) ? $review->rating : 5;
                $rating = $rating + $review_rating;
            }
            $rating = round($rating / count($reviews), 1);
            $rating = number_format((float)$rating, 1, '.', '');
        }
        
        if( 'yes' == $settings['page_info'] && 'yes' == $settings['page_custom_image_switch'] ) {
            
            $image_src = $settings['page_custom_image'];
            
            $image_src_size = Group_Control_Image_Size::get_attachment_image_src( $image_src['id'], 'thumbnail', $settings );
            
            if( empty( $image_src_size ) ) : $image_src_size = $image_src['url']; else: $image_src_size = $image_src_size; endif;
            
            $custom_image = ! empty( $image_src_size ) ? $image_src_size : '';
            
            
        } else {
            
            $custom_image = '';
        }
        
        $show_stars = 'yes' == $settings['stars'] ? true : false;
    
        $show_date = 'yes' == $settings['date'] ? true : false;
        
        $date_format = $settings['date_format'];
        
        $this->add_render_attribute( 'page', 'class', 'premium-fb-rev-page' );
        
        $this->add_render_attribute( 'reviews', 'class', 'premium-fb-rev-content' );
        
        $page_rate = false;
        
        if( 'yes' == $settings['page_info'] && 'yes' == $settings['page_rate'] ) {
            $page_rate = true;
        }
        
        $rev_text = 'yes' == $settings['text'] ? true : false;
        
        $rev_length = $settings['words_num'];
        
        $page_star_size    = ! empty( $settings['page_star_size'] ) ? $settings['page_star_size'] : 16;
        $page_fill_color   = ! empty( $settings['page_fill'] ) ? $settings['page_fill'] : '#ffab40';
        $page_empty_color  = ! empty( $settings['page_empty'] ) ? $settings['page_empty'] : '#ccc';
        
        $rev_star_size      = ! empty( $settings['reviews_star_size'] ) ? $settings['reviews_star_size'] : 16;
        $rev_fill_color     = ! empty( $settings['reviews_fill'] ) ? $settings['reviews_fill'] : '#ffab40';
        $rev_empty_color    = ! empty( $settings['reviews_empty'] ) ? $settings['reviews_empty'] : '#ccc';
        
        if( 'yes' == $settings['limit'] ){
            if  ( '0' == $settings['limit_num'] ) {
                $limit = 0;
            } else {
                $limit = ! empty( $settings['limit_num'] ) ? $settings['limit_num'] : 9999;    
            }
            
        } else {
            $limit = 9999;
        }
        
        if( 'yes' == $settings['filter'] ) {
            $min_filter = ! empty( $settings['filter_min'] ) ? $settings['filter_min'] : 1;
            $max_filter = ! empty( $settings['filter_max'] ) ? $settings['filter_max'] : 5;
        } else {
            $min_filter = 1;
            $max_filter = 5;
        }
        
        $carousel = 'yes' == $settings['reviews_carousel'] ? true : false; 
        
        $reviews_number = intval ( 100 / substr( $settings['reviews_columns'], 0, strpos( $settings['reviews_columns'], '%') ) );
        
        $page_settings = array(
            'name'          => $page_name,
            'rating'        => $rating,
            'fill_color'    => $page_fill_color,
            'empty_color'   => $page_empty_color,
            'stars'         => $show_stars,
            'size'          => $page_star_size,
            'rate'          => $page_rate,
            'image'         => $custom_image
        );
        
        $reviews_settings = array(
            'id'            => $page_id,
            'fill_color'    => $rev_fill_color,
            'empty_color'   => $rev_empty_color,
            'stars'         => $show_stars,
            'stars_size'    => $rev_star_size,
            'filter_min'    => $min_filter,
            'filter_max'    => $max_filter,
            'date'          => $show_date,
            'format'        => $date_format,
            'limit'         => $limit,
            'text'          => $rev_text,
            'rev_length'    => $rev_length,
            'readmore'      => $settings['readmore'],
            'skin_type'     => $settings['skin_type'] 
        );
        
        $this->add_render_attribute('container', 'class', array (
                'premium-fb-rev-container', 
                'facebook-reviews',
                'premium-reviews-' . $settings['reviews_style']
            )
        );
        
        $this->add_render_attribute('container', 'data-col', $reviews_number );
        
        $this->add_render_attribute('container', 'data-style', $settings['reviews_style'] );
        
        if( $carousel ) {
            $this->add_render_attribute('container', 'data-carousel', $carousel );
            
            $play = 'yes' == $settings['carousel_play'] ? true : false;
            $speed = ! empty( $settings['carousel_autoplay_speed'] ) ? $settings['carousel_autoplay_speed'] : 5000;
            $rtl = 'yes' == $settings['carousel_rtl'] ? true : false;
            
            $this->add_render_attribute('container', 'data-play', $play );
            
            $this->add_render_attribute('container', 'data-speed', $speed );
            
            $this->add_render_attribute('container', 'data-rtl', $rtl );
            
        }
        
        if ( 'yes' === $settings['schema'] ) {
            
            $ratings = count( $reviews );
            
			$this->add_render_attribute(
				'container',
				[
					'itemscope' => '',
					'itemtype'  => 'http://schema.org/' . $settings['schema_type'],
				]
			);
		}
        
        ?>

        <div <?php echo $this->get_render_attribute_string( 'container' ); ?>>
            <div class="premium-fb-rev-list">
                <?php if( 'yes' == $settings['page_info'] ) : ?>
                <div <?php echo $this->get_render_attribute_string( 'page' ); ?>>
                    <div class="premium-fb-rev-page-inner">
                        <?php premium_fb_rev_page( $page_id, $page_settings ); ?>
                    </div>
                </div>
                <?php endif; ?>
                <div <?php echo $this->get_render_attribute_string( 'reviews' ); ?>>
                    <?php premium_fb_rev_reviews( $reviews, $reviews_settings ); ?>
                </div>
            </div>
            <?php if( 'yes' === $settings['schema'] ) { ?>
            
                <div class="elementor-screen-only" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
					<span itemprop="ratingValue" class="elementor-screen-only"><?php echo $rating; ?></span><span itemprop="reviewCount" class="elementor-screen-only"><?php echo $ratings; ?></span>
				</div>
            
            <?php } ?>
        </div>

        <?php if ( \Elementor\Plugin::instance()->editor->is_edit_mode() ) {

            if ( 'masonry' === $settings['reviews_style'] && 'yes' !== $settings['reviews_carousel'] ) {
                $this->render_editor_script();
            }
        }
    
    }

    /**
	 * Render Editor Masonry Script.
	 *
	 * @since 1.9.4
	 * @access protected
	 */
	protected function render_editor_script() {

		?><script type="text/javascript">
			jQuery( document ).ready( function( $ ) {

				$( '.premium-fb-rev-reviews' ).each( function() {

                    var $node_id 	= '<?php echo $this->get_id(); ?>',
                        scope 		= $( '[data-id="' + $node_id + '"]' ),
                        selector 	= $(this);
                    
					if ( selector.closest( scope ).length < 1 ) {
						return;
					}
					
                    var masonryArgs = {
                        itemSelector	: '.premium-fb-rev-review-wrap',
                        percentPosition : true,
                        layoutMode		: 'masonry',
                    };

                    var $isotopeObj = {};

                    selector.imagesLoaded( function() {

                        $isotopeObj = selector.isotope( masonryArgs );

                        selector.find('.premium-fb-rev-review-wrap').resize( function() {
                            $isotopeObj.isotope( 'layout' );
                        });
                    });

				});
			});
		</script>
		<?php
    }
}