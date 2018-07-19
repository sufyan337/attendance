function save_cls(ID,title){
jQuery(document).ready(function($) {
	var data = {
		'action': 'update_cls',
		'ID'	: ID ,
		'title' : title     // We pass php values differently!
	};
	// We can also pass the url value separately from ajaxurl for front end AJAX implementations
	jQuery.post(ajax_object.ajax_url, data, function(response) {
		$('#cls'+ID).html(response);
		$('#btn'+ID).attr('class','ui mini primary button');
		$('#btn'+ID).html('Edit');
	    $('#btn'+ID).attr('onclick','edit_cls('+ID+',\''+title+'\')');

	});
});
}

function save_per(ID,title){
jQuery(document).ready(function($) {
	var data = {
		'action': 'update_per',
		'ID'	: ID ,
		'title' : title     // We pass php values differently!
	};
	// We can also pass the url value separately from ajaxurl for front end AJAX implementations
	jQuery.post(ajax_object.ajax_url, data, function(response) {
		$('#per'+ID).html(response);
		$('#btn'+ID).attr('class','ui mini primary button');
		$('#btn'+ID).html('Edit');
	    $('#btn'+ID).attr('onclick','edit_per('+ID+',\''+title+'\')');

	});
});
}

function save_stu(ID,title,cls){
jQuery(document).ready(function($) {
	$('#btn'+ID).attr('class','ui mini green loading button');
	var data = {
		'action': 'update_stu',
		'ID'	: ID ,
		'title' : title,
		'cls'	: cls     // We pass php values differently!
	};
	// We can also pass the url value separately from ajaxurl for front end AJAX implementations
	jQuery.post(ajax_object.ajax_url, data, function(response) {
		$('#btn'+ID).attr('class','ui mini primary button');
		$('#stu'+ID).html(title);
		var cls_new = $('#cls'+ID).val();
		$('#stu_cls'+ID).html(response);
		$('#btn'+ID).html('Edit');
	    $('#btn'+ID).attr('onclick','edit_stu('+ID+',\''+title+'\',\''
	+cls+'\')');
	});
});
}

function save_teacher(ID,title){
jQuery(document).ready(function($) {
	var data = {
		'action': 'update_teacher',
		'ID'	: ID ,
		'title' : title     // We pass php values differently!
	};
	// We can also pass the url value separately from ajaxurl for front end AJAX implementations
	jQuery.post(ajax_object.ajax_url, data, function(response) {
		$('#teacher'+ID).html(response);
		$('#btn'+ID).attr('class','ui mini primary button');
		$('#btn'+ID).html('Edit');
	    $('#btn'+ID).attr('onclick','edit_teacher('+ID+',\''+title+'\')');

	});
});
}