<!-- Offline assets -->
<link rel="stylesheet" type="text/css" href="<?php echo plugins_url(); ?>/hs-attendance/assets/semantic/components/table.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo plugins_url(); ?>/hs-attendance/assets/semantic/components/icon.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo plugins_url(); ?>/hs-attendance/assets/semantic/components/button.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo plugins_url(); ?>/hs-attendance/assets/semantic/components/label.min.css">
<!-- Export assest Files-->
<link href="<?php echo plugins_url(); ?>/hs-attendance/export/tableexport.min.css" rel="stylesheet" type="text/css">


<script type="text/javascript" src="<?php echo plugins_url(); ?>/hs-attendance/assets/jquery/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="<?php echo plugins_url(); ?>/hs-attendance/assets/semantic/js/tablesort.js"></script>
<!-- Export JavaScripts -->
<script src="<?php echo plugins_url(); ?>/hs-attendance/export/FileSaver.min.js"></script>
<script src="<?php echo plugins_url(); ?>/hs-attendance/export/Blob.min.js"></script>
<script src="<?php echo plugins_url(); ?>/hs-attendance/export/xls.core.min.js"></script>
<script src="<?php echo plugins_url(); ?>/hs-attendance/export/tableexport.min.js"></script>



<!-- Actual Code --> 
<h1>Class Report:</h1>
<h2>Enter two dates range within one month</h2>
<form action="" method="post" class="ui form">
	Class: 
	<select name="clsid" id="clsid">
		<?php 
		global $wpdb;
		$all_cls = $wpdb->get_results($wpdb->prepare("SELECT ID,post_title FROM $wpdb->posts WHERE post_type = %s" , 'cls_name'));
		foreach ($all_cls as $cls) {
			echo '<option value="'.$cls->ID.'">'.$cls->post_title.'</option>';
		} ?>
	</select>
	Start Date: <input type="date" name="dateid" id="dateid" value="<?php echo date('Y-m-d'); ?>">
	End Date: <input type="date" name="dateid2" id="dateid2" value="<?php echo date('Y-m-d'); ?>">
	<input type="submit" name="submit" class="ui primary mini button">
</form>

<table class="ui celled fixed structured sortable table" id="Class-Daily-Report">
	<thead>
		<tr>
			<th>Sl.No.</th>
			<th>Date</th>
			<?php 
			$all_per = $wpdb->get_results($wpdb->prepare("SELECT ID,post_title FROM $wpdb->posts WHERE post_type = %s",'per_name'));
			foreach ($all_per as $per) {
				echo '<th>Period '.$per->post_title.'</th>';
			}
			?>
		</tr>
	</thead>
	<tbody>
<?php
if(isset($_POST["submit"])){
?>
<script>
	$(function() { 
	 $("table").tableExport({formats: ["xlsx","xls", "csv", "txt"], });
	});
	$('#Class-Daily-Report').attr('id','<?php echo get_the_title($_POST["clsid"]); ?> Report');
	$('#clsid option[value="<?php echo $_POST["clsid"]; ?>"]').attr("selected", "selected");
	$('#dateid').attr("value", "<?php echo $_POST["dateid"]; ?>");
	$('#dateid2').attr("value", "<?php echo $_POST["dateid2"]; ?>");
	$('#period option[value="<?php echo $_POST["period"]; ?>"]').attr("selected", "selected");
</script>
<?php
global $wpdb;
$dateid = $_POST["dateid"];
$dateid2 = $_POST["dateid2"];
$time1 = date_create($dateid);
$time2 = date_create($dateid2);
$date_diff = date_diff($time1,$time2);
$dcp_raw['date']   = $_POST["dateid"];
$dcp_raw['cls']	   = $_POST["clsid"];
$inputdate = $dateid;
$i = 0;
$presentees = 0;
$absentees  = 0;
function object_to_array($object) {
    return (array) $object;
}
$diff = $date_diff->d;
for($day=0; $day <= $diff ; $day++){
	$date = new DateTime($inputdate); $i++;
	echo '<tr><td>'.$i.'.</td><td>'.$date->format('Y-m-d').'</td>';
	foreach ($all_per as $per) {
		$dcp_raw['period'] = $per->ID;
		$dcp_raw['date']   = $inputdate;
		$dcp_json = json_encode($dcp_raw);
		//echo $dcp_json.'<br>';
		$presentees = $wpdb->get_var($wpdb->prepare("
			SELECT presentees FROM hs_att WHERE dcp = %s" , $dcp_json ) );
		echo '<td>'.$presentees.'</td>';
	}
	$date->modify('+1 day');
	$inputdate = $date->format('Y-m-d');
} 
} 
?>
		</tr>
	</tbody>
</table>
<script type="text/javascript">
$('table').tablesort();
</script>