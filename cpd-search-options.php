<?php

require_once(dirname(__FILE__) . "/cpd-area-options.php");

$cpd_server_options = array(
	"Production" => "https://apps.cpd.co.uk/restapi/v1",
	"Staging" => "https://staging.cpd.co.uk/restapi/v1",
	"Local development" => "https://staging.cpd.local/restapi/v1",
);

$results_per_page_options = array(
	"5", "10", "20", "25", "50", "100"
);

$cpd_tenure_options = array(
	"" => "Leasehold and Freehold",
	"F" => "Freehold",
	"L" => "Leasehold",
);

$cpd_sizeunit_options = array(
	"1" => "sq m",
	"2" => "sq ft",
	"3" => "acres",
	"4" => "hectares",
);

function cpd_sector_options($sectors, $sectors_chosen) { 
	if(!is_array($sectors)) {
		$sectors = array();
	}
	if(!is_array($sectors_chosen)) {
		$sectors_chosen = array();
	}

	// Build sectors options
	$sector_options = $_SESSION['cpd_agent_sectors'];
	$sectoroptions = "";
	foreach($sectors as $key) {
		$value = $sector_options[$key];
		$selected = (in_array($key, $sectors_chosen) ? "selected=\"selected\"" : "");
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
	global $cpd_sizeunit_options;
	$sizeunitoptions = "";
	foreach($cpd_sizeunit_options as $key => $value) {
		$selected = ($key == $sizeunits ? "selected=\"selected\"" : "");
		$sizeunitoptions .= "<option value=\"".$key."\" ".$selected.">".$value."</option>\n";
	}
	return $sizeunitoptions;
}

function cpd_tenure_options($tenure) {
	// Add options for tenure pulldown
	global $cpd_tenure_options;
	$tenureoptions = "";
	foreach($cpd_tenure_options as $key => $value) {
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
	global $cpd_server_options;

	if (!current_user_can('manage_options')) {
		wp_die(__('You do not have sufficient permissions to access this page.') );
	}

	if (isset($_POST['submit'])) {
		if (isset($_POST['serveroptions'])) {
			check_admin_referer('cpd-search-options-serveroptions');
			cpd_search_server_check();
		}
		elseif (isset($_POST['pluginconfig'])) {
			check_admin_referer('cpd-search-options-pluginconfig');
			cpd_search_options_posted();
		}
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
  <img src="<?php echo plugins_url("cpd-search")."/images/cpd_logo.png"; ?>" alt="CPD"/>
</a>

<h2>CPD Settings</h2>

<h3>Introduction</h3>

<p>This plugin allows UK-based commercial property estate agents to easily embed a commercial property search facility in their WordPress site. It is provided for free, both as a courtesy and a service of CPD, in the hope that it will be useful. Feel free to use the plug-in as is, or modify it to suite your needs. No guarantees are provided, unless expressly written. If you have any comments, questions, or enhancement requests, please feel free to contact <a href="mailto:support@cpd.co.uk">CPD Support</a>.</p>

<h3>Application Credentials</h3>

<p>In order to use this plugin, you will need an application token, which must be provided by  <a href="http://www.cpd.co.uk/" target="_blank">Commercial Property Database Ltd</a>. If you are an existing CPD member agent, or are developing a site for a member agent, please contact CPD to obtain your application token. If you are not an existing member, or are evaluating the plug-in for use by a potential member agent, please <a href="http://www.cpd.co.uk/join-now/">join here</a>.</p>

<form method="post" action="">
<?php
if(function_exists('wp_nonce_field') )
	wp_nonce_field('cpd-search-options-serveroptions');
?>
<input type="hidden" name="serveroptions" value="true"/>

<table class="form-table">
  <tr valign="top">
    <th scope="row">Server</th>
    <td>
      <select name="cpd_rest_url">
<?php
      $cpd_rest_url = get_option('cpd_rest_url');
      foreach($cpd_server_options as $value => $key) {
        $selected = ($key == $cpd_rest_url ? "selected='selected'" : "");
?>
        <option value="<?php echo $key; ?>" <?php echo $selected; ?>><?php echo $value; ?></option>
<?php
}
?>
      </select>
    </td>
    <td rowspan="3">
      <?php
        if(get_option('cpd_agentref')) {
      ?>
      <table>
        <tr>
          <td>Agent Name:</td>
          <td><b><?php echo get_option('cpd_agentname'); ?></b></td>
        </tr>
        <tr>
          <td>Token Created:</td>
          <td><?php echo get_option('cpd_token_created'); ?></td>
        </tr>
        <tr>
          <td>Token Expires:</td>
          <td><?php echo get_option('cpd_token_expires'); ?></td>
        </tr>
      </table>
      <?php
        }
      ?>
    </td>
  </tr>
  <tr valign="top">
    <th scope="row">Application token</th>
    <td>
      <input type="text" name="cpd_application_token" value="<?php echo get_option('cpd_application_token'); ?>" />
    </td>
  </tr>
  <tr valign="top">
    <td colspan="2">
      <p class="submit">
        <input type="submit" name="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
      </p>
    </td>
  </tr>
</table>

</form>

<h3>Preperation</h3>

<p>During the course of the visitor's session, this plugin may send various e-mails during the course of the visit. These e-mails are sent using the standard WordPress mail delivery mechanism, which you may need to configure. <!-- They are generated using a simple template mechanism, described further down, which allows you to customise the colour, style, logo and wording of the e-mails for your organisation. --></p>

<p>The e-mails sent will often contain links back to your site which the visitor must click. These links will arrive at landing pages that you will need to set up in advance. These landing pages are:</p>
<p><h3>User confirmation</h3>
<p><strong>[cpd_verify_user]</strong> - A page (e.g. '/confirm-user?token=xyz'), containing the '[cpd_verify_user]' shortcode.</p>
 <p><h3>Change password</h3></p>
<p><strong>[cpd_password_change]</strong> - A page (e.g. '/change-password?token=xyz'), containing the '[cpd_password_change]' shortcode.</p>

<p>In order to provide your visitors with full details of your listings, or have you contact them if they click 'register interest' on any results, they will need to register their contact details.</p>

<h3>Creating Search Pages</h3>

<p>To embed a search form into a WordPress page, create a new WordPress page or post, containing one of the following short codes:</p>

<p><strong>[cpd_search_our_database]</strong> - Form to search our full database of UK properties, including all agents and areas.</p>
<p><strong>[cpd_current_instructions]</strong> - List of all the properties listed by the authenticating agent, grouped by sector.</p>
<!--  <li><tt>[cpd_map_search]</tt> - Geographically oriented search, based on Google Maps (unfinished/experimental!)</li> -->


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
      <input type="text" name="cpd_map_widget_width" value="<?php echo get_option('cpd_map_widget_width'); ?>" />
    </td>
  </tr>

  <tr valign="top">
    <th scope="row">Widget height</th>
    <td>
      <input type="text" name="cpd_map_widget_height" value="<?php echo get_option('cpd_map_widget_height'); ?>" />
    </td>
  </tr>

</table>
-->

<h3>General Settings</h3>

<form method="post" action="">
<input type="hidden" name="pluginconfig" value="true"/>
<?php
if(function_exists('wp_nonce_field') )
	wp_nonce_field('cpd-search-options-pluginconfig');
?>

<table class="form-table">
  <tr valign="top">
    <th scope="row">Search sectors</th>
    <td>
      <table>
        <tr>
          <th>Sector</th>
          <th>Current Instructions</th>
          <th>Search Our Database</th>
        </tr>
<?php
        $agent_sectors = isset($_SESSION['cpd_agent_sectors']) ? $_SESSION['cpd_agent_sectors'] : array();
        $ci_sectors = explode(",", get_option('cpd_ci_sector_ids'));
        $sod_sectors = explode(",", get_option('cpd_sod_sector_ids'));
        foreach($agent_sectors as $sectorcode => $sectordescription) {
            $ci_selected = "";
            foreach($ci_sectors as $searchcode) {
                if($searchcode == $sectorcode) {
                    $ci_selected = ' checked="checked"';
                    break;
                }
            }
            $sod_selected = "";
            foreach($sod_sectors as $searchcode) {
                if($searchcode == $sectorcode) {
                    $sod_selected = ' checked="checked"';
                    break;
                }
            }
?>
        <tr>
          <td>
            <div>
              <?php echo $sectordescription ?>
            </div>
          </td>
          <td>
            <div>
              <input type="checkbox" name="cpd_ci_sector[<?php echo $sectorcode ?>]" value="<?php echo $sectordescription ?>" <?php echo $ci_selected; ?>/>
            </div>
          </td>
          <td>
            <div>
              <input type="checkbox" name="cpd_sod_sector[<?php echo $sectorcode ?>]" value="<?php echo $sectordescription ?>" <?php echo $sod_selected; ?>/>
            </div>
          </td>
        </tr>
<?php
        }
?>
      </table>
    </td>
  </tr>
  <tr valign="top">
    <th scope="row">Default results per page</th>
    <td>
      <select name="cpd_search_results_per_page">
<?php
      foreach($results_per_page_options as $value) {
          $selected = ($value == get_option('cpd_search_results_per_page') ? "selected='selected'" : "");
?>
        <option value="<?php echo $value; ?>" <?php echo $selected; ?>><?php echo $value; ?></option>
<?php
}
?>
      </select>
    </td>
  </tr>
  <tr valign="top">
    <th scope="row">Allow unregistered access to PDFs?</th>
    <td>
      <input type="checkbox" name="cpd_unregistered_pdfs" value="Y" <?php if(get_option('cpd_unregistered_pdfs')) { ?>checked="checked"<?php } ?> />
    </td>
  </tr>
  <tr valign="top">
    <th scope="row">Terms &amp; Conditions URL</th>
    <td>
      <input name="cpd_terms_url" value="<?php echo get_option('cpd_terms_url'); ?>" />
    </td>
  </tr>
  <tr valign="top">
    <th scope="row">Service context (*)</th>
    <td>
      <input name="cpd_service_context" value="<?php echo get_option('cpd_service_context'); ?>" />
    </td>
  </tr>
  <tr valign="top">
    <th scope="row">QR Code Service context (*)</th>
    <td>
      <input name="cpd_qrcode_service_context" value="<?php echo get_option('cpd_qrcode_service_context'); ?>" />
    </td>
  </tr>
  <tr valign="top">
    <th scope="row">Development mode (**)</th>
    <td>
      <input type="checkbox" name="cpd_development_mode" value="Y" <?php if(get_option('cpd_development_mode')) { ?>checked="checked"<?php } ?> />
    </td>
  </tr>
</table>

<p>(*) The service context is an optional short, alphanumeric string with no spaces, that will be used to identify visitors that register on this site, as opposed to other sites belonging to the same agent. The default value used, if not configured here, is 'WordpressPlugin'.</p>
<p>(**) Development mode currently forces the use of the default 'inc' templates, and may display additional technical information at runtime, both in the UI and in the server logs.</p>

<p class="submit">
  <input type="submit" name="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
</p>

</form>
</div>
	<?php
}

function cpd_search_options_posted() {
	// Update options as registered
	update_option('cpd_search_results_per_page', $_REQUEST['cpd_search_results_per_page'] * 1);
	//update_option('cpd_map_widget_width', $_REQUEST['cpd_map_widget_width'] * 1);
	//update_option('cpd_map_widget_height', $_REQUEST['cpd_map_widget_height'] * 1);
	update_option('cpd_service_context', $_REQUEST['cpd_service_context']);
	update_option('cpd_qrcode_service_context', $_REQUEST['cpd_qrcode_service_context']);
	update_option('cpd_development_mode', isset($_REQUEST['cpd_development_mode']) && $_REQUEST['cpd_development_mode'] == "Y");
	update_option('cpd_unregistered_pdfs', isset($_REQUEST['cpd_unregistered_pdfs']) && $_REQUEST['cpd_unregistered_pdfs'] == "Y");
	update_option('cpd_terms_url', $_REQUEST['cpd_terms_url']);

	$ci_sectors = array();
	$sector_options = $_SESSION['cpd_agent_sectors'];
	foreach($sector_options as $key => $value) {
		if(isset($_REQUEST['cpd_ci_sector'][$key])) {
			$ci_sectors[] = $key;
		}
	}
	update_option('cpd_ci_sector_ids',implode(",", $ci_sectors));

	$sod_sectors = array();
	$sector_options = $_SESSION['cpd_agent_sectors'];
	foreach($sector_options as $key => $value) {
		if(isset($_REQUEST['cpd_sod_sector'][$key])) {
			$sod_sectors[] = $key;
		}
	}
	update_option('cpd_sod_sector_ids', implode(",", $sod_sectors));

	?>
	<div id="message" class="updated fade">
	<p><?php _e('Options saved.', 'cpd-search'); ?></p>
	</div>
	<?php
}

function cpd_search_server_check() {
	// Set the given server URL and token details
	$cpd_rest_url = $_REQUEST['cpd_rest_url'];
	$cpd_application_token = trim($_REQUEST['cpd_application_token']);
	
	// Confirm the connection works by requesting the status of the agent token
	$params = array(
		'refresh' => true
	);
	$url = sprintf("%s/agents/statuscheck/?%s", $cpd_rest_url, http_build_query($params));
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array(
		'X-CPD-Token: '.$cpd_application_token
	));
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	$rawdata = curl_exec($curl);
	$info = curl_getinfo($curl);
	if(curl_errno($curl)) {
		?>
		<div class="error">
		<p>CURL error: <?php echo curl_error($curl); ?></p>
		</div>
		<?php
		return;
	}
	curl_close($curl);
	if($info['http_code'] != 200) {
		?>
		<div class="error">
		<p>Server connection failed: <?php echo $info['http_code']; ?></p>
		</div>
		<?php
		return;
	}
	$response = json_decode($rawdata);
	
	$token = $response->messages[0]->detail;
	update_option('cpd_token_context', $token->token->context);
	update_option('cpd_token_created', $token->token->created_date);
	update_option('cpd_token_lastused', isset($token->token->last_used) ? $token->token->last_used : "");
	update_option('cpd_token_expires', $token->token->expiry_date);

	$agent = $response->messages[1]->detail;
	update_option('cpd_agent_id', $agent->uid);
	update_option('cpd_agentref', $agent->ref);
	update_option('cpd_agentname', $agent->owner);
	
	update_option('cpd_rest_url', $cpd_rest_url);
	update_option('cpd_application_token', $cpd_application_token);
	
	?>
	<div class="updated">
	<p>Server connection successful.</p>
	</div>
	<?php
}

function cpd_sector_id_for_code($sectorcode) {
	$sector_mapping = array(
		'O' => 1,
		'SO' => 2,
		'S' => 3,
		'I' => 4,
		'BU' => 5,
		'R' => 6,
		'PU' => 7,
		'L' => 8,
		'W' => 9,
		'X' => 10,
		'M' => 11,
		'C' => 12,
		'H' => 13,
		'G' => 14,
		'AC' => 15,
		'U' => 16,
		'E' => 17,
		'A' => 18,
		'B' => 19,
		'Z' => 20,
		'GC' => 21,
	);
	if(isset($sector_mapping[$sectorcode]))
		return $sector_mapping[$sectorcode];
	return 0;
}

function cpd_search_admin_init() {
	$options = get_option('cpd-search-options');
	if($options && count($options) > 0) {
		update_option('cpd_application_token', $options['cpd_application_token']);
		update_option('cpd_service_context', $options['cpd_service_context']);
		update_option('cpd_rest_url', 'https://apps.cpd.co.uk/restapi/v1');
		update_option('cpd_agent_id', $options['cpd_agent_id']);
		update_option('cpd_agentref', $options['cpd_agentref']);
		update_option('cpd_agentname', $options['cpd_agentname']);
		update_option('cpd_unregistered_pdfs', $options['cpd_unregistered_pdfs']);
		update_option('cpd_search_results_per_page', $options['cpd_search_results_per_page']);
		update_option('cpd_development_mode', false);
		update_option('cpd_map_widget_height', $options['cpd_map_widget_height']);
		update_option('cpd_map_widget_width', $options['cpd_map_widget_width']);

		$cpd_ci_sector_ids = array();
		$cpd_ci_sectors = explode(",", $options['cpd_ci_sectors']);
		foreach($cpd_ci_sectors as $sectorcode) {
			if($sectorcode * 1 > 0) {
				$cpd_ci_sector_ids[] = $sectorcode;
			}
			else {
				$sector_id = cpd_sector_id_for_code($sectorcode);
				$cpd_ci_sector_ids[] = $sector_id;
			}
		}
		update_option('cpd_ci_sector_ids', implode(',', $cpd_ci_sector_ids));
		
		$cpd_sod_sector_ids = array();
		$cpd_sod_sectors = explode(",", $options['cpd_sod_sectors']);
		foreach($cpd_sod_sectors as $sectorcode) {
			if($sectorcode * 1 > 0) {
				$cpd_sod_sector_ids[] = $sectorcode;
			}
			else {
				$sector_id = cpd_sector_id_for_code($sectorcode);
				$cpd_sod_sector_ids[] = $sector_id;
			}
		}
		update_option('cpd_sod_sector_ids', implode(',', $cpd_sod_sector_ids));
		
		// Keep an old copy kicking around just in case
		update_option('cpd-redundant-search-option', $options);
		delete_option('cpd-search-options');
		return;
	}
	
	// Default settings
	if(!get_option('cpd_rest_url')) {
		update_option('cpd_rest_url', 'https://apps.cpd.co.uk/restapi/v1');
	}
	if(!get_option('cpd_service_context')) {
		update_option('cpd_service_context', 'WordpressPlugin');
	}
}

function cpd_search_admin_menu() {
	add_options_page(
		__('CPD Search', 'cpd-search'),
		__('CPD Search', 'cpd-search'),
		'manage_options',
		__FILE__,
		'cpd_search_options_page');
}

// Hooks to allow CPD search configuration settings and options to be set
add_action('admin_init', 'cpd_search_admin_init');
add_action('admin_menu', 'cpd_search_admin_menu');

?>
