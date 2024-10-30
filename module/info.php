<style>
  .styled-table {
    border-collapse: collapse;
    margin: 25px 0;
    min-width: 400px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
}
.styled-table thead tr {
    background-color: #009879;
    color: #ffffff;
    text-align: left;
}
.styled-table th,
.styled-table td {
    padding: 12px 15px;
}
.styled-table tbody tr {
    border-bottom: 1px solid #dddddd;
}

.styled-table tbody tr:nth-of-type(even) {
    background-color: #f3f3f3;
}

</style>
<h2>How do I use MetalpriceAPI plugin?</h2>
<div>
  <h4>Intro</h4>
  <p>Our plugin uses shortcodes. Here is some more information about <a target="_blank" href="https://wordpress.com/support/shortcodes/">WordPress Shortcodes.</a></p>
</div>
<div>
  <h4>Usage</h4>
  <p>Use shortcode <code>[metalpriceapi]</code> to display metal rates on your wp website.</p>
</div>
<hr>
<div>
  <h4>Options</h4>
  <p>The table below includes all of the current options available to you for customizing the [metalpriceapi] shortcode output. All options below are <b>optional</b>.</p>
  <table border="0" width="90%" cellpadding="0"  class="styled-table">
    <tbody>
      <tr>
        <th>Option</th>
        <th>Description</th>
        <th>Choices</th>
        <th width="80px">Default Setting</th>
      </tr>
      <tr>
        <td><b>symbol</b></td>
        <td>The metal price you would like to display or the currency price you would like to display.</td>
        <td><a target="_blank" href="https://metalpriceapi.com/currencies">List of supported symbols</a> (Use code column).</td>
        <td>XAU</td>
      </tr>
      <tr>
        <td><b>base</b></td>
        <td>Display price in a specified currency.</td>
        <td><a target="_blank" href="https://metalpriceapi.com/currencies">List of supported symbols</a> (Use code column).</td>
        <td>USD</td>
      </tr>
      <tr>
        <td><b>price_round</b></td>
        <td>Round price to a desired amount of decimal places.</td>
        <td></td>
        <td>2 (Rounds up to 2 decimal places)</td>
      </tr>
      <tr>
        <td><b>date</b></td>
        <td>If you want to use rates from a specific historical date, you should specify this field in YYYY-MM-DD format. Specifying this option will use the <a target="_blank" href="https://metalpriceapi.com/documentation#api_historical">Historical Rates API</a>. If this option is not specified, shortcodes will use the <a target="_blank" href="https://metalpriceapi.com/documentation#api_realtime">Real-Time Rates API</a>.</td>
        <td></td>
        <td>Live rate will be used.</td>
      </tr>
      <tr>
        <td><b>days</b></td>
        <td>The number of days to subtract from the current date to determine the target date for the query. It accepts a negative integer value, where the absolute value represents the number of days to go back in time. For example, -7 would query data from one week ago, -30 would query data from one month ago, and so on. Specifying this option will use the <a target="_blank" href="https://metalpriceapi.com/documentation#api_historical">Historical Rates API</a>. If this option is not specified, shortcodes will use the <a target="_blank" href="https://metalpriceapi.com/documentation#api_realtime">Real-Time Rates API</a>.</td>
        <td></td>
        <td>Live rate will be used.</td>
      </tr>
      <tr>
        <td><b>date_format</b></td>
        <td>Display date in a specific format.</td>
        <td><a target="_blank" href="https://www.php.net/manual/en/datetime.format.php">Formats.</a></td>
        <td>Y-m-d</td>
      </tr>
      <tr>
        <td><b>unit</b></td>
        <td>Specifies units to use.</td>
        <td>Defaults to troy ounces. Can also change to <b>gram</b> or <b>kilogram</b>. <a target="_blank" href="https://metalpriceapi.com/documentation#api_realtime">Learn more here.</a></td>
        <td>troy_oz</td>
      </tr>
      <tr>
        <td><b>purity</b></td>
        <td>Specifies the purity to use.</td>
        <td>Multiples output by this value. Use <b>0.9</b> for 90% purity or <b>0.999</b> for 99.9% purity for example.</a></td>
        <td>1</td>
      </tr>
      <tr>
        <td><b>operation</b></td>
        <td>Perform mathmatical operations on results. Use "value" to represent the result.<br>e.g. Display 90% of the price or 99% of the price and then subtract 5.</td>
        <td>+&nbsp;&nbsp;-&nbsp;&nbsp;*&nbsp;&nbsp;/&nbsp;&nbsp;(&nbsp;&nbsp;)</td>
        <td></td>
      </tr>
    </tbody>
  </table>
</div>
<hr>
<div>
  <h3>Examples</h3>
  <ul>
    <li><code>[metalpriceapi base="USD" symbol="XAU" price_round="3"]</code></li>
    &nbsp;
    <li><code>[metalpriceapi price_round="2"]</code></li>
    &nbsp;
    <li><code>[metalpriceapi base="USD" symbol="XAU" unit="kilogram"]</code></li>
    &nbsp;
    <li><code>[metalpriceapi date="2021-01-28"]</code></li>
    &nbsp;
    <li><code>[metalpriceapi date="2021-01-28" date_format="F jS, Y"]</code></li>
    &nbsp;
    <li><code>[metalpriceapi days="-1"]</code></li>
    &nbsp;
    <li><code>[metalpriceapi base="USD" symbol="XAU" price_round="3" operation="(((value)-5)*10)*0.7"]</code></li>
    &nbsp;
    <li><code>[metalpriceapi base="USD" symbol="XAU" price_round="3" operation="(((value)*.05)+(value))"]</code></li>
    &nbsp;
    <li><code>[metalpriceapi]</code></li>
  </ul>
  <h3>Carat Examples</h3>
  <ul>
    <li><code>[metalpriceapi_carat base="USD" purity="24k" price_round="3"]</code></li>
    &nbsp;
    <li><code>[metalpriceapi_carat base="EUR" purity="6k" date_format="m-d-Y"]</code></li>
    &nbsp;
    <li><code>[metalpriceapi_carat date="2021-01-28" base="EUR" purity="6k"]</code></li>
    &nbsp;
    <li><code>[metalpriceapi_carat purity="24k"]</code></li>
  </ul>
</div>