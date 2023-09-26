<?php
include("../../config.php");
$country_id=$_REQUEST['countryid'];
$county=$_REQUEST['county'];

$selectCounty = $db->query('SELECT county.id, county.name FROM ' . $dbPrefix . 'county as county WHERE country_id=' . $country_id, array());

?>
<select class="form-control" name="county" id="county" data-validation-engine="validate[required]" data-errormessage-value-missing="Please select at least one county." style="width: 320px;" >
	<option value="">Select County</option>	
	
	<?php
	foreach ($selectCounty->result_array() as $row) {
		$countyName = $row['name']; ?>
		<option <?php if($row['id'] == $county) { echo "selected"; } ?> value="<?php echo $row['id']; ?>" ><?php echo $countyName; ?></option>
		
	<?php } ?>
</select>