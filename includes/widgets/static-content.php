<?php
/**
 * Display static content from an specific page.
 *
 * @since Ink 1.0
 *
 * @package Stag_Customizer
 * @subpackage Ink
 */
class Stag_Widget_Static_Content extends Stag_Widget {
	/**
	 * Constructor
	 */
	public function __construct() {
		$this->widget_id          = 'stag_widget_static_content';
		$this->widget_cssclass    = 'stag_widget_static_content';
		$this->widget_description = __( 'Displays content from a specific page.', 'om-assistant' );
		$this->widget_name        = __( 'Section: Static Content', 'om-assistant' );
		$this->settings           = array(
			'title' => array(
				'type'  => 'text',
				'std'   => '',
				'label' => __( 'Title:', 'om-assistant' ),
			),
			'page' => array(
				'type'  => 'page',
				'std'   => '',
				'label' => __( 'Select Page:', 'om-assistant' ),
			)
		);

		parent::__construct();
	}

	/**
	 * Widget function.
	 *
	 * @see WP_Widget
	 * @access public
	 * @param array $args
	 * @param array $instance
	 * @return void
	 */
	function widget( $args, $instance ) {
		if ( $this->get_cached_widget( $args ) )
			return;

		ob_start();

		extract( $args );

		$title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );
		$page  = absint( $instance[ 'page' ] );
		$post  = get_post( $page );

		echo $before_widget;

		// Allow site-wide customization of the 'Read more' link text
		$read_more = apply_filters( 'stag_read_more_text', __( 'Read more', 'om-assistant' ) );

		?>

		<?php if ( is_object( $post ) ) : ?>
		<section class="inner-section">
			<article id="post-<?php $post->ID; ?>" <?php post_class(); ?>>
				<?php if ( $title ) echo $before_title . $title . $after_title; ?>

				<div class="entry-content">
					<?php echo apply_filters( 'the_content', $post->post_content ); ?>
				</div>
			</article>
		</section>
		<?php endif; ?>

		<?php
		echo $after_widget;

		wp_reset_postdata();

		$content = ob_get_clean();

		echo $content;

		$this->cache_widget( $args, $content );
	}

	/**
	 * Registers the widget with the WordPress Widget API.
	 *
	 * @return void.
	 */
	public static function register() {
		register_widget( __CLASS__ );
	}
}

add_action( 'widgets_init', array( 'Stag_Widget_Static_Content', 'register' ) );
