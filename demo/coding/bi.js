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
    let objClass=new Proc();
    objClass.loadPanel();
});
function drawChart() {
    $("#mygraph").empty();
    var data = google.visualization.arrayToDataTable(regionchart);
    var options = {
        title: 'Hospital Regions',
        is3D: true,
    };
    $("#mygraph").show();
    var chart = new google.visualization.PieChart(document.getElementById('mygraph'));
    chart.draw(data, options);
}
function stackedChart() {
    $("#mygraph").empty();
    var data = google.visualization.arrayToDataTable(stackedchartArr);

    var options = {
        isStacked: true,
        height: 300,
        legend: {position: 'top', maxLines: 3},
        vAxis: {minValue: 0},
        series: {
            0:{color:'red'},
            1:{color:'green'},
            2:{color:'brown'},
            3:{color:'purple'},
            4:{color:'pink'},
            5:{color:'yellow'},
            6:{color:'blue'},
            7:{color:'orange'},
            8:{color:'darkblue'},
            9:{color:'grey'}
        }
    };

    $("#mygraph").show();
    var chart =  new google.charts.Bar(document.getElementById('mygraph'));
    chart.draw(data, google.charts.Bar.convertOptions(options));
}
function initialize() {
    var mapOptions = {
        zoom: 5,
        center: new google.maps.LatLng(-26.195246, 28.034088),
        mapTypeId: google.maps.MapTypeId.TERRAIN
    };
    $("#mymap").show();

    var map = new google.maps.Map(document.getElementById('mymap'), mapOptions);
    for(mapx in maparr)
    {
        let lat=maparr[mapx]["lat"];
        let lon=maparr[mapx]["lon"];
        let val=maparr[mapx]["val"];
        var marker = new google.maps.Marker({
            position: new google.maps.LatLng(lat, lon),
            map: map,
            label: val
        });
    }

}
class Proc
{
    constructor() {
        Proc.myvar = [];
        Proc.myvar1 = [];
        Proc.myHierach = [];
    }

    loadPanel()
    {
        let start_date=$("#dat1").val();
        let end_date=$("#dat2").val();
        let medical_scheme=$("#medical_scheme").val();
        let txtup=$("#txtup").val();
        let txtstatus=$("#txtstatus").val();
        let obj={identity_number:6,start_date:start_date,end_date:end_date,medical_scheme:medical_scheme,txtup:txtup,medical_scheme:medical_scheme,txtup:txtup,txtstatus:txtstatus};

        $.ajax({
            url: "ajax.php",
            beforeSend:function (xhr)
            {
                $(".spinner").show("fast");
            },
            type:"POST",
            data:obj,
            success: function(data){

                let json=JSON.parse(data);
                let  totals=json.totals;
                let  top=json.top;
                let  medical_aid=json.medical_aid;
                let count=0

                $.each(totals, function(index) {
                    let ui_name=totals[index].ui_name;
                    let field_name=totals[index].field_name;
                    let status=totals[index].status;
                    let type=totals[index].type;
                    if(status==="checked")
                    {
                        Proc.myvar.push(field_name);
                        Proc.myvar1.push(ui_name);
                    }
                });

                $.each(top, function(index) {
                    let ui_name=top[index].ui_name;
                    let table=top[index].table;
                    let status=top[index].status;
                    let downmenu="";
                    let ctoggle="";
                    let idx=ui_name.split(" ").join("_");
                    $.each(table, function(index1) {
                        let ux=table[index1].ui_name;
                        let id=ux.split(" ").join("_");
                        let condition=table[index1].condition;
                        if(status==="checked")
                        {
                            downmenu+="<div class=\"downmenu\">\n" +
                                "            <label><input id='"+id+"' class=\"uk-radio downradio\" type=\"radio\" name=\"radio2\" value='"+ux+"'> "+ux+"</label></div>";
                            ctoggle="ctoggle";
                        }
                        else {
                            ctoggle="downradio";
                        }
                        const inrad={"barheading":ux,"conditions":condition};
                        radioforsidebar.push(inrad);

                    });
                    $("#top_panel").append("<button id='"+idx+"' class='uk-button uk-button-default opt1 "+ctoggle+"'>"+ui_name+"</button><div></div>"+downmenu);

                });
                for(let key in medical_aid)
                {
                    $("#medical_scheme").append("<option>"+medical_aid[key]+"</option>");
                }

            },
            complete:function (xhr,status) {
                $(".spinner").hide("fast");
            },
            error:function (xhr,status,error) {
                alert("There is an error");
            }
        });

    }


}
const arrayColumn = (arr, n) => arr.map(x => x[n]);
const getbenefit = () => {
    let start_date=$("#dat1").val();
    let end_date=$("#dat2").val();
    let medical_scheme=$("#medical_scheme").val();
    let txtup=$("#txtup").val();
    let txtstatus=$("#txtstatus").val();
    let obj={identity_number:7,start_date:start_date,end_date:end_date,medical_scheme:medical_scheme,txtup:txtup,txtstatus:txtstatus};

    $.ajax({
        url: "ajax.php",
        beforeSend:function (xhr)
        {
            $("#bb").text("loading...");
        },
        type:"POST",
        data:obj,
        //stackedchartArr
        success: function(data){
            let  obj=JSON.parse(data);

            let ty="<table class=\"uk-table-striped myTable\"><thead><tr>";

            obj[0].unshift("Year");
            for (i in obj[0])
            {

                stackedchartArr.push(arrayColumn(obj, i));
                ty+="<th>"+obj[0][i]+"</th>";
            }
            ty+="<th>Grand Totals</th></tr></thead>";
            let c=delete obj[0];
            for(let key in obj)
            {

                let tot=0;
                ty+="<tr>";
                for (i in obj[key])
                {
                    ty+="<td>"+obj[key][i]+"</td>";
                    obj[key][0]=0;
                    tot+=obj[key][i];

                }
                ty+="<th>"+tot+"</th></tr>";

            }
            ty+="</table>";
            $("#info").html(ty);
            google.charts.load('current', {'packages':['bar']});
            google.charts.setOnLoadCallback(stackedChart);

        },
        complete:function (xhr,status) {
            $("#bb").empty();

        },
        error:function (xhr,status,error) {
            alert("There is an error");
        }
    });
}
const gethospitals = () => {
    let start_date=$("#dat1").val();
    let end_date=$("#dat2").val();
    let medical_scheme=$("#medical_scheme").val();
    let txtup=$("#txtup").val();
    let txtstatus=$("#txtstatus").val();
    let obj={identity_number:8,start_date:start_date,end_date:end_date,medical_scheme:medical_scheme,txtup:txtup,txtstatus:txtstatus};

    $.ajax({
        url: "ajax.php",
        beforeSend:function (xhr)
        {
            $("#bb").text("loading...");
        },
        type:"POST",
        data:obj,
        success: function(data){
            let  obj=JSON.parse(data);
            let ty="<table class=\"uk-table-striped myTable\"><thead><tr>";

            obj[0].unshift("Hospital");
            for (i in obj[0])
            {

                stackedchartArr.push(arrayColumn(obj, i));
                ty+="<th>"+obj[0][i]+"</th>";
            }
            ty+="<th>Grand Totals</th></tr></thead>";
            let c=delete obj[0];
            for(let key in obj)
            {

                let tot=0;
                ty+="<tr>";
                for (i in obj[key])
                {
                    ty+="<td>"+obj[key][i]+"</td>";
                    obj[key][0]=0;
                    tot+=obj[key][i];

                }
                ty+="<th>"+tot+"</th></tr>";

            }
            ty+="</table>";
            $("#info").html(ty);
            google.charts.load('current', {'packages':['bar']});
            google.charts.setOnLoadCallback(stackedChart);
        },
        complete:function (xhr,status) {
            $("#bb").empty();
        },
        error:function (xhr,status,error) {
            alert("There is an error");
        }
    });
}
const gethospitalsByRegion = () => {
    let start_date=$("#dat1").val();
    let end_date=$("#dat2").val();
    let medical_scheme=$("#medical_scheme").val();
    let txtup=$("#txtup").val();
    let txtstatus=$("#txtstatus").val();
    let obj={identity_number:9,start_date:start_date,end_date:end_date,medical_scheme:medical_scheme,txtup:txtup,txtstatus:txtstatus};

    $.ajax({
        url: "ajax.php",
        beforeSend:function (xhr)
        {
            $("#bb").text("loading...");
        },
        type:"POST",
        data:obj,
        success: function(data){
            let  obj=JSON.parse(data);

            let ty="<table class=\"uk-table-striped myTable\"><thead><tr><th>Region</th><th>Number of Hospitals</th></tr></thead>";

            for(let key in obj)
            {
                let inarr=[obj[key]["Region"],parseInt(obj[key]["tot"])]
                ty+="<tr><td>"+obj[key]["Region"]+"</td><td>"+obj[key]["tot"]+"</td></tr>";
                regionchart.push(inarr);
                let ov={"lat":obj[key]["lat"],"lon":obj[key]["lon"],"val":obj[key]["tot"]};
                maparr.push(ov);
            }
            ty+="</table>";
            $("#info").html(ty);
            google.charts.load("current", {packages:["corechart"]});
            google.charts.setOnLoadCallback(drawChart);
            initialize();

        },
        complete:function (xhr,status) {
            $("#bb").empty();
        },
        error:function (xhr,status,error) {
            alert("There is an error");
        }
    });
}
const getICD = () => {
    let start_date=$("#dat1").val();
    let end_date=$("#dat2").val();
    let medical_scheme=$("#medical_scheme").val();
    let txtup=$("#txtup").val();
    let txtstatus=$("#txtstatus").val();
    let obj={identity_number:12,start_date:start_date,end_date:end_date,medical_scheme:medical_scheme,txtup:txtup,txtstatus:txtstatus};

    $.ajax({
        url: "ajax.php",
        beforeSend:function (xhr)
        {
            $("#bb").text("loading...");
        },
        type:"POST",
        data:obj,
        success: function(data){
            let  obj=JSON.parse(data);
            let ty="<table class=\"uk-table-striped myTable\"><thead><tr><th>ICD-10</th><th>MCA GROUPER</th><th>Number of Claims</th><th>Claim Payout</th></tr></thead>";
            for(let key in obj)
            {

                let payout=fomata(parseFloat(obj[key]["payout"]).toFixed(2));
                ty+="<tr><td>"+obj[key]["shortdesc"]+"</td><td>"+obj[key]["ccs_grouper_desc"]+"</td><th>"+obj[key]["tot"]+"</th><th>"+payout+"</th></tr>";
            }
            ty+="</table>";
            $("#info").html(ty);
        },
        complete:function (xhr,status) {
            $("#bb").empty();
        },
        error:function (xhr,status,error) {
            alert("There is an error");
        }
    });
}
const getProviders = () => {
    let start_date=$("#dat1").val();
    let end_date=$("#dat2").val();
    let medical_scheme=$("#medical_scheme").val();
    let txtup=$("#txtup").val();
    let txtstatus=$("#txtstatus").val();
    let obj={identity_number:10,start_date:start_date,end_date:end_date,medical_scheme:medical_scheme,txtup:txtup,txtstatus:txtstatus};

    $.ajax({
        url: "ajax.php",
        beforeSend:function (xhr)
        {
            $("#bb").text("loading...");
        },
        type:"POST",
        data:obj,
        success: function(data){
            let  json=JSON.parse(data);
            const products=json["products"];
            const content=json["data"];
            let ty="<table class=\"uk-table-striped myTable\"><thead>";
            ty+="<tr><th th colspan='1'></th>";
            for(let x=0;x<products.length;x++)
            {
                ty+="<th colspan='2' style='text-align: center;'>"+products[x]+"</th>";
            }
            ty+="<th style='text-align: center;' colspan='2'>Total</th>";
            ty+="</tr></thead>";
            ty+="<tr><th>Speciality</th>";
            for(let x=0;x<products.length;x++)
            {
                ty+="<th>Count</th><th>Value</th>";
            }
            ty+="<th>Grand Count</th><th>Grand Value</th></tr>";
            for(key in content)
            {
                let grantcount=0;
                let grantvalue=0;
                let disciplinecode=content[key]["disciplinecode"];
                let discipline=content[key]["discipline"];
                let inp_products=content[key]["products"];

                let sps=disciplinecode+" - "+discipline;
                ty+="<tr>";
                ty+="<td>"+sps+"</td>";
                for(let x=0;x<products.length;x++)
                {
                    let myrad =  inp_products.filter(function(creature) {
                        return creature.product === products[x];
                    });

                    let countx=0;
                    let valuex=0;
                    if(myrad.length>0)
                    {
                        countx=parseFloat(myrad[0]["counts"]);
                        valuex=parseFloat(myrad[0]["value_payout"]);
                    }
                    grantcount+=countx;
                    grantvalue+=valuex;
                    valuex=valuex.toFixed(2);
                    valuex=`${USDollar.format(valuex)}`;
                    valuex=valuex.replace("ZAR","");

                    ty+="<td>"+countx+"</td><td>"+valuex+"</td>";
                }
                grantvalue=parseFloat(grantvalue).toFixed(2);
                grantvalue=`${USDollar.format(grantvalue)}`;
                grantvalue=grantvalue.replace("ZAR","");
                ty+="<td>"+grantcount+"</td><td>"+grantvalue+"</td>";
                ty+="</tr>";
            }
            ty+="</table>";
            $("#info").html(ty);

        },
        complete:function (xhr,status) {
            $("#bb").empty();
        },
        error:function (xhr,status,error) {
            alert("There is an error");
        }
    });
}
const getRegions = () => {
    let start_date=$("#dat1").val();
    let end_date=$("#dat2").val();
    let medical_scheme=$("#medical_scheme").val();
    let txtup=$("#txtup").val();
    let txtstatus=$("#txtstatus").val();
    let obj={identity_number:11,start_date:start_date,end_date:end_date,medical_scheme:medical_scheme,txtup:txtup,txtstatus:txtstatus};

    $.ajax({
        url: "ajax.php",
        beforeSend:function (xhr)
        {
            $("#bb").text("loading and analysing...");
        },
        type:"POST",
        data:obj,
        success: function(data){

            let  obj1=JSON.parse(data);
            let obj=obj1["data"];
            let myears=obj1["years"];
            let mproducts=obj1["products"];

            let ty="<table class=\"uk-table-striped myTable\"><thead>";
            ty+="<tr><th>Regions</th>";
            for(key in myears)
            {
                ty+="<th colspan='3' style='text-align: center;'>"+myears[key]+"</th>";
            }
            ty+="</tr><tr><th></th>";

            for(let x=0;x<myears.length;x++) {
                for (keyx in mproducts) {
                    ty += "<th>" + mproducts[keyx] + "</th>";
                }
            }
            ty+="</tr><tr><th></th>";
            for(let x=0;x<myears.length;x++) {
                for (keyx in mproducts) {
                    ty += "<th>Volume</th>";
                }
            }
            ty+="<tr></thead>";
            for(key in obj)
            {
                const yersarr=obj[key]["years"];
                ty+="<tr><td>"+obj[key]["region"]+"</td>";
                for(key1 in yersarr)
                {
                    const products=yersarr[key1]["products"];
                    for(key2 in products)
                    {
                        ty+="<td>"+products[key2]["val"]+"</td>";
                    }

                }
                ty+="</tr>";
            }
            ty+="</table>";
            $("#info").html(ty);
        },
        complete:function (xhr,status) {
            $("#bb").empty();
        },
        error:function (xhr,status,error) {
            alert("There is an error");
        }
    });
}
const searchTable = () => {
    const trs = document.querySelectorAll('.myTable tr:not(.header)')
    const filter = document.querySelector('#myInput').value
    const regex = new RegExp(filter, 'i')
    const isFoundInTds = td => regex.test(td.innerHTML)
    const isFound = childrenArr => childrenArr.some(isFoundInTds)
    const setTrStyleDisplay = ({ style, children }) => {
        style.display = isFound([
            ...children // <-- All columns
    ]) ? '' : 'none'
    }

    trs.forEach(setTrStyleDisplay)
}
const fomata=(number)=>{
    var nf = Intl.NumberFormat();
    return nf.format(number);
};

let objClass=new Proc();
const getGroupICD = () => {
    let start_date=$("#dat1").val();
    let end_date=$("#dat2").val();
    let medical_scheme=$("#medical_scheme").val();
    let txtup=$("#txtup").val();
    let txtstatus=$("#txtstatus").val();
    let obj={identity_number:13,start_date:start_date,end_date:end_date,medical_scheme:medical_scheme,txtup:txtup,txtstatus:txtstatus};

    $.ajax({
        url: "ajax.php",
        beforeSend:function (xhr)
        {
            $("#bb").text("loading...");
        },
        type:"POST",
        data:obj,
        success: function(data){

            const json=JSON.parse(data);

            const products=json["products"];
            const content=json["data"];
            const seamless=json["seamless"];
            let ty="<table class=\"uk-table-striped myTable\"><thead>";
            ty+="<tr><th th colspan='1'></th>";
            for(let x=0;x<products.length;x++)
            {
                ty+="<th colspan='4' style='text-align: center;'>"+products[x]+"</th>";
            }
            ty+="<th style='text-align: center;' colspan='4'>Total</th>";
            ty+="</tr></thead>";
            ty+="<tr><th>MCA Grouper</th>";
            for(let x=0;x<products.length;x++)
            {
                ty+="<th>Count</th><th>Value</th><th>Avg/Cost</th><th>Avg/Age</th>";
            }
            ty+="<th>Grand Count</th><th>Grand Value</th><th>Avg/Cost</th><th>Avg/Age</th></tr>";
            for(key in content)
            {
                let grantcount=0;
                let grantvalue=0;
                let grantage=0;
                let icd=content[key]["diag_code"];
                let icd_desc=content[key]["shortdesc"];
                let grouper=content[key]["ccs_grouper_desc"];
                let inp_products=content[key]["products"];
                let age=content[key]["age"];
                let totcount=parseFloat(seamless["call"]);
                let all=seamless["all"];

                icd=icd+" - "+icd_desc;
                ty+="<tr>";
                ty+="<td title='"+icd+"'><div class=\"uk-inline\"><button class=\"uk-button uk-button-default iicd\" type=\"button\">"+grouper+"</button><div uk-dropdown=\"mode: click\" class='icds'></div></div></td>";
                for(let x=0;x<products.length;x++)
                {
                    let myrad =  inp_products.filter(function(creature) {
                        return creature.product === products[x];
                    });
                    let ocount =  all.filter(function(creature) {
                        return creature.product === products[x];
                    });
                    let countx=0;
                    let valuex=0;
                    let inage=0;
                    if(myrad.length>0)
                    {
                        countx=parseFloat(myrad[0]["counts"]);
                        valuex=parseFloat(myrad[0]["value_payout"]);
                        inage=myrad[0]["age"];
                    }
                    grantcount+=countx;
                    grantvalue+=valuex;
                    let cost=((valuex/parseFloat(ocount[0]["count"]))*1000).toFixed(2);
                    valuex=valuex.toFixed(2);
                    valuex=`${USDollar.format(valuex)}`;
                    valuex=valuex.replace("ZAR","");
                    cost=`${USDollar.format(cost)}`;
                    cost=cost.replace("ZAR","");
                    ty+="<td>"+countx+"</td><td>"+valuex+"</td><td>"+cost+"</td><td>"+inage+"</td>";
                }
                let costpayout=((grantvalue/totcount)*1000).toFixed(2);
                grantvalue=parseFloat(grantvalue).toFixed(2);
                grantvalue=`${USDollar.format(grantvalue)}`;
                grantvalue=grantvalue.replace("ZAR","");
                costpayout=`${USDollar.format(costpayout)}`;
                costpayout=costpayout.replace("ZAR","");
                ty+="<td>"+grantcount+"</td><td>"+grantvalue+"</td><td>"+costpayout+"</td><td>"+age+"</td>";
                ty+="</tr>";
            }
            ty+="</table>";
            $("#info").html(ty);

        },
        complete:function (xhr,status) {
            $("#bb").empty();
        },
        error:function (xhr,status,error) {
            alert("There is an error");
        }
    });
}
const getGroupBenefit = () => {
    let start_date=$("#dat1").val();
    let end_date=$("#dat2").val();
    let medical_scheme=$("#medical_scheme").val();
    let txtup=$("#txtup").val();
    let txtstatus=$("#txtstatus").val();
    let obj={identity_number:12,start_date:start_date,end_date:end_date,medical_scheme:medical_scheme,txtup:txtup,txtstatus:txtstatus};

    $.ajax({
        url: "ajax.php",
        beforeSend:function (xhr)
        {
            $("#bb").text("loading...");
        },
        type:"POST",
        data:obj,
        success: function(data){
console.log(data);
            const json=JSON.parse(data);
            const products=json["products"];
            const content=json["data"];
            const seamless=json["seamless"];
            let totcount=parseFloat(seamless["call"]);
            let ty="<table class=\"uk-table-striped myTable\"><thead>";
            ty+="<tr><th></th>";
            for(let x=0;x<products.length;x++)
            {
                ty+="<th colspan='3' style='text-align: center;'>"+products[x]+"</th>";
            }
            ty+="<th style='text-align: center;' colspan='3'>Total</th>";
            ty+="</tr></thead>";
            ty+="<tr><th>Benefit</th>";
            for(let x=0;x<products.length;x++)
            {
                ty+="<th>Count</th><th>Value</th><th>Avg/Cost</th>";
            }
            ty+="<th>Grand Count</th><th>Grand Value</th><th>Avg/Cost</th></tr>";
            for(key in content)
            {
                let grantcount=0;
                let grantvalue=0;
                let claiminsureditem_benefittiers=content[key]["claiminsureditem_benefittiers"];
                let inp_products=content[key]["products"];
                let totcount=parseFloat(seamless["call"]);
                let all=seamless["all"];
                ty+="<tr>";
                ty+="<td>"+claiminsureditem_benefittiers+"</td>";
                for(let x=0;x<products.length;x++)
                {
                    let myrad =  inp_products.filter(function(creature) {
                        return creature.product === products[x];
                    });
                    let ocount =  all.filter(function(creature) {
                        return creature.product === products[x];
                    });
                    let countx=0;
                    let valuex=0;
                    if(myrad.length>0)
                    {
                        countx=parseFloat(myrad[0]["counts"]);
                        valuex=parseFloat(myrad[0]["value_payout"]);
                    }

                    grantcount+=countx;
                    grantvalue+=valuex;
                    let cost=((valuex/parseFloat(ocount[0]["count"]))*1000).toFixed(2);
                    valuex=valuex.toFixed(2);
                    valuex=`${USDollar.format(valuex)}`;
                    valuex=valuex.replace("ZAR","");
                    cost=`${USDollar.format(cost)}`;
                    cost=cost.replace("ZAR","");
                    ty+="<td>"+countx+"</td><td>"+valuex+"</td><td>"+cost+"</td>";
                }
                let costpayout=((grantvalue/totcount)*1000).toFixed(2);
                grantvalue=parseFloat(grantvalue).toFixed(2);
                grantvalue=`${USDollar.format(grantvalue)}`;
                grantvalue=grantvalue.replace("ZAR","");
                costpayout=`${USDollar.format(costpayout)}`;
                costpayout=costpayout.replace("ZAR","");
                ty+="<td>"+grantcount+"</td><td>"+grantvalue+"</td><td>"+costpayout+"</td>";
                ty+="</tr>";
            }
            ty+="</table>";
            $("#info").html(ty);

        },
        complete:function (xhr,status) {
            $("#bb").empty();
        },
        error:function (xhr,status,error) {
            alert("There is an error");
        }
    });
}

const getHighCostClaims = () => {
    let start_date=$("#dat1").val();
    let end_date=$("#dat2").val();
    let medical_scheme=$("#medical_scheme").val();
    let txtup=$("#txtup").val();
    let txtstatus=$("#txtstatus").val();
    let obj={identity_number:14,start_date:start_date,end_date:end_date,medical_scheme:medical_scheme,txtup:txtup,txtstatus:txtstatus};

    $.ajax({
        url: "ajax.php",
        beforeSend:function (xhr)
        {
            $("#bb").text("loading...");
        },
        type:"POST",
        data:obj,
        success: function(data){

            const json=JSON.parse(data);

            let ty="<table class=\"uk-table-striped myTable\"><thead><tr>";
            ty+="<th>Claim Number</th>";
            ty+="<th>Product Name</th>";
            ty+="<th>Value</th>";
            ty+="<th>ICD10 Codes</th>";
            ty+="<th>Speciality</th>";
            ty+="</tr></thead>";

            for(key in json)
            {
                let claim_id=json[key]["claim_id"];
                let claim_claimnumber=json[key]["claim_claimnumber"];
                let product=json[key]["product"];
                let payout=parseFloat(json[key]["payout"]);
                let icd10=json[key]["icd10"];
                let providers=json[key]["providers"];
                payout=payout.toFixed(2);
                payout=`${USDollar.format(payout)}`;
                payout=payout.replace("ZAR","");
//claiminsureditem_icdcode,p.shortdesc,p.ccs_grouper_desc
                ty+="<tr>";
                ty+="<td>"+claim_claimnumber+"</td>";
                ty+="<td>"+product+"</td>";
                ty+="<td>"+payout+"</td>";
                ty+="<td><button class=\"uk-button uk-button-default uk-float-left\" type=\"button\">View ICD10</button><div uk-dropdown=\"pos: bottom-left; target: !.target\">";
                ty+="<table style='background-color: floralwhite !important;'><tr><th>ICD10</th><th>Description</th><th>MCA Grouper</th></tr>";
                for (key1 in icd10)
                {
                    let claiminsureditem_icdcode=icd10[key1]["claiminsureditem_icdcode"];
                    let shortdesc=icd10[key1]["shortdesc"];
                    let ccs_grouper_desc=icd10[key1]["ccs_grouper_desc"];
                    ty+="<tr><td>"+claiminsureditem_icdcode+"</td><td>"+shortdesc+"</td><td>"+ccs_grouper_desc+"</td></tr>";
                }
                ty+="</table></div></td>";

                ty+="<td><button class=\"uk-button uk-button-default uk-float-left\" type=\"button\">View Provider</button><div uk-dropdown=\"pos: bottom-left; target: !.target\">";
                ty+="<table style='background-color: lightblue !important;'><tr><th>Practice Number</th><th>Name</th><th>Discipline Code</th><th>Discipline</th></tr>";
                for (key2 in providers)
                {
                    let claiminsureditem_icdcode=providers[key2]["claiminsureditem_icdcode"];
                    let practice_number=providers[key2]["practice_number"];
                    let fullname=providers[key2]["fullname"];
                    let disciplinecode=providers[key2]["disciplinecode"];
                    let discipline=providers[key2]["discipline"];
                    ty+="<tr><td>"+practice_number+"</td><td>"+fullname+"</td><td>"+disciplinecode+"</td><td>"+discipline+"</td></tr>";
                }
                ty+="</table></div></td>";
                ty+="</tr>";
            }

            ty+="</table>";

            $("#info").html(ty);

        },
        complete:function (xhr,status) {
            $("#bb").empty();
        },
        error:function (xhr,status,error) {
            alert("There is an error");
        }
    });
}
$(document).on('click','.opt1',function() {
    if($("#dat1").val().length<2)
    {
        $("#info").html("<h4 style='color: red' align='center'>Please select the date range first</h4>");
    }
    else {
        stackedchartArr = [];

        $(".opt1").removeClass("uk-button-danger");
        $(".opt1").addClass("uk-button-default");
        $(this).removeClass("uk-button-default");
        $(this).addClass("uk-button-danger");
        $(".col-sm-8").addClass("col-sm-10").removeClass("col-sm-8");
        $(".sidebar").slideUp();
        let bb1 = $(this).text();

        $("#info").empty();

        $("#mygraph").hide();
        $("#mymap").hide();
        $("#bb1").text(bb1);

        $("#lastclicked").val($(this).attr("id"));
        if ($(this).hasClass("ctoggle")) {
            $(".downmenu").slideToggle("fast");
        } else {
            $(".downmenu").hide("fast");
        }
        if (bb1 === "Benefit tier by Year") {
            getbenefit();
        } else if (bb1 === "Hospital with regions") {
            gethospitalsByRegion();
        } else if (bb1 === "Regions") {
            getRegions();
        } else if (bb1 === "High Cost Claims") {

            getHighCostClaims();
        } else if (bb1 === "Specialities") {

            getProviders();
        }
        let sect = $('input[name="myrad"]:checked').val();
        $("#txtup").val(sect);
    }
});
const selectSearched = (search) => {
    $("#searching").val(search);
    $("#txtup").val(search);
    $("#suggesstion-box").hide();
    let lastclicked=$("#lastclicked").val();
    $("#"+lastclicked).click();
}
$(document).on('change','#medical_scheme',function() {
    let lastclicked=$("#lastclicked").val();
    $("#"+lastclicked).click();

});
$(document).on('change','input[name="myrad"]',function() {
    let sect=$('input[name="myrad"]:checked').val();
    let lastclicked=$("#lastclicked").val();
    $("#txtup").val(sect);
    $("#searching").val("");
    $("#"+lastclicked).click();
});
$(document).on('change','input[name="mystatus"]',function() {
    let sect=$('input[name="mystatus"]:checked').val();
    let lastclicked=$("#lastclicked").val();
    $("#txtstatus").val(sect);
    $("#"+lastclicked).click();
});
$(document).on('click','.iicd',function() {
    $(".icds").empty();
 let ccsgrouper=$(this).text();
    var obj={
        identity_number:16,
        start_date:"",
        end_date:"",
        ccsgrouper:ccsgrouper
    };
    $.ajax({
        type: "POST",
        url: "ajax.php",
        data:obj,
        beforeSend: function(){
        },
        success: function(data){

       let json=JSON.parse(data);
       let tv="<ul>";
       for(keyx in json)
       {
           let icd=json[keyx]["diag_code"];
           let icd_desc=json[keyx]["shortdesc"];
           tv+="<li>"+icd+" -- "+icd_desc+"</li>";
       }
       tv+="</ul>";
            $(".icds").append(tv);
        }
    });
});
$(document).on('keyup','#searching',function() {
    var obj={
        identity_number:15,
        start_date:"",
        end_date:"",
        keyword:$(this).val(),
        lastclicked:$("#lastclicked").val()
    };
    $.ajax({
        type: "POST",
        url: "ajax.php",
        data:obj,
        beforeSend: function(){
        },
        success: function(data){
            $("#suggesstion-box").show();
            $("#suggesstion-box").html(data);
        }
    });
});
$(document).on('click','.downradio',function() {
    if($("#dat1").val().length<2)
    {
            $("#info").html("<h4 style='color: red' align='center'>Please select the date range first</h4>");
    }
    else {
        stackedchartArr = [];
        $("#mygraph").hide();
        $("#mymap").hide();

        $("#lastclicked").val($(this).attr("id"));
        let myval = $(this).val();
        if ($(this).hasClass("uk-button")) {
            myval = $(this).text();
        }
        $(".col-sm-10").addClass("col-sm-8").removeClass("col-sm-10");
        $(".sidebar").slideDown();
        $("#info").empty();
        var myrad = radioforsidebar.filter(function (creature) {
            return creature.barheading === myval;
        });

        if (myrad.length > 0) {
            let headd = myrad[0].barheading;
            $("#sidename").text(headd);
            if (headd === "Hospitals") {
                gethospitals();
            }
            if (headd === "ICD-10 grouped") {
                let sect = $('input[name="myrad"]:checked').val();

                getGroupICD();
            }
            if (headd === "Benefit Utilisation") {
                let sect = $('input[name="myrad"]:checked').val();

                getGroupBenefit();
            }

        }
        let sect = $('input[name="myrad"]:checked').val();
        $("#txtup").val(sect);
    }
});




