<?php
/**
 * DevAccelerate engineering panel.
 *
 * @package DevAccelerateLab
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$panel_kicker = DevAccelerate_Theme::field( 'devaccelerate_panel_kicker', 'ENGINEERING ENABLEMENT / COURSE 02' );
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
$panel_cursor = DevAccelerate_Theme::field( 'devaccelerate_panel_cursor', '_' );
$command_symbol = DevAccelerate_Theme::field( 'devaccelerate_command_symbol', '>' );
$runtime = DevAccelerate_Theme::field(
	'devaccelerate_runtime',
	array(
		'devaccelerate_runtime_filename'    => 'workflow.config',
		'devaccelerate_runtime_status'      => 'ACTIVE',
		'devaccelerate_runtime_key_one'     => 'agent.role',
		'devaccelerate_runtime_value_one'   => 'implementation_assistant',
		'devaccelerate_runtime_key_two'     => 'human.role',
		'devaccelerate_runtime_value_two'   => 'decision_owner',
		'devaccelerate_runtime_key_three'   => 'review.mode',
		'devaccelerate_runtime_value_three' => 'required',
		'devaccelerate_runtime_key_four'    => 'output.state',
		'devaccelerate_runtime_value_four'  => 'verified',
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
$show_matrix = function_exists( 'get_field' )
	? (bool) get_field( 'devaccelerate_show_matrix' )
	: true;
$accent = sanitize_hex_color( DevAccelerate_Theme::field( 'devaccelerate_accent', '#b7ff3c' ) );
$accent = $accent ? $accent : '#b7ff3c';
$matrix = DevAccelerate_Theme::field(
	'devaccelerate_matrix',
	array(
		'devaccelerate_matrix_eyebrow'          => 'IMPLEMENTATION MATRIX',
		'devaccelerate_matrix_title'            => 'A controlled path from tool trial to team workflow',
		'devaccelerate_matrix_item_one_label'   => 'SELECT',
		'devaccelerate_matrix_item_one_text'    => 'Match the tool to repository access, task risk, and review requirements.',
		'devaccelerate_matrix_item_two_label'   => 'CONSTRAIN',
		'devaccelerate_matrix_item_two_text'    => 'Define context, acceptance criteria, and the decisions that remain human-owned.',
		'devaccelerate_matrix_item_three_label' => 'VERIFY',
		'devaccelerate_matrix_item_three_text'  => 'Use tests, diffs, and manual review before generated work reaches the codebase.',
	)
);

get_header();
?>
<main id="devaccelerate-main" class="devaccelerate-main" style="--devaccelerate-accent: <?php echo esc_attr( $accent ); ?>;">
	<section class="devaccelerate-panel">
		<div class="devaccelerate-panel__copy">
			<p class="devaccelerate-kicker"><?php echo esc_html( $panel_kicker ); ?></p>
			<h1><?php echo esc_html( $panel_title ); ?><span class="devaccelerate-cursor"><?php echo esc_html( $panel_cursor ); ?></span></h1>
			<p class="devaccelerate-panel__description"><?php echo esc_html( $panel_description ); ?></p>
			<a class="devaccelerate-command" href="#" aria-disabled="true" data-console-placeholder="true">
				<span aria-hidden="true"><?php echo esc_html( $command_symbol ); ?></span>
				<?php echo esc_html( is_array( $resource ) && ! empty( $resource['title'] ) ? $resource['title'] : 'Inspect the workflow' ); ?>
			</a>
		</div>
		<aside class="devaccelerate-runtime-card" aria-label="<?php esc_attr_e( 'Course runtime summary', 'devaccelerate-lab' ); ?>">
			<div class="devaccelerate-runtime-card__top">
				<span><?php echo esc_html( $runtime['devaccelerate_runtime_filename'] ); ?></span>
				<span class="devaccelerate-runtime-card__status"><?php echo esc_html( $runtime['devaccelerate_runtime_status'] ); ?></span>
			</div>
			<dl>
				<div>
					<dt><?php echo esc_html( $runtime['devaccelerate_runtime_key_one'] ); ?></dt>
					<dd><?php echo esc_html( $runtime['devaccelerate_runtime_value_one'] ); ?></dd>
				</div>
				<div>
					<dt><?php echo esc_html( $runtime['devaccelerate_runtime_key_two'] ); ?></dt>
					<dd><?php echo esc_html( $runtime['devaccelerate_runtime_value_two'] ); ?></dd>
				</div>
				<div>
					<dt><?php echo esc_html( $runtime['devaccelerate_runtime_key_three'] ); ?></dt>
					<dd><?php echo esc_html( $runtime['devaccelerate_runtime_value_three'] ); ?></dd>
				</div>
				<div>
					<dt><?php echo esc_html( $runtime['devaccelerate_runtime_key_four'] ); ?></dt>
					<dd><?php echo esc_html( $runtime['devaccelerate_runtime_value_four'] ); ?></dd>
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
				<p><?php echo esc_html( $matrix['devaccelerate_matrix_eyebrow'] ); ?></p>
				<h2><?php echo esc_html( $matrix['devaccelerate_matrix_title'] ); ?></h2>
			</div>
			<ol class="devaccelerate-matrix__list">
				<li>
					<span><?php echo esc_html( $matrix['devaccelerate_matrix_item_one_label'] ); ?></span>
					<p><?php echo esc_html( $matrix['devaccelerate_matrix_item_one_text'] ); ?></p>
				</li>
				<li>
					<span><?php echo esc_html( $matrix['devaccelerate_matrix_item_two_label'] ); ?></span>
					<p><?php echo esc_html( $matrix['devaccelerate_matrix_item_two_text'] ); ?></p>
				</li>
				<li>
					<span><?php echo esc_html( $matrix['devaccelerate_matrix_item_three_label'] ); ?></span>
					<p><?php echo esc_html( $matrix['devaccelerate_matrix_item_three_text'] ); ?></p>
				</li>
			</ol>
		</section>
	<?php endif; ?>
</main>
<?php
get_footer();
