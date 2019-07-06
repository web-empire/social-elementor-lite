<?php
/**
 * General Setting Form
 *
 * @package SOCIAL_ELEMENTOR
 */

use SocialElementor\Classes\Social_Helper;

$widgets       = Social_Helper::get_widget_options();

$kb_url = apply_filters( 'social_knowledge_base_link', '#' );

$code_doc_url = apply_filters( 'social_code_snippets_link', '#' );

?>

<div class="social-container social-general">
<div id="poststuff">
	<div id="post-body" class="columns-2">
		<div id="post-body-content">
			<!-- All WordPress Notices below header -->
			<h1 class="screen-reader-text"> <?php _e( 'General', 'social-elementor' ); ?> </h1>
				<div class="widgets postbox">
					<h2 class="hndle social-flex social-widgets-heading"><span><?php esc_html_e( 'Social & Blog Post Elements', 'social-elementor' ); ?></span>
						<div class="social-bulk-actions-wrap">
							<a class="bulk-action social-activate-all button"> <?php esc_html_e( 'Activate All', 'social-elementor' ); ?> </a>
							<a class="bulk-action social-deactivate-all button"> <?php esc_html_e( 'Deactivate All', 'social-elementor' ); ?> </a>
						</div>
					</h2>
				</div>

				<div class="social-widgets-boxes">
					<div class="social-widget-section">
						<?php if ( is_array( $widgets ) && ! empty( $widgets ) ) : ?>

							<ul class="social-widget-list">
								<?php
								foreach ( $widgets as $addon => $info ) {
									$doc_url       = ( isset( $info['doc_url'] ) && ! empty( $info['doc_url'] ) ) ? ' href="' . esc_url( $info['doc_url'] ) . '"' : '';
									$anchor_target = ( isset( $info['doc_url'] ) && ! empty( $info['doc_url'] ) ) ? " target='_blank' rel='noopener'" : '';

									$class = 'deactivate';
									$link  = array(
										'link_class' => 'social-activate-widget',
										'link_text'  => __( 'Activate', 'social-elementor' ),
									);

									if ( $info['is_activate'] && ! $info['is_pro'] ) {
										$class = 'activate';
										$link  = array(
											'link_class' => 'social-deactivate-widget',
											'link_text'  => __( 'Deactivate', 'social-elementor' ),
										);
									}
									switch ( $info['slug'] ) {
										case 'social-white-label':
											$class = $info['slug'];
											$link  = array(
												'link_class' => 'social-white-label-module',
												'link_text'  => __( 'Settings', 'social-elementor' ),
												'link_url'   => admin_url( 'options-general.php?page=' . SOCIAL_ELEMENTOR_SLUG . '&action=branding' ),
											);
											break;
									}

									$features = '';
									$features .= '<div class="social-widget-features">';
									$features .= '<p class="widget-feature">' . $info['features']['first'] . '</p>';
									$features .= '<p class="widget-feature">' . $info['features']['second'] . '</p>';
									$features .= '<p class="widget-feature">' . $info['features']['third'] . '</p>';
									$features .= '<p class="widget-feature">' . $info['features']['fourth'] . '</p>';
									$features .= '<p class="widget-feature">' . $info['features']['fifth'] . '</p>';
									$features .= '<p class="widget-feature">' . $info['features']['sixth'] . '</p>';
									$features .= '<p class="widget-feature">' . $info['features']['seventh'] . '</p>';
									$features .= '</div>';

									echo '<li id="' . esc_attr( $addon ) . '"  class="social-widget-wrapper ' . esc_attr( $class ) . '">';
									echo '<h3> <a class="social-widget-title"' . $doc_url . $anchor_target . ' >' . esc_html( $info['title'] ) . '</a>';

									if ( $info['is_pro'] ) {

										$pro_url = '#';
										$pro_label = __( 'Releasing Soon!!!', 'social-elementor' );

										printf(
											'<a href="%1$s" class="get-pro-social"> %2$s </a>',
											esc_url( $pro_url ),
											esc_html( $pro_label )
										);
									} else {
										printf(
											'<a href="%1$s" class="%2$s"> %3$s </a>',
											( isset( $link['link_url'] ) && ! empty( $link['link_url'] ) ) ? esc_url( $link['link_url'] ) : '#',
											esc_attr( $link['link_class'] ),
											esc_html( $link['link_text'] )
										);
									}

									if ( $info['is_activate'] && isset( $info['setting_url'] ) ) {

										printf(
											'<a href="%1$s" class="%2$s"> %3$s </a>',
											esc_url( $info['setting_url'] ),
											esc_attr( 'social-advanced-settings' ),
											esc_html( $info['setting_text'] )
										);
									}

									echo '</h3> <div class="social-widget-link-wrapper">' . $features . '';

									echo '</div></li>';
								}
								?>
							</ul>
						<?php endif; ?>
					</div>
				</div>
		</div>
		<div class="postbox-container social-sidebar" id="postbox-container-1">
			<div id="side-sortables">
				<div class="postbox">
					<h2 class="hndle social-normal-cusror">
						<span><?php esc_html_e( 'Upcoming Social Elements', 'social-elementor' ); ?></span>
						<span class="dashicons dashicons-share"></span>
					</h2>
					<div class="inside">
						<p>
							<?php
							esc_html_e( 'Here is the tentative upcoming releases in the Social Elementor.We hope you like it!!!', 'social-elementor' );
							?>
						</p>
						
						<?php

							$upcoming_widgets	= array (
								'first'		=>	__( 'Twitter Posts', 'social-elementor' ),
								'second'	=>	__( 'Facebook Posts', 'social-elementor' ),
								'third'		=>	__( 'Instagram Posts', 'social-elementor' ),
								'fourth'	=>	__( 'Google Reviews Posts', 'social-elementor' ),
								'fifth'		=>	__( 'Social Connection Button', 'social-elementor' ),
								'sixth'		=>	__( 'Much More with Improvements', 'social-elementor' ),
							);

							$features = '';
							$features .= '<div class="social-widget-features">';
							$features .= '<p class="widget-feature"><b class="feature"> 01. </b>' . $upcoming_widgets['first'] . '</p>';
							$features .= '<p class="widget-feature"><b class="feature"> 02. </b>' . $upcoming_widgets['second'] . '</p>';
							$features .= '<p class="widget-feature"><b class="feature"> 03. </b>' . $upcoming_widgets['third'] . '</p>';
							$features .= '<p class="widget-feature"><b class="feature"> 04. </b>' . $upcoming_widgets['fourth'] . '</p>';
							$features .= '<p class="widget-feature"><b class="feature"> 05. </b>' . $upcoming_widgets['fifth'] . '</p>';
							$features .= '<p class="widget-feature"><b class="feature"> </b>' . $upcoming_widgets['sixth'] . '</p>';
							$features .= '</div>';

							echo $features; ?>

							<p>
								<?php
								esc_html_e( 'You can contribute to improvise this plugin or can give us suggessions to make it more better. Your contribution will highly appreciated!', 'social-elementor' );
								?>
							</p>

							<?php $contribute_suggession      = apply_filters( 'contribute_suggession', '#' );
							$contribute_suggession_text = apply_filters( 'contribute_suggession_text', __( 'Coming Soon »', 'social-elementor' ) );

							printf(
								/* translators: %1$s: demos link. */
								'%1$s',
								! empty( $contribute_suggession ) ? '<a href=' . esc_url( $contribute_suggession ) . ' target="_blank" rel="noopener">' . esc_html( $contribute_suggession_text ) . '</a>' :
								esc_html( $contribute_suggession_text )
							);
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- /post-body -->
	<br class="clear"/>
</div>

<div class="social-container social-connect">
	<div id="poststuff">
		<div id="post-body" class="columns-2">
			<div id="post-body-content">
				<div id="side-sortables">
					<div class="postbox">
						<h2 class="hndle social-normal-cusror">
							<span><?php esc_html_e( 'Visit Demos', 'social-elementor' ); ?></span>
							<span class="dashicons dashicons-welcome-view-site"></span>
						</h2>
						<div class="inside">
							<p>
								<?php
								esc_html_e( 'Visit here to see our elegant demos for these widgets. We hope you like it!!!', 'social-elementor' );
								?>
							</p>
								<?php
									$visit_demos      = apply_filters( 'visit_demos', '#' );
									$visit_demos_text = apply_filters( 'visit_demos_text', __( 'Coming Soon »', 'social-elementor' ) );
									// $visit_demos_text = apply_filters( 'visit_demos_text', __( 'View Demos »', 'social-elementor' ) );

									printf(
										/* translators: %1$s: demos link. */
										'%1$s',
										! empty( $visit_demos ) ? '<a href=' . esc_url( $visit_demos ) . ' target="_blank" rel="noopener">' . esc_html( $visit_demos_text ) . '</a>' :
										esc_html( $visit_demos_text )
									);
								?>
							</p>
						</div>
					</div>

					<div class="postbox">
						<h2 class="hndle social-normal-cusror">
							<span><?php esc_html_e( 'Knowledge Base', 'social-elementor' ); ?></span>
							<span class="dashicons dashicons-book"></span>
						</h2>
						<div class="inside">
							<p>
								<?php esc_html_e( 'Not sure how something works? Take a peek at the knowledge base and learn.', 'social-elementor' ); ?>
							</p>
							<a href='<?php echo esc_url( $kb_url ); ?> ' target="_blank" rel="noopener"><?php esc_attr_e( 'Coming Soon »', 'social-elementor' ); ?></a>
						</div>
					</div>

					<div class="postbox">
						<h2 class="hndle social-normal-cusror">
							<span><?php esc_html_e( 'Code Snippets', 'social-elementor' ); ?></span>
							<span class="dashicons dashicons-editor-code"></span>
						</h2>
						<div class="inside">
							<p>
								<?php esc_html_e( 'Social Widget\'s Actions and Filters are listed here, which will help you to configure custom requirements.', 'social-elementor' ); ?>
							</p>
							<a href='<?php echo esc_url( $code_doc_url ); ?> ' target="_blank" rel="noopener"><?php esc_attr_e( 'Coming Soon »', 'social-elementor' ); ?></a>
						</div>
					</div>

						<div class="postbox">
							<h2 class="hndle social-normal-cusror">
								<span><?php esc_html_e( 'Five Star Support', 'social-elementor' ); ?></span>
								<span class="dashicons dashicons-awards"></span>
							</h2>
							<div class="inside">
								<p>
									<?php
									printf(
										/* translators: %1$s: social name. */
										esc_html_e( 'Got a question? Get in touch with Social & Blog Posts Addons for Elementor developers. We\'re happy to help!', 'social-elementor' )
									);
									?>
								</p>
								<?php
									$social_support_link      = apply_filters( 'social_support_link', '#' );
									$social_support_link_text = apply_filters( 'social_support_link_text', __( 'Coming Soon »', 'social-elementor' ) );

									printf(
										/* translators: %1$s: social support link. */
										'%1$s',
										! empty( $social_support_link ) ? '<a href=' . esc_url( $social_support_link ) . ' target="_blank" rel="noopener">' . esc_html( $social_support_link_text ) . '</a>' :
										esc_html( $social_support_link_text )
									);
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
