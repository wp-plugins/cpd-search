<?php
function cpd_save_searches_sidebar_widget_init(){
	
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
		echo $before_widget;
		if ( $title )
			echo $before_title;
			echo "<div id='saved_searches'>
				  <div class='saved_searches-content'>
					<h2><span>". $title ."</span></h2>
					<div class='saved'>
						<span class='properties'><span id='number_item_saved'></span> Searches saved</span>
					</div>
					<div class='block'>
					<div class=''>
                    <ul id='saved-searches'>";
				echo cpd_saved_searches_widget_load_items();
				echo"</ul>
					</div>
					</div>
				  <div class='nav'>
					<a href='#' name='open' id='open' OnClick='cpd_open_saved_searches();'>Open</a>
					<a href='#' name='' id='' OnClick='cpd_last_result();'>Last Results</a>
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
	
	if(!isset($_SESSION['cpd_saved_searches_widget']) || count($_SESSION['cpd_saved_searches_widget']) == 0 )
	{
		
	}
	else 
	{
		$list_li = $_SESSION['cpd_saved_searches_widget'];	
		if(count($list_li) != 0)
		{
			foreach($list_li as $data)
			{
				$str .= "<li class='content-block' id='".$data['id']."'><div><p><input type='checkbox' name='clipboard_select_all[]' id='".$data['id']."' /><span>Search Name</span><span class='date-last'>Date Last Search</span><img src='wp-content/plugins/cpd-search/images/X.png' id='".$data['id']."' onClick='cpd_remove_saved_searches(this);' width='15' height='13' /></p><p><span>Postcode:</span><span class='postcode'>".$data['postcode']."</span><span class='location date-last'>Location:</span><span class='address'> ".$data['address']."</span></p><p><span>Tenure:</span><span class='tenure_text'> ".$data['tenure_text']."</span><span class='tenure' style='display:none'>".$data['tenure']."</span><span class='sectors' style='display:none'>".implode(',',$data['sectors'])."</span><span class='areas' style='display:none'>".implode(',',$data['areas'])."</span><span>Size:</span><span>".$data['sizefrom'].'-'. $data['sizeto'].$data['size_text']."</span><span class='sizefrom' style='display:none'>".$data['sizefrom']."</span><span class='sizeto' style='display:none'>".$data['sizeto']."</span><span class='size' style='display:none'>".$data['size']."</span></p></div></li>";
			}
		}
	}
	return $str;
}


function cpd_saved_searches_widget_ajax() {
	
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
	
	$row=array();
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
	$results[]=$row;		
	
	$cpd_saved_searches_widget = $_SESSION['cpd_saved_searches_widget'];
	$cpd_saved_searches_widget[] = $results[0];
	
	$_SESSION['cpd_saved_searches_widget'] = $cpd_saved_searches_widget;
	
	$response = array(
		'success' => true,
		'id'=> $id,
		'address'=> $address,
		'postcode'=> $postcode,
		'tenure'=> $tenure,
		'tenure_text'=> $tenure_text,
		'sizeto'=> $sizeto,
		'sizefrom'=> $sizefrom,
		'size'=> $size,
		'size_text'=> $size_text,
		'sectors'=> $sectors,
		'areas'=> $areas,
	);
	
	header( "Content-Type: application/json" );
	echo json_encode($response);
	exit;
	
}

add_action('wp_ajax_cpd_saved_searches_widget_ajax', 'cpd_saved_searches_widget_ajax');
add_action('wp_ajax_nopriv_cpd_saved_searches_widget_ajax', 'cpd_saved_searches_widget_ajax');

//remmove ajax
function cpd_remove_saved_searches_widget_ajax(){
	
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

add_action('wp_ajax_cpd_remove_saved_searches_widget_ajax', 'cpd_remove_saved_searches_widget_ajax');
add_action('wp_ajax_nopriv_cpd_remove_saved_searches_widget_ajax', 'cpd_remove_saved_searches_widget_ajax');

?>