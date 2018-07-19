<!-- Offline assets -->
<link rel="stylesheet" type="text/css" href="<?php echo plugins_url(); ?>/hs-attendance/assets/semantic/components/table.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo plugins_url(); ?>/hs-attendance/assets/semantic/components/icon.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo plugins_url(); ?>/hs-attendance/assets/semantic/components/button.min.css">

<script type="text/javascript" src="<?php echo plugins_url(); ?>/hs-attendance/assets/jquery/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="<?php echo plugins_url(); ?>/hs-attendance/assets/semantic/js/tablesort.js"></script>

<h1>Periodes</h1>
<form action="" method="post">
<table>
	<tr>
		<th>Period Name:</th>
		<td><input type="text" name="per_name"></td>
		<td><input type="submit" name="add_per" value="Add Period" class="ui mini green button"></td>
	</tr>
</table>
</form>
<?php
global $wpdb;
if(isset($_POST["per_name"])){
	$wpdb->insert($wpdb->posts,
				array ('post_title' => $_POST["per_name"],
						'post_type' => 'per_name'));
}
$per_name = $wpdb->get_results($wpdb->prepare("SELECT ID,post_title FROM $wpdb->posts WHERE post_type = %s",'per_name'));
?>
<table class="ui celled fixed sortable table">
	<thead>
		<tr>
			<th>Sl.No.</th>
			<th>Period Name</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
<?php 
foreach ($per_name as $per_name) { $i++; 
echo '<tr>
	<td>'.$i.'.</td>
	<td><span id="per'.$per_name->ID.'">'.$per_name->post_title.'</span></td>
	<td><button id="btn'.$per_name->ID.'" class="ui mini primary button"
	onclick="edit_per('.$per_name->ID.',\''
	.$per_name->post_title.'\')">Edit</button></td>
</tr>';
} ?>
	</tbody>
</table>

<script type="text/javascript">
function edit_per( ID , title ) {
$('#per'+ID).html('<input id="inp'+ID+'" type="text" name="per_name" value="'+title+'" >');
	$('#inp'+ID).focus();
	$('#inp'+ID).select();
	$('#inp'+ID).keypress(function (e) {
	 var key = e.which;
	 if(key == 13)  // the enter key code
	  {
	    submit_per(ID);
	    return false;  
	  }
	});
	$('#inp'+ID).keyup(function(f) {
    //alert(f.which);
	if(f.which == 27){
		  	$('#per'+ID).html(title);
		  	$('#btn'+ID).html('Edit');
		    $('#btn'+ID).attr('onclick','edit_cls('+ID+',\''+title+'\')');
		}
	});
$('#btn'+ID).html('Save');
//title = $('#per'+ID).html;
$('#btn'+ID).attr('onclick','submit_per('+ID+')');
}
function submit_per(ID){
	$('#btn'+ID).attr('class','ui mini loading primary button');
	title = $("#inp"+ID).val();
	save_per(ID,title);
}
$('table').tablesort();
</script>