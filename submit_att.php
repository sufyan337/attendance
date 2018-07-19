<link rel="stylesheet" type="text/css" href="<?php echo plugins_url(); ?>/hs-attendance/assets/semantic/components/table.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo plugins_url(); ?>/hs-attendance/assets/semantic/components/icon.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo plugins_url(); ?>/hs-attendance/assets/semantic/components/button.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo plugins_url(); ?>/hs-attendance/assets/semantic/components/checkbox.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo plugins_url(); ?>/hs-attendance/assets/semantic/components/message.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo plugins_url(); ?>/hs-attendance/assets/semantic/components/label.min.css">

<script type="text/javascript" src="<?php echo plugins_url(); ?>/hs-attendance/assets/jquery/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="<?php echo plugins_url(); ?>/hs-attendance/assets/semantic/js/tablesort.js"></script>


<?php
global $wpdb;
$uid = get_current_user_id();
if(is_user_logged_in($uid) && !current_user_can('mark_att')){
if(!$_GET["clsid"]){
	?>
<p><link rel="stylesheet" type="text/css" href="<?php echo plugins_url(); ?>/hs-attendance/assets/semantic/semantic.min.css">
<script src="<?php echo plugins_url(); ?>/hs-attendance/assets/semantic/semantic.min.js"></script>
	<form action="" method="get" onsubmit="return validateForm()"" name="myForm">
		<label class="cls_label">Select Class: </label>
		<select name="clsid" id="select_cls" class="ui search selection dropdown"required="">
			<option value="--Select--">-- Select --</option>
			<!--<script type="text/javascript">
				$("#select_cls").change(function() {
				     this.form.submit();
				});
			</script>-->
			<?php 
			$all_cls = $wpdb->get_results($wpdb->prepare("SELECT ID,post_title FROM $wpdb->posts WHERE post_type = %s",'cls_name'));
			foreach ($all_cls as $cls) {
				echo '<option value="'.$cls->ID.'">'.$cls->post_title.'</option>';
			} ?>
		</select>
		<label class="per_label">Select Period: </label>
		<select name="period" id="select_per" class="ui search selection dropdown">
			<option value="--Select--">-- Select --</option>
			<?php 
			$all_per = $wpdb->get_results($wpdb->prepare("SELECT ID,post_title FROM $wpdb->posts WHERE post_type = %s",'per_name'));
			foreach ($all_per as $per) {
				echo '<option value="'.$per->ID.'">'.$per->post_title.'</option>';
			} ?>
		</select>
		Date: <input type="date" name="date" value="<?php echo date('Y-m-d'); ?>">
		<input type="submit" name="" value="Submit" class="ui primary button">
	</form>
</p>
<script type="text/javascript">
$('#select_cls').dropdown();
$('#select_per').dropdown();

function validateForm() {
	var x = document.forms["myForm"]["clsid"].value;
	var y = document.forms["myForm"]["period"].value;
    if (x == "--Select--") {
        alert("Class must be selected");
        return false;
    }
    if (y == "--Select--") {
        alert("Period must be selected");
        return false;
    }
}
</script>
	<?php 
} else { 
	echo '<p><b>Class: '.get_the_title($_GET["clsid"]).'
		<br>Period: '.get_the_title($_GET["period"]).'</b></p>'; 
	$atts = $wpdb->get_results($wpdb->prepare("SELECT ID,post_title,post_content FROM $wpdb->posts WHERE post_type = %s",'stu_name')); 
} 

if(!$_POST["save_att"]){ ?>

<div class="ui form">
<form action="" method="post">
<table class="ui celled sortable blue table">
	<thead>
		<tr>
			<th>Roll No</th>
			<th>Name</th>
			<th>Attendence</th>
		</tr>
	</thead>
	<tbody>
	<?php 
	if(!$_GET["clsid"]){
		echo '<tfoot class="full-width"><tr>
				<td colspan="3">
				<center>Select any class</center>
				</td>
			</tr></tfoot>';
	} else {
	foreach ($atts as $att) {
			//$content_post = get_post($att->ID);
			//$content = $content_post->post_content;
			$temp = $att->post_content;
			$att_content = json_decode($temp);
			$att_clsid = $att_content->cls;
			
			if($_GET["clsid"] == $att_clsid){
			$i++;
			echo '<tr><td>'.$i.'</td>
			<td>'.$att->post_title.'</td>
			<td>
			<div class="ui toggle checkbox" id="chk'.$i.'">
				<input type="checkbox" tabindex="0" onclick="run('.$i.')" name="st'.$att->ID.'" value="1" checked>
				<label id="msg'.$i.'"><span class="ui green label">Present</span></label>
			</div>
			</td>
			<script type="text/javascript">
			$("#chk'.$i.'").checkbox();
			</script>
			</tr>';
			} 
		}
	}	?>

	</tbody>
</table>
<input type="submit" name="save_att" class="ui primary button">
</form>
</div>
<script type="text/javascript">
	$('table').tablesort();
	function run(x){
		if($("#msg"+x).html()=='<span class="ui green label">Present</span>'){
			$("#msg"+x).html('<span class="ui red label">Absent</span>');
		} else {
			$("#msg"+x).html('<span class="ui green label">Present</span>');
		}
	}
</script>
<?php 
} else { $n = 0;
	$presentees = 0;
	$absentees = 0;
	$total = 0;
	foreach ($atts as $att) {
		//$content_post = get_post($att->ID);
		//$content = $content_post->post_content;
		$temp = $att->post_content;
		$att_content = json_decode($temp);
		$att_clsid = $att_content->cls;
		
		if($_GET["clsid"] == $att_clsid){
			$i++;
			$present = $_POST["st".$att->ID];
			if ($present) {
				$presentees++;
			} else {
				$absentees++;
			} 
			$total++;
			$att_raw_sl['slno'.$n] = $att->ID;
			$att_raw_at['atno'.$n] = $present;
			$n++;
		}
	}
	$final_raw[0] = $att_raw_sl;
	$final_raw[1] = $att_raw_at;
	$dcp_raw['date']   = $_GET["date"];
	$dcp_raw['cls']	   = $_GET["clsid"];
	$dcp_raw['period'] = $_GET["period"];
	$dcp_json = json_encode($dcp_raw);
	$att_json = json_encode($final_raw);
	$go = $wpdb->get_var($wpdb->prepare("
			SELECT dcp FROM hs_att WHERE dcp = %s",$dcp_json));
if(!$go){
	$x = $wpdb->insert('hs_att',
		array(	
				'dt'		=> $_GET["date"],
				'cls'		=> $_GET["clsid"],
				'prd'		=> $_GET["period"],
				'dcp'		=> $dcp_json ,
				'att'		=> $att_json ,
				'presentees' => $presentees ,
				'absentees'	=> $absentees ,
				'total'		=> $total 
			) );
}
echo '<br>Date: '.$_GET["date"];
echo '<br>Class: '.get_the_title($_GET["clsid"]);
echo '<br>Period: '.get_the_title($_GET["period"]);
echo '<br>Presentees : '.$presentees ;
echo '<br>Absentees : '.$absentees ;
echo '<br>Total: '.$total;
if($x){ ?>
	<meta http-equiv="refresh" content="6;URL=<?php echo get_permalink(); ?>" />
<div class="ui positive message" id="finalmsg">
  <i class="close icon" onclick="$('#finalmsg').addClass('transition hidden')"></i>
  <div class="header">
    Attendance posted successfully!
  </div>
</div>
<div class="ui icon positive message">
  <i class="notched circle loading icon"></i>
  <div class="content">
    <div class="header">
      Just one second
    </div>
    <p>You will be redirected to main page.</p>
  </div>
</div>
<?php } else { ?>
<div class="ui negative message" id="finalmsg">
  <i class="close icon" onclick="error()"></i>
  <div class="header">
    Already submitted!
  </div>
  <p>Please delete the previous one to post this period attendance.</p>
</div>

<div class="ui icon negative transition hidden message" id="error_redir">
  <i class="notched circle loading icon"></i>
  <div class="content">
    <div class="header">
      Just one second
    </div>
    <p>You will be redirected to main page.</p>
  </div>
</div>
<script type="text/javascript">
function error() {
	$('#finalmsg').addClass('transition hidden'); 
	$('#error_redir').removeClass('transition hidden')
	setTimeout(function() {
  window.location.href = "<?php echo get_permalink(); ?>";
}, 3000);
}
</script>
<?php	}
}
} else {
	echo '<div class="ui negative message" id="finalmsg">
		
		<div class="header">
		Please login with a Teacher account to continue!
		</div>
		<p>It seems you don\'t have permission to access this page.</p>
		</div>';

} ?>