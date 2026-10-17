<?php
/**
 * Content types the owner edits from the WordPress dashboard.
 * None of them have public single pages; they only feed the landing page.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function bpsl_post_types() {
	return array(
		'bpsl_package'     => array( 'Package', 'Packages', 'dashicons-tag', array( 'title', 'editor', 'page-attributes' ) ),
		'bpsl_location'    => array( 'Location', 'Locations', 'dashicons-location', array( 'title', 'editor', 'thumbnail', 'page-attributes' ) ),
		'bpsl_gallery'     => array( 'Gallery Photo', 'Gallery', 'dashicons-format-gallery', array( 'title', 'thumbnail', 'page-attributes' ) ),
		'bpsl_testimonial' => array( 'Testimonial', 'Testimonials', 'dashicons-format-quote', array( 'title', 'editor', 'page-attributes' ) ),
		'bpsl_faq'         => array( 'FAQ', 'FAQs', 'dashicons-editor-help', array( 'title', 'editor', 'page-attributes' ) ),
	);
}

add_action(
	'init',
	function () {
		foreach ( bpsl_post_types() as $slug => $cfg ) {
			register_post_type(
				$slug,
				array(
					'labels'              => array(
						'name'          => $cfg[1],
						'singular_name' => $cfg[0],
						'add_new_item'  => 'Add new ' . strtolower( $cfg[0] ),
						'edit_item'     => 'Edit ' . strtolower( $cfg[0] ),
						'menu_name'     => $cfg[1],
					),
					'public'              => false,
					'show_ui'             => true,
					'show_in_menu'        => true,
					'show_in_rest'        => true,
					'exclude_from_search' => true,
					'publicly_queryable'  => false,
					'has_archive'         => false,
					'rewrite'             => false,
					'menu_icon'           => $cfg[2],
					'menu_position'       => 21,
					'supports'            => $cfg[3],
				)
			);
		}
	}
);

/** Extra fields per type: key => array( label, input type, help ). */
function bpsl_meta_config() {
	return array(
		'bpsl_package'     => array(
			'price'      => array( 'Price in IDR (numbers only, e.g. 500000)', 'number', '' ),
			'unit'       => array( 'Price is per', 'text', 'Shown as "/ person". Use "person" or "group".' ),
			'instructor' => array( 'Instructor', 'text', 'e.g. 1 Teacher' ),
			'students'   => array( 'Students', 'text', 'e.g. 1 Student' ),
			'duration'   => array( 'Duration', 'text', 'e.g. 2 Hours + 15 Min Break' ),
			'badge'      => array( 'Badge (optional)', 'text', 'e.g. Most popular. Also highlights the card.' ),
		),
		'bpsl_location'    => array(
			'level' => array( 'Good for', 'text', 'e.g. Beginners and improvers' ),
		),
		'bpsl_testimonial' => array(
			'origin' => array( 'Guest country or city', 'text', 'e.g. Australia' ),
			'rating' => array( 'Rating (1 to 5)', 'number', '' ),
		),
	);
}

add_action(
	'add_meta_boxes',
	function () {
		foreach ( array_keys( bpsl_meta_config() ) as $type ) {
			add_meta_box( 'bpsl_details', 'Details', 'bpsl_render_meta_box', $type, 'normal', 'high' );
		}
	}
);

function bpsl_render_meta_box( $post ) {
	wp_nonce_field( 'bpsl_save_meta', 'bpsl_meta_nonce' );
	$fields = bpsl_meta_config();
	if ( empty( $fields[ $post->post_type ] ) ) {
		return;
	}
	echo '<table class="form-table" role="presentation">';
	foreach ( $fields[ $post->post_type ] as $key => $field ) {
		$value = bpsl_meta( $post->ID, $key );
		printf(
			'<tr><th scope="row"><label for="bpsl_%1$s">%2$s</label></th><td><input class="regular-text" type="%3$s" id="bpsl_%1$s" name="bpsl_%1$s" value="%4$s" %5$s>%6$s</td></tr>',
			esc_attr( $key ),
			esc_html( $field[0] ),
			esc_attr( $field[1] ),
			esc_attr( $value ),
			'number' === $field[1] ? 'min="0" step="1"' : '',
			$field[2] ? '<p class="description">' . esc_html( $field[2] ) . '</p>' : ''
		);
	}
	echo '</table>';
}

add_action(
	'save_post',
	function ( $post_id, $post ) {
		if ( ! isset( $_POST['bpsl_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['bpsl_meta_nonce'] ) ), 'bpsl_save_meta' ) ) {
			return;
		}
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
		$config = bpsl_meta_config();
		if ( empty( $config[ $post->post_type ] ) ) {
			return;
		}
		foreach ( $config[ $post->post_type ] as $key => $field ) {
			$name = 'bpsl_' . $key;
			if ( ! isset( $_POST[ $name ] ) ) {
				continue;
			}
			$raw = sanitize_text_field( wp_unslash( $_POST[ $name ] ) );
			if ( 'number' === $field[1] ) {
				$raw = '' === $raw ? '' : absint( $raw );
				if ( 'rating' === $key && '' !== $raw ) {
					$raw = max( 1, min( 5, $raw ) );
				}
			}
			update_post_meta( $post_id, '_bpsl_' . $key, $raw );
		}
	},
	10,
	2
);
