/*
+--------------------------------------------------+
	detect cehckbox change event and call ajax function
	@author Cloudwebs
+--------------------------------------------------+
*/
$("input[type=checkbox]").on( "change", function()
{
   showLoader();
   var name = $(this).attr('name');
   var checked = ($(this).is(":checked"))? 1:0;
   var val = '';

   if(name == "viewall" || name == "addall" || name == "editall" || name == "deleteall" )
   {
	   $('.'+$(this).attr('class')+'.'+$(this).attr('data-')).each(function(){
			$(this).prop('checked', checked);
			val += $(this).val()+'|'+checked+'||';
		});

		updatePermission(val.slice(0, -2),name,$(this).attr('class'));
   }
   else if(name == "allall")
   {
	   $('.'+$(this).attr('class')).prop('checked', checked);
	   $('.'+$(this).attr('class')+'-tr').each(function(){
			val += $(this).attr('data-')+'|'+checked+'||';
		});
		updatePermission(val.slice(0, -2),name,$(this).attr('class'));
   }
   else if(name == "all")
   {
	   var obj = $(this).parents('tr');
		$(obj).find('td').each(function(){
		   $(this).find('input:checkbox').prop('checked', checked);
		});
		val = $(obj).attr('data-')+'|'+checked;
	    var cls = $(this).attr('class');
		updatePermission(val,name,cls.substring(0,cls.indexOf(" ",0)));
   }
   else
   {
	    var cls = $(this).attr('class');
		updatePermission($(this).val()+'|'+checked,name,cls.substring(0,cls.indexOf(" ",0)));
   }
   hideLoader();
	return false;
});

/*
+--------------------------------------------------+
	ajax function to update user permissions
	@author Cloudwebs

+--------------------------------------------------+
*/
function updatePermission(val,type,cls)
{
	$.ajaxSetup({
	    headers: {
		        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		    }
		});

	$.ajax({
    	url: url+'/admin/changePermission',
        type: "POST",
        data: {val : val, type : type},
        dataType: 'json',
		error: function()
		{
			//showToast("Invalid mobile Number.", "warning");
			//$(".send-otp-span").removeClass("d-none");
			//$(".send-otp-loader").addClass("d-none");
		},
		statusCode: {
			409: function( data ) {
				showToast( data.responseJSON.error.message );
			},
			500: function( data ) {
				showToast( data.responseJSON.error.message );
			},
			200: function( data ) {
				if( data.type == "success" ){
                    showToast( data.msg );
                    parentCheck(cls);
                }
			},
		},
    });
}

/*
+--------------------------------------------------+
	function checks if all check box of parents are checked
	@author Cloudwebs
	return true if all checked
+--------------------------------------------------+
*/
function parentCheck(cls)
{
	var is_view_checked = true;
	var is_add_checked = true;
	var is_edit_checked = true;
	var is_delete_checked = true;
	var is_row_checked = true;

	$('.'+cls+'-tr').each(function(){
		is_row_checked = true;
		$(this).find('input:checkbox').each(function(){
			var name =  $(this).attr('name');
			if(name == "all")
				(is_row_checked)?$(this).prop('checked',true):$(this).prop('checked',false);
			else if(!$(this).is(":checked"))
			{
				is_row_checked = false;
				if(name == "view")
					is_view_checked = false;
				else if(name == "add")
					is_add_checked = false;
				else if(name == "edit")
					is_edit_checked = false;
				else if(name == "delete")
					is_delete_checked = false;
			}
		});
	});

	(is_view_checked)?$($('.'+cls+'[name="viewall"]')).prop('checked',true):$($('.'+cls+'[name="viewall"]')).prop('checked',false);
	(is_add_checked)?$($('.'+cls+'[name="addall"]')).prop('checked',true):$($('.'+cls+'[name="addall"]')).prop('checked',false);
	(is_edit_checked)?$($('.'+cls+'[name="editall"]')).prop('checked',true):$($('.'+cls+'[name="editall"]')).prop('checked',false);
	(is_delete_checked)?$($('.'+cls+'[name="deleteall"]')).prop('checked',true):$($('.'+cls+'[name="deleteall"]')).prop('checked',false);

	if(is_view_checked == true && is_add_checked == true && is_edit_checked == true && is_delete_checked == true )
		$($('.'+cls+'[name="allall"]')).prop('checked', true);
	else
		$($('.'+cls+'[name="allall"]')).prop('checked', false);
}


/*
+---------------------------------------------+
	show preloader at listing table.
+---------------------------------------------+
*/
function showLoader()
{
	$('.pre_loader').show();
}

/*
+---------------------------------------------+
	show preloader at listing table.
+---------------------------------------------+
*/
function hideLoader()
{
	$('.pre_loader').hide();
}

/*
+---------------------------------------------+
	AutoHide notification div. function call
	from timeout every 7 seconds.
+---------------------------------------------+
*/
function hideNotification()
{
	$('.notification').slideUp();
}

$(document).ready(function () {
	let all = 0;
	const type = ['v','a','e','d'];

	$.each(userArr,function(i,v	){
		$.each(type , function (key,value) {
			var totalCheckbox = $(`.user-${v}.${value}`).length;
			var CheckedCheckbox = $(`.user-${v}.${value}:checked`).length;
			if(totalCheckbox == CheckedCheckbox){
				all++;
				$(`input[type="checkbox"][data-class="user-${v}"][data-="${value}"]`).prop('checked',true);
			}
		});
		if($(`input[type="checkbox"][data-class="user-${v}"][data-class="user-${v}"]:checked`).length == 4){
			$(`input[type="checkbox"][name="allall"][data-class="user-${v}"]`).prop('checked',true);
		}
	});
});

$(document).on('click','#vtab-option .tabs-left li',function(){
	$('#vtab-option .tabs-left li').removeClass('active');
	$(this).addClass('active');
});
