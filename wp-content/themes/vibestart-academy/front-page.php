<?php
/**
 * VibeStart home page.
 *
 * @package VibeStartAcademy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$hero = vibestart_academy_group(
	'vibestart_hero',
	array(
		'vibestart_hero_eyebrow'     => 'AI BUILDING FOR ABSOLUTE BEGINNERS',
		'vibestart_hero_title'       => 'Build with AI Before You Learn to Code',
		'vibestart_hero_description' => 'A practical starting point for turning plain-language ideas into useful digital projects with modern AI tools.',
		'vibestart_hero_cta_label'   => 'Explore the beginner path',
	)
);

$path = vibestart_academy_group(
	'vibestart_learning_path',
	array(
		'vibestart_path_title'       => 'A Simple Path from Idea to Prototype',
		'vibestart_path_description' => 'Learn the repeatable habits that help non-programmers communicate with AI, improve outputs, and finish small working products.',
	)
);

$cards = array(
	vibestart_academy_group(
		'vibestart_card_one',
		array(
			'vibestart_card_one_title'       => 'Describe Your Idea',
			'vibestart_card_one_description' => 'Turn a rough concept into a clear request that an AI coding tool can understand.',
		)
	),
	vibestart_academy_group(
		'vibestart_card_two',
		array(
			'vibestart_card_two_title'       => 'Guide the AI',
			'vibestart_card_two_description' => 'Review each result, provide focused feedback, and keep the project aligned with your goal.',
		)
	),
	vibestart_academy_group(
		'vibestart_card_three',
		array(
			'vibestart_card_three_title'       => 'Launch a Working Prototype',
			'vibestart_card_three_description' => 'Combine small verified steps into a simple project you can open, test, and share.',
		)
	),
);

get_header();
?>
<main id="vibestart-main" class="vibestart-main">
	<section class="vibestart-hero">
		<div class="vibestart-hero__content">
			<p class="vibestart-eyebrow"><?php echo esc_html( $hero['vibestart_hero_eyebrow'] ); ?></p>
			<h1><?php echo esc_html( $hero['vibestart_hero_title'] ); ?></h1>
			<p class="vibestart-hero__description"><?php echo esc_html( $hero['vibestart_hero_description'] ); ?></p>
			<a class="vibestart-button" href="#" aria-disabled="true" data-placeholder-link="true">
				<?php echo esc_html( $hero['vibestart_hero_cta_label'] ); ?>
			</a>
		</div>
		<div class="vibestart-hero__visual" aria-hidden="true">
			<div class="vibestart-prompt-card">
				<span class="vibestart-prompt-card__label">YOUR IDEA</span>
				<p>Create a simple course page for people who have never written code.</p>
				<div class="vibestart-prompt-card__status">Ready to build</div>
			</div>
		</div>
	</section>

	<section class="vibestart-learning-path">
		<div class="vibestart-section-heading">
			<p class="vibestart-eyebrow"><?php esc_html_e( 'YOUR FIRST THREE STEPS', 'vibestart-academy' ); ?></p>
			<h2><?php echo esc_html( $path['vibestart_path_title'] ); ?></h2>
			<p><?php echo esc_html( $path['vibestart_path_description'] ); ?></p>
		</div>
		<div class="vibestart-card-grid">
			<?php foreach ( $cards as $index => $card ) : ?>
				<?php
				$number = $index + 1;
				$title_key = 'vibestart_card_' . array( 'one', 'two', 'three' )[ $index ] . '_title';
				$description_key = 'vibestart_card_' . array( 'one', 'two', 'three' )[ $index ] . '_description';
				?>
				<article class="vibestart-step-card">
					<span class="vibestart-step-card__number"><?php echo esc_html( str_pad( (string) $number, 2, '0', STR_PAD_LEFT ) ); ?></span>
					<h3><?php echo esc_html( $card[ $title_key ] ); ?></h3>
					<p><?php echo esc_html( $card[ $description_key ] ); ?></p>
				</article>
			<?php endforeach; ?>
		</div>
	</section>
</main>
<?php
get_footer();
