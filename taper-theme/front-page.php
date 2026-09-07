<?php
/**
 * The homepage template — interactive taper-down schedule tool followed by
 * AdSense-optimized guide content and an FAQ accordion.
 *
 * @package TaperTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<section class="tt-hero" id="tt-tool">
	<div class="tt-container">

		<div class="tt-hero-head">
			<span class="tt-eyebrow"><?php esc_html_e( 'Free Planning Tool', 'tapertheme' ); ?></span>
			<h1><?php esc_html_e( 'Personalized Taper-Down Reduction Schedule & Daily Tracker', 'tapertheme' ); ?></h1>
			<p><?php esc_html_e( 'Answer four quick questions and get a day-by-day plan that steps your consumption down gradually to zero — plus a live savings tracker and a printable PDF schedule.', 'tapertheme' ); ?></p>
		</div>

		<div class="tt-card tt-tool-card" id="tt-taper-form-card">
			<form id="tt-taper-form" novalidate>
				<div class="tt-form-grid">

					<div class="tt-field">
						<label for="tt-substance"><?php esc_html_e( '1. What are you reducing?', 'tapertheme' ); ?></label>
						<select id="tt-substance" name="substance" required>
							<option value="cigarettes"><?php esc_html_e( 'Cigarettes', 'tapertheme' ); ?></option>
							<option value="alcohol"><?php esc_html_e( 'Alcohol (standard drinks)', 'tapertheme' ); ?></option>
							<option value="vaping"><?php esc_html_e( 'Vaping (puffs / sessions)', 'tapertheme' ); ?></option>
							<option value="custom"><?php esc_html_e( 'Custom', 'tapertheme' ); ?></option>
						</select>
						<span class="tt-hint tt-visually-hidden" id="tt-custom-unit-hint"><?php esc_html_e( 'Describe what one unit means for your custom substance.', 'tapertheme' ); ?></span>
						<input type="text" id="tt-custom-unit" name="custom_unit" placeholder="<?php esc_attr_e( 'Unit name, e.g. \'pills\' or \'cans\'', 'tapertheme' ); ?>" hidden>
					</div>

					<div class="tt-field">
						<label for="tt-baseline"><?php esc_html_e( '2. Current daily amount', 'tapertheme' ); ?></label>
						<input type="number" id="tt-baseline" name="baseline" min="1" max="500" step="0.5" placeholder="<?php esc_attr_e( 'e.g. 20', 'tapertheme' ); ?>" required>
						<span class="tt-hint"><?php esc_html_e( 'Your typical daily use right now (units per day).', 'tapertheme' ); ?></span>
					</div>

					<div class="tt-field">
						<label for="tt-pace"><?php esc_html_e( '3. Taper pace', 'tapertheme' ); ?></label>
						<select id="tt-pace" name="pace" required>
							<option value="30"><?php esc_html_e( 'Gentle — 30 days', 'tapertheme' ); ?></option>
							<option value="21" selected><?php esc_html_e( 'Standard — 21 days', 'tapertheme' ); ?></option>
							<option value="14"><?php esc_html_e( 'Fast — 14 days', 'tapertheme' ); ?></option>
						</select>
						<span class="tt-hint"><?php esc_html_e( 'A slower pace usually feels more sustainable.', 'tapertheme' ); ?></span>
					</div>

					<div class="tt-field">
						<label for="tt-cost"><?php esc_html_e( '4. Cost per unit', 'tapertheme' ); ?></label>
						<input type="number" id="tt-cost" name="cost" min="0" step="0.01" placeholder="<?php esc_attr_e( 'e.g. 0.75', 'tapertheme' ); ?>">
						<span class="tt-hint"><?php esc_html_e( 'Optional — used to estimate money saved. Any currency.', 'tapertheme' ); ?></span>
					</div>

				</div>

				<div class="tt-form-actions">
					<button type="submit" class="tt-btn tt-btn-primary" id="tt-generate-btn">
						<?php esc_html_e( 'Generate My Reduction Schedule', 'tapertheme' ); ?>
					</button>
				</div>

				<div class="tt-form-error" id="tt-form-error" hidden role="alert"></div>
			</form>
		</div>

		<!-- Dynamic Output Results Dashboard — populated client-side by taper-logic.js -->
		<div id="tt-results" hidden aria-live="polite">

			<div class="tt-summary-grid">
				<div class="tt-stat">
					<span class="tt-stat-value" id="tt-stat-days">—</span>
					<span class="tt-stat-label"><?php esc_html_e( 'Days to Zero', 'tapertheme' ); ?></span>
				</div>
				<div class="tt-stat">
					<span class="tt-stat-value" id="tt-stat-saved">—</span>
					<span class="tt-stat-label"><?php esc_html_e( 'Estimated Total Saved', 'tapertheme' ); ?></span>
				</div>
				<div class="tt-stat">
					<span class="tt-stat-value" id="tt-stat-start-quota">—</span>
					<span class="tt-stat-label"><?php esc_html_e( "Day 1 Quota", 'tapertheme' ); ?></span>
				</div>
				<div class="tt-stat">
					<span class="tt-stat-value" id="tt-stat-avoided">—</span>
					<span class="tt-stat-label"><?php esc_html_e( 'Units Avoided (Total)', 'tapertheme' ); ?></span>
				</div>
			</div>

			<div class="tt-calendar-wrap">
				<h2><?php esc_html_e( 'Your Day-by-Day Schedule', 'tapertheme' ); ?></h2>
				<p class="tt-text-muted"><?php esc_html_e( 'Check off each day as you complete it. Your progress is saved automatically on this device.', 'tapertheme' ); ?></p>
				<div class="tt-calendar-grid" id="tt-calendar-grid"></div>
			</div>

			<div class="tt-results-actions">
				<button type="button" class="tt-btn tt-btn-primary" id="tt-download-pdf">
					<?php esc_html_e( 'Download Printable Schedule (PDF)', 'tapertheme' ); ?>
				</button>
				<button type="button" class="tt-btn tt-btn-secondary" id="tt-download-image">
					<?php esc_html_e( 'Download as Image', 'tapertheme' ); ?>
				</button>
				<button type="button" class="tt-btn tt-btn-secondary" id="tt-recalculate">
					<?php esc_html_e( 'Recalculate', 'tapertheme' ); ?>
				</button>
			</div>
		</div>

	</div>
</section>

<div class="tt-toast" id="tt-toast" role="status"></div>

<section class="tt-content-section">
	<div class="tt-container">
		<div class="tt-prose">

			<h2><?php esc_html_e( 'Understanding Gradual Tapering vs Cold Turkey', 'tapertheme' ); ?></h2>
			<p>
				<?php esc_html_e( 'When people decide to cut back on a habit — whether it\'s cigarettes, alcohol, vaping, or something else entirely — they usually consider two broad strategies: stopping all at once ("cold turkey") or reducing gradually over a set period ("tapering"). Both approaches can work, but they suit very different situations, risk profiles, and personalities.', 'tapertheme' ); ?>
			</p>
			<p>
				<?php esc_html_e( 'Cold turkey relies on a single moment of willpower and can produce fast results, but it also concentrates withdrawal symptoms — irritability, cravings, sleep disruption, and in some cases more serious physical effects — into a short, intense window. For some substances and some people, that intensity makes relapse more likely, because the body and mind are asked to adjust instantly rather than incrementally.', 'tapertheme' ); ?>
			</p>
			<p>
				<?php esc_html_e( 'Tapering, by contrast, spreads the adjustment across days or weeks. Each daily reduction is small enough that it is rarely comfortable, but it is usually manageable. This is the same principle used in many structured behavior-change programs: break a large, intimidating goal into a sequence of small, achievable steps, and let consistency do the work that a single burst of willpower cannot. A well-designed reduction schedule gives you a clear target for today — not an abstract goal for "someday" — which tends to keep motivation and momentum higher over time.', 'tapertheme' ); ?>
			</p>
			<p>
				<?php esc_html_e( 'Neither approach is universally "better." Cold turkey can be appropriate when a substance carries little physical dependence risk or when a person has strong support systems and a clear trigger (such as a health scare) driving fast change. Gradual tapering tends to be favored when physical dependence is present, when previous quit attempts have failed abruptly, or when someone simply prefers a structured, trackable plan. This tool is built around the tapering model because it is easier to sustain for most people, and because it produces a concrete daily plan you can act on immediately.', 'tapertheme' ); ?>
			</p>

			<h2><?php esc_html_e( 'The Science of Habit De-escalation & Craving Waves', 'tapertheme' ); ?></h2>
			<p>
				<?php esc_html_e( 'Cravings are not a straight, ever-rising line — they behave more like waves. Intensity builds, peaks, and then subsides, typically within 15 to 30 minutes, even without acting on the urge. Understanding this pattern is one of the most useful psychological tools in any reduction plan: a craving you "surf" for a few minutes will pass on its own far more often than people expect.', 'tapertheme' ); ?>
			</p>
			<p>
				<?php esc_html_e( 'Habits are also reinforced by repetition and context. Every time a behavior is paired with a specific trigger — a morning coffee, a stressful phone call, a particular time of day — the brain strengthens the association between that trigger and the reward. Gradually reducing frequency, rather than eliminating it instantly, allows those trigger-behavior associations to weaken in a more controlled way, and gives you repeated practice at tolerating a craving without immediately resolving it.', 'tapertheme' ); ?>
			</p>
			<p>
				<?php esc_html_e( 'This is why a reduction schedule is organized around daily quotas and suggested time windows rather than a single "just use less" instruction. Spacing out allowed uses across the day, and slightly widening the gaps as the schedule progresses, gives the nervous system small, repeated exposures to "waiting out" a craving — which is a skill that improves with practice, much like any other. By the time your daily quota reaches zero, you will have already practiced tolerating the craving wave dozens of times.', 'tapertheme' ); ?>
			</p>
			<p>
				<?php esc_html_e( 'It also helps to separate physical urges from situational habits. Some cravings are driven by genuine physiological withdrawal; others are simply learned routines (for example, always using something while driving, or during a work break). Identifying which of your daily "slots" are driven by habit rather than need can help you decide which ones to eliminate first when your quota drops.', 'tapertheme' ); ?>
			</p>

			<h2><?php esc_html_e( 'How to Use This Reduction Schedule Effectively', 'tapertheme' ); ?></h2>
			<ol>
				<li><?php esc_html_e( 'Be honest about your baseline. Track your actual daily use for a day or two if you are unsure — an inflated or deflated starting number will throw off every day that follows.', 'tapertheme' ); ?></li>
				<li><?php esc_html_e( 'Choose a pace that matches your history. If previous fast attempts have failed, choose the 30-day gentle pace rather than the 14-day fast pace — consistency matters more than speed.', 'tapertheme' ); ?></li>
				<li><?php esc_html_e( 'Use the suggested time windows as guardrails, not rules. They are designed to spread your remaining quota across your waking hours so no single stretch of the day is left completely unplanned.', 'tapertheme' ); ?></li>
				<li><?php esc_html_e( 'Check off each day as you complete it. The visual streak is a genuine motivator, and your progress is saved automatically on this device so you can close the tab and come back later.', 'tapertheme' ); ?></li>
				<li><?php esc_html_e( 'Download your schedule as a PDF or image and keep a copy somewhere visible — on your fridge, as your phone wallpaper, or printed next to your workspace.', 'tapertheme' ); ?></li>
				<li><?php esc_html_e( 'If you miss a target day, do not restart the whole plan. Simply continue from where you are; a single off-plan day is far less damaging than abandoning the schedule altogether.', 'tapertheme' ); ?></li>
				<li><?php esc_html_e( 'Re-evaluate weekly. If a pace feels unsustainable, it is completely reasonable to regenerate the schedule with a gentler timeline rather than pushing through in a way that risks relapse.', 'tapertheme' ); ?></li>
			</ol>

			<div class="tt-disclaimer" id="medical-disclaimer">
				<h3>&#9888; <?php esc_html_e( 'Medical Disclaimer', 'tapertheme' ); ?></h3>
				<p>
					<?php esc_html_e( 'This tool provides general educational and self-organizational planning information only. It is not medical advice, and it is not a substitute for evaluation, diagnosis, or treatment by a qualified healthcare professional.', 'tapertheme' ); ?>
				</p>
				<p>
					<strong><?php esc_html_e( 'If you have a heavy or long-term alcohol dependency, or dependency on any other chemical substance, abrupt or unsupervised reduction can be dangerous.', 'tapertheme' ); ?></strong>
					<?php esc_html_e( 'Alcohol and certain sedative withdrawal can cause severe, potentially life-threatening symptoms, including seizures. Do not attempt to self-manage a heavy dependency without first speaking to a doctor or an addiction medicine specialist, who can advise whether a medically supervised taper or detoxification is necessary.', 'tapertheme' ); ?>
				</p>
				<p>
					<?php esc_html_e( 'If you experience severe withdrawal symptoms — confusion, hallucinations, seizures, chest pain, or thoughts of self-harm — seek emergency medical attention immediately.', 'tapertheme' ); ?>
					<a href="<?php echo esc_url( home_url( '/medical-disclaimer/' ) ); ?>"><?php esc_html_e( 'Read our full Medical Disclaimer.', 'tapertheme' ); ?></a>
				</p>
			</div>

			<h2 id="faq"><?php esc_html_e( 'Frequently Asked Questions', 'tapertheme' ); ?></h2>
			<div class="tt-faq">

				<details class="tt-faq-item">
					<summary><?php esc_html_e( 'Is this reduction schedule medically supervised?', 'tapertheme' ); ?></summary>
					<div class="tt-faq-answer">
						<p><?php esc_html_e( 'No. This is a self-directed planning and tracking tool, not a medical program. For heavy or long-term dependencies — especially alcohol or other chemical substances — please consult a healthcare professional before starting any reduction plan.', 'tapertheme' ); ?></p>
					</div>
				</details>

				<details class="tt-faq-item">
					<summary><?php esc_html_e( 'How is my daily quota calculated?', 'tapertheme' ); ?></summary>
					<div class="tt-faq-answer">
						<p><?php esc_html_e( 'The tool takes your current baseline daily amount and your chosen pace (14, 21, or 30 days), then reduces your allowance by a proportional amount each day until it reaches zero on the final day. The reduction is gradual and progressive rather than a fixed flat amount, so early days feel more achievable.', 'tapertheme' ); ?></p>
					</div>
				</details>

				<details class="tt-faq-item">
					<summary><?php esc_html_e( 'Is my data stored anywhere or sent to a server?', 'tapertheme' ); ?></summary>
					<div class="tt-faq-answer">
						<p><?php esc_html_e( 'No. All calculations happen entirely in your browser using client-side JavaScript. Your schedule and check-in progress are saved only in your browser\'s local storage on this device — nothing is transmitted to or stored on our servers.', 'tapertheme' ); ?></p>
					</div>
				</details>

				<details class="tt-faq-item">
					<summary><?php esc_html_e( 'What happens if I miss a day or go over my quota?', 'tapertheme' ); ?></summary>
					<div class="tt-faq-answer">
						<p><?php esc_html_e( 'Nothing happens automatically — the schedule does not penalize you. Simply pick back up the next day. Consistency over the full schedule matters far more than perfection on any single day.', 'tapertheme' ); ?></p>
					</div>
				</details>

				<details class="tt-faq-item">
					<summary><?php esc_html_e( 'Can I use this for a substance not listed in the dropdown?', 'tapertheme' ); ?></summary>
					<div class="tt-faq-answer">
						<p><?php esc_html_e( 'Yes — select "Custom" and enter your own unit name (for example, "cans" or "pills"). The underlying math works the same way regardless of what you are tracking.', 'tapertheme' ); ?></p>
					</div>
				</details>

				<details class="tt-faq-item">
					<summary><?php esc_html_e( 'How accurate is the money-saved estimate?', 'tapertheme' ); ?></summary>
					<div class="tt-faq-answer">
						<p><?php esc_html_e( 'It is a simple estimate: (baseline minus your daily quota) multiplied by your entered cost per unit, summed across the whole schedule. It does not account for price changes, bulk discounts, or taxes, so treat it as a helpful approximation rather than an exact figure.', 'tapertheme' ); ?></p>
					</div>
				</details>

				<details class="tt-faq-item">
					<summary><?php esc_html_e( 'Can I download or print my schedule?', 'tapertheme' ); ?></summary>
					<div class="tt-faq-answer">
						<p><?php esc_html_e( 'Yes. Once your schedule is generated, use the "Download Printable Schedule (PDF)" button to save a PDF, or "Download as Image" to save a PNG you can share or print separately.', 'tapertheme' ); ?></p>
					</div>
				</details>

			</div>

		</div>
	</div>
</section>

<?php
get_footer();
