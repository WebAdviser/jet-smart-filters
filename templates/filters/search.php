<?php

if ( empty( $args ) ) {
	return;
}

$query_var   = $args['query_var'];
$placeholder = $args['placeholder'];
$current     = $this->get_current_filter_value( $args );
$classes = array(
	'jet-search-filter'
);

if ( '' !== $args['button_icon'] ) {
	$classes[] = 'button-icon-position-' . $args['button_icon_position'];
}
?>
<div class="<?php echo implode( ' ', $classes ) ?>" <?php $this->filter_data_atts( $args, $filter ); ?>>
	<?php include jet_smart_filters()->get_template( 'common/filter-label.php' ); ?>
	<input
		class="jet-search-filter__input"
		type="search"
		autocomplete="off"
		name="<?php echo $query_var; ?>"
		value="<?php echo $current; ?>"
		placeholder="<?php echo $placeholder; ?>"
		<?php $this->control_data_atts( $args ); ?>
	>
	<button
		type="button"
		class="jet-search-filter__submit apply-filters__button"
		data-apply-type="<?php echo $args['apply_type']; ?>"
		data-apply-provider="<?php echo $args['content_provider']; ?>"
		data-query-id="<?php echo $args['query_id']; ?>"
	>
		<?php echo 'left' === $args['button_icon_position'] ? $args['button_icon'] : ''; ?>
		<span class="jet-search-filter__submit-text"><?php echo $args['button_text']; ?></span>
		<?php echo 'right' === $args['button_icon_position'] ? $args['button_icon'] : ''; ?>
	</button>
</div>
