$(function() {


    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    /**
     * Validate Hospital Form add, edit.
     */
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
                let url = base_url + "myhospital/add";
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
                                window.location.href = base_url + "myhospital";
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
                let url = base_url + "myhospital/hospital_specialization";
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
                                window.location.href = base_url + `myhospital/add?id=${response.hospital_id}#basictab2`;
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
                let url = base_url + "mydoctor/add";
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
                            $.jGrowl(response.msg, { header: "Doctor", theme: 'success-theme' });

                            // redirect to edit mode if redirect_url is available
                            if (response.redirect_url) {
                                window.setTimeout(function () {
                                    window.location.href = response.redirect_url;
                                }, 2000);
                            } else {
                                window.setTimeout(function () {
                                    window.location.href = base_url + "mydoctor";
                                }, 2000);
                            }
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
                let url = base_url + "mydoctor/doctor_specialization";
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
                                window.location.href = base_url + `mydoctor/add?id=${response.doctor_id}#basictab2`;
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
                let url = base_url + "mydoctor/doctor_location";

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
                                window.location.href = base_url + `mydoctor/add?id=${response.doctor_id}#basictab3`;
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
    // $(document).on("click", "#save_doctor_availability", function () {
    //     $("#doctor_availability_form").validate({
    //         rules: {
    //             // Add dynamic rules if needed
    //             // 'availability[Monday][start_time]': { required: true },
    //             // 'availability[Monday][end_time]': { required: true },
    //             // etc...
    //         },
    //         messages: {
    //             // Dynamic messages can be added if required
    //         },
    //         submitHandler: function () {
    //             var formData = new FormData($("#doctor_availability_form")[0]);
    //             let url = base_url + "mydoctor/doctor_availability";

    //             $.ajax({
    //                 url: url,
    //                 type: "POST",
    //                 data: formData,
    //                 cache: false,
    //                 processData: false,
    //                 contentType: false,
    //                 beforeSend: function () {
    //                     $(".loaderDiv").removeClass("hidden");
    //                 },
    //                 success: function (response) {
    //                     $(".loaderDiv").addClass("hidden");

    //                     if (response.status === 200) {
    //                         $.jGrowl(response.msg, { header: "Availability Saved", theme: 'success-theme' });

    //                         // Redirect or jump to another tab
    //                         setTimeout(function () {
    //                             window.location.href = base_url + `mydoctor/add?id=${response.doctor_id}#basictab4`;
    //                         }, 2000);
    //                     } else {
    //                         $.jGrowl(response.msg, { header: "Error", theme: 'error-theme' });
    //                     }
    //                 },
    //                 error: function (xhr) {
    //                     $(".loaderDiv").addClass("hidden");
    //                     $.jGrowl("Something went wrong!", { header: "Error", theme: 'error-theme' });
    //                 }
    //             });
    //         }
    //     });
    // });


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
    });




    $(document).on("click", "#save_invoice", function(){
        $("#invoice_form").validate({
            rules: {
                doctor_id: {
                    required: true,
                    number: true
                },
                hospital_clinic_name: {
                    required: true
                },
                consultation_fee: {
                    required: true,
                    number: true
                },
            },
            messages: {
                doctor_id: {
                    required: "Doctor ID is required.",
                    number: "Please enter a valid number."
                },
                hospital_clinic_name: {
                    required: "Hospital/Clinic Name is required."
                },
                consultation_fee: {
                    required: "Consultation Fee is required.",
                    number: "Please enter a valid amount."
                },
            },
            submitHandler: function () {
                var formData = new FormData($("#invoice_form")[0]);
                let url = base_url + "invoice-master/add";
                $.ajax({
                    url: url,
                    data: formData,
                    cache: false,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    beforeSend: function () {
                        $(".loaderDiv").removeClass("hidden"); // loader show
                    },
                    success: function (response) {
                        $(".loaderDiv").addClass("hidden"); // loader hide
                        if (response.status === 200) {
                            $.jGrowl(response.msg, { header: "Invoice Master", theme: 'success-theme' });

                            setTimeout(function(){
                                window.location.href = base_url + "invoice-master";
                            }, 2000);
                        } else {
                            $.jGrowl(response.msg, { header: "Invoice", theme: 'error-theme' });
                        }
                    },
                    error: function () {
                        $(".loaderDiv").addClass("hidden");
                        $.jGrowl("Something went wrong.", { header: "Error", theme: 'error-theme' });
                    }
                });
            }
        });
    });



    $(document).on("click", "#save_prescription_invoice", function () {
        $("#prescription_invoice_form").validate({
            rules: {
                invoice_master_id: {
                    required: true,
                    number: true
                },
                invoice_number: {
                    required: true
                },
                patient_name: {
                    required: true
                },
                patient_address: {
                    required: true
                },
                patient_phone_no: {
                    required: true,
                    digits: true,
                    minlength: 10,
                    maxlength: 15
                },
            },
            messages: {
                invoice_master_id: {
                    required: "Invoice Master ID is required.",
                    number: "Please enter a valid number."
                },
                patient_name: {
                    required: "Patient Name is required."
                },
                patient_address: {
                    required: "Patient Address is required."
                },
                patient_phone_no: {
                    required: "Patient Phone Number is required.",
                    digits: "Please enter a valid phone number.",
                    minlength: "Phone number must be at least 10 digits.",
                    maxlength: "Phone number cannot exceed 15 digits."
                },
            },
            submitHandler: function () {
                var formData = new FormData($("#prescription_invoice_form")[0]);
                let url = base_url + "prescription-invoice/add";
                $.ajax({
                    url: url,
                    data: formData,
                    cache: false,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    beforeSend: function () {
                        $(".loaderDiv").removeClass("hidden"); // loader show
                    },
                    success: function (response) {
                        $(".loaderDiv").addClass("hidden"); // loader hide
                        if (response.status === 200) {
                            $.jGrowl(response.msg, { header: "Prescription Invoice", theme: 'success-theme' });

                            setTimeout(function () {
                                window.location.href = base_url + "prescription-invoice";
                            }, 2000);
                        } else {
                            $.jGrowl(response.msg, { header: "Prescription Invoice", theme: 'error-theme' });
                        }
                    },
                    error: function () {
                        $(".loaderDiv").addClass("hidden");
                        $.jGrowl("Something went wrong.", { header: "Error", theme: 'error-theme' });
                    }
                });
            }
        });
    });





  });
