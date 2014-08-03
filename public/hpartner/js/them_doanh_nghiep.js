/* [ ---- Gebo Admin Panel - validation ---- ] */

	$(document).ready(function() {
		//* validation with tooltips
		gebo_validation.ttip();
		//* regular validation
		gebo_validation.reg();
	});
	
	//* validation
	gebo_validation = {
		ttip: function() {
			var ttip_validator = $('.form_validation_ttip').validate({
				onkeyup: false,
				errorClass: 'error',
				validClass: 'valid',
				highlight: function(element) {
					$(element).closest('div').addClass("f_error");
				},
				unhighlight: function(element) {
					$(element).closest('div').removeClass("f_error");
				},
                rules: {
					/* new js rule for payoo admin */
					business_name: {required:true, minlength:3},
					address: {required:true, minlength: 3},
					phone: {required: true, minlength: 10, number: true},
					email: {required: true, email: true},
					website: {url: true},
					general_information: {required: true, minlength:3},
					/* END new js rule for payoo admin */					
				},
				invalidHandler: function(form, validator) {
					$.sticky("Có lỗi trong thông tin nhập vào. Xin kiểm tra và nhập lại", {autoclose : 5000, position: "top-right", type: "st-error" });
				},
				errorPlacement: function(error, element) {
					// Set positioning based on the elements position in the form
					var elem = $(element);
					
					// Check we have a valid error message
					if(!error.is(':empty')) {
						if( (elem.is(':checkbox')) || (elem.is(':radio')) ) {
							// Apply the tooltip only if it isn't valid
							elem.filter(':not(.valid)').parent('label').parent('div').find('.error_placement').qtip({
								overwrite: false,
								content: error,
								position: {
									my: 'left bottom',
									at: 'center right',
									viewport: $(window),
									adjust: {
										x: 6
									}
								},
								show: {
									event: false,
									ready: true
								},
								hide: false,
								style: {
									classes: 'ui-tooltip-red ui-tooltip-rounded' // Make it red... the classic error colour!
								}
							})
							// If we have a tooltip on this element already, just update its content
							.qtip('option', 'content.text', error);
						} else {
							// Apply the tooltip only if it isn't valid
							elem.filter(':not(.valid)').qtip({
								overwrite: false,
								content: error,
								position: {
									my: 'bottom left',
									at: 'top right',
									viewport: $(window),
                                    adjust: { x: -8, y: 6 }
								},
								show: {
									event: false,
									ready: true
								},
								hide: false,
								style: {
									classes: 'ui-tooltip-red ui-tooltip-rounded' // Make it red... the classic error colour!
								}
							})
							// If we have a tooltip on this element already, just update its content
							.qtip('option', 'content.text', error);
						};
                        
					}
					// If the error is empty, remove the qTip
					else {
						if( (elem.is(':checkbox')) || (elem.is(':radio')) ) {
							elem.parent('label').parent('div').find('.error_placement').qtip('destroy');
						} else {
							elem.qtip('destroy');
						}
					}
				},
				success: $.noop // Odd workaround for errorPlacement not firing!
			})
		},
        reg: function() {
            reg_validator = $('.form_validation_reg').validate({
				onkeyup: false,
				errorClass: 'error',
				validClass: 'valid',
				highlight: function(element) {
					$(element).closest('div').addClass("f_error");
				},
				unhighlight: function(element) {
					$(element).closest('div').removeClass("f_error");
				},
                errorPlacement: function(error, element) {
                    $(element).closest('div').append(error);
                },
                rules: {
				},
                invalidHandler: function(form, validator) {
					$.sticky("There are some errors. Please corect them and submit again.", {autoclose : 5000, position: "top-right", type: "st-error" });
				}
            })
        }
	};