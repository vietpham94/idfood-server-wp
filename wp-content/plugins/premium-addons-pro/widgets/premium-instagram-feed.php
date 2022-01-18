<?php

/**
 * Class: Premium_Instagram_Feed
 * Name: Instagram Feed
 * Slug: premium-addon-instagram-feed
 */
namespace PremiumAddonsPro\Widgets;

// Elementor Classes.
use Elementor\Widget_Base;
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

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

/**
 * Class Premium_Instagram_Feed
 */
class Premium_Instagram_Feed extends Widget_Base {

    public function get_name() {
        return 'premium-addon-instagram-feed';
    }

    public function get_title() {
		return sprintf( '%1$s %2$s', Helper_Functions::get_prefix(), __('Instagram Feed', 'premium-addons-pro') );
	}
    
    public function is_reload_preview_required() {
        return true;
    }
    
    public function get_style_depends() {
        return [
            'font-awesome-5-all',
            'premium-addons',
            'pa-prettyphoto'
        ];
    }
    
    public function get_script_depends() {
        return [
            'imagesloaded',
            'prettyPhoto-js',
            'isotope-js',
            'instafeed-js',
            'jquery-slick',
            'premium-pro-js',
        ];
    }

    public function get_icon() {
        return 'pa-pro-instagram-feed';
    }

    public function get_categories() {
        return [ 'premium-elements' ];
    }

    public function get_custom_help_url() {
		return 'https://premiumaddons.com/support';
	}
    
    /**
	 * Register Instagram Feed controls.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
    protected function _register_controls() {

        $this->start_controls_section('premium_instagram_feed_general_settings_section',
            [
                'label'         => __('Access Credentials', 'premium-addons-pro')
            ]
        );

        $this->add_control('deprecate_notice', 
            [
                'raw'               => __('<b>Important:</b> Instagram API has been deprecated, so you will need to migrate to the new API handled by Facebook through the login button below. For further information, please check this <a href="https://www.instagram.com/developer/" target="_blank">page</a>', 'premium-addons-pro'),
                'type'              => Controls_Manager::RAW_HTML,
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
            ] 
        );

        $this->add_control('api_version',
            [
                'label'             => __('API Version:', 'premium-addons-pro'),
                'type'              => Controls_Manager::HIDDEN,
                'options'           => [
                    // 'old'   => __('Instagram API', 'premium-addons-pro'),
                    'new'   => __('Facebook New Instagram API', 'premium-addons-pro'),
                ],
                'label_block'       => true,
                'default'           => 'new',
            ]
        );

        $this->add_control('instagram_login',
            [
                'type'  => Controls_Manager::RAW_HTML,
                'raw'   => '<form onsubmit="connectInstagramInit(this);" action="javascript:void(0);" data-type="reviews"><input type="submit" value="Log in with Facebook" class="elementor-button" style="background-color: #3b5998; color: #fff;"></form>',
                'label_block' => true,
                'condition' => [
                    'api_version'   => 'new'
                ]
            ]
        );

        $this->add_control('premium_instagram_feed_client_access_token',
                [
                    'label'         => __('Access Token', 'premium-addons-pro'),
                    'type'          => Controls_Manager::TEXT,
                    'dynamic'       => [ 'active' => true ],
                    'default'       => '2075884021.1677ed0.2fd28d5d3abf45d4a80534bee8376f4c',
                    'label_block'   => false,
                    'description'   => 'Get your access token from <a href="http://www.jetseotools.com/instagram-access-token/" target="_blank">here</a>',
                    'condition' => [
                        'api_version'   => 'old'
                    ]
                ]
            );

            $this->add_control('new_accesstoken',
                [
                    'label'         => __('Access Token', 'premium-addons-pro'),
                    'type'          => Controls_Manager::TEXTAREA,
                    'dynamic'       => [ 'active' => true ],
                    'label_block'   => true,
                    'condition' => [
                        'api_version'   => 'new'
                    ]
                ]
            );

        $this->add_control('new_api_notice', 
            [
                'raw'               => __('The amount of information given about Instagram media is greatly reduced in the new API', 'premium-addons-pro'),
                'type'              => Controls_Manager::RAW_HTML,
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                'condition' => [
                    'api_version'   => 'new'
                ]
            ] 
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_instagram_feed_query_section',
            [
                'label'             => __('Queries', 'premium-addons-pro')
            ]
        );
        
        $this->add_control('premium_instagram_feed_tag_name',
            [
                'label'         => __('Filter by Hashtags', 'premium-addons-pro'),
                'type'          => Controls_Manager::TEXT,
                'description'   => __('You can separate tags by a comma, for example: sport,football,tennis', 'premium-addons-pro'),
                'dynamic'       => [ 'active' => true ],
                'label_block'   => true,
            ]
        );
        
        $this->add_control('premium_instagram_feed_sort_by',
            [
                'label'             => __('Sort By:', 'premium-addons-pro'),
                'type'              => Controls_Manager::SELECT,
                'options'           => [
                    'none'              => __('none', 'premium-addons-pro'),
                    'most-recent'       => __('Most Recent', 'premium-addons-pro'),
                    'least-recent'      => __('Least Recent', 'premium-addons-pro'),
                    'most-liked'        => __('Most Liked', 'premium-addons-pro'),
                    'least-liked'       => __('Least Liked', 'premium-addons-pro'),
                    'most-commented'    => __('Most Commented', 'premium-addons-pro'),
                    'least-commented'   => __('Least Commented', 'premium-addons-pro'),
                    'random'            => __('Random', 'premium-addons-pro'),
                ],
                'default'           => 'none',
                'condition' => [
                    'api_version'   => 'old'
                ]
            ]
        );
        
        $this->add_control('premium_instagram_feed_link',
			[
				'label'                 => __( 'Enable Redirection', 'premium-addons-pro' ),
				'type'                  => Controls_Manager::SWITCHER,
                'description'           => __('Redirect to Photo Link on Instgram','premium-addons-pro'),
			]
		);

		$this->add_control('premium_instagram_feed_new_tab',
			[
				'label'                 => __( 'Open in a New Tab', 'premium-addons-pro' ),
				'type'                  => Controls_Manager::SWITCHER,
                'condition'             => [
                    'premium_instagram_feed_link'   => 'yes'
                ]
			]
		);
        
        $this->add_control('premium_instagram_feed_popup',
			[
				'label'                 => __( 'Lightbox', 'premium-addons-pro' ),
				'type'                  => Controls_Manager::SWITCHER,
                'description'           => __('Modal image works only on the frontend', 'premium-addons-pro'),
                'condition'             => [
                    'premium_instagram_feed_link!'   => 'yes'
                ]
			]
		);
        
        $this->add_control('premium_instagram_feed_popup_theme',
            [
                'label'             => __('Lightbox Theme', 'premium-addons-pro'),
                'type'              => Controls_Manager::SELECT,
                'options'           => [
                    'pp_default'        => __('Default', 'premium-addons-pro'),
                    'light_rounded'     => __('Light Rounded', 'premium-addons-pro'),
                    'dark_rounded'      => __('Dark Rounded', 'premium-addons-pro'),
                    'light_square'      => __('Light Square', 'premium-addons-pro'),
                    'dark_square'       => __('Dark Square', 'premium-addons-pro'),
                    'facebook'          => __('Facebook', 'premium-addons-pro'),
                ],
                'default'           => 'pp_default',
                'condition'             => [
                    'premium_instagram_feed_link!'  => 'yes',
                    'premium_instagram_feed_popup'  => 'yes'
                ]
            ]
        );
        
        $this->add_control('premium_instagram_feed_show_likes',
			[
				'label'                 => __( 'Show Likes', 'premium-addons-pro' ),
                'type'                  => Controls_Manager::SWITCHER,
                'condition'             => [
                    'api_version'   => 'old'
                ]
			]
		);
        
        $this->add_control('premium_instagram_feed_show_comments',
			[
				'label'                 => __( 'Show Comments', 'premium-addons-pro' ),
                'type'                  => Controls_Manager::SWITCHER,
                'condition'             => [
                    'api_version'   => 'old'
                ]
			]
		);
        
        $this->add_control('premium_instagram_feed_show_caption',
			[
				'label'                 => __( 'Show Caption', 'premium-addons-pro' ),
				'type'                  => Controls_Manager::SWITCHER,
			]
        );

        $this->add_control('premium_instagram_feed_caption_number',
            [
                'label'             => __('Maximum Words Number', 'premium-addons-pro'),
                'type'              => Controls_Manager::NUMBER,
                'min'           => 1,
                'condition'             => [
                    'premium_instagram_feed_show_caption'   => 'yes'
                ],
            ]
        );
        
        $this->add_control('show_videos',
			[
				'label'                 => __( 'Show Videos On Click', 'premium-addons-pro' ),
                'type'                  => Controls_Manager::SWITCHER,
                'return_value'          => 'true',
                'condition'             => [
                    'premium_instagram_feed_link!'   => 'yes',
                    'premium_instagram_feed_popup!' => 'yes',
                    'api_version'                   => 'new'
                ]
			]
		);
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_instagram_feed_layout_settings_section',
            [
                'label'             => __('Layout', 'premium-addons-pro'),
            ]
        );
        
        $this->add_control('premium_instagram_feed_img_number',
            [
                'label'             => __('Maximum Images Number', 'premium-addons-pro'),
                'type'              => Controls_Manager::NUMBER,
                'default'           => 6,
            ]
        );
        
        $this->add_control('premium_instagram_feed_masonry',
			[
				'label'                 => __( 'Masonry', 'premium-addons-pro' ),
				'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'yes'
			]
        );
        
        $this->add_responsive_control('premium_instgram_feed_image_height',
            [
                'label'         => __('Image Height', 'premium-addons-pro'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px',"em"],
                'range'             => [
                    'px'    => [
                        'min' => 50, 
                        'max' => 500,
                        ],
                    'em'    => [
                        'min' => 1, 
                        'max' => 100,
                    ]
                ],
                'default'       => [
                    'size'  => 300,
                    'unit'  => 'px'
                ],
                'condition'     => [
                    'premium_instagram_feed_masonry!'    => 'yes'
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-insta-img-wrap img' => 'height: {{SIZE}}{{UNIT}};'
                ]
            ]
        );
        
        $this->add_responsive_control('premium_instagram_feed_column_number',
            [
                'label'             => __('Images per Row', 'premium-addons-pro'),
                'type'              => Controls_Manager::SELECT,
                'options'           => [
                    '100%'  => __('1 Column', 'premium-addons-pro'),
                    '50%'   => __('2 Columns', 'premium-addons-pro'),
                    '33.33%'=> __('3 Columns', 'premium-addons-pro'),
                    '25%'   => __('4 Columns', 'premium-addons-pro'),
                    '20%'   => __('5 Columns', 'premium-addons-pro'),
                    '16.667%'=> __('6 Columns', 'premium-addons-pro'),
                ],
                'desktop_default'   => '33.33%',
				'tablet_default'    => '50%',
				'mobile_default'    => '100%',
                'render_type'       => 'template',
                'selectors'         => [
                    '{{WRAPPER}} .premium-insta-feed' => 'width: {{VALUE}}'
                ],
            ]
        );
        
        $this->add_control('premium_instagram_feed_image_hover',
            [
                'label'         => __('Hover Image Effect', 'premium-addons-pro'),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'none'   => __('None', 'premium-addons-pro'),
                    'zoomin' => __('Zoom In', 'premium-addons-pro'),
                    'zoomout'=> __('Zoom Out', 'premium-addons-pro'),
                    'scale'  => __('Scale', 'premium-addons-pro'),
                    'gray'   => __('Grayscale', 'premium-addons-pro'),
                    'blur'   => __('Blur', 'premium-addons-pro'),
                    'sepia'  => __('Sepia', 'premium-addons-pro'),
                    'bright' => __('Brightness', 'premium-addons-pro'),
                    'trans'  => __('Translate', 'premium-addons-pro'),
                ],
                'default'       => 'zoomin',
                'label_block'   => true
            ]
        );
        
        $this->end_controls_section();

        $this->start_controls_section('carousel',
            [
                'label'         => __('Carousel','premium-addons-pro'),
            ]
        );
        
        $this->add_control('feed_carousel',
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
                    'feed_carousel'  => 'yes'
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
                    'feed_carousel' => 'yes',
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
                    'feed_carousel' => 'yes'
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-instafeed-container a.carousel-arrow.carousel-next' => 'right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .premium-instafeed-container a.carousel-arrow.carousel-prev' => 'left: {{SIZE}}{{UNIT}};',
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
            'https://premiumaddons.com/docs/instagram-feed-widget-tutorial' => 'Getting started »',
            'https://premiumaddons.com/docs/how-to-migrate-instagram-feed-widget-from-the-old-api-to-the-new-api' => 'How to migrate Instagram Feed widget to the New API »',
            'https://premiumaddons.com/docs/how-to-filter-images-by-hashtags-in-instagram-feed-widget' => 'How to Filter Images By Hashtags »'
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
        
        $this->start_controls_section('premium_instgram_feed_photo_box_style',
            [
                'label'             => __('Photo Box','premium-addons-pro'),
                'tab'               => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->start_controls_tabs( 'premium_instgram_feed_tweet_box' );
        
        $this->start_controls_tab('premium_instgram_feed_photo_box_normal',
            [
                'label'             => __('Normal', 'premium-addons-pro'),
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(), 
            [
                'name'          => 'premium_instgram_feed_photo_box_border',
                'selector'      => '{{WRAPPER}} .premium-insta-img-wrap',
            ]
        );
        
        $this->add_control('premium_instgram_feed_photo_box_border_radius',
            [
                'label'         => __('Border Radius', 'premium-addons-pro'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%' ,'em'],
                'default'       => [
                    'unit'  => 'px',
                    'size'  => 0,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-insta-img-wrap' => 'border-radius: {{SIZE}}{{UNIT}};'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'label'         => __('Shadow','premium-addons-pro'),
                'name'          => 'premium_instgram_feed_photo_box_shadow',
                'selector'      => '{{WRAPPER}} .premium-insta-img-wrap',
            ]
        );
        
        $this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'css_filters',
				'selector' => '{{WRAPPER}} .premium-insta-img-wrap img',
			]
		);
        
        $this->add_responsive_control('premium_instgram_feed_photo_box_margin',
            [
                'label'         => __('Margin', 'premium-addons-pro'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-insta-img-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );
        
        $this->add_responsive_control('premium_instgram_feed_photo_box_padding',
                [
                    'label'         => __('Padding', 'premium-addons-pro'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => ['px', 'em', '%'],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-insta-img-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ]
                ]);
        
        $this->end_controls_tab();

        $this->start_controls_tab('premium_instgram_feed_photo_box_hover',
            [
                'label'             => __('Hover', 'premium-addons-pro'),
            ]
            );
        
        $this->add_control('premium_instgram_feed_overlay_background', 
            [
                'label'         => __('Overlay Background', 'premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .premium-insta-info-wrap' => 'background-color: {{VALUE}};',
                ],
            ]
        );
                
        $this->add_group_control(
            Group_Control_Border::get_type(), 
            [
                'name'          => 'premium_instgram_feed_photo_box_border_hover',
                'selector'      => '{{WRAPPER}} .premium-insta-feed-wrap:hover .premium-insta-img-wrap',
            ]
        );
        
        $this->add_control('premium_instgram_feed_photo_box_border_radius_hover',
            [
                'label'         => __('Border Radius', 'premium-addons-pro'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%' ,'em'],
                'default'       => [
                    'unit'  => 'px',
                    'size'  => 0,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-insta-feed-wrap:hover .premium-insta-img-wrap' => 'border-radius: {{SIZE}}{{UNIT}};'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'label'         => __('Shadow','premium-addons-pro'),
                'name'          => 'premium_instgram_feed_photo_box_shadow_hover',
                'selector'      => '{{WRAPPER}} .premium-insta-feed-wrap:hover .premium-insta-img-wrap',
            ]
        );
        
        $this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'css_filters_hover',
				'selector' => '{{WRAPPER}} .premium-insta-feed-wrap:hover .premium-insta-img-wrap img',
			]
		);
        
        $this->add_responsive_control('premium_instgram_feed_photo_box_margin_hover',
            [
                'label'         => __('Margin', 'premium-addons-pro'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-insta-feed-wrap:hover .premium-insta-img-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();
            
        /*End Tweet Box Section*/
        $this->end_controls_section();

        $this->start_controls_section('premium_instgram_feed_photo_likes_style',
            [
                'label'             => __('Likes','premium-addons-pro'),
                'tab'               => Controls_Manager::TAB_STYLE,
                'condition'         => [
                    'premium_instagram_feed_show_likes' => 'yes',
                    'api_version'                       => 'old'
                ]
            ]
        );
        
        $this->start_controls_tabs( 'premium_instgram_feed_likes' );
        
        $this->start_controls_tab('premium_instgram_feed_likes_icon',
            [
                'label'             => __('Icon', 'premium-addons-pro'),
            ]
            );

        /*Likes Icon Color*/
        $this->add_control('premium_instgram_feed_likes_color', 
                [
                    'label'         => __('Icon Color', 'premium-addons-pro'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme' => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                        ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-insta-heart' => 'color: {{VALUE}};',
                        ],
                    ]
                );

        /*Likes Icon Size*/
        $this->add_responsive_control('premium_instgram_feed_likes_size',
                [
                    'label'         => __('Size', 'premium-addons-pro'),
                    'type'          => Controls_Manager::SLIDER,
                    'size_units'    => ['px',"em"],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-insta-heart' => 'font-size: {{SIZE}}{{UNIT}};'
                        ]
                    ]
                );
        
        $this->add_control('premium_instgram_feed_likes_background', 
                [
                    'label'         => __('Background Color', 'premium-addons-pro'),
                    'type'          => Controls_Manager::COLOR,
                    'selectors'     => [
                        '{{WRAPPER}} .premium-insta-heart' => 'background-color: {{VALUE}};',
                        ],
                    ]
                );
        
        $this->add_group_control(
            Group_Control_Border::get_type(), 
                [
                    'name'          => 'premium_instgram_feed_likes_border',
                    'selector'      => '{{WRAPPER}} .premium-insta-heart',
                ]
                );
        
        /*Container Border Radius*/
        $this->add_control('premium_instgram_feed_likes_border_radius',
                [
                    'label'         => __('Border Radius', 'premium-addons-pro'),
                    'type'          => Controls_Manager::SLIDER,
                    'size_units'    => ['px', '%' ,'em'],
                    'default'       => [
                        'unit'  => 'px',
                        'size'  => 0,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-insta-heart' => 'border-radius: {{SIZE}}{{UNIT}};'
                    ]
                ]
                );
        
        /*Container Box Shadow*/
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
                [
                    'label'         => __('Shadow','premium-addons-pro'),
                    'name'          => 'premium_instgram_feed_likes_shadow',
                    'selector'      => '{{WRAPPER}} .premium-insta-heart',
                ]
                );
        
        $this->add_responsive_control('premium_instgram_feed_likes_margin',
                [
                    'label'         => __('Margin', 'premium-addons-pro'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => ['px', 'em', '%'],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-insta-heart' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ]
                ]);
                
        /*Container Padding*/
        $this->add_responsive_control('premium_instgram_feed_likes_padding',
                [
                    'label'         => __('Padding', 'premium-addons-pro'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => ['px', 'em', '%'],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-insta-heart' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ]
                ]);
        
        $this->end_controls_tab();
        
        $this->start_controls_tab('premium_instgram_feed_likes_number',
            [
                'label'             => __('Number', 'premium-addons-pro'),
            ]
            );
        
        /*Likes Number Color*/
        $this->add_control('premium_instgram_feed_likes_number_color', 
                [
                    'label'         => __('Color', 'premium-addons-pro'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme' => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_2,
                        ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-insta-likes' => 'color: {{VALUE}};',
                        ],
                    ]
                );
        
        /*Likes Number Typography*/
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'          => 'premium_instgram_feed_likes_number_type',
                    'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                    'selector'      => '{{WRAPPER}} .premium-insta-likes',
                    ]
                );
        
        $this->add_control('premium_instgram_feed_likes_number_background', 
                [
                    'label'         => __('Background Color', 'premium-addons-pro'),
                    'type'          => Controls_Manager::COLOR,
                    'selectors'     => [
                        '{{WRAPPER}}  .premium-insta-likes' => 'background-color: {{VALUE}};',
                        ],
                    ]
                );
        
        $this->add_group_control(
            Group_Control_Border::get_type(), 
                [
                    'name'          => 'premium_instgram_feed_likes_number_border',
                    'selector'      => '{{WRAPPER}} .premium-insta-likes',
                ]
                );
        
        /*Container Border Radius*/
        $this->add_control('premium_instgram_feed_likes_number_border_radius',
                [
                    'label'         => __('Border Radius', 'premium-addons-pro'),
                    'type'          => Controls_Manager::SLIDER,
                    'size_units'    => ['px', '%' ,'em'],
                    'default'       => [
                        'unit'  => 'px',
                        'size'  => 0,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-insta-likes' => 'border-radius: {{SIZE}}{{UNIT}};'
                    ]
                ]
                );
        
        /*Container Box Shadow*/
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
                [
                    'label'         => __('Shadow','premium-addons-pro'),
                    'name'          => 'premium_instgram_feed_likes_number_shadow',
                    'selector'      => '{{WRAPPER}} .premium-insta-likes',
                ]
                );
        
        $this->add_responsive_control('premium_instgram_feed_likes_number_margin',
                [
                    'label'         => __('Margin', 'premium-addons-pro'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => ['px', 'em', '%'],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-insta-likes' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ]
                ]);
                
        /*Container Padding*/
        $this->add_responsive_control('premium_instgram_feed_likes_number_padding',
                [
                    'label'         => __('Padding', 'premium-addons-pro'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => ['px', 'em', '%'],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-insta-likes' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ]
                ]);
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_instgram_feed_photo_comments_style',
            [
                'label'             => __('Comments','premium-addons-pro'),
                'tab'               => Controls_Manager::TAB_STYLE,
                'condition'         => [
                    'premium_instagram_feed_show_comments' => 'yes',
                    'api_version'                       => 'old'
                ]
            ]
        );
        
        $this->start_controls_tabs( 'premium_instgram_feed_comments' );
        
        $this->start_controls_tab('premium_instgram_feed_comments_icon',
            [
                'label'             => __('Icon', 'premium-addons-pro'),
            ]
            );

        /*Likes Icon Color*/
        $this->add_control('premium_instgram_feed_comment_color', 
                [
                    'label'         => __('Color', 'premium-addons-pro'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme' => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                        ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-insta-comment' => 'color: {{VALUE}};',
                        ],
                    ]
                );

        /*Likes Icon Size*/
        $this->add_responsive_control('premium_instgram_feed_comment_size',
                [
                    'label'         => __('Size', 'premium-addons-pro'),
                    'type'          => Controls_Manager::SLIDER,
                    'size_units'    => ['px',"em"],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-insta-comment' => 'font-size: {{SIZE}}{{UNIT}};'
                        ]
                    ]
                );
        
        $this->add_control('premium_instgram_feed_comment_background', 
                [
                    'label'         => __('Background Color', 'premium-addons-pro'),
                    'type'          => Controls_Manager::COLOR,
                    'selectors'     => [
                        '{{WRAPPER}} .premium-insta-comment' => 'background-color: {{VALUE}};',
                        ],
                    ]
                );
        
        $this->add_group_control(
            Group_Control_Border::get_type(), 
                [
                    'name'          => 'premium_instgram_feed_comments_border',
                    'selector'      => '{{WRAPPER}} .premium-insta-comment',
                ]
                );
        
        /*Likes Border Radius*/
        $this->add_control('premium_instgram_feed_comment_border_radius',
                [
                    'label'         => __('Border Radius', 'premium-addons-pro'),
                    'type'          => Controls_Manager::SLIDER,
                    'size_units'    => ['px', '%' ,'em'],
                    'default'       => [
                        'unit'  => 'px',
                        'size'  => 0,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-insta-comment' => 'border-radius: {{SIZE}}{{UNIT}};'
                    ]
                ]
                );
        
        /*Likes Box Shadow*/
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
                [
                    'label'         => __('Shadow','premium-addons-pro'),
                    'name'          => 'premium_instgram_feed_comments_shadow',
                    'selector'      => '{{WRAPPER}} .premium-insta-comment',
                ]
                );
        
        $this->add_responsive_control('premium_instgram_feed_comments_margin',
                [
                    'label'         => __('Margin', 'premium-addons-pro'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => ['px', 'em', '%'],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-insta-comment' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ]
                ]);
                
        /*Likes Padding*/
        $this->add_responsive_control('premium_instgram_feed_comments_padding',
                [
                    'label'         => __('Padding', 'premium-addons-pro'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => ['px', 'em', '%'],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-insta-comment' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ]
                ]);
        
        $this->end_controls_tab();
        
        $this->start_controls_tab('premium_instgram_feed_comments_number',
            [
                'label'             => __('Number', 'premium-addons-pro'),
            ]
            );
        
        /*Likes Number Color*/
        $this->add_control('premium_instgram_feed_comments_number_color', 
                [
                    'label'         => __('Color', 'premium-addons-pro'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme' => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_2,
                        ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-insta-comments' => 'color: {{VALUE}};',
                        ],
                    ]
                );
        
        /*Likes Number Typography*/
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'          => 'premium_instgram_feed_comments_number_typo',
                    'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                    'selector'      => '{{WRAPPER}} .premium-insta-comments',
                    ]
                );
        
        $this->add_control('premium_instgram_feed_comments_number_background', 
                [
                    'label'         => __('Background Color', 'premium-addons-pro'),
                    'type'          => Controls_Manager::COLOR,
                    'selectors'     => [
                        '{{WRAPPER}}  .premium-insta-comments' => 'background-color: {{VALUE}};',
                        ],
                    ]
                );
        
        $this->add_group_control(
            Group_Control_Border::get_type(), 
                [
                    'name'          => 'premium_instgram_feed_comments_number_border',
                    'selector'      => '{{WRAPPER}} .premium-insta-comments',
                ]
                );
        
        $this->add_control('premium_instgram_feed_comments_number_border_radius',
                [
                    'label'         => __('Border Radius', 'premium-addons-pro'),
                    'type'          => Controls_Manager::SLIDER,
                    'size_units'    => ['px', '%' ,'em'],
                    'default'       => [
                        'unit'  => 'px',
                        'size'  => 0,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-insta-comments' => 'border-radius: {{SIZE}}{{UNIT}};'
                    ]
                ]
                );
        
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
                [
                    'label'         => __('Shadow','premium-addons-pro'),
                    'name'          => 'premium_instgram_feed_comments_number_shadow',
                    'selector'      => '{{WRAPPER}} .premium-insta-comments',
                ]
                );
        
        $this->add_responsive_control('premium_instgram_feed_comments_number_margin',
                [
                    'label'         => __('Margin', 'premium-addons-pro'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => ['px', 'em', '%'],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-insta-comments' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ]
                ]);
        
        $this->add_responsive_control('premium_instgram_feed_comments_number_padding',
                [
                    'label'         => __('Padding', 'premium-addons-pro'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => ['px', 'em', '%'],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-insta-comments' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ]
                ]);
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_instgram_feed_caption',
            [
                'label'             => __('Caption','premium-addons-pro'),
                'tab'               => Controls_Manager::TAB_STYLE,
                'condition'         => [
                    'premium_instagram_feed_show_caption' => 'yes'
                ]   
            ]
        );
        
        $this->add_control('premium_instgram_feed_caption_color', 
            [
                'label'         => __('Text Color', 'premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'scheme' => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
            'selectors'     => [
                    '{{WRAPPER}} .premium-insta-image-caption' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'          => 'premium_instgram_feed_caption_typo',
                'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                'selector'      => '{{WRAPPER}} .premium-insta-image-caption'
            ]
        );
        
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'          => 'premium_instgram_feed_caption_shadow',
                'selector'      => '{{WRAPPER}} .premium-insta-image-caption'
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_instgram_feed_general_style',
            [
                'label'             => __('Container','premium-addons-pro'),
                'tab'               => Controls_Manager::TAB_STYLE,
            ]
        );
            
        $this->add_control('premium_instgram_feed_container_background', 
            [
                'label'         => __('Background', 'premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .premium-instafeed-container' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(), 
                [
                    'name'          => 'premium_instgram_feed_container_box_border',
                    'selector'      => '{{WRAPPER}} .premium-instafeed-container',
                ]
                );
        
        $this->add_control('premium_instgram_feed_container_box_border_radius',
                [
                    'label'         => __('Border Radius', 'premium-addons-pro'),
                    'type'          => Controls_Manager::SLIDER,
                    'size_units'    => ['px', '%' ,'em'],
                    'default'       => [
                        'unit'  => 'px',
                        'size'  => 0,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-instafeed-container' => 'border-radius: {{SIZE}}{{UNIT}};'
                    ]
                ]
                );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
                [
                    'name'          => 'premium_instgram_feed_container_box_shadow',
                    'selector'      => '{{WRAPPER}} .premium-instafeed-container',
                ]
                );
        
        $this->add_responsive_control('premium_instgram_feed_container_margin',
                [
                    'label'         => __('Margin', 'premium-addons-pro'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => ['px', 'em', '%'],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-instafeed-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ]
                ]);
                
        $this->add_responsive_control('premium_instgram_feed_container_padding',
                [
                    'label'         => __('Padding', 'premium-addons-pro'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => ['px', 'em', '%'],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-instafeed-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ]
                ]);
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_instgram_feed_spinner_style',
            [
                'label'             => __('Spinner','premium-addons-pro'),
                'tab'               => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control('premium_instgram_feed_spinner_background', 
            [
                'label'         => __('Color', 'premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .premium-loader' => 'border-top-color: {{VALUE}} !important',
                ],
            ]
        );
        
        $this->add_control('premium_instgram_feed_circle_background', 
            [
                'label'         => __('Spinner Background', 'premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .premium-loader' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->end_controls_section();

        $this->start_controls_section('carousel_style',
            [
                'label'         => __('Carousel', 'premium-addons-pro'),
                'tab'           => Controls_Manager::TAB_STYLE,
                'condition'     => [
                    'feed_carousel'  => 'yes'
                ]
            ]
        );
        
        $this->add_control('arrow_color',
            [
                'label'         => __('Arrow Color', 'premium-addons-pro'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-instafeed-container .slick-arrow' => 'color: {{VALUE}};',
                ]
            ]
        );

        $this->add_responsive_control('arrow_size',
            [
                'label'         => __('Size', 'premium-addons-pro'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%' ,'em'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-instafeed-container .slick-arrow i' => 'font-size: {{SIZE}}{{UNIT}};'
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
                    '{{WRAPPER}} .premium-instafeed-container .slick-arrow' => 'background-color: {{VALUE}};',
                ]
            ]
        );
        
        $this->add_control('arrow_border_radius',
            [
                'label'         => __('Border Radius', 'premium-addons-pro'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%' ,'em'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-instafeed-container .slick-arrow' => 'border-radius: {{SIZE}}{{UNIT}};'
                ]
            ]
        );

        $this->add_control('arrow_padding',
            [
                'label'         => __('Padding', 'premium-addons-pro'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%' ,'em'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-instafeed-container .slick-arrow' => 'padding: {{SIZE}}{{UNIT}};'
                ]
            ]
        );

        $this->end_controls_section();
        
    }
    
    /**
	 * Render Instagram Feed widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
    protected function render() {
        
        $settings = $this->get_settings_for_display();
        
        $hover_effect = 'premium-insta-' . $settings['premium_instagram_feed_image_hover'];
        
        $api_version = $settings['api_version'];
        
        $new_tab = $settings['premium_instagram_feed_new_tab'] == 'yes' ? 'target="_blank"' : '' ;
        if( $settings['premium_instagram_feed_link'] == 'yes') {
            $link = '<a href="{{link}}"' . $new_tab . '></a>';
        } else {
            if( 'yes' === $settings['premium_instagram_feed_popup'] ) {
                $link = '<a href="{{image}}" data-rel="prettyPhoto[premium-insta-' . esc_attr( $this->get_id() ) . ']"></a>';
            } else {
                $link = '';
            }
        }
        
        if( 'yes' === $settings['premium_instagram_feed_show_caption'] ) {
            $caption = '<p class="premium-insta-image-caption">{{caption}}</p>';
        } else {
            $caption = '';
        }

        $access_token = 'old' === $api_version ? $settings['premium_instagram_feed_client_access_token'] : $settings['new_accesstoken'];
        
        $tags = '';

        if( ! empty( $settings['premium_instagram_feed_tag_name'] ) ) {
            $tags = explode(  ",", $settings['premium_instagram_feed_tag_name'] );
            foreach( $tags as $index => $tag ) {
                $tags[ $index ] = trim( $tag );
            }
        }
        

        $likes = '';
        $comments = '';
        $sort = 'none';

        $api = $settings['api_version'];

        if( 'old' === $api ) {

            if( 'yes' === $settings['premium_instagram_feed_show_likes'] ) {
                $likes = '<p><i class="fas fa-heart premium-insta-heart" aria-hidden="true"></i> <span  class="premium-insta-likes">{{likes}}</span></p>';
            }

            if( 'yes' === $settings['premium_instagram_feed_show_comments'] ) {
                $comments = '<p><i class="fas fa-comment premium-insta-comment" aria-hidden="true"></i><span class="premium-insta-comments">{{comments}}</span></p>';
            }

            $sort = $settings['premium_instagram_feed_sort_by'];
        }

        
        
        $limit  = ! empty( $settings['premium_instagram_feed_img_number'] ) ? $settings['premium_instagram_feed_img_number'] : 6;

        $carousel = 'yes' === $settings['feed_carousel'] ? true : false; 
        
        if( $carousel ) {
            
            $play = 'yes' == $settings['carousel_play'] ? true : false;
            
            $speed = ! empty( $settings['carousel_autoplay_speed'] ) ? $settings['carousel_autoplay_speed'] : 5000;
            
            $this->add_render_attribute('instagram', [
                'data-carousel' => $carousel,
                'data-play'     => $play,
                'data-speed'    => $speed,
                'data-rtl'      => is_rtl()
            ]);
            
        }
        
        $instagram_settings = [
            'api'           => $api,
            'accesstok'     => $access_token,
            'tags'          => $tags,
            'sort'          => $sort,
            'limit'         => $limit,
            'likes'         => $likes,
            'comments'      => $comments,
            'description'   => $caption,
            'link'          => $link,
            'videos'        => $settings['show_videos'],
            'id'            => 'premium-instafeed-container-' . $this->get_id(),
            'masonry'       => ( $settings['premium_instagram_feed_masonry'] == 'yes' ) ? true : false,
            'theme'         => $settings['premium_instagram_feed_popup_theme'],
            'words'         => $settings['premium_instagram_feed_caption_number']
        ];
        
        $this->add_render_attribute( 'instagram', [
            'class'         => 'premium-instafeed-container',
            'data-settings' => wp_json_encode( $instagram_settings )
        ]);
        
        $this->add_render_attribute( 'instagram_container', [
            'id'    => 'premium-instafeed-container-' . $this->get_id(),
            'class' => [
                'premium-insta-grid',
                $hover_effect
            ]
        ]);

        $feed_number = intval ( 100 / substr( $settings['premium_instagram_feed_column_number'], 0, strpos( $settings['premium_instagram_feed_column_number'], '%') ) );
        
        $this->add_render_attribute( 'instagram', 'data-col', $feed_number );

        
        if( 'Invalid License Key' === $access_token ) : ?>
            <div class="premium-error-notice">
                <?php echo __('Please activate your license to get the access token','premium-addons-pro'); ?>
            </div>
        <?php elseif( empty ( $access_token ) ) : ?>
            <div class="premium-error-notice">
                <?php echo __('Please fill the required fields: Access Token','premium-addons-pro'); ?>
            </div>
        <?php else: ?>
            <div <?php echo $this->get_render_attribute_string('instagram'); ?>>
                <div <?php echo $this->get_render_attribute_string('instagram_container'); ?>></div>
                <div class="premium-loading-feed premium-show-loading">
                    <div class="premium-loader"></div>
                </div>
            </div>

        <?php
        
        endif;
    }
}