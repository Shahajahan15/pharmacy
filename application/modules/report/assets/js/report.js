$('.errorlogLayout a').on('click', function(){
   $(this).closest('div').toggleClass('pL100').siblings().removeClass('pL100');
   $(this).closest('div').children('.errorlog').toggleClass('hide').siblings().removeClass('hide');
});
