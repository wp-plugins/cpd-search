<?php

class CPDShortlist_Widget extends WP_Widget {
	function init() {
		add_action('widgets_init', array('CPDShortlist_Widget', 'load_widgets'));
		
		wp_enqueue_script('cpd-shortlist-widget', cpd_plugin_dir_url('cpd-shortlist-widget.js'));
		
		$shortlist = CPDSearch::fetch_shortlist();
		wp_localize_script('cpd-shortlist-widget', "CPDShortlist", $shortlist);
	}
	
	function load_widgets() {
		register_widget('CPDShortlist_Widget');
	}
	
	function CPDShortlist_Widget() {
		$widget_ops = array(
			'classname' => 'shortlist_widget',
			'description' => __('Property Shortlist Widget', 'login_widget')
		);
		
		$control_ops = array( 
			'width' => 300,
			'height' => 350,
			'id_base' => 'shortlist_widget'
		);
		
		$this->WP_Widget( 
			'shortlist_widget',
			__('CPD Shortlist Widget', 'Shortlist Widget'),
			$widget_ops,
			$control_ops
		);
	}
	
	function widget($args, $instance) {
		$title = apply_filters('widget_title', $instance['title']);
		$url = apply_filters('widget_url', $instance['url']);
		$link = $instance['link'];

		echo $args['before_widget'];
		if(!empty($title)) {
			echo $args['before_title'];
			echo $title;
			echo $args['after_title'];
		}
		echo self::shortlist();
		echo $args['after_widget'];
	}
	
	function shortlist() {
		return '<div class="cpdshortlist">Shortlisted properties...</div>';
	}
	
	function form($instance) {
		$defaults = array( 
			'title' => __('CPD Shortlist Widget', 'Shortlist Widget')
		);
		$instance = wp_parse_args((array)$instance, $defaults );
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'CPD Shortlist Widget'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>
		<?php
	}
}

CPDShortlist_Widget::init();

