<?php

namespace Elementor;

use Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class Jet_Smart_Filters_Range_Widget extends Jet_Smart_Filters_Base_Widget {

	public function get_name() {
		return 'jet-smart-filters-range';
	}

	public function get_title() {
		return __( 'Range Filter', 'jet-smart-filters' );
	}

	public function get_icon() {
		return 'jet-smart-filters-range-filter';
	}

	public function get_script_depends() {
		if( wp_is_mobile() ){
			return array( 'jquery-touch-punch' );
		}

		return array();
	}

	public function get_help_url() {
		return jet_smart_filters()->widgets->prepare_help_url(
			'https://crocoblock.com/knowledge-base/articles/jetsmartfilters-how-to-create-a-price-range-filter-for-woocommerce-products/',
			$this->get_name()
		);
	}

	public function register_filter_style_controls() {

		$css_scheme = apply_filters(
			'jet-smart-filters/widgets/range/css-scheme',
			array(
				'slider'       => '.jet-range__slider',
				'range-values' => '.jet-range__values',
				'range'        => '.ui-slider-range',
				'range-point'  => '.ui-slider-handle',
			)
		);

		$this->start_controls_section(
			'section_slider_style',
			array(
				'label'      => esc_html__( 'Slider', 'jet-smart-filters' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_responsive_control(
			'slider_stroke',
			array(
				'label'      => esc_html__( 'Stroke', 'jet-smart-filters' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px'
				),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 20,
					),
				),
				'default' => array(
					'size' => 4,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['slider'] => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'slider_style_tabs' );

		$this->start_controls_tab(
			'slider_default_styles',
			array(
				'label' => esc_html__( 'Default', 'jet-smart-filters' ),
			)
		);

		$this->add_control(
			'slider_background_color',
			array(
				'label' => esc_html__( 'Color', 'jet-smart-filters' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['slider'] => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'slider_border',
				'label'       => esc_html__( 'Border', 'jet-smart-filters' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['slider'],
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'slider_range_styles',
			array(
				'label' => esc_html__( 'Range', 'jet-smart-filters' ),
			)
		);

		$this->add_control(
			'slider_range_background_color',
			array(
				'label' => esc_html__( 'Color', 'jet-smart-filters' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['range'] => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'slider_range_border',
				'label'       => esc_html__( 'Border', 'jet-smart-filters' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['range'],
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'slider_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-smart-filters' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['slider'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} ' . $css_scheme['range'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'range_points_heading',
			array(
				'label'     => esc_html__( 'Range Points', 'jet-smart-filters' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'range_points_width',
			array(
				'label'      => esc_html__( 'Points Width', 'jet-smart-filters' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px'
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 30,
					),
				),
				'default' => array(
					'size' => 15,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['range-point'] => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'range_points_height',
			array(
				'label'      => esc_html__( 'Points Height', 'jet-smart-filters' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px'
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 30,
					),
				),
				'default' => array(
					'size' => 15,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['range-point'] => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'range_points_background_color',
			array(
				'label' => esc_html__( 'Background Color', 'jet-smart-filters' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['range-point'] => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'range_points_border',
				'label'       => esc_html__( 'Border', 'jet-smart-filters' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['range-point'],
			)
		);

		$this->add_control(
			'range_points_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-smart-filters' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['range-point'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'range_points_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['range-point'],
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_values_style',
			array(
				'label'      => esc_html__( 'Values', 'jet-smart-filters' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'values_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['range-values'],
			)
		);

		$this->add_control(
			'values_color',
			array(
				'label' => esc_html__( 'Color', 'jet-smart-filters' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['range-values'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'values_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-smart-filters' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['range-values'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'values_alignment',
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
					'{{WRAPPER}} ' . $css_scheme['range-values'] => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

	}

}
