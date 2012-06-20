<?php
function cpd_save_searches_sidebar_widget_init(){
	wp_enqueue_script('jquery-ui-dialog', cpd_plugin_dir_url(__FILE__) . "js/jquery.ui.dialog.js", array('jquery-ui-widget'), "", true);
	wp_enqueue_script('cpd-save-searches-controller', cpd_plugin_dir_url(__FILE__) . "js/cpd-saved-searches-sidebar-widget-controller.js");
}

Class CPD_Save_Searches_Widget extends WP_Widget {
	
	function CPD_Save_Searches_Widget(){
		
		cpd_save_searches_sidebar_widget_init();
		
		$widget_ops = array( 
			'classname' => 'save_searches_widget', 
			'description' => __('Save Searches Widget', 'save_searches_widget') 
		);
		
		$control_ops = array( 
			'width' => 300, 'height' => 350, 
			'id_base' => 'save_searches_widget' 
		);		
		
		$this->WP_Widget( 
			'save_searches_widget', __('Save Searches Widget', 'save_searches_widget'), $widget_ops, $control_ops 
		);
	}
	
	function widget( $args, $instance ) {		
		extract( $args );
		$home = home_url();
		$path_dir_plugin = $home.cpd_plugin_dir_url('cpd-search');
		$title = apply_filters('widget_title', $instance['title'] );
		$save_a_search = cpd_get_template_contents("saved_search_popup");
		echo $before_widget;
		if ( $title )
			echo $before_title;
			echo cpd_saved_searches_widget_load_items();
			echo $save_a_search;
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
		'title' => __('Save Searches Widget', 'Save Searches Widget')
		);
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'CPD Save Searches Widget'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>
	<?php
	}
}

function cpd_save_searches_widget_load_widgets() {
	register_widget('CPD_Save_Searches_Widget' );
}

add_action( 'widgets_init', 'cpd_save_searches_widget_load_widgets' );

function cpd_saved_searches_widget_load_items()
{	
	$str = '';
	$home = home_url();
	$path_dir_plugin = $home.cpd_plugin_dir_url('cpd-search');	
	$conten_template  = cpd_get_template_contents("saved_searches");	
	$form_save_search  = substr($conten_template,strpos($conten_template,"<!--template item-->"));			
	
	if(!isset($_SESSION['cpd_saved_searches_widget']) || count($_SESSION['cpd_saved_searches_widget']) == 0 )
	{
		$item_template  = substr($conten_template,0,strpos($conten_template,"<!--template item-->"));	
		$item_template  = str_replace("[display_none]","display:none",$item_template);			
		$form_save_search  = str_replace("[contentbox]",$item_template,$form_save_search);
	}
	else 
	{
		$list_li = $_SESSION['cpd_saved_searches_widget'];
				
		if(count($list_li) != 0)
		{	
			$list_item_template = '';
			$item_template  = substr($conten_template,0,strpos($conten_template,"<!--template item-->"));	
			$item_template  = str_replace("[display_none]","display:none",$item_template);
			$list_item_template .= $item_template;
			foreach($list_li as $data)
			{	
				$item_template  = substr($conten_template,0,strpos($conten_template,"<!--template item-->"));				
				$item_template  = str_replace("[display_none]","",$item_template);
				$item_template  = str_replace("[search_name]",$data['search_name'],$item_template);
				$item_template  = str_replace("[date_last_search]",$data['date_last_search'],$item_template);
				$item_template  = str_replace("[id]",$data['id'],$item_template);
				$item_template  = str_replace("[postcode]",$data['postcode'],$item_template);
				$item_template  = str_replace("[location]",$data['address'],$item_template);
				$item_template  = str_replace("[tenure_text]",$data['tenure_text'],$item_template);
				$item_template  = str_replace("[tenure]",$data['tenure'],$item_template);
				$item_template  = str_replace("[sizefrom]",$data['sizefrom'],$item_template);
				$item_template  = str_replace("[sizeto]",$data['sizeto'],$item_template);
				$item_template  = str_replace("[size_units]",$data['size'],$item_template);
				if ($data['sectors']!= null){
					$item_template  = str_replace("[sectors]",implode(',',$data['sectors']),$item_template);
				}else {
					$item_template  = str_replace("[sectors]","",$item_template);
				}
				if ($data['areas']!= null){
					$item_template  = str_replace("[areas]",implode(',',$data['areas']),$item_template);
				}else{
					$item_template  = str_replace("[areas]","",$item_template);
				}
				$item_template  = str_replace("[size]",$data['sizefrom'].'-'. $data['sizeto'].$data['size_text'],$item_template);
				$list_item_template .= $item_template;
			}
			$form_save_search  = str_replace("[contentbox]",$list_item_template,$form_save_search);
		}
	}
	return $form_save_search;
}


function cpd_saved_searches_widget_ajax() {
	
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

	
	if(!isset($_SESSION['cpd_saved_searches_widget']))
	{
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
	
	$response = array(
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
		'sectors'=> implode(",",$sectors),
		'areas'=> implode(",",$areas),
		
	);
	
	header( "Content-Type: application/json" );
	echo json_encode($response);
	exit;
	
}

add_action('wp_ajax_cpd_saved_searches_widget_ajax', 'cpd_saved_searches_widget_ajax');
add_action('wp_ajax_nopriv_cpd_saved_searches_widget_ajax', 'cpd_saved_searches_widget_ajax');

//remmove ajax
function cpd_saved_searches_widget_remove_item_widget_ajax(){
	
	$id = $_REQUEST['id'];	
	$cpd_saved_searches_widget_temp = array();	
	
	if(isset($_SESSION['cpd_saved_searches_widget']))
	{
		$cpd_saved_searches_widget = $_SESSION['cpd_saved_searches_widget'];
		foreach($cpd_saved_searches_widget as $item)
		{
			if($item['id']!=$id ){
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

add_action('wp_ajax_cpd_saved_searches_widget_remove_item_widget_ajax', 'cpd_saved_searches_widget_remove_item_widget_ajax');
add_action('wp_ajax_nopriv_cpd_saved_searches_widget_remove_item_widget_ajax', 'cpd_saved_searches_widget_remove_item_widget_ajax');

?>