$(function() {

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



  });
