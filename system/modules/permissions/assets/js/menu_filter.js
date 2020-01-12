// Filterable Menu Permissions List
    // @author A. H. Abid
    $('.filter_menu_actions').on('change', function(e){

        var menuModuleId = $('#filter_menu_module').val(),
            menuModuleItemId = $('#filter_menu_module_item').val(),
            menuModuleItemSubId = $('#filter_menu_module_item_sub').val(),
            tableRowsObj = $('#menu_permissions_tbl tbody tr');


        if (menuModuleId) {
            $('#filter_menu_module_item').find('option[data-parent-id!="'+menuModuleId+'"]').hide();
            $('#filter_menu_module_item').find('option[value=""],option[data-parent-id="'+menuModuleId+'"]').show();
        }

        if (menuModuleItemId) {
            $('#filter_menu_module_item_sub').find('option[data-parent-id!="'+menuModuleItemId+'"]').hide();
            $('#filter_menu_module_item_sub').find('option[value=""],option[data-parent-id="'+menuModuleItemId+'"]').show();
        }

        if (menuModuleId == "" && menuModuleItemId == "" && menuModuleItemSubId == "") {
            $('#filter_menu_module_item').find('option').show();
            $('#filter_menu_module_item_sub').find('option').show();
            tableRowsObj.css({display: ''});
            return;
        }

        tableRowsObj.each(function(i, row){
            var $row = $(row),
                showRow = false,
                rowModuleId = $row.attr('data-module-id'),
                rowItemId = $row.attr('data-item-id'),
                rowItemSubId = $row.attr('data-item-sub-id');

            if (menuModuleId && menuModuleItemId && menuModuleItemSubId) {
                showRow = (rowModuleId == menuModuleId && rowItemId == menuModuleItemId && rowItemSubId == menuModuleItemSubId);
            }
            else if (menuModuleId && menuModuleItemSubId) {
                showRow = (rowModuleId == menuModuleId && rowItemSubId == menuModuleItemSubId);
            }
            else if (menuModuleId && menuModuleItemId) {
                showRow = (rowModuleId == menuModuleId && rowItemId == menuModuleItemId);
            }
            else if (menuModuleItemId && menuModuleItemSubId) {
                showRow = (rowItemId == menuModuleItemId && rowItemSubId == menuModuleItemSubId);
            }
            else if (menuModuleItemSubId) {
                showRow = (rowItemSubId == menuModuleItemSubId);
            }
            else if (menuModuleItemId) {
                showRow = (rowItemId == menuModuleItemId);
            }
            else if (menuModuleId) {
                showRow = (rowModuleId == menuModuleId);
            }

            $row.css({display: showRow ? '' : 'none' });
        });
    });