<?php

require_once(dirname(__FILE__) . "/cpd-area-options.php");

$soapopts = array('trace' => 1, 'exceptions' => 1, 'features' => SOAP_SINGLE_ELEMENT_ARRAYS);

$results_per_page_options = array(
	"5", "10", "20", "25", "50", "100"
);

function cpd_sector_options($sectors, $all = false) { 
	if(!is_array($sectors)) {
		$sectors = array();
	}

	// Build sectors options
	// [TODO] Should gather this from a GetSectors SOAP API call
	$sector_options = array(
		"O" => "Offices",
		"S" => "Shops",
		"R" => "Restaurant/Takeaway",
		"E" => "Education",
		"H" => "Medical",
	);
	if($all) {
		$sector_options = array(
			"O" => "Offices",
			"SO" => "Serviced Office",
			"S" => "Shops",
			"I" => "Industrial",
			"BU" => "Business Units",
			"R" => "Restaurant/Takeaway",
			"PU" => "Pubs",
			"L" => "Leisure",
			"W" => "Retail Warehousing",
			"X" => "Showrooms",
			"M" => "Motor Related",
			"C" => "Mixed/Commercial",
			"H" => "Medical",
			"G" => "Studio/Gallery",
			"AC" => "Arts/Crafts",
			"U" => "Live/Work Unit",
			"E" => "Education",
			"A" => "Storage",
			"B" => "Land/Site",
			"Z" => "Hall/Misc",
			"GC" => "Garden Centers",
		);
	}
	foreach($sector_options as $key => $value) {
		$selected = (in_array($key, $sectors) ? "selected=\"selected\"" : "");
		$sectoroptions .= "<option value=\"".$key."\" ".$selected.">".$value."</option>\n";
	}
	return $sectoroptions;
}

function cpd_area_options($areas) {
	if(!is_array($areas)) {
		$areas = array();
	}

	// Add options for perpage pulldown
	global $cpd_area_options;
	$areaoptions = "";
	foreach($cpd_area_options as $key => $value) {
		$selected = (in_array($key, $areas) ? "selected=\"selected\"" : "");
		$areaoptions .= "<option value=\"".$key."\" ".$selected.">".$value."</option>\n";
	}
	return $areaoptions;
}

function cpd_sizeunit_options($sizeunits) {
	// Add options for sizeunits pulldown
	$sizeunit_options = array(
		"1" => "sq m",
		"2" => "sq ft",
		"3" => "acres",
		"4" => "hectares",
	);
	$sizeunitoptions = "";
	foreach($sizeunit_options as $key => $value) {
		$selected = ($key == $sizeunits ? "selected=\"selected\"" : "");
		$sizeunitoptions .= "<option value=\"".$key."\" ".$selected.">".$value."</option>\n";
	}
	return $sizeunitoptions;
}

function cpd_tenure_options($tenure) {
	// Add options for tenure pulldown
	$tenure_options = array(
		"" => "Leasehold and Freehold",
		"F" => "Freehold",
		"L" => "Leasehold",
	);
	$tenureoptions = "";
	foreach($tenure_options as $key => $value) {
		$selected = ($key == $tenure ? "selected=\"selected\"" : "");
		$tenureoptions .= "<option value=\"".$key."\" ".$selected.">".$value."</option>\n";
	}
	return $tenureoptions;
}

function cpd_perpage_options($limit) {
	// Add options for perpage pulldown
	global $results_per_page_options;
	$perpageoptions = "";
	foreach($results_per_page_options as $value) {
		$selected = ($value == $limit ? "selected='selected'" : "");
		$perpageoptions .= "<option value=\"".$value."\" ".$selected.">".$value."</option>\n";
	}
	return $perpageoptions;
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

<h3>Preparation</h3>

<p>During the course of the visitor's session, this plugin may send various e-mails during the course of the visit. These e-mails are sent using the standard WordPress mail delivery mechanism, which you may need to configure. They are generated using a simple template mechanism, described further down, which allows you to customise the colour, style, logo and wording of the e-mails for your organisation.</p>

<p>The e-mails sent will often contain links back to your site which the visitor must click. These links will arrive at landing pages that you will need to set up in advance. These landing pages are:</p>
<dl>
  <dt><dfn>User confirmation</dfn></dt>
  <dd>A page (e.g. '/confirm-user?token=xyz'), containing the '[cpd_verify_user]' shortcode.</dd>
  <dt><dfn>Change password</dfn></dt>
  <dd>A page (e.g. '/change-password?token=xyz'), containing the '[cpd_password_reset]' shortcode.</dd>
</dl>

<p>In order to provide your visitors with full details of your listings, or have you contact them if they click 'register interest' on any results, they will need to register their contact details.</p>

<h3>Creating Search Pages</h3>

<p>To embed a search form into a WordPress page, create a new WordPress page or post, containing one of the following short codes:</p>

<ul>
  <li><tt>[cpd_search_our_database]</tt> - Form to search our full database of UK properties, including all agents and areas.</li>
  <li><tt>[cpd_current_instructions]</tt> - List of all the properties listed by the authenticating agent, grouped by sector.</li>
<!--  <li><tt>[cpd_map_search]</tt> - Geographically oriented search, based on Google Maps (unfinished/experimental!)</li> -->
</ul>

<h3>Template mechanism</h3>

<p>By default, the plugin will use the templates supplied in the 'inc' folder of this plugin. These are designed to be simple, clean and easy to extend by adding custom CSS rules to the site theme.</p>

<p>Should these templates need further customisation at the HTML level, they can be copied from the 'inc' folder into a 'cpdtemplates' subfolder in the root of the site's current theme, and customised there.</p>

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
    <th scope="row">Service context (**)</th>
    <td>
      <input name="cpd_service_context" value="<?php echo $options['cpd_service_context']; ?>"" />
    </td>
  </tr>
  <tr valign="top">
    <th scope="row">Development mode (**)</th>
    <td>
      <input type="checkbox" name="cpd_development_mode" value="Y" <?php if($options['cpd_development_mode']) { ?>checked="checked"<?php } ?> />
    </td>
  </tr>
</table>

<p>(*) The service context is an optional short, alphanumeric string with no spaces, that will be used to identify visitors that register on this site, as opposed to other sites belonging to the same agent. The default value used, if not configured here, is 'WordpressPlugin'.</p>
<p>(**) Development mode currently forces the use of the default 'inc' templates, and may display additional technical information at runtime, both in the UI and in the server logs.</p>

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
	$options['cpd_service_context'] = $_REQUEST['cpd_service_context'];
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
	global $soapopts;

	// Check details, and report success/failure of connection
	$options = get_option('cpd-search-options');
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
