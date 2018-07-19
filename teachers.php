<!-- Local assets -->
<link rel="stylesheet" type="text/css" href="<?php echo plugins_url(); ?>/hs-attendance/assets/semantic/components/table.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo plugins_url(); ?>/hs-attendance/assets/semantic/components/icon.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo plugins_url(); ?>/hs-attendance/assets/semantic/components/button.min.css">

<script type="text/javascript" src="<?php echo plugins_url(); ?>/hs-attendance/assets/jquery/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="<?php echo plugins_url(); ?>/hs-attendance/assets/semantic/js/tablesort.js"></script>

<?php
global $wpdb;
if(!isset($_POST["view_teacher"])){
	?>
	<h1>Teachers</h1>
	<form action="" method="post">
	<table>
		<tr>
			<th>Teacher Name:</th>
			<td><input type="text" name="teacher_name"></td>
			<td><input type="submit" name="add_teacher" value="Add Teacher" class="ui mini green button"></td>
		</tr>
	</table>
	</form>
	<?php
	global $wpdb;
	if(isset($_POST["teacher_name"])){
		$wpdb->insert($wpdb->posts,
					array ('post_title' => $_POST["teacher_name"],
							'post_type' => 'teacher_name'));
	}
	$teacher_name = $wpdb->get_results($wpdb->prepare("SELECT ID,post_title FROM $wpdb->posts WHERE post_type = %s",'teacher_name'));
	?>
	<table class="ui celled fixed sortable table">
		<thead>
			<tr>
				<th>Sl.No.</th>
				<th>Teacher Name</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
	<?php 
	foreach ($teacher_name as $teacher_name) { $i++; 
	echo '<tr>
		<td>'.$i.'.</td>
		<td><span id="teacher'.$teacher_name->ID.'">'.$teacher_name->post_title.'</span></td>
		<td>
		<form action="" method="post">
			<input type="hidden" name="ID" value="'.$teacher_name->ID.'">
			<input type="submit" name="view_teacher" value="View" class="ui green button mini">
		<button id="btn'.$teacher_name->ID.'" class="ui mini primary button"
		onclick="edit_teacher('.$teacher_name->ID.',\''
		.$teacher_name->post_title.'\')">Quick Edit</button>
		</form>
		</td>
	</tr>';
	} ?>
		</tbody>
	</table>

	<script type="text/javascript">
	function edit_teacher( ID , title ) {
	$('#teacher'+ID).html('<input id="inp'+ID+'" type="text" name="teacher_name" value="'+title+'" >');
		$('#inp'+ID).focus();
		$('#inp'+ID).select();
		$('#inp'+ID).keypress(function (e) {
		 var key = e.which;
		 if(key == 13)  // the enter key code
		  {
		    submit_teacher(ID);
		    return false;  
		  }
		});
	$('#btn'+ID).html('Save');
	//title = $('#teacher'+ID).html;
	$('#btn'+ID).attr('onclick','submit_teacher('+ID+')');
	$('#inp'+ID).keyup(function(f) {
	    //alert(f.which);
		if(f.which == 27){
			  	$('#teacher'+ID).html(title);
			  	$('#btn'+ID).html('Edit');
			    $('#btn'+ID).attr('onclick','edit_teacher('+ID+',\''+title+'\')');
			  }
		});
	}
	function submit_teacher(ID){
		$('#btn'+ID).attr('class','ui mini loading green button');
		title = $("#inp"+ID).val();
		save_teacher(ID,title);
	}
	$('table').tablesort();
	</script>
	<?php 
} else { 
	$ID = $_POST["ID"];
	
	if (isset($_POST["email"])) {
		
	}
	$teachers = $wpdb->get_results($wpdb->prepare("SELECT ID,post_title,post_content FROM $wpdb->posts WHERE ID = %d ",$ID));
	foreach ($teachers as $teacher) {
		echo $teacher->post_content;
		echo '
<h1>Teacher view:</h1>
<form action="" method="post">
<input type="submit" name="save_teacher" class="ui primary mini button">
<input type="hidden" name="view_teacher" value="yes">
<input type="hidden" name="ID" value="'.$_POST["ID"].'">
<table class="ui celled table">
	<tr>
		<td>Name:</td>
		<td><input type="text" name="teacher_name" value="'.$teacher->post_title.'"></td>
	</tr>
	<tr>
		<td>Email</td>
		<td><input type="email" name="email" value=""></td>
	</tr>
	<tr>
		<td>Phone</td>
		<td><input type="text" name=""></td>
	</tr>
	<tr>
		<td>Address</td>
		<td><textarea rows="4"></textarea></td>
	</tr>
	<tr>
		<td>Date of Birth</td>
		<td><input type="date" name=""></td>
	</tr>
	<tr>
		<td>Gender</td>
		<td><select><option>Male</option><option>Female</option></select></td>
	</tr>
	<tr>
		<td>Class Incharge for</td>
		<td><select><option>I Class</option></select></td>
	</tr>
	<tr>
		<td>Teaching classes</td>
		<td>
			<select multiple="" >
				<option>1</option>
				<option>2</option>
				<option>3</option>
			</select></td>
	</tr>
</table>
</form>
';
		
	}
} ?>

