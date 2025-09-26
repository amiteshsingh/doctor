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

    $(document).on("change keyup",".filterPrescriptionInvoice", function(){
        $('.ajaxSorting').removeClass('fa-sort-up fa-sort-down').addClass('fa-sort');
        ajaxSearching(1, 'prescription-invoice', 'prescription-invoice');
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

    

});


/* 
Reset Filter and Pagination 
@Param first page and which module section and url
@Result Clear all filter binding section
*/
function FilterReset(page, type, url){

    $('.filterHospital').val('').trigger('change');
    $('.filterSpecialization').val('').trigger('change');
    $('.filterDoctor').val('').trigger('change');
    $('.filterPrescriptionInvoice').val('').trigger('change');


    $('.ajaxSorting').removeClass('fa-sort-up fa-sort-down').addClass('fa-sort');

    $('#order_status').val('');
    $('.nav-tabs li').removeClass('active');

    ajaxSearching(page, type, url);
}


/* 
Filter and Pagination using ajay 
@Param current page and which module section
return result binding section
*/
function ajaxSearching(current_page, filtertype, url) {
	var page = current_page;
	var type = filtertype;
	var data = {};
    // alert(page);
    // alert(type);

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
    }else if(type === 'prescription-invoice'){ 
        var url = base_url + url;
        var search = $("#search").val();
		var sortBy = $("#sortBy").val();
		var orderBy = $("#orderBy").val();
        var data = {
            sortBy: sortBy,
			orderBy: orderBy,
			search: search,
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

               


			} else {
				$('#data_listing').html('<div class="block-item"><div class="item fullwidth"><p class="custom_error center mb0">No Data Found!</p></div></div>');

				// $('#data_listing1').html('<div class="block-item"><div class="item fullwidth"><p class="custom_error center mb0">No Data Found!</p></div></div>');
            }
			if (filtertype == 'dashboard') {

			} else {
				$(window).scrollTop(0);
			}
		}
	});
}



