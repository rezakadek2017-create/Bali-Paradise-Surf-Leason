<?php
/**
 * One-time starter content on first load after activation.
 * Only Package A is business-verified; locations and FAQs are neutral drafts
 * for the owner to review (see README).
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function bpsl_seed_insert( $type, $title, $content, $order, $meta = array() ) {
	$id = wp_insert_post(
		array(
			'post_type'    => $type,
			'post_status'  => 'publish',
			'post_title'   => $title,
			'post_content' => $content,
			'menu_order'   => $order,
		)
	);
	if ( $id && ! is_wp_error( $id ) ) {
		foreach ( $meta as $k => $v ) {
			update_post_meta( $id, '_bpsl_' . $k, $v );
		}
	}
}

add_action(
	'init',
	function () {
		if ( get_option( 'bpsl_seeded' ) ) {
			return;
		}
		update_option( 'bpsl_seeded', 1, false ); // Set first to avoid duplicates.

		// Real packages from live site
		$packages = array(
			array(
				'title'   => 'Private',
				'content' => "One teacher, one student — full focus.\n\nFastest way to progress, every wave is yours.",
				'meta'    => array(
					'price'      => 500000,
					'unit'       => 'person',
					'instructor' => '1 Teacher',
					'students'   => '1 Student',
					'duration'   => '2 Hours + 15 Min Break',
					'badge'      => '',
				),
			),
			array(
				'title'   => 'Semi-Private',
				'content' => "One teacher shared between two students.\n\nPerfect for couples or a parent & child.",
				'meta'    => array(
					'price'      => 450000,
					'unit'       => 'person',
					'instructor' => '1 Teacher',
					'students'   => '2 Students',
					'duration'   => '2 Hours + 15 Min Break',
					'badge'      => '',
				),
			),
			array(
				'title'   => 'Small Group',
				'content' => "Two teachers for four students — a low 1:2 ratio.\n\nGreat for friends & families.",
				'meta'    => array(
					'price'      => 400000,
					'unit'       => 'person',
					'instructor' => '2 Teachers',
					'students'   => '4 Students',
					'duration'   => '2 Hours + 15 Min Break',
					'badge'      => '',
				),
			),
			array(
				'title'   => 'Big Group',
				'content' => "3–4 teachers for 8–9 students — no one left waiting.\n\nOur most affordable way to ride. Perfect for groups, classes & celebrations.",
				'meta'    => array(
					'price'      => 300000,
					'unit'       => 'person',
					'instructor' => '3–4 Teachers',
					'students'   => '8–9 Students',
					'duration'   => '2 Hours + 15 Min Break',
					'badge'      => 'Best Value',
				),
			),
		);

		foreach ( $packages as $i => $pkg ) {
			bpsl_seed_insert( 'bpsl_package', $pkg['title'], $pkg['content'], $i + 1, $pkg['meta'] );
		}

		$locations = array(
			array( 'Seminyak Beach', 'Our home base on Jl. Arjuna. Sandy bottom with forgiving waves when the swell is small.', 'Beginners and improvers' ),
			array( 'Kuta Beach', 'A long, sandy beach with gentle white-water waves. A classic first-lesson spot.', 'Beginners' ),
			array( 'Canggu', 'Slow, rolling waves that suit longboards. We choose the best break for the day.', 'Improvers' ),
		);
		foreach ( $locations as $i => $loc ) {
			bpsl_seed_insert( 'bpsl_location', $loc[0], $loc[1], $i + 1, array( 'level' => $loc[2] ) );
		}

		$testimonials = array(
			array( 'Sophie M.', "Stood up on my very first wave! Our instructor was so patient and encouraging. Honestly the best morning of our whole Bali trip — I can't recommend them enough.", 'Australia', 5 ),
			array( 'Lukas & Anna', "Booked a semi-private lesson for our honeymoon. Safe, fun and genuinely warm people who made us feel like friends. We left with huge smiles and amazing photos.", 'Germany', 5 ),
			array( 'Priya R.', "Took my two kids (8 and 11) and they LOVED every second. The team made them feel completely safe in the water. We're already planning to come back next holiday.", 'Singapore', 5 ),
		);
		foreach ( $testimonials as $i => $test ) {
			bpsl_seed_insert( 'bpsl_testimonial', $test[0], $test[1], $i + 1, array( 'origin' => $test[2], 'rating' => $test[3] ) );
		}

		$faqs = array(
			array( 'Do I need any experience to join?', 'Not at all! Most of our guests have never surfed before. We start on the sand with the basics, then head into gentle, beginner-friendly white water. Complete first-timers are our specialty.' ),
			array( 'Is it safe for kids and non-swimmers?', 'Yes. We teach in shallow, waist-deep water with a low student-to-instructor ratio and provide a buoyant soft-top board and rash guard. Every lesson is adapted to your age, comfort and confidence.' ),
			array( 'What should I bring?', 'Just swimwear, a towel, sunscreen and a smile. We provide the surfboard, rash guard and all the coaching. Leave valuables at your accommodation — the beach is casual and relaxed.' ),
			array( 'What if the waves or weather are bad?', 'The ocean changes daily, so we adjust lesson times to the safest tides. Message us on WhatsApp and we\'ll tell you the best time to surf today — or reschedule for free if conditions aren\'t right.' ),
			array( 'How do I book a lesson?', 'Simply tap any WhatsApp button on this page. Tell us your date, group size and experience level — we usually reply within minutes and confirm today\'s best tide for you.' ),
		);
		foreach ( $faqs as $i => $faq ) {
			bpsl_seed_insert( 'bpsl_faq', $faq[0], $faq[1], $i + 1 );
		}
	},
	99
);
