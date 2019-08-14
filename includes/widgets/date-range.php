<?php

namespace Elementor;

use Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class Jet_Smart_Filters_Date_Range_Widget extends Jet_Smart_Filters_Base_Widget {

	public function get_name() {
		return 'jet-smart-filters-date-range';
	}

	public function get_title() {
		return __( 'Date Range Filter', 'jet-smart-filters' );
	}

	public function get_icon() {
		return 'jet-smart-filters-date-range-filter';
	}

	public function get_help_url() {
		return jet_smart_filters()->widgets->prepare_help_url(
			'https://crocoblock.com/knowledge-base/articles/jetsmartfilters-how-to-add-a-date-range-filter-based-on-the-dates-in-the-meta-fields/',
			$this->get_name()
		);
	}

	protected function _register_controls() {

		$css_scheme = apply_filters(
			'jet-smart-filters/widgets/date-range/css-scheme',
			array(
				'filter-wrapper'            => '.jet-smart-filters-date-range',
				'filter-content'            => '.jet-smart-filters-date-range .jet-date-range',
				'filters-label'       => '.jet-filter-label',
				'inputs'                    => '.jet-date-range__inputs',
				'input'                     => '.jet-date-range__inputs > input',
				'apply-filters-button'      => '.jet-date-range__submit',
				'apply-filters-button-icon' => '.jet-date-range__submit > i',
				'calendar-wrapper'          => '.ui-datepicker',
				'calendar'                  => '.ui-datepicker-calendar',
				'calendar-header'           => '.ui-datepicker-header',
				'calendar-prev-button'      => '.ui-datepicker-prev',
				'calendar-next-button'      => '.ui-datepicker-next',
				'calendar-title'            => '.ui-datepicker-title',
				'calendar-body-header'      => '.ui-datepicker-calendar thead',
				'calendar-body-content'     => '.ui-datepicker-calendar tbody',
			)
		);

		$this->start_controls_section(
			'section_general',
			array(
				'label' => __( 'Content', 'jet-smart-filters' ),
			)
		);

		$this->add_control(
			'filter_id',
			array(
				'label'   => __( 'Select filter', 'jet-smart-filters' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => $this->get_widget_filters(),
			)
		);

		$this->add_control(
			'content_provider',
			array(
				'label'   => __( 'This filter for', 'jet-smart-filters' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => jet_smart_filters()->data->content_providers(),
			)
		);

		$this->add_control(
			'epro_posts_notice',
			array(
				'type' => Controls_Manager::RAW_HTML,
				'raw'  => __( 'Please set <b>jet-smart-filters</b> into Query ID option of Posts widget you want to filter', 'jet-smart-filters' ),
				'condition' => array(
					'content_provider' => 'epro-posts',
				),
			)
		);

		$this->add_control(
			'apply_type',
			array(
				'label'   => __( 'Apply type', 'jet-smart-filters' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'ajax',
				'options' => array(
					'ajax'   => __( 'AJAX', 'jet-smart-filters' ),
					'reload' => __( 'Page reload', 'jet-smart-filters' ),
				),
			)
		);

		$this->add_control(
			'apply_button_text',
			array(
				'label'   => esc_html__( 'Apply button text', 'jet-smart-filters' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Apply', 'jet-smart-filters' ),
			)
		);

		$this->add_control(
			'apply_button_icon',
			array(
				'label'   => esc_html__( 'Apply button icon', 'jet-smart-filters' ),
				'type'    => Controls_Manager::ICON,
				'default' => '',
			)
		);

		$this->add_control(
			'show_label',
			array(
				'label'        => esc_html__( 'Show filter label', 'jet-smart-filters' ),
				'type'         => Controls_Manager::SWITCHER,
				'description'  => '',
				'label_on'     => esc_html__( 'Yes', 'jet-smart-filters' ),
				'label_off'    => esc_html__( 'No', 'jet-smart-filters' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'query_id',
			array(
				'label'       => esc_html__( 'Query ID', 'jet-smart-filters' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'description' => __( 'Set unique query ID if you use multiple widgets of same provider on the page. Same ID you need to set for filtered widget.', 'jet-smart-filters' ),
			)
		);

		$this->end_controls_section();

		$this->controls_section_content( $css_scheme );

		$this->controls_section_calendar( $css_scheme );

		$this->controls_section_date_inputs( $css_scheme );

		$this->controls_section_filter_label( $css_scheme );

		$this->controls_section_apply_filter_button( $css_scheme );

	}

	protected function controls_section_content( $css_scheme ){

		$this->start_controls_section(
			'section_date_range_content_style',
			array(
				'label'      => __( 'Content', 'jet-smart-filters' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_responsive_control(
			'content_position',
			array(
				'label'   => esc_html__( 'Position', 'jet-smart-filters' ),
				'type'    => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => array(
					'line' => array(
						'title' => esc_html__( 'Line', 'jet-smart-filters' ),
						'icon'  => 'fa fa-ellipsis-h',
					),
					'column' => array(
						'title' => esc_html__( 'Columns', 'jet-smart-filters' ),
						'icon'  => 'fa fa-bars',
					),
				),
				'selectors_dictionary' => array(
					'line'      => 'display:flex; flex-direction:row;',
					'column'    => 'display:flex; flex-direction:column;',
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['filter-content'] => '{{VALUE}}',
				),
				'prefix_class' => 'jet-smart-filter-content-position-',
			)
		);

		$this->add_responsive_control(
			'content_date_range_input_width',
			array(
				'label'      => esc_html__( 'Inputs Width', 'jet-smart-filters' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
					'%',
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 500,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'size' => 100,
					'unit' => '%',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['inputs'] => 'max-width: {{SIZE}}{{UNIT}}; width:100%;',
				),
			)
		);

		$this->add_responsive_control(
			'content_line_horizontal_alignment',
			array(
				'label'   => esc_html__( 'Horizontal Alignment', 'jet-smart-filters' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'flex-start' => array(
						'title' => esc_html__( 'Left', 'jet-smart-filters' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-smart-filters' ),
						'icon'  => 'eicon-h-align-center',
					),
					'flex-end' => array(
						'title' => esc_html__( 'Right', 'jet-smart-filters' ),
						'icon'  => 'eicon-h-align-right',
					),
					'space-between' => array(
						'title' => esc_html__( 'Justify', 'jet-smart-filters' ),
						'icon'  => 'eicon-h-align-stretch',
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['filter-content'] => 'justify-content: {{VALUE}};',
				),
				'condition' => array(
					'content_position' => 'line'
				)
			)
		);

		$this->add_responsive_control(
			'content_line_vertical_alignment',
			array(
				'label'     => esc_html__( 'Vertical Alignment', 'jet-smart-filters' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'left',
				'options'   => array(
					'flex-start' => array(
						'title' => esc_html__( 'Top', 'jet-smart-filters' ),
						'icon'  => 'eicon-v-align-top',
					),
					'center'     => array(
						'title' => esc_html__( 'Middle', 'jet-smart-filters' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'flex-end'   => array(
						'title' => esc_html__( 'Bottom', 'jet-smart-filters' ),
						'icon'  => 'eicon-v-align-bottom',
					),
					'stretch'   => array(
						'title' => esc_html__( 'Stretch', 'jet-smart-filters' ),
						'icon'  => 'eicon-v-align-stretch',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['filter-content'] => 'align-items: {{VALUE}};',
				),
				'condition' => array(
					'content_position' => 'line'
				)
			)
		);

		$this->add_responsive_control(
			'content_column_horizontal_alignment',
			array(
				'label'   => esc_html__( 'Horizontal Alignment', 'jet-smart-filters' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'flex-start' => array(
						'title' => esc_html__( 'Left', 'jet-smart-filters' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-smart-filters' ),
						'icon'  => 'eicon-h-align-center',
					),
					'flex-end' => array(
						'title' => esc_html__( 'Right', 'jet-smart-filters' ),
						'icon'  => 'eicon-h-align-right',
					),
					'stretch' => array(
						'title' => esc_html__( 'Stretch', 'jet-smart-filters' ),
						'icon'  => 'eicon-h-align-stretch',
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['filter-content'] => 'align-items: {{VALUE}};',
				),
				'condition' => array(
					'content_position' => 'column'
				)
			)
		);

		$this->end_controls_section();

	}

	protected function controls_section_calendar( $css_scheme ) {

		$this->start_controls_section(
			'section_calendar_styles',
			array(
				'label'      => __( 'Calendar', 'jet-smart-filters' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_responsive_control(
			'calendar_offset_top',
			array(
				'label'      => esc_html__( 'Offset Top', 'jet-smart-filters' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 40,
					),
				),
				'selectors'  => array(
					'.jet-smart-filters-datepicker-{{ID}}' . $css_scheme['calendar-wrapper'] => 'margin-top: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'calendar_width',
			array(
				'label'      => esc_html__( 'Calendar Width', 'jet-smart-filters' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 1000,
					),
				),
				'default'    => array(
					'size' => 300,
					'unit' => 'px',
				),
				'selectors'  => array(
					'.jet-smart-filters-datepicker-{{ID}}' . $css_scheme['calendar-wrapper'] => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'calendar_body_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-smart-filters' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.jet-smart-filters-datepicker-{{ID}}' . $css_scheme['calendar-wrapper'] => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'calendar_body_border',
				'label'       => esc_html__( 'Border', 'jet-smart-filters' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '.jet-smart-filters-datepicker-{{ID}}' . $css_scheme['calendar-wrapper'],
			)
		);

		$this->add_control(
			'calendar_body_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-smart-filters' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'.jet-smart-filters-datepicker-{{ID}}' . $css_scheme['calendar-wrapper'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'calendar_body_box_shadow',
				'selector' => '.jet-smart-filters-datepicker-{{ID}}' . $css_scheme['calendar-wrapper'],
			)
		);

		$this->add_responsive_control(
			'calendar_body_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-smart-filters' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'.jet-smart-filters-datepicker-{{ID}}' . $css_scheme['calendar-wrapper'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_calendar_title',
			array(
				'label'      => __( 'Calendar Caption', 'jet-smart-filters' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'calendar_title_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-smart-filters' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.jet-smart-filters-datepicker-{{ID}} ' . $css_scheme['calendar-title'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'calendar_title_typography',
				'selector' => '.jet-smart-filters-datepicker-{{ID}} ' . $css_scheme['calendar-title'],
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_calendar_prev_next',
			array(
				'label'      => __( 'Calendar Navigation Arrows', 'jet-smart-filters' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_responsive_control(
			'calendar_prev_next_size',
			array(
				'label'      => esc_html__( 'Size', 'jet-smart-filters' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 30,
					),
				),
				'default'    => array(
					'size' => 15,
					'unit' => 'px',
				),
				'selectors'  => array(
					'.jet-smart-filters-datepicker-{{ID}} ' . $css_scheme['calendar-prev-button'] . '> span:before' => 'font-size: {{SIZE}}{{UNIT}};',
					'.jet-smart-filters-datepicker-{{ID}} ' . $css_scheme['calendar-next-button'] . '> span:before' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'calendar_prev_next_normal_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-smart-filters' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.jet-smart-filters-datepicker-{{ID}} ' . $css_scheme['calendar-next-button'] . '> span:before' => 'color: {{VALUE}}',
					'.jet-smart-filters-datepicker-{{ID}} ' . $css_scheme['calendar-prev-button'] . '> span:before' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'calendar_prev_next_hover_color',
			array(
				'label'     => esc_html__( 'Hover Color', 'jet-smart-filters' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.jet-smart-filters-datepicker-{{ID}} ' . $css_scheme['calendar-next-button'] . ':hover > span:before' => 'color: {{VALUE}}',
					'.jet-smart-filters-datepicker-{{ID}} ' . $css_scheme['calendar-prev-button'] . ':hover > span:before' => 'color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_calendar_header',
			array(
				'label'      => __( 'Calendar Week Days', 'jet-smart-filters' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'calendar_header_border',
				'label'       => esc_html__( 'Header Border', 'jet-smart-filters' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '.jet-smart-filters-datepicker-{{ID}} ' . $css_scheme['calendar-body-header'],
			)
		);

		$this->add_control(
			'calendar_header_background_color',
			array(
				'label'     => esc_html__( 'Header Background Color', 'jet-smart-filters' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.jet-smart-filters-datepicker-{{ID}} ' . $css_scheme['calendar-body-header'] => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'calendar_header_cells_heading',
			array(
				'label'     => esc_html__( 'Day', 'jet-smart-filters' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'calendar_header_cells_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-smart-filters' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'.jet-smart-filters-datepicker-{{ID}} ' . $css_scheme['calendar-body-header'] . ' > tr > th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'calendar_header_cells_border',
				'label'       => esc_html__( 'Border', 'jet-smart-filters' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '.jet-smart-filters-datepicker-{{ID}} ' . $css_scheme['calendar-body-header'] . ' > tr > th',
			)
		);

		$this->add_control(
			'calendar_header_cells_first_border_width',
			array(
				'label'      => esc_html__( 'First Item Border Width', 'jet-smart-filters' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'.jet-smart-filters-datepicker-{{ID}} ' . $css_scheme['calendar-body-header'] . ' > tr > th:first-child' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'calendar_header_cells_border_border!' => ''
				)
			)
		);

		$this->add_control(
			'calendar_header_cells_last_border_width',
			array(
				'label'      => esc_html__( 'Last Item Border Width', 'jet-smart-filters' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'.jet-smart-filters-datepicker-{{ID}} ' . $css_scheme['calendar-body-header'] . ' > tr > th:last-child' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'calendar_header_cells_border_border!' => ''
				)
			)
		);

		$this->add_control(
			'calendar_header_cells_content',
			array(
				'label'     => esc_html__( 'Day Content', 'jet-smart-filters' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'calendar_header_cells_content_typography',
				'selector' => '.jet-smart-filters-datepicker-{{ID}} ' . $css_scheme['calendar-body-header'] . ' > tr > th > span',
			)
		);

		$this->add_control(
			'calendar_header_cells_content_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-smart-filters' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.jet-smart-filters-datepicker-{{ID}} ' . $css_scheme['calendar-body-header'] . ' > tr > th > span' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'calendar_header_cells_content_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-smart-filters' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.jet-smart-filters-datepicker-{{ID}} ' . $css_scheme['calendar-body-header'] . ' > tr > th > span' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'calendar_header_cells_content_border',
				'label'       => esc_html__( 'Border', 'jet-smart-filters' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '.jet-smart-filters-datepicker-{{ID}} ' . $css_scheme['calendar-body-header'] . ' > tr > th > span',
			)
		);

		$this->add_control(
			'calendar_header_cells_content_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-smart-filters' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'.jet-smart-filters-datepicker-{{ID}} ' . $css_scheme['calendar-body-header'] . ' > tr > th > span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'calendar_header_cells_content_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-smart-filters' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'.jet-smart-filters-datepicker-{{ID}} ' . $css_scheme['calendar-body-header'] . ' > tr > th > span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'section_calendar_content',
			array(
				'label'      => __( 'Calendar Days', 'jet-smart-filters' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'calendar_content_border',
				'label'       => esc_html__( 'Body Border', 'jet-smart-filters' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '.jet-smart-filters-datepicker-{{ID}} ' . $css_scheme['calendar-body-content'],
			)
		);

		$this->add_control(
			'calendar_content_background_color',
			array(
				'label'     => esc_html__( 'Body Background Color', 'jet-smart-filters' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.jet-smart-filters-datepicker-{{ID}} ' . $css_scheme['calendar-body-content'] => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'calendar_content_cells_heading',
			array(
				'label'     => esc_html__( 'Day', 'jet-smart-filters' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'calendar_content_cells_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-smart-filters' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'.jet-smart-filters-datepicker-{{ID}} ' . $css_scheme['calendar-body-content'] . ' > tr > td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'calendar_content_cells_border',
				'label'       => esc_html__( 'Border', 'jet-smart-filters' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '.jet-smart-filters-datepicker-{{ID}} ' . $css_scheme['calendar-body-content'] . ' > tr > td',
			)
		);

		$this->add_control(
			'calendar_content_cells_first_border_width',
			array(
				'label'      => esc_html__( 'First Item Border Width', 'jet-smart-filters' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'.jet-smart-filters-datepicker-{{ID}} ' . $css_scheme['calendar-body-content'] . ' > tr > td:first-child' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'calendar_content_cells_border_border!' => ''
				)
			)
		);

		$this->add_control(
			'calendar_content_cells_last_border_width',
			array(
				'label'      => esc_html__( 'Last Item Border Width', 'jet-smart-filters' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'.jet-smart-filters-datepicker-{{ID}} ' . $css_scheme['calendar-body-content'] . ' > tr > td:last-child' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'calendar_content_cells_border_border!' => ''
				)
			)
		);

		$this->add_control(
			'calendar_content_cells_content',
			array(
				'label'     => esc_html__( 'Day Content', 'jet-smart-filters' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'calendar_content_cells_content_typography',
				'selector' => '.jet-smart-filters-datepicker-{{ID}} ' . $css_scheme['calendar-body-content'] . ' > tr > td > span,' . '.jet-smart-filters-datepicker-{{ID}} ' . $css_scheme['calendar-body-content'] . ' > tr > td > a',
			)
		);

		$this->start_controls_tabs( 'calendar_content_cells_content_style_tabs' );
		$this->start_controls_tab(
			'calendar_content_cells_content_default_styles',
			array(
				'label' => esc_html__( 'Default', 'jet-smart-filters' ),
			)
		);

		$this->add_control(
			'calendar_content_cells_content_default_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-smart-filters' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.jet-smart-filters-datepicker-{{ID}} ' . $css_scheme['calendar-body-content'] . ' > tr > td > span' => 'color: {{VALUE}}',
					'.jet-smart-filters-datepicker-{{ID}} ' . $css_scheme['calendar-body-content'] . ' > tr > td > a'    => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'calendar_content_cells_content_default_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-smart-filters' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.jet-smart-filters-datepicker-{{ID}} ' . $css_scheme['calendar-body-content'] . ' > tr > td > span' => 'background-color: {{VALUE}}',
					'.jet-smart-filters-datepicker-{{ID}} ' . $css_scheme['calendar-body-content'] . ' > tr > td > a'    => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();
		$this->start_controls_tab(
			'calendar_content_cells_content_hover_styles',
			array(
				'label' => esc_html__( 'Hover', 'jet-smart-filters' ),
			)
		);

		$this->add_control(
			'calendar_content_cells_content_hover_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-smart-filters' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.jet-smart-filters-datepicker-{{ID}} ' . $css_scheme['calendar-body-content'] . ' > tr > td > a:hover' => 'color: {{VALUE}}',
					'.jet-smart-filters-datepicker-{{ID}} ' . $css_scheme['calendar-body-content'] . ' > tr > td.ui-datepicker-today > a:hover' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'calendar_content_cells_content_hover_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-smart-filters' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.jet-smart-filters-datepicker-{{ID}} ' . $css_scheme['calendar-body-content'] . ' > tr > td > a:hover' => 'background-color: {{VALUE}}',
					'.jet-smart-filters-datepicker-{{ID}} ' . $css_scheme['calendar-body-content'] . ' > tr > td.ui-datepicker-today > a:hover' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'calendar_content_cells_content_hover_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'jet-smart-filters' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.jet-smart-filters-datepicker-{{ID}} ' . $css_scheme['calendar-body-content'] . ' > tr > td > a:hover' => 'border-color: {{VALUE}}',
					'.jet-smart-filters-datepicker-{{ID}} ' . $css_scheme['calendar-body-content'] . ' > tr > td.ui-datepicker-today > a:hover' => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();
		$this->start_controls_tab(
			'calendar_content_cells_content_active_styles',
			array(
				'label' => esc_html__( 'Active', 'jet-smart-filters' ),
			)
		);

		$this->add_control(
			'calendar_content_cells_content_active_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-smart-filters' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.jet-smart-filters-datepicker-{{ID}} ' . $css_scheme['calendar-body-content'] . ' > tr > td > a.ui-state-active' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'calendar_content_cells_content_active_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-smart-filters' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.jet-smart-filters-datepicker-{{ID}} ' . $css_scheme['calendar-body-content'] . ' > tr > td > a.ui-state-active' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'calendar_content_cells_content_active_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'jet-smart-filters' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.jet-smart-filters-datepicker-{{ID}} ' . $css_scheme['calendar-body-content'] . ' > tr > td > a.ui-state-active' => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();
		$this->start_controls_tab(
			'calendar_content_cells_content_current_styles',
			array(
				'label' => esc_html__( 'Current', 'jet-smart-filters' ),
			)
		);

		$this->add_control(
			'calendar_content_cells_content_current_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-smart-filters' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.jet-smart-filters-datepicker-{{ID}} ' . $css_scheme['calendar-body-content'] . ' > tr > td.ui-datepicker-today > a'    => 'color: {{VALUE}}',
					'.jet-smart-filters-datepicker-{{ID}} ' . $css_scheme['calendar-body-content'] . ' > tr > td.ui-datepicker-today > span' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'calendar_content_cells_content_current_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-smart-filters' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.jet-smart-filters-datepicker-{{ID}} ' . $css_scheme['calendar-body-content'] . ' > tr > td.ui-datepicker-today > a'    => 'background-color: {{VALUE}}',
					'.jet-smart-filters-datepicker-{{ID}} ' . $css_scheme['calendar-body-content'] . ' > tr > td.ui-datepicker-today > span' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'calendar_content_cells_content_current_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'jet-smart-filters' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.jet-smart-filters-datepicker-{{ID}} ' . $css_scheme['calendar-body-content'] . ' > tr > td.ui-datepicker-today > a'    => 'border-color: {{VALUE}}',
					'.jet-smart-filters-datepicker-{{ID}} ' . $css_scheme['calendar-body-content'] . ' > tr > td.ui-datepicker-today > span' => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'calendar_content_cells_content_border',
				'label'       => esc_html__( 'Border', 'jet-smart-filters' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '.jet-smart-filters-datepicker-{{ID}} ' . $css_scheme['calendar-body-content'] . ' > tr > td > span,' . '.jet-smart-filters-datepicker-{{ID}} ' . $css_scheme['calendar-body-content'] . ' > tr > td > a',
			)
		);

		$this->add_control(
			'calendar_content_cells_content_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-smart-filters' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'.jet-smart-filters-datepicker-{{ID}} ' . $css_scheme['calendar-body-content'] . ' > tr > td > span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'.jet-smart-filters-datepicker-{{ID}} ' . $css_scheme['calendar-body-content'] . ' > tr > td > a'    => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'calendar_content_cells_content_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-smart-filters' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'.jet-smart-filters-datepicker-{{ID}} ' . $css_scheme['calendar-body-content'] . ' > tr > td > span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'.jet-smart-filters-datepicker-{{ID}} ' . $css_scheme['calendar-body-content'] . ' > tr > td > a'    => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

	}

	protected function controls_section_date_inputs( $css_scheme ) {

		$this->start_controls_section(
			'section_date_range_input_style',
			array(
				'label'      => __( 'Inputs', 'jet-smart-filters' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_responsive_control(
			'date_range_input_width',
			array(
				'label'      => esc_html__( 'Width', 'jet-smart-filters' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'%',
				),
				'range'      => array(
					'%' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'default'    => array(
					'size' => 45,
					'unit' => '%',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['input'] => 'max-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'date_range_input_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['input'],
			)
		);

		$this->add_control(
			'date_range_input_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-smart-filters' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['input'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'date_range_input_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-smart-filters' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['input'] => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'date_range_input_border',
				'label'       => esc_html__( 'Border', 'jet-smart-filters' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['input'],
				'separator'   => 'before'
			)
		);

		$this->add_control(
			'date_range_input_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-smart-filters' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['input'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'date_range_input_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['input'],
			)
		);

		$this->add_responsive_control(
			'date_range_input_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-smart-filters' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['input'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before'
			)
		);

		$this->end_controls_section();

	}

	protected function controls_section_filter_label( $css_scheme ) {

		$this->start_controls_section(
			'section_label_style',
			array(
				'label'      => esc_html__( 'Label', 'jet-smart-filters' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'label_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['filters-label'],
			)
		);

		$this->add_control(
			'label_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-smart-filters' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['filters-label'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'label_border',
				'label'       => esc_html__( 'Border', 'jet-smart-filters' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['filters-label'],
			)
		);

		$this->add_responsive_control(
			'label_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-smart-filters' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['filters-label'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before'
			)
		);

		$this->add_responsive_control(
			'label_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-smart-filters' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['filters-label'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'label_alignment',
			array(
				'label'     => esc_html__( 'Text Alignment', 'jet-smart-filters' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'jet-smart-filters' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-smart-filters' ),
						'icon'  => 'fa fa-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'jet-smart-filters' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['filters-label'] => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

	}

	protected function controls_section_apply_filter_button( $css_scheme ) {

		$this->start_controls_section(
			'section_filter_apply_button_style',
			array(
				'label'      => esc_html__( 'Button', 'jet-smart-filters' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'filter_apply_button_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['apply-filters-button'],
			)
		);

		$this->start_controls_tabs( 'filter_apply_button_style_tabs' );

		$this->start_controls_tab(
			'filter_apply_button_normal_styles',
			array(
				'label' => esc_html__( 'Normal', 'jet-smart-filters' ),
			)
		);

		$this->add_control(
			'filter_apply_button_normal_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-smart-filters' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['apply-filters-button'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'filter_apply_button_normal_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-smart-filters' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['apply-filters-button'] => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'filter_apply_button_hover_styles',
			array(
				'label' => esc_html__( 'Hover', 'jet-smart-filters' ),
			)
		);

		$this->add_control(
			'filter_apply_button_hover_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-smart-filters' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['apply-filters-button'] . ':hover' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'filter_apply_button_hover_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-smart-filters' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['apply-filters-button'] . ':hover' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'filter_apply_button_hover_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'jet-smart-filters' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['apply-filters-button'] . ':hover' => 'border-color: {{VALUE}}',
				),
				'condition' => array(
					'filter_apply_button_border_border!' => '',
				)
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'filter_apply_button_border',
				'label'       => esc_html__( 'Border', 'jet-smart-filters' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['apply-filters-button'],
				'separator'   => 'before'
			)
		);

		$this->add_control(
			'filter_apply_button_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-smart-filters' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['apply-filters-button'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'filter_apply_button_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['apply-filters-button'],
			)
		);

		$this->add_responsive_control(
			'filter_apply_button_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-smart-filters' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['apply-filters-button'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before'
			)
		);

		$this->add_responsive_control(
			'filter_apply_button_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-smart-filters' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['apply-filters-button'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'filter_apply_button_icon_heading',
			array(
				'label'     => esc_html__( 'Icon', 'jet-smart-filters' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'apply_button_icon!' => ''
				)
			)
		);

		$this->add_control(
			'filter_apply_button_icon_position',
			array(
				'label'       => esc_html__( 'Position', 'jet-smart-filters' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => array(
					'left'  => array(
						'title' => esc_html__( 'Left', 'jet-smart-filters' ),
						'icon'  => 'fa fa-arrow-left',
					),
					'right' => array(
						'title' => esc_html__( 'Right', 'jet-smart-filters' ),
						'icon'  => 'fa fa-arrow-right',
					),
				),
				'toggle'      => true,
				'default'     => 'left',
				'condition'   => array(
					'apply_button_icon!' => ''
				)
			)
		);

		$this->add_responsive_control(
			'filter_apply_button_icon_size',
			array(
				'label'      => esc_html__( 'Size', 'jet-smart-filters' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 40,
					),
				),
				'default'    => array(
					'size' => 15,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['apply-filters-button-icon'] => 'font-size: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'apply_button_icon!' => ''
				)
			)
		);

		$this->add_responsive_control(
			'filter_apply_button_icon_offset',
			array(
				'label'      => esc_html__( 'Icon Offset', 'jet-smart-filters' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'default'    => array(
					'size' => 0,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .button-icon-position-right ' . $css_scheme['apply-filters-button-icon'] => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .button-icon-position-left ' . $css_scheme['apply-filters-button-icon']  => 'margin-right: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'apply_button_icon!' => ''
				)
			)
		);

		$this->add_control(
			'filter_apply_button_icon_normal_color',
			array(
				'label'     => esc_html__( 'Default Color', 'jet-smart-filters' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['apply-filters-button-icon'] => 'color: {{VALUE}}',
				),
				'condition' => array(
					'apply_button_icon!' => ''
				)
			)
		);

		$this->add_control(
			'filter_apply_button_icon_hover_color',
			array(
				'label'     => esc_html__( 'Hover Color', 'jet-smart-filters' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['apply-filters-button'] . ':hover > i' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'apply_button_icon!' => ''
				)
			)
		);

		$this->end_controls_section();

	}

	protected function render() {

		$base_class = $this->get_name();
		$settings   = $this->get_settings();

		if ( empty( $settings['filter_id'] ) ) {
			return;
		}

		printf( '<div class="%1$s jet-filter">', $base_class );

		if ( 'reload' === $settings['apply_type'] ) {
			$apply_type = 'reload';
		} else {
			$apply_type = 'ajax-reload';
		}

		$format     = '<i class="%s"></i>';
		$icon       = $settings['apply_button_icon'] ? sprintf( $format, $settings['apply_button_icon'] ) : '';
		$query_id   = ! empty( $settings['query_id'] ) ? $settings['query_id'] : 'default';
		$show_label = ! empty( $settings['show_label'] ) ? $settings['show_label'] : false;
		$show_label = filter_var( $show_label, FILTER_VALIDATE_BOOLEAN );

		jet_smart_filters()->filter_types->render_filter_template( $this->get_widget_fiter_type(), array(
			'filter_id'            => $settings['filter_id'],
			'content_provider'     => $settings['content_provider'],
			'apply_type'           => $apply_type,
			'button_text'          => $settings['apply_button_text'],
			'button_icon'          => $icon,
			'button_icon_position' => $settings['filter_apply_button_icon_position'],
			'query_id'             => $query_id,
			'show_label'           => $show_label,
		) );

		echo '</div>';

	}

}
