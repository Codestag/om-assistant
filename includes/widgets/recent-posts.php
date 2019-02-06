<?php
/**
 * Custom Recent Posts
 *
 * @since Ink 1.0
 */
class Stag_Widget_Recent_Posts_Grid extends Stag_Widget {
	/**
	 * Constructor
	 */
	public function __construct() {
		$this->widget_id          = 'stag_widget_recent_posts_grid';
		$this->widget_cssclass    = 'stag_widget_recent_posts_grid';
		$this->widget_description = __( 'Displays recent posts from Blog in post grid style.', 'om-assistant' );
		$this->widget_name        = __( 'Section: Recent Posts (Grid)', 'om-assistant' );
		$this->settings           = array(
			'title' => array(
				'type'  => 'text',
				'std'   => '',
				'label' => __( 'Title:', 'om-assistant' ),
			),
			'count' => array(
				'type'  => 'number',
				'std'   => '3',
				'label' => __( 'Number of posts to show:', 'om-assistant' ),
			),
			'category' => array(
				'type'  => 'categories',
				'std'   => '0',
				'label' => __( 'Post Category:', 'om-assistant' ),
			),
			'layout' => array(
				'type'    => 'select',
				'std'     => 'post-half',
				'label'   => __( 'Post Grid Layout:', 'om-assistant' ),
				'options' => array(
					'post-full'      => __( 'Post Full', 'om-assistant' ),
					'post-half'      => __( 'Post Half', 'om-assistant' ),
					'post-one-third' => __( 'Post One Third', 'om-assistant' ),
				)
			),
			'show-button' => array(
				'type'  => 'checkbox',
				'std'   => true,
				'label' => __( 'Show More Posts button', 'om-assistant' ),
			),
			'button-text' => array(
				'type'  => 'text',
				'std'   => __( 'More Posts', 'om-assistant' ),
				'label' => __( 'Button Text:', 'om-assistant' ),
			),
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

		$title       = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );
		$count       = absint( $instance['count'] );
		$category    = (int) $instance['category'];
		$layout      = $instance['layout'];
		$posts       = wp_get_recent_posts( array( 'numberposts' => $count, 'post_status' => 'publish', 'category' => $category ), OBJECT );
		$show_button = (bool) $instance['show-button'];
		$button_text = $instance['button-text'];
		$cat_link    = get_category_link( $category );
		$button_link = $cat_link;

		if ( 0 === $category ) {
			$button_link = get_permalink( get_option( 'page_for_posts' ) );
		}

		global $post;

		echo $before_widget;

		echo '<section class="custom-recent-posts layout-' . esc_attr( $layout ) . '">';

		if ( $title ) {
			echo $before_title . $title . $after_title;
		}

		// Add filter for adding post-grid class
		add_filter( 'stag_showing_grid_posts', '__return_true' );

		foreach ( $posts as $post ) : setup_postdata( $post );
			get_template_part( 'partials/content' );
		endforeach;

		echo '</section>';

		?>

		<?php if ( $show_button && $button_link != ''  ) : ?>
		<div class="more-posts">
			<span><a href="<?php echo esc_url( $button_link ); ?>" class="button"><?php echo esc_html( $button_text ); ?></a></span>
		</div>
		<?php endif; ?>

		<?php

		wp_reset_postdata();

		remove_all_filters( 'stag_showing_grid_posts' );

		echo $after_widget;

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

add_action( 'widgets_init', array( 'Stag_Widget_Recent_Posts_Grid', 'register' ) );
