$(document).ready(function () {
    /*  $(".textWithSpace").keypress(function () {
          debugger;
          if (event.keyCode == 8 || event.keyCode == 32) return true;
          if (!((event.keyCode >= 65 && event.keyCode <= 90) || (event.keyCode >= 97 && event.keyCode <= 122))) return false;
      });*/

    $('.textWithSpace').keypress(function (e) {
        var specialKeys = new Array();
        specialKeys.push(8);
        specialKeys.push(32);
        var keyCode = e.which ? e.which : e.keyCode
        var ret = ((keyCode >= 65 && keyCode <= 90) || (keyCode >= 97 && keyCode <= 122) || specialKeys.indexOf(keyCode) != -1);
        return ret;
    });

    $(".numberOnly").keypress(function () {
        if (event.keyCode == 8) return true;
        if (!(event.keyCode >= 48 && event.keyCode <= 57)) return false;
    });


    $(".amount").keypress(function () {
        if (event.keyCode == 8 || event.keyCode == 46) return true;
        if (!(event.keyCode >= 48 && event.keyCode <= 57)) return false;
    });
    $("form").submit(function (event) {

        var $btn = $(document.activeElement);
        if ($btn.attr('formnovalidate') != 'formnovalidate')
        // return validate(this);
            var chkvalidate = validate(this);
        if (chkvalidate == true) {
            //$('#main_pageloader').show();
            //ShowOnpageLoopader();
            btnChange($btn);
            var frmId = $(this).attr('id');
            if (frmId == 'frmReg') {
                $('#myModal_varify_otp_email').show();
            } else if (frmId == 'frmupdate') {
                //submitForm();
                $('#myModal_PaymentUser').show();
            } else {
                $("form").submit();
            }
            event.preventDefault();
        } else {
            return false;
        }
    });
    $('.required').focusout(function () {
        if ($(this).val().length == 0) {
            $(this).addClass('errorClass');
            return false;
        } else {
            $(this).removeClass('errorClass');
        }
    });

    $('.requiredDD').focusout(function () {
        if ($(this).val() == 0) {
            $(this).addClass('errorClass');
            return false;
        } else {
            $(this).removeClass('errorClass');
        }
    })
});

function btnChange(dis) {
    setTimeout(function () {
        $(dis).removeClass("onclic");
        $(dis).addClass("mdi-check", 450, callback(dis));
        $('#main_pageloader').hide();
    }, 100);
}

function callback(dis) {
    setTimeout(function () {
        $(dis).removeClass("mdi-check");
    }, 100);
}

function CheckLen(input, maxlen) {
    if ((input.value.length > maxlen - 1 && event.keyCode != 8) || event.keyCode == 86)
        return false;
}

function validate(frm) {
    var frmId = $(frm).attr('id');
    //frmId=frm;
    var ret = true;

    function setError(dispMsg) {
        $(this).next('.errorText').remove();
        $(this).addClass('errorClass');
        // $(this).after("<p class='errorText'>" + dispMsg + "</p>");
        // event.preventDefault();
        ret = false;
    }

    function unsetError() {
        $(this).next('.errorText').remove();
        $(this).removeClass('errorClass');
    }

    $("#" + frmId + " .required").each(function () {
        if ($(this).val().length == 0)
            setError.call(this, "Please Enter " + $(this).attr('placeholder'));
        else
            unsetError.call(this);
    });

    $("#" + frmId + " .requiredDD").each(function () {
        if ($(this).val() == 0) {
            var label = $("label[for='" + $(this).attr('id') + "']");
            setError.call(this, "Please Select " + label.html());
        } else
            unsetError.call(this);
    });

    if (ret != false) {
        $("#" + frmId + " .contact").each(function () {
            if ($(this).val().length != 10)
                setError.call(this, "Please Enter a Valid Contact");
            else
                unsetError.call(this);
        });


        $("#" + frmId + " .email").each(function () {
            var re = /^[-a-z0-9~!$%^&*_=+}{\'?]+(\.[-a-z0-9~!$%^&*_=+}{\'?]+)*@([a-z0-9_][-a-z0-9_]*(\.[-a-z0-9_]+[a-z][a-z])|([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}))(:[0-9]{1,5})?$/i;
            if (!re.test($(this).val()))
                setError.call(this, "Invalid Email");
            else
                unsetError.call(this);
        });


        $("#" + frmId + " .amount").each(function () {
            var re = /^[0-9]+(\.[0-9]+)?$/;
            if (!re.test($(this).val()))
                setError.call(this, "Invalid Amount");
            else
                unsetError.call(this);
        });

        $("#" + frmId + " .name").each(function () {
            if ($(this).val().length < 2)
                setError.call(this, "Name must have atleast 2 characters");
            else
                unsetError.call(this);
        });

        $("#" + frmId + " .zip").each(function () {
            if ($(this).val().length != 6)
                setError.call(this, "ZIP Code must have 6 digits");
            else
                unsetError.call(this);
        });

        $("#" + frmId + " .pan").each(function () {
            var re = /^[A-Z]{5}[0-9]{4}[A-Z]{1}$/;
            if (!re.test($(this).val()))
                setError.call(this, "PAN No. format is invalid");
            else
                unsetError.call(this);
        });

        $("#" + frmId + " .tinno").each(function () {
            var re = /^[0-9]{11}[A-Z]{1}$/;
            if (!re.test($(this).val()))
                setError.call(this, "TIN No. format is invalid");
            else
                unsetError.call(this);
        });

        $("#" + frmId + " .cstno").each(function () {
            var re = /^[0-9]{11}[A-Z]{1}$/;
            if (!re.test($(this).val()))
                setError.call(this, "CST No. format is invalid");
            else
                unsetError.call(this);
        });

        $("#" + frmId + " .servtaxno").each(function () {
            var re = /^[A-Z]{5}[0-9]{4}[A-Z]{3}[0-9]{3}$/;
            if (!re.test($(this).val()))
                setError.call(this, "Service TAX No. format is invalid");
            else
                unsetError.call(this);
        });

        $("#" + frmId + " .ifsc").each(function () {
            var re = /^[A-Z]{4}[0-9]{7}$/;
            if (!re.test($(this).val()))
                setError.call(this, "IFSC No. format is invalid");
            else
                unsetError.call(this);
        });

        $("#" + frmId + " .micr").each(function () {
            var re = /^[0-9]{9}$/;
            if (!re.test($(this).val()))
                setError.call(this, "MICR No. format is invalid");
            else
                unsetError.call(this);
        });

        $("#" + frmId + " .chequeno").each(function () {
            var re = /^[0-9]{6}$/;
            if (!re.test($(this).val()))
                setError.call(this, "Cheque No. should be 6 digits");
            else
                unsetError.call(this);
        });

        $("#" + frmId + " .aadhar").each(function () {
            var re = /^[0-9]{12}$/;
            if (!re.test($(this).val()))
                setError.call(this, "Aadhaar No. format is invalid");
            else
                unsetError.call(this);
        });

        $("#" + frmId + " .password").each(function () {
            if ($(this).val().length < 4)
                setError.call(this, "Password must have atleast 4 digits");
            else
                unsetError.call(this);
        });
    }
    //$('p').delay(5000).fadeOut('slow');
    return ret;
}


var current_uploadfile = 0;
var oversize = false;
var overfilesname = "";

function PreviewImage() {
    //  $('#image_preview').text('');
    total_file = 0;
    current_uploadfile = 0;
    total_file += document.getElementById("upload_file_image").files.length;
    var _file = document.getElementById("upload_file_image").files;
    var imageUrl = $(this).find('[name=upload_file_image]').val();
    var fileInput = $("input#upload_file_image[type=file]")[0],
        file = fileInput.files && fileInput.files[0];
    if (total_file < 11) {
        if (current_uploadfile < 11) {
            for (var i = 0; i < total_file; i++) {
                if (current_uploadfile < 10) {
                    //sizefile = Number(i.size);
                    var sizefile = _file.item(i).size;
                    if (sizefile > 1048576 * 3) {
                        oversize = true;
                        overfilesname += _file.item(i).name + ", ";
                    } else {
                        debugger;

                        var currentfile = _file[i].name;
                        var tmppath = window.URL.createObjectURL(fileInput.files[i]);

                        // var convertFunction = convertFileToDataURLviaFileReader;
                        // convertFunction(currentfile, function(base64Img) {
                        //     alert(base64Img);
                        // var src = window.URL.createObjectURL(event.target.files[i]);
                        // })

                        //var FR= new FileReader();
                        // tour_dates = new Array();

                        //FR.addEventListener("load", function(e) {
                        // document.getElementById("img").src       = e.target.result;
                        //document.getElementById("b64").innerHTML = e.target.result;
                        //  alert("First Alert"+e.target.result);
                        // console.log(e.target.result);
                        //tour_dates.push(e.target.result);
                        // tmppath=e.target.result;
                        //});
                        /// var tids = tour_dates;
                        //var base= FR.readAsDataURL(_file[i]);
                        // alert(" Alert"+base);
                        // var selectedFile = _file[i];
                        // selectedFile.convertToBase64(function(base64){
                        //     alert(base64);
                        // })


                        var append_image = "<div class='upimg_box'>" +
                            "<i class='thumb_edit mdi mdi-pencil' data-toggle='modal' data-target='#modal_crop_forpost' onclick='EditPostImage(this);'></i><i class='thumb_close mdi mdi-close' onclick='Remove_uploadimg(this);'></i>" +
                            "<img class='up_img' src='" + tmppath + "' />" +
                            "<input class='profile-upload-pic dynamic_fileappend' type='file' val='" + _file.item(i).name + "'  /></div>";

                        $('#image_preview').append(append_image);

                        $('#files_block').show();
                        current_uploadfile++;
                        // });
                    }
                } else {
                    error_noti("Maximum 10 images post at a time");
                    break;
                }
            }
        } else {
            error_noti("Maximum 10 images post at a time");
            return false;
        }
    } else {
        error_noti("Maximum 10 images post at a time");
        return false;
    }
    if (oversize == true) {
        error_noti(overfilesname + " File size must not exceed 3 MB");
    }
}

/*
var RequireText = function(me) {
    var text = $.trim($(me).val());
    if (text == '') {
        $(me).addClass("vErrorRed");
        return false;
    } else {
        $(me).removeClass("vErrorRed");
        return true;
    }
}

var RequireSpecialText = function(me) {
    var text = $.trim($(me).val());
    if (text == '') {
        $(me).parent().addClass("userhas-error");
        return false;
    } else {
        $(me).parent().removeClass("userhas-error");
        return true;
    }
}



var RequireSelect = function(me) {
    var SelectedText = $(me).find('option:selected').text();
    var SelectedVal = $(me).find('option:selected').val();
    var FirstText = $(me).find('option').first().text();
    var FirstVal = $(me).find('option').first().val();

    if (FirstText == SelectedText && FirstVal == SelectedVal) {
        $(me).addClass("vErrorRed");
        return false;
    } else {
        $(me).removeClass("vErrorRed");
        return true;
    }
}

var EmailValidate = function(me) {
    var pattern = /^[a-zA-Z0-9\-_]+(\.[a-zA-Z0-9\-_]+)*@[a-z0-9]+(\-[a-z0-9]+)*(\.[a-z0-9]+(\-[a-z0-9]+)*)*\.[a-z]{2,4}$/;

    var emailText = $.trim($(me).val());
    if (pattern.test(emailText)) {
        $(me).removeClass("vErrorRed");
        return true;
    } else {
        $(me).addClass("vErrorRed");
        return false;
    }
}

var UrlValidate = function(me) {
    var pattern = /^(?:(?:https?|ftp):\/\/)(?:\S+(?::\S*)?@)?(?:(?!10(?:\.\d{1,3}){3})(?!127(?:\.\d{1,3}){3})(?!169\.254(?:\.\d{1,3}){2})(?!192\.168(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]+-?)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]+-?)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:\/[^\s]*)?$/i;

    var urlText = $.trim($(me).val());
    if (urlText == "") {
        $(me).removeClass("vErrorRed");
        return true;
    }

    if (pattern.test(urlText)) {
        $(me).removeClass("vErrorRed");
        return true;
    } else {
        $(me).addClass("vErrorRed");
        return false;
    }
}

var RequireMatchPassword = function(me1, me2) {

    var value1 = $.trim($(me1).val());
    var value2 = $.trim($(me2).val());

    if (value1 == value2) {
        $(me2).removeClass("vErrorRed");
        return true;
    } else {
        $(me2).addClass("vErrorRed");
        return false;
    }
}

var PageValidation = function(SubmitButtonID) {
    var IsValid = true;

    $('.vRequiredText').each(function() {
        if ($(this).attr("data-validate") == SubmitButtonID) {
            if (!Boolean(RequireText($(this)))) {
                IsValid = false;
            }
        }
    });

    $('.vSpecialRequiredText').each(function() {
        if ($(this).attr("data-validate") == SubmitButtonID) {
            if (!Boolean(RequireSpecialText($(this)))) {
                IsValid = false;
            }
        }
    });

    $('.vRequiredDropdown').each(function() {
        if ($(this).attr("data-validate") == SubmitButtonID) {
            if (!Boolean(RequireSelect($(this)))) {
                IsValid = false;
            }
        }
    });

    $('.vEmailAddress').each(function() {
        if ($(this).attr("data-validate") == SubmitButtonID) {
            if (!Boolean(EmailValidate($(this)))) {
                IsValid = false;
            }
        }
    });

    $('.vIntegerOnly').each(function() {
        var txt = $(this).val();
        var me = $(this);
        if (txt != '' && ($(this).attr("data-validate") == SubmitButtonID)) {
            var num = Number(txt);
            if (isNaN(num)) {
                IsValid = false;

                if ($(me).hasClass("vSpecialRequiredText")) {
                    $(me).parent().addClass("userhas-error");
                } else {
                    $(me).addClass("vErrorRed");
                }

            } else {

                if ($(me).hasClass("vSpecialRequiredText")) {
                    $(me).parent().removeClass("userhas-error");
                } else {
                    $(me).removeClass("vErrorRed");
                }
            }
        }
    });

    $('.vNumberOnly').each(function() {
        var txt = $(this).val();
        var me = $(this);
        if (txt != '' && ($(this).attr("data-validate") == SubmitButtonID)) {
            var num = Number(txt);
            if (isNaN(num)) {
                IsValid = false;

                if ($(me).hasClass("vSpecialRequiredText")) {
                    $(me).parent().addClass("userhas-error");
                } else {
                    $(me).addClass("vErrorRed");
                }

            } else {

                if ($(me).hasClass("vSpecialRequiredText")) {
                    $(me).parent().removeClass("userhas-error");
                } else {
                    $(me).removeClass("vErrorRed");
                }
            }
        }
    });

    return IsValid;
}*/

/*
$(document).ready(function() {
    $('.vRequiredText').focusout(function() {
        RequireText($(this));
    });

    // for bootstrap controls
    $('.vSpecialRequiredText').focusout(function() {
        RequireSpecialText($(this));
    });

    $('.vRequiredDropdown').focusout(function() {
        RequireSelect($(this));
    });

    $('.vIntegerOnly').keypress(function(e) {
        var specialKeys = new Array();
        specialKeys.push(8);
        var keyCode = e.which ? e.which : e.keyCode
        var ret = ((keyCode >= 48 && keyCode <= 57) || specialKeys.indexOf(keyCode) != -1);
        return ret;
    });

    $('.vNumberOnly').keypress(function(evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode;
        return !(charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57));
    });

    $('.vAlphabetOnly').keypress(function(e) {
        var specialKeys = new Array();
        specialKeys.push(8);
        specialKeys.push(32);
        var keyCode = e.which ? e.which : e.keyCode
        var ret = ((keyCode >= 65 && keyCode <= 90) || (keyCode >= 97 && keyCode <= 122) || specialKeys.indexOf(keyCode) != -1);
        return ret;
    });

    $('.vAlphabetAndNumberOnly').keypress(function(e) {
        var specialKeys = new Array();
        specialKeys.push(8);
        specialKeys.push(32);
        var keyCode = e.which ? e.which : e.keyCode
        var ret = ((keyCode >= 48 && keyCode <= 57) || (keyCode >= 65 && keyCode <= 90) || (keyCode >= 97 && keyCode <= 122) || specialKeys.indexOf(keyCode) != -1);
        return ret;
    });

    $('.vEmailAddress').focusout(function() {
        EmailValidate($(this));
    });

    $('.vUrlValidation').focusout(function() {
        UrlValidate($(this));
    });



});*/
