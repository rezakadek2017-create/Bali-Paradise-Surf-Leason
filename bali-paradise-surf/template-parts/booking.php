<?php
/**
 * Booking form. Nothing is stored on the server: the form builds a WhatsApp
 * message in the browser (assets/js/main.js) and opens wa.me.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$packages = bpsl_get_posts( 'bpsl_package' );
?>
<section class="section section--sea booking" id="book" aria-labelledby="book-title">
	<div class="wrap booking__grid">
		<div class="booking__form">
			<h2 id="book-title">Book your lesson on WhatsApp</h2>
			<p class="booking__intro">Tell us a little about your group. We open WhatsApp with your message ready to send, then confirm everything with you there.</p>

			<?php if ( ! bpsl_wa_number() && current_user_can( 'manage_options' ) ) : ?>
				<p class="admin-note">Admin notice: no WhatsApp number is set. <a href="<?php echo esc_url( admin_url( 'customize.php?autofocus[section]=bpsl_settings' ) ); ?>">Add it in the Customizer</a>.</p>
			<?php endif; ?>

			<form id="booking-form" class="form" data-wa="<?php echo esc_attr( bpsl_wa_number() ); ?>" novalidate>
				<div class="field">
					<label for="bk-name">Your name</label>
					<input id="bk-name" name="guest_name" type="text" autocomplete="name" required>
					<p class="field__error" hidden>Please enter your name.</p>
				</div>

				<div class="field">
					<label for="bk-package">Lesson</label>
					<select id="bk-package" name="package" required>
						<?php if ( $packages ) : ?>
							<?php foreach ( $packages as $p ) : ?>
								<?php
								$price = (int) bpsl_meta( $p->ID, 'price' );
								$unit  = bpsl_meta( $p->ID, 'unit' );
								$unit  = $unit ? $unit : 'person';
								?>
								<option value="<?php echo (int) $p->ID; ?>"
									data-name="<?php echo esc_attr( get_the_title( $p ) ); ?>"
									data-price="<?php echo (int) $price; ?>"
									data-unit="<?php echo esc_attr( $unit ); ?>">
									<?php echo esc_html( get_the_title( $p ) ); ?><?php echo $price ? ' - ' . esc_html( bpsl_money( $price ) ) . ' / ' . esc_html( $unit ) : ''; ?>
								</option>
							<?php endforeach; ?>
						<?php else : ?>
							<option value="0" data-name="Surf lesson" data-price="0" data-unit="person">Surf lesson</option>
						<?php endif; ?>
					</select>
				</div>

				<div class="field-row">
					<div class="field">
						<label for="bk-date">Preferred date</label>
						<input id="bk-date" name="date" type="date" required>
						<p class="field__error" hidden>Please choose a date.</p>
					</div>
					<div class="field">
						<label for="bk-time">Preferred time</label>
						<select id="bk-time" name="time">
							<option>Morning</option>
							<option>Afternoon</option>
							<option>Flexible</option>
						</select>
					</div>
				</div>

				<div class="field-row">
					<div class="field">
						<label for="bk-people">Number of surfers</label>
						<div class="stepper">
							<button type="button" class="stepper__btn" data-step="-1" aria-label="Fewer surfers">&minus;</button>
							<input id="bk-people" name="people" type="number" min="1" max="20" value="1" inputmode="numeric" required>
							<button type="button" class="stepper__btn" data-step="1" aria-label="More surfers">+</button>
						</div>
						<p class="field__error" hidden>Enter a number from 1 to 20.</p>
					</div>
					<div class="field">
						<label for="bk-level">Surf experience</label>
						<select id="bk-level" name="level">
							<option>Never surfed</option>
							<option>Tried once or twice</option>
							<option>Surf regularly</option>
						</select>
					</div>
				</div>

				<div class="field">
					<label for="bk-ages">Ages of surfers <span class="optional">(optional)</span></label>
					<input id="bk-ages" name="ages" type="text" placeholder="e.g. 34, 32, 9">
				</div>

				<div class="field">
					<label for="bk-stay">Where are you staying? <span class="optional">(optional)</span></label>
					<input id="bk-stay" name="stay" type="text" placeholder="Hotel or area">
				</div>

				<div class="field">
					<label for="bk-notes">Anything we should know? <span class="optional">(optional)</span></label>
					<textarea id="bk-notes" name="notes" rows="3" placeholder="Medical conditions, fears, goals"></textarea>
				</div>

				<button class="btn btn--sun btn--block" type="submit"><?php echo bpsl_icon( 'whatsapp', 22 ); // phpcs:ignore WordPress.Security.EscapeOutput ?> Send on WhatsApp</button>
				<noscript><p class="form__noscript">JavaScript is off. <a href="<?php echo esc_url( bpsl_wa_link( bpsl_default_wa_text() ) ); ?>">Message us on WhatsApp directly</a>.</p></noscript>
			</form>
		</div>

		<aside class="booking__preview" aria-labelledby="preview-title">
			<h3 id="preview-title">Your message</h3>
			<div class="chat" aria-live="polite">
				<div class="chat__bar"><span class="chat__avatar"><?php echo bpsl_icon( 'wave', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span> Bali Paradise Surf Lesson</div>
				<div class="chat__body">
					<p class="chat__bubble" id="wa-preview"><?php echo esc_html( bpsl_default_wa_text() ); ?></p>
				</div>
			</div>
			<button type="button" class="btn btn--ghost btn--sm booking__copy" id="wa-copy">Copy message</button>
			<p class="booking__hint">This is what you will send. You can edit it in WhatsApp before sending.</p>
		</aside>
	</div>
</section>
