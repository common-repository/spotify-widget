<?php /*

**************************************************************************

Plugin Name:  Spotify Widget
Plugin URI:   http://wordpress.org/extend/plugins/spotify-widget/
Description:  A Spotify play button
Version:      1.0.1
Author:       Josh Betz
Author URI:   http://joshbetz.com/
License:      GPLv2 or later

Text Domain:  jb-spotify-widget
Domain Path:  /languages/

**************************************************************************/

class Jb_Spotify_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress
	 */
	public function __construct() {
		parent::__construct( 'jb_spotify_widget', 'Spotify Widget', array( 'description' => __( 'A Spotify play button', 'jb-spotify-widget' ) ) );
	}

 	public function form( $instance ) {
 		$title = isset( $instance[ 'title' ] ) ? $instance[ 'title' ] : __( 'Spotify', 'jb-spotify-widget' );
 		$playlist = isset( $instance[ 'playlist' ] ) ? $instance[ 'playlist' ] : 'spotify:user:1219859855:playlist:1aLEMVC78LEnbxMvz4lhTH';
 		$theme = isset( $instance[ 'theme' ] ) ? $instance[ 'theme' ] : 'black';
 		$size = isset( $instance[ 'size' ] ) ? $instance[ 'size' ] : 'large';

 		$width = isset( $instance[ 'width' ] ) ? $instance[ 'width' ] : 300;
 		$height = isset( $instance[ 'height' ] ) ? $instance[ 'height' ] : 380;
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'jb-spotify-widget' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'playlist' ); ?>"><?php _e( 'Spotify URI:', 'jb-spotify-widget' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'playlist' ); ?>" name="<?php echo $this->get_field_name( 'playlist' ); ?>" type="text" value="<?php echo esc_attr( $playlist ); ?>" />
		</p>

		<h4>Advanced Options</h4>
		<p>
			<label for="<?php echo $this->get_field_id( 'size' ); ?>"><?php _e( 'Size:', 'jb-spotify-widget' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'size' ); ?>" name="<?php echo $this->get_field_name( 'size' ); ?>" type="radio" value="large"<?php if ( 'large' == $size ) echo ' checked'; ?> /> <?php _e( 'Large', 'jb-spotify-widget' ); ?>
			<input id="<?php echo $this->get_field_id( 'size' ); ?>" name="<?php echo $this->get_field_name( 'size' ); ?>" type="radio" value="small"<?php if ( 'small' == $size ) echo ' checked'; ?> /> <?php _e( 'Small', 'jb-spotify-widget' ); ?><br>
			<input size="5" id="<?php echo $this->get_field_id( 'width' ); ?>" name="<?php echo $this->get_field_name( 'width' ); ?>" type="text" value="<?php echo esc_attr( $width ); ?>" /> x <input size="5" id="<?php echo $this->get_field_id( 'height' ); ?>" name="<?php echo $this->get_field_name( 'height' ); ?>" type="text" value="<?php echo esc_attr( $height ); ?>" /> pixels

		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'theme' ); ?>"><?php _e( 'Theme:', 'jb-spotify-widget' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'theme' ); ?>" name="<?php echo $this->get_field_name( 'theme' ); ?>" type="radio" value="black"<?php if ( 'black' == $theme ) echo ' checked'; ?> /> <?php _e( 'Dark', 'jb-spotify-widget' ); ?>
			<input id="<?php echo $this->get_field_id( 'theme' ); ?>" name="<?php echo $this->get_field_name( 'theme' ); ?>" type="radio" value="white"<?php if ( 'white' == $theme ) echo ' checked'; ?> /> <?php _e( 'Light', 'jb-spotify-widget' ); ?>
		</p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance[ 'title' ] = sanitize_text_field( $new_instance[ 'title' ] );
		$instance[ 'playlist' ] = sanitize_text_field( $new_instance[ 'playlist' ] );
		$instance[ 'theme' ] = 'black' == sanitize_key( $new_instance[ 'theme' ] ) ? 'black' : 'white';
		$instance[ 'size' ] = 'large' == sanitize_key( $new_instance[ 'size' ] ) ? 'large' : 'small';
		$instance[ 'width' ] = intval( $new_instance[ 'width' ] );

		$default_height = 'large' == $instance[ 'size' ] ? $instance[ 'width' ] + 80 : 80;
		$instance[ 'height' ] = 'small' == $instance[ 'size' ] || 80 < $new_instance[ 'height' ] - $instance[ 'width' ] ? intval( $new_instance[ 'height' ] ) : $default_height;

		return $instance;
	}

	public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', esc_attr( $instance[ 'title' ] ) );
		$playlist = esc_attr( $instance[ 'playlist' ] );
		$theme = esc_attr( $instance[ 'theme' ] );
		$width = intval( $instance[ 'width' ] );
		$height = intval( $instance[ 'height' ] );

		print $before_widget;
		if ( ! empty( $title ) )
			print $before_title . $title . $after_title;
		printf( '<iframe src="https://embed.spotify.com/?uri=%s&theme=%s" frameborder="0" width="%d" height="%d" allowtransparency="true"></iframe>', $playlist, $theme, $width, $height );
		print $after_widget;
	}

}

add_action( 'widgets_init', create_function( '', 'register_widget( "Jb_Spotify_Widget" );' ) );