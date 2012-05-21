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
						echo "<div id='clipboard'>
				  <div class='clipboard-content'>
					<h2><span>". $title ."</span></h2>
					<div class='saved'>
						<span><label><input type='checkbox' name='cbx_clipboard_all' id='cbx_clipboard_all' onclick='cpd_clipboard_widget_sellect_all(this);' />Select all</label></span>
						<span class='properties'><span id='number_item'></span> properties saved</span>
					</div>
					<div class='block'>
					<div class='location'>
                    <ul id='clipboard_list'>";
					echo cpd_clipboard_widget_load_items();
					echo"</ul>
					</div>
					</div>
				  <div class='nav'>
				  <p><a href='#' name='btn_preview_clipboard' id='btn_preview_clipboard'>PREVIEW</a></p>
				  <ul>		
					  <li><a href='#' class='email'>eMail</a></li>
					  <li><a href='#' class='print'>Print</a></li>
					  <li class='last'><a href='#' class='save'>Save</a></li>
				  </ul>
				  <ul class='last'>		
					  <li><a href='#'>Twiter</a></li>
					  <li><a href='#'>Facebook</a></li>
					  <li class='last'><a href='#'>Blog post</a></li>
				  </ul>
				  </div>
				  </div>";
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
	
	if(!isset($_SESSION['cpd_clipboard_widget_propref_objs']) || count($_SESSION['cpd_clipboard_widget_propref_objs']) == 0 )
	{
		
	}
	else 
	{
		$list_propref_obj = $_SESSION['cpd_clipboard_widget_propref_objs'];		
		
		if(count($list_propref_obj) != 0)
		{
			foreach($list_propref_obj as $obj)
			{
				$str .= "<li propref_clipboard = '".$obj['PropertyID']."' id = '".$obj['PropertyID']."'><div class='checkbox'><input type='checkbox' name='cbx_clipboard' id='cbx_clipboard' onclick='cpd_clipboard_widget_sellect_obj(this);' /></div><div class='content-block'><p class='name'> <span><img src='".$path_dir_plugin."images/btn_close.png' propref_clipboard = '".$obj['PropertyID']."' class='btn_close' onClick='cpd_clipboard_widget_delete(this);' width='15' height='13' /></span> ". $obj['Address']. "</p><p><span>Postcode:</span> " .$obj['Postcode'] . "</p><p><span>Location:</span> " .$obj['Location'] . "</p><p><span>Tenure:</span> " .$obj['TenureDescription'] . "</p><p><span>Size:</span> " .$obj['SizeDescription'] . "</p></div></li>";
			}
		}
	}
	return $str;
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
?>