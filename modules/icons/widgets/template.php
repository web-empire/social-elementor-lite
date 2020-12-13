<?php
/**
 * Social Icons Module Template.
 *
 * @package SOCIAL_ELEMENTOR
 */

use Elementor\Icons_Manager;
use SocialElementor\Classes\Social_Helper;

$total_social_icons     = $settings['se_social_icon_total'];
$is_sticky              = $settings['se_social_icon_is_sticky'];
$sticky_custom_selector = '';

if ( 'yes' === $is_sticky ) {
	$sticky_custom_selector = 'se-social-icon-is-sticky';
}

$is_separator_enable     = $settings['se_social_icon_need_sep'];
$se_social_icon_sep_type = $settings['se_social_icon_sep_type'];
$is_custom_separators    = $settings['custom_separator'];
$separators              = $is_custom_separators ? $is_custom_separators : '';

?>
<div class="se-social-icons-wrapper <?php echo $sticky_custom_selector; ?>">
	<?php
	foreach ( $total_social_icons as $key => $icons ) {
		$icon         = $icons['se_social_icon']['value'];
		$social_label = esc_html( $icons['se_social_icon_name'] );
		$link_attr    = 'link_' . $key;

		if ( ! empty( $icons['se_social_icon'] ) ) {
			$social_name = str_replace( array( 'fa fa-', 'fab fa-', 'far fa-' ), '', $icon );
		}

		$this->add_link_attributes( $link_attr, $icons['se_social_icon_link'] );

		$this->add_render_attribute(
			$link_attr,
			'class',
			array(
				'se-social-icon',
				'elementor-repeater-item-' . $icons['_id'] . ' ',
				'elementor-social-icon-' . ( $icon ? $social_name : 'label' ),
			)
		);

		if ( ! empty( $icon ) ) {
			$this->add_render_attribute( $link_attr, 'class', 'se-social-icon-trigger' );
		} else {
			$this->add_render_attribute( $link_attr, 'class', 'se-social-icon--empty' );
		}

		?>
			<a <?php echo $this->get_render_attribute_string( $link_attr ); ?>>
				<?php
				Icons_Manager::render_icon( $icons['se_social_icon'] );
				if ( ! empty( $social_label ) && '' !== $social_label ) {
					echo "<span class='se-social-icon-label'>" . $social_label . '</span>';
				}

				?>
			</a>
		<?php

		if ( 'yes' === $is_separator_enable ) {
			echo "<span class='se-social-icon-sep'> " . $separators . ' </span>';
		}
	}
	?>
</div>
