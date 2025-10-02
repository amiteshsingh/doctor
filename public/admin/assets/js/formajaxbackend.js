$(function() {

    /**
     * Validate Hospital Form add, edit.
     */

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
     $(document).on("click", "#save_hospital", function(){
        $("#hospital_form").validate({
            rules: {
                name: {
                    required: true
                },
               
                status: {
                    required: true
                },
        
            },
            messages: {
                name: {
                    required: "This field required."
                },
               
                status: {
                    required: "This field required."
                }

            },
            submitHandler: function () {
                var formData = new FormData($("#hospital_form")[0]);
                let url = base_url + "hospital/add";
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
                        if (response.status === 200) {
                            // alert(response.status);
                            $.jGrowl(response.msg, { header: "Hospital", theme: 'success-theme' });

                            window.setTimeout(function () {
                                window.location.href = base_url + "hospital";
                            }, 2000);
                        } else {
                            $.jGrowl(response.msg, { header: "Error", theme: 'error-theme' });
                        }
                    }
                });
            }
        });
    });



    /**
     * Validate Hospital Form add, edit.
     */
     $(document).on("click", "#save_specialization", function(){
        $("#specialization_form").validate({
            rules: {
                name: {
                    required: true
                },
               
                status: {
                    required: true
                },
        
            },
            messages: {
                name: {
                    required: "This field required."
                },
               
                status: {
                    required: "This field required."
                }

            },
            submitHandler: function () {
                var formData = new FormData($("#specialization_form")[0]);
                let url = base_url + "specialization/add";
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
                        if (response.status === 200) {
                            // alert(response.status);
                            $.jGrowl(response.msg, { header: "Specialization", theme: 'success-theme' });

                            window.setTimeout(function () {
                                window.location.href = base_url + "specialization";
                            }, 2000);
                        } else {
                            $.jGrowl(response.msg, { header: "Error", theme: 'error-theme' });
                        }
                    }
                });
            }
        });
    });

    /**
     * Validate Hospital Form add, edit.
     */
     $(document).on("click", "#save_hospital_specialization", function(){
        
        $("#hospital_specialization_form").validate({  

            rules: {
                specialization_ids: {
                    required: true
                }
            },
            messages: {
                specialization_ids: {
                    required: "This field required."
                }
            },
            submitHandler: function () {
                var formData = new FormData($("#hospital_specialization_form")[0]);
                let url = base_url + "hospital/hospital_specialization";
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
                        // console.log(response);
                        if (response.status === 200) {
                            // alert(response.status);
                            $.jGrowl(response.msg, { header: "Hospital Specialization", theme: 'success-theme' });

                            window.setTimeout(function () {
                                window.location.href = base_url + `hospital/add?id=${response.hospital_id}#basictab2`;
                            }, 2000);
                        } else {
                            $.jGrowl(response.msg, { header: "Error", theme: 'error-theme' });
                        }
                    }
                });
            }
        });
    });


       /**
     * Validate doctor Form add, edit for admin panel.
     */
     $(document).on("click", "#save_doctor", function(){
        $("#doctor_form").validate({
            rules: {
                name: {
                    required: true
                },
               
                status: {
                    required: true
                },
        
            },
            messages: {
                name: {
                    required: "This field required."
                },
                status: {
                    required: "This field required."
                }
            },
            submitHandler: function () {
                var formData = new FormData($("#doctor_form")[0]);
                let url = base_url + "doctor/add";
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
                        if (response.status === 200) {
                            // alert(response.status);
                            $.jGrowl(response.msg, { header: "Doctor", theme: 'success-theme' });

                            window.setTimeout(function () {
                                window.location.href = base_url + "doctor";
                            }, 2000);
                        } else {
                            $.jGrowl(response.msg, { header: "Error", theme: 'error-theme' });
                        }
                    }
                });
            }
        });
    });

        /**
     * Validate save_doctor_specialization Form add, edit.
     */
    $(document).on("click", "#save_doctor_specialization", function(){
        
        $("#doctor_specialization_form").validate({  

            rules: {
                specialization_ids: {
                    required: true
                }
            },
            messages: {
                specialization_ids: {
                    required: "This field required."
                }
            },
            submitHandler: function () {
                var formData = new FormData($("#doctor_specialization_form")[0]);
                let url = base_url + "doctor/doctor_specialization";
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
                        // console.log(response);
                        if (response.status === 200) {
                            // alert(response.status);
                            $.jGrowl(response.msg, { header: "Doctor Specialization", theme: 'success-theme' });

                            window.setTimeout(function () {
                                window.location.href = base_url + `doctor/add?id=${response.doctor_id}#basictab2`;
                            }, 2000);
                        } else {
                            $.jGrowl(response.msg, { header: "Error", theme: 'error-theme' });
                        }
                    }
                });
            }
        });
    });


      /**
     * Validate save_doctor_location Form add, edit.
     */
    $(document).on("click", "#save_doctor_location", function () {

        $("#doctor_location_form").validate({
            rules: {
                practice_name: { required: true },
                address: { required: true },
                city: { required: true },
                state: { required: true },
                zip_code: { required: true },
                location_phone: { required: true },
                degree_type: { required: true },

                'languages[]': { required: true },
            },
            messages: {
                practice_name: { required: "This field is required." },
                address: { required: "This field is required." },
                city: { required: "This field is required." },
                state: { required: "This field is required." },
                zip_code: { required: "This field is required." },
                location_phone: { required: "This field is required." },
                degree_type: { required: "This field is required." },
         
                'languages[]': { required: "Please select at least one language." },
            },
            submitHandler: function () {
                var formData = new FormData($("#doctor_location_form")[0]);
                let url = base_url + "doctor/doctor_location";

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
                        if (response.status === 200) {
                            $.jGrowl(response.msg, { header: "Doctor Info", theme: 'success-theme' });
                            window.setTimeout(function () {
                                window.location.href = base_url + `doctor/add?id=${response.doctor_id}#basictab3`;
                            }, 2000);
                        } else {
                            $.jGrowl(response.msg, { header: "Error", theme: 'error-theme' });
                        }
                    }
                });
            }
        });
    });

    // Validate doctor_availability_form add, edit
    $(document).on("click", "#save_doctor_availability", function () {
        $("#doctor_availability_form").validate({
            ignore: [], // hidden fields भी validate होंगे
            rules: {
                // हम यहाँ static rules नहीं देंगे, बल्कि नीचे dynamic check करेंगे
            },
            messages: {},
            submitHandler: function () {
                let valid = true;

                // Loop through all slots and check start/end time
                $("#doctor_availability_form .slot").each(function () {
                    let start = $(this).find("input[name*='[start_time]']").val();
                    let end   = $(this).find("input[name*='[end_time]']").val();

                    // अगर user ने कोई एक भरा और दूसरा खाली छोड़ दिया
                    if ((start && !end) || (!start && end)) {
                        valid = false;
                        $(this).find("input").addClass("error");
                    } else {
                        $(this).find("input").removeClass("error");
                    }
                });

                if (!valid) {
                    $.jGrowl("Please fill both Start and End time for each slot!", { header: "Validation Error", theme: 'error-theme' });
                    return false;
                }

                // ✅ अगर सब ठीक है तो Ajax submit होगा
                var formData = new FormData($("#doctor_availability_form")[0]);
                let url = base_url + "mydoctor/doctor_availability";

                $.ajax({
                    url: url,
                    type: "POST",
                    data: formData,
                    cache: false,
                    processData: false,
                    contentType: false,
                    beforeSend: function () {
                        $(".loaderDiv").removeClass("hidden");
                    },
                    success: function (response) {
                        $(".loaderDiv").addClass("hidden");

                        if (response.status === 200) {
                            $.jGrowl(response.msg, { header: "Availability Saved", theme: 'success-theme' });

                            // Redirect or jump to another tab
                            setTimeout(function () {
                                window.location.href = base_url + `mydoctor/add?id=${response.doctor_id}#basictab4`;
                            }, 2000);
                        } else {
                            $.jGrowl(response.msg, { header: "Error", theme: 'error-theme' });
                        }
                    },
                    error: function () {
                        $(".loaderDiv").addClass("hidden");
                        $.jGrowl("Something went wrong!", { header: "Error", theme: 'error-theme' });
                    }
                });
            }
        });
    })





  });
