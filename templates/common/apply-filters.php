<?php
/**
 * Apply filters button
 */
?>
<div class="apply-filters">
	<button
		type="button"
		class="apply-filters__button"
		data-apply-provider="<?php echo $settings['content_provider']; ?>"
		data-apply-type="<?php echo $settings['apply_type']; ?>"
		data-query-id="<?php echo $query_id; ?>"
	>
		<?php echo $settings['apply_button_text']; ?>
	</button>
</div>