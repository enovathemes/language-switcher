<?php 
function yourprefix_custom_language_switcher($atts, $content = null) {

    extract(shortcode_atts(
        array(
            'extra_class'  => '',
        ), $atts)
    );

    $output      = '';

    $class = array();

	if (!empty($extra_class)) {
		$class[] = esc_attr($extra_class);
	}

    $class[] = 'language-switcher';

    $element_id = rand(1,1000000);

    $output .= '<div id="language-switcher-'.$element_id.'" class="'.implode(" ", $class).'">';
        	
    	$output .= '<div class="language-toggle sm-globe hbe-toggle" data-close-icon="'.esc_attr($close_icon).'"></div>';

    	// Let's give WPML priority
    	if (class_exists('SitePress')){

    		$languages = icl_get_languages('skip_missing=0');

    		if(1 < count($languages)){
    			$output .= '<ul class="wpml-ls">';
				    foreach($languages as $l){
				    	$output .= '<li><a href="'.$l['url'].'"><img src="'.$l['country_flag_url'].'" />'.$l['translated_name'].'</a><li>';
				    }
			    $output .= '</ul>';
			}

		}elseif(function_exists('pll_the_languages')) {
			$output .= '<ul class="polylang-ls">';
				$output .=pll_the_languages(
					array(
						'echo'=>0,
						'show_flags'=>1,
						'hide_if_empty'=>0
					)
				);
			$output .= '</ul>';
		} else {
			$output .= '<ul class="no-ls">';
				$output .= '<li><a target="_blank" href="//wordpress.org/plugins/polylang/">'.esc_html__("Polylang","textdomain").'</a></li>';
				$output .= '<li><a target="_blank" href="//wpml.org/">'.esc_html__("WPML","textdomain").'</a></li>';
			$output .= '</ul>';
		}

    $output .= '</div>';

    return $output;
    
}

add_shortcode('custom_language_switcher', 'yourprefix_custom_language_switcher');


add_action('widgets_init', 'yourprefix_register_ls_widget');
function yourprefix_register_ls_widget(){
	register_widget( 'Enovathemes_Addons_WP_Widget_LS' );
}

class  Enovathemes_Addons_WP_Widget_LS extends WP_Widget {

	public function __construct() {
		parent::__construct(
			'flickr',
			esc_html__('* Custom language switcher', 'textdomain'),
			array( 'description' => esc_html__('WPML or Polylang custom language switcher', 'textdomain'))
		);
	}

	public function widget( $args, $instance ) {

		extract($args);

		$title          = apply_filters( 'widget_title', $instance['title'] );
		$extra_class    = isset($instance['extra_class']) ? esc_attr($instance['extra_class']) : "";

		echo $before_widget;
		
			if ( ! empty( $title ) ){echo $before_title . $title . $after_title;}

			echo do_shortcode('[custom_language_switcher extra_class="'.esc_attr($extra_class).'"]');

		echo $after_widget;
	}

 	public function form( $instance ) {

 		$defaults = array(
 			'title'       => esc_html__('Custom language switcher', 'textdomain'),
 			'extra_class' => '',
 		);

 		$instance = wp_parse_args((array) $instance, $defaults);

		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php echo esc_html__( 'Title:', 'textdomain' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'extra_class' ); ?>"><?php echo esc_html__( 'Flickr id:', 'textdomain' ); ?><p><?php echo esc_html__( 'For more infomration go:', 'textdomain' ); ?> <a target="_blank" href="http://idgettr.com/"><?php echo esc_html__( 'here', 'textdomain' ); ?></a></p></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'extra_class' ); ?>" name="<?php echo $this->get_field_name( 'extra_class' ); ?>" type="text" value="<?php echo $instance['extra_class']; ?>" />
		</p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title']       = strip_tags( $new_instance['title'] );
		$instance['extra_class'] = strip_tags( $new_instance['extra_class'] );
		return $instance;
	}
}

?>