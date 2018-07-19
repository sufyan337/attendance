<!-- Offline assets -->
<link rel="stylesheet" type="text/css" href="<?php echo plugins_url(); ?>/hs-attendance/assets/semantic/components/table.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo plugins_url(); ?>/hs-attendance/assets/semantic/components/icon.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo plugins_url(); ?>/hs-attendance/assets/semantic/components/button.min.css">

<script type="text/javascript" src="<?php echo plugins_url(); ?>/hs-attendance/assets/jquery/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="<?php echo plugins_url(); ?>/hs-attendance/assets/semantic/js/tablesort.js"></script>

<h1>Students</h1>
<form action="" method="post">
<table>
	<tr>
		<th>Student Name:</th>
		<td><input type="text" name="stu_name"></td>
		<td><select name="cls">
<?php 
global $wpdb;
$all_cls = $wpdb->get_results($wpdb->prepare("SELECT ID,post_title FROM $wpdb->posts WHERE post_type = %s" , 'cls_name'));
foreach ($all_cls as $cls) {
	echo '<option value="'.$cls->ID.'">'.$cls->post_title.'</option>';
} ?>
		</select></td>
		<td><input type="submit" class="ui mini green button" name="add_stu" value="Add Student"></td>
	</tr>
</table>
</form>
<?php
global $wpdb;
if(isset($_POST["stu_name"])){
	$cls_name['cls'] = $_POST["cls"];
	$post_content = json_encode($cls_name);
	$wpdb->insert($wpdb->posts,
				array ('post_title' => $_POST["stu_name"],
						'post_content' => $post_content,
						'post_type' => 'stu_name'));
}
$stu_name = $wpdb->get_results($wpdb->prepare("SELECT ID,post_title,post_content FROM $wpdb->posts WHERE post_type = %s",'stu_name'));
?>
<table class="ui celled sortable fixed blue table">
	<thead>
		<tr>
			<th>Sl.No.</th>
			<th>Student Name</th>
			<th>Class</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
<?php 
foreach ($stu_name as $stu_name) { $i++; 
$raw_content = $stu_name->post_content;
$stu = json_decode($raw_content);
echo '<tr>
	<td>'.$i.'.</td>
	<td><span id="stu'.$stu_name->ID.'">'.$stu_name->post_title.'</span></td>
	<td><span id="stu_cls'.$stu_name->ID.'">'.get_the_title($stu->cls).'</span></td>
	<td><button id="btn'.$stu_name->ID.'" 
	class="ui mini primary button"
	onclick="edit_stu('.$stu_name->ID.',\''
	.$stu_name->post_title.'\',\''
	.$stu->cls.'\',\''.$stu->ID.'\')">Edit</button></td>
</tr>';
} ?>
	</tbody>
</table>

<script type="text/javascript">
function edit_stu( ID , title  , clsid, cls ) {
	//changing input button
$('#stu'+ID).html('<input id="inp'+ID+'" type="text" name="stu_name" value="'+title+'" >');
//changing select form
options = '<?php foreach ($all_cls as $cls) { echo '<option value="'.$cls->ID.'">'.$cls->post_title.'</option>'; } ?>';
$('#stu_cls'+ID).html('<span id="stu_cls'+ID+'"><select id="cls'+ID+'">'+options+'</select></span>');
// Making option as select
$('#cls'+ID+' option[value="'+clsid+'"]').attr("selected", "selected");
$('#inp'+ID).keypress(function (e) {
	 var key = e.which;
	 if(key == 13)  // the enter key code
		  {
		    submit_stu(ID);
		    return false;  
		  }
	});
$('#btn'+ID).html('Save');
$('#btn'+ID).attr('onclick','submit_stu('+ID+')');
}
function submit_stu(ID){
	title = $("#inp"+ID).val();
	cls   = $("#cls"+ID).val();
	save_stu(ID,title,cls);
}
$('table').tablesort();
</script>