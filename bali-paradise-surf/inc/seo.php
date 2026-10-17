<?php
/**
 * Lightweight SEO: title, description, Open Graph, schema.org JSON-LD.
 * Skipped automatically when Yoast SEO or Rank Math is active.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function bpsl_seo_plugin_active() {
	return defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) || defined( 'SEOPRESS_VERSION' );
}

add_filter(
	'pre_get_document_title',
	function ( $title ) {
		if ( bpsl_seo_plugin_active() || ! is_front_page() ) {
			return $title;
		}
		return 'Bali Paradise Surf Lesson | Surf Lessons in Seminyak, Bali';
	}
);

function bpsl_description() {
	if ( is_front_page() ) {
		return (string) bpsl_opt( 'meta_description' );
	}
	if ( is_singular() ) {
		$text = wp_strip_all_tags( get_the_excerpt() );
		if ( $text ) {
			return wp_trim_words( $text, 26, '...' );
		}
	}
	return (string) get_bloginfo( 'description' );
}

add_action(
	'wp_head',
	function () {
		if ( bpsl_seo_plugin_active() ) {
			return;
		}
		$desc  = bpsl_description();
		$title = wp_get_document_title();
		$url   = is_front_page() ? home_url( '/' ) : ( is_singular() ? get_permalink() : home_url( add_query_arg( array() ) ) );
		$img   = '';
		$hero  = (int) bpsl_opt( 'hero_image' );
		if ( is_singular() && has_post_thumbnail() ) {
			$img = get_the_post_thumbnail_url( null, 'large' );
		} elseif ( $hero ) {
			$img = wp_get_attachment_image_url( $hero, 'large' );
		}

		if ( $desc ) {
			printf( '<meta name="description" content="%s">' . "\n", esc_attr( $desc ) );
		}
		printf( '<meta property="og:type" content="%s">' . "\n", is_singular( 'post' ) ? 'article' : 'website' );
		printf( '<meta property="og:site_name" content="%s">' . "\n", esc_attr( get_bloginfo( 'name' ) ) );
		printf( '<meta property="og:title" content="%s">' . "\n", esc_attr( $title ) );
		if ( $desc ) {
			printf( '<meta property="og:description" content="%s">' . "\n", esc_attr( $desc ) );
		}
		printf( '<meta property="og:url" content="%s">' . "\n", esc_url( $url ) );
		if ( $img ) {
			printf( '<meta property="og:image" content="%s">' . "\n", esc_url( $img ) );
			echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
		}
	},
	5
);

function bpsl_print_jsonld( $data ) {
	echo '<script type="application/ld+json">' . wp_json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP ) . '</script>' . "\n";
}

add_action(
	'wp_head',
	function () {
		if ( ! is_front_page() || bpsl_seo_plugin_active() ) {
			return;
		}
		$b = bpsl_business();

		$business = array(
			'@context'        => 'https://schema.org',
			'@type'           => array( 'LocalBusiness', 'SportsActivityLocation' ),
			'@id'             => home_url( '/#business' ),
			'name'            => $b['name'],
			'description'     => bpsl_description(),
			'url'             => home_url( '/' ),
			'address'         => array(
				'@type'           => 'PostalAddress',
				'streetAddress'   => $b['street'],
				'addressLocality' => $b['locality'],
				'addressRegion'   => $b['region'],
				'addressCountry'  => $b['country'],
			),
			'aggregateRating' => array(
				'@type'       => 'AggregateRating',
				'ratingValue' => $b['rating'],
				'bestRating'  => '5',
				'reviewCount' => (string) $b['reviews'],
			),
		);

		$hero = (int) bpsl_opt( 'hero_image' );
		if ( $hero ) {
			$business['image'] = wp_get_attachment_image_url( $hero, 'large' );
		}
		if ( bpsl_wa_number() ) {
			$business['telephone'] = '+' . bpsl_wa_number();
		}
		if ( bpsl_opt( 'email' ) ) {
			$business['email'] = bpsl_opt( 'email' );
		}
		$same = array_values( array_filter( array( bpsl_opt( 'instagram' ), bpsl_opt( 'tripadvisor' ), bpsl_opt( 'google_reviews' ), bpsl_opt( 'maps_url' ) ) ) );
		if ( $same ) {
			$business['sameAs'] = $same;
		}

		$offers = array();
		foreach ( bpsl_get_posts( 'bpsl_package' ) as $p ) {
			$price = (int) bpsl_meta( $p->ID, 'price' );
			if ( ! $price ) {
				continue;
			}
			$offers[] = array(
				'@type'         => 'Offer',
				'price'         => (string) $price,
				'priceCurrency' => 'IDR',
				'itemOffered'   => array(
					'@type'       => 'Service',
					'name'        => get_the_title( $p ),
					'description' => wp_strip_all_tags( $p->post_content ),
				),
			);
		}
		if ( $offers ) {
			$business['makesOffer'] = $offers;
		}
		bpsl_print_jsonld( $business );

		$faqs = bpsl_get_posts( 'bpsl_faq' );
		if ( $faqs ) {
			$entities = array();
			foreach ( $faqs as $f ) {
				$entities[] = array(
					'@type'          => 'Question',
					'name'           => get_the_title( $f ),
					'acceptedAnswer' => array(
						'@type' => 'Answer',
						'text'  => wp_strip_all_tags( $f->post_content ),
					),
				);
			}
			bpsl_print_jsonld(
				array(
					'@context'   => 'https://schema.org',
					'@type'      => 'FAQPage',
					'mainEntity' => $entities,
				)
			);
		}
	},
	6
);
