<?php
/** Shared weekly schedule; the parent template supplies the station key. */
defined( 'ABSPATH' ) || exit;

$platinum_schedules = require __DIR__ . '/schedules.php';
$platinum_schedule_days = $platinum_schedules[ $platinum_schedule_station ];
$platinum_schedule_labels = array(
	'Monday'    => __( 'Lunes', 'platinum-home' ),
	'Tuesday'   => __( 'Martes', 'platinum-home' ),
	'Wednesday' => __( 'Miércoles', 'platinum-home' ),
	'Thursday'  => __( 'Jueves', 'platinum-home' ),
	'Friday'    => __( 'Viernes', 'platinum-home' ),
	'Saturday'  => __( 'Sábado', 'platinum-home' ),
	'Sunday'    => __( 'Domingo', 'platinum-home' ),
);
$platinum_schedule_now = new DateTimeImmutable( 'now', new DateTimeZone( 'America/Guayaquil' ) );
$platinum_schedule_today = $platinum_schedule_now->format( 'l' );
$platinum_schedule_clock = $platinum_schedule_now->format( 'H:i' );
$platinum_schedule_frequencies = 'quito' === $platinum_schedule_station
	? array( array( __( 'Quito y sus alrededores', 'platinum-home' ), '760 AM' ) )
	: array( array( __( 'Quito y Tulcán', 'platinum-home' ), '90.9 FM' ), array( __( 'Guayaquil', 'platinum-home' ), '94.5 FM' ), array( __( 'Ambato', 'platinum-home' ), '101.7 FM' ), array( __( 'Cuenca', 'platinum-home' ), '107.7 FM' ) );
?>
<section class="platinum-schedule platinum-schedule--<?php echo esc_attr( $platinum_schedule_station ); ?>" id="horarios" aria-labelledby="horarios-heading">
	<h2 id="horarios-heading"><?php esc_html_e( 'Horarios', 'platinum-home' ); ?></h2>
	<p class="platinum-schedule-caption"><?php esc_html_e( 'Programación semanal · Hora de Ecuador', 'platinum-home' ); ?></p>
	<div class="platinum-schedule-widget" data-schedule-widget>
		<div class="platinum-schedule-tabs" role="tablist" aria-label="<?php esc_attr_e( 'Días de programación', 'platinum-home' ); ?>">
			<?php foreach ( $platinum_schedule_days as $platinum_day => $platinum_shows ) : ?>
				<?php $platinum_day_id = $platinum_schedule_station . '-' . strtolower( $platinum_day ); ?>
				<button type="button" role="tab" id="schedule-tab-<?php echo esc_attr( $platinum_day_id ); ?>" aria-controls="schedule-panel-<?php echo esc_attr( $platinum_day_id ); ?>" aria-selected="<?php echo $platinum_day === $platinum_schedule_today ? 'true' : 'false'; ?>" tabindex="<?php echo $platinum_day === $platinum_schedule_today ? '0' : '-1'; ?>" data-schedule-tab="<?php echo esc_attr( $platinum_day ); ?>"><?php echo esc_html( $platinum_schedule_labels[ $platinum_day ] ); ?></button>
			<?php endforeach; ?>
		</div>
		<?php foreach ( $platinum_schedule_days as $platinum_day => $platinum_shows ) : ?>
			<?php $platinum_day_id = $platinum_schedule_station . '-' . strtolower( $platinum_day ); ?>
			<div class="platinum-schedule-panel" role="tabpanel" tabindex="0" id="schedule-panel-<?php echo esc_attr( $platinum_day_id ); ?>" aria-labelledby="schedule-tab-<?php echo esc_attr( $platinum_day_id ); ?>" data-schedule-panel="<?php echo esc_attr( $platinum_day ); ?>"<?php echo $platinum_day === $platinum_schedule_today ? '' : ' hidden'; ?>>
				<ul class="platinum-schedule-list">
					<?php foreach ( $platinum_shows as $platinum_show ) : ?>
						<?php
						list( $platinum_start, $platinum_end ) = explode( ' - ', $platinum_show['time'] );
						$platinum_on_air = $platinum_day === $platinum_schedule_today && $platinum_schedule_clock >= $platinum_start && $platinum_schedule_clock < ( '00:00' === $platinum_end ? '24:00' : $platinum_end );
						$platinum_has_description = '' !== $platinum_show['description'];
						?>
						<li class="platinum-schedule-item<?php echo $platinum_on_air ? ' is-on-air' : ''; ?>" data-schedule-start="<?php echo esc_attr( $platinum_start ); ?>" data-schedule-end="<?php echo esc_attr( $platinum_end ); ?>"<?php if ( $platinum_on_air ) : ?> aria-current="time"<?php endif; ?>>
							<?php if ( $platinum_has_description ) : ?><details><?php endif; ?>
							<<?php echo $platinum_has_description ? 'summary' : 'div'; ?> class="platinum-schedule-row">
								<span class="platinum-schedule-time"><?php echo esc_html( $platinum_show['time'] ); ?></span>
								<span class="platinum-schedule-title"><?php echo esc_html( $platinum_show['title'] ); ?><span class="platinum-on-air"<?php echo $platinum_on_air ? '' : ' hidden'; ?>><?php esc_html_e( 'Al aire', 'platinum-home' ); ?></span></span>
							</<?php echo $platinum_has_description ? 'summary' : 'div'; ?>>
							<?php if ( $platinum_has_description ) : ?>
								<p class="platinum-schedule-description"><?php echo esc_html( $platinum_show['description'] ); ?></p>
							</details>
							<?php endif; ?>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php endforeach; ?>
	</div>
	<ul class="platinum-frequencies" aria-label="<?php esc_attr_e( 'Frecuencias', 'platinum-home' ); ?>">
		<?php foreach ( $platinum_schedule_frequencies as $platinum_frequency ) : ?>
			<li><span><?php echo esc_html( $platinum_frequency[0] ); ?></span><strong><?php echo esc_html( $platinum_frequency[1] ); ?></strong></li>
		<?php endforeach; ?>
	</ul>
</section>
