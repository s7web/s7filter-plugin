<?php
/**
 * Setting for individual pages template
 *
 * @package S7designFilter
 */

?>
<div class="wrap">
	<div id="poststuff">

		<div id="post-body" class="metabox-holder columns-2">

			<!-- main content -->
			<div id="post-body-content">

				<div class="meta-box-sortables ui-sortable">

					<div class="postbox">

						<div class="handlediv" title="Click to toggle"><br></div>
						<!-- Toggle -->

						<h3 class="hndle"><span><span
										class="dashicons dashicons-admin-settings"></span> <?php esc_attr_e( 'Pages settings', 's7design-filter' ); ?></span>
						</h3>

						<div class="inside">
							<p>
								<label for="s7_add_new_page"> <?php esc_html_e( 'Specify page for filter:', 's7design-filter' ) ?></label>
								<input type="text" name="s7_add_new_page" id="s7_add_new_page">
								<button class="button button-primary"
										id="s7_add_page_button"> <?php esc_html_e( 'Add page',
										's7design-filter' ); ?></button>
								<span class="description" id="s7_pages_validation" style="color: red;">
										<!-- javascript validation message -->
									</span><br>
							</p>
							<div id="s7_pages_list_container">

								<?php if ( $pages ) : ?>
									<?php foreach ( $pages as $id => $page ) : ?>
										<h3><?php esc_html_e( $page['title'] ); ?></h3>
										<form action="<?php esc_url( admin_url() ); ?>admin-post.php" method="post">
											<h4>Filter by:</h4>
											<input type="radio" <?php if ( $page['settings'] && 'tags' === $page['settings']['filter'] ) {
												echo 'checked'; }
											?> value="tags" name="s7filter-settings-data[filter]"> Tags
											<br>
											<input type="radio" <?php if ( $page['settings'] && 'categories' === $page['settings']['filter'] ) {
												echo 'checked'; }
											?> value="categories" name="s7filter-settings-data[filter]"> Categories
											<br>
											<input type="radio" <?php if ( $page['settings'] && 'both' === $page['settings']['filter'] ) {
												echo 'checked'; } ?> value="both" name="s7filter-settings-data[filter]"> Both categories and tags for
											posts
											<br>
											<input type="radio" <?php if ( $page['settings'] && 'none' === $page['settings']['filter'] ) {
												echo 'checked'; } ?> value="none" name="s7filter-settings-data[filter]"> None
											<br>
											<h4>Maximum posts per this page:</h4>
											<div class="wppf-fold">
												<input type="text" value="<?php if ( $page['settings'] ) {
													echo $page['settings']['per_page']; } ?>" class="4_posts_per_page" size="3" name="s7filter-settings-data[per_page]">
											</div>
											<h4><?php esc_html_e( 'Show pages containing tags:', 's7design-filter' ); ?></h4>
											<div class="wppf-fold">
												<fieldset>
													<?php if ( $tags ) : ?>
														<?php foreach ( $tags as $tag ) : ?>
															<input type="checkbox" <?php if ( $page['settings'] && $page['settings']['tags'] && in_array( $tag->term_id, $page['settings']['tags'] ) ) {
																echo 'checked'; } ?> value="<?php echo $tag->term_id; ?>"
																   name="s7filter-settings-data[tags][]"> <?php echo $tag->name; ?>
															<br>
														<?php endforeach; ?>
													<?php else : ?>
														<?php esc_html_e( 'No tags.', 's7design-filter' ); ?>
													<?php endif; ?>
												</fieldset>
											</div>
											<h4>Show pages from categories:</h4>
											<div class="wppf-fold">
												<fieldset>
													<?php if ( $categories ) : ?>
														<?php foreach ( $categories as $category ) : ?>
															<input type="checkbox" <?php if ( $page['settings'] && $page['settings']['categories'] && in_array( $category->term_id, $page['settings']['categories'] ) ) {
																echo 'checked'; } ?> value="<?php echo $category->term_id; ?>"
																   name="s7filter-settings-data[categories][]"> <?php echo $category->name; ?>
															<br>
														<?php endforeach; ?>
													<?php else : ?>
														<?php esc_html_e( 'No categories.', 's7design-filter' ); ?>
													<?php endif; ?>
												</fieldset>
											</div>
											<h4>Post list template:</h4>
											<div class="wppf-fold">
												<select value="default" size="1" class="4_template wppf-controls"
														id="wppf_opts[4][template]" name="s7filter-settings-data[template]">
													<option <?php if ( $page['settings'] && 'default-thumb-enabled' == $page['settings']['template'] ) {
														echo 'selected'; } ?> value="default-thumb-enabled">default-thumb-enabled
													</option>
													<option <?php if ( $page['settings'] && 'default' == $page['settings']['template'] ) {
														echo 'selected'; } ?> value="default">default
													</option>
												</select>
											</div>
											<h4><?php esc_html_e( 'Date/time settings for the page:', 's7design-filter' ); ?></h4>
											<div class="wppf-fold">
												<input type="text" value="<?php if ( $page['settings'] ) {
													echo $page['settings']['dateformat']; } ?>" class="4_dateformat wppf-controls" id="wppf_opts[4][dateformat]"
													   name="s7filter-settings-data[dateformat]">
											</div>
											<h4><?php esc_html_e( 'Heading tag for the posts on this page:', 's7design-filter' ); ?></h4>
											<div class="wppf-fold">
												<input type="text" value="<?php if ( $page['settings'] ) {
													echo $page['settings']['heading']; } ?>" class="4_heading_tag wppf-controls" name="s7filter-settings-data[heading]">
											</div>
											<h4><?php esc_html_e( 'Heading class for the posts on this page:', 's7design-filter' ); ?></h4>
											<div class="wppf-fold">
												<input type="text" value="<?php if ( $page['settings'] ) {
													echo $page['settings']['heading_class']; } ?>" class="4_heading_class wppf-controls"
													   name="s7filter-settings-data[heading_class]">
											</div>
											<br>
											<input type="hidden" name="action" value="s7_save_page_settings">
											<input type="hidden" name="page-id" value="<?php echo esc_attr( $id ); ?>">
											<input type="submit" class="button button-primary" value="Save">
										</form>
									<?php endforeach; ?>
								<?php endif; ?>
							</div>

						</div>
						<!-- .inside -->

					</div>
					<!-- .postbox -->

				</div>
				<!-- .meta-box-sortables .ui-sortable -->

			</div>
			<!-- post-body-content -->

			<!-- sidebar -->
			<div id="postbox-container-1" class="postbox-container">

				<div class="meta-box-sortables">

					<div class="postbox">

						<div class="handlediv" title="Click to toggle"><br></div>
						<!-- Toggle -->

						<h3 class="hndle"><span><span class="dashicons dashicons-sos">

								</span>
								<?php
								esc_attr_e(
									'Pages settings',
									's7design-filter'
								); ?>
							</span>
						</h3>

						<div class="inside">
							<p>
								<?php
								esc_html_e(
									'Set up pages on which will be shown filter for posts, also every page has her
									own settings, like number of displayed posts, tags, styls and etc',
									's7design-filter'
								);
								?>
							</p>
						</div>
						<!-- .inside -->

					</div>
					<!-- .postbox -->

				</div>
				<!-- .meta-box-sortables -->

			</div>
			<!-- #postbox-container-1 .postbox-container -->

		</div>
		<!-- #post-body .metabox-holder .columns-2 -->

		<br class="clear">
	</div>
	<!-- #poststuff -->

</div> <!-- .wrap -->
