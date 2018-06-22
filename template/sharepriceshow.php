<!DOCTYPE html>
<html>
<head>
<style>
table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
}

td, th {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
}

tr:nth-child(even) {
    background-color: #dddddd;
}
</style>
</head>
<body>
<h2>Share Price</h2>

    <p>Start Date: <input type="text" id="datepicker"> End Date: <input type="text" id="datepicker2"><input type="submit" id="sbt" class="button button-primary button-large" name="" value="Submit"></p>
<div class="ajaxresponse">
<table>
  <tr><?php //echo $a; ?>
  	<th>ID</th>
    <th>Ticker</th>
    <th>Date</th>
    <th>High</th>
    <th>Open</th>
    <th>Low</th>
    <th>Close</th>
    <th>Volume</th>
    <th>Adjclose</th>
  </tr>

<?php 
global $wpdb;
$sharepricedata=$wpdb->get_results("SELECT * FROM share_prices WHERE DATE=CURDATE()  ");
if(empty($sharepricedata)){
    echo '</table><div style="text-align:center;margin-top: 10px;color: red;font-weight: 600;">No Data found.</div>';
}
else{
$count=1;
foreach ($sharepricedata as $price) { //$count=1; //print_r($price);?>
	<tr>
    <td><?php echo $count; ?></td>
    <td><?php echo $price->TICKER;  ?></td>
    <td><?php echo $price->DATE;  ?></td>
    <td><?php echo $price->HIGH;  ?></td>
    <td><?php echo $price->OPEN;  ?></td>
    <td><?php echo $price->LOW;  ?></td>
    <td><?php echo $price->CLOSE;  ?></td>
    <td><?php echo $price->VOLUME;  ?></td>
    <td><?php echo $price->ADJCLOSE;  ?></td>
  </tr>
<?php $count++; } }?>
  
</table>
</div>
</body>
</html>
<script type="text/javascript">
    +function($){
        $( "#datepicker" ).datepicker({
            dateFormat: "yy-mm-dd"
        });
        $( "#datepicker2" ).datepicker({
            dateFormat: "yy-mm-dd"
        });
       $('#sbt').click(function(){
        var date = $("#datepicker").val();
        var date2 = $("#datepicker2").val();
        var data= { action: 'my_action', 'date' :date, 'date2':date2};
        jQuery.post(ajaxurl, data,function(response) {     
         $('.ajaxresponse').html(response);
         });
       });
    }(jQuery);
</script>