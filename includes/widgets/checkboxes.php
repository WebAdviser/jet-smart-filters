<?php

namespace Elementor;

use Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class Jet_Smart_Filters_Checkboxes_Widget extends Jet_Smart_Filters_Base_Widget {

	public function get_name() {
		return 'jet-smart-filters-checkboxes';
	}

	public function get_title() {
		return __( 'Checkboxes Filter', 'jet-smart-filters' );
	}

	public function get_icon() {
		return 'jet-smart-filters-checkboxes-filter';
	}

	public function get_help_url() {
		return jet_smart_filters()->widgets->prepare_help_url(
			'https://crocoblock.com/knowledge-base/articles/jetsmartfilters-how-to-create-a-checkboxes-filter-a-difference-between-checkboxes-select-and-radio-filters/',
			$this->get_name()
		);
	}

	public function register_filter_style_controls() {

		$css_scheme = apply_filters(
			'jet-smart-filters/widgets/checkboxes/css-scheme',
			array(
				'item'                  => '.jet-checkboxes-list__row',
				'child-items'           => '.jet-list-tree__children',
				'label'                 => '.jet-checkboxes-list__label',
				'checkbox'              => '.jet-checkboxes-list__decorator',
				'checkbox-checked-icon' => '.jet-checkboxes-list__checked-icon',
				'list-item'             => '.jet-checkboxes-list__row',
				'list-wrapper'          => '.jet-checkboxes-list-wrapper',
				'list-children'         => '.jet-list-tree__children',
			)
		);

		$this->start_controls_section(
			'section_items_style',
			array(
				'label'      => esc_html__( 'Items', 'jet-smart-filters' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->register_horizontal_layout_controls( $css_scheme );

		$this->add_responsive_control(
			'items_space_between',
			array(
				'label'      => esc_html__( 'Space Between', 'jet-smart-filters' ),
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
					'size' => 10,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['child-items'] . ':not(:last-child)'  => 'margin-bottom: calc({{SIZE}}{{UNIT}}/2);',
					'{{WRAPPER}} ' . $css_scheme['child-items'] . ':not(:first-child)' => 'padding-top: calc({{SIZE}}{{UNIT}}/2);',
					'{{WRAPPER}} ' . $css_scheme['item'] . ':not(:last-child)'         => 'margin-bottom: calc({{SIZE}}{{UNIT}}/2);',
					'{{WRAPPER}} ' . $css_scheme['item'] . ':not(:first-child)'        => 'padding-top: calc({{SIZE}}{{UNIT}}/2);',
				),
				'condition'  => array(
					'filters_position!' => 'inline-block'
				)
			)
		);

		$this->add_responsive_control(
			'sub_items_offset_left',
			array(
				'label'      => esc_html__( 'Children Offset Left', 'jet-smart-filters' ),
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
					'size' => 10,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['child-items'] => 'padding-left: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_checkbox_style',
			array(
				'label'      => esc_html__( 'Checkbox', 'jet-smart-filters' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_responsive_control(
			'checkbox_size',
			array(
				'label'      => esc_html__( 'Size', 'jet-smart-filters' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px'
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
					'{{WRAPPER}} ' . $css_scheme['checkbox'] => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'checkbox_style_tabs' );

		$this->start_controls_tab(
			'checkbox_normal_styles',
			array(
				'label' => esc_html__( 'Normal', 'jet-smart-filters' ),
			)
		);

		$this->add_control(
			'checkbox_normal_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-smart-filters' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['checkbox'] => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'checkbox_checked_styles',
			array(
				'label' => esc_html__( 'Checked', 'jet-smart-filters' ),
			)
		);

		$this->add_control(
			'checkbox_checked_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-smart-filters' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-checkboxes-list__input:checked +' . $css_scheme['checkbox'] => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'checkbox_checked_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'jet-smart-filters' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-checkboxes-list__input:checked +' . $css_scheme['checkbox'] => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'checkbox_border',
				'label'       => esc_html__( 'Border', 'jet-smart-filters' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['checkbox'],
			)
		);

		$this->add_control(
			'checkbox_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-smart-filters' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['checkbox'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow:hidden;',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_checked_icon_style',
			array(
				'label'      => esc_html__( 'Checkbox Icon', 'jet-smart-filters' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_responsive_control(
			'checked_icon_size',
			array(
				'label'      => esc_html__( 'Size', 'jet-smart-filters' ),
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
				'default'    => array(
					'size' => 12,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['checkbox-checked-icon'] => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'checked_icon_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-smart-filters' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['checkbox-checked-icon'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_checkbox_label_style',
			array(
				'label'      => esc_html__( 'Checkbox Label', 'jet-smart-filters' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'checkbox_label_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['label'],
			)
		);

		$this->add_responsive_control(
			'checkbox_label_offset',
			array(
				'label'      => esc_html__( 'Offset Left', 'jet-smart-filters' ),
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
				'default'    => array(
					'size' => 5,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['label'] => 'margin-left: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'checkbox_label_style_tabs' );

		$this->start_controls_tab(
			'checkbox_label_normal_styles',
			array(
				'label' => esc_html__( 'Normal', 'jet-smart-filters' ),
			)
		);

		$this->add_control(
			'checkbox_label_normal_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-smart-filters' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['label'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'checkbox_label_checked_styles',
			array(
				'label' => esc_html__( 'Checked', 'jet-smart-filters' ),
			)
		);

		$this->add_control(
			'checkbox_label_checked_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-smart-filters' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-checkboxes-list__input:checked ~' . $css_scheme['label'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

	}

}