const radioforsidebar=[];
let regionchart=[["Region","Number"]];
let maparr=[];
let stackedchartArr=[];

let USDollar = new Intl.NumberFormat('za-za', {
    style: 'currency',
    currency: 'Zar',
});
$(function () {

    $('.select2').select2()
    $('.select2bs4').select2({
        theme: 'bootstrap4'
    });
    $('.daterange').daterangepicker({
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        startDate: moment().subtract(29, 'days'),
        endDate: moment()
    }, function (start, end) {
        $("#dat1").val(start.format('Y-MM-DD'));
        $("#dat2").val(end.format('YYYY-MM-DD'));
        $("#datetxt").text(start.format('D MMM YYYY') + ' - ' + end.format('D MMM YYYY'));
        let lastclicked=$("#lastclicked").val();
        if(lastclicked==="")
        {
            $("#info").html("<h4 style='color: red' align='center'>Date selected but no Tab is selected</h4>");
        }
        else
        {

            $("#"+lastclicked).click();

        }
    });
    
});

const getDrops = () =>{

$.ajax({
            url: "clinicalAjax.php",
            beforeSend:function (xhr)
            {
                $(".spinner").show("fast");
            },
            type:"POST",
            data:obj,
            success: function(data){         

               
            },
            complete:function (xhr,status) {
                $(".spinner").hide("fast");
            },
            error:function (xhr,status,error) {
                alert("There is an error");
            }
        });
    }