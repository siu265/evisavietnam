<?php

namespace IMMIGROPLUGIN\Element;

use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Border;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Plugin;

/**
 * Elementor button widget.
 * Elementor widget that displays a button with the ability to control every
 * aspect of the button design.
 *
 * @since 1.0.0
 */
class Tab_Country extends Widget_Base {

	/**
	 * Get widget name.
	 * Retrieve button widget name.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'immigro_tab_country';
	}

	/**
	 * Get widget title.
	 * Retrieve button widget title.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Tab Country', 'immigro' );
	}

	/**
	 * Get widget icon.
	 * Retrieve button widget icon.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'fa fa-briefcase';
	}

	/**
	 * Get widget categories.
	 * Retrieve the list of categories the button widget belongs to.
	 * Used to determine where to display the widget in the editor.
	 *
	 * @since  2.0.0
	 * @access public
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'immigro' ];
	}
	
	/**
	 * Register button widget controls.
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'tab_country',
			[
				'label' => esc_html__( 'Tab Country', 'immigro' ),
			]
		);

				
		$this->add_control(
			'sec_class',
			[
				'label'       => __( 'Section Class', 'rashid' ),
				'type'        => Controls_Manager::TEXTAREA,
				'dynamic'     => [
					'active' => true,
				],
				'placeholder' => __( 'Enter Section Class', 'rashid' ),
			]
		);	


		$this->end_controls_section();
		
		// New Tab#1

		$this->start_controls_section(
					'content_section',
					[
						'label' => __( 'Feature Block', 'rashid' ),
						'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
					]
				);

				
				$this->add_control(
				  'repeat', 
					[
						'type' => Controls_Manager::REPEATER,
						'seperator' => 'before',
						'default' => 
							[
								['block_title' => esc_html__('Projects Completed', 'rashid')],
							],
						'fields' => 
							[		
																	
										'block_title'=>	
									[
										'name' => 'block_title',
										'label' => esc_html__('Title', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('', 'rashid')
									],

									'block_title1'=>	
									[
										'name' => 'block_title1',
										'label' => esc_html__('Title', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('', 'rashid')
									],
									
										'block_text'=>
									
									[
										'name' => 'block_text',
										'label' => esc_html__('Text', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('', 'rashid')
									],	

									'block_image'=>

									[
										'name' => 'block_image',
										'label' => __( 'Image', 'rashid' ),
										'type' => Controls_Manager::MEDIA,
										'default' => ['url' => Utils::get_placeholder_image_src(),],
									  ],	

									  'block_alt_text'=>

									  [
										'name' => 'block_alt_text',
										'label' => esc_html__('Alt Text', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('iamge', 'rashid')
									],

                                    'block_btnlink'=>

                                    [
                                        'name' => 'block_btnlink',
                                        'label' => __( 'Button Url', 'rashid' ),
                                        'type' => Controls_Manager::URL,
                                        'placeholder' => __( 'https://your-link.com', 'rashid' ),
                                        'show_external' => true,
                                        'default' => [
                                          'url' => '',
                                          'is_external' => true,
                                          'nofollow' => true,
                                        ],
                                     ],

									// content-2

									'block_title2'=>	
									[
										'name' => 'block_title2',
										'label' => esc_html__('Title', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('', 'rashid')
									],
									
										'block_text1'=>
									
									[
										'name' => 'block_text1',
										'label' => esc_html__('Text', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('', 'rashid')
									],	

									'block_image1'=>

									[
										'name' => 'block_image1',
										'label' => __( 'Image', 'rashid' ),
										'type' => Controls_Manager::MEDIA,
										'default' => ['url' => Utils::get_placeholder_image_src(),],
									  ],	

									  'block_alt_text1'=>

									  [
										'name' => 'block_alt_text1',
										'label' => esc_html__('Alt Text', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('iamge', 'rashid')
									],
									
									'block_btnlink1'=>

                                    [
                                        'name' => 'block_btnlink1',
                                        'label' => __( 'Button Url', 'rashid' ),
                                        'type' => Controls_Manager::URL,
                                        'placeholder' => __( 'https://your-link.com', 'rashid' ),
                                        'show_external' => true,
                                        'default' => [
                                          'url' => '',
                                          'is_external' => true,
                                          'nofollow' => true,
                                        ],
                                     ],

									// content-3

									'block_title3'=>	
									[
										'name' => 'block_title3',
										'label' => esc_html__('Title', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('', 'rashid')
									],
									
										'block_text2'=>
									
									[
										'name' => 'block_text2',
										'label' => esc_html__('Text', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('', 'rashid')
									],	

									'block_image2'=>

									[
										'name' => 'block_image2',
										'label' => __( 'Image', 'rashid' ),
										'type' => Controls_Manager::MEDIA,
										'default' => ['url' => Utils::get_placeholder_image_src(),],
									  ],	

									  'block_alt_text2'=>

									  [
										'name' => 'block_alt_text2',
										'label' => esc_html__('Alt Text', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('iamge', 'rashid')
									],
									

                                    'block_btnlink2'=>

                                    [
                                        'name' => 'block_btnlink2',
                                        'label' => __( 'Button Url', 'rashid' ),
                                        'type' => Controls_Manager::URL,
                                        'placeholder' => __( 'https://your-link.com', 'rashid' ),
                                        'show_external' => true,
                                        'default' => [
                                          'url' => '',
                                          'is_external' => true,
                                          'nofollow' => true,
                                        ],
                                     ],

									// content-4

									'block_title4'=>	
									[
										'name' => 'block_title4',
										'label' => esc_html__('Title', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('', 'rashid')
									],
									
										'block_text3'=>
									
									[
										'name' => 'block_text3',
										'label' => esc_html__('Text', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('', 'rashid')
									],	

									'block_image3'=>

									[
										'name' => 'block_image3',
										'label' => __( 'Image', 'rashid' ),
										'type' => Controls_Manager::MEDIA,
										'default' => ['url' => Utils::get_placeholder_image_src(),],
									  ],	

									  'block_alt_text3'=>

									  [
										'name' => 'block_alt_text3',
										'label' => esc_html__('Alt Text', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('iamge', 'rashid')
									],

                                    'block_btnlink3'=>

                                    [
                                        'name' => 'block_btnlink3',
                                        'label' => __( 'Button Url', 'rashid' ),
                                        'type' => Controls_Manager::URL,
                                        'placeholder' => __( 'https://your-link.com', 'rashid' ),
                                        'show_external' => true,
                                        'default' => [
                                          'url' => '',
                                          'is_external' => true,
                                          'nofollow' => true,
                                        ],
                                     ],

									// content-5

									'block_title5'=>	
									[
										'name' => 'block_title5',
										'label' => esc_html__('Title', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('', 'rashid')
									],
									
										'block_text4'=>
									
									[
										'name' => 'block_text4',
										'label' => esc_html__('Text', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('', 'rashid')
									],	

									'block_image4'=>

									[
										'name' => 'block_image4',
										'label' => __( 'Image', 'rashid' ),
										'type' => Controls_Manager::MEDIA,
										'default' => ['url' => Utils::get_placeholder_image_src(),],
									  ],	

									  'block_alt_text4'=>

									  [
										'name' => 'block_alt_text4',
										'label' => esc_html__('Alt Text', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('iamge', 'rashid')
									],

                                    'block_btnlink4'=>

                                    [
                                        'name' => 'block_btnlink4',
                                        'label' => __( 'Button Url', 'rashid' ),
                                        'type' => Controls_Manager::URL,
                                        'placeholder' => __( 'https://your-link.com', 'rashid' ),
                                        'show_external' => true,
                                        'default' => [
                                          'url' => '',
                                          'is_external' => true,
                                          'nofollow' => true,
                                        ],
                                     ],

									// content-6

									'block_title6'=>	
									[
										'name' => 'block_title6',
										'label' => esc_html__('Title', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('', 'rashid')
									],
									
										'block_text5'=>
									
									[
										'name' => 'block_text5',
										'label' => esc_html__('Text', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('', 'rashid')
									],	

									'block_image5'=>

									[
										'name' => 'block_image5',
										'label' => __( 'Image', 'rashid' ),
										'type' => Controls_Manager::MEDIA,
										'default' => ['url' => Utils::get_placeholder_image_src(),],
									  ],	

									  'block_alt_text5'=>

									  [
										'name' => 'block_alt_text5',
										'label' => esc_html__('Alt Text', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('iamge', 'rashid')
									],

                                    'block_btnlink5'=>

                                    [
                                        'name' => 'block_btnlink5',
                                        'label' => __( 'Button Url', 'rashid' ),
                                        'type' => Controls_Manager::URL,
                                        'placeholder' => __( 'https://your-link.com', 'rashid' ),
                                        'show_external' => true,
                                        'default' => [
                                          'url' => '',
                                          'is_external' => true,
                                          'nofollow' => true,
                                        ],
                                     ],

									// content-7

									'block_title7'=>	
									[
										'name' => 'block_title7',
										'label' => esc_html__('Title', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('', 'rashid')
									],
									
										'block_text6'=>
									
									[
										'name' => 'block_text6',
										'label' => esc_html__('Text', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('', 'rashid')
									],	

									'block_image6'=>

									[
										'name' => 'block_image6',
										'label' => __( 'Image', 'rashid' ),
										'type' => Controls_Manager::MEDIA,
										'default' => ['url' => Utils::get_placeholder_image_src(),],
									  ],	

									  'block_alt_text6'=>

									  [
										'name' => 'block_alt_text6',
										'label' => esc_html__('Alt Text', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('iamge', 'rashid')
									],

                                    'block_btnlink6'=>

                                    [
                                        'name' => 'block_btnlink6',
                                        'label' => __( 'Button Url', 'rashid' ),
                                        'type' => Controls_Manager::URL,
                                        'placeholder' => __( 'https://your-link.com', 'rashid' ),
                                        'show_external' => true,
                                        'default' => [
                                          'url' => '',
                                          'is_external' => true,
                                          'nofollow' => true,
                                        ],
                                     ],

									// content-8

									'block_title8'=>	
									[
										'name' => 'block_title8',
										'label' => esc_html__('Title', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('', 'rashid')
									],
									
										'block_text7'=>
									
									[
										'name' => 'block_text7',
										'label' => esc_html__('Text', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('', 'rashid')
									],	

									'block_image7'=>

									[
										'name' => 'block_image7',
										'label' => __( 'Image', 'rashid' ),
										'type' => Controls_Manager::MEDIA,
										'default' => ['url' => Utils::get_placeholder_image_src(),],
									  ],	

									  'block_alt_text7'=>

									  [
										'name' => 'block_alt_text7',
										'label' => esc_html__('Alt Text', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('iamge', 'rashid')
									],


                                    'block_btnlink7'=>

                                    [
                                        'name' => 'block_btnlink7',
                                        'label' => __( 'Button Url', 'rashid' ),
                                        'type' => Controls_Manager::URL,
                                        'placeholder' => __( 'https://your-link.com', 'rashid' ),
                                        'show_external' => true,
                                        'default' => [
                                          'url' => '',
                                          'is_external' => true,
                                          'nofollow' => true,
                                        ],
                                     ],

									// content-9

									'block_title9'=>	
									[
										'name' => 'block_title9',
										'label' => esc_html__('Title', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('', 'rashid')
									],
									
										'block_text8'=>
									
									[
										'name' => 'block_text8',
										'label' => esc_html__('Text', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('', 'rashid')
									],	

									'block_image8'=>

									[
										'name' => 'block_image8',
										'label' => __( 'Image', 'rashid' ),
										'type' => Controls_Manager::MEDIA,
										'default' => ['url' => Utils::get_placeholder_image_src(),],
									  ],	

									  'block_alt_text8'=>

									  [
										'name' => 'block_alt_text8',
										'label' => esc_html__('Alt Text', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('iamge', 'rashid')
									],

                                    'block_btnlink8'=>

                                    [
                                        'name' => 'block_btnlink8',
                                        'label' => __( 'Button Url', 'rashid' ),
                                        'type' => Controls_Manager::URL,
                                        'placeholder' => __( 'https://your-link.com', 'rashid' ),
                                        'show_external' => true,
                                        'default' => [
                                          'url' => '',
                                          'is_external' => true,
                                          'nofollow' => true,
                                        ],
                                     ],

									// content-10

									'block_title10'=>	
									[
										'name' => 'block_title10',
										'label' => esc_html__('Title', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('', 'rashid')
									],
									
										'block_text9'=>
									
									[
										'name' => 'block_text9',
										'label' => esc_html__('Text', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('', 'rashid')
									],	

									'block_image9'=>

									[
										'name' => 'block_image9',
										'label' => __( 'Image', 'rashid' ),
										'type' => Controls_Manager::MEDIA,
										'default' => ['url' => Utils::get_placeholder_image_src(),],
									  ],	

									  'block_alt_text9'=>

									  [
										'name' => 'block_alt_text9',
										'label' => esc_html__('Alt Text', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('iamge', 'rashid')
									],

                                    'block_btnlink9'=>

                                    [
                                        'name' => 'block_btnlink9',
                                        'label' => __( 'Button Url', 'rashid' ),
                                        'type' => Controls_Manager::URL,
                                        'placeholder' => __( 'https://your-link.com', 'rashid' ),
                                        'show_external' => true,
                                        'default' => [
                                          'url' => '',
                                          'is_external' => true,
                                          'nofollow' => true,
                                        ],
                                     ],

									// content-11

									'block_title11'=>	
									[
										'name' => 'block_title11',
										'label' => esc_html__('Title', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('', 'rashid')
									],
									
										'block_text10'=>
									
									[
										'name' => 'block_text10',
										'label' => esc_html__('Text', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('', 'rashid')
									],	

									'block_image10'=>

									[
										'name' => 'block_image10',
										'label' => __( 'Image', 'rashid' ),
										'type' => Controls_Manager::MEDIA,
										'default' => ['url' => Utils::get_placeholder_image_src(),],
									  ],	

									  'block_alt_text10'=>

									  [
										'name' => 'block_alt_text10',
										'label' => esc_html__('Alt Text', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('iamge', 'rashid')
									],

                                    'block_btnlink10'=>

                                    [
                                        'name' => 'block_btnlink10',
                                        'label' => __( 'Button Url', 'rashid' ),
                                        'type' => Controls_Manager::URL,
                                        'placeholder' => __( 'https://your-link.com', 'rashid' ),
                                        'show_external' => true,
                                        'default' => [
                                          'url' => '',
                                          'is_external' => true,
                                          'nofollow' => true,
                                        ],
                                     ],

									// content-12

									'block_title12'=>	
									[
										'name' => 'block_title12',
										'label' => esc_html__('Title', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('', 'rashid')
									],
									
										'block_text11'=>
									
									[
										'name' => 'block_text11',
										'label' => esc_html__('Text', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('', 'rashid')
									],	

									'block_image11'=>

									[
										'name' => 'block_image11',
										'label' => __( 'Image', 'rashid' ),
										'type' => Controls_Manager::MEDIA,
										'default' => ['url' => Utils::get_placeholder_image_src(),],
									  ],	

									  'block_alt_text11'=>

									  [
										'name' => 'block_alt_text11',
										'label' => esc_html__('Alt Text', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('iamge', 'rashid')
									],

                                    'block_btnlink11'=>

                                    [
                                        'name' => 'block_btnlink11',
                                        'label' => __( 'Button Url', 'rashid' ),
                                        'type' => Controls_Manager::URL,
                                        'placeholder' => __( 'https://your-link.com', 'rashid' ),
                                        'show_external' => true,
                                        'default' => [
                                          'url' => '',
                                          'is_external' => true,
                                          'nofollow' => true,
                                        ],
                                     ],

									// content-13

									'block_title13'=>	
									[
										'name' => 'block_title13',
										'label' => esc_html__('Title', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('', 'rashid')
									],
									
										'block_text12'=>
									
									[
										'name' => 'block_text12',
										'label' => esc_html__('Text', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('', 'rashid')
									],	

									'block_image12'=>

									[
										'name' => 'block_image12',
										'label' => __( 'Image', 'rashid' ),
										'type' => Controls_Manager::MEDIA,
										'default' => ['url' => Utils::get_placeholder_image_src(),],
									  ],	

									  'block_alt_text12'=>

									  [
										'name' => 'block_alt_text12',
										'label' => esc_html__('Alt Text', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('iamge', 'rashid')
									],

                                    'block_btnlink12'=>

                                    [
                                        'name' => 'block_btnlink12',
                                        'label' => __( 'Button Url', 'rashid' ),
                                        'type' => Controls_Manager::URL,
                                        'placeholder' => __( 'https://your-link.com', 'rashid' ),
                                        'show_external' => true,
                                        'default' => [
                                          'url' => '',
                                          'is_external' => true,
                                          'nofollow' => true,
                                        ],
                                     ],

									// content-14

									'block_title14'=>	
									[
										'name' => 'block_title14',
										'label' => esc_html__('Title', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('', 'rashid')
									],
									
										'block_text13'=>
									
									[
										'name' => 'block_text13',
										'label' => esc_html__('Text', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('', 'rashid')
									],	

									'block_image13'=>

									[
										'name' => 'block_image13',
										'label' => __( 'Image', 'rashid' ),
										'type' => Controls_Manager::MEDIA,
										'default' => ['url' => Utils::get_placeholder_image_src(),],
									  ],	

									  'block_alt_text13'=>

									  [
										'name' => 'block_alt_text13',
										'label' => esc_html__('Alt Text', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('iamge', 'rashid')
									],

                                    'block_btnlink13'=>

                                    [
                                        'name' => 'block_btnlink13',
                                        'label' => __( 'Button Url', 'rashid' ),
                                        'type' => Controls_Manager::URL,
                                        'placeholder' => __( 'https://your-link.com', 'rashid' ),
                                        'show_external' => true,
                                        'default' => [
                                          'url' => '',
                                          'is_external' => true,
                                          'nofollow' => true,
                                        ],
                                     ],

									// content-15

									'block_title15'=>	
									[
										'name' => 'block_title15',
										'label' => esc_html__('Title', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('', 'rashid')
									],
									
										'block_text14'=>
									
									[
										'name' => 'block_text14',
										'label' => esc_html__('Text', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('', 'rashid')
									],	

									'block_image14'=>

									[
										'name' => 'block_image14',
										'label' => __( 'Image', 'rashid' ),
										'type' => Controls_Manager::MEDIA,
										'default' => ['url' => Utils::get_placeholder_image_src(),],
									  ],	

									  'block_alt_text14'=>

									  [
										'name' => 'block_alt_text14',
										'label' => esc_html__('Alt Text', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('iamge', 'rashid')
									],

                                    'block_btnlink14'=>

                                    [
                                        'name' => 'block_btnlink14',
                                        'label' => __( 'Button Url', 'rashid' ),
                                        'type' => Controls_Manager::URL,
                                        'placeholder' => __( 'https://your-link.com', 'rashid' ),
                                        'show_external' => true,
                                        'default' => [
                                          'url' => '',
                                          'is_external' => true,
                                          'nofollow' => true,
                                        ],
                                     ],

									// content-16

									'block_title16'=>	
									[
										'name' => 'block_title16',
										'label' => esc_html__('Title', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('', 'rashid')
									],
									
										'block_text15'=>
									
									[
										'name' => 'block_text15',
										'label' => esc_html__('Text', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('', 'rashid')
									],	

									'block_image15'=>

									[
										'name' => 'block_image15',
										'label' => __( 'Image', 'rashid' ),
										'type' => Controls_Manager::MEDIA,
										'default' => ['url' => Utils::get_placeholder_image_src(),],
									  ],	

									  'block_alt_text15'=>

									  [
										'name' => 'block_alt_text15',
										'label' => esc_html__('Alt Text', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('iamge', 'rashid')
									],

                                    'block_btnlink15'=>

                                    [
                                        'name' => 'block_btnlink15',
                                        'label' => __( 'Button Url', 'rashid' ),
                                        'type' => Controls_Manager::URL,
                                        'placeholder' => __( 'https://your-link.com', 'rashid' ),
                                        'show_external' => true,
                                        'default' => [
                                          'url' => '',
                                          'is_external' => true,
                                          'nofollow' => true,
                                        ],
                                     ],



							],
						'title_field' => '{{block_title}}',
					 ]
			);
				
				
		$this->end_controls_section();	
					
		
		}

	/**
	 * Render button widget output on the frontend.
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	 
	protected function render() {
		$settings = $this->get_settings_for_display();
		$allowed_tags = wp_kses_allowed_html('post');
		?>
        
		
	
		<section class="country-page-section p_relative centred">
            <div class="tabs-box">
                <div class="tab-btn-box p_relative d_block centred mb_150">
                    <div class="auto-container">
                        <ul class="tab-btns tab-buttons clearfix">
							<?php foreach($settings['repeat'] as $key=>$item):?>
                                <li class="tab-btn <?php if($key == 1) echo 'active-btn';?>" data-tab="#tab-<?php echo esc_attr($key+5);?>">
                                    <?php echo wp_kses($item['block_title'], $allowed_tags);?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
                <div class="auto-container">

                    <div class="tabs-content">
					<?php foreach($settings['repeat'] as $key=>$item):?>
                        <div class="tab <?php if($key == 1) echo 'active-tab';?>" id="tab-<?php echo esc_attr($key+5);?>">
                            <div class="row clearfix">
                                <div class="col-lg-3 col-md-6 col-sm-12 counter-block">
                                    <div class="country-block-two">
                                        <div class="inner-box">
                                            <div class="flag">
												<?php if(wp_get_attachment_url($item['block_image']['id'])): ?>
                                                <img src="<?php echo wp_get_attachment_url($item['block_image']['id']);?>" alt="<?php echo wp_kses($item['block_alt_text'], $allowed_tags);?>">
                                                <?php else :?>
                                                <div class="noimage"></div>
                                                <?php endif;?>
											</div>
											<h4><a href="<?php echo esc_url($item['block_btnlink']['url']);?>"><?php echo wp_kses($item['block_title1'], $allowed_tags);?></a></h4>
                                            <p><?php echo wp_kses($item['block_text'], $allowed_tags);?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-12 counter-block">
                                    <div class="country-block-two">
										<div class="inner-box">
                                            <div class="flag">
												<?php if(wp_get_attachment_url($item['block_image1']['id'])): ?>
                                                <img src="<?php echo wp_get_attachment_url($item['block_image1']['id']);?>" alt="<?php echo wp_kses($item['block_alt_text1'], $allowed_tags);?>">
                                                <?php else :?>
                                                <div class="noimage"></div>
                                                <?php endif;?>
											</div>
                                            <h4><a href="<?php echo esc_url($item['block_btnlink1']['url']);?>"><?php echo wp_kses($item['block_title2'], $allowed_tags);?></a></h4>
                                            <p><?php echo wp_kses($item['block_text1'], $allowed_tags);?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-12 counter-block">
                                    <div class="country-block-two">
										<div class="inner-box">
                                            <div class="flag">
												<?php if(wp_get_attachment_url($item['block_image2']['id'])): ?>
                                                <img src="<?php echo wp_get_attachment_url($item['block_image2']['id']);?>" alt="<?php echo wp_kses($item['block_alt_text2'], $allowed_tags);?>">
                                                <?php else :?>
                                                <div class="noimage"></div>
                                                <?php endif;?>
											</div>
                                            <h4><a href="<?php echo esc_url($item['block_btnlink2']['url']);?>"><?php echo wp_kses($item['block_title3'], $allowed_tags);?></a></h4>
                                            <p><?php echo wp_kses($item['block_text2'], $allowed_tags);?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-12 counter-block">
                                    <div class="country-block-two">
										<div class="inner-box">
                                            <div class="flag">
												<?php if(wp_get_attachment_url($item['block_image3']['id'])): ?>
                                                <img src="<?php echo wp_get_attachment_url($item['block_image3']['id']);?>" alt="<?php echo wp_kses($item['block_alt_text3'], $allowed_tags);?>">
                                                <?php else :?>
                                                <div class="noimage"></div>
                                                <?php endif;?>
											</div>
                                            <h4><a href="<?php echo esc_url($item['block_btnlink3']['url']);?>"><?php echo wp_kses($item['block_title4'], $allowed_tags);?></a></h4>
                                            <p><?php echo wp_kses($item['block_text3'], $allowed_tags);?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-12 counter-block">
                                    <div class="country-block-two">
										<div class="inner-box">
                                            <div class="flag">
												<?php if(wp_get_attachment_url($item['block_image4']['id'])): ?>
                                                <img src="<?php echo wp_get_attachment_url($item['block_image4']['id']);?>" alt="<?php echo wp_kses($item['block_alt_text4'], $allowed_tags);?>">
                                                <?php else :?>
                                                <div class="noimage"></div>
                                                <?php endif;?>
											</div>
                                            <h4><a href="<?php echo esc_url($item['block_btnlink4']['url']);?>"><?php echo wp_kses($item['block_title5'], $allowed_tags);?></a></h4>
                                            <p><?php echo wp_kses($item['block_text4'], $allowed_tags);?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-12 counter-block">
                                    <div class="country-block-two">
										<div class="inner-box">
                                            <div class="flag">
												<?php if(wp_get_attachment_url($item['block_image5']['id'])): ?>
                                                <img src="<?php echo wp_get_attachment_url($item['block_image5']['id']);?>" alt="<?php echo wp_kses($item['block_alt_text5'], $allowed_tags);?>">
                                                <?php else :?>
                                                <div class="noimage"></div>
                                                <?php endif;?>
											</div>
                                            <h4><a href="<?php echo esc_url($item['block_btnlink5']['url']);?>"><?php echo wp_kses($item['block_title6'], $allowed_tags);?></a></h4>
                                            <p><?php echo wp_kses($item['block_text5'], $allowed_tags);?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-12 counter-block">
                                    <div class="country-block-two">
										<div class="inner-box">
                                            <div class="flag">
												<?php if(wp_get_attachment_url($item['block_image6']['id'])): ?>
                                                <img src="<?php echo wp_get_attachment_url($item['block_image6']['id']);?>" alt="<?php echo wp_kses($item['block_alt_text6'], $allowed_tags);?>">
                                                <?php else :?>
                                                <div class="noimage"></div>
                                                <?php endif;?>
											</div>
                                            <h4><a href="<?php echo esc_url($item['block_btnlink6']['url']);?>"><?php echo wp_kses($item['block_title7'], $allowed_tags);?></a></h4>
                                            <p><?php echo wp_kses($item['block_text6'], $allowed_tags);?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-12 counter-block">
                                    <div class="country-block-two">
										<div class="inner-box">
                                            <div class="flag">
												<?php if(wp_get_attachment_url($item['block_image7']['id'])): ?>
                                                <img src="<?php echo wp_get_attachment_url($item['block_image7']['id']);?>" alt="<?php echo wp_kses($item['block_alt_text7'], $allowed_tags);?>">
                                                <?php else :?>
                                                <div class="noimage"></div>
                                                <?php endif;?>
											</div>
                                            <h4><a href="<?php echo esc_url($item['block_btnlink7']['url']);?>"><?php echo wp_kses($item['block_title8'], $allowed_tags);?></a></h4>
                                            <p><?php echo wp_kses($item['block_text7'], $allowed_tags);?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-12 counter-block">
                                    <div class="country-block-two">
										<div class="inner-box">
                                            <div class="flag">
												<?php if(wp_get_attachment_url($item['block_image8']['id'])): ?>
                                                <img src="<?php echo wp_get_attachment_url($item['block_image8']['id']);?>" alt="<?php echo wp_kses($item['block_alt_text8'], $allowed_tags);?>">
                                                <?php else :?>
                                                <div class="noimage"></div>
                                                <?php endif;?>
											</div>
                                            <h4><a href="<?php echo esc_url($item['block_btnlink8']['url']);?>"><?php echo wp_kses($item['block_title9'], $allowed_tags);?></a></h4>
                                            <p><?php echo wp_kses($item['block_text8'], $allowed_tags);?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-12 counter-block">
                                    <div class="country-block-two">
										<div class="inner-box">
                                            <div class="flag">
												<?php if(wp_get_attachment_url($item['block_image9']['id'])): ?>
                                                <img src="<?php echo wp_get_attachment_url($item['block_image9']['id']);?>" alt="<?php echo wp_kses($item['block_alt_text9'], $allowed_tags);?>">
                                                <?php else :?>
                                                <div class="noimage"></div>
                                                <?php endif;?>
											</div>
                                            <h4><a href="<?php echo esc_url($item['block_btnlink9']['url']);?>"><?php echo wp_kses($item['block_title10'], $allowed_tags);?></a></h4>
                                            <p><?php echo wp_kses($item['block_text9'], $allowed_tags);?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-12 counter-block">
                                    <div class="country-block-two">
										<div class="inner-box">
                                            <div class="flag">
												<?php if(wp_get_attachment_url($item['block_image10']['id'])): ?>
                                                <img src="<?php echo wp_get_attachment_url($item['block_image10']['id']);?>" alt="<?php echo wp_kses($item['block_alt_text10'], $allowed_tags);?>">
                                                <?php else :?>
                                                <div class="noimage"></div>
                                                <?php endif;?>
											</div>
                                            <h4><a href="<?php echo esc_url($item['block_btnlink10']['url']);?>"><?php echo wp_kses($item['block_title11'], $allowed_tags);?></a></h4>
                                            <p><?php echo wp_kses($item['block_text10'], $allowed_tags);?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-12 counter-block">
                                    <div class="country-block-two">
										<div class="inner-box">
                                            <div class="flag">
												<?php if(wp_get_attachment_url($item['block_image11']['id'])): ?>
                                                <img src="<?php echo wp_get_attachment_url($item['block_image11']['id']);?>" alt="<?php echo wp_kses($item['block_alt_text11'], $allowed_tags);?>">
                                                <?php else :?>
                                                <div class="noimage"></div>
                                                <?php endif;?>
											</div>
                                            <h4><a href="<?php echo esc_url($item['block_btnlink11']['url']);?>"><?php echo wp_kses($item['block_title12'], $allowed_tags);?></a></h4>
                                            <p><?php echo wp_kses($item['block_text11'], $allowed_tags);?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-12 counter-block">
                                    <div class="country-block-two">
										<div class="inner-box">
                                            <div class="flag">
												<?php if(wp_get_attachment_url($item['block_image12']['id'])): ?>
                                                <img src="<?php echo wp_get_attachment_url($item['block_image12']['id']);?>" alt="<?php echo wp_kses($item['block_alt_text12'], $allowed_tags);?>">
                                                <?php else :?>
                                                <div class="noimage"></div>
                                                <?php endif;?>
											</div>
                                            <h4><a href="<?php echo esc_url($item['block_btnlink12']['url']);?>"><?php echo wp_kses($item['block_title13'], $allowed_tags);?></a></h4>
                                            <p><?php echo wp_kses($item['block_text12'], $allowed_tags);?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-12 counter-block">
                                    <div class="country-block-two">
										<div class="inner-box">
                                            <div class="flag">
												<?php if(wp_get_attachment_url($item['block_image13']['id'])): ?>
                                                <img src="<?php echo wp_get_attachment_url($item['block_image13']['id']);?>" alt="<?php echo wp_kses($item['block_alt_text13'], $allowed_tags);?>">
                                                <?php else :?>
                                                <div class="noimage"></div>
                                                <?php endif;?>
											</div>
                                            <h4><a href="<?php echo esc_url($item['block_btnlink13']['url']);?>"><?php echo wp_kses($item['block_title14'], $allowed_tags);?></a></h4>
                                            <p><?php echo wp_kses($item['block_text13'], $allowed_tags);?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-12 counter-block">
                                    <div class="country-block-two">
										<div class="inner-box">
                                            <div class="flag">
												<?php if(wp_get_attachment_url($item['block_image14']['id'])): ?>
                                                <img src="<?php echo wp_get_attachment_url($item['block_image14']['id']);?>" alt="<?php echo wp_kses($item['block_alt_text14'], $allowed_tags);?>">
                                                <?php else :?>
                                                <div class="noimage"></div>
                                                <?php endif;?>
											</div>
                                            <h4><a href="<?php echo esc_url($item['block_btnlink14']['url']);?>"><?php echo wp_kses($item['block_title15'], $allowed_tags);?></a></h4>
                                            <p><?php echo wp_kses($item['block_text14'], $allowed_tags);?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-12 counter-block">
                                    <div class="country-block-two">
										<div class="inner-box">
                                            <div class="flag">
												<?php if(wp_get_attachment_url($item['block_image15']['id'])): ?>
                                                <img src="<?php echo wp_get_attachment_url($item['block_image15']['id']);?>" alt="<?php echo wp_kses($item['block_alt_text15'], $allowed_tags);?>">
                                                <?php else :?>
                                                <div class="noimage"></div>
                                                <?php endif;?>
											</div>
                                            <h4><a href="<?php echo esc_url($item['block_btnlink15']['url']);?>"><?php echo wp_kses($item['block_title16'], $allowed_tags);?></a></h4>
                                            <p><?php echo wp_kses($item['block_text15'], $allowed_tags);?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
					<?php endforeach; ?>
                    </div>
                </div>
            </div>
        </section>

   
		<?php 
	}

}