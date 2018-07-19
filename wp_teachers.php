<!-- Local assets -->
<link rel="stylesheet" type="text/css" href="<?php echo plugins_url(); ?>/hs-attendance/assets/semantic/components/table.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo plugins_url(); ?>/hs-attendance/assets/semantic/components/icon.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo plugins_url(); ?>/hs-attendance/assets/semantic/components/button.min.css">

<script type="text/javascript" src="<?php echo plugins_url(); ?>/hs-attendance/assets/jquery/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="<?php echo plugins_url(); ?>/hs-attendance/assets/semantic/js/tablesort.js"></script>

<?php
global $wpdb;
if(!isset($_POST["view_teacher"]) || isset($_POST["go_back"])){
?>
<h1>Teachers</h1>
<a href="http://localhost/wp-admin/user-new.php"><button class="ui green mini button">Add Teacher</button></a><b> Create new user and keep role as teacher.</b>
<?php
global $wpdb;
if(isset($_POST["teacher_name"])){
	$wpdb->insert($wpdb->posts,
				array ('post_title' => $_POST["teacher_name"],
						'post_type' => 'teacher_name'));
}
$args = array(
  'role'         => 'teacher',
  'role__in'     => array(),
  'role__not_in' => array(),
  'orderby'      => 'login',
  'order'        => 'ASC',
  'count_total'  => false,
  'fields'       => 'all',
  'who'          => ''
 ); 
$teachers = get_users( $args );
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
foreach ($teachers as $teacher_name) { $i++; 
echo '<tr>
	<td>'.$i.'.</td>
	<td><span id="teacher'.$teacher_name->ID.'">'.$teacher_name->display_name.'</span></td>
	<td>
	<form action="" method="post">
		<input type="hidden" name="ID" value="'.$teacher_name->ID.'">
		<input type="submit" name="view_teacher" value="Edit" class="ui blue button mini">';
echo '</form>
	</td>
</tr>';
} ?>
	</tbody>
</table>

<script type="text/javascript">
	$('table').tablesort();
</script>
<?php 
} else { 
	$ID = $_POST["ID"];
	if (isset($_POST["save_teacher"])) {
		$display_name = $_POST["display_name"];
		$email = $_POST["email"];
		$phone = $_POST["phone"];
		$address = $_POST["address"];
		$dob	= $_POST["dob"];
		$gender	= $_POST["gender"];
		$class_incharge	= $_POST["class_incharge"];
		update_user_meta( $ID , 'phone' , $phone );
		update_user_meta( $ID , 'address' , $address );
		update_user_meta( $ID , 'dob' , $dob );
		update_user_meta( $ID , 'gender' , $gender );
		update_user_meta( $ID , 'class_incharge' , $class_incharge );
		wp_update_user( array(	'ID' => $ID , 
								'display_name'	=> $display_name,
								'user_email'	=> $email	) );
	}
	$teacher = get_user_by('ID',$ID);
	$phone = get_user_meta( $ID , 'phone' , true);
	$address = get_user_meta( $ID , 'address' , true);
	$dob = get_user_meta( $ID , 'dob' , true);
	$gender = get_user_meta( $ID , 'gender' , true );
	$class_incharge = get_user_meta( $ID , 'class_incharge' , true );	
	echo '
	<h1>Teacher view:</h1>
	<form action="" method="post">
	<input type="submit" name="save_teacher" class="ui primary mini button" value="Save Changes">
	<input type="submit" name="go_back" class="ui mini button" value="Go Back">
	<input type="hidden" name="view_teacher" value="yes">
	<input type="hidden" name="ID" value="'.$ID.'">
	<table class="ui celled table">
		<tr>
			<td>Name:</td>
			<td><input type="text" name="display_name" value="'.$teacher->display_name.'"></td>
		</tr>
		<tr>
			<td>Email</td>
			<td><input type="email" name="email" value="'.$teacher->user_email.'"></td>
		</tr>
		<tr>
			<td>Phone</td>
			<td><input type="text" name="phone" value="'.$phone.'"></td>
		</tr>
		<tr>
			<td>Address</td>
			<td><textarea rows="4" name="address">'.$address.'</textarea></td>
		</tr>
		<tr>
			<td>Date of Birth</td>
			<td><input type="date" name="dob" value="'.$dob.'"></td>
		</tr>
		<tr>
			<td>Gender</td>
			<td><select name="gender" id="gender"><option value="Male">Male</option>
			<option value="Female">Female</option></select></td>
		</tr>
		<tr>
			<td>Class Incharge for</td>
			<td><select name="class_incharge" id="class_incharge">
					<option value="1">I Class</option>
					<option value="2">II Class</option>
					<option value="3">III Class</option>
					<option value="4">IV Class</option>
				</select>
			</td>
		</tr>
	</table>
	<input type="submit" name="save_teacher" class="ui primary mini button" value="Save Changes">
	<input type="submit" name="go_back" class="ui mini button" value="Go Back">
	</form>
	';
		
	}
?>
<script type="text/javascript">
$('#gender option[value="<?php echo $gender; ?>"]').attr('selected','selected');
$('#class_incharge option[value="<?php echo $class_incharge; ?>"]').attr('selected','selected');
</script>
