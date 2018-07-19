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

<form action="" method="post" class="ui form">
	Class: <select name="clsid" id="clsid">
		<?php 
		global $wpdb;
		$all_cls = $wpdb->get_results($wpdb->prepare("SELECT ID,post_title FROM $wpdb->posts WHERE post_type = %s" , 'cls_name'));
		foreach ($all_cls as $cls) {
			echo '<option value="'.$cls->ID.'">'.$cls->post_title.'</option>';
		} ?>
	</select>
	Date: <input type="date" name="dateid" id="dateid" value="<?php echo date('Y-m-d'); ?>">
	Period:
		<select name="period" id="period">
			<?php 
			$all_per = $wpdb->get_results($wpdb->prepare("SELECT ID,post_title FROM $wpdb->posts WHERE post_type = %s",'per_name'));
			foreach ($all_per as $per) {
				echo '<option value="'.$per->ID.'">'.$per->post_title.'</option>';
			} ?>
		</select>
	<input type="submit" name="submit" class="ui primary mini button">
</form>
<?php if(isset($_POST["submit"]) && 1){ echo '<div id="canvas-holder" style="width:40%">
	<canvas id="chart-area"></canvas>
</div>'; } ?>
<table class="ui celled fixed structured sortable table" id="Class-Daily-Report">
	<thead>
		<tr>
			<th>Sl.No</th>
			<th>Student Name</th>
			<th>Attendance</th>
		</tr>
	</thead>
	<tbody>
<?php
if(isset($_POST["submit"])){
?>
<script>
$(function() { 
 $("table").tableExport({formats: ["xlsx","xls", "csv", "txt"],    });
});
$('#Class-Daily-Report').attr('id','<?php echo get_the_title($_POST["clsid"]); ?> Report');
</script>
<script type="text/javascript">
$('#clsid option[value="<?php echo $_POST["clsid"]; ?>"]').attr("selected", "selected");
$('#dateid').attr("value", "<?php echo $_POST["dateid"]; ?>");
$('#period option[value="<?php echo $_POST["period"]; ?>"]').attr("selected", "selected");
</script>
<?php
global $wpdb;
$dcp_raw['date']   = $_POST["dateid"];
$dcp_raw['cls']	   = $_POST["clsid"];
$dcp_raw['period'] = $_POST["period"];
$dcp_json = json_encode($dcp_raw);
$cls_reports = $wpdb->get_results($wpdb->prepare('
	SELECT * FROM hs_att WHERE dcp = %s',$dcp_json));
$i = 0;
$presentees = 0;
$absentees  = 0;
foreach ($cls_reports as $rep) {
	$i++;
	$cls_data = json_decode($rep->att);
		function object_to_array($object) {
		    return (array) $object;
		}
	$new = object_to_array($rep->att);
	$new1 = json_decode($new[0]);
	$slnos = object_to_array($new1[0]);
	$loop = $slnos;
	$atnos = object_to_array($new1[1]);
	$z = 0; 
	$n = 1;
	foreach ($loop as $loop) {
		echo '<tr><td>'.$n++.'.</td><td>'.get_the_title($slnos['slno'.$z]).'</td><td>'; 
		if($atnos['atno'.$z]){ $presentees++;
			echo '<span class="ui green label">Present</span>';
		} else { $absentees++;
			echo '<span class="ui red label">Absent</span>';
		}
		echo '</td>'; 
		
		echo '</tr>';	$z++; 
	}
} 
} 
?>
	</tbody>
</table>
<?php $n--;
$new = ($n*100)/2; ?>
<script type="text/javascript">
$('table').tablesort();
$('#graph_td').attr('rowspan','<?php echo $n; ?>');
$('#canvas').attr('height','<?php echo $new; ?>');
</script>

<!-- Chart js code starts here -->

<script src="<?php echo plugins_url(); ?>/hs-attendance/assets/charts/Chart.bundle.js"></script>
<script src="<?php echo plugins_url(); ?>/hs-attendance/assets/charts/utils.js"></script>
<script>
	var randomScalingFactor = function() {
		return Math.round(Math.random() * 100);
	};

	var config = {
		type: 'pie',
		data: {
			datasets: [{
				data: [
					<?php echo $presentees.','.$absentees ?>			
				],
				backgroundColor: [
					window.chartColors.blue,
					window.chartColors.red
				],
				label: 'Class Daily Report'
			}],
			labels: [
				'Presentees',
				'Absentees'					
			]
		},
		options: {
			responsive: true
		}
	};

	window.onload = function() {
		var ctx = document.getElementById('chart-area').getContext('2d');
		window.myPie = new Chart(ctx, config);
	};
</script>
<!-- Chart js code ends here -->

<!---
<script>
	var barChartData = {
		labels: ['<?php echo get_the_title($_POST["clsid"]); ?>'],
		datasets: [{
			label: 'Present',
			backgroundColor: window.chartColors.blue,
			data: [
				<?php echo $presentees; ?>
			]
		}, {
			label: 'Absent',
			backgroundColor: window.chartColors.red,
			data: [
				<?php echo $absentees; ?>
			]
		}]

	};
	window.onload = function() {
		var ctx = document.getElementById('canvas').getContext('2d');
		window.myBar = new Chart(ctx, {
			type: 'bar',
			data: barChartData,
			options: {
				title: {
					display: false,
					text: 'Chart.js Bar Chart - Stacked'
				},
				tooltips: {
					mode: 'index',
					intersect: true
				},
				responsive: true,
				scales: {
					xAxes: [{
						stacked: true,
					}],
					yAxes: [{
						stacked: true
					}]
				}
			}
		});
	};

	document.getElementById('randomizeData').addEventListener('click', function() {
		barChartData.datasets.forEach(function(dataset) {
			dataset.data = dataset.data.map(function() {
				return randomScalingFactor();
			});
		});
		window.myBar.update();
	});
</script>
-->