/**
 * Coin admin module backend js function and envent handlers'
 * Author: Clavax Technologies Pvt. Ltd.
 * Varsion: 1.0
 */

var APP_URL = window.location.origin;  

if (APP_URL.includes("127.0.0.1") || APP_URL.includes("localhost")) {
    APP_URL = APP_URL + "/doctor/";
} else {
    APP_URL = APP_URL + "/doctor/";
}

if (!APP_URL.endsWith("/")) {
    var base_url = APP_URL + "/";
} else {
    var base_url = APP_URL;
}

console.log("B URL =>", base_url);

$(document).ready(function(){
    
  
    $(document).on("change keyup",".filterHospital", function(){
        $('.ajaxSorting').removeClass('fa-sort-up fa-sort-down').addClass('fa-sort');
        ajaxSearching(1, 'myhospital', 'myhospital');
    })
    $(document).on("change keyup",".filterSpecialization", function(){
        $('.ajaxSorting').removeClass('fa-sort-up fa-sort-down').addClass('fa-sort');
        ajaxSearching(1, 'specialization', 'specialization');
    })
    $(document).on("change keyup",".filterDoctor", function(){
        $('.ajaxSorting').removeClass('fa-sort-up fa-sort-down').addClass('fa-sort');
        ajaxSearching(1, 'doctor', 'mydoctor');
    })

    
    

    $(".ajaxSorting").click(function(){
        let sort_order = $(this).data("sort_order");
        let sort_by = $(this).data("sort_by");
        let type = $(this).data('type');
        $("#orderBy").val(sort_order);
        $("#sortBy").val(sort_by);   
        $('.ajaxSorting').removeClass('fa-sort-up fa-sort-down').addClass('fa-sort');
        if(typeof sort_order !=='undefined' && sort_order && sort_order.toLowerCase() == 'asc'){
           $(this).data('sort_order', 'desc');
           $(this).removeClass('fa-sort').addClass('fa-sort-up');
        }
        if(typeof sort_order !=='undefined' && sort_order && sort_order.toLowerCase() == 'desc'){
           $(this).data('sort_order', 'asc');
           $(this).removeClass('fa-sort').addClass('fa-sort-down');
        }
        ajaxSearching(1, type, type);
    });

    // $(".ajaxSorting1").click(function(){
    //     let sort_order = $(this).data("sort_order1");
    //     let sort_by = $(this).data("sort_by1");
    //     let type = $(this).data('type');
    //     $("#orderBy1").val(sort_order);
    //     $("#sortBy1").val(sort_by);   
    //     $('.ajaxSorting1').removeClass('fa-sort-up fa-sort-down').addClass('fa-sort');
    //     if(typeof sort_order !=='undefined' && sort_order && sort_order.toLowerCase() == 'asc'){
    //        $(this).data('sort_order1', 'desc');
    //        $(this).removeClass('fa-sort').addClass('fa-sort-up');
    //     }
    //     if(typeof sort_order !=='undefined' && sort_order && sort_order.toLowerCase() == 'desc'){
    //        $(this).data('sort_order1', 'asc');
    //        $(this).removeClass('fa-sort').addClass('fa-sort-down');
    //     }
    //     ajaxSearching(1, type, type);
    // });

});


/* 
Filter and Pagination using ajay 
@Param current page and which module section
return result binding section
*/
function ajaxSearching(current_page, filtertype, url) {
	var page = current_page;
	var type = filtertype;
	var data = {};

    if(type === 'customer_membership'){
        
        var user_id = $("#user_id").val();
        var user_membership = $("#user_membership").val();
        var url = base_url + 'customer/show/'+user_id;
        
        var search = $("#search").val();
		var sortBy = $("#sortBy").val();
		var orderBy = $("#orderBy").val();
        var data = {
			sortBy: sortBy,
			orderBy: orderBy,
            page: page,
            user_membership,user_membership,
        }
        //console.log(data);
    }else if(type === 'myhospital'){ 
        var url = base_url + url;
        var search = $("#search").val();
		var sortBy = $("#sortBy").val();
		var orderBy = $("#orderBy").val();
        var status = $("#status").val();
        var approval_status = $("#approval_status").val();
        var data = {
            sortBy: sortBy,
			orderBy: orderBy,
			search: search,
            status: status,
            approval_status: approval_status,
            page: page,
        }
    }else if(type === 'specialization'){ 
        var url = base_url + url;
        var search = $("#search").val();
		var sortBy = $("#sortBy").val();
		var orderBy = $("#orderBy").val();
        var status = $("#status").val();
        var data = {
            sortBy: sortBy,
			orderBy: orderBy,
			search: search,
            status: status,
            page: page,
        }
    }else if(type === 'doctor'){ 
        var url = base_url + url;
        var search = $("#search").val();
		var sortBy = $("#sortBy").val();
		var orderBy = $("#orderBy").val();
        var status = $("#status").val();
        var approval_status = $("#approval_status").val();
        var data = {
            sortBy: sortBy,
			orderBy: orderBy,
			search: search,
            status: status,
            approval_status: approval_status,
            page: page,
        }
    }else {
        console.log('clothNot url defile here');
        return;
	}
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
		url: url,
		type: 'post',
		dataType: 'json',
		data: data,
		beforeSend: function () {
			$(".loaderDiv").removeClass("hidden");
		},
		success: function (response) {
			$(".loaderDiv").addClass("hidden");
			console.log(response);
			if (response.error == 0) {
				$('#data_listing').empty();
				$("#pagination_data").empty();
				$("#data_listing").html(response.content_html);
				$("#pagination_data").html(response.pagination_html);


                // $('#data_listing1').empty();
				// $("#pagination_data1").empty();
				// $("#data_listing1").html(response.content_html);
				// $("#pagination_data1").html(response.pagination_html);

                // $('#data_listing2').empty();
				// $("#pagination_data2").empty();
				// $("#data_listing2").html(response.content_html1);
				// $("#pagination_data2").html(response.pagination_html1);


			} else {
				$('#data_listing').html('<div class="block-item"><div class="item fullwidth"><p class="custom_error center mb0">No Data Found!</p></div></div>');

				// $('#data_listing1').html('<div class="block-item"><div class="item fullwidth"><p class="custom_error center mb0">No Data Found!</p></div></div>');

				// $('#data_listing2').html('<div class="block-item"><div class="item fullwidth"><p class="custom_error center mb0">No Data Found!</p></div></div>');
				
            }
			if (filtertype == 'dashboard') {

			} else {
				$(window).scrollTop(0);
			}
		}
	});
}

/* 
Reset Filter and Pagination 
@Param first page and which module section and url
@Result Clear all filter binding section
*/
function FilterReset(page, type, url){

    $('.filterHospital').val('').trigger('change');
    $('.filterSpecialization').val('').trigger('change');
    $('.filterDoctor').val('').trigger('change');


    $('.ajaxSorting').removeClass('fa-sort-up fa-sort-down').addClass('fa-sort');

    $('#order_status').val('');
    $('.nav-tabs li').removeClass('active');

    ajaxSearching(page, type, url);
}



// vendor password change

$(document).ready(function () {

    $.validator.addMethod("pwcheck", function(value) {
        return /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,})/.test(value)
        });

    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)
    {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }

    $(document).on("click", "#save-password", function(){
        
        $("#add-password").validate({
            rules: {
                newpassword: {
                    required: true,
                    pwcheck: true,
                    minlength: 7,
                    maxlength: 20
                },
                renewpassword: {
                    required: true,
                    minlength: 7,
                    maxlength: 20,
                    equalTo : "#newpassword"
                },
                

            },
            messages: {
                newpassword:{
                    required: " This field required.",
                    pwcheck: "Your password should be minimum length 7 alphanumeric, include 1 number & 1 alphabet.",
                },
                renewpassword:{
                    required: "This field required.",
                },
            
            
            },
            submitHandler:function(){
                var formData = new FormData($("#add-password")[0]);
                console.log($("#add-password")[0]);
                
                let url = base_url + "vendorservice/passwordchange"
                $.ajax({
                    url: url,
                    data: formData,
                    cache: false,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    beforeSend: function () {
                        $(".loaderDiv").removeClass("hidden");
                    },
                    success: function (response) {
                        $(".loaderDiv").addClass("hidden");
                        console.log(response);
                        if (response.status === 200) {  
                            $.growl.notice({ title: "Password", message: response.msg, size: 'large'});
                            window.setTimeout(function () {
                                window.location.href = base_url + "vendorservice/edit/"+response.vendorid +"/?active=menu4";
                            }, 2000);
                        } else {
                            $.growl.error({ message: response.msg,size: 'large'});
                        }
                    }
                });
            }
        });
    })

});





  /* Video File validaton on category add and update page */
function videoValidation() {
    var fileInput = document.getElementById('video');
    var filePath = fileInput.value;
    // Allowing file type
    var allowedExtensions = /(\.mp4|\.jpg|\.jpeg|\.png)$/i;
    if (!allowedExtensions.exec(filePath)) {
        $.growl.error({ message: 'Invalid file type', size: 'large' });
        fileInput.value = '';
        return false;
    } else {
        if (fileInput.files && fileInput.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {

                //console.log(e.target.result);
                var images_name = e.target.result;
                var fileType = images_name.split("/");
                //console.log(fileType[0]);
                //console.log(fileType[1]);

                if(fileType[0]==='data:image'){
                    
                    $("#remove-upload").empty();
                    $("#profile-upload").html("");
                    $("#profile-upload").append('<div id =""class="profile-pic"><img id="video-preview" src="'+e.target.result+'" /></div>');
                    //$("#video-preview").css('display', 'block');
                    //$("#video-preview").attr('src', e.target.result);
                }else if(fileType[0]==='data:video'){
                    $("#remove-upload").empty();
                    $("#profile-upload").html("");
                    $("#profile-upload").append('<video width="320" height="240" controls><source  src="'+e.target.result+'" type="video/mp4"></video>');
                    
                }

                
            };
            reader.readAsDataURL(fileInput.files[0]);
        }
    }
}


  /* Video File validaton on category add and update page */
  function videosValidation($id) {
    var fileInput = document.getElementById('video'+$id);
    var filePath = fileInput.value;
    // Allowing file type
    var allowedExtensions = /(\.mp4)$/i;
    if (!allowedExtensions.exec(filePath)) {
        $.growl.error({ message: 'Invalid file type', size: 'large' });
        fileInput.value = '';
        return false;
    } else {
        if (fileInput.files && fileInput.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {

                //console.log(e.target.result);
                var images_name = e.target.result;
                var fileType = images_name.split("/");
                //console.log(fileType[0]);
                //console.log(fileType[1]);

                if(fileType[0]==='data:video'){
                    
                    $("#remove-upload"+$id).empty();
                    $("#profile-upload"+$id).html("");
                    $("#profile-upload"+$id).append('<video width="320" height="240" controls><source  src="'+e.target.result+'" type="video/mp4"></video>');
                }

                
            };
            reader.readAsDataURL(fileInput.files[0]);
        }
    }
}




$(document).ready(function(){
    
    $(document).on("change", "#information_type", function(){
        var information_type = $("#information_type").val();
        if(information_type==1){
            $("#general_video").css("display", "block");
        }
        if(information_type==0){
            $("#general_video").css("display", "none");
        }
    });


    //User Notification status change TRUE/FALSE
    $(".user_notification").on("change", this, function(){

        notification_id = $(".user_notification_id", this).val();
        vendorID = $(".user_ID", this).val();
        
        send_email = $("#send_email", this).val();
        send_sms = $("#send_sms", this).val();
        send_push = $("#send_push", this).val();

        //alert(user_notification_id);
        //alert(vendorID);

        if($("#send_email").is(':checked')){
            send_email = 1;
        }else{
            send_email = 0;
        }
        if($("#send_sms").is(':checked')){
            send_sms = 1;
        }else{
            send_sms = 0;
        }
        if($("#send_push").is(':checked')){
            send_push = 1;
        }else{
            send_push = 0;
        }
        //alert(send_email);
        //alert(send_sms);
        //alert(send_push);
        
        let url = base_url + "vendorservice/notification/"+vendorID;
        formData = "id="+notification_id+'&send_email='+send_email+'&send_sms='+send_sms+'&send_push='+send_push;
        $.ajax({
            url: url,
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            type: 'get',
            beforeSend: function () {
                $(".loaderDiv").removeClass("hidden");
            },
            success: function (response) {
                $(".loaderDiv").addClass("hidden");
                console.log(response);

                if (response.status === 200) {  
                    $.growl.notice({ tittle: "Notification", message: response.msg, size: 'large'});
                } else {
                    $.growl.error({ message: response.msg,size: 'large'});
                }
            }
        });
    })


    $('#category_id').on('change', function () {
        $("#subcategory_id").html('');
        category_id = $("#category_id").val();
       
        $.ajax({
            url: base_url + "general_information/fetch-child-category",
            type: "GET",
            data: {
                category_id: category_id,
            },
            dataType: 'json',
            success: function (result) {
                console.log(result);
                
                $("#subcategory_id").html('<option value="0">Select Child Category</option>');
                $.each(result, function (key, value) {
                    $("#subcategory_id").append('<option value="' + value.id + '">' + value.name + '</option>');
                });
            }
        });

    });


    $(document).on("click",".showOrdIdModel", function () {

        let id = $(this).data('id');
        $('#selectModal1').modal('show');
        $("#order_id").val(id);
        $('#service_partner_id').html('');
        $.ajax({
            url: base_url + "order/serviceprovider",
            type: "POST",
            data: {
                order_id: id,
            },
            dataType: 'json',
            beforeSend: function () {
                $(".loaderDiv").removeClass("hidden");
            },
            success: function (response) {
                $(".loaderDiv").addClass("hidden");
                console.log(response);

                $('#service_partner_id').html('<option value="">Select Service Provider</option>');
                $.each(response, function (key, value) {
                    $('#service_partner_id').append('<option value="' + value
                        .id + '">' + value.first_name + '</option>');
                });
                
              
            }
        });

    })
    
    $('#btnAssignServiceProvider').on("click", function () {
       
        let order_id = $("#order_id").val();
        let service_partner_id = $("#service_partner_id").val();
        $.ajax({
            url: base_url + "order/assignprovider",
            type: "POST",
            data: {
                order_id: order_id, service_partner_id: service_partner_id,
            },
            dataType: 'json',
            beforeSend: function () {
                $(".loaderDiv").removeClass("hidden");
            },
            success: function (response) {
                $(".loaderDiv").addClass("hidden");
                if (response.status === 200) {
                    $("#assignServiceProvider")[0].reset();
                    $.growl.notice({ title: "Assign Service Provider", message: response.msg, size: 'large' });
                    window.setTimeout(function () {
                        $('#selectModal1').modal('hide');
                    }, 2000);
                    window.location.href = base_url + "order";
                } else {
                    $.growl.error({ message: response.msg, size: 'large' });
                }
              
            }
        });

    })


});

