/* global siteURL, ci_csrf_token */

var employeePolicyTaggingInTab = function(el){
    var $ = jQuery;
    var _this = this;
    this.$el = $(el);

    this.init = function() {
        _this.$el.on('change', '.check_policy', _this.checkPolicy);
        _this.$el.on('click', '#employeeSearch', _this.taggingEmployeeSearch);
        _this.$el.on('click', '#Save', _this.savePolicyTaggingInfo);

    }

    _this.checkPolicy = function() {
        checkCon = 3;
        $(this)
            .closest('.row')
            .children('div:nth-child('+checkCon+')')
            .children('div')
            .children('select.a')
            .prop('disabled', !this.checked).val('');
    };

    _this.taggingEmployeeSearch = function() {
        var empId = $('#employee_emp_id').val();
        var empName = $('#employee_name').val();
        var empDept = $('#emp_department').val();
        var empWithPolicy = $('#with_policy_name').val();
        var empWithputPolicy = $('#without_policy_name').val();
        var targetUrl = "policy_tagging/hrm/getEmployeeSearchAjax";
        var ci_csrf_token 	= $("input[name='ci_csrf_token']").val();

        var sendData  = {
            ci_csrf_token:ci_csrf_token,
            empId:empId,
            empName:empName,
            empDept:empDept,
            empWithPolicy:empWithPolicy,
            empWithoutPolicy:empWithputPolicy
        };

        setInnerHTMLAjax(siteURL+targetUrl, sendData, "#show_res");

        var empId = $('#employee_emp_id').val('');
        var empName = $('#employee_name').val('');
        var empDept = $('#emp_department').val('');
        var empWithPolicy = $('#with_policy_name').val('');
        var empWithputPolicy = $('#without_policy_name').val('');
    };

    _this.savePolicyTaggingInfo = function(e) {
        var empId = '';
        var policyType = '';
        var policyId = '';
        e.preventDefault();

        $('table.showtable tr').each(function(){
            if($(this).find('input.empCheckid').is(':checked') == true){
                var employeeId = $(this).find('input.empCheckid').val();
                empId += empId=='' ? employeeId : ','+employeeId;
            }
        });

        $('.policyTrackingPanel').find('.policySelector').each(function(){
            if( $(this).find('input.check_policy').is(':checked') == true ) {
                var policy_type = $(this).find('input.check_policy').val();
                policyType += policyType=='' ? policy_type : ','+policy_type;

                var policy_id = parseInt($(this).find('select').val());
                if (isNaN(policy_id) || policy_id == ''){
                    alert("Please Select Policy Name");
                    return false;
                }
                policyId += policyId=='' ? policy_id : ','+policy_id;
            }
        });

       // console.log("policy id ="+ policyType);return;
        if (policyType == '') {
            alert("Please Check Policy Type");
            return false;
        }
        if (policyId == '') {
            alert("Please Select Policy Name");
            return false;
        }
        if (empId == '') {
            alert("Please Check Employee Name");
            return;
        }
        var targetUrl = "policy_tagging/hrm/savePolicyDetailsAjax";
        var ci_csrf_token 	= $("input[name='ci_csrf_token']").val();

        sendData  = {
            ci_csrf_token:ci_csrf_token,
            empId:empId,
            policyType:policyType,
            policyId:policyId
        };

        $.ajax({
            type: "POST",
            url: siteURL+targetUrl, //calling method in controller
            data: sendData,
            dataType:'json',
            success: function (response) {
               // console.log("response =" + response);
                if (response.success == true) {
                    //window.location(siteURL+"/policy_tagging/hrm/create");
                    window.location.href = siteURL+"policy_tagging/hrm/create";
                    /*$('table.showtable').find('tbody input[type="checkbox"]:checked').closest('tr').each(function(){
                        //var $row = $(tr);
                        //console.log($(this).find('.medical').html('<span class="label label-success">Yes</span>'));
                        $(this).find('.medical').html('<span class="label label-success">Yes</span>');
                    }); */
                    //var empArr = empId.split(',');
                    //var policyTypeArr = policyType.split(',');
                   // var empArrPush = [];
                   // var policyTypeArrPush = [];
                   /* for(i=0; i < empArr.length; i++) {
                         empArrPush = empArr[i];
                         $('table.showtable [value='+empArrPush+']').closest('tr').html('<span class="label label-success">Yes</span>');
                    } */
                   // console.log("empArr= "+empArrPush);
                   // $(this).closest('tr').find('.dd_amount').val(d_amount);
                   //var _this = $("table.sample_list [index="+master_id+"]").closest('tr');

                    showMessages(response.message, 'success');
                } else {
                    showMessages(response.message, 'error');
                }
            }, error: function (jqXHR) {
                showMessages('Unknown Error!!!', 'error');
            }
        });

        $('select.a').val('');
        $('select.a').attr('disabled','disabled');
        if( $('select.a').attr('disabled',false)){
            $('select.a').attr('disabled','disabled'); }
        if( $('.check_policy').attr('checked', true)){
            $('.check_policy').attr('checked', false); }
        if( $('td.column-check input:checkbox').attr('checked', true)){
            $('td.column-check input:checkbox').attr('checked', false);
        };
        policyType = [];
        policyId = [];
        sendData = '';
    };
};

jQuery(function() {
   // var $el = jQuery('#employee-policy-tagging-form');
    var $el = jQuery('.common_search_form');
    if ($el.size()) {
        (new employeePolicyTaggingInTab($el)).init();
    }
});