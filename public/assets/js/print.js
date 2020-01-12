
//======= Start Print Function =======
function PrintElem(elem)
{
    Popup($(elem).html());
}

function Popup(data)
{
    var mywindow = window.open('', 'printArea', 'height=1150, width=820');
    mywindow.document.write('<html><head><title>Print Prescription</title>');
    mywindow.document.write('<link rel="stylesheet" href="'+base_url+'asset/css/bootstrap3.3.7.css" type="text/css" />');
    mywindow.document.write('<script src="'+base_url+'asset/js/bootstrap.min.js"></script>');
    mywindow.document.write('<script src="'+base_url+'asset/js/jquery.min.js"></script>');

    //mywindow.document.write('< style> table > tr,th, td{font-size: 11px !important;}</style>');
    //mywindow.document.write('</ head><body style="font-size: 11px;">');
    mywindow.document.write('</ head><body>');
    mywindow.document.write(data);
    mywindow.document.write('</ body></html>');

    mywindow.document.close(); // necessary for IE >= 10
    mywindow.focus(); // necessary for IE >= 10

    mywindow.print();
    mywindow.close();

    return true;
}