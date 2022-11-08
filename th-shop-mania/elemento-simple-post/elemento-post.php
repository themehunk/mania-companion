<?php
include_once 'post-setting.php';
// Elementor Classes.
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Color as Scheme_Color;

class elementoPostSimple extends Widget_Base
{
    public function get_name()
    {
        return 'elemento-post-simple';
    }
    private function postSetting()
    {
        return new elemento_post_simple();
    }
    public function get_title()
    {
        return __('Simple Posts', 'mania-companion');
    }

    public function get_icon()
    {
        return 'eicon-post-list';
    }

    public function get_categories()
    {
        return ['elemento-addon-simple-cate', 'prodect-shop-category'];
    }
    protected function register_controls()
    {
        $this->contentSetting();
        $this->titleANDexcerpt();
        $this->paginationControlls();
        $this->containerStyle();
        $this->titleStyle();
        $this->metaStyle();
        $this->excerptStyle();
        $this->readmoreStyle();
        $this->paginationControllsStyle();
    }

    // content general controlls register 
    protected function contentSetting()
    {
        $this->start_controls_section(
            'section_content',
            [
                'label' => "Layout",
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_responsive_control(
            'number_of_column',
            [
                'type'        => Controls_Manager::NUMBER,
                'label'       => __('Number of Column', 'mania-companion'),
                'devices' => ['desktop', 'tablet', 'mobile'],
                'min' => 1,
                'max' => 6,
                'desktop_default' => 3,
                'tablet_default' => 3,
                'mobile_default' => 2,
                'selectors' => [
                    '{{WRAPPER}} .elemento-post-layout-listGrid .elemento-post-layout-iteme' => 'width:  calc(100% / {{VALUE}});',
                ],
            ]
        );
        $this->add_control(
            'number_of_post',
            [
                'type'        => Controls_Manager::NUMBER,
                'label'       => __('Number Of Post', 'mania-companion'),
                'default'     => 3,
            ]
        );
        $this->add_control(
            'post_category',
            [
                'label'       => __('Select Post Category', 'mania-companion'),
                'type'        => Controls_Manager::SELECT2,
                'default'     => ['all'],
                'label_block' => false,
                'multiple'    => true,
                'options'     => elemento_post_simple::postcategory(),
            ]
        );
        $this->add_control(
            'post_show_by',
            [
                'label'   => __('Choose Option', 'mania-companion'),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'recent' => "Recent",
                    'rand' => "Random",
                    'title' => "Title",
                ],
                'default' => 'recent',
            ]
        );
        $this->end_controls_section();
    }
    protected function titleANDexcerpt()
    {
        $this->start_controls_section(
            'title_excerpt',
            [
                'label' => "Content",
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control(
            'post_title_tag',
            [
                'label'   => __('Title Html Tag', 'mania-companion'),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'h1' => __("H1", "mania-companion"),
                    'h2' => __("H2", "mania-companion"),
                    'h3' => __("H3", "mania-companion"),
                    'h4' => __("H4", "mania-companion"),
                    'h5' => __("H5", "mania-companion"),
                    'h6' => __("H6", "mania-companion"),
                    'p'  => __("P", "mania-companion"),
                ],
                'default' => 'h2',
                
                // ],
            ]
        );
        // excerpt 
        $this->add_control(
            'excerpt_anable',
            [
                'label'        => __('Excerpt', 'mania-companion'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Show', 'mania-companion'),
                'label_off'    => __('Hide', 'mania-companion'),
                'return_value' => 'on',
                'default'      => 'on',
                "separator" => "before"
            ]
        );
        $this->add_control(
            'excerpt_length',
            [
                'type'        => Controls_Manager::NUMBER,
                'label'       => __('Excerpt Length', 'mania-companion'),
                'min' => 1,
                'max' => 1000,
                'default' => 70,
                'condition' => [
                    'excerpt_anable' => 'on',
                ],
            ]
        );
        // meta data 
        $this->add_control(
            'post_meta_data',
            [
                'label'       => __('Meta Data', 'mania-companion'),
                'type'        => Controls_Manager::SELECT2,
                'default'     => ['date'],
                'label_block' => true,
                'multiple'    => true,
                'options'     => ["author" => "Author", 'date' => "Date", "comments" => "Comments"],
                "separator" => "before"
            ]
        );
        $this->add_control(
            'post_metadata_separator',
            [
                'label' => "Separator Between",
                'label_block' => false,
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => "|",
                'selectors' => [
                    '{{WRAPPER}} .elemento-post-content .elemento-post-meta-data span + span:before' => 'content: "{{VALUE}}";',
                ],
            ]
        );
        
        $this->add_control(
            'read_more_text',
            [
                'label' => __("Text", 'mania-companion'),
                'label_block' => false,
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => "Read More >>",
                
            ]
        );
        $this->add_control(
            'read_more_new_tab',
            [
                'label'        => __('Open In New Tab', 'mania-companion'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Yes', 'mania-companion'),
                'label_off'    => __('No', 'mania-companion'),
                'return_value' => 'on',
                'default'      => 'on',
                
            ]
        );
        $this->end_controls_section();
    }
    protected function paginationControlls()
    {
        $this->start_controls_section(
            'pagination_content',
            [
                'label' => "Pagination",
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'pagination_show',
            [
                'label'        => __('Pagination', 'mania-companion'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Show', 'mania-companion'),
                'label_off'    => __('Hide', 'mania-companion'),
                'return_value' => 'on',
                'default'      => 'off',
                "separator" => "before"
            ]
        );
        $this->add_control(
            'pagination_alignment',
            [
                'label' => __('Alignment', 'mania-companion'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'mania-companion'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'mania-companion'),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'flex-end' => [
                        'title' => __('Right', 'mania-companion'),
                        'icon' => 'eicon-h-align-right',
                    ]
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .elemento-addons-pagination' => 'justify-content: {{VALUE}};',
                ],
                'toggle' => true,
                'condition' => [
                    'pagination_show' => 'on'
                ],
            ]
        );
        $this->end_controls_section();
    }
    protected function containerStyle()
    {
        $this->start_controls_section(
            'container_style',
            [
                'label' => __('Box Style', 'mania-companion'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'container_padding',
            [
                'label' => __('Box Padding', 'mania-companion'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .elemento-post-content-all' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'top' => 0,
                'right' => 0,
                'bottom' => 0,
                'left' => 0,
                'unit' => 'px',
                'isLinked' => true,
            ]
        );
        $this->add_control(
            'content_padding',
            [
                'label' => __('Content Padding', 'mania-companion'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .elemento-post-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'top' => 10,
                'right' => 10,
                'bottom' => 10,
                'left' => 10,
                'unit' => 'px',
                'isLinked' => true,
                'separator' => "after"
            ]
        );
        $this->add_responsive_control(
            'box_content_spacing',
            [
                'label'     => __('Box Content Spacing', 'mania-companion'),
                'type'      => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .elemento-addons-simple-post .elemento-post-content' => 'grid-gap : {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'border',
                'label' => __('Border', 'mania-companion'),
                'selector' => '{{WRAPPER}} .elemento-post-content-all',
            ]
        );
        $this->add_control(
            'container_border_radius',
            [
                'label' => __('Border Radius', 'mania-companion'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .elemento-post-content-all' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'top' => 0,
                'right' => 0,
                'bottom' => 0,
                'left' => 0,
                'unit' => 'px',
                'isLinked' => true,
                // 'separator' => "after"
            ]
        );
        // normal and hover 
        $this->add_control(
            'container_background_color',
            [
                'label'     => __('Background Color', 'mania-companion'),
                'type'      => Controls_Manager::COLOR,
                "default"   => "#f9f9f9",
                'scheme'    => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elemento-post-layout-listGrid .elemento-post-layout-iteme > div' => 'background-color: {{VALUE}};',
                ],
            ]
        );


        $this->start_controls_tabs('post_box_shadow');
        $this->start_controls_tab(
            'post_box_shadow_normal',
            [
                'label'     => __('Normal', 'mania-companion'),
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'post_box_',
                'label' => __('Box Shadow', 'mania-companion'),
                'selector' => '{{WRAPPER}} .elemento-post-layout-listGrid .elemento-post-layout-iteme > div',
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'post_box_shadow_hover',
            [
                'label'     => __('Hover', 'mania-companion'),
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'post_box__hover',
                'label' => __('Box Shadow', 'mania-companion'),
                'selector' => '{{WRAPPER}} .elemento-post-layout-listGrid .elemento-post-layout-iteme > div:hover',
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();


        $this->end_controls_section();
    }
    protected function titleStyle()
    {
        $this->start_controls_section(
            'title_style',
            [
                'label' => __('Title', 'mania-companion'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .elemento-addons-layout-post .elemento-post-title',
                'fields_options' => [
                    'font_size' => [
                        'default' => [
                            'unit' => 'px',
                            'size' => '16',
                        ],
                    ],
                    
                    'font_weight' => [
                        'default' => 'bold',
                    ],
                    'font_family' => [
                        'default' => 'sans-serif'
                    ]
                ],
            ]
        );
       
        $this->start_controls_tabs('title_style_');
        $this->start_controls_tab(
            'title_style_normal',
            [
                'label'     => __('Normal', 'mania-companion'),
            ]
        );

        $this->add_control(
            'heading_color',
            [
                'label'     => __('Color', 'mania-companion'),
                'type'      => Controls_Manager::COLOR,
                "default"   => "#54595f",
                'scheme'    => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elemento-post-content .elemento-post-title' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'heading_style_hover',
            [
                'label'     => __('Hover', 'mania-companion'),
            ]
        );
        $this->add_control(
            'heading_hover_color',
            [
                'label'     => __('Color', 'mania-companion'),
                'type'      => Controls_Manager::COLOR,
                "default"   => "#383a3c",
                'scheme'    => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elemento-post-content .elemento-post-title:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }
    protected function metaStyle()
    {
        $this->start_controls_section(
            'meta_style',
            [
                'label' => __('Post Meta', 'mania-companion'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'meta_typography',
                'selector' => '{{WRAPPER}} .elemento-post-content .elemento-post-meta-data',
                'fields_options' => [
                    'font_size' => [
                        'default' => [
                            'unit' => 'px',
                            'size' => '12',
                        ],
                    ],
                    'font_family' => [
                        'default' => 'sans-serif'
                    ]
                ],
            ]
        );
        $this->add_control(
            'meta_color',
            [
                'label'     => __('Color', 'mania-companion'),
                'type'      => Controls_Manager::COLOR,
                "default"   => "#adadad",
                'scheme'    => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elemento-post-content .elemento-post-meta-data' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'separator_color',
            [
                'label'     => __('Separator Color', 'mania-companion'),
                'type'      => Controls_Manager::COLOR,
                "default"   => "#adadad",
                'scheme'    => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elemento-post-content .elemento-post-meta-data span + span:before' => 'color: {{VALUE}};',
                ],
                'separator' => "before"
            ]
        );
        $this->end_controls_section();
    }
    protected function excerptStyle()
    {
        $this->start_controls_section(
            'excerpt_style',
            [
                'label' => __('Excerpt', 'mania-companion'),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'excerpt_anable' => 'on',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'excerpt_typography',
                'selector' => '{{WRAPPER}} .elemento-post-content .elemento-post-excerpt p',
                'fields_options' => [
                    'font_size' => [
                        'default' => [
                            'unit' => 'px',
                            'size' => '14',
                        ],
                    ],
                    'letter_spacing' => [
                        'default' => [
                            'unit' => 'em',
                            'size' => '0.3',
                        ],
                    ],
                    'line_height' => [
                        'default' => [
                            'unit' => 'em',
                            'size' => '1.5',
                        ],
                    ],
                    'font_weight' => [
                        'default' => 'normal',
                    ],
                    'font_family' => [
                        'default' => 'sans-serif'
                    ]
                ],
            ]
        );
        $this->add_control(
            'excerpt_color',
            [
                'label'     => __('Color', 'mania-companion'),
                'type'      => Controls_Manager::COLOR,
                "default"   => "#777",
                'scheme'    => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elemento-post-content .elemento-post-excerpt p' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();
    }
    protected function readmoreStyle()
    {
        $this->start_controls_section(
            'readmore_style',
            [
                'label' => __('Read More', 'mania-companion'),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'excerpt_anable' => 'on',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'readmore_typography',
                'selector' => '{{WRAPPER}} .elemento-post-content .elemento-post-read-more',
                'fields_options' => [
                    'font_size' => [
                        'default' => [
                            'unit' => 'px',
                            'size' => '18',
                        ],
                    ],
                    'font_weight' => [
                        'default' => '700',
                    ],
                    'font_family' => [
                        'default' => 'sans-serif'
                    ]
                ],
            ]
        );

        $this->add_control(
            'readMore_padding',
            [
                'label' => __('Padding', 'mania-companion'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .elemento-post-content .elemento-post-read-more' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'top' => 0,
                'right' => 0,
                'bottom' => 0,
                'left' => 0,
                'unit' => 'px',
                'isLinked' => true,
            ]
        );

        // normal and hover 
        $this->start_controls_tabs('readmore_style_');
        $this->start_controls_tab(
            'readmore_style_normal',
            [
                'label'     => __('Normal', 'mania-companion'),
            ]
        );

        $this->add_control(
            'readmore_color',
            [
                'label'     => __('Color', 'mania-companion'),
                'type'      => Controls_Manager::COLOR,
                "default"   => "#61ce70",
                'scheme'    => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elemento-post-content .elemento-post-read-more' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'readmore_bg_color',
            [
                'label'     => __('Background Color', 'mania-companion'),
                'type'      => Controls_Manager::COLOR,
                "default"   => "transparent",
                'scheme'    => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elemento-post-content .elemento-post-read-more' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'readmore_style_hover',
            [
                'label'     => __('Hover', 'mania-companion'),
            ]
        );
        $this->add_control(
            'readmore_hover_color',
            [
                'label'     => __('Color', 'mania-companion'),
                'type'      => Controls_Manager::COLOR,
                "default"   => "#383a3c",
                'scheme'    => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elemento-post-content .elemento-post-read-more:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'readmore_bg_color_hover',
            [
                'label'     => __('Background Color', 'mania-companion'),
                'type'      => Controls_Manager::COLOR,
                "default"   => "transparent",
                'scheme'    => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elemento-post-content .elemento-post-read-more:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
    }
    protected function paginationControllsStyle()
    {
        $this->start_controls_section(
            'pagination_content_style',
            [
                'label' => "Pagination",
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'pagination_show' => 'on'
                ],
            ]
        );

        $this->add_responsive_control(
            'Pagination_font_size',
            [
                'label'     => __('Font Size', 'mania-companion'),
                'type'      => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'     => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .elemento-addons-pagination .elemento-post-link' => 'font-size : {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'pagination_padding',
            [
                'label' => __('Padding', 'mania-companion'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .elemento-addons-pagination .elemento-post-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'top' => 0,
                'right' => 0,
                'bottom' => 0,
                'left' => 0,
                'unit' => 'px',
                'isLinked' => true,
            ]
        );
        $this->add_responsive_control(
            'Pagination_gap',
            [
                'label'     => __('Pagination Gap', 'mania-companion'),
                'type'      => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'     => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .elemento-addons-pagination' => 'grid-gap : {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('pagination_style');
        $this->start_controls_tab(
            'pagination_style_normal',
            [
                'label'     => __('Normal', 'mania-companion'),
            ]
        );
        $this->add_control(
            'pagination_color',
            [
                'label'     => __('Color', 'mania-companion'),
                'type'      => Controls_Manager::COLOR,
                "default"   => "#616161",
                'scheme'    => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elemento-addons-pagination .elemento-post-link' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'pagination_background_color',
            [
                'label'     => __('Background Color', 'mania-companion'),
                'type'      => Controls_Manager::COLOR,
                "default"   => "#ffff",
                'scheme'    => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elemento-addons-pagination .elemento-post-link' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'pagination_style_hover',
            [
                'label'     => __('Hover/active', 'mania-companion'),
            ]
        );
        $this->add_control(
            'pagination_color_hover',
            [
                'label'     => __('Color', 'mania-companion'),
                'type'      => Controls_Manager::COLOR,
                "default"   => "#1b1a1a",
                'scheme'    => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elemento-addons-pagination .elemento-post-link:hover,
                    {{WRAPPER}} .elemento-addons-pagination .elemento-post-link.active' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'pagination_background_color_hover',
            [
                'label'     => __('Background Color', 'mania-companion'),
                'type'      => Controls_Manager::COLOR,
                "default"   => "#f3f3f3",
                'scheme'    => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elemento-addons-pagination .elemento-post-link:hover,
                    {{WRAPPER}} .elemento-addons-pagination .elemento-post-link.active' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    // content general controlls register 

    // php render 
    protected function render()
    {
        $settings = $this->get_settings();
        $this->postSetting()->post_html($settings);
    }

    // class end 
}

Elementor\Plugin::instance()->widgets_manager->register_widget_type(new elementoPostSimple());
