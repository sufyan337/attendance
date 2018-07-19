<!-- Local assets -->
<link rel="stylesheet" type="text/css" href="<?php echo plugins_url(); ?>/hs-attendance/assets/semantic/components/table.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo plugins_url(); ?>/hs-attendance/assets/semantic/components/icon.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo plugins_url(); ?>/hs-attendance/assets/semantic/components/button.min.css">

<script type="text/javascript" src="<?php echo plugins_url(); ?>/hs-attendance/assets/jquery/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="<?php echo plugins_url(); ?>/hs-attendance/assets/semantic/js/tablesort.js"></script>

<h1>Classes</h1>
<form action="" method="post">
<table>
	<tr>
		<th>Class Name:</th>
		<td><input type="text" name="cls_name"></td>
		<td><input type="submit" name="add_cls" value="Add Class" class="ui mini green button"></td>
	</tr>
</table>
</form>
<?php
global $wpdb;
if(isset($_POST["cls_name"])){
	$wpdb->insert($wpdb->posts,
				array ('post_title' => $_POST["cls_name"],
						'post_type' => 'cls_name'));
}
$cls_name = $wpdb->get_results($wpdb->prepare("SELECT ID,post_title FROM $wpdb->posts WHERE post_type = %s",'cls_name'));
?>
<table class="ui celled fixed sortable table">
	<thead>
		<tr>
			<th>Sl.No.</th>
			<th>Class Name</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
<?php 
foreach ($cls_name as $cls_name) { $i++; 
echo '<tr>
	<td>'.$i.'.</td>
	<td><span id="cls'.$cls_name->ID.'">'.$cls_name->post_title.'</span></td>
	<td><button id="btn'.$cls_name->ID.'" class="ui mini primary button"
	onclick="edit_cls('.$cls_name->ID.',\''
	.$cls_name->post_title.'\')">Edit</button></td>
</tr>';
} ?>
	</tbody>
</table>

<script type="text/javascript">
function edit_cls( ID , title ) {
$('#cls'+ID).html('<input id="inp'+ID+'" type="text" name="cls_name" value="'+title+'" >');
	$('#inp'+ID).focus();
	$('#inp'+ID).select();
	$('#inp'+ID).keypress(function (e) {
	 var key = e.which;
	 if(key == 13)  // the enter key code
	  {
	    submit_cls(ID);
	    return false;  
	  }
	});
$('#btn'+ID).html('Save');
//title = $('#cls'+ID).html;
$('#btn'+ID).attr('onclick','submit_cls('+ID+')');
$('#inp'+ID).keyup(function(f) {
    //alert(f.which);
	if(f.which == 27){
		  	$('#cls'+ID).html(title);
		  	$('#btn'+ID).html('Edit');
		    $('#btn'+ID).attr('onclick','edit_cls('+ID+',\''+title+'\')');
		  }
	});
}
function submit_cls(ID){
	$('#btn'+ID).attr('class','ui mini loading green button');
	title = $("#inp"+ID).val();
	save_cls(ID,title);
}
$('table').tablesort();
</script>


