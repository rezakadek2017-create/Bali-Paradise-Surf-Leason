<?php
/**
 * Who we are + what makes the school different.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$values = array(
	array( 'flower', 'Surf with a Friend', 'Our instructors are famous for their warm smiles, patience, and deep knowledge of local tides.' ),
	array( 'heart', 'Support the Community', 'When you book a lesson, your money goes directly to local Balinese families, helping sustain traditional village life.' ),
	array( 'shield', 'Safety First', 'We tailor every lesson so you feel 100% safe, confident, and excited in the water.' ),
	array( 'wave', 'Beginner Friendly', 'Complete first-timers are our specialty. We start on the sand with the basics before heading into gentle white water.' ),
	array( 'users', 'All Ages Welcome', 'Kids, teens, adults and families, together or one by one. We adapt our teaching to your pace, age, and level.' ),
	array( 'pin', 'Community Driven', '100% Balinese owned and operated. By surfing with us, you directly support local families and village communities.' ),
);
?>
<section class="section" id="about" aria-labelledby="about-title">
	<div class="wrap about">
		<div class="about__intro">
			<h2 id="about-title">100% Balinese Owned & Operated</h2>
			<p class="lead">Welcome to Bali Paradise Surf Lesson! Founded by local Balinese surfers, we aren't just instructors—we are your friendly gateway to the island's vibrant culture and world-class waves. We believe the ocean is for everyone. Whether you are a young child catching your very first wave, an adult looking for a new hobby, or a senior surfer checking an item off your bucket list, we adapt our teaching to your pace, age, and gender.</p>
		</div>
		<ul class="values">
			<?php foreach ( $values as $v ) : ?>
				<li>
					<span class="values__icon"><?php echo bpsl_icon( $v[0], 24 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
					<div>
						<h3><?php echo esc_html( $v[1] ); ?></h3>
						<p><?php echo esc_html( $v[2] ); ?></p>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>

