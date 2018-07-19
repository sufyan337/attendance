<?php
add_action( 'admin_enqueue_scripts', 'my_enqueue' );
function my_enqueue($hook) {
    
	wp_enqueue_script( 'ajax-script', plugins_url( '/my.js', __FILE__ ), array('jquery') );

	// in JavaScript, object properties are accessed as ajax_object.ajax_url, ajax_object.we_value
	wp_localize_script( 'ajax-script', 'ajax_object',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'we_value' => 1234 ) );
}

// Same handler function...
add_action( 'wp_ajax_update_cls', 'update_cls' );
function update_cls() {
	global $wpdb;
	$title 	= $_POST["title"];
	$ID 	= $_POST["ID"];
	$whatever = $wpdb->update($wpdb->posts,
					array('post_title'	=> $title ),
					array('ID'			=> $ID ));
 
    echo $title;
	wp_die();
}

// Same handler function for periods...
add_action( 'wp_ajax_update_per', 'update_per' );
function update_per() {
	global $wpdb;
	$title 	= $_POST["title"];
	$ID 	= $_POST["ID"];
	$whatever = $wpdb->update($wpdb->posts,
					array('post_title'	=> $title ),
					array('ID'			=> $ID ));
 
    echo $title;
	wp_die();
}

add_action( 'wp_ajax_update_stu', 'update_stu' );
function update_stu() {
	global $wpdb;
	$title 	= $_POST["title"];
	$ID 	= $_POST["ID"];
	$content_post = get_post($my_postid);
	$content = $content_post->post_content;
	$cls_raw = json_decode($content);
	$cls_raw['cls'] = $_POST["cls"];
	$cls 	 = json_encode($cls_raw);
	$whatever = $wpdb->update($wpdb->posts,
					array('post_title'	=> $title,
						'post_content'	=> $cls ),
					array('ID'			=> $ID ));
    echo get_the_title($_POST["cls"]); 
	wp_die();
}

add_action( 'wp_ajax_update_teacher', 'update_teacher' );
function update_teacher() {
	global $wpdb;
	$title 	= $_POST["title"];
	$ID 	= $_POST["ID"];
	$whatever = $wpdb->update($wpdb->posts,
					array('post_title'	=> $title ),
					array('ID'			=> $ID ));
 
    echo $title;
	wp_die();
}