<?php 
function cpd_clipboard_form_widget_init() {
	wp_enqueue_script('cpd-add-clipboard', cpd_plugin_dir_url(__FILE__) . "js/cpd-clipboard-widget-controller.js");
}

class CPD_Clipboard_Widget extends WP_Widget {

	function CPD_Clipboard_Widget() {
		cpd_clipboard_form_widget_init();
		
		$widget_ops = array( 
			'classname' => 'clipboard_widget', 
			'description' => __('Clipboard Widget', 'clipboard_widget') 
		);
		
		$control_ops = array( 
			'width' => 300, 'height' => 350, 
			'id_base' => 'clipboard_widget' 
		);
		
		$this->WP_Widget( 
			'clipboard_widget', __('Clipboard Widget', 'Clipboard_Widget'), $widget_ops, $control_ops 
		);
	}
	
	function widget( $args, $instance ) {
		
		extract( $args );
		$home = home_url();
		$path_dir_plugin = $home.cpd_plugin_dir_url('cpd-search');
		
		$title = apply_filters('widget_title', $instance['title'] );
		echo $before_widget;
		
		if ( $title )
			echo $before_title;
			echo cpd_clipboard_widget_load_items();
			echo $after_title;
		echo $after_widget;
	}
	
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		return $instance;
	}
	
	function form( $instance ) {
		$defaults = array( 
		'title' => __('Clipboard Widget', 'Clipboard Widget')
		);
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'CPD Clipboard Widget'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>
	<?php
	}
}

function cpd_clipboard_widget_load_items()
{	
	$str = '';
	$home = home_url();
	$path_dir_plugin = $home.cpd_plugin_dir_url('cpd-search');
	$content_template  = cpd_get_template_contents("clipboard_widget");
	$form_clipboard  = substr($conten_template,strpos($content_template,"<!--template item-->"));
	if(!isset($_SESSION['cpd_clipboard_widget_propref_objs']) || count($_SESSION['cpd_clipboard_widget_propref_objs']) == 0 )
	{
		$item_template  = substr($content_template,0,strpos($content_template,"<!--template item-->"));	
		$item_template  = str_replace("[display_none]","display:none",$item_template);			
		$form_clipboard  = str_replace("[contentbox]",$item_template,$form_clipboard);
	}
	else 
	{
		$list_propref_obj = $_SESSION['cpd_clipboard_widget_propref_objs'];		
		
		if(count($list_propref_obj) != 0)
		{
			$list_item_template = '';
			$item_template_displaynnone  = substr($content_template,0,strpos($content_template,"<!--template item-->"));	
			$item_template_displaynnone  = str_replace("[display_none]","display:none",$item_template_displaynnone);
			foreach($list_propref_obj as $obj)
			{
				$item_template  = substr($content_template,0,strpos($content_template,"<!--template item-->"));				
				$item_template  = str_replace("[display_none]","",$item_template);
				$item_template  = str_replace("[id]",$obj['PropertyID'],$item_template);
				$item_template  = str_replace("[pluginurl]",$path_dir_plugin,$item_template);
				$item_template  = str_replace("[Address]",$obj['Address'],$item_template);
				$item_template  = str_replace("[Postcode]",$obj['Postcode'],$item_template);
				$item_template  = str_replace("[Location]",$obj['Location'],$item_template);
				$item_template  = str_replace("[TenureDescription]",$obj['TenureDescription'],$item_template);
				$item_template  = str_replace("[SizeDescription]",$obj['SizeDescription'],$item_template);
				$list_item_template .= $item_template;
			}
			$list_item_template = $item_template_displaynnone.$list_item_template;
			$form_clipboard  = str_replace("[contentbox]",$list_item_template,$form_clipboard);
		}
	}
	return $form_clipboard;
}

function cpd_clipboard_widget_load_widgets() {
	register_widget('CPD_Clipboard_Widget' );
}

add_action( 'widgets_init', 'cpd_clipboard_widget_load_widgets' );

function cpd_clipboard_widget_ajax() {
	
	// Gather inputs from request/session
	$propref = trim($_REQUEST['propref']);
	
	if(!isset($_SESSION['cpd_clipboard_widget_propref']))
	{
		$list_propref = array();
		$_SESSION['cpd_clipboard_widget_propref'] = $list_propref;
	}
	
	$list_propref =  $_SESSION['cpd_clipboard_widget_propref'];
	$status = true;
	
	foreach($list_propref as $value)
	{
		if(trim($value) == trim($propref))
		{
			$status = false;
		}
	}	
	
	if($status)
	{
		$list_propref[] = $propref;
	}
	else
	{
		$response = array(
		'success' => false,
		'error' => "Item exists on clipboard"
		);
		header( "Content-Type: application/json" );
		echo json_encode($response);
		exit;
	}
	
	$_SESSION['cpd_clipboard_widget_propref'] = $list_propref;
	
	
	// Send our search request to the server
	$searchCriteria = new SearchCriteriaType();
	$searchCriteria->Start = 1;
	$searchCriteria->Limit = 1;
	$searchCriteria->DetailLevel = "full";
	$propertyIDsType = new PropertyIDsType();
	$propertyIDsType->PropertyID = $propref;
	$searchCriteria->PropertyIDs = $propertyIDsType;
	
	// Perform search
	$searchRequest = new SearchPropertyType();
	$searchRequest->SearchCriteria = $searchCriteria;
	
	try {
		$options = get_option('cpd-search-options');
		$soapopts = array('trace' => 1, 'exceptions' => 1);
		$client = new CPDPropertyService($options['cpd_soap_base_url']."CPDPropertyService?wsdl", $soapopts);
		$headers = wss_security_headers($options['cpd_agentref'], $options['cpd_password']);
		$client->__setSOAPHeaders($headers);
		$searchResponse = $client->SearchProperty($searchRequest);
	}
	catch(Exception $e) {
		file_put_contents("/tmp/debugme", print_r($client, true));
		$response = array(
			'success' => false,
			'error' => $e->getMessage()
		);
		header( "Content-Type: application/json" );
		echo json_encode($response);
		exit;
	}
	
	// Filter results to avoid sending sensitive fields over the wire
	$results = array();
	if(isset($searchResponse->PropertyList->Property)) {
		// Workaround for PITA in PHP SOAP parser...
		$propList = $searchResponse->PropertyList->Property;
		if($propList instanceof PropertyType) {
			$propList = array($propList);
		}
		foreach($propList as $record) {
			$row = array();
			$row['PropertyID'] = $record->PropertyID;
			$row['SectorDescription'] = $record->SectorDescription;
			$row['SizeDescription'] = $record->SizeDescription;
			$row['TenureDescription'] = $record->TenureDescription;
			$row['BriefSummary'] = $record->BriefSummary;
			$row['Address'] = $record->Address;
			$row['Latitude'] = $record->Latitude;
			$row['Longitude'] = $record->Longitude;
			$row['RegionName'] = $record->RegionName;
			$row['Location'] = $record->RegionName;			
			$row['Postcode'] = $record->Postcode;			
			
			// Add thumb URL, only if one is available
			if(isset($record->PropertyMedia)) {
				$mediaList = $record->PropertyMedia;
				if($propList instanceof PropertyMediaType) {
					$propList = array($propList);
				}
				foreach($mediaList as $media) {
					if($media->Type == "photo" && $media->Position == 1) {
						$row['ThumbURL'] = $media->ThumbURL;
						break;
					}
				}
			}

			$results[] = $row;
		}
	}
	
	if(!isset($_SESSION['cpd_clipboard_widget_propref_objs']))
	{
		$cpd_clipboard_widget_propref_objs = array();
		$_SESSION['cpd_clipboard_widget_propref_objs'] = $cpd_clipboard_widget_propref_obj;
	}	
	
	$cpd_clipboard_widget_propref_objs = $_SESSION['cpd_clipboard_widget_propref_objs'];
	$cpd_clipboard_widget_propref_objs[] = $results[0];
	$_SESSION['cpd_clipboard_widget_propref_objs'] = $cpd_clipboard_widget_propref_objs;
	
	// Return response as JSON
	$response = array(
		'success' => true,
		'total' => $searchResponse->ResultCount,
		'results' => $results,
	);
	header( "Content-Type: application/json" );
	echo json_encode($response);
	exit;
}

add_action('wp_ajax_cpd_clipboard_widget_ajax', 'cpd_clipboard_widget_ajax');
add_action('wp_ajax_nopriv_cpd_clipboard_widget_ajax', 'cpd_clipboard_widget_ajax');


function cpd_clipboard_widget_delete_ajax() {
	
	// Gather inputs from request/session
	$propref = trim($_REQUEST['propref']);
	
	if(!isset($_SESSION['cpd_clipboard_widget_propref']))
	{
		$response = array(
		'success' => false,
		'error' => "Not items exists on clipboard"
		);
		header( "Content-Type: application/json" );
		echo json_encode($response);
		exit;
	}
	
	$cpd_clipboard_widget_propref_objs_temp = array();
	
	if(isset($_SESSION['cpd_clipboard_widget_propref_objs']))
	{
		$cpd_clipboard_widget_propref_objs = $_SESSION['cpd_clipboard_widget_propref_objs'];
		foreach($cpd_clipboard_widget_propref_objs as $item)
		{
			
			if($item["PropertyID"] != $propref)
			{
				$cpd_clipboard_widget_propref_objs_temp[] = $item;
			}
		}
		$_SESSION['cpd_clipboard_widget_propref_objs'] = $cpd_clipboard_widget_propref_objs_temp;
	}	
	
	$list_propref =  $_SESSION['cpd_clipboard_widget_propref'];
	$status = true;
	$list_propref_temp = array();
	
	foreach($list_propref as $value)
	{
		if(trim($value) != trim($propref))
		{
			$list_propref_temp[] = $value;
		}
	}		
	
	$_SESSION['cpd_clipboard_widget_propref'] = $list_propref_temp;
	$response = array(
	'success' => true,
	);
	header( "Content-Type: application/json" );
	echo json_encode($response);
	exit;

}

add_action('wp_ajax_cpd_clipboard_widget_delete_ajax', 'cpd_clipboard_widget_delete_ajax');
add_action('wp_ajax_nopriv_cpd_clipboard_widget_delete_ajax', 'cpd_clipboard_widget_delete_ajax');

function cpd_clipboard_widget_pushpost_ajax(){

	$current_user = wp_get_current_user();
	
	if (0 == $current_user->ID ) {
		$response = array(
			'success' => false,
			'error'  => "Please login",
		);
		header( "Content-Type: application/json" );
		echo json_encode($response);
		exit;
	} elseif ($current_user->roles[0] != 'administrator'){
		$response = array(
			'success' => false,
			'error'  => "You are not enough permission",
		);
		header( "Content-Type: application/json" );
		echo json_encode($response);
		exit;	
	}
	
	$propref = array();
	$propref = $_REQUEST['id'];
	$num_li  = count($propref);
	$temp = '';
	
	for($i = 0; $i < $num_li;$i++){
		$temp .= '[publish_property_ref id="'.$propref[$i].'"]<br/>';
	}
	
	$update_post = array(
			'post_content' => $temp,
			'post_type'    => 'post',
			'post_status'  => 'publish',
			'post_title'   => 'publish_property_ref '.$propref[$i]
		);
		
	$post = wp_insert_post($update_post);
	$link = get_edit_post_link($post,'&');
	
	$results   = array();
		$results['propref']	= $propref;
		$results['link'] = $link;
	
	$response = array(
		'success' => true,
		'results'  => $results
	);
	
	header( "Content-Type: application/json" );
	echo json_encode($response);
	exit;
}

add_action('wp_ajax_cpd_clipboard_widget_pushpost_ajax', 'cpd_clipboard_widget_pushpost_ajax');
add_action('wp_ajax_nopriv_cpd_clipboard_widget_pushpost_ajax', 'cpd_clipboard_widget_pushpost_ajax');

function publish_property_ref($atts){
	
	$propref = $atts["id"];
	
	// Send our search request to the server
	$searchCriteria = new SearchCriteriaType();
	$searchCriteria->Start = 1;
	$searchCriteria->Limit = 1;
	$searchCriteria->DetailLevel = "full";
	$propertyIDsType = new PropertyIDsType();
	$propertyIDsType->PropertyID = $propref;
	$searchCriteria->PropertyIDs = $propertyIDsType;
	
	// Perform search
	$searchRequest = new SearchPropertyType();
	$searchRequest->SearchCriteria = $searchCriteria;
	
	try {
		$options = get_option('cpd-search-options');
		$soapopts = array('trace' => 1, 'exceptions' => 1);
		$client = new CPDPropertyService($options['cpd_soap_base_url']."CPDPropertyService?wsdl", $soapopts);
		$headers = wss_security_headers($options['cpd_agentref'], $options['cpd_password']);
		$client->__setSOAPHeaders($headers);
		$searchResponse = $client->SearchProperty($searchRequest);
	}
	catch(Exception $e) {
		echo $e->getMessage();
	}
	
	// Filter results to avoid sending sensitive fields over the wire
	$results = array();
	if(isset($searchResponse->PropertyList->Property)) {
		// Workaround for PITA in PHP SOAP parser...
		$propList = $searchResponse->PropertyList->Property;
		if($propList instanceof PropertyType) {
			$propList = array($propList);
		}
		
		foreach($propList as $record) {
			
			$row = array();
			$row['PropertyID'] = $record->PropertyID;
			$row['SectorDescription'] = $record->SectorDescription;
			$row['SizeDescription'] = $record->SizeDescription;
			$row['TenureDescription'] = $record->TenureDescription;
			$row['BriefSummary'] = $record->BriefSummary;
			$row['Address'] = $record->Address;
			$row['Latitude'] = $record->Latitude;
			$row['Longitude'] = $record->Longitude;
			$row['RegionName'] = $record->RegionName;
			$row['Location'] = $record->RegionName;			
			$row['Postcode'] = $record->Postcode;			
			
			// Add thumb URL, only if one is available
			if(isset($record->PropertyMedia)) {
				$mediaList = $record->PropertyMedia;
				if($propList instanceof PropertyMediaType) {
					$propList = array($propList);
				}
				foreach($mediaList as $media) {
					if($media->Type == "photo" && $media->Position == 1) {
						$row['ThumbURL'] = $media->ThumbURL;
						break;
					}
				}
			}
			
			$results[] = $row;
			$form = cpd_get_template_contents("publish_property");
			$form = str_replace("[resultnum]","", $form);
			$form = str_replace("[propref]", $propref, $form);
			$form = str_replace("[typedesc]", $row['SectorDescription'], $form);
			$form = str_replace("[sizedesc]", $row['SizeDescription'], $form);
			$form = str_replace("[areadesc]", $row['Address'], $form);
			$form = str_replace("[summary]", $row['BriefSummary'], $form);
			$form = str_replace("[photo]", "<img src='".$row['ThumbURL']."'", $form);
			$form = str_replace("[themeurl]", get_template_directory_uri(), $form);
			
		}
	}
	return $form ;
}

add_shortcode('publish_property_ref','publish_property_ref');
?>
