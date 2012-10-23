<?php

class CPDSavedSearchesWidget extends WP_Widget {
	function init(){		
		wp_enqueue_script('cpd-saved-searches-controller', cpd_plugin_dir_url(__FILE__) . "js/cpd-saved-searches-widget-controller.js");
		add_action('widgets_init', array('CPDSavedSearchesWidget','load_widgets'));
		add_action('wp_ajax_cpd_saved_searches_widget_ajax', array('CPDSavedSearchesWidget', 'ajax'));
		add_action('wp_ajax_nopriv_cpd_saved_searches_widget_ajax', array('CPDSavedSearchesWidget', 'ajax'));
		add_action('wp_ajax_cpd_saved_searches_widget_remove_item_widget_ajax', array('CPDSavedSearchesWidget', 'remove_item_ajax'));
		add_action('wp_ajax_nopriv_cpd_saved_searches_widget_remove_item_widget_ajax', array('CPDSavedSearchesWidget', 'remove_item_ajax'));
	}
	
	function CPDSavedSearchesWidget(){
		$widget_ops = array( 
			'classname' => 'saved_searches_widget', 
			'description' => __('Saved Searches Widget', 'saved_searches_widget') 
		);
		
		$control_ops = array( 
			'width' => 300, 'height' => 350, 
			'id_base' => 'saved_searches_widget' 
		);		
		
		$this->WP_Widget( 
			'saved_searches_widget', __('Saved Searches Widget', 'saved_searches_widget'), $widget_ops, $control_ops 
		);
	}
	
	function widget($args, $instance) {
		extract($args);
		$home = home_url();
		$path_dir_plugin = $home.cpd_plugin_dir_url('cpd-search');
		$title = apply_filters('widget_title', $instance['title'] );		
		$save_a_search = cpd_get_template_contents("saved_search_popup");		
		echo $before_widget;
		if ($title) {
			echo $before_title;
			echo self::load_items();
			echo $save_a_search;
			echo $after_title;
		}
		echo $after_widget;
	}
	
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		return $instance;
	}
	
	function form($instance) {
		$defaults = array( 
		'title' => __('Saved Searches Widget', 'Saved Searches Widget')
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'CPD Saved Searches Widget'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>
		<?php
	}
	
	function load_widgets() {
		register_widget('CPDSavedSearchesWidget' );
	}
	
	function load_items() {
		$str = '';
		$home = home_url();
		$path_dir_plugin = $home.cpd_plugin_dir_url('cpd-search');
		$content_template = cpd_get_template_contents("saved_searches");		
	
		if(!isset($_SESSION['cpd_saved_searches_widget']) || count($_SESSION['cpd_saved_searches_widget']) == 0 ) {
			$content_template = str_replace("[contentbox]", "", $content_template);
			return $content_template;			
		}
		
		$list_li = $_SESSION['cpd_saved_searches_widget'];			
		$list_item_template = '';
		
		foreach($list_li as $data) {
			$item_template = substr($content_template,0,strpos($content_template,'<div class="clipboardseperator"></div>'));
			$item_template = str_replace("[pluginurl]", $path_dir_plugin, $item_template);
			$item_template = str_replace("[display_none]","", $item_template);
			$item_template = str_replace("[search_name]", $data['search_name'], $item_template);
			$item_template = str_replace("[date_last_search]", $data['date_last_search'], $item_template);
			$item_template = str_replace("[id]", $data['id'], $item_template);
			$item_template = str_replace("[postcode]", $data['postcode'], $item_template);
			$item_template = str_replace("[location]", $data['address'], $item_template);
			$item_template = str_replace("[tenure_text]", $data['tenure_text'], $item_template);
			$item_template = str_replace("[tenure]", $data['tenure'], $item_template);
			$item_template = str_replace("[sizefrom]", $data['sizefrom'], $item_template);
			$item_template = str_replace("[sizeto]", $data['sizeto'], $item_template);
			$item_template = str_replace("[size_units]", $data['size'], $item_template);
			if ($data['sectors']!= null) {
				$item_template = str_replace("[sectors]",implode(',', $data['sectors']), $item_template);
			}
			else {
				$item_template = str_replace("[sectors]","", $item_template);
			}
			if ($data['areas']!= null) {
				$item_template = str_replace("[areas]",implode(',', $data['areas']), $item_template);
			}
			else{
				$item_template = str_replace("[areas]","", $item_template);
			}
			$item_template = str_replace("[size]", $data['sizefrom'].'-'. $data['sizeto'].$data['size_text'], $item_template);
			$list_item_template .= $item_template;
		}
		return str_replace("[contentbox]", $list_item_template, $content_template);
	}
	
	function ajax() {
		$home = home_url();
		$path_dir_plugin = $home.cpd_plugin_dir_url('cpd-search');	
		$search_name = trim($_REQUEST['search_name']);
		$date_last_search = trim($_REQUEST['date_last_search']);
		$id = trim($_REQUEST['id']);
		$address= trim($_REQUEST['address']);
		$postcode= trim($_REQUEST['postcode']);
		$tenure= trim($_REQUEST['tenure']);
		$tenure_text= trim($_REQUEST['tenure_text']);
		$sizeto= $_REQUEST['sizeto'];
		$sizefrom= $_REQUEST['sizefrom'];
		$size_text= $_REQUEST['size_text'];
		$size= $_REQUEST['size'];
		$sectors= $_REQUEST['sectors'];
		$areas= $_REQUEST['areas'];
		
		if(!isset($_SESSION['cpd_saved_searches_widget'])) {
			$cpd_saved_searches_widget = array();
			$_SESSION['cpd_saved_searches_widget'] = $cpd_saved_searches_widget;
		}
		
		$row = array();
		$row['search_name'] = $search_name;
		$row['date_last_search'] = $date_last_search;
		$row['id'] = $id;
		$row['address'] = $address;
		$row['postcode'] = $postcode;
		$row['tenure'] = $tenure;
		$row['tenure_text'] = $tenure_text;
		$row['sizeto'] = $sizeto;
		$row['sizefrom'] = $sizefrom;
		$row['size'] = $size;
		$row['size_text'] = $size_text;
		$row['sectors'] = $sectors;
		$row['areas'] = $areas;
		$results[] = $row;
	
		$cpd_saved_searches_widget = $_SESSION['cpd_saved_searches_widget'];
		$cpd_saved_searches_widget[] = $results[0];
		$_SESSION['cpd_saved_searches_widget'] = $cpd_saved_searches_widget;
		
		if(is_null($sectors))
		{
			$sectors = array();
		}
		
		if(is_null($areas))
		{
			$areas = array();
		}
		
		$response = array(
			'pluginurl' => $path_dir_plugin,
			'success' => true,
			'search_name'=> $search_name,
			'date_last_search'=> $date_last_search,
			'id'=> $id,
			'address'=> $address,
			'postcode'=> $postcode,
			'tenure'=> $tenure,
			'tenure_text'=> $tenure_text,
			'sizeto'=> $sizeto,
			'sizefrom'=> $sizefrom,
			'size'=> $size,
			'size_text'=> $size_text,			
			'sectors'=> implode(",", $sectors),
			'areas'=> implode(",", $areas),
		);
	
		header( "Content-Type: application/json" );
		echo json_encode($response);
		exit;
	}
	
	function remove_item_ajax(){
		$id = $_REQUEST['id'];  
		$cpd_saved_searches_widget_temp = array();
		if(isset($_SESSION['cpd_saved_searches_widget'])) {
			$cpd_saved_searches_widget = $_SESSION['cpd_saved_searches_widget'];
			foreach($cpd_saved_searches_widget as $item) {
				if($item['id'] != $id){
					$cpd_saved_searches_widget_temp[] = $item;
				}
			}
			$_SESSION['cpd_saved_searches_widget'] = $cpd_saved_searches_widget_temp;
		}
		$response = array(
			'success' => true,
			'id'=> $id,
		);

		header( "Content-Type: application/json" );
		echo json_encode($response);
		exit;
	}
}

CPDSavedSearchesWidget::init();

?>
