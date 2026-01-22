<?php
/**
 * Search Form template
 *
 * @package IMMIGRO
 * @author tonatheme
 * @version 1.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Restricted' );
}
?>

<div class="form-inner">
	<form action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get" class="search-form">
		<div class="form-group">
			<input type="search" name="s" placeholder="<?php echo esc_attr__( 'Search here', 'immigro' ); ?>" required="">
			<button type="submit"><i class="icon-6"></i></button>
		</div>
	</form>
</div>