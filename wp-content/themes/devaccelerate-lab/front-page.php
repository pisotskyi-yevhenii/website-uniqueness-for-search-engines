<?php
/**
 * DevAccelerate engineering panel.
 *
 * @package DevAccelerateLab
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$panel_title = DevAccelerate_Theme::field( 'devaccelerate_panel_title', 'Integrate AI into Real Development Workflows' );
$panel_description = DevAccelerate_Theme::field(
	'devaccelerate_panel_description',
	'Learn how to select coding agents, define review boundaries, and introduce AI without weakening engineering ownership.'
);
$resource = DevAccelerate_Theme::field(
	'devaccelerate_primary_resource',
	array(
		'title'  => 'Inspect the workflow',
		'url'    => '#',
		'target' => '',
	)
);
$metric_one = DevAccelerate_Theme::field(
	'devaccelerate_metric_one',
	array(
		'devaccelerate_metric_one_value' => '01',
		'devaccelerate_metric_one_label' => 'Tool selection framework',
	)
);
$metric_two = DevAccelerate_Theme::field(
	'devaccelerate_metric_two',
	array(
		'devaccelerate_metric_two_value' => '02',
		'devaccelerate_metric_two_label' => 'Review and control loop',
	)
);
$show_matrix = (bool) DevAccelerate_Theme::field( 'devaccelerate_show_matrix', true );
$accent = sanitize_hex_color( DevAccelerate_Theme::field( 'devaccelerate_accent', '#b7ff3c' ) );
$accent = $accent ? $accent : '#b7ff3c';

get_header();
?>
<main id="devaccelerate-main" class="devaccelerate-main" style="--devaccelerate-accent: <?php echo esc_attr( $accent ); ?>;">
	<section class="devaccelerate-panel">
		<div class="devaccelerate-panel__copy">
			<p class="devaccelerate-kicker">ENGINEERING ENABLEMENT / COURSE 02</p>
			<h1><?php echo esc_html( $panel_title ); ?></h1>
			<p class="devaccelerate-panel__description"><?php echo esc_html( $panel_description ); ?></p>
			<a class="devaccelerate-command" href="#" aria-disabled="true" data-console-placeholder="true">
				<span aria-hidden="true">&gt;</span>
				<?php echo esc_html( is_array( $resource ) && ! empty( $resource['title'] ) ? $resource['title'] : 'Inspect the workflow' ); ?>
			</a>
		</div>
		<aside class="devaccelerate-runtime-card" aria-label="<?php esc_attr_e( 'Course runtime summary', 'devaccelerate-lab' ); ?>">
			<div class="devaccelerate-runtime-card__top">
				<span>workflow.config</span>
				<span class="devaccelerate-runtime-card__status">ACTIVE</span>
			</div>
			<dl>
				<div>
					<dt>agent.role</dt>
					<dd>implementation_assistant</dd>
				</div>
				<div>
					<dt>human.role</dt>
					<dd>decision_owner</dd>
				</div>
				<div>
					<dt>review.mode</dt>
					<dd>required</dd>
				</div>
				<div>
					<dt>output.state</dt>
					<dd>verified</dd>
				</div>
			</dl>
		</aside>
	</section>

	<section class="devaccelerate-metrics" aria-label="<?php esc_attr_e( 'Course modules', 'devaccelerate-lab' ); ?>">
		<article>
			<strong><?php echo esc_html( $metric_one['devaccelerate_metric_one_value'] ?? '01' ); ?></strong>
			<p><?php echo esc_html( $metric_one['devaccelerate_metric_one_label'] ?? 'Tool selection framework' ); ?></p>
		</article>
		<article>
			<strong><?php echo esc_html( $metric_two['devaccelerate_metric_two_value'] ?? '02' ); ?></strong>
			<p><?php echo esc_html( $metric_two['devaccelerate_metric_two_label'] ?? 'Review and control loop' ); ?></p>
		</article>
	</section>

	<?php if ( $show_matrix ) : ?>
		<section class="devaccelerate-matrix">
			<div class="devaccelerate-matrix__heading">
				<p>IMPLEMENTATION MATRIX</p>
				<h2><?php esc_html_e( 'A controlled path from tool trial to team workflow', 'devaccelerate-lab' ); ?></h2>
			</div>
			<ol class="devaccelerate-matrix__list">
				<li>
					<span>SELECT</span>
					<p><?php esc_html_e( 'Match the tool to repository access, task risk, and review requirements.', 'devaccelerate-lab' ); ?></p>
				</li>
				<li>
					<span>CONSTRAIN</span>
					<p><?php esc_html_e( 'Define context, acceptance criteria, and the decisions that remain human-owned.', 'devaccelerate-lab' ); ?></p>
				</li>
				<li>
					<span>VERIFY</span>
					<p><?php esc_html_e( 'Use tests, diffs, and manual review before generated work reaches the codebase.', 'devaccelerate-lab' ); ?></p>
				</li>
			</ol>
		</section>
	<?php endif; ?>
</main>
<?php
get_footer();
