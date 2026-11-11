<?php
/** Radio Platinum audio catalog. File-system integration will replace these demonstration records. */
defined( 'ABSPATH' ) || exit;

$is_quito = platinum_is_audio_catalog_quito_page();
$station  = $is_quito ? 'Radio Quito' : 'Radio Platinum';
$audios   = $is_quito ? array(
	'2026 10 14 ENTREVISTA ACTUALIDAD QUITO - LAS NOTICIAS MÁS IMPORTANTES DE LA JORNADA',
	'2026 10 14 ENTREVISTA MOVILIDAD Y SERVICIOS PARA LA CIUDAD DE QUITO',
	'2026 10 14 ENTREVISTA CULTURA Y COMUNIDAD EN LA CAPITAL',
	'2026 10 14 NOTICIERO ECUADORADIO QUITO - PRIMERA EMISIÓN',
	'2026 10 13 ENTREVISTA ECONOMÍA Y EMPLEO EN QUITO',
	'2026 10 13 ENTREVISTA DEPORTES Y ACTUALIDAD NACIONAL',
	'2026 10 13 ANÁLISIS DE COYUNTURA CON INVITADOS ESPECIALES',
	'2026 10 13 ENTREVISTA SALUD Y BIENESTAR PARA LA COMUNIDAD',
	'2026 10 12 RESUMEN INFORMATIVO DE LA SEMANA',
) : array(
	'2026 10 14 ENTREV RONNY PARRA DOCENTE UNIVERSITARIO Y EXPERTO PETROLERO - DERRAME DE CRUDO EN QUININDÉ ESMERALDAS',
	'2026 10 14 ENTREV GENERAL SP PATRICIO CARRILLO EXMINISTRO DEL INTERIOR - RESULTADOS ÚLTIMOS OPERATIVOS EN EL PAÍS',
	'2026 10 14 ENTREV MARCO OLMEDO ASAMBLEÍSTA DE ADN - INFORME SOBRE AUMENTO PATRIMONIAL A AUTORIDADES',
	'2026 10 14 ENTREV HENRY YANDÚN PRESIDENTE CONSTRUCTORES POSITIVOS - 11 PROPUESTAS PARA LOS GAD DEL ECUADOR',
	'2026 10 13 ENTREV DRA ALEXANDRA ALVARADO DIR INSTITUTO GEOFÍSICO DE LA EPN - SISMOS DEL 2026 EN AMÉRICA LATINA',
	'2026 10 13 ENTREV CRISTIAN PALIZ DOCENTE Y EXPERTO METEORÓLOGO - CLIMA Y FENÓMENO DEL NIÑO EN ECUADOR',
	'2026 10 13 ENTREV ANDRÉS ALBUJA ANALISTA ECONÓMICO - CRECIMIENTO Y EMPLEO',
	'2026 10 13 ENTREV PAULINA PAZMIÑO COLEGIO DE INGENIEROS - RETOS DE INFRAESTRUCTURA',
	'2026 10 12 ENTREV RODRIGO GÓMEZ DE LA TORRE COMITÉ EMPRESARIAL - ACTUALIDAD NACIONAL',
);
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class( 'platinum-home platinum-audio-catalog-page' ); ?>>
<?php wp_body_open(); ?>
<a class="platinum-skip" href="#catalogo-audios"><?php esc_html_e( 'Saltar al catálogo de audios', 'platinum-home' ); ?></a>
<?php require __DIR__ . '/header.php'; ?>
<main class="platinum-container platinum-audio-catalog-content" id="catalogo-audios">
	<p class="platinum-eyebrow"><?php echo esc_html( $station ); ?></p>
	<h1><?php esc_html_e( 'Audios', 'platinum-home' ); ?></h1>
	<details class="platinum-audio-collection" open>
		<summary><span><?php echo esc_html( sprintf( __( 'Noticieros %s', 'platinum-home' ), $station ) ); ?></span><i aria-hidden="true"></i></summary>
		<div class="platinum-audio-grid">
			<?php foreach ( $audios as $audio_title ) : ?>
				<article class="platinum-audio-card">
					<audio controls preload="none" aria-label="<?php echo esc_attr( $audio_title ); ?>" data-demo-audio></audio>
					<h2><?php echo esc_html( $audio_title ); ?></h2>
				</article>
			<?php endforeach; ?>
		</div>
	</details>
</main>
<?php require __DIR__ . '/footer.php'; ?>
<?php wp_footer(); ?>
</body>
</html>
