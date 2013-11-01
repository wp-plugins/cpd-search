<?php

class CPDClipboardWidget extends WP_Widget {
	function init() {
		wp_enqueue_script('cpd-add-clipboard', plugins_url("cpd-search")."/cpd-clipboard-widget.js");
		
		add_action( 'widgets_init', array('CPDClipboardWidget', 'load_widgets'));
		add_action('wp_ajax_cpd_clipboard_widget_add_ajax', array('CPDClipboardWidget', 'add_ajax'));
		add_action('wp_ajax_nopriv_cpd_clipboard_widget_add_ajax', array('CPDClipboardWidget', 'add_ajax'));
		add_action('wp_ajax_cpd_clipboard_widget_delete_ajax', array('CPDClipboardWidget', 'delete_ajax'));
		add_action('wp_ajax_nopriv_cpd_clipboard_widget_delete_ajax', array('CPDClipboardWidget', 'delete_ajax'));
		add_action('wp_ajax_cpd_clipboard_widget_pushpost_ajax', array('CPDClipboardWidget', 'pushpost_ajax'));
		add_action('wp_ajax_nopriv_cpd_clipboard_widget_pushpost_ajax', array('CPDClipboardWidget', 'pushpost_ajax'));
		add_shortcode('publish_property_ref', array('CPDClipboardWidget', 'publish_property_ref'));
	}
	
	function load_widgets() {
		register_widget('CPDClipboardWidget' );
	}
	
	function CPDClipboardWidget() {
		$widget_ops = array(
			'classname' => 'clipboard_widget',
			'description' => __('Clipboard Widget', 'clipboard_widget')
		);
		
		$control_ops = array( 
			'width' => 300,
			'height' => 350,
			'id_base' => 'clipboard_widget'
		);
		
		$this->WP_Widget( 
			'clipboard_widget',
			__('Clipboard Widget', 'Clipboard_Widget'),
			$widget_ops,
			$control_ops
		);
	}
	
	function widget($args, $instance) {
		extract($args);
		
		$title = apply_filters('widget_title', $instance['title']);
		echo $before_widget;
		if($title) {
			echo $before_title;
			echo $this->load_items();
			echo $after_title;
		}
		echo $after_widget;
	}
	
	function load_items() {
		return cpd_get_template_contents("clipboard_widget");
	}
	
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		return $instance;
	}
	
	function form($instance) {
		$defaults = array(
			'title' => __('Clipboard Widget', 'Clipboard Widget')
		);
		$instance = wp_parse_args((array)$instance, $defaults);
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'CPD Clipboard Widget'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>
		<?php
	}

	function add_ajax() {
		// Gather inputs from request/session
		$property_id = trim($_REQUEST['propref']);
		
		// Initialise clipboard, if not already done
		if(!isset($_SESSION['clipboard_property_ids'])) {
			$list_property_ids = array();
			$_SESSION['clipboard_property_ids'] = $list_property_ids;
		}
		$list_property_ids = $_SESSION['clipboard_property_ids'];
		if(!isset($_SESSION['clipboard_property_objs'])) {
			$list_property_objs = array();
			$_SESSION['clipboard_property_objs'] = $property_id_obj;
		}
		$list_property_objs = $_SESSION['clipboard_property_objs'];
		
		// Identify whether item already exists in the clipboard (and report)
		$status = true;
		foreach($list_property_ids as $value) {
			if(trim($value) == trim($property_id)) {
				$status = false;
				break;
			}
		}
		if(!$status) {
			$response = array(
				'success' => false,
				'error' => "Item exists on clipboard"
			);
			header( "Content-Type: application/json" );
			echo json_encode($response);
			exit;
		}
		
		// Add this propref to the clipboard
		$list_property_ids[] = $property_id;
		$_SESSION['clipboard_property_ids'] = $list_property_ids;
		
		// Add the object to the clipboard
		$list_property_objs[] = $results[0];
		$_SESSION['clipboard_property_objs'] = $list_property_objs;
		
		// Adds the record to the user's clipboard on the server
		$params = array(
			'clipboard' => null,
			'property' => $property_id
		);
		$url = sprintf("%s/users/clipboards/results/", get_option('cpd_rest_url'));
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'X-CPD-Token: '.cpd_get_user_token(),
			'Content-Type: application/json'
		));
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$rawdata = curl_exec($curl);
		$info = curl_getinfo($curl);
		if(curl_errno($curl)) {
			header( "HTTP/1.1 501 Internal Server Error" );
			echo "Curl error: ".curl_error($curl);
			exit;
		}
		curl_close($curl);
		if($info['http_code'] != 201) {
			header( "HTTP/1.1 501 Internal Server Error" );
			echo "Proxy returned status ".$info['http_code'];
			exit;
		}
		
		// Return response as JSON
		$response = array(
			'success' => true,
			'total' => count($list_property_ids)
		);
		header( "HTTP/1.1 201 Created" );
		exit;
	}

	function delete_ajax() {
		// Gather inputs from request
		$property_id = trim($_REQUEST['propref']);
		
		// Handle clipboard being empty
		if(!isset($_SESSION['clipboard_property_ids'])) {
			$response = array(
				'success' => false,
				'error' => "No items currently added to the clipboard"
			);
			header( "Content-Type: application/json" );
			echo json_encode($response);
			exit;
		}
		
		// Create a new ids list with the object to delete missing
		$list_property_ids = $_SESSION['clipboard_property_ids'];
		$list_property_ids_temp = array();
		foreach($list_property_ids as $value) {
			if(trim($value) != trim($property_id)) {
				$list_property_ids_temp[] = $value;
			}
		}
		$_SESSION['clipboard_property_ids'] = $list_property_ids_temp;
		
		// Create a new objs list with the object to delete missing
		$list_property_objs = $_SESSION['clipboard_property_objs'];
		$list_property_objs_temp = array();
		if(isset($_SESSION['clipboard_property_objs'])) {
			foreach($list_property_objs as $item) {
				if($item["PropertyID"] != $property_id) {
					$list_property_objs_temp[] = $item;
				}
			}
		}
		$_SESSION['clipboard_property_objs'] = $list_property_objs_temp;
		
		$params = array(
			'clipboard' => null,
			'property' => $property_id
		);
		$url = sprintf("%s/users/clipboards/results/", get_option('cpd_rest_url'));
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'X-CPD-Token: '.cpd_get_user_token()
		));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$rawdata = curl_exec($curl);
		$info = curl_getinfo($curl);
		if(curl_errno($curl)) {
			header( "HTTP/1.1 501 Internal Server Error" );
			echo "Curl error: ".curl_error($curl);
			exit;
		}
		curl_close($curl);
		if($info['http_code'] != 204) {
			header( "HTTP/1.1 501 Internal Server Error" );
			echo "Proxy returned status ".$info['http_code'];
			exit;
		}

		// Indicate success
		$response = array(
			'success' => true,
		);
		header( "Content-Type: application/json" );
		echo json_encode($response);
		exit;
	}

	function pushpost_ajax(){
		$current_user = wp_get_current_user();
		if ($current_user->ID < 1) {
			$response = array(
				'success' => false,
				'error' => "Please log in as an administrator to perform this action.", 
			);
			header( "Content-Type: application/json" );
			echo json_encode($response);
			exit;
		}
		if ($current_user->roles[0] != 'administrator') {
			$response = array(
				'success' => false,
				'error' => "You must be an administrator to perform this action.", 
			);
			header( "Content-Type: application/json" );
			echo json_encode($response);
			exit;
		}
		
		// Create a template containing short-codes for the indicated props
		$property_ids = $_REQUEST['id'];
		$temp = '';
		foreach($property_ids as $property_id) {
			$temp .= '[publish_property_ref id="'.$property_id.'"]<br/>';
		}
		
		// Create the new post entry
		$update_post = array(
			'post_content' => $temp,
			'post_type' => 'post',
			'post_status' => 'publish',
			'post_title' => 'publish_property_ref '.join(",", $property_ids)
		);
		$post = wp_insert_post($update_post);
		$link = get_edit_post_link($post, '&');
		
		// 
		$results  = array();
		$results['propref'] = $property_id;
		$results['link'] = $link;
		$response = array(
			'success' => true,
			'link' => $link,
		);
		header( "Content-Type: application/json" );
		echo json_encode($response);
		exit;
	}

	function publish_property_ref($atts) {
		$property_id = $atts["id"];
		
		// [TODO] Request the details of this property
	
		// Filter results to avoid sending sensitive fields over the wire
		$results = array();
		if(isset($searchResponse->PropertyList->Property)) {
			$propList = $searchResponse->PropertyList->Property;
			foreach($propList as $record) {
				// Add thumb URL, only if one is available
				$thumbURL = null;
				if(isset($record->PropertyMedia)) {
					$mediaList = $record->PropertyMedia;
					foreach($mediaList as $media) {
						if($media->Type == "photo" && $media->Position == 1) {
							$thumbURL = $media->ThumbURL;
							break;
						}
					}
				}
				
				$results[] = $row;
				$form = cpd_get_template_contents("publish_property");
				$form = str_replace("[resultnum]", "", $form);
				$form = str_replace("[propref]", $prop->PropertyID, $form);
				$form = str_replace("[typedesc]", $prop->SectorDescription, $form);
				$form = str_replace("[sizedesc]", $prop->SizeDescription, $form);
				$form = str_replace("[areadesc]", $prop->Address, $form);
				$form = str_replace("[summary]", $prop->BriefSummary, $form);
				$form = str_replace("[photo]", $thumbURL == "" ? "" : "<img src=\"".$thumbURL."\"/>", $form);
				$form = str_replace("[pluginurl]", plugins_url(), $form);
			}
		}
		
		return $form;
	}
}

add_action("init", array("CPDClipboardWidget", "init"));

?>
