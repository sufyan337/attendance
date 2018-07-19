<?php
/*
Plugin Name: Haysky Attendance Plugin
Plugin URI: https://www.haysky.com/
Description: Attendance plugin by Haysky
Version: 1.0
Author: Sufyan
Author URI: https://www.sufyan.in/
License: GPLv2 or later
*/
function hsatt_create_table(){
	include (dirname(__FILE__).'/activation_hook.php');
}
function hsatt_info(){
	include (dirname(__FILE__).'/info.php');
}
function hsatt_classes(){
	include (dirname(__FILE__).'/classes.php');
}
function hsatt_periods(){
	include (dirname(__FILE__).'/periods.php');
}

function hsatt_students(){
	include (dirname(__FILE__).'/students.php');
}

function hsatt_teachers(){
	//include (dirname(__FILE__).'/teachers.php');
	include (dirname(__FILE__).'/wp_teachers.php');
}

function hsatt_submit_att(){
	include (dirname(__FILE__).'/submit_att.php');
}
add_shortcode('hsatt_submit_att','hsatt_submit_att');

function hsatt_cls_att_report(){
	include (dirname(__FILE__).'/cls_daily_report.php');
}
function hsatt_cls_month_report(){
	include (dirname(__FILE__).'/cls_month_report.php');
}

function hs_create_leave(){
	include (dirname(__FILE__).'/create_leave.php');
}
add_shortcode('hs_create_leave','hs_create_leave');

include (dirname(__FILE__).'/ajax.php');
//include (dirname(__FILE__).'/edit.php');

function hs_att_admin_menu(){
    add_menu_page('HS Attendance','HS Attendance','manage_options','hsatt_info','hsatt_info','dashicons-book','2');
    add_submenu_page('hsatt_info','Teachers','Teachers','manage_options','hsatt_teachers','hsatt_teachers');
    add_submenu_page('hsatt_info','Students','Students','manage_options','hsatt_students','hsatt_students');
    add_submenu_page('hsatt_info','Classes','Classes','manage_options','hsatt_classes','hsatt_classes');
    add_submenu_page('hsatt_info','Periods','Periods','manage_options','hsatt_periods','hsatt_periods');
    add_submenu_page('hsatt_info','Class Daily Report','Class Daily Report','manage_options','hsatt_cls_att_report','hsatt_cls_att_report');
    add_submenu_page('hsatt_info','Class Monthly Report','Class Monthly Report','manage_options','hsatt_cls_month_report','hsatt_cls_month_report');
}
add_action('admin_menu' , 'hs_att_admin_menu');


add_role( 'teacher', 'Teacher', array( 'read' => true, 'level_0' => true ) );
$role = get_role( 'teacher' );
$role->add_cap('mark_att');
//remove_role( 'teacher', 'Teacher', array( 'read' => true, 'level_0' => true ) );
