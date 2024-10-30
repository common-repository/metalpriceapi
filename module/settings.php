<h1>MetalpriceAPI Settings</h1>
<hr>
<?php if($is_saved) { ?>
  <div class="notice notice-success">
    <p>Your settings have been saved!</p>
  </div>
<?php } ?>
<form class="" method="post">
  <?php wp_nonce_field(-1, "mpa_nonce");?>
  <table class="form-table">
    <tbody>
      <tr>
        <th>API Key</th>
        <td>
          <input type="text" class="regular-text" name="mpa_api_key" value="<?php echo esc_html(get_option('mpa_api_key')); ?>">
          <p class="description">
            <span>
              <?php echo isset($api_status) && trim($api_status) != "" ? "API Status: ".esc_html($api_status)."" : ""; ?>
            </span>
          </p>
          <p class="description">Get your API key at <a href="https://metalpriceapi.com" target="_blank">MetalpriceAPI</a>. Copy your API Access Key here after sign up.</p>
        </td>
      </tr>
    </tbody>
  </table>
  <h3>Shortcode templates</h3>
  <p>Our API can return in three different states, success, failure, or no data. Customize how each state displays to your users.</p>
  <table class="form-table">
    <tbody>
      <tr>
        <th><span class="success">Fetch Success</span></th>
        <td>
          <?php wp_editor(
            stripslashes(
              get_option(
                "mpa_data_success",
                "{{symbol}} {{price}}"
              )
            ),
            "mpa_data_success",
            array(
              "textarea_name" =>"mpa_data_success",
              "textarea_rows" => 3
            )
          );
          ?>
          <p class="description">You can use {{base}}, {{timestamp}}, {{price}}, {{symbol}}, {{date}} here.</p>
        </td>
      </tr>

      <tr>
        <th><span class="empty">Fetch Returns No Result</span></th>
        <td>
          <?php wp_editor(
            stripslashes(
              get_option(
                "mpa_data_none",
                "No result."
              )
            ),
            "mpa_data_none",
            array(
              "textarea_name" => "mpa_data_none",
              "textarea_rows" => 3
            )
          );
          ?>
        </td>
      </tr>

      <tr>
        <th><span class="error">Fetch Error</span></th>
        <td>
          <?php wp_editor(
            stripslashes(
              get_option(
                "mpa_data_error",
                "Error: {{error}}")
              ),
              "mpa_data_error",
              array(
                "textarea_name" => "mpa_data_error",
                "textarea_rows" =>  3
              )
            );
            ?>
            <p class="description">You can use {{error}} to display the error returned from the API or specify a general error message here such as "Something went wrong".</p>
        </td>
      </tr>
    </tbody>
  </table>
  <h3>Carat Endpoint</h3>
  <p>Customize <a target="_blank" href="https://metalpriceapi.com/documentation#api_carat">carat endpoint</a> display.</p>
  <table class="form-table">
    <tbody>
      <tr>
        <th><span class="success">Fetch Carat Success</span></th>
        <td>
          <?php wp_editor(
            stripslashes(
              get_option(
                "mpa_data_carat_success",
                "{{price}}"
              )
            ),
            "mpa_data_carat_success",
            array(
              "textarea_name" =>"mpa_data_carat_success",
              "textarea_rows" => 3
            )
          );
          ?>
          <p class="description">You can use {{base}}, {{timestamp}}, {{price}}, {{date}} here.</p>
        </td>
      </tr>
    </tbody>
  </table>
  <p>
    <button type="submit" class="button button-primary" name="save">Submit</button>
  </p>
</form>