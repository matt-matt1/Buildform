<?php
/**
 * 404.php
 *
 * The template for displaying 404 pages (not found)
 *
 * @author    BetterStudio
 * @package   Publisher
 * @version   2.0.2
 */

get_header();

// Shows breadcrumb
if ( (function_exists('publisher_show_breadcrumb') && publisher_show_breadcrumb() ) {
	Better_Framework()->breadcrumb()->generate( array(
		'before'       => '<div class="container bf-breadcrumb-container">',
		'after'        => '</div>',
		'custom_class' => 'bc-top-style'
	) );
}
?>
	<div class="content-wrap">
		<main <?php if (function_exists('publisher_attr')) { publisher_attr( 'content', '' ); } ?>>

			<div class="container layout-1-col layout-no-sidebar">
				<div class="row main-section">

					<div class="content-column content-404">

						<div class="row first-row">

							<div class="col-lg-12 text-404-section">
								<p class="text-404 heading-typo">404</p>
							</div>

							<div class="col-lg-12 desc-section">
								<h1 class="title-404"><?php function_exists('publisher_translation_echo') ? publisher_translation_echo( '404_not_found' ) : t('_404_not_found'); ?></h1>
								<p><?php t( '_404_not_found_message' ); ?></p>
								<div class="action-links clearfix">

									<script type="text/javascript">
										if (document.referrer) {
											document.write('<div class="search-action-container"><a href="' + document.referrer + '"><i class="fa fa-angle-double-right"></i> <?php publisher_translation_echo( '404_go_previous_page' ); ?></a></div>');
										}
									</script>

									<div class="search-action-container">
										<a href="<?php echo function_exists('esc_url') && function_exists('home_url') ? esc_url( home_url( '/' ) ) : BASE; ?>"><i
												class="fa fa-angle-double-right"></i> <?php t( '_404_go_homepage' ); ?>
										</a>
									</div>
								</div>
							</div>

						</div><!-- .first-row -->

						<div class="row second-row">
							<div class="col-lg-12">
								<div class="top-line">
									<?php if (function_exists('get_search_form')) { get_search_form(); } ?>
								</div>
							</div>
						</div><!-- .second-row -->

					</div><!-- .content-column -->

				</div><!-- .main-section -->
			</div> <!-- .layout-1-col -->

		</main><!-- main -->
	</div><!-- .content-wrap -->

<?php if (function_exists('get_footer')) { get_footer(); } ?>

