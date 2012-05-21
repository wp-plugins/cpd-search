<?php

/**
 * Code and template for handling plugin settings.
 */

$cpd_templates = array(
	"common" => "Common Dialogs",
	"user_registration" => "User Registration",
	"user_verification" => "User Verification",
	"user_login" => "User Login",
	"user_password_change" => "User Password Change",
	"current_instructions" => "Current Instructions",
	"search_our_database" => "Search Our Database",
	"search_form_widget" => "Search Form Widget",
);

function cpd_search_admin_init() {
	$options = get_option('cpd-search-options');
	if(count($options) > 0) {
		return;
	}
	
	// Register settings
	register_setting( 'cpd-search-options', 'cpd_soap_base_url');
	register_setting( 'cpd-search-options', 'cpd_agentref');
	register_setting( 'cpd-search-options', 'cpd_password');
	register_setting( 'cpd-search-options', 'cpd_search_results_per_page');
	register_setting( 'cpd-search-options', 'cpd_map_widget_width');
	register_setting( 'cpd-search-options', 'cpd_map_widget_height');
	register_setting( 'cpd-search-options', 'cpd_development_mode');
	
	$options = array(
		'cpd_soap_base_url' => 'http://soap.cpd.co.uk/services/',
		'cpd_agentref' => 'youragentref',
		'cpd_password' => 'password',
		'cpd_map_widget_width' => '640',
		'cpd_map_widget_height' => '480',
		'cpd_search_results_per_page' => '10',
		'cpd_development_mode' => false,
	);

	// Default an option for each template from it's template
	global $cpd_templates;
	foreach($cpd_templates as $id => $name) {
		register_setting( 'cpd-search-options', "cpd_".$id."_html");
		$form = file_get_contents(dirname(__FILE__) . "/inc/".$id."_ui.html");
		$options["cpd_".$id."_ui"] = $form;
	}

	update_option('cpd-search-options', $options);
}

function cpd_search_admin_menu() {
	add_options_page(
		__('CPD Search', 'cpd-search'),
		__('CPD Search', 'cpd-search'),
		'manage_options',
		__FILE__,
		'cpd_search_options_page');
}

function cpd_get_template($id) {
	return get_template_directory()."/cpdtemplates/".$id."_ui.html";
}

function cpd_get_template_uri($id) {
	return get_template_directory_uri()."/cpdtemplates/".$id."_ui.html";
}

function cpd_get_template_contents($id) {
	$options = get_option('cpd-search-options');
	if($options['cpd_development_mode']) {
		return file_get_contents(dirname(__FILE__)."/inc/".$id."_ui.html");
	}
	if(!file_exists(cpd_get_template($id))) {
		return $options["cpd_".$id."_ui"];
	}

	return file_get_contents(cpd_get_template($id));
}

function cpd_search_options_page() {
	global $results_per_page_options;

	$server_options = array(
		"Production" => "https://soap.cpd.co.uk/services/",
		"Demo" => "https://demo.cpd.co.uk/soap/services/",
		"Staging" => "https://staging.cpd.co.uk/soap/services/",
		"Local development" => "https://staging.cpd.local/soap/services/",
	);
	if (!current_user_can('manage_options')) {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}

	if (isset($_POST['submit'])) {
		check_admin_referer('plugin-name-action_cpd-options');
		cpd_search_options_posted();
	}
	?>
<style type="text/css">
#cpdoptionslogo {
	float: right;
}
textarea.htmltemplate {
	font-family: Mono;
	width: 100%;
	height: 100px;
}
p.error {
	color: red;
}
</style>

<div class="wrap">

<a href="http://www.cpd.co.uk/" id="cpdoptionslogo" target="_blank">
  <img src="<?php echo cpd_plugin_dir_url(__FILE__); ?>/images/cpd_logo.png" alt="CPD"/>
</a>

<h2>CPD Settings</h2>

<form method="post" action="">
<?php
if ( function_exists('wp_nonce_field') )
	wp_nonce_field('plugin-name-action_cpd-options');
?>

<?php $options = get_option('cpd-search-options'); ?>

<h3>Introduction</h3>

<p>This plugin allows UK-based commercial property estate agents to easily embed a commercial property search facility in their WordPress site. It is provided for free, both as a courtesy and a service of CPD, in the hope that it will be useful. Feel free to use the plug-in as is, or modify it to suite your needs. No guarantees are provided, unless expressly written. If you have any comments, questions, or enhancement requests, please feel free to contact <a href="mailto:support@cpd.co.uk">CPD Support</a>.</p>

<h3>Member Credentials</h3>

<p>In order to use this plugin, you will need an account with <a href="http://www.cpd.co.uk/" target="_blank">Commercial Property Database Ltd</a>. If you are an existing CPD member agent, or are developing a site for a member agent, please provide your member agent credentials. If you are not an existing member, or are evaluating the plug-in for use by a potential member agent, please <a href="http://www.cpd.co.uk/join-now/">join here</a>.</p>

<table class="form-table">
  <tr valign="top">
    <th scope="row">Member agent reference</th>
    <td>
      <input type="text" name="cpd_agentref" value="<?php echo $options['cpd_agentref']; ?>" />
    </td>
  </tr>

  <tr valign="top">
    <th scope="row">Password</th>
    <td>
      <input type="password" name="cpd_password" value="<?php echo $options['cpd_password']; ?>" />
    </td>
  </tr>
</table>

<h3>Server Settings</h3>

<p>Please select the appropriate server for your scenario.</p>

<table class="form-table">
  <tr valign="top">
    <th scope="row">Server</th>
    <td>
      <select name="cpd_soap_base_url">
<?php
      foreach($server_options as $value => $key) {
	$selected = ($key == $options['cpd_soap_base_url'] ? "selected='selected'" : "");
?>
        <option value="<?php echo $key; ?>" <?php echo $selected; ?>><?php echo $value; ?></option>
<?php
}
?>
      </select>
    </td>
  </tr>
</table>

<ul>
  <li>Our production server is recommended for use only on live deployments.</li>
  <li>Our demo server is the best choice for web developers evaluating the plugin for integration in their WordPress site.</li>
  <li>The other options should not generally be used, as they are intended for CPD plugin developers and support engineers.</li>
</ul>

<h3>Creating Search Pages</h3>

<p>To embed a search form into a WordPress page, create a new WordPress page or post, containing one of the following short codes:</p>

<ul>
  <li><tt>[cpd_search_our_database]</tt> - Form to search our full database of UK properties, including all agents and areas.</li>
  <li><tt>[cpd_current_instructions]</tt> - List of all the properties listed by the authenticating agent, grouped by sector.</li>
<!--  <li><tt>[cpd_map_search]</tt> - Geographically oriented search, based on Google Maps (unfinished/experimental!)</li> -->
</ul>

<p>To customise the HTML and CSS used to present these, it is easiest to copy files from the 'inc' folder that is supplied with this plugin into a 'cpdtemplates' subfolder of the theme you are using. This plugin will use these files from the theme if they are found, otherwise it will use the copies stored here in the plugin configuration.</p>

<h3>Handling User Registration</h3>

<p>Additionally, you should also create landing pages to fulfil the 'verify user' and 'change password' links that may be sent by CPD at the user's request, once the system is fully configured. These are simply pages that contain one of the following shortcodes each.</p>

<ul>
  <li><tt>[cpd_verify_user]</tt> - Performs a simple token check and displays success or failure. Example URI: '/verify-user?token=xyz...'.</li>
  <li><tt>[cpd_password_change]</tt> - Allows a confirmed user to change their password. Example URI: '/password-change'.</li>
<!--  <li><tt>[cpd_map_search]</tt> - (unfinished/experimental!)</li> -->
</ul>

<p>When preparing for deployment, please <a href="mailto:support@cpd.co.uk">send us</a> the production URLs for these pages, so we can update the e-mail templates we send.</p>

<h3>UI Templates</h3>

<p>The UI for the search forms, results and all other dialogs  
<?php
  global $cpd_templates;
  foreach($cpd_templates as $id => $name) {
    $template_file = cpd_get_template($id);
    $template_uri = cpd_get_template_uri($id);
    ?>
<h4><?php echo $name; ?></h4>
    <?php
    if(file_exists($template_file)) {
      ?>
<p>Found template in theme (<tt><a href="<?php echo $template_uri; ?>"><?php echo $template_uri; ?></a></tt>).</p>
      <?php
    }
    else {
      ?>
            <?php  the_editor($options["cpd_{$id}_ui"], "cpd_{$id}_ui");
 ?>
      <?php
    }
  }
?>

<!--

<h3>Map Search Settings</h3>

<p>To add a map search into a page, add the '[cpd_search_map_search]' tag.</p>

<table class="form-table">
  <tr valign="top">
    <th scope="row">Widget width</th>
    <td>
      <input type="text" name="cpd_map_widget_width" value="<?php echo $options['cpd_map_widget_width']; ?>" />
    </td>
  </tr>

  <tr valign="top">
    <th scope="row">Widget height</th>
    <td>
      <input type="text" name="cpd_map_widget_height" value="<?php echo $options['cpd_map_widget_height']; ?>" />
    </td>
  </tr>

</table>
-->

<h3>General Settings</h3>

<p>These settings are defaults that will be applied to all the available search forms.</p>

<table class="form-table">
  <tr valign="top">
    <th scope="row">Default results per page</th>
    <td>
      <select name="cpd_search_results_per_page">
<?php
      foreach($results_per_page_options as $value) {
          $selected = ($value == $options['cpd_search_results_per_page'] ? "selected='selected'" : "");
?>
        <option value="<?php echo $value; ?>" <?php echo $selected; ?>><?php echo $value; ?></option>
<?php
}
?>
      </select>
    </td>
  </tr>
  <tr valign="top">
    <th scope="row">Development mode (*)</th>
    <td>
      <input type="checkbox" name="cpd_development_mode" value="Y" <?php if($options['cpd_development_mode']) { ?>checked="checked"<?php } ?> />
    </td>
  </tr>
</table>

<p>(*) Development mode may display additional technical information at runtime, both in the UI and in the server logs.</p>

<?php
	if (isset($_POST['submit'])) {
		cpd_search_server_check();
	}
?>

<p class="submit">
  <input type="submit" name="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
</p>

</form>
</div>
	<?php
}

function cpd_search_options_posted() {
	// Update options as registered
	$options = get_option('cpd-search-options');
	$options['cpd_agentref'] = $_REQUEST['cpd_agentref'];
	$options['cpd_password'] = $_REQUEST['cpd_password'];
	$options['cpd_soap_base_url'] = $_REQUEST['cpd_soap_base_url'];
	$options['cpd_search_results_per_page'] = $_REQUEST['cpd_search_results_per_page'] * 1;
	$options['cpd_map_widget_width'] = $_REQUEST['cpd_map_widget_width'] * 1;
	$options['cpd_map_widget_height'] = $_REQUEST['cpd_map_widget_height'] * 1;
	$options['cpd_development_mode'] = $_REQUEST['cpd_development_mode'] == "Y";
	
	global $cpd_templates;
	foreach($cpd_templates as $id => $name) {
		$options["cpd_".$id."_ui"] = stripslashes($_REQUEST["cpd_".$id."_ui"]);
		if(trim($options["cpd_".$id."_ui"]) == "") {
			$form = file_get_contents(dirname(__FILE__) . "/inc/".$id."_ui.html");
			$options["cpd_".$id."_ui"] = $form;
		}
	}

	update_option('cpd-search-options', $options);

	?>
	<div id="message" class="updated fade">
	<p><?php _e('Options saved.', 'cpd-search'); ?></p>
	</div>
	<?php
}

function cpd_search_server_check() {
	// Check details, and report success/failure of connection
	$options = get_option('cpd-search-options');
	$soapopts = array('trace' => 1, 'exceptions' => 1);
	$client = new SoapClient($options['cpd_soap_base_url']."CPDPropertyService?wsdl", $soapopts);
	$headers = wss_security_headers($options['cpd_agentref'], $options['cpd_password']);
	$client->__setSOAPHeaders($headers);

	// Confirm the connection works by requesting schema version
	try {
		$versionResponse = $client->GetDBSchemaVersion();
		?>
		<div class="updated">
		<p>Server connection successful: Database version is <?php echo $versionResponse->Version ?>
		</div>
		<?php
	}
	catch(SoapFault $e) {
		?>
		<div class="error">
		<p>Server connection failed: <?php echo $e->getMessage(); ?></p>
		</div>
		<?php
	}
}

// Hooks to allow CPD search configuration settings and options to be set
add_action( 'admin_init', 'cpd_search_admin_init');
add_action( 'admin_menu', 'cpd_search_admin_menu');

?>
