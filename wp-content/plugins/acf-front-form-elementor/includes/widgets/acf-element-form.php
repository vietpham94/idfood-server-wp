<?php
namespace ACF_FF_Elementor\Widgets;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Core\Schemes;
use Elementor;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;
use ACF_FF_Elementor\acf_ff_elementor;
/**
 * Press Elements Site Title
 *
 * Site title element for elementor.
 *
 * @since 1.0.0
 */
class ACF_Element_Form extends Widget_Base {

	/**
	 * ACF Field Groups
	 * 
	 * @var array
	 */
	protected $acf_groups;

	/**
	 * ACF Fields
	 * 
	 * @var array
	 */
	protected $acf_fields;
	
	/**
	 * Enable Advanced settings ( HTML code and more )
	 *
	 * @var boolean
	 */
	protected $enable_advanced = false;

	/**
	 * Enable WP Content Editor Buttons Style
	 *
	 * @var boolean
	 */
	protected $wp_editor_bts = true;

	public function get_name() {
		return 'acf-form';
	}

	public function get_title() {
		return __( 'ACF Form', $this->get_local() );
	}

	public function get_icon() {
		return 'eicon-form-horizontal';
	}

	public function get_categories() {
		return [ 'acf-front-form-elementor' ];
	}

	public function get_local(){
		return 'acf-front-form-elementor';
	}

	/**
     * Loads page URLs
     *
     * @since 1.2.0
     * @return void
     */
    protected function get_page_links(){
        $pages = get_posts([
            'post_type' => 'page',
            'numberposts' => -1,
		]);
		
		$page_links = [
			'none' => __( '- This page -', $this->get_local() ),
			'home' => __( '- Home URL -', $this->get_local() ),
		];

        if ( $pages ){
            foreach( $pages as $page ){
                $page_links[ $page->ID ] = $page->post_title;
            }
		}
		
		$page_links['custom'] = __( '- Custom URL -', $this->get_local() );

		return $page_links;
    }

	protected function load_acf_groups_fields(){
        /**
         * Get Groups Fields
         */
        
        $args = array(
            'numberposts' => -1,
            'post_type'   => 'acf-field-group'
        );
        
        $groups = get_posts( $args );

        if( $groups ){
            foreach( $groups as $g ){
                $this->acf_groups[ $g->ID ] = $g->post_title;
            }
        }

        /**
         * Get fields
         */
        
        $args = array(
            'numberposts' => -1,
            'post_type'   => 'acf-field',
            //'post_parent' => 944
        );
        
        $fields = get_posts( $args );

        if( $fields ){
            foreach( $fields as $f ){
                $this->acf_fields[ $f->ID ] = $f->post_title;
            }
        }
    }
	
	protected function add_controls_section_post(){

		$this->start_controls_section(
			'section_post',
			[
				'label' => __( 'Post', $this->get_local() ),
			]
		);

		$this->add_control(
			'is_new_post',
			[
				'label' => __( 'Create New Post', $this->get_local() ),
				'type' => Controls_Manager::SWITCHER,
				'description'	=> __( 'Whether or not to create a new post.', $this->get_local() ),
				'default' => 'yes',
				'show_label' => true,
			]
		);

		$this->add_control(
			'post_id',
			[
				'label' => __( 'Post ID', $this->get_local() ),
				'type' => Controls_Manager::NUMBER,
				'placeholder' => __( 'post id or new_post', $this->get_local() ),
				'default' => 1,
				'show_label' => true,
				'condition' => [
					'is_new_post' => '',
				],
			]
		);

		$this->add_control(
			'new_post_heading',
			[
				'label'		=> __( 'New Post Values', $this->get_local() ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'is_new_post' => 'yes',
				],
			]
		);
		
		$this->add_control(
			'np_title',
			[
				'label' => __( 'Post Title', $this->get_local() ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'show_label' => true,
				'condition' => [
					'is_new_post' => 'yes'
				],
			]
		);
		
		$args = array(
            //'_builtin' => false,
            'public' => true
        );
        //
        $post_types = get_post_types($args, 'objects');
        //
        arsort( $post_types ); //, SORT_ASC );
        //
        $output = array();
        foreach ($post_types as $post_type) {
            $output[ $post_type->name ] = $post_type->label;
		}
		
		$this->add_control(
			'np_type',
			[
				'label' => __( 'Post Type', $this->get_local() ),
				'type' => Controls_Manager::SELECT,
				'default' => 'post',
				'options'	=> $output,
				'show_label' => true,
				'condition' => [
					'is_new_post' => 'yes',
				],
			]
		);
		
		$this->add_control(
			'np_status',
			[
				'label' => __( 'Post Status', $this->get_local() ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'publish' 	=> [
						'title' => __( 'Publish', $this->get_local() ),
						'icon' 	=> 'eicon-check-circle',
					],
					'draft' 	=> [
						'title' => __( 'Draft', $this->get_local() ),
						'icon' 	=> 'fa fa-edit',
					],
					'private' 	=> [
						'title' => __( 'Private', $this->get_local() ),
						'icon' 	=> 'fa fa-lock',
					],
					'pending' 	=> [
						'title' => __( 'Pending', $this->get_local() ),
						'icon' 	=> 'fa fa-pause',
					],
					'trash' 	=> [
						'title' => __( 'Trash', $this->get_local() ),
						'icon' 	=> 'fa fa-trash',
					],
				],
				'default' => 'publish',
				'show_label' => true,
				'condition' => [
					'is_new_post' => 'yes',
				],
			]
		);
		
		//	'new_post'    => '',
		$this->add_control(
			'new_post',
			[
				'label' => __( 'Additional New Post Variables', $this->get_local() ),
				'description'	=> __('One per line. New post values, Example : post_password=123. More details <a href="https://codex.wordpress.org/Class_Reference/WP_Post" target="_blank">here</a>', $this->get_local() ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => '',
				'show_label' => true,
				'condition' => [
					'is_new_post' => 'yes',
                ],
			]
		);
		
		$this->end_controls_section();
	}

	protected function add_controls_section_form(){
		
		$this->start_controls_section(
			'section_form',
			[
				'label' => __( 'Form', $this->get_local() ),
			]
		);

		$this->add_control(
			'form',
			[
				'label' => __( 'This is a Form', $this->get_local() ),
				'type' => Controls_Manager::SWITCHER,
				'description'	=> __( 'Whether or not to create a form element. Useful when a adding to an existing form. Defaults to Yes', $this->get_local() ),
				'default' => 'yes',
				'show_label' => true,
			]
		);

		//id
		$this->add_control(
			'acf_form_id',
			[
				'label' => __( 'Form ID', $this->get_local() ),
				'type' => Controls_Manager::TEXT,
				'description'	=> __( 'Unique identifier for the form. Defaults to "acf-form"', $this->get_local() ),
				'default' => 'acf-form',
				'show_label' => true,
				'condition'	=> [
					'form'	=> 'yes'
				],
			]
		);

		//form_attributes
		$this->add_control(
			'form_attributes',
			[
				'label' => __( 'Form Attributes', $this->get_local() ),
				'type' => Controls_Manager::TEXTAREA,
				//'description'	=> __( 'One per line. List of HTML attributes for the form element', $this->get_local() ),
				'default' => '',
				'placeholder'	=> __( 'One per line. List of HTML attributes for the form element', $this->get_local() ),
				'show_label' => true,
				'condition'	=> [
					'form'	=> 'yes'
				],
			]
		);
		
		$this->add_control(
			'link_to',
			[
				'label' => __( 'Return to', $this->get_local() ),
				'description' => __('The Page where to be redirected to after the form is submit. Default to current page.', $this->get_local()),
				'type' => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => $this->get_page_links(),
				'condition'	=> [
					'form'	=> 'yes'
				],
			]
		);

		$this->add_control(
			'return',
			[
				'label' => __( 'Custom URL', $this->get_local() ),
				'type' => Controls_Manager::URL,
				'placeholder' => __( 'http://your-custom-url.com', $this->get_local() ),
				'condition' => [
					'link_to' => 'custom',
					'form'	=> 'yes'
				],
				'default' => [
					'url' => '',
				],
				'show_label' => false,
			]
		);
		
		$this->add_control(
			'submit_value',
			[
				'label' => __( 'Submit Value', $this->get_local() ),
				'type' => Controls_Manager::TEXT,
				'description' => __( 'The text displayed on the submit button', $this->get_local() ),
				'placeholder' => __( 'Update', $this->get_local() ),
				'default' => 'Update',
				'show_label' => true,
				'condition'	=> [
					'form'	=> 'yes'
				],
			]
		);
		
		//updated_message
		$this->add_control(
			'updated_message',
			[
				'label' => __( 'Updated Message', $this->get_local() ),
				'type' => Controls_Manager::TEXT,
				'description'	=> __( 'A message displayed above the form after being redirected. Empty for no message', $this->get_local() ),
				'placeholder' => __( 'Post updated', $this->get_local() ),
				'default' => 'Post updated',
				'show_label' => true,
				'condition'	=> [
					'form'	=> 'yes'
				],
			]
		);

		//Whether or not to sanitize all $_POST data with the wp_kses_post() function. Defaults to true
		$this->add_control(
			'kses',
			[
				'label' => __( 'Sanitize with wp_kses_post()', $this->get_local() ),
				'type' => Controls_Manager::SWITCHER,
				'description'	=> __( 'Whether or not to sanitize all $_POST data with the wp_kses_post() function. Defaults to Yes', $this->get_local() ),
				'default' => 'yes',
				'show_label' => true,
				'condition'	=> [
					'form'	=> 'yes'
				],
			]
		);

		$this->end_controls_section();
		

		/**
		 * Form style
		 */
		$this->start_controls_section(
			'form_style',
			[
				'label' => __( 'Form', $this->get_local() ),
				'tab'	=> Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label' => __( 'Alignment', $this->get_local() ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', $this->get_local() ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', $this->get_local() ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', $this->get_local() ),
						'icon' => 'fa fa-align-right',
					],
					'justify' => [
						'title' => __( 'Justified', $this->get_local() ),
						'icon' => 'fa fa-align-justify',
					],
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				],
			]
		);

		//echo var_dump( $this->get_selectors( ['.acf-form', '.acf' ], 'background-color:{{SHIOT}}' ));

		$this->get_ui_style_section( 'form', ['.acf-form'], '', true, true );

	}

	/**
	 * Builds array selectors for elementor
	 * @param array $csss
	 * @param string $value
	 * @return array
	 */
	protected function get_selectors( $css_array, $value = '' ){

		$selectors = [];

		foreach ( $css_array as $css ) {

			if ( $value != '' ){

				$selectors[ '{{WRAPPER}} ' . $css ] = $value ;

			}else{

				$selectors[] = '{{WRAPPER}} ' . $css;

			}
		}
		
		return $selectors;
	}
	/**
	 * Builds string with comma separated css selector
	 * @param array $csss
	 * @param string $value
	 * @return string
	 */
	protected function get_multiple_selector( $css_array, $value = '' ){

		$selectors = '';

		foreach ( $css_array as $css ) {
			
			$selectors .= '{{WRAPPER}} ' . $css . $value . ',';
		}
		
		$selectors = \rtrim( $selectors, "," );

		return $selectors;
	}
	/**
	 * Adds a thick devider
	 *
	 * @param string $name
	 * @return void
	 */
	protected function add_divider( $name ){
		$this->add_control(
			$name,
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);
	}

	/**
	 * Builds UI style for given object
	 * - Padding/margin
	 * - Background color
	 * - Border
	 *
	 * @return void
	 */
	protected function get_ui_style_section( $element, $css_array, $heading = '', $separator = false, $end_section = false, $typos = [] ){
		
		if ( count( $typos ) > 0 ){
			
			foreach ( $typos as $key => $value ) {

				if ( $value == [] ) $value = $css_array;

				$this->add_group_control(
					\Elementor\Group_Control_Typography::get_type(),
					[
						'name' => $element . $key . '_typography',
						'label' => $key . ' Typography',
						'scheme' => Schemes\Typography::TYPOGRAPHY_1,
						'selector' => $this->get_multiple_selector( $value ),
					]
				);
	
				$this->add_group_control(
					\Elementor\Group_Control_Text_Shadow::get_type(),
					[
						'name' => $element . $key . '_text_shadow',
						'label' => $key . ' Text Shadow',
						'selector' => $this->get_multiple_selector( $value ),
					]
				);
			}
		}

		if ( $heading != '' ){

			$this->add_control(
				$element . '_heading',
				[
					'label'		=> $heading,
					'type' => Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

		}elseif ( $separator ){

			$this->add_divider( $element . '_separator_0' );
		}
		/**
		 * Width
		 */
		$this->add_responsive_control(
			$element . '_width',
			[
				'label' => __( 'Width', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => '%',
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'size_units' => [ '%', 'px', 'vw' ],
				'range' => [
					'%' => [
						'min' => 1,
						'max' => 100,
					],
					'px' => [
						'min' => 1,
						'max' => 2000,
					],
					'vw' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => $this->get_selectors( $css_array, 'width: {{SIZE}}{{UNIT}};'),
			]
		);

		$this->add_responsive_control(
			$element . '_space',
			[
				'label' => __( 'Max Width', 'elementor' ) . ' (%)',
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => '%',
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'size_units' => [ '%' ],
				'range' => [
					'%' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => $this->get_selectors( $css_array, 'max-width: {{SIZE}}{{UNIT}};'),
			]
		);

		$this->add_divider( $element . '_separator_effects' );

		$this->start_controls_tabs( $element . '_effects' );
		
		$this->get_group_ui_style( $element, $css_array, 'Normal', false, $typos );
		
		$this->get_group_ui_style( $element . '_hover', $css_array, 'Hover', true, $typos );

		//$this->get_group_ui_style( $element . '_hover', $css, 'Hover', true );

		$this->end_controls_tabs();

		/**
		 * Margin
		 */
		$this->add_responsive_control(
			$element . '_margin', 
			[
				'label'			=> __( 'Margin', $this->get_local() ),
				'type'			=> Controls_Manager::DIMENSIONS,
				'size_units'	=> [ 'px', '%' ],
				'selectors'		=> $this->get_selectors( $css_array, 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
			]
		);
		/**
		 * Padding
		 */
		$this->add_responsive_control(
			$element . '_padding', 
			[
				'label'			=> __( 'Padding', $this->get_local() ),
				'type'			=> Controls_Manager::DIMENSIONS,
				'size_units'	=> [ 'px', 'em', '%' ],
				'selectors'		=> $this->get_selectors( $css_array, 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
			]
		);
		/**
		 * Border
		 */
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => $element . '_border',
				'selector' => $this->get_multiple_selector( $css_array ),
				'separator' => 'before',
			]
		);
		/**
		 * Border Radius
		 */
		$this->add_responsive_control(
			$element . '_border_radius',
			[
				'label' => __( 'Border Radius', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => $this->get_selectors( $css_array, 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
			]
		);
		/**
		 * Box shadow
		 */
		if ( $element != 'input_field' ){
			$this->add_group_control(
				\Elementor\Group_Control_Box_Shadow::get_type(),
				[
					'name' => $element . '_box_shadow',
					'exclude' => [
						'box_shadow_position',
					],
					'selector' => $this->get_multiple_selector( $css_array )
				]
			);

		}

		if ( $end_section ) {
			$this->end_controls_section();
		}

	}
	/**
	 * Appends css selector to all css values
	 *
	 * @param array $css_array
	 * @param string $selector
	 * @return array
	 */
	protected function append_css_selector( $css_array, $selector ){

		$css_selectors = [];

		if ( \is_array( $selector ) ){

			foreach ($selector as $value) {

				foreach ($css_array as $css) {

					$css_selectors[] = $css . ' > ' . $value;
				}
			}

		}else{

			foreach ($css_array as $value) {
			
				$css_selectors[] = $value . $selector;
			}
		}

		return $css_selectors;

	}
	/**
	 * Build background color, borders, margin and padding UI
	 *
	 * @param string the element name (id)
	 * @param array array of css selectors
	 * @param string Tab title
	 * @param boolean transition duration
	 * @return void
	 */
	protected function get_group_ui_style( $element, 
											$css_array, 
											$tab_label = '', 
											$transition = false, 
											$typo_array = null, 
											$text_color_label = 'Text Color' ){

		$css = $css_array;
		$typos = $typo_array;

		if ( $transition && $tab_label != '' ){
			
			$css = $this->append_css_selector( $css_array, ':hover' );

			foreach ($typo_array as $key => $value) {
				
				$typos[ $key ] = $this->append_css_selector( $css, $value );

			}
		}
		
		if ( $tab_label != '' ){

			$tab_id = $element . \strtolower( $tab_label );
			
			$this->start_controls_tab( 
				$tab_id ,
				[
					'label' => $tab_label,
				]
			);

		}
		/**
		 * Opacity
		 */
		$this->add_control(
			$element . '_opacity',
			[
				'label' => __( 'Opacity', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 1,
						'min' => 0.10,
						'step' => 0.01,
					],
				],
				'selectors' => $this->get_selectors( $css, 'opacity: {{SIZE}};'),
			]
		);

		if ( $typos ){
			/**
			 * Text color
			 */

			foreach ( $typos as $key => $value ) {

				if ( $value == [] ) $value = $css;

				$this->add_control(
					$element . $key  . '_text_color',
					[
						'label' => $key . ' Text Color',
						'type' => Controls_Manager::COLOR,
						'scheme' => [
							'type' => Schemes\Color::get_type(),
							'value' => Schemes\Color::COLOR_1,
						],
						'selectors' => $this->get_selectors( $value, 'color: {{VALUE}};' ),
					]
				);
			}
		}

		/**
		 * background color
		 */
		$this->add_control(
			$element . '_background_color',
			[
				'label' => __( 'Background Color', $this->get_local() ),
				'type' => Controls_Manager::COLOR,
				'selectors' => $this->get_selectors( $css, 'background-color: {{VALUE}};' )
			]
		);

		if ( $transition && $tab_label != '' ){

			$tab_id = $element . \strtolower( $tab_label );

			$trs = $css_array;

			/**
			 * apply transition to text too
			 */
			foreach ($typo_array as $key => $value) {
				
				foreach ($value as $v) {
					
					$trs[] = $v;
				}
			}
			
			$this->add_control(
				$tab_id . '_transition',
				[
					'label' => __( 'Transition Duration', 'elementor' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'max' => 5,
							'step' => 0.1,
						],
					],
					'selectors' => $this->get_selectors( $trs, 'transition-duration: {{SIZE}}s'),
				]
			);
			/*
			$this->add_control(
				$tab_id . '_animation',
				[
					'label' => $tab_label . ' Animation',
					'type' => Controls_Manager::HOVER_ANIMATION,
					'selectors' => $this->get_selectors( $css_array ),
				]
			);
			*/
		}
		$this->add_divider( $element . '_separator_1' );

		if ( $tab_label != '' ){
			
			$this->end_controls_tab();
		}

	}

	protected function add_controls_section_fields(){

		$this->start_controls_section(
			'section_fields',
			[
				'label' => __( 'Fields', $this->get_local() ),
			]
		);

		$this->add_responsive_control(
			'field_el',
			[
				'label' => __( 'Field Element', $this->get_local() ),
				'description' => __("Determines element used to wrap a field. Defaults to 'div'", $this->get_local()),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'div' => [
						'title' => __( 'Division (div)', $this->get_local() ),
						'icon' 	=> 'eicon-slider-push',
					],
					'tr' => [
						'title'	=> __( 'Table Row (tr)', $this->get_local() ),
						'icon'	=> 'eicon-table',
					],
					'td' => [
						'title'	=> __( 'Table Data (td)', $this->get_local() ),
						'icon'	=> 'eicon-form-vertical',
					],
					'ul' => [
						'title'	=> __( 'Unordered List (ul)', $this->get_local() ),
						'icon'	=> 'eicon-editor-list-ul',
					],
					'ol' => [
						'title'	=> __( 'Ordered List (ol)', $this->get_local() ),
						'icon'	=> 'eicon-editor-list-ol',
					],
					'dl' => [
						'title'	=> __( 'Description List (dl)', $this->get_local() ),
						'icon'	=> 'eicon-post-list',
					],
				],
				'default' => 'div',
			]
		);

		$this->add_divider( '_separator_0' );
		
		$this->add_control(
			'show_title',
			[
				'label' => __( 'Show Title', $this->get_local() ),
				'type' => Controls_Manager::SWITCHER,
				'description' => __( 'Whether to use the default wp post title as a field in the form or not', $this->get_local() ),
				'default' => false,
				'show_label' => true,
			]
        );
        $this->add_control(
			'show_content',
			[
				'label' => __( 'Show Content', $this->get_local() ),
				'type' => Controls_Manager::SWITCHER,
				'description' => __( 'Whether to use the default wp post content as a field in the form or not', $this->get_local() ),
				'default' => false,
				'show_label' => true,
			]
		);

		$this->load_acf_groups_fields();

		$this->add_control(
			'field_groups',
			[
				'label'			=> __( 'Field Groups', $this->get_local() ),
				'description' => __( 'Select the ACF Field Groups to use in the form', $this->get_local() ),
				'type'			=> Controls_Manager::SELECT2,
				'options'		=> $this->acf_groups,
				'default'		=> '',
				'show_label'	=> true,
				'multiple'		=> true,
			]
		);
		$this->add_control(
			'fields',
			[
				'label'			=> __( 'Fields', $this->get_local() ),
				'description' => __( 'Select the ACF Fields to use in the form', $this->get_local() ),
				'type'			=> Controls_Manager::SELECT2,
				'options'		=> $this->acf_fields,
				'default'		=> '',
				'show_label'	=> true,
				'multiple'		=> true,
			]
		);

		$this->add_divider( 'separator_1' );
		
		// uploader
		$this->add_control(
			'uploader',
			[
				'label' => __( 'Uploader', $this->get_local() ),
				'description' => __( 'Whether to use the WP uploader or a basic input for image and file fields', $this->get_local() ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'wp' => [
						'title' => __( 'WordPress', $this->get_local() ),
						'icon' 	=> 'fa fa-wordpress',
					],
					'basic' => [
						'title'	=> __( 'Basic', $this->get_local() ),
						'icon'	=> 'fa fa-upload',
					],
				],
				'default' => 'wp',
			]
		);

		//honeypot
		$this->add_control(
			'honeypot',
			[
				'label' 		=> __( 'Honeypot', $this->get_local() ),
				'type' 			=> Controls_Manager::SWITCHER,
				'description'	=> __( 'Whether to include a hidden input field to capture non human form submission. Defaults to Yes', $this->get_local()),
				'default' 		=> 'yes',
				'show_label'	=> true,
			]
		);

		$this->end_controls_section();

		/**
		 * Fields Style
		 */
		$this->start_controls_section(
			'fields_style',
			[
				'label' => __( 'Fields', $this->get_local() ),
				'tab'	=> Controls_Manager::TAB_STYLE,
			]
		);

		// label_placement
		$this->add_responsive_control(
			'label_placement',
			[
				'label' => __( 'Label Placement', $this->get_local() ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'top' => [
						'title' => __( 'Top', $this->get_local() ),
						'icon' 	=> 'fa fa-arrow-up',
					],
					'left' => [
						'title'	=> __( 'Left', $this->get_local() ),
						'icon'	=> 'fa fa-arrow-left',
					],
				],
				'default' => 'top',
			]
		);
		//Label Width
		$this->add_responsive_control(
			$element . '_label_width',
			[
				'label' => __( 'Label Width', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'condition' => [
					'label_placement' => 'left'
				],
				'default' => [
					'unit' => '%',
					'size' => 20,
				],
				'size_units' => [ '%' ],
				'range' => [
					'%' => [
						'min' => 1,
						'max' => 100,
					]
				],
				'selectors' => [
					'{{WRAPPER}} .acf-label' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .acf-input' => 'width: calc(100{{UNIT}} - {{SIZE}}{{UNIT}});',
				],
			]
		);

		//instruction_placement
		$this->add_responsive_control(
			'instruction_placement',
			[
				'label' => __( 'Instruction Placement', $this->get_local() ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'label' => [
						'title' => __( 'Label', $this->get_local() ),
						'icon' 	=> 'eicon-align-left',
					],
					'field' => [
						'title'	=> __( 'Field', $this->get_local() ),
						'icon'	=> 'eicon-post-list',
					],
				],
				'default' => 'label',
			]
		);

		$this->add_divider( 'end_label_placement' );

		// '.acf-fields.-left>.acf-field'

		$text_selectors = [
			'Label' => [ '.acf-label label' ],
			'Instruction' => [ '.acf-label p.description', '.acf-input p.description' ],
			'Required' => [ '.acf-label label > span.acf-required' ],
		];

		$this->get_ui_style_section( 'field', [ '.acf-fields .acf-field' ], '', true, true, $text_selectors );

		/**
		 * Input Field Style
		 */
		$this->start_controls_section(
			'input_fields_style',
			[
				'label' => __( 'Input', $this->get_local() ),
				'tab'	=> Controls_Manager::TAB_STYLE,
			]
		);
		
		//$input_css = [ '.acf-input-wrap > input', '.acf-input-wrap > textarea', '.acf-input-wrap > select' ];

		$input_css = [
			'.acf-field input:not(.ed_button)',
			'.acf-field textarea:not(.ed_button)',
			'.acf-field select:not(.ed_button)',
			//'.acf-field .acf-input-wrap input',
			//'.acf-field .acf-input-wrap textarea',
			//'.acf-field .acf-input-wrap select',
			//'.acf-input .acf-input-wrap .acf-is-prepended', // no effect
			//'.acf-input .acf-input-wrap .acf-is-appended', // no effect
			'.acf-input .acf-input-prepend',
			'.acf-input .acf-input-append',
		];

		$input_typos = [
			'Input' => [],
			'Placeholder' => [
				'::placeholder',
			],
			//'Prepend/Append' => ['.acf-input-prepend', '.acf-input-append'],
		];

		//$this->get_ui_style_section( 'input_field', '.acf-input-wrap > input', '', true, true );
		$this->get_ui_style_section( 'input_field', $input_css, '', false, true, $input_typos );

		$this->end_controls_section();

		/**
		 * Button
		 */
		$this->start_controls_section(
			'submit_button_style',
			[
				'label' => __( 'Submit Button', $this->get_local() ),
				'condition' => [
					'form' => 'yes'
				],
				'tab'	=> Controls_Manager::TAB_STYLE,
			]
		);

		// acf-button button button-primary button-large
		$buttons = [
			'input.acf-button.button.button-primary',
			'.acf-form-submit > input[type="submit"]',
			'.acf-form-submit > button',
			'.acf-form-submit > input[type="button"]',
			'.acf-form-submit > input[type="reset"]',
			//'.wp-media-buttons button',
			//'.wp-editor-tabs button',
		];

		$btn_text = [
			'Text' => []
		];
		
		$this->get_ui_style_section( 'submit_button', $buttons, '', true, true, $btn_text );

		$this->end_controls_section();

		if ( $this->wp_editor_bts ){

			/**
			 * WP Editor buttons
			 */
			$this->start_controls_section(
				'content_editor_buttons',
				[
					'label' => __( 'Content Editor Buttons', $this->get_local() ),
					'tab'	=> Controls_Manager::TAB_STYLE,
					'condition' => [
						'show_content' => 'yes'
					]
				]
			);

			// acf-button button button-primary button-large
			$buttons = [
				'.wp-media-buttons button',
				'.wp-editor-tabs button',
			];

			$btn_text = [
				'Text' => []
			];
			
			$this->get_ui_style_section( 'content_editor_buttons', $buttons, '', true, true, $btn_text );

			$this->end_controls_section();

		}
	}

	protected function add_controls_section_html(){

		$this->start_controls_section(
			'section_html',
			[
				'label' => __( 'Advanced', $this->get_local() ),
			]
		);

		$this->add_control(
			'html_before_fields',
			[
				'label' => __( 'HTML Before Fields', $this->get_local() ),
				'description'	=> __('Extra HTML to add before the fields' , $this->get_local()),
				'type' => Controls_Manager::CODE,
				'language' => 'html',
				'rows' => 20,
				'show_label' => true,
			]
		);
		
		$this->add_control(
			'html_after_fields',
			[
				'label' => __( 'HTML After Fields', $this->get_local() ),
				'description'	=> __('Extra HTML to add after the fields' , $this->get_local()),
				'type' => Controls_Manager::CODE,
				'language' => 'html',
				'rows' => 20,
				'show_label' => true,
			]
		);
		//HTML Update Message
		$this->add_control(
			'html_updated_message',
			[
				'label' => __( 'HTML Update Message', $this->get_local() ),
				'default' => '<div id="message" class="updated"><p>%s</p></div>',
				'description'	=> __('A message displayed above the form after being redirected. Can also be set to false for no message' , $this->get_local()),
				'type' => Controls_Manager::CODE,
				'language' => 'html',
				'rows' => 20,
				'show_label' => true,
			]
		);
		//Submit Button
		$this->add_control(
			'html_submit_button',
			[
				'label' => __( 'Submit Button', $this->get_local() ),
				'description'	=> __('HTML used to render the submit button' , $this->get_local()),
				'default' => '<input type="submit" class="acf-button button button-primary button-large" value="%s" />',
				'type' => Controls_Manager::CODE,
				'language' => 'html',
				'rows' => 20,
				'show_label' => true,
			]
		);
		//Submit Spinner
		$this->add_control(
			'html_submit_spinner',
			[
				'label' => __( 'Submit Spinner', $this->get_local() ),
				'description'	=> __('HTML used to render the submit button loading spinner' , $this->get_local()),
				'default' => '<span class="acf-spinner"></span>',
				'type' => Controls_Manager::CODE,
				'language' => 'html',
				'rows' => 20,
				'show_label' => true,
			]
		);

		
		$this->end_controls_section();
	}

	protected function add_controls_section_table(){

		$this->start_controls_section(
			'section_table',
			[
				'label' => __( 'Table', $this->get_local() ),
				'condition' => [
                    'field_el'	=> [ 'td', 'tr' ]
				],
				//'tab'		=> Controls_Manager::TAB_STYLE,
			]
		);

		//	'table_el'
		$this->add_control(
			'table_el',
			[
				'label' => __( 'Add table tags', $this->get_local() ),
				'description'	=> __('Whether or not to add table tag. Works only if field_el is set to tr or td', $this->get_local() ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'show_label' => true,
			]
		);

		/**
		 * Thead labels
		 */
		$this->add_control(
			'thead_default_labels',
			[
				'label' => __( 'Try Default Labels in Header', $this->get_local() ),
				'description'	=> __('Whether or not to use default labels in table header', $this->get_local() ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'show_label' => true,
				'condition' => [
                    'table_el'	=> 'yes',
                ],
			]
		);
		//	'thead'    => '',
		$this->add_control(
			'thead',
			[
				'label' => __( 'Table Head Columns', $this->get_local() ),
				'description'	=> __('One per line. Text added into td tags. This will add the thead tag', $this->get_local() ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => '',
				'show_label' => true,
				'condition' => [
                    'thead_default_labels' => '',
                ],
			]
		);
		/**
		 * TFoot labels
		 */
		$this->add_control(
			'tfoot_default_labels',
			[
				'label' => __( 'Try Default Labels in Footer', $this->get_local() ),
				'description'	=> __('Whether or not to use default labels in table footer', $this->get_local() ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'show_label' => true,
				'condition' => [
                    'table_el'	=> 'yes',
                ],
			]
		);
		//	'tfoot'    => '',
		$this->add_control(
			'tfoot',
			[
				'label' => __( 'Table Footer Columns', $this->get_local() ),
				'description'	=> __('One per line. Text added into td tags. This will add the tfooter tag', $this->get_local() ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => '',
				'show_label' => true,
				'condition' => [
                    'tfoot_default_labels' => '',
                ],
			]
		);

		//$this->get_ui_style_section( 'thead', [ '.acf-form-table thead tr' ], 'Table Header', true, false );
		//$this->get_ui_style_section( 'tbody', [ '.acf-form-table tbody tr' ], 'Table Rows', true, false );
		//$this->get_ui_style_section( 'tfoot', [ '.acf-form-table tfoot tr' ], 'Table Footer', true, true );

		$this->end_controls_section();
	}

	/**
	 * Authorizations section
	 *
	 * @return void
	 */
	protected function add_controls_section_authorizations(){

		$this->start_controls_section(
			'section_auth',
			[
				'label' => __( 'Authorizations', $this->get_local() ),
			]
		);

		/*
		'logged_in'     => true,
		'roles'         => '',
		'author_only'   => true
		*/
		//	'logged_in'
		$this->add_control(
			'logged_in',
			[
				'label' => __( 'Logged in only', $this->get_local() ),
				'description'	=> __('Display the form for only logged in users.', $this->get_local() ),
				'type' => Controls_Manager::SWITCHER,
				'default' => true,
				'show_label' => true,
			]
		);
		//	'roles'    => '',
		global $wp_roles;
		
		$this->add_control(
			'roles',
			[
				'label' => __( 'Roles', $this->get_local() ),
				'description'	=> __('Define which roles the form will be displayed to. Leave blank for all', $this->get_local() ),
				'type' => Controls_Manager::SELECT2,
				'default' => '',
				'options' => $wp_roles->get_names(),
				'show_label' => true,
				'multiple'	=> true,
				'condition' => [
                    'logged_in'	=> 'yes',
                ],
			]
		);
		//	'users'    => '',

		$users = get_users(); //[0]->display_name

		$users_options = [];

		foreach ($users as $value) {
			
			$users_options[ $value->ID ] = $value->display_name;
		}

		$this->add_control(
			'users',
			[
				'label' => __( 'Users', $this->get_local() ),
				'description'	=> __('Define which users the form will be displayed to. Leave blank for all', $this->get_local() ),
				'type' => Controls_Manager::SELECT2,
				'default' => '',
				'options' => $users_options,
				'show_label' => true,
				'multiple'	=> true,
				'condition' => [
                    'logged_in'	=> 'yes',
                ],
			]
		);

		$this->end_controls_section();
	}
    
	protected function _register_controls() {

		$this->add_controls_section_post();

		$this->add_controls_section_form();

		$this->add_controls_section_fields();

		$this->add_controls_section_table();

		$this->add_controls_section_authorizations();

		if ( $this->enable_advanced ) $this->add_controls_section_html();

	}
	/**
	 * Whether the reload preview is required or not.
	 *
	 * Used to determine whether the reload preview is required.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return bool Whether the reload preview is required.
	 */
	public function is_reload_preview_required() {
		return true;
	}


	protected function render() {

		$args = $this->get_shortcode();
		
		$shortcode = do_shortcode( shortcode_unautop( $args ) );

		?>
		<div class="elementor-shortcode"><?php echo $shortcode; ?></div>
		<?php
		
	}
	private function get_shortcode(){

		$args = '[acf_front_form';

		/**
		 * Post Section
		 */

		if ( $this->get_settings('is_new_post') == 'yes' ) {

			$args .= ' post_id="new_post"';

			$args .= ( $this->get_settings('np_title') ) ? ' np_title="'.$this->get_settings('np_title').'"' : '';
			$args .= ( $this->get_settings('np_status') ) ? ' np_status="'.$this->get_settings('np_status').'"' : '';
			$args .= ( $this->get_settings('np_type') ) ? ' np_type="'.$this->get_settings('np_type').'"' : '';
			
			/**
			 * New_Post attributes
			 */
			if ( $this->get_settings('new_post') ) {

				$fs = \explode( "\n", $this->get_settings('new_post') );

				$args .= ' new_post="';

				foreach ( $fs as $f ) {
					$args .= $f . ',';
				}
				$args = rtrim($args,",") . '"';
			}

		}else{

			$args .= ( $this->get_settings('post_id') ) ? ' post_id="'.$this->get_settings('post_id').'"' : '';
			
		}

		/**
		 * Form section
		 */
		$args .= ( $this->get_settings('form') != 'yes' ) ? ' form="No"' : '';
		$args .= ( $this->get_settings('acf_form_id') != 'acf-form' ) ? ' id="'.$this->get_settings('acf_form_id').'"' : '';

		if ( $this->get_settings('form_attributes') ) {

			$fatts = \explode( "\n", $this->get_settings('form_attributes') );

			$args .= ' form_attributes="';

			foreach ( $fatts as $fa ) {
				$args .= $fa . ',';
			}
			$args = rtrim($args,",") . '"';
		}
		
		switch ( $this->get_settings('link_to') ) {

			case 'none':
				// do nothing
				break;
			case 'home':
				$args .= ' return="' . home_url() . '"';
				break;
			case 'custom':
				if ( $this->get_settings('return') ) {

					$url = $this->get_settings('return')['url'];

					if ( filter_var($url, FILTER_VALIDATE_URL) !== FALSE ) {

						$args .= ' return="' . $url . '"';
					}
				}
				break;
			default:
				if ( \is_numeric( $this->get_settings('link_to') ) ) {
					$args .= ' return="' . get_permalink( $this->get_settings('link_to') ) . '"';
				}
				break;
		}
		$args .= ( $this->get_settings('submit_value') ) ? ' submit_value="'. $this->get_settings('submit_value') . '"' : '';
		$args .= ( $this->get_settings('updated_message') ) ? ' updated_message="'. $this->get_settings('updated_message') . '"' : '';
		$args .= ( $this->get_settings('kses') != 'yes' ) ? ' kses="No"' : '';

		/**
		 * Fields section
		 */
		$args .= ( $this->get_settings('show_title') ) ? ' post_title="Yes"' : '';
		$args .= ( $this->get_settings('show_content') ) ? ' post_content="Yes"' : '';
		//
		$args .= ( $this->get_settings('field_el') != 'div' ) ? ' field_el="'. $this->get_settings('field_el').'"' : '';
		$args .= ( $this->get_settings('label_placement') != 'top' ) ? ' label_placement="'.$this->get_settings('label_placement').'"' : '';
		$args .= ( $this->get_settings('instruction_placement') != 'label' ) ? ' instruction_placement="'.$this->get_settings('instruction_placement').'"' : '';
		$args .= ( $this->get_settings('uploader') != 'wp' ) ? ' uploader="'.$this->get_settings('uploader').'"' : '';

		$field_group_ids = [];
		$field_ids = [];

		if ( $this->get_settings('field_groups') ) {

			$fgs = $this->get_settings('field_groups');

			$args .= ' field_groups="';

			foreach ( $fgs as $fg ) {
				$args .= $fg . ',';
				$field_group_ids[] = $fg;
			}
			$args = rtrim($args,",") . '"';
		}

		if ( $this->get_settings('fields') ) {

			$fs = $this->get_settings('fields');

			$args .= ' fields="';

			foreach ( $fs as $f ) {
				$args .= $f . ',';
				$field_ids[] = $f;
			}
			$args = rtrim($args,",") . '"';
		}
		$args .= ( $this->get_settings('honeypot') != 'yes' ) ? ' honeypot="No"' : '';

		/**
		 * Table Section
		 */
		if ( $this->get_settings('table_el') == 'yes' ) {

			$args .= ' table_el="Yes"';

			/**
			 * Table header columns
			 */
			if ( $this->get_settings('thead_default_labels' ) ){

				$args .= ' thead="@"';

			}elseif ( $this->get_settings('thead') ) {

				$fs = explode( "\n", $this->get_settings('thead') );

				$args .= ' thead="';

				foreach ( $fs as $f ) {
					$args .= $f . ',';
				}
				$args = rtrim($args,",") . '"';
			}
			/**
			 * Table header columns
			 */
			if ( $this->get_settings('tfoot_default_labels' ) ){

				$args .= ' tfoot="@"';

			}elseif ( $this->get_settings('tfoot') ) {

				$fs = \explode( "\n", $this->get_settings('tfoot') );

				$args .= ' tfoot="';

				foreach ( $fs as $f ) {
					$args .= $f . ',';
				}
				$args = rtrim($args,",") . '"';
			}
		}

		/**
		 * Authorizations section
		 */
		if ( $this->get_settings('logged_in') == 'yes' ) {

			$args .= ' logged_in="Yes"';

			if ( $this->get_settings('roles') ) {

				$fs = $this->get_settings('roles');

				$args .= ' roles="';

				foreach ( $fs as $f ) {
					$args .= $f . ',';
				}
				$args = rtrim($args,",") . '"';
			}

			if ( $this->get_settings('users') ) {

				$fs = $this->get_settings('users');

				$args .= ' users="';

				foreach ( $fs as $f ) {
					$args .= $f . ',';
				}
				$args = rtrim($args,",") . '"';
			}

		}

		//
		$args .= ']';

		$enclosed = false;

		// html_submit_button
		if ( $this->get_settings('html_submit_button') && $this->enable_advanced ){

			$args .= '[submit_button]' . $this->get_settings('html_submit_button') . '[/submit_button]';
			$enclosed = true;
		}

		// html_before_fields
		if ( $this->get_settings( 'html_before_fields' ) && $this->enable_advanced ){

			$args .= '[before_fields]' . $this->get_settings('html_before_fields') . '[/before_fields]';
			$enclosed = true;

		}
		// html_after_fields
		if ( $this->get_settings( 'html_after_fields' ) && $this->enable_advanced ){

			$args .= '[after_fields]' . $this->get_settings('html_after_fields') . '[/after_fields]';
			$enclosed = true;

		}
		// updated_message
		if ( $this->get_settings( 'updated_message' ) && $this->enable_advanced ){

			$args .= '[updated_message]' . $this->get_settings('updated_message') . '[/updated_message]';
			$enclosed = true;

		}
		// html_submit_spinner
		if ( $this->get_settings( 'html_submit_spinner' ) && $this->enable_advanced ){

			$args .= '[submit_spinner]' . $this->get_settings('html_submit_spinner') . '[/submit_spinner]';
			$enclosed = true;

		}

		if ( $enclosed ) $args .= '[/acf_front_form]';

		return $args;

	}
	/**
	 * Render shortcode widget as plain content.
	 *
	 * Override the default behavior by printing the shortcode insted of rendering it.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function render_plain_content() {
		// In plain mode, render without shortcode
		echo $this->get_shortcode();
	}


	protected function _content_template() {}
}
