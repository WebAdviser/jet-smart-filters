<?php

namespace Elementor;

use Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class Jet_Smart_Filters_Base_Widget extends Widget_Base {

	public function get_name() {
		// Rewrite this in child widget
		return 'jet-smart-filters-base';
	}

	public function get_categories() {
		return array( jet_smart_filters()->widgets->get_category() );
	}

	/**
	 * Returns filter control settings
	 *
	 * @return array
	 */
	public function get_filter_control_settings() {
		return array(
			'type'     => Controls_Manager::SELECT2,
			'multiple' => true,
		);
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_general',
			array(
				'label' => __( 'Content', 'jet-smart-filters' ),
			)
		);

		$filter_contrl = $this->get_filter_control_settings();

		$this->add_control(
			'filter_id',
			array(
				'label'       => __( 'Select filter', 'jet-smart-filters' ),
				'label_block' => true,
				'type'        => $filter_contrl['type'],
				'multiple'    => $filter_contrl['multiple'],
				'default'     => '',
				'options'     => $this->get_widget_filters(),
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
			'apply_on',
			array(
				'label'     => __( 'Apply on', 'jet-smart-filters' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'value',
				'options'   => array(
					'value'  => __( 'Value change', 'jet-smart-filters' ),
					'submit' => __( 'Click on apply button', 'jet-smart-filters' ),
				),
				'condition' => array(
					'apply_type' => 'ajax',
				),
			)
		);

		$this->add_control(
			'apply_button',
			array(
				'label'        => esc_html__( 'Show apply button', 'jet-smart-filters' ),
				'type'         => Controls_Manager::SWITCHER,
				'description'  => '',
				'label_on'     => esc_html__( 'Yes', 'jet-smart-filters' ),
				'label_off'    => esc_html__( 'No', 'jet-smart-filters' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'apply_button_text',
			array(
				'label'     => esc_html__( 'Apply button text', 'jet-smart-filters' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'Apply filters', 'jet-smart-filters' ),
				'condition' => array(
					'apply_button' => 'yes'
				),
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

		$this->register_filter_style_controls();

		$css_scheme = apply_filters(
			'jet-smart-filters/widgets/base/css-scheme',
			array(
				'filter'               => '.jet-filter',
				'filters-label'        => '.jet-filter-label',
				'apply-filters'        => '.apply-filters',
				'apply-filters-button' => '.apply-filters__button',
			)
		);

		$this->base_controls_section_filter_label( $css_scheme );

		$this->base_controls_section_filter_apply_button( $css_scheme );

		$this->base_controls_section_filter_group( $css_scheme );

	}

	public function base_controls_section_filter_label( $css_scheme ) {

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
				'label'     => esc_html__( 'Alignment', 'jet-smart-filters' ),
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

	public function base_controls_section_filter_apply_button( $css_scheme ) {

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
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
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

		$this->add_responsive_control(
			'filter_apply_button_alignment',
			array(
				'label'     => esc_html__( 'Alignment', 'jet-smart-filters' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'flex-start'   => array(
						'title' => esc_html__( 'Left', 'jet-smart-filters' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-smart-filters' ),
						'icon'  => 'fa fa-align-center',
					),
					'flex-end'  => array(
						'title' => esc_html__( 'Right', 'jet-smart-filters' ),
						'icon'  => 'fa fa-align-right',
					),
					'stretch'  => array(
						'title' => esc_html__( 'Stretch', 'jet-smart-filters' ),
						'icon'  => 'fa fa-align-justify',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['apply-filters-button'] => 'align-self: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

	}

	public function base_controls_section_filter_group( $css_scheme ) {

		$this->start_controls_section(
			'section_group_filters_style',
			array(
				'label'      => esc_html__( 'Grouped Filters', 'jet-smart-filters' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_responsive_control(
			'group_filters_vertical_offset',
			array(
				'label'      => esc_html__( 'Vertical Space Between', 'jet-smart-filters' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'size' => 10,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['filter'] . '+' . $css_scheme['filter'] => 'margin-top: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

	}

	/**
	 * Register filter style controls. Specific for each widget.
	 *
	 * @return void
	 */
	public function register_filter_style_controls() {
	}

	/**
	 * Register filter style controls. Specific for each widget.
	 *
	 * @return void
	 */
	public function register_horizontal_layout_controls( $css_scheme ) {

		$this->add_responsive_control(
			'filters_position',
			array(
				'label'       => esc_html__( 'Filters Position', 'jet-smart-filters' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => array(
					'inline-block'    => array(
						'title' => esc_html__( 'Line', 'jet-smart-filters' ),
						'icon'  => 'fa fa-ellipsis-h',
					),
					'block' => array(
						'title' => esc_html__( 'Column', 'jet-smart-filters' ),
						'icon'  => 'fa fa-bars',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['list-item']     => 'display: {{VALUE}};',
					'{{WRAPPER}} ' . $css_scheme['list-children'] => 'display: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'filters_space_between_horizontal',
			array(
				'label'      => esc_html__( 'Horizontal Offset', 'jet-smart-filters' ),
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
					'size' => 5,
					'unit' => 'px',
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['list-item']     => 'margin-right: calc({{SIZE}}{{UNIT}}/2); margin-left: calc({{SIZE}}{{UNIT}}/2);',
					'{{WRAPPER}} ' . $css_scheme['list-children'] => 'margin-right: calc({{SIZE}}{{UNIT}}/2); margin-left: calc({{SIZE}}{{UNIT}}/2);',
					'{{WRAPPER}} ' . $css_scheme['list-wrapper']  => 'margin-left: calc(-{{SIZE}}{{UNIT}}/2); margin-right: calc(-{{SIZE}}{{UNIT}}/2);',
				),
				'condition'  => array(
					'filters_position' => 'inline-block'
				)
			)
		);

		$this->add_responsive_control(
			'filters_space_between_vertical',
			array(
				'label'      => esc_html__( 'Vertical Offset', 'jet-smart-filters' ),
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
					'size' => 5,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['list-item'] => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'filters_position' => 'inline-block'
				)
			)
		);

		$this->add_responsive_control(
			'filters_list_alignment',
			array(
				'label'   => esc_html__( 'Alignment', 'jet-smart-filters' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'left' => array(
						'title' => esc_html__( 'Left', 'jet-smart-filters' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-smart-filters' ),
						'icon'  => 'fa fa-align-center',
					),
					'right' => array(
						'title' => esc_html__( 'Right', 'jet-smart-filters' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['list-wrapper'] => 'text-align: {{VALUE}};',
				),
				'condition'  => array(
					'filters_position' => 'inline-block'
				)
			)
		);

	}

	/**
	 * Returns apropriate filters list for widget
	 */
	public function get_widget_filters() {
		return jet_smart_filters()->data->get_filters_by_type( $this->get_widget_fiter_type() );
	}

	/**
	 * Returns widget filter type
	 */
	public function get_widget_fiter_type() {
		return str_replace( 'jet-smart-filters-', '', $this->get_name() );
	}

	/**
	 * Returns CSS selector for nested element
	 *
	 * @param  [type] $el [description]
	 *
	 * @return [type]     [description]
	 */
	public function css_selector( $el = null ) {
		return sprintf( '{{WRAPPER}} .%1$s%2$s', $this->get_name(), $el );
	}

	protected function render() {

		$base_class = $this->get_name();
		$settings   = $this->get_settings();

		if ( empty( $settings['filter_id'] ) ) {
			return;
		}

		$filter_ids = $settings['filter_id'];

		if ( ! is_array( $filter_ids ) ) {
			$filter_ids = array( $filter_ids );
		}

		if ( 'ajax' === $settings['apply_type'] && 'submit' === $settings['apply_on'] ) {
			$apply_type = 'ajax-reload';
		} else {
			$apply_type = $settings['apply_type'];
		}

		$query_id          = ! empty( $settings['query_id'] ) ? $settings['query_id'] : 'default';
		$show_label        = ! empty( $settings['show_label'] ) ? $settings['show_label'] : false;
		$show_items_label  = ! empty( $settings['show_items_label'] ) ? $settings['show_items_label'] : false;
		$filter_image_size = ! empty( $settings['filter_image_size'] ) ? $settings['filter_image_size'] : 'full';
		$show_label        = filter_var( $show_label, FILTER_VALIDATE_BOOLEAN );

		foreach ( $filter_ids as $filter_id ) {

			$filter_id = apply_filters( 'jet-smart-filters/render_filter_template/filter_id', $filter_id );

			printf( '<div class="%1$s jet-filter">', $base_class );

			jet_smart_filters()->filter_types->render_filter_template( $this->get_widget_fiter_type(), array(
				'filter_id'        => $filter_id,
				'content_provider' => $settings['content_provider'],
				'apply_type'       => $apply_type,
				'query_id'         => $query_id,
				'show_label'       => $show_label,
				'display_options'  => array(
					'show_items_label' => $show_items_label,
					'filter_image_size' => $filter_image_size
				)
			) );

			echo '</div>';

		}

		if ( 'yes' === $settings['apply_button'] ) {
			include jet_smart_filters()->get_template( 'common/apply-filters.php' );
		}

	}

}
