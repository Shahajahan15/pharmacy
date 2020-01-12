var siteURL = $("#site-url-hidden-field").val();
var cssURL = siteURL + "/../themes/admin/css/";

if (!$.fn.bootstrapDP && $.fn.datepicker && $.fn.datepicker.noConflict) {
    var datepicker = $.fn.datepicker.noConflict();
    $.fn.bootstrapDP = datepicker;
}
$.datepicker.setDefaults({dateFormat: 'dd/mm/yy', changeYear: true, changeMonth: true, yearRange: "-100:+0"});

function initPluginUtils(rootEl) {
    /*
     Dropdowns
     */
    //rootEl.find('.dropdown-toggle').dropdown();

    //date picker initialize here
    rootEl.find(".datepickerCommon").datepicker({
        format: 'dd/mm/yyyy'
    });

    var timePickerObj = rootEl.find('.timepickerCommon');
    if (timePickerObj.length > 0) {
        timePickerObj.closest('.form-group').addClass('bootstrap-timepicker').addClass('timepicker');
        timePickerObj.timepicker({'step': 10});
    }

    //chosen initialize here
    //rootEl.find(".chosenCommon").chosen();
    rootEl.find(".chosenCommon").select2();


    //input mask are initialized here
    rootEl.find(".datemask").inputmask("yyyy-mm-dd", {"placeholder": "yyyy-mm-dd"});
    // rootEl.find(".numbermask").inputmask({ "mask": "9", "repeat": '*' });
    // rootEl.find(".decimalmask").inputmask("decimal", {
    //     onUnMask: function(maskedValue, unmaskedValue) {
    //         return unmaskedValue;
    //     }
    // });
    rootEl.find("[data-mask]").inputmask();

    //form validation are initialized here
    rootEl.find(".form-horizontal").validator();
    rootEl.find(".nform-horizontal").validator();

    //form datatables are initialized here
    if (typeof oTable == 'undefined') {
        oTable = $(".filterTable").DataTable({
            "sPaginationType": "full_numbers"
        });
    } else {
        try {
            oTable.fnClearTable(0);
            oTable.fnDraw();
        } catch (e) {
        }
    }

    /*
     Set focus on the first form field
     */
    rootEl.find(":input:visible:first").focus();
}

initPluginUtils($(document));

if ($('#env_var').val() == 'production') {
    $(document).bind("contextmenu", function (e) {
        e.preventDefault();
    });
}

$(document).on("click", "a[data-toggle='collapse']", (function () {
    if ($(this).attr('class') == 'collapsed') {
        $(this).parent().find('span').removeClass().addClass('glyphicon glyphicon-plus');
    } else {
        $(this).parent().find('span').removeClass().addClass('glyphicon glyphicon-minus');
    }
}));

/* Nofication Close Buttons */
$('.notification a.close').click(function (e) {
    e.preventDefault();

    $(this).parent('.notification').fadeOut();
});

/*         get date of birth        */
$(document).on("change keyup", ".c-age", function () {
    var age = $(this).val();
    var dd = new Date();
    var yr = dd.getFullYear();
    var tage = yr - parseInt(age);
    var ma = dd.getMonth();
    var mn = dd.getMonth();
    if (mn < 10) {
        mn = "0" + (mn + 1);
    }
    var cdate = dd.getDate() + "/" + mn + "/" + tage;
    $(".c-birth").val(cdate);
    $(".c-birth").trigger('change');
});

$(document).on("keyup", "#cyear", function () {
    var year = parseInt($(this).val() * 1);
    if (year > 130) {
        parseInt($(this).val(130));
        var year = 130;
    }
    setOfBirth(year, 0, 0);
});

$(document).on("keyup", "#cmonth", function () {
    var month = parseInt($(this).val() * 1);
    if (month > 12) {
        parseInt($(this).val(12));
        var month = 12;
    }
    setOfBirth(0, month, 0);
});

$(document).on("keyup", "#cday", function () {
    var day = parseInt($(this).val() * 1);
    if (day > 29) {
        parseInt($(this).val(29));
        var day = 29;
    }
    setOfBirth(0, 0, day);
});

function setOfBirth(year, month, day) {

    if (parseInt(year * 1) == 0) {
        var year = parseInt($("#cyear").val() * 1);
    }
    if (parseInt(month * 1) == 0) {
        var month = parseInt($("#cmonth").val() * 1);
    }
    if (parseInt(day * 1) == 0) {
        var day = parseInt($("#cday").val() * 1);
    }
    var dd = new Date();
    dd.setDate(dd.getDate() - day);
    dd.setMonth(dd.getMonth() - month);
    dd.setFullYear(dd.getFullYear() - year);
    var yr = dd.getFullYear();
    var mn = dd.getMonth();
    var d = dd.getDate();
    var mn = mn + 1;
    /*	if(mn <= 11) {
            // mn = "0"+(mn+1);
            mn = mn;
        }
        if (d <= 30) {
            mn = mn;
        }
        if(month == 0 && day == 0)
        {
            mn = mn+1;
        }*/


    var cdate = d + "/" + mn + "/" + yr;
    console.log(cdate);
    if (isNaN(d) || isNaN(mn) || isNaN(yr)) {
        console.log("Yes");
        var cdate = "00/00/0000";
    } else {
        console.log("NO");
    }

    $(".nc-birth").val(cdate);
    $(".nc-birth").trigger('change');
}

/*      reference     name      */
$(document).on("keyup", ".auto_reference", function () {
    var auto_name = $(this).val();
    if ($.trim(auto_name) === "") {
        $('.reference_name, #reference_id').val('');
    }
    var width = $(this).attr('width');
    var ci_csrf_token = $("input[name='ci_csrf_token']").val();
    var sendData = {auto_name: auto_name, ci_csrf_token: ci_csrf_token, width: width};
    var targetUrl = siteURL + "common/report/getReferenceNameAjax";
    $.post(targetUrl, sendData).done(function (data) {
        $(".refer_auto_box").show();
        $('.refer_auto_box').html(data)
    });
});

/*   auto complete blur    */

/*     get  auto complete patient      */

$(document).on("keyup", ".auto_name", function () {
    var auto_name = $(this).val();
    var width = $(this).attr('width');
    var type = $(this).attr('c-type');
    var ci_csrf_token = $("input[name='ci_csrf_token']").val();
    var sendData = {auto_name: auto_name, ci_csrf_token: ci_csrf_token, width: width, type: type};
    var targetUrl = siteURL + "common/report/getAutoNameAjax";
    $.post(targetUrl, sendData).done(function (data) {
        $(".auto_box").show();
        $('.auto_box').html(data)
    });
});


/*     get  auto complete doctor      */

$(document).on("keyup", ".d_auto_name", function () {
    var auto_name = $(this).val();
    if ($.trim(auto_name) === "") {
        $(".doctor_name, .doctor_id").val('');
    }
    var width = $(this).attr('width');
    var type = $(this).attr('c-type');
    var ci_csrf_token = $("input[name='ci_csrf_token']").val();
    var sendData = {auto_name: auto_name, ci_csrf_token: ci_csrf_token, width: width, type: type};
    var targetUrl = siteURL + "common/report/getAutoNameAjax";
    $.post(targetUrl, sendData).done(function (data) {
        $(".d_auto_box").show();
        $('.d_auto_box').html(data)
    });
});

$(document).on("keyup", ".admission_search_id", function () {
    var admission_auto_name = $(this).val();
    var type = $(this).attr('c-type');
    if ($.trim(admission_auto_name) === "") {

        if ($('.form_reset').length > 0) {
            $('.form_reset')[0].reset();
        }
        admission_empty_value(type);
    }
    var ci_csrf_token = $("input[name='ci_csrf_token']").val();
    var sendData = {admission_auto_name: admission_auto_name, ci_csrf_token: ci_csrf_token};
    var targetUrl = siteURL + "common/report/getAddmissionCodeAjax";
    $.post(targetUrl, sendData).done(function (data) {
        $(".admission_autocomplete_box").show();
        $('.admission_autocomplete_box').html(data)
    });
});

function admission_empty_value(type) {
    if (type == 1) {
        $("#migrationTablePKid").val('');
        $("#patient_id").val('');
        $("#admission_id").val('');
        $("#admission_patient_bed_id").val('');
    }
}

$(document).on("click", ".admission_show", function () {
    var admission_id = $(this).attr('id');
    var ci_csrf_token = $("input[name='ci_csrf_token']").val();
    var sendData = {admission_id: admission_id, ci_csrf_token: ci_csrf_token};
    var targetUrl = siteURL + $('.admission_autocomplete_box').attr('target-url');
    //alert(targetUrl);
    $.post(targetUrl, sendData).done(function (data) {
        $('.admission_search_id').val(admission_id);
        eval(data);
        $(".admission_autocomplete_box").hide();
    });
});

/*     get  auto complete patient      */

$(document).on("keyup", ".autocomplete_search", function () {

    var patient_id = $(this).val();
    var type = $(this).attr('c-type');
    if ($.trim(patient_id) == "") {
        patient_vale_empty(type);
        $('.form_reset')[0].reset();
        return false;
    }
    var width = $(this).attr('width');
    var ci_csrf_token = $("input[name='ci_csrf_token']").val();
    var sendData = {patient_id: patient_id, ci_csrf_token: ci_csrf_token, width: width, type: type};
    var targetUrl = siteURL + "common/report/getPatientAjax";
    var $this = $(this);
    $.post(targetUrl, sendData).done(function (data) {
        //console.log(data);
        $this.closest('div').find(".autocomplete_box").show();
        $this.closest('div').find('.autocomplete_box').html(data)
    });
});

function patient_vale_empty(type) {
    if (type == 3) {
        $(".patient_idd, .doctor_id, .reference_id").val('');
    }

    if (type == 4) {
        $(".patient_idd, .reference_id").val('');
    }
}

/* patient search remove */
$(document).on("click", ".patient-search-remove", function () {
    $(".autocomplete_search, .patient_idd").val('');
    $(".autocomplete_box").hide();
});
/* remove */
$(document).on("click", ".patient-name-remove", function () {
    $(".patient_name").val('');
    $(".auto_box").hide();
});
$(document).on("click", ".doctor-name-remove", function () {
    $(".doctor_name, .doctor_id").val('');
    $(".d_auto_box").hide();
});
$(document).on("click", ".reference-name-remove", function () {
    $(".reference_name, .reference_id").val('');
    $(".refer_auto_box").hide();
});
$(document).on("click", ".admission-search-remove", function () {
    $(".admission_search_id").val('');
    $(".admission_autocomplete_box").hide();
});
/*     get  auto complete doctor      */

$(document).on("keyup", ".doctor_search", function () {
    var doctor_id = $(this).val();
    if ($.trim(doctor_id) === "") {
        doctor_vale_empty();
        //$('.form_reset')[0].reset();
    }
    var width = $(this).attr('width');
    var type = $(this).attr('c-type');
    var ci_csrf_token = $("input[name='ci_csrf_token']").val();
    var sendData = {doctor_id: doctor_id, ci_csrf_token: ci_csrf_token, width: width, type: type};
    var targetUrl = siteURL + "common/report/getDoctorAjax";
    $.post(targetUrl, sendData).done(function (data) {
        $(".auto_doctor_box").show();
        $('.auto_doctor_box').html(data);
        //$(".save").removeClass("disabled");
    });
});

function doctor_vale_empty() {
    $(".doctor_search, #odp_doctor_department, #odp_doctor_id, #odp_schedule_id, #odp_appointment_type, #odp_ticket_fee").val('');
}

$(document).on("click", ".show_hide", function () {
    $('.auto_doctor_box').hide();
});

/*       doctor show     */
$(document).on("click", ".doctor_show", function () {

    var id = $(this).attr('id');
    var ci_csrf_token = $("input[name='ci_csrf_token']").val();
    var sendData = {id: id, ci_csrf_token: ci_csrf_token};
    var targetUrl = siteURL + $('.auto_doctor_box').attr('target-url');
    $.post(targetUrl, sendData).done(function (data) {

        eval(data);
        //  $(".save").removeClass("disabled");
        $(".auto_doctor_box").hide();
    });

});

$(document).on("click blur", ".patient_show", function () {

    var id = $(this).attr('id');
    var ci_csrf_token = $("input[name='ci_csrf_token']").val();
    var sendData = {id: id, ci_csrf_token: ci_csrf_token};
    var url = $('.autocomplete_box').attr('target-url');
    var targetUrl = siteURL + $('.autocomplete_box').attr('target-url');
    //console.log(sendData);return false;

    $.post(targetUrl, sendData).done(function (data) {

        eval(data);
        $(".autocomplete_box").hide();
    });

});

function selectAutoPatientName(auto_name) {
    $(".auto_name").val(auto_name);
    $(".auto_box").hide();
}

function selectAutoDoctorName(val, auto_name) {
    $(".d_auto_name").val(auto_name);
    $("#odp_doctor_id").val(val);
    $(".d_auto_box").hide();
}

function selectAutoReferenceName(val, name) {
    $("#reference_name").val(name);
    $("#reference_id").val(val);
    $(".refer_auto_box").hide();
}


/*
	Check All Feature
*/
$(document).on('click', ".check-all", function () {
    if (!$(this).is(':checked')) {
        $("table tbody input[type=checkbox]").removeAttr('checked');
    } else {
        $("table tbody input[type=checkbox]").prop('checked', true);
        //$("table tbody input[type=checkbox]").attr('checked', true);
    }
});


/*
	Responsive Navigation
*/
//$('.collapse').collapse();

/*
 Prevent elements classed with "no-link" from linking
*/
//$(".no-link").click(function(e){ e.preventDefault();	});


//======= Start Print Function =======
function PrintElem(elem) {
    Popup($(elem).html());
}

function Popup(data) {
    var mywindow = window.open('', 'printArea', 'height=850,width=950');
    mywindow.document.write('<html><head><title>Report Print</title>');
    mywindow.document.write('<link rel="stylesheet" href="' + cssURL + 'bootstrap.css" type="text/css" />');
    mywindow.document.write('<link rel="stylesheet" href="' + cssURL + 'font-awesome.min.css" type="text/css" />');
    mywindow.document.write('<link rel="stylesheet" href="' + cssURL + 'ionicons.min.css" type="text/css" />');
    mywindow.document.write('<link rel="stylesheet" href="' + cssURL + 'datatables/dataTables.bootstrap.css" type="text/css" />');
    mywindow.document.write('<link rel="stylesheet" href="' + cssURL + 'bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css" type="text/css" />');
    mywindow.document.write('</head><body >');
    mywindow.document.write(data);
    mywindow.document.write('</body></html>');

    mywindow.document.close(); // necessary for IE >= 10
    myDelay = setInterval(checkReadyState, 20);

    function checkReadyState() {
        if (mywindow.document.readyState == "complete") {

            clearInterval(myDelay);
            mywindow.focus(); // necessary for IE >= 10

            mywindow.print();
            mywindow.close();
        }
    }

    return true;


}

//====== End Print Function =========


/**
 *
 *
 *
 *
 *
 */
function setInnerHTMLAjax(targetUrl, sendData, innerHtmlID, firstOption, isJSON, isChosen) {
    if (typeof(firstOption) === 'undefined') {
        firstOption = 0;
    }
    if (typeof(isJSON) === 'undefined') {
        isJSON = 0;
    }
    if (typeof(isChosen) === 'undefined') {
        isChosen = 0;
    }
    $.post(targetUrl, sendData).done(function (data) { //alert(data);
        if (isJSON == 1) {
            var result = JSON.parse(data);
        } else {
            var result = data;
        }

        if (result != "") {
            if (firstOption != 0) {
                result = firstOption + result;
            }
            $(innerHtmlID).html(result);
        } else {
            if (firstOption != 0) {
                result = firstOption;
            }
            $(innerHtmlID).html(result);
        }


        //console.log("result = "+result);

        if (isChosen != 0) {
            $(innerHtmlID).trigger("chosen:updated");
        }
    }, "json");
}


function setValueAjax(targetUrl, sendData, fieldID, isJSON) {

    if (typeof(isJSON) === 'undefined') {
        isJSON = 0;
    }
    $.post(targetUrl, sendData).done(function (data) {
        //alert(data);
        if (isJSON == 1) {
            var result = JSON.parse(data);
        } else {
            var result = data;
        }

        if (result != "") {
            $(fieldID).val(result);
            $(fieldID).trigger('change');
        }
    }, "json");
}


function getValueAjax(targetUrl, sendData, isJSON) {

    if (typeof(isJSON) === 'undefined') {
        isJSON = 0;
    }
    $.post(targetUrl, sendData).done(function (data) {

        if (isJSON == 1) {
            var result = JSON.parse(data);
        } else {
            var result = data;
        }

        if (result != "") {
            return result;
        }
    }, "json");
}

function getDataByAjax(targetUrl, sendData) {
    $.post(targetUrl, sendData).done(function (data) {
        return JSON.parse(data);
    }, "json");
}

function evalDataByAjax(targetUrl, sendData) {
    $.post(targetUrl, sendData).done(function (data) {
        console.log(data);
        //alert(data);
        return eval(data);
    });
}

/*    print  */
function printDiv(divName) {
    var originalContents = document.body.innerHTML;
    /*non printable fields and column remove */
    var v = $('#' + divName);
    v.find('.no-printable').remove();
    v.find('a').removeAttr('href');
    v.find('span.glyphicon-pencil').remove();

    /* end */
    //var printContents = document.getElementById(divName).innerHTML;
    document.body.innerHTML = v.html();
    window.print();
    document.body.innerHTML = originalContents;
}

function showErrorModal() {
    $('#commonModalTitle').html('<center>Internal Error !!</center>');
    $('#commonModalBody').remove();
    $('#commonModalFooter').remove();
    $('#commonModal').modal('show');
}

//Searching
function searchList(targetUrl, formObj, destinationObj) {
    var inputData = formObj.serializeArray();
    // var inputData = formObj.serialize();

    if (typeof targetUrl === 'undefined') {
        targetUrl = window.location.href;
    }
    console.log("Input Data: " + targetUrl);
    $.post(targetUrl, inputData, function (response) {
        destinationObj.html(response);
    }).always(function () {
        formObj.find('.loader-only').remove();
    }).fail(function () {
        showErrorModal();
    })
}

$(document).ready(function () {

    $(document).on('click', 'div.pagination ul li a, #search', function (e) {
        if ($('#search_result').length) {
            e.preventDefault();
        }
        if ($(this).attr('id') == 'search') {
            $('p#temp-data').text('');
        }
        //console.log($(this).attr('id'));
        var $self = $(this),
            $form = $self.closest('form'),
            destinationObj = $self.closest('.lists-placement'),
            link = $self.attr('id') == 'search' ? $form.attr('action') : $self.attr('href');

        if (link === '#') {
            return;
        }

        if (destinationObj.length == 0) {
            destinationObj = $('#search_result');
        }

        $form.append('<div class="loader-only"></div>');
        // console.log("DEGI"+destinationObj);

        searchList(link, $form, destinationObj);


    });

});


//Common class .confirm for confirm message shown before action

$(document).ready(function () {
    var submit_url = '';
    var type = '';
    $('.confirm').bind('click', function (e) {
        e.preventDefault();

        if (e.target.tagName == 'A') {
            submit_url = $(this).attr('href');
        } else if (e.target.tagName == 'INPUT') {
            type = $(this).attr('type');
            if (type == 'submit') {

                submit_url = $(this).parents('form').attr('action');
            }
        } else {
            submit_url = $(this).attr('data-href');
        }

        if (submit_url == null) {
            alert('Url Not found');
            return false;
        } else {
            $('#alert_message').html('Are You Sure ?');
            $('#confirm_alert').modal('show');
        }

    });

    $('#confirm_action').bind('click', function () {
        $('#confirm_alert').modal('hide');
        //alert(typeof(type));return false;
        if (type == 'submit') {
            $('form').submit();
            return false;
        } else {
            window.location.href = submit_url;
        }

    });
});

$(document).on('click', '.confirm-alert', function (e) {
    e.preventDefault();
    $('#confirmAlert').modal('show');
})

function print_view(data) {
    myWindow = window.open('', 'printArea', 'width=1600,height=900');
    if (myWindow && myWindow.document) {
        myWindow.document.write(data);
    }
};

function showMessages(message, type) {
    console.log(message);
    $("#messages").show();
    $("#messages>div").addClass("alert-" + type);
    $("#text").html(message);
};

function swalAlertMessage(message = '', type = 0) {
    swal({
        text: message,
    });
}

/*
@purpose:Common Search from database like test name
start hare
*/


$(document).on('keyup focusin', '.test_name-search', function () {
    var test_name = $(this).val();

    var withoutSpace = test_name.replace(/ /g, "");

    if (withoutSpace.length == 0) {
        $(this).closest('div.form-group').find('.test_autocomplete_box').html('');
        return false;
    }
    $this = $(this);
    var sendData = {test_name: test_name};
    var targetUrl = siteURL + 'common/report/getTestNameSuggestion';

    $.get(targetUrl, sendData, function (data) {
        $this.closest('div.form-group').find('.test_autocomplete_box').html(data);
    })
})

///All package Modal SHow
$(document).on('click', '.getAllTestpackage', function () {
    var targetUrl = siteURL + 'common/report//getAllTestpackage';
    $('#commonModalTitle').html('Test Packages');
    $('#commonModalBody').html('<div class="loader"></div>');
    $('#commonModalFooter').html('');

    $('#commonModal').modal('show');

    $.get(targetUrl, function (data) {

        $('#commonModalBody').html(data);
        //console.log(data);
    })
})


///All Pharmacy package Modal SHow
$(document).on('click', '.getAllPharmacypackage', function () {
    var targetUrl = siteURL + 'common/report//getAllPharmacypackage';
    $('#commonModalTitle').html('Pharmacy Packages');
    $('#commonModalBody').html('<div class="loader"></div>');
    $('#commonModalFooter').html('');

    $('#commonModal').modal('show');

    $.get(targetUrl, function (data) {

        $('#commonModalBody').html(data);
        //console.log(data);
    })
})


$(document).on('click', '.c-remove', function () {
    $(this).closest('.form-group').find('.test_name-search').val('');
    $(this).closest('.autocomplete_box').html('');
    $(this).closest('.test_autocomplete_box').html('');
})

$(document).on('click', '.remove', function () {
    $(this).closest('tr').remove();
})


/*
@purpose:Common Search from database like test name
finished hare
*/


/*
@purpose: modal for all test for selecting

start hare
*/

$(document).on('click', '.select-from-test', function () {
    var targetUrl = siteURL + 'common/report/getAllTest';

    $('#commonModalTitle').html('Select Test For patient');
    $('#commonModalFooter').html('');
    $('#commonModal').modal('show');

    $('#commonModalBody').html('<div class="loader"></div>');
    $.get(targetUrl, function (data) {
        $('#commonModalBody').html(data);
    })
})


/*
	@ for selecting a input on focus*/

$(document).on('focusin', '.on-focus-selected', function () {
    $(this).select();
})

/*
	@ return
*/


/*
	Get All Medicine By on key up
*/

$(document).on('keyup', '.medicine_name-search', function () {
    var medicine_name = $(this).val();

    var withoutSpace = medicine_name.replace(/ /g, "");

    if (withoutSpace.length == 0) {
        $(this).closest('.form-group').find('.autocomplete_box').html('');
        return false;
    }
    $this = $(this);
    var sendData = {medicine_name: medicine_name};
    var targetUrl = siteURL + 'common/report/getMedicineProByKey';

    $.get(targetUrl, sendData, function (data) {
        $this.closest('.form-group').find('.autocomplete_box').html(data);
    })

});


/*       search panel list        */

/*
    get store sub category by store category id
*/

function getStoreSubCategoryList() {
    var store_category_id = $("#store_category_id_sp").val();
    var ci_csrf_token = $("input[name='ci_csrf_token']").val();
    var targetUrl = siteURL + "common/report/getStoreSubCategoryList";

    var sendData = {ci_csrf_token: ci_csrf_token, store_category_id: store_category_id};
    $.ajax({
        url: targetUrl,
        type: 'POST',
        data: sendData,
        success: function (response) {
            $('#store_sub_category_id_sp').children('option:not(:first)').remove();
            $("#store_sub_category_id_sp").append(response);
        }
    });
}

function getPatientSubtypeList() {
    var patient_type_id = $("#patient_type_id_sp").val();
    var ci_csrf_token = $("input[name='ci_csrf_token']").val();
    var targetUrl = siteURL + "common/report/getPatientSubtypeList";

    var sendData = {ci_csrf_token: ci_csrf_token, patient_type_id: patient_type_id};
    $.ajax({
        url: targetUrl,
        type: 'POST',
        data: sendData,
        success: function (response) {
            $('#patient_subtype_id_sp').children('option:not(:first)').remove();
            $("#patient_subtype_id_sp").append(response);
        }
    });
}

/*
    get store product by store sub category id
*/

function getStoreProductList() {
    var store_sub_category_id = $("#store_sub_category_id_sp").val();
    var ci_csrf_token = $("input[name='ci_csrf_token']").val();
    var targetUrl = siteURL + "common/report/getStoreProductList";

    var sendData = {ci_csrf_token: ci_csrf_token, store_sub_category_id: store_sub_category_id};
    $.ajax({
        url: targetUrl,
        type: 'POST',
        data: sendData,
        success: function (response) {
            $('#store_product_id_sp').children('option:not(:first)').remove();
            $("#store_product_id_sp").append(response);
        }
    });
}

// get store product by store category id
function getStoreProductListbyCategoryId() {
    var store_category_id = $("#store_category_id_sp").val();
    var ci_csrf_token = $("input[name='ci_csrf_token']").val();
    var targetUrl = siteURL + "common/report/getStoreProductListbyCategoryId";

    var sendData = {ci_csrf_token: ci_csrf_token, store_category_id: store_category_id};
    $.ajax({
        url: targetUrl,
        type: 'POST',
        data: sendData,
        success: function (response) {
            $('#store_product_id_sp').children('option:not(:first)').remove();
            $("#store_product_id_sp").append(response);
        }
    });
}

/*
    get pharmacy sub category by store category id
*/

function getPharmacySubCategoryList() {
    var pharmacy_category_id = $("#pharmacy_category_id_sp").val();
    var ci_csrf_token = $("input[name='ci_csrf_token']").val();
    // var targetUrl = siteURL + "common/report/getPharmacySubCategoryList";
    var targetUrl = siteURL + "common/report/getPharmacyProductList";

    var sendData = {ci_csrf_token: ci_csrf_token, pharmacy_category_id: pharmacy_category_id};
    $.ajax({
        url: targetUrl,
        type: 'POST',
        data: sendData,
        success: function (response) {
            //$('#pharmacy_sub_category_id_sp').children('option:not(:first)').remove();
            // $("#pharmacy_sub_category_id_sp").append(response);
            $('#pharmacy_product_id_sp').children('option:not(:first)').remove();
            $("#pharmacy_product_id_sp").append(response);
        }
    });
}

/*
    get pharmacy product by store sub category id
*/

function getPharmacyProductList() {
    var pharmacy_sub_category_id = $("#pharmacy_sub_category_id_sp").val();
    var ci_csrf_token = $("input[name='ci_csrf_token']").val();
    var targetUrl = siteURL + "common/report/getPharmacyProductList";

    var sendData = {ci_csrf_token: ci_csrf_token, pharmacy_sub_category_id: pharmacy_sub_category_id};
    $.ajax({
        url: targetUrl,
        type: 'POST',
        data: sendData,
        success: function (response) {
            $('#pharmacy_product_id_sp').children('option:not(:first)').remove();
            $("#pharmacy_product_id_sp").append(response);
        }
    });
}

$(document).on('keyup', '.store-product-search', function () {
    var product_name = $(this).val();

    var withoutSpace = product_name.replace(/ /g, "");

    if (withoutSpace.length == 0) {
        $(this).closest('.form-group').find('.autocomplete_box').html('');
        return false;
    }
    $this = $(this);
    var sendData = {product_name: product_name};
    var targetUrl = siteURL + 'common/report/getStoreProductBykey';

    $.get(targetUrl, sendData, function (data) {
        $this.closest('.form-group').find('.autocomplete_box').html(data);
    })

})

$(document).on('keyup', '.store-product-search-byStore', function () {
    var product_name = $(this).val();

    var withoutSpace = product_name.replace(/ /g, "");

    if (withoutSpace.length == 0) {
        $(this).closest('.form-group').find('.autocomplete_box').html('');
        return false;
    }
    $this = $(this);
    var sendData = {product_name: product_name};
    var targetUrl = siteURL + 'common/report/getStoreProductBykeyWithStore';

    $.get(targetUrl, sendData, function (data) {
        $this.closest('.form-group').find('.autocomplete_box').html(data);
    })

})

function datepicker() {
    $(".datepickerCommon").datepicker({
        format: 'dd/mm/yyyy'
    });
}


/*
	Get All Medicine By on key up
*/

$(document).on('keyup', '.canteen_purchase_product', function () {
    var product_name = $(this).val();

    var withoutSpace = product_name.replace(/ /g, "");

    if (withoutSpace.length == 0) {
        $(this).closest('.form-group').find('.autocomplete_box').html('');
        return false;
    }
    $this = $(this);
    var sendData = {product_name: product_name};
    var targetUrl = siteURL + 'common/report/getCanteenPurchaseProductByKey';

    $.get(targetUrl, sendData, function (data) {
        $this.closest('.form-group').find('.autocomplete_box').html(data);
    })

})

$(document).on('keyup', '.canteen_raw_material_purchase', function () {
    var product_name = $(this).val();

    var withoutSpace = product_name.replace(/ /g, "");

    if (withoutSpace.length == 0) {
        $(this).closest('.form-group').find('.autocomplete_box').html('');
        return false;
    }
    $this = $(this);
    var sendData = {product_name: product_name};
    var targetUrl = siteURL + 'common/report/getCanteenRawProductByKey';

    $.get(targetUrl, sendData, function (data) {
        $this.closest('.form-group').find('.autocomplete_box').html(data);
    })

})

$(".c-birth").datepicker({
    onSelect: function (e) {

        var date_splits = $(this).val().split('/');
        if (date_splits.length != 3) {
            //console.log('false date type');
            return false;
        }
        var new_date = [date_splits[2], '-', date_splits[1], '-', date_splits[0]].join('');

        var birthdate = new Date(new_date);

        var ageDifMs = Date.now() - birthdate.getTime();
        var ageDate = new Date(ageDifMs); // miliseconds from epoch
        var age = Math.abs(ageDate.getUTCFullYear() - 1970);
        if (isNaN(age)) {
            age = 1;
        }

        $('.c-age').val(age);
        $(".c-birth").trigger('change');
        $(".c-age").trigger('change');
    }
});


$(".nc-birth").datepicker({
    onSelect: function (e) {
        var date_splits = $(this).val().split('/');
        if (date_splits.length != 3) {
            //console.log('false date type');
            return false;
        }
        var new_date = [date_splits[2], '-', date_splits[1], '-', date_splits[0]].join('');

        var birthDate = new Date(new_date);
        // console.log(birthdate);
        var today = new Date();
        var year = today.getFullYear() - birthDate.getFullYear();
        var mn = today.getMonth() - birthDate.getMonth();
        var d = today.getDate() - birthDate.getDate();
        $("#cyear").val(year);
        $("#cmonth").val(mn);
        $("#cday").val(d);
        $("#cyear, #cday, #cmonth, .c-birth").trigger('change');
    }
});

//numeric init
$(document).on("keypress", ".numeric", function (e) {
    var a = [];
    var k = (e.which) ? e.which : e.keyCode;
    if (k == 8 || k == 37 || k == 39) {
        return true;
    }

    for (i = 48; i < 58; i++) {
        a.push(i);
    }

    if (this.value.indexOf(".") === -1) {
        a.push(46);
    }

    if (!(a.indexOf(k) >= 0)) {
        e.preventDefault();
        return;
    }

    var keystring = String.fromCharCode(k);
    var num = this.value + keystring;
    $(this).val(num);
    e.preventDefault();
});

$(document).on("keypress", ".numeric-zero", function (e) {
    var a = [];
    var k = (e.which) ? e.which : e.keyCode;
    if (k == 8 || k == 37 || k == 39) {
        return true;
    }

    for (i = 48; i < 58; i++) {
        a.push(i);
    }

    if (!(a.indexOf(k) >= 0)) {
        e.preventDefault();
        return;
    }

    var keystring = String.fromCharCode(k);
    var num = this.value + keystring;
    $(this).val(num);
    e.preventDefault();
});

$(document).on('keypress keyup', '.real-number', function (event) {
    var number = parseInt($(this).val());
    //console.log(event);
    if (isNaN(number) || number == 0) {
        if (event.charCode == 48) {
            $(this).val('');
            return false;
        }
        if (event.charCode == 0) {
            $(this).val('');
        }
    }
    return isRealNumber(event, this)
});

function isRealNumber(evt, element) {

    var charCode = (evt.which) ? evt.which : event.keyCode

    if (
        //(charCode != 45 || $(element).val().indexOf('-') != -1) &&      // “-” CHECK MINUS, AND ONLY ONE.
    //(charCode != 46 || $(element).val().indexOf('.') != -1) &&      // “.” CHECK DOT, AND ONLY ONE.
        (charCode < 48 || charCode > 57))
        return false;

    return true;
}

$(document).on('keypress keyup change', '.decimal , .decimalmask', function (event) {
    if (isDecimal(event, this) == true) {
        if ($.isNumeric($(this).val())) {
            return true;
        } else {
            $(this).val('');
        }
    } else {
        return false;
    }

});

function isDecimal(evt, element) {

    var charCode = (evt.which) ? evt.which : event.keyCode

    if (
        //(charCode != 45 || $(element).val().indexOf('-') != -1) &&      // “-” CHECK MINUS, AND ONLY ONE.
        (charCode != 46 || $(element).val().indexOf('.') != -1) &&      // “.” CHECK DOT, AND ONLY ONE.
        (charCode < 48 || charCode > 57))
        return false;


    return true;
}

function alertMessage(message) {
    $('#alertModalMessage').html(message);
    $('#alertModal').modal('show');
}

/**
 * The Form List Single Page JS
 *
 * Work need from devs:
 * - backend sample done in pharmacy/controllers/product.php@form_list
 *           also check @show_list, @create, @edit
 *           and how it return ajax/json response
 * - must pass form_action_url and list_action_url
 * - edit url class = '.bf-edit-action'
 * - cancel button class = '.bf-cancel-action'
 * - delete button class = '.bf-delete-action'
 * - some form/list specific js files needs a bit of tinkering to work
 *
 * Incase you need a different save implementation
 * - add class "local-form-submit" in the form element
 *
 * @author A. H. Abid
 */
(function () {

    var bfListView = $('#bf-list-view'),
        bfListSection = $('#search_result'),
        bfFormView = $('#bf-form-view'),
        bfFormSection = $('#bf-form-section'),
        bfForm = bfFormView.find('form'),
        listActionUrl = bfListView.attr('data-list-action-url'),
        formActionUrl = bfFormView.attr('data-form-action-url'),
        loader = '<div class="loader-only"></div>',

        loadFormSection = function (formUrl) {
            if (bfFormView.find('.fa.fa-spin').length == 0) {
                bfFormSection.append(loader);
            }

            $.ajax({
                type: 'get',
                url: formUrl,
                dataType: 'html'
            })
                .always(function () {
                    bfFormSection.find('.loader-only').remove();
                })
                .done(function (resp) {
                    var $resp = $(resp)
                    var csrfToken = $resp.find('input[name="ci_csrf_token"]').val();

                    $resp.find('form').attr('action', formUrl);

                    bfFormSection.html($resp);
                    bfFormView.find('form').attr('action', formUrl);
                    $(".nform-horizontal").validator();

                    if (csrfToken) {
                        $('input[name="ci_csrf_token"]').val(csrfToken);
                    }

                    initPluginUtils(bfFormSection);
                })
                .fail(function (x, t, s) {
                    showErrorModal();
                });
        },

        loadListSection = function () {
            if (bfListView.find('.fa.fa-spin').length == 0) {
                bfListSection.append(loader);
            }

            $.ajax({
                type: 'get',
                url: listActionUrl,
                dataType: 'html'
            })
                .always(function () {
                    bfListSection.find('.loader-only').remove();
                })
                .done(function (resp) {
                    bfListSection.html(resp);
                })
                .fail(function (x, t, s) {
                    showErrorModal();
                });
        };

    if (listActionUrl != undefined && listActionUrl.length > 0) {
        loadFormSection(formActionUrl);
    }

    if (formActionUrl != undefined && formActionUrl.length > 0) {
        loadListSection();
    }

    // Cancel on Click Action
    bfFormView.on('click', '.bf-cancel-action', function (e) {
        e.preventDefault();

        loadFormSection(formActionUrl);
    });

    // Edit on Click Action
    bfListView.on('click', '.bf-edit-action', function (e) {
        e.preventDefault();
        var editActionUrl = $(this).attr('href');
        loadFormSection(editActionUrl);
    });

    // Save on Click Action form submission
    bfFormView.on('submit', 'form:not(.local-form-submit)', function (e) {
        e.preventDefault();

        var $self = $(this),
            $btn = bfFormView.find('input[type="submit"]'),
            $form = bfFormView.find('form'),
            formUrl = $form.attr('action'),
            formData = $form.serialize() + '&save=1';


        /*if ($form.find('.has-error').length > 0) {
            console.warn('Form has errors. Validate fields first.');
            return false;
        }*/

        $btn.prop('disabled', true);

        $.ajax({
            type: 'post',
            url: formUrl,
            data: formData,
            dataType: 'json'
        })
            .always(function () {
                $btn.prop('disabled', false);
            })
            .done(function (resp) {
                if (resp.status == true) {
                    showMessages(resp.message, 'success')
                } else {
                    showMessages(resp.message, 'error')
                }
                loadListSection();
                loadFormSection(formActionUrl);
            })
            .fail(function (x, t, s) {
                showErrorModal();
            });
    })

    // Delete on Click Action
    bfListView.on('click', '.bf-delete-action', function (e) {
        e.preventDefault();

        var $self = $(this),
            formData = bfListView.find('form').serialize();

        $self.prop('disabled', true);

        bfListSection.append(loader);

        $.ajax({
            type: 'post',
            url: listActionUrl,
            data: formData,
            dataType: 'html',
            timeout: 10000
        })
            .always(function () {
                $self.prop('disabled', false);
                bfListSection.find('.loader-only').remove();
            })
            .done(function (resp) {
                bfListSection.html(resp);
            })
            .fail(function (x, t, s) {
                showErrorModal();
            });
    });

    bfListView.on('bf-list:reload', function (e) {
        loadListSection();
    });

}());

$(document).on('click', '.reprint', function (e) {
    e.preventDefault();
    var target = $(this).attr('href');
    $.get(target, function (data) {
        print_view(data);
    });
});

$(document).on('click', '.modal-pagination ul li a', function (e) {
    e.preventDefault();
    var target = $(this).attr('href');
    $.get(target, function (data) {
        $('#commonModalBody').html(data);
    });
});

$('.employee-auto-complete').select2({
    ajax: {
        url: siteURL + "common/report/getEmployeeByKey",
        dataType: 'json',
        delay: 250,
        data: function (params) {
            return {
                q: params.term,
            };
        },
        processResults: function (data, params) {

            return {
                results: data.items
            };
        },
        cache: true
    },

    allowClear: true,
    placeholder: 'Employee Name/Code',
    minimumInputLength: 1
});

$('.employee-mobile-autoComplete').select2({
    ajax: {
        url: siteURL + "common/report/getAllEmployeeByKey",
        dataType: 'json',
        delay: 250,
        data: function (params) {
            return {
                q: params.term,
            };
        },
        processResults: function (data, params) {

            return {
                results: data.items
            };
        },
        cache: true
    },

    allowClear: true,
    placeholder: 'Employee Mobile',
    minimumInputLength: 1
});

$('.nurse-auto-complete').select2({
    ajax: {
        url: siteURL + "common/report/getNurseByKey",
        dataType: 'json',
        delay: 250,
        data: function (params) {
            return {
                q: params.term,
            };
        },
        processResults: function (data, params) {

            return {
                results: data.items
            };
        },
        cache: true
    },

    allowClear: true,
    placeholder: 'Nurse Name/Code',
    minimumInputLength: 1
});
$('.canteen-customar-autocomplete').select2({
    ajax: {
        url: siteURL + "common/report/getCanteenCustomerByKey",
        dataType: 'json',
        delay: 250,
        data: function (params) {
            return {
                q: params.term,
            };
        },
        processResults: function (data, params) {

            return {
                results: data.items
            };
        },
        cache: true
    },

    allowClear: true,
    placeholder: 'Customer Name/Mobile',
    minimumInputLength: 1
});

$('.pharmacy-customar-autocomplete').select2({
    ajax: {
        url: siteURL + "common/report/getPharmacyCustomerByKey",
        dataType: 'json',
        delay: 250,
        data: function (params) {
            return {
                q: params.term,
            };
        },
        processResults: function (data, params) {

            return {
                results: data.items
            };
        },
        cache: true
    },

    allowClear: true,
    placeholder: 'Customer Name/Mobile',
    minimumInputLength: 1
});
// canteen all type customer (including canteen customer,patient, doctor, employee) auto complete
$('.canteen-all-customar-autocomplete').select2({
    ajax: {
        url: siteURL + "common/report/getCanteenAllCustomerByKey",
        dataType: 'json',
        delay: 250,
        data: function (params) {
            return {
                q: params.term,
                c_type: $('#customer_type_list_sp').val(),
            };
        },
        processResults: function (data, params) {

            return {
                results: data.items
            };
        },
        cache: true
    },
    allowClear: true,
    placeholder: 'Customer Name',
    minimumInputLength: 1
});

$('.doctor-autocomplete').select2({
    ajax: {
        url: siteURL + "common/report/getAllDoctorByKey",
        dataType: 'json',
        delay: 250,
        data: function (params) {
            return {
                q: params.term,
                d_type: $('#doctor_type_list_sp').val(),
            };
        },
        processResults: function (data, params) {

            return {
                results: data.items
            };
        },
        cache: true
    },
    allowClear: true,
    placeholder: 'Doctor Name',
    minimumInputLength: 1
});
/*************Patient Name AutoComplete Field  21/04/18 By Nazmul Hossain****************/
$('.patient-autocomplete').select2({
    ajax: {
        url: siteURL + "common/report/getAllPatientByKey",
        dataType: 'json',
        delay: 250,
        data: function (params) {
            return {
                q: params.term,
            };
        },
        processResults: function (data, params) {

            return {
                results: data.items
            };
        },
        cache: true
    },

    allowClear: true,
    placeholder: 'Patient Name',
    minimumInputLength: 1
});
/*************all Patient ID AutoField Complete By kabir****************/

$('.all-autoComplete').select2({
    //alert();
    ajax: {
        url: siteURL + "common/report/getAllTypePatientByKey",
        dataType: 'json',
        delay: 250,
        data: function (params) {
            return {
                q: params.term,
            };
        },
        processResults: function (data, params) {

            return {
                results: data.items
            };
        },
        cache: true
    },

    allowClear: true,
    placeholder: 'Patient Name',
    minimumInputLength: 1
});


/*****************************/

$('.test-autoComplete').select2({
    ajax: {
        url: siteURL + "common/report/get_test_key",
        dataType: 'json',
        delay: 250,
        data: function (params) {
            return {
                q: params.term,
            };
        },
        processResults: function (data, params) {

            return {
                results: data.items
            };
        },
        cache: true
    },

    allowClear: true,
    placeholder: 'Test Name',
    minimumInputLength: 1
});


/************************
 /*************Patient ID AutoField Complete 21/04/18 By Nazmul Hossain****************/
$('.patientIdField-autoComplete').select2({
    ajax: {
        url: siteURL + "common/report/getAllPatientIdByKey",
        dataType: 'json',
        delay: 250,
        data: function (params) {
            return {
                q: params.term,
            };
        },
        processResults: function (data, params) {

            return {
                results: data.items
            };
        },
        cache: true
    },

    allowClear: true,
    placeholder: 'Patient ID/Code',
    minimumInputLength: 1
});

$('.doctor-auto-complete').select2({
    ajax: {
        url: siteURL + "common/report/getDoctorByKey",
        dataType: 'json',
        delay: 250,
        data: function (params) {
            return {
                q: params.term,
            };
        },
        processResults: function (data, params) {

            return {
                results: data.items
            };
        },
        cache: true
    },

    allowClear: true,
    placeholder: 'Doctor Name/code/designation',
    minimumInputLength: 1
});
$('.consultant-auto-complete').select2({
    ajax: {
        url: siteURL + "common/report/getConsultantDoctorByKey",
        dataType: 'json',
        delay: 250,
        data: function (params) {
            return {
                q: params.term,
            };
        },
        processResults: function (data, params) {

            return {
                results: data.items
            };
        },
        cache: true
    },

    allowClear: true,
    placeholder: 'Consultant.Doctor/Organization/phone/mobile',
    minimumInputLength: 1
});

$('.reference-auto-complete').select2({
    ajax: {
        url: siteURL + "common/report/getReferenceByKey",
        dataType: 'json',
        delay: 250,
        data: function (params) {
            return {
                q: params.term,
            };
        },
        processResults: function (data, params) {

            return {
                results: data.items
            };
        },
        cache: true
    },

    allowClear: true,
    placeholder: 'Ref.Doctor/Organization/phone/mobile',
    minimumInputLength: 1
});

$('.surgeon-auto-complete').select2({
    ajax: {
        url: siteURL + "common/report/getSurgeonDoctorByKey",
        dataType: 'json',
        delay: 250,
        data: function (params) {
            return {
                q: params.term,
            };
        },
        processResults: function (data, params) {
            return {
                results: data.items
            };
        },
        cache: true
    },
    allowClear: true,
    placeholder: 'Surgeon Doctor/phone/mobile',
    minimumInputLength: 1
});

$('.patient-auto-complete').select2({
    ajax: {
        url: siteURL + "common/report/getPatientByKey",
        dataType: 'json',
        delay: 250,
        data: function (params) {
            return {
                q: params.term,
            };
        },
        processResults: function (data, params) {
            return {
                results: data.items
            };
        },
        cache: true
    },

    allowClear: true,
    placeholder: 'Admitted Patient ID/Name/Contact No/Bed No/DoctorName.',
    minimumInputLength: 1
});

//====== discharge patient (Robin) ======
$('.discharge-patient-auto-complete').select2({
    ajax: {
        url: siteURL + "common/report/getDischargePatientByKey",
        dataType: 'json',
        delay: 250,
        data: function (params) {
            return {
                q: params.term,
            };
        },
        processResults: function (data, params) {
            return {
                results: data.items
            };
        },
        cache: true
    },

    allowClear: true,
    placeholder: 'Discharge Patient ID/Name/Contact No/Bed No/DoctorName.',
    minimumInputLength: 1
});
//====== robin =======
$('.normal-patient-auto-complete').select2({
    ajax: {
        url: siteURL + "common/report/getNormalPatientByKey",
        dataType: 'json',
        delay: 250,
        data: function (params) {
            return {
                q: params.term,
            };
        },
        processResults: function (data, params) {
            return {
                results: data.items
            };
        },
        cache: true
    },

    allowClear: true,
    placeholder: 'Normal Patient ID/Name/Contact No',
    minimumInputLength: 1
});
$('.serial-patient-auto-complete').select2({
    ajax: {
        url: siteURL + "booth/patient/serial/patient",
        dataType: 'json',
        delay: 250,
        data: function (params) {
            return {
                q: params.term,

            };
        },
        processResults: function (data, params) {
            patientIdData = $.extend({}, data.assoc);
            return {
                results: data.items
            };
        },
        cache: true
    },
    allowClear: true,
    placeholder: 'Serial Patient Name / Contact No.',
    minimumInputLength: 1

});
$('.pharmacy-auto-complete').select2({
    ajax: {
        url: siteURL + "common/report/getPharmacyCustomerByKey",
        dataType: 'json',
        delay: 250,
        data: function (params) {
            return {
                q: params.term,
            };
        },
        processResults: function (data, params) {
            return {
                results: data.items
            };
        },
        cache: true
    },

    allowClear: true,
    placeholder: 'Customer Name/Contact No',
    minimumInputLength: 1
});

$('.test-auto-complete').select2({
    ajax: {
        url: siteURL + "common/report/getTestByKey",
        dataType: 'json',
        delay: 250,
        data: function (params) {
            return {
                q: params.term,
            };
        },
        processResults: function (data, params) {
            return {
                results: data.items
            };
        },
        cache: true
    },

    allowClear: true,
    placeholder: 'Test Name',
    minimumInputLength: 1
});

$('.admission-auto-complete').select2({
    ajax: {
        url: siteURL + "common/report/getPatientByAdmissionCode",
        dataType: 'json',
        delay: 250,
        data: function (params) {
            return {
                q: params.term,
            };
        },
        processResults: function (data, params) {
            return {
                results: data.items
            };
        },
        cache: true
    },

    allowClear: true,
    placeholder: 'Patient ID/Admission ID/Bed No/Patient Name/Contact No',
    minimumInputLength: 1
});

// $('.medicine-auto-complete').select2({
//    ajax: {
//         url: siteURL + "common/report/getMedicineName",
//         dataType: 'json',
//         delay: 250,
//         data: function (params) {
//         	console.log("ff"+params);
//             return {
//                 q: params.term,
//             };
//         },
//         processResults: function (data, params) {
//         	console.log("dd"+params+"data"+data);
//             return {
//                 results: data.items
//             };
//         },
//         cache: true
//     },

//     allowClear: true,
//     placeholder: 'Medicine Name',
//     minimumInputLength: 1
// });


$(document).on('select2:select', '.admission-auto-complete', function () {
    var admission_id = $(this).val();
    var url = $(this).attr('url');
    var targetUrl = siteURL + 'admission_bed_migrate/patient/getPatientDataAjax';

    if (url === undefined || url === null) {
        var targetUrl = siteURL + 'admission_bed_migrate/patient/getPatientDataAjax';
    } else {
        var targetUrl = siteURL + url;
    }
    var ci_csrf_token = $("input[name='ci_csrf_token']").val();
    var sendData = {admission_id: admission_id, ci_csrf_token: ci_csrf_token};
    //alert(targetUrl);
    $.post(targetUrl, sendData).done(function (data) {
        eval(data);
    });
})


$('.medicine-auto-complete').select2({
    ajax: {
        url: siteURL + "common/report/getMedicineByKey",
        dataType: 'json',
        delay: 250,
        data: function (params) {
            return {
                q: params.term,
            };
        },
        processResults: function (data, params) {
            return {
                results: data.items
            };
        },
        cache: true
    },

    allowClear: true,
    placeholder: 'Medicine Name',
    minimumInputLength: 1
});
/*     store product auto complected    */

$('.store-product-auto-complete').select2({
    ajax: {
        url: siteURL + "common/report/getStoreProductsByKey",
        dataType: 'json',
        delay: 250,
        data: function (params) {
            return {
                q: params.term,
            };
        },
        processResults: function (data, params) {
            return {
                results: data.items
            };
        },
        cache: true
    },

    allowClear: true,
    placeholder: 'Product Name',
    minimumInputLength: 1
});

/****************Store Product Name Field Auto Complete ***************/

$('.store-all-product-autocomplete').select2({
    ajax: {
        url: siteURL + "common/report/getStoreAllProductsByKey",
        dataType: 'json',
        delay: 250,
        data: function (params) {
            return {
                q: params.term,
            };
        },
        processResults: function (data, params) {
            return {
                results: data.items
            };
        },
        cache: true
    },

    allowClear: true,
    placeholder: 'Product Name',
    minimumInputLength: 1
});
// Reference doctor

$('.refdoc-auto-complete').select2({
    ajax: {
        url: siteURL + "common/report/getRefereceDoctorByKey",
        dataType: 'json',
        delay: 250,
        data: function (params) {
            return {
                q: params.term,
            };
        },
        processResults: function (data, params) {
            return {
                results: data.items
            };
        },
        cache: true
    },

    allowClear: true,
    placeholder: 'Select Reference Doctor',
    minimumInputLength: 1
});
/*        canteen product list  for purchse    */

$('.canteen-auto-complete').select2({
    ajax: {
        url: siteURL + "common/report/getCanteenProductByKey",
        dataType: 'json',
        delay: 250,
        data: function (params) {
            return {
                q: params.term,
            };
        },
        processResults: function (data, params) {
            return {
                results: data.items
            };
        },
        cache: true
    },

    allowClear: true,
    placeholder: 'Product Name',
    minimumInputLength: 1
});
/*        canteen product list  for sale    */

$('.canteen-sale-auto-complete').select2({
    ajax: {
        url: siteURL + "common/report/getCanteenProductSaleByKey",
        dataType: 'json',
        delay: 250,
        data: function (params) {
            return {
                q: params.term,
            };
        },
        processResults: function (data, params) {
            return {
                results: data.items
            };
        },
        cache: true
    },

    allowClear: true,
    placeholder: 'Product Name',
    minimumInputLength: 1
});

/*        canteen product list  for purchase    */

$('.canteen-direct-auto-complete').select2({
    ajax: {
        url: siteURL + "common/report/getCanteenProductDirectByKey",
        dataType: 'json',
        delay: 250,
        data: function (params) {
            return {
                q: params.term,
            };
        },
        processResults: function (data, params) {
            return {
                results: data.items
            };
        },
        cache: true
    },

    allowClear: true,
    placeholder: 'Product Name',
    minimumInputLength: 1
});

/*      doctor department list      */
$('.doctor-department-auto-complete').select2({
    ajax: {
        url: siteURL + "booth/patient/dept_wise_serial/departments",
        dataType: 'json',
        delay: 250,
        data: function (params) {
            return {
                q: params.term
            };
        },
        processResults: function (data, params) {
            return {
                results: data.items
            };
        },
        cache: true
    },
    minimumInputLength: 1,
    placeholder: 'Enter Department Name'
});

/*        pharmacy product list      */

/*    $('.pharmacy-package-auto-complete').select2({
       ajax: {
            url: siteURL + "common/report/getPharmacyProductList",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term,
                };
            },
            processResults: function (data, params) {
                return {
                    results: data.items
                };
            },
            cache: true
        },
        
        allowClear: true,
        placeholder: 'Product Name',
        minimumInputLength: 1
    });*/

/*         delete         */

$(document).on("click", ".c_delete", function (e) {
    e.preventDefault();
    var cnf = confirm("Are You Confirm!...");
    if (cnf != true) {
        return false;
    }
    var $this = $(this);
    $(this).html('<i class="fa fa-spinner fa-spin"></i>');
    var id = $(this).attr("id");
    var url = $(this).attr('url');
    var ci_csrf_token = $("input[name='ci_csrf_token']").val();
    var sendData = {id: id, ci_csrf_token: ci_csrf_token};
    var targetUrl = siteURL + url;

    $.ajax({
        url: targetUrl,
        type: "POST",
        data: sendData,
        dataType: "json",
        success: function (response) {
            if (response.success == true) {
                $this.closest("tr").fadeOut(1000);

                showMessages(response.message, 'success');
            } else {
                showMessages(response.message, 'error');
            }
        }, error: function (jqXHR) {
            showMessages('Unknown Error!!!', 'error');
        }
    });
});


$(document).on('click', '.search-print', function () {
    var sendData = $('#search').closest('form').serialize() + '&print=1';
    var target = $('#search').closest('form').attr('action');
    console.log(sendData);
    $.post(target, sendData, function (data) {
        print_view(data);
    })
});

function doubleClickNotSubmit($this) {
    $($this).prop('disabled', false);
    setTimeout(function () {
        $($this).prop('disabled', true);
    }, 100);
    $($this).submit();
}

function myFunction() {
    var input, filter, table, tr, td, i;
    input = document.getElementById("test_name");
    filter = input.value.toUpperCase();
    table = document.getElementById("test_table");
    tr = table.getElementsByTagName("tr");
    for (i = 1; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[1];
        if (td) {
            if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}

function openShortList() {
    document.getElementById("mySidenav").style.width = "220px";
}

function closeNav() {
    document.getElementById("mySidenav").style.width = "0";
}

// Doctor wise test list
$(document).on('click', '.getShortList', function () {
    var ref_doctor_id = $('.ref_id').val();
    var targetUrl = siteURL + 'common/report/getCommonList/' + ref_doctor_id;

    $.get(targetUrl, function (data) {
        console.log(data);
        $('#mySidenav').html(data);
        // console.log(data);
    })
})

// Pharmacy Product list
$(document).on('click', '.getPharmacyShortList', function () {
    var targetUrl = siteURL + 'common/report/getPharmacyList';

    $.get(targetUrl, function (data) {
        console.log(data);
        $('#mySidenav').html(data);
        // console.log(data);
    })
})

//Sub Pharmacy Product list
$(document).on('click', '.getSubPharmacyShortList', function () {
    var targetUrl = siteURL + 'common/report/getSubPharmacyList';

    $.get(targetUrl, function (data) {
        console.log(data);
        $('#mySidenav').html(data);
        // console.log(data);
    })
})

$(document).on('change', 'select[name="doctor_type_list"]', function () {
    var doctor_type = $(this).val();
    var targetUrl = siteURL + 'common/report/getDoctorNameList/' + doctor_type;
    var ci_csrf_token = $("input[name='ci_csrf_token']").val();
    var sendData = {ci_csrf_token: ci_csrf_token};
    $('#doctor_list').html('<option value="">.....Searching....</option>');

    $.post(targetUrl, sendData, function (data) {
        $('#doctor_list').html(data);
    })
});


$(document).on('click', '.select2-container', function () {
//console.log(this);
//console.log($('.select2-container').index(this));
    if ($('.select2-container').index(this) == 1) {
        $('#ref_doc_id').removeClass();
        //$('#ref_doc').prop('disabled',true);
    }
});

function getTotal(y, e) {
    e.preventDefault();
//	console.log(y.getAttribute('href'));
    href = y.getAttribute('href');
    sendData = {
        // patient_name: $('#patient_name_list_flag').val(),
        // patient_id: $('#patient_id_list_flag').val(),
        // mr_num: $('#mr_num_sp').val(),
        // from_date: $('#from_date_sp').val(),
        // to_date: $('#to_date_sp').val(),
        ci_csrf_token: $("input[name='ci_csrf_token']").val()
    }


    $('#accordion fieldset input').each(function (i, j) {
        var name = $(j).attr('name');
        var value = $(j).val();
        sendData[name] = value;
        //console.log(name+' '+val);
    })

    $('#accordion fieldset select').each(function (i, j) {
        var name = $(j).attr('name');
        var value = $(j).val();
        sendData[name] = value;
        //console.log(name+' '+val);
    })
    //console.log(sendData);
    $.ajax({
        url: siteURL + href,
        data: sendData,
        type: "POST",
        success: function (response) {
            console.log(response);
            if (response == 'false') {
                //console.log('YYYYY');
                //console.log(response);
            } else {
                $('p#temp-data').remove();
                //console.log(response);
                //response = JSON.parse(response);
                //mem = '<p id="temp-data" hidden>Total Bill: '+response.tot_bill+' &nbsp;&nbsp;&nbsp; Net Bill: '+response.net_bill+' &nbsp;&nbsp;&nbsp; Paid Amount: '+ response.paid_amount+' &nbsp;&nbsp;&nbsp; Due: '+response.due+'</p>';
                mem = '<p id="temp-data" hidden>' + response + '</p>';

                $('#messages').after(mem);
                if (mem.search('null') > 0 || mem.search('undefined') > 0) {
                    $('#disp-total').attr('hidden', true);
                } else {
                    $('#disp-total').attr('hidden', false);
                }
                $('#disp-total').text($('p#temp-data').text());
            }
        }
    })

}



