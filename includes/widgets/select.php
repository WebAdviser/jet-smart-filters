<?php

namespace Elementor;

use Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class Jet_Smart_Filters_Select_Widget extends Jet_Smart_Filters_Base_Widget {

	public function get_name() {
		return 'jet-smart-filters-select';
	}

	public function get_title() {
		return __( 'Select Filter', 'jet-smart-filters' );
	}

	public function get_icon() {
		return 'jet-smart-filters-select-filter';
	}

	public function get_help_url() {
		return jet_smart_filters()->widgets->prepare_help_url(
			'https://crocoblock.com/knowledge-base/articles/jetsmartfilters-how-to-use-the-select-filter-to-filter-publications-or-products/',
			$this->get_name()
		);
	}

	public function register_filter_style_controls() {

		$css_scheme = apply_filters(
			'jet-smart-filters/widgets/select/css-scheme',
			array(
				'filter' => '.jet-smart-filters-select .jet-select',
				'select' => '.jet-smart-filters-select .jet-select__control',
			)
		);

		$this->start_controls_section(
			'section_content_style',
			array(
				'label'      => esc_html__( 'Content', 'jet-smart-filters' ),
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
					'{{WRAPPER}} ' . $css_scheme['filter'] => '{{VALUE}}',
				),
				'prefix_class' => 'jet-smart-filter-content-position-',
			)
		);

		$this->add_responsive_control(
			'select_width',
			array(
				'label'      => esc_html__( 'Select Width', 'jet-smart-filters' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'%',
					'px',
				),
				'range'      => array(
					'%'  => array(
						'min' => 10,
						'max' => 100,
					),
					'px' => array(
						'min' => 50,
						'max' => 400,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 150,
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['select'] => 'max-width: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_select_style',
			array(
				'label' => esc_html__( 'Select', 'jet-smart-filters' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'select_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['select'],
			)
		);

		$this->add_control(
			'select_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-smart-filters' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['select'] => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'select_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-smart-filters' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['select'] => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'select_border',
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['select'],
				'separator'   => 'before'

			)
		);

		$this->add_control(
			'select_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-smart-filters' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['select'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'select_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['select'],
			)
		);

		$this->add_responsive_control(
			'select_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-smart-filters' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['select'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before'
			)
		);

		$this->add_responsive_control(
			'select_alignment',
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
				'selectors_dictionary' => array(
					'left'   => 'margin-left: 0; margin-right: auto;',
					'center' => 'margin-left: auto; margin-right: auto;',
					'right'  => 'margin-left: auto; margin-right: 0;',
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['select'] => '{{VALUE}}',
				),
			)
		);

		$this->end_controls_section();

	}

}
