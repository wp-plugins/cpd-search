<?php 

require_once(dirname(__FILE__) . "/cpd-register-interest.php");

class CPDLoginWidget extends WP_Widget {
	function init() {
		add_action( 'widgets_init', array('CPDLoginWidget', 'load_widgets'));
	}

	function CPDLoginWidget() {
		
		$widget_ops = array(
			'classname' => 'login_widget',
			'description' => __('Login Widget', 'login_widget')
		);
		
		$control_ops = array( 
			'width' => 300,
			'height' => 350,
			'id_base' => 'login_widget'
		);
		
		$this->WP_Widget( 
			'login_widget',
			__('Login Widget', 'Login_Widget'),
			$widget_ops,
			$control_ops
		);
	}
	
	function widget($args, $instance) {
		extract($args);
		
		$title = apply_filters('widget_title', $instance['title']);
		$url = apply_filters('widget_url', $instance['url']);
		$link = $instance['link'];

		echo $before_widget;
		if($title) {
			echo $before_title;
			echo $title;
			echo $after_title;
		}
		echo self::prefilled_form();
		echo $after_widget;
	}
	
	function prefilled_form() {
		// Read in necessary form template sections from plugin options
		$form = cpd_get_template_contents("login_widget");
		
		// Populate form defaults
		$form = str_replace("[email]", $email, $form);
		
		// Add theme/plugin base URLs
		$form = str_replace("[pluginurl]", plugins_url(), $form);
				
		if(cpd_search_is_user_registered())
		{
			$form = str_replace("[display1]", "display:none", $form);
			$form = str_replace("[display2]", "display:inline-block", $form);
		}
		else
		{
			$form = str_replace("[display2]", "display:none", $form);
			$form = str_replace("[display1]", "display:inline-block", $form);
		}
		
		return $form;
	}
	
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		return $instance;
	}
	
	function form($instance) {
		$defaults = array( 
			'title' => __('Login Widget', 'Login Widget')
		);
		$instance = wp_parse_args((array)$instance, $defaults );
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'CPD Login Widget'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>
		<?php
	}
	
	function load_widgets() {
		register_widget('CPDLoginWidget' );
	}
}

CPDLoginWidget::init();

?>
