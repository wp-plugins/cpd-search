<?php

$cpd_server_options = array(
	"Production" => "https://rest.cpd.co.uk",
	"Staging" => "https://staging.cpd.co.uk/restapi/v1",
	"Local development" => "http://localhost:8001",
);

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
  <img src="<?php echo cpd_plugin_dir_url("images/cpd_logo.png"); ?>" alt="CPD"/>
</a>

<h2>CPD Search Settings</h2>

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
        if(get_option('cpd_agent_id')) {
      ?>
      <table>
        <tr>
          <td>Agent Name:</td>
          <td><b><?php echo get_option('cpd_agent_name'); ?></b></td>
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
    <th scope="row">Service context</th>
    <td>
      <input type="text" name="cpd_service_context" value="<?php echo get_option('cpd_service_context'); ?>" />
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
</div>
	<?php
}

function cpd_search_server_check() {
	// Check details, and report success/failure of connection
	update_option('cpd_application_token', $_REQUEST['cpd_application_token']);
	update_option('cpd_rest_url', $_REQUEST['cpd_rest_url']);
	update_option('cpd_service_context', $_REQUEST['cpd_service_context']);

	// Confirm the connection works by requesting the status of the agent token
	$params = array(
		'refresh' => true
	);
	$url = sprintf("%s/agents/statuscheck/?%s", get_option('cpd_rest_url'), http_build_query($params));
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array(
		'X-CPD-Token: '.get_option('cpd_application_token'),
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
	update_option('cpd_agent_ref', $agent->ref);
	update_option('cpd_agent_name', $agent->owner);
	
	?>
	<div class="updated">
	<p>Server connection successful.</p>
	</div>
	<?php
}

function cpd_search_admin_init() {
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

