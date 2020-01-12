// JavaScript Document

$(".phonenumber").inputmask({"mask": "9", "repeat": '11'});

$('.employeeCreateForm a').click(function (e) {
    e.preventDefault();

    var url = $(this).attr("data-url");
    var href = this.hash;
    var pane = $(this);

    // ajax load from data-url
    $(href).load(url, function (result) {
        pane.tab('show');

        $().ready(function (e) {
            $(".form-horizontal").validator();

            jQuery(function (e) {
                var $el = jQuery('#employee-policy-tagging-form');
                if ($el.size()) {
                    (new employeePolicyTaggingInTab($el)).init();
                }
            });

            var $el = $(result).find('#employeeEducationInnerHTML');
            if ($el.size()) {
                viewEducation();
            }

        })
    });
});


$(document).ready(function () {
    var href = $('#tab_active').val();
    var url = $('#tab_url').val();
    var id = $('#id').val();
    var url = siteURL + url + id;
    //var pane = $(this);

    // ajax load from data-url
    $(href).load(url, function (result) {
        $(".datepickerCommon").datepicker();
        $('.employeeCreateForm a[href="' + href + '"]').tab('show');

        if (id > 0) {
            $("li").removeClass("disabled");
            $("a").removeClass("tab-disabled");
        }

        $("div").removeClass("tab-pane active").addClass("tab-pane");
        $(href).addClass("tab-pane active");
        $().ready(function () {
            $(".form-horizontal").validator();
        })
    });
});


// Nasir  create = 22_9_15  modify =04-10-15   start email========
function emailCheck() {

    var employee_email = $.trim($('#employee_email_address').val());
    var ci_csrf_token = $("input[name='ci_csrf_token']").val();

    if (employee_email != "") {

        $("#checkEmail").show();
        var targetUrl = "employee/hrm/checkEmailAjax";

        var sendData = {employee_email: employee_email, ci_csrf_token: ci_csrf_token};
        setInnerHTMLAjax(siteURL + targetUrl, sendData, "#checkEmail");
    }
    else {
        $("#checkEmail").hide();
    }
}

// Nasir  create = 22_9_15  modify =04-10-15   end email========


// Nasir  create = 29_9_15  modify =04-10-15   start National Id========
function nationalidCheck() {

    var employeeNationalid = $.trim($('#hrm_employee_national_id').val());
    var ci_csrf_token = $("input[name='ci_csrf_token']").val();

    if (employeeNationalid != "") {
        $("#checkNationalid").show();
        var targetUrl = "employee/hrm/checkNationalidAjax";
        var sendData = {employeeNationalid: employeeNationalid, ci_csrf_token: ci_csrf_token};
        setInnerHTMLAjax(siteURL + targetUrl, sendData, "#checkNationalid");
    }
    else {
        $("#checkNationalid").hide();
    }
}

// Nasir  create = 29_9_15  modify =04-10-15   end National Id========


// Nasir  create = 29_9_15  modify =04-10-15   start Passport========
function passportCheck() {
    var employeePassportNo = $.trim($('#hrm_employee_passport_no').val());
    var ci_csrf_token = $("input[name='ci_csrf_token']").val();

    if (employeePassportNo != "") {
        $("#checkPasspost").show();
        var targetUrl = "employee/hrm/checkPassportAjax";
        var sendData = {employeePassportNo: employeePassportNo, ci_csrf_token: ci_csrf_token};
        setInnerHTMLAjax(siteURL + targetUrl, sendData, "#checkPasspost");
    }
    else {
        $("#checkPasspost").hide();
    }
}

// Nasir  create = 29_9_15  modify =04-10-15   end Passport========

// Nasir  create = 29_9_15  modify =04-10-15   start Driving Licence========
function drivingLicencetCheck() {
    var employeeDrivingLicence = $.trim($('#hrm_employee_driving_licence').val());
    var ci_csrf_token = $("input[name='ci_csrf_token']").val();

    if (employeeDrivingLicence != "") {

        $("#checkDrivingLicence").show();
        var targetUrl = "employee/hrm/checkDrivingLicenceAjax";

        var sendData = {employeeDrivingLicence: employeeDrivingLicence, ci_csrf_token: ci_csrf_token};
        setInnerHTMLAjax(siteURL + targetUrl, sendData, "#checkDrivingLicence");

    }
    else {
        $("#checkDrivingLicence").hide();

    }
}

// Nasir  create = 29_9_15  modify =04-10-15  end Driving Licence========


/*============ Start Permanent Address =========*/

//============ For District List ================
function getDistrictList(divisionId) {
    //var divisionId = $(this).val();
    var ci_csrf_token = $("input[name='ci_csrf_token']").val();
    if (divisionId == "" || divisionId == 0) {
        return;
    }
    var targetUrl = "area/library/getDistrictListAjax";
    var sendData = {divisionId: divisionId, ci_csrf_token: ci_csrf_token};
    var FirstOP = "<option value=''>Select One</option>";
    var isJSON = 1;
    //alert(divisionId);
    setInnerHTMLAjax(siteURL + targetUrl, sendData, "#employee_permanent_district", FirstOP, isJSON);
}


//============ For Police Station (Area) ================
function getPoliceStation(districtId) {
    var divisionId = $("#employee_permanent_division").val();

    var ci_csrf_token = $("input[name='ci_csrf_token']").val();
    if (divisionId == "" || divisionId == 0) {
        return;
    }
    var targetUrl = "trtarea/library/getAreaListAjax";
    var sendData = {divisionId: divisionId, districtId: districtId, ci_csrf_token: ci_csrf_token};
    var FirstOP = "<option value=''>Select One</option>";
    var isJSON = 1;
    //alert(divisionId+" "+districtId);
    setInnerHTMLAjax(siteURL + targetUrl, sendData, "#employee_permanent_police_station", FirstOP, isJSON);
}


//============ For Police Station (TRT Area) ================
function getPermanentPostOffice(areaId) {
    var divisionId = $("#employee_permanent_division").val();
    var districtId = $("#employee_permanent_district").val();


    var ci_csrf_token = $("input[name='ci_csrf_token']").val();
    if (areaId == "" || areaId == 0) {
        return;
    }
    var targetUrl = "employee/hrm/getTRTAreaListAjax";

    var sendData = {divisionId: divisionId, districtId: districtId, areaId: areaId, ci_csrf_token: ci_csrf_token};
    var FirstOP = "<option value=''>Select One</option>";
    var isJSON = 1;
    //alert(divisionId+" "+districtId);
    setInnerHTMLAjax(siteURL + targetUrl, sendData, "#employee_permanent_post_office", FirstOP, isJSON);
}


/*============== End Permanent Address ========*/


/*============= Start Mailing Address ======*/

//============ For District List ================
function getMailingDistrictList(divisionId) {
    //var divisionId = $(this).val();
    var ci_csrf_token = $("input[name='ci_csrf_token']").val();
    if (divisionId == "" || divisionId == 0) {
        return;
    }
    var targetUrl = "area/library/getDistrictListAjax";
    var sendData = {divisionId: divisionId, ci_csrf_token: ci_csrf_token};
    var FirstOP = "<option value=''>Select One</option>";
    var isJSON = 1;
    //alert(divisionId);
    setInnerHTMLAjax(siteURL + targetUrl, sendData, "#employee_mailing_district", FirstOP, isJSON);
}


//============ For Police Station (Area) ================
function getMailingPoliceStation(districtId) {
    var divisionId = $("#employee_mailing_division").val();
    var districtId = $("#employee_mailing_district").val();

    var ci_csrf_token = $("input[name='ci_csrf_token']").val();
    if (divisionId == "" || divisionId == 0) {
        return;
    }
    var targetUrl = "trtarea/library/getAreaListAjax";
    var sendData = {divisionId: divisionId, districtId: districtId, ci_csrf_token: ci_csrf_token};
    var FirstOP = "<option value=''>Select One</option>";
    var isJSON = 1;
    //alert(divisionId+" "+districtId);
    setInnerHTMLAjax(siteURL + targetUrl, sendData, "#employee_mailing_police_station", FirstOP, isJSON);
}

//============ For Police Station (TRT Area) ================
function getMailingPostOffice(areaId) {
    var divisionId = $("#employee_mailing_division").val();
    var districtId = $("#employee_mailing_district").val();

    var ci_csrf_token = $("input[name='ci_csrf_token']").val();
    if (areaId == "" || areaId == 0) {
        return;
    }
    var targetUrl = "employee/hrm/getTRTAreaListAjax";
    var sendData = {divisionId: divisionId, districtId: districtId, areaId: areaId, ci_csrf_token: ci_csrf_token};
    var FirstOP = "<option value=''>Select One</option>";
    var isJSON = 1;
    //alert(divisionId+" "+districtId);
    setInnerHTMLAjax(siteURL + targetUrl, sendData, "#employee_mailing_post_office", FirstOP, isJSON);
}

/*============= End Mailing Address ========*/

//====== Same As Mailing Address ========

function sameAsMailingAddress() {


    if ($('#sameas').is(':checked')) {
        //=== When checked====
        var permanent_division = $("#employee_permanent_division").val();
        $("#employee_mailing_division").prop('selectedIndex', permanent_division);

        var permanent_district = $("#employee_permanent_district").val();
        $("#employee_mailing_district").prop('selectedIndex', permanent_district);

        var permanent_police_station = $("#employee_permanent_police_station").val();
        $("#employee_mailing_police_station").val(permanent_police_station);


        var permanent_village = $("#employee_permanent_village").val();
        $("#employee_mailing_village").val(permanent_village);

        var employee_permanent_post_office = $('#employee_permanent_post_office').val();
        $("#employee_mailing_post_office").val(employee_permanent_post_office);

        var permanent_city_village_bengali = $("#permanent_city_village_bengali").val();
        $("#present_city_town_bengali").val(permanent_city_village_bengali);

    } else {
        //=== When Unchecked====
        $("#employee_mailing_division").prop('selectedIndex', "");
        $("#employee_mailing_district").prop('selectedIndex', "");
        $("#employee_mailing_police_station").prop('selectedIndex', "");
        $("#employee_mailing_post_office").prop('selectedIndex', "");
        $("#employee_mailing_village").val("");
        $("#present_city_town_bengali").val('');

    }
}

function addEducation() {
    var employeeId = $("#empId").val();
    //alert(employeeId );
    var examName = $("#emp_qualification_exam_name").val();
    var boardName = $("#emp_qualification_board_name").val();
    var passYaer = $("#emp_qualification_pass_yaer").val();
    var score = $("#emp_qualification_score").val();
    var cgpa = $("#emp_qualification_cgpa").val();
    var examResult = $("#emp_qualification_exam_result").val();
    var ci_csrf_token = $("input[name='ci_csrf_token']").val();
    var targetUrl = "employee/hrm/educationAjax";

    var sendData = {
        ci_csrf_token: ci_csrf_token,
        employeeId: employeeId,
        examName: examName,
        boardName: boardName,
        passYaer: passYaer,
        score: score,
        cgpa: cgpa,
        examResult: examResult,

    };
    var isJSON = 0;
    if ((employeeId != '' & examName != '' & boardName != '' & passYaer != '' & score != '' & examResult != '')) {

        //evalDataByAjax(siteURL+targetUrl,sendData);


        setInnerHTMLAjax(siteURL + targetUrl, sendData, "#employeeEducationInnerHTML", "", isJSON, 0);

    }

}

function viewEducation() {
    var employeeId = $("#empId").val();
    var ci_csrf_token = $("input[name='ci_csrf_token']").val();
    var targetUrl = "employee/hrm/educationAjax";

    var sendData = {
        ci_csrf_token: ci_csrf_token,
        employeeId: employeeId,
        onlyView: 1
    };

    setInnerHTMLAjax(siteURL + targetUrl, sendData, "#employeeEducationInnerHTML", "", 0, 0);
}


function deleteEducation(educationId, empId) {

    var curriIdDelet = educationId;
    var employeeId = empId;

    var ci_csrf_token = $("input[name='ci_csrf_token']").val();
    var targetUrl = "employee/hrm/deleteService";

    var sendData = {
        ci_csrf_token: ci_csrf_token,
        employeeId: employeeId,
        curriIdDelet: curriIdDelet,

    };
    var isJSON = 0;
    if ((employeeId != '' & curriIdDelet != '')) {
        setInnerHTMLAjax(siteURL + targetUrl, sendData, "#employeeEducationInnerHTML", "", isJSON, 0);

    }

}


//================ Add job experience 
function addJobexperience(emp_id) {
    var employeeId = emp_id;
    var EMP_JOB_EXP_ID = $("#EMP_JOB_EXP_ID").val();
    var ORGANIZATION = $("#ORGANIZATION").val();
    var ORGANIZATION_BENGALI = $("#ORGANIZATION_BENGALI").val();
    var ORGANIZATION_ADDRESS = $("#ORGANIZATION_ADDRESS").val();
    var ORGANIZATION_ADDRESS_BENGALI = $("#ORGANIZATION_ADDRESS_BENGALI").val();
    var POSITION = $("#POSITION").val();
    var YEAR_START = $("#YEAR_START").val();
    var YEAR_END = $("#YEAR_END").val();
    var REASON_FOR_LEAVING = $("#REASON_FOR_LEAVING").val();
    var REASON_FOR_LEAVING_BENGALI = $("#REASON_FOR_LEAVING_BENGALI").val();
    var CONTACT_PERSON = $("#CONTACT_PERSON").val();
    var CONTACT_PERSON_BENGALI = $("#CONTACT_PERSON_BENGALI").val();
    var CONTACT_NUMBER = $("#CONTACT_NUMBER").val();
    var ci_csrf_token = $("input[name='ci_csrf_token']").val();
    var targetUrl = "employee/hrm/experienceAjax";

    var sendData = {
        ci_csrf_token: ci_csrf_token,
        employeeId: employeeId,
        EMP_JOB_EXP_ID: EMP_JOB_EXP_ID,
        ORGANIZATION: ORGANIZATION,
        ORGANIZATION_BENGALI: ORGANIZATION_BENGALI,
        ORGANIZATION_ADDRESS: ORGANIZATION_ADDRESS,
        ORGANIZATION_ADDRESS_BENGALI: ORGANIZATION_ADDRESS_BENGALI,
        POSITION: POSITION,
        YEAR_START: YEAR_START,
        YEAR_END: YEAR_END,
        REASON_FOR_LEAVING: REASON_FOR_LEAVING,
        REASON_FOR_LEAVING_BENGALI: REASON_FOR_LEAVING_BENGALI,
        CONTACT_PERSON: CONTACT_PERSON,
        CONTACT_PERSON_BENGALI: CONTACT_PERSON_BENGALI,
        CONTACT_NUMBER: CONTACT_NUMBER
    };
    var isJSON = 0;
    if ((ORGANIZATION != '' & POSITION != '' & YEAR_START != '' & YEAR_END != '' & REASON_FOR_LEAVING != '')) {
        setInnerHTMLAjax(siteURL + targetUrl, sendData, "#employeeJobExperienceInnerHTML", "", isJSON, 0);
        resetJobExperienceForm();
    }
}

//================ view job experience list
function showJobexperience(emp_id) {
    var employeeId = emp_id;
    var ci_csrf_token = $("input[name='ci_csrf_token']").val();
    var targetUrl = "employee/hrm/experienceAjax";
    var sendData = {
        ci_csrf_token: ci_csrf_token,
        employeeId: employeeId
    };
    var isJSON = 0;
    if ((employeeId != '')) {
        setInnerHTMLAjax(siteURL + targetUrl, sendData, "#employeeJobExperienceInnerHTML", "", isJSON, 0);
    }
}


// Edit Job experience 
function editExperience(employment_id, empId) {
    var employment_id_Edit = employment_id;

    if (employment_id_Edit > 0) {
        var employeeId = empId;
        var employment_id_Edit = employment_id;
        var employeeId = empId;
        var ci_csrf_token = $("input[name='ci_csrf_token']").val();
        var targetUrl = "employee/hrm/getJobExperienceAjax";
        var sendData = {employment_id_Edit: employment_id_Edit, employeeId: employeeId, ci_csrf_token: ci_csrf_token};
        var result = evalDataByAjax(siteURL + targetUrl, sendData);
    } else {
        resetJobExperienceForm();
    }
}

// END Edit job experience


// reset job experience form reset 
function resetJobExperienceForm() {
    $('#EMP_JOB_EXP_ID').val('');
    $('#job_experience_form')[0].reset();
}


// Delete job experience 
function deleteExperience(employment_id, empId) {
    var employment_id_Delete = employment_id;
    var employeeId = empId;
    var ci_csrf_token = $("input[name='ci_csrf_token']").val();
    var targetUrl = "employee/hrm/deleteEmployment";
    var sendData = {
        ci_csrf_token: ci_csrf_token,
        employeeId: employeeId,
        employment_id_Delete: employment_id_Delete,
    };
    var isJSON = 0;
    if ((employeeId != '' & employment_id_Delete != '')) {
        setInnerHTMLAjax(siteURL + targetUrl, sendData, "#employeeJobExperienceInnerHTML", "", isJSON, 0);
    }
}


//================ Add Training History
function addTraining(emp_id) {
    var employeeId = emp_id;
    var EMP_TRAINING_ID = $("#EMP_TRAINING_ID").val();
    var TRAINING = $("#TRAINING").val();
    var CONDUCTED_BY = $("#CONDUCTED_BY").val();
    var CONDUCTED_BY_BENGALI = $("#CONDUCTED_BY_BENGALI").val();
    var COMPLETION_DATE = $("#COMPLETION_DATE").val();
    var CERTIFICATE_FLAG = $('input:checkbox[name=CERTIFICATE_FLAG]:checked').val();
    var ci_csrf_token = $("input[name='ci_csrf_token']").val();
    var targetUrl = "employee/hrm/trainingAjax";

    var sendData = {
        ci_csrf_token: ci_csrf_token,
        employeeId: employeeId,
        TRAINING: TRAINING,
        CONDUCTED_BY: CONDUCTED_BY,
        CONDUCTED_BY_BENGALI: CONDUCTED_BY_BENGALI,
        COMPLETION_DATE: COMPLETION_DATE,
        CERTIFICATE_FLAG: CERTIFICATE_FLAG,
        EMP_TRAINING_ID: EMP_TRAINING_ID,
    };
    var isJSON = 0;
    if ((TRAINING != '' & COMPLETION_DATE != '')) {
        setInnerHTMLAjax(siteURL + targetUrl, sendData, "#employeeTrainingInnerHTML", "", isJSON, 0);
        resetTrainingInfoForm();
    }
}

// reset after submit training info form
function resetTrainingInfoForm() {
    $('#EMP_TRAINING_ID').val('');
    $('#training_info_form')[0].reset();
}


//================ view Training History
function showTraining(emp_id) {
    var employeeId = emp_id;
    var ci_csrf_token = $("input[name='ci_csrf_token']").val();
    var targetUrl = "employee/hrm/trainingAjax";
    var sendData = {
        ci_csrf_token: ci_csrf_token,
        employeeId: employeeId,
    };
    var isJSON = 0;

    if ((employeeId != '')) {
        setInnerHTMLAjax(siteURL + targetUrl, sendData, "#employeeTrainingInnerHTML", "", isJSON, 0);
    }
}

// edit Training History
function editTrainingHistory(employment_id) {
    var training_id_Edit = employment_id;

    if (training_id_Edit > 0) {

        var ci_csrf_token = $("input[name='ci_csrf_token']").val();
        var targetUrl = "employee/hrm/getTrainingAjax";
        var sendData = {
            training_id_Edit: training_id_Edit,
            ci_csrf_token: ci_csrf_token
        };
        var result = evalDataByAjax(siteURL + targetUrl, sendData);

    } else {
        resetTrainingInfoForm();
    }
}


// Delete Training History
function deleteTrainingHistory(training_id, empId) {
    var training_id_Delete = training_id;
    var employeeId = empId;
    var ci_csrf_token = $("input[name='ci_csrf_token']").val();
    var targetUrl = "employee/hrm/deleteTraining";
    var sendData = {
        ci_csrf_token: ci_csrf_token,
        employeeId: employeeId,
        training_id_Delete: training_id_Delete,
    };
    var isJSON = 0;
    if ((employeeId != '' & training_id_Delete != '')) {
        setInnerHTMLAjax(siteURL + targetUrl, sendData, "#employeeTrainingInnerHTML", "", isJSON, 0);
    }
}


//================ Add Family info Start ========================

//Add Family History
function addFamilyInfo(emp_id) {
    var employeeId = emp_id;
//	var EMP_family_ID   			= $("#EMP_family_ID").val();
    var NAME = $.trim($("#NAME").val());
//	var NAME_BENGALI   	    		= $.trim($("#NAME_BENGALI").val());
    var RELATION = $("#RELATION").val();
//	var BIRTH_DATE   	       		= $("#BIRTH_DATE").val();
//	var AGE   	   	 				= $("#AGE").val();
    var OCCPATION = $("#OCCPATION").val();
    var NID = $("#NATIONAL_ID").val();
    var file_data = $('#hrm_family_photo').prop('files')[0];
//	var photo   		   				= $("#hrm_employee_photo").val();
//	var OCCPATION_BENGALI   		= $("#OCCPATION_BENGALI").val();
    var CONTACT_NO = $("#CONTACT_NO").val();
    var EMP_FAMILY_ID_TARGET = $("#EMP_FAMILY_ID_TARGET").val();
    var ci_csrf_token = $("input[name='ci_csrf_token']").val();
    var targetUrl = "employee/hrm/familyInfoAjax";
    var sendData = {
        ci_csrf_token: ci_csrf_token,
        employeeId: employeeId,
        NAME: NAME,
        RELATION: RELATION,
        OCCPATION: OCCPATION,
        NID: NID,
        hrm_family_photo:file_data,
        CONTACT_NO: CONTACT_NO,
        EMP_FAMILY_ID_TARGET: EMP_FAMILY_ID_TARGET
    };

    var isJSON = 0;
    if ((NAME != '' & RELATION != '' & CONTACT_NO != '')) {
        setInnerHTMLAjax(siteURL + targetUrl, sendData, "#employeeFamilyInfoInnerHTML", "", isJSON, 0);
        resetFamilyInfoForm();
    }
}


// reset after submit training info form
function resetFamilyInfoForm() {
    $('#EMP_FAMILY_ID_TARGET').val('');
    $('#familyInFoFrm')[0].reset();
}

// Edit Family History
function editFamilyInfo(EMP_FAMILY_ID, empId) {
    if (EMP_FAMILY_ID > 0) {
        var EMP_FAMILY_ID_EDIT = EMP_FAMILY_ID;
        var employeeId = empId;
        var ci_csrf_token = $("input[name='ci_csrf_token']").val();
        var targetUrl = "employee/hrm/getFamilyInfoAjax";
        var sendData = {EMP_FAMILY_ID_EDIT: EMP_FAMILY_ID_EDIT, employeeId: employeeId, ci_csrf_token: ci_csrf_token};
        var result = evalDataByAjax(siteURL + targetUrl, sendData);
    }
    else {
        resetFamilyInfoForm();
    }
}


// Delete Family History
function deleteFamilyInfo(EMP_FAMILY_ID, empId) {
    if (EMP_FAMILY_ID > 0) {
        var EMP_FAMILY_ID_DELETE = EMP_FAMILY_ID;
        var employeeId = empId;
        var ci_csrf_token = $("input[name='ci_csrf_token']").val();
        var targetUrl = "employee/hrm/deleteFamily";
        var sendData = {
            ci_csrf_token: ci_csrf_token,
            employeeId: employeeId,
            EMP_FAMILY_ID_DELETE: EMP_FAMILY_ID_DELETE,
        };
        var isJSON = 0;
    }
    if ((employeeId != '' & EMP_FAMILY_ID_DELETE != '')) {
        setInnerHTMLAjax(siteURL + targetUrl, sendData, "#employeeFamilyInfoInnerHTML", "", isJSON, 0);
    }
}

// age calculation 
function ageCalculation() {
    //=== input data process =====
    var inputValue = document.getElementById("BIRTH_DATE").value;

    //split "/" and convert to stringtime  from input value
    var birthYear = parseInt(inputValue.substring(6, 10));
    var birthMonth = parseInt(inputValue.substring(2, 0) - 1); //Jan 0
    var birthDay = parseInt(inputValue.substring(3, 5));

    //=== input data =====
    var birthYear;
    var age;

    var now = new Date();
    tday = now.getDate();
    tmo = (now.getMonth());
    tyr = (now.getFullYear());

    {
        if ((tmo > birthMonth) || (tmo == birthMonth & tday >= birthDay)) {
            age = birthYear
        }
        else {
            age = birthYear + 1
        }
        var ageValue = (tyr - age);
        // put the value desire field
        document.getElementById("AGE").value = ageValue;
    }
}

$(document).ready(function () {
    /*       get employee name by department name     */
    $("#BANK_NAME").on("change", function () {
        var ci_csrf_token = $("input[name='ci_csrf_token']").val();
        var id = $(this).val();
        var sendData = {id: id, ci_csrf_token: ci_csrf_token}
        //console.log(sendData);
        var targetUrl = siteURL + "employee/hrm/getBranchNameByBankId";
        $("#BRANCH_ID").html('<option>Loading...</option>');
        $.ajax({
            url: targetUrl,
            type: "POST",
            data: sendData,
            dataType: "json",
            success: function (response) {
                console.log(response);
                $("#BRANCH_ID").html(response);
            }, error: function (jqXHR) {
                console.log(jqXHR);
                showMessages('Unknown Error!!!', 'error');
            }
        });
    });
});


$(document).on('click', '.add-new-post-office', function () {
    var data = {};
    data.division = $('#employee_permanent_division').val();
    data.district = $('#employee_permanent_district').val();
    data.police_station = $('#employee_permanent_police_station').val();
    //console.log(data);
    if (data.division == '' || data.district == '' || data.police_station == '') {
        var mass = 'Please Fill Required <b>';
        var i = 0;
        for (v in data) {
            if (data[v] == '') {
                if (i > 0) {
                    mass += ', ' + v.substring(0, 1).toUpperCase() + v.substring(1)
                } else {
                    mass += ' ' + v.substring(0, 1).toUpperCase() + v.substring(1)
                }

            }
            i++;
        }
        mass += '</b> First';
        alertMessage(mass);
        return false;
    }

    var targetUrl = siteURL + '/trtarea/library/modal_create';
    data.ci_csrf_token = $('input[name="ci_csrf_token"]').val();
    data.from = 1;

    $('#commonModalTitle').html('Add Your Post Office');
    $('#commonModalBody').html('<div class="loader"></div>');
    $('#commonModalFooter').remove();
    $('#commonModal').modal('show');
    $.post(targetUrl, data, function (data) {
        $('#commonModalBody').html(data);
    })
})

$(document).on('submit', '.post-office-add-form', function (e) {
    e.preventDefault();
    var sendData = $(this).serialize() + '&from=1&save=1';
    var targetUrl = $(this).attr('action');
    //console.log(sendData);
    $.post(targetUrl, sendData, function (data) {
        //console.log(data);
        $(data.position_id).html(data.options);
        $('#employee_mailing_post_office').html(data.all_options).trigger('change');
        $(data.position_id).val(data.insert_id).trigger('change');
        $('#commonModal').modal('hide');
    }, 'json')
})

$(document).on('click', '.permanent-post-office', function () {
    var id = $(this).attr('id');
    $('#employee_permanent_post_office').val(id).trigger('change');
    $('#commonModal').modal('hide');
})

///Mailing post office js
$(document).on('click', '.add-new-mailing-post-office', function () {
    var data = {};
    data.division = $('#employee_mailing_division').val();
    data.district = $('#employee_mailing_district').val();
    data.police_station = $('#employee_mailing_police_station').val();
    //console.log(data);
    if (data.division == '' || data.district == '' || data.police_station == '') {
        var mass = 'Please Fill Required <b>';
        var i = 0;
        for (v in data) {
            if (data[v] == '') {
                if (i > 0) {
                    mass += ', ' + v.substring(0, 1).toUpperCase() + v.substring(1)
                } else {
                    mass += ' ' + v.substring(0, 1).toUpperCase() + v.substring(1)
                }

            }
            i++;
        }
        mass += '</b> First';
        alertMessage(mass);
        return false;
    }

    var targetUrl = siteURL + '/trtarea/library/modal_create';
    data.ci_csrf_token = $('input[name="ci_csrf_token"]').val();
    data.from = 2;

    $('#commonModalTitle').html('Add Your Post Office');
    $('#commonModalBody').html('<div class="loader"></div>');
    $('#commonModalFooter').remove();
    $('#commonModal').modal('show');
    $.post(targetUrl, data, function (data) {
        $('#commonModalBody').html(data);
    })
})

$(document).on('submit', '.mailing-post-office-add-form', function (e) {
    e.preventDefault();
    var sendData = $(this).serialize() + '&from=2&save=1';
    var post_office = $('input[name="library_zone_trt_name"]').val();
    //console.log(sendData);return false;
    var targetUrl = $(this).attr('action');
    //console.log(sendData);
    $.post(targetUrl, sendData, function (data) {
        //console.log(data);
        $(data.position_id).html(data.options).trigger('change');
        $(data.position_id).val(data.insert_id).trigger('change');
        $('#employee_permanent_post_office').append('<option value="' + data.insert_id + '">' + post_office + '</option>');
        $('#commonModal').modal('hide');
    }, 'json')
})
$(document).on('click', '.mailing-post-office', function () {
    var id = $(this).attr('id');
    $('#employee_mailing_post_office').val(id).trigger('change');
    $('#commonModal').modal('hide');
})