<?php
/**
 * Remove filters button
 */
?>
<div class="jet-remove-all-filters">
	<button
		type="button"
		class="jet-remove-all-filters__button"
		data-apply-provider="<?php echo $settings['content_provider']; ?>"
		data-apply-type="<?php echo $settings['apply_type']; ?>"
		data-query-id="<?php echo $query_id; ?>"
	>
		<?php echo $settings['remove_filters_text']; ?>
	</button>
</div>