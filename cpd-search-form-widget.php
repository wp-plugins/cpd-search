<?php
require_once(dirname(__FILE__) . "/cpd-common.php");

function cpd_search_form_widget_gather_inputs() {
	// Build the request from session-stored variables and any updated ones
	// passed in with the POST request

	if(isset($_REQUEST['start'])) {
		$_SESSION['cpd_search_our_database_start'] = $_REQUEST['start'];
	}
	if(isset($_REQUEST['limit'])) {
		$_SESSION['cpd_search_our_database_limit'] = $_REQUEST['limit'];
	}
	if(isset($_REQUEST['sectors'])) {
		$_SESSION['cpd_search_our_database_sectors_sidebar'] = $_REQUEST['sectors'];
	}
	if(isset($_REQUEST['address'])) {
		$_SESSION['cpd_search_our_database_address'] = $_REQUEST['address'];
	}
	if(isset($_REQUEST['areas'])) {
		$_SESSION['cpd_search_our_database_areas'] = $_REQUEST['areas'];
	}
	if(isset($_REQUEST['sizefrom'])) {
		$_SESSION['cpd_search_our_database_sizefrom'] = $_REQUEST['sizefrom'];
	}
	if(isset($_REQUEST['sizeto'])) {
		$_SESSION['cpd_search_our_database_sizeto'] = $_REQUEST['sizeto'];
	}
	if(isset($_REQUEST['sizeunits'])) {
		$_SESSION['cpd_search_our_database_sizeunits'] = $_REQUEST['sizeunits'];
	}
	if(isset($_REQUEST['tenure'])) {
		$_SESSION['cpd_search_our_database_tenure'] = $_REQUEST['tenure'];
	}
		
	// Ensure any missing values are defaulted
	if(($_SESSION['cpd_search_our_database_start'] * 1) < 1) {
		$_SESSION['cpd_search_our_database_start'] = 1;
	}
	if(($_SESSION['cpd_search_our_database_limit'] * 1) < 1) {
		$options = get_option('cpd-search-options');
		$_SESSION['cpd_search_our_database_limit'] = $options['cpd_search_results_per_page'];
	}

	// Handle page number requests
	if($_REQUEST['page'] > 0) {
		$pagenum = $_REQUEST['page'] * 1;
		$limit = $_SESSION['cpd_search_our_database_limit'] * 1;
		$start = (($pagenum - 1) * $limit) + 1;
		$_SESSION['cpd_search_our_database_start'] = ($start > 0 ? $start : 1);
	}
}

function cpd_search_form_widget() {
	// Gather inputs from request/session
	cpd_search_form_widget_gather_inputs();
	
	$start = $_SESSION['cpd_search_our_database_start'];
	$limit = $_SESSION['cpd_search_our_database_limit'];
	$sectors =  $_SESSION['cpd_search_our_database_sectors'];
	$address =  $_SESSION['cpd_search_our_database_address'];
	$areas =  $_SESSION['cpd_search_our_database_areas'];
	$sizefrom =  $_SESSION['cpd_search_our_database_sizefrom'];
	$sizeto =  $_SESSION['cpd_search_our_database_sizeto'];
	$sizeunits =  $_SESSION['cpd_search_our_database_sizeunits'];
	$tenure =  $_SESSION['cpd_search_our_database_tenure'];
    
	// Read in necessary form template sections from plugin options
	$form = cpd_get_template_contents("search_form_widget");
    
	
	// Add options for actionurl pulldown
	$widget_options = get_option( 'widget_search_form_widget' );
	$index  = 0;
	$keytemp  = -1;
	foreach($widget_options as $key=>$item)
	{
		$index++;
		if(($index+1) == count($widget_options))
			$keytemp = $key;
	}
	if($keytemp != -1)
	{
		$actionurl = isset($widget_options[$keytemp]["url"]) ? $widget_options[$keytemp]["url"] : "";
	}
	else
		$actionurl = "";
	$form = str_replace("[actionurl]", $actionurl, $form);
	// Add options for sizeunits pulldown
	$sizeunitoptions = cpd_sizeunit_options($sizeunits);
	$form = str_replace("[sizeunitoptions]", $sizeunitoptions, $form);
	
	// Add options for sector pulldown
	$sectoroptions = cpd_sector_options($sectors);
	$form = str_replace("[sectoroptions]", $sectoroptions, $form);

	// Add tenure options
	$tenureoptions = cpd_tenure_options($tenure);
	$form = str_replace("[tenureoptions]", $tenureoptions, $form);

	// Add options for area pulldown
	$areaoptions = cpd_area_options($areas);
	$form = str_replace("[areaoptions]", $areaoptions, $form);

	// Add per-page options
	$perpageoptions = cpd_perpage_options($limit);
	$form = str_replace("[perpageoptions]", $perpageoptions, $form);

	// Populate form defaults
	$form = str_replace("[sizefrom]", $sizefrom, $form);
	$form = str_replace("[sizeto]", $sizeto, $form);
	$form = str_replace("[sizeunits]", $sizeunits, $form);
	$form = str_replace("[sectors]", json_encode($sectors), $form);
	$form = str_replace("[tenure]", $tenure, $form);
	$form = str_replace("[areas]", json_encode($areas), $form);
	$form = str_replace("[address]", $address, $form);

	// Add theme base URL
	$form = str_replace("[themeurl]", get_template_directory_uri(), $form);

	return $form;
}

add_shortcode('cpd_search_form_widget', 'cpd_search_form_widget');

class CPD_SearchSidebar_Widget extends WP_Widget {
	function CPD_SearchSidebar_Widget() {
		$widget_ops = array(
			'classname' => 'search_form_widget',
			'description' => __('CPD search sidebar widget.', 'search_form_widget')
		);
		$control_ops = array(
			'width' => 300,
			'height' => 350,
			'id_base' => 'search_form_widget'
		);
		$this->WP_Widget('search_form_widget',
			__('CPD Search', 'search_form_widget'),
			$widget_ops,
			$control_ops
		);
	}

	function widget( $args, $instance ) {
		extract( $args );

		$title = apply_filters('widget_title', $instance['title'] );
		$url = apply_filters('widget_url', $instance['url'] );
		$link = $instance['link'];

		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;
		echo cpd_search_form_widget();
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['url'] = strip_tags( $new_instance['url'] );
		return $instance;
		
	}

	function form( $instance ) {
		$defaults = array(
			'title' => __('Search sidebar', 'search_form_widget'),
			'url' => __('','url')
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'hybrid'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
			<label for="<?php echo $this->get_field_id( 'url' ); ?>"><?php _e('Search Url:', 'hybrid'); ?></label>
			<input id="<?php echo $this->get_field_id( 'url' ); ?>" name="<?php echo $this->get_field_name( 'url' ); ?>" value="<?php echo $instance['url']; ?>" style="width:100%;" />
		</p>
<?php
	}
}

function cpd_search_form_widget_load_widgets() {
	register_widget('CPD_SearchSidebar_Widget' );
}

add_action( 'widgets_init', 'cpd_search_form_widget_load_widgets' );

?>
