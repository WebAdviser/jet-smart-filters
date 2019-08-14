<?php
/**
 * Pagination template
 */

$pages = ! empty( $props['max_num_pages'] ) ? absint( $props['max_num_pages'] ) : 1;
$page  = ! empty( $props['page'] ) ? absint( $props['page'] ) : 1;
$pages_mid_size  = ! empty( $controls['pages_mid_size'] ) ? absint( $controls['pages_mid_size'] ) : 0;
$pages_end_size  = ! empty( $controls['pages_end_size'] ) ? absint( $controls['pages_end_size'] ) : 0;
$pages_show_all = ( 0 === $pages_mid_size ) ? true : false;
$dots = true;

if ( 2 > $pages ) {
	return;
}

?>
<div class="jet-filters-pagination">
	<?php if ( 1 < $page && $controls['nav'] ) : ?>
	<span class="jet-filters-pagination__item prev-next prev">
		<a class="jet-filters-pagination__link prev-next prev"<?php jet_smart_filters()->render->pager_data_atts(
			array(
				$provider,
				$apply_type,
				$page - 1,
				$query_id
			)
		); ?>><?php
			echo $controls['prev'];
		?></a>
	</span>
	<?php endif; ?>
	<?php
		for ( $i = 1; $i <= $pages ; $i++ ) {
			$current = ( $page === $i ) ? ' jet-filters-pagination__link-current' : '';
			$show_dots =  ( $pages_end_size < $i && $i < $page - $pages_mid_size ) || ( $pages_end_size <= ( $pages - $i ) && $i > $page + $pages_mid_size ) ;

			if ( !$show_dots || $pages_show_all ) {
				?>
				<span class="jet-filters-pagination__item">
					<a class="jet-filters-pagination__link<?php echo $current; ?>"<?php jet_smart_filters()->render->pager_data_atts(
							array(
								$provider,
								$apply_type,
								$i,
								$query_id
							)
						); ?>><?php
						echo $i;
					?></a>
				</span>
				<?php
				$dots = true;
			} elseif ( $dots ) {
			  printf( '<span class="jet-filters-pagination__item"><span class="jet-filters-pagination__dots">%s</span></span>', __( '&hellip;', 'jet-smart-filters' ) );
			  $dots = false;
		  }

	}
	?>
	<?php if ( $pages !== $page && $controls['nav'] ) : ?>
		<span class="jet-filters-pagination__item prev-next next">
			<a class="jet-filters-pagination__link prev-next next"<?php jet_smart_filters()->render->pager_data_atts(
				array(
					$provider,
					$apply_type,
					$page + 1,
					$query_id
				)
			); ?>><?php
				echo $controls['next'];
			?></a>
		</span>
	<?php endif; ?>
</div>
