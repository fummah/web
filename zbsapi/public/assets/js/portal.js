let months_arr=[];
let values_arr=[];
$.ajax({
    url:"/getfunerals",
    type:"GET",
    async:false,
    data:{},
    success:function (data) {

        for(key in data)
        {
            months_arr.push(data[key]["months"]);
            values_arr.push(data[key]["totals"]);
        }
    },
    error:function(jqXHR, exception)
    {
        console.log("Connection error");
    }
});
var ctx1 = document.getElementById("chart-line").getContext("2d");

var gradientStroke1 = ctx1.createLinearGradient(0, 230, 0, 50);

gradientStroke1.addColorStop(1, 'rgba(251, 99, 64, 0.2)');
gradientStroke1.addColorStop(0.2, 'rgba(251, 99, 64, 0.0)');
gradientStroke1.addColorStop(0, 'rgba(251, 99, 64, 0)');
new Chart(ctx1, {
    type: "line",
    data: {
        labels: months_arr,
        datasets: [{
            label: "Number of Funerals",
            tension: 0.4,
            borderWidth: 0,
            pointRadius: 0,
            borderColor: "#fb6340",
            backgroundColor: gradientStroke1,
            borderWidth: 3,
            fill: true,
            data: values_arr,
            maxBarThickness: 6

        }],
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false,
            }
        },
        interaction: {
            intersect: false,
            mode: 'index',
        },
        scales: {
            y: {
                grid: {
                    drawBorder: false,
                    display: true,
                    drawOnChartArea: true,
                    drawTicks: false,
                    borderDash: [5, 5]
                },
                ticks: {
                    display: true,
                    padding: 10,
                    color: '#fbfbfb',
                    font: {
                        size: 11,
                        family: "Open Sans",
                        style: 'normal',
                        lineHeight: 2
                    },
                }
            },
            x: {
                grid: {
                    drawBorder: false,
                    display: false,
                    drawOnChartArea: false,
                    drawTicks: false,
                    borderDash: [5, 5]
                },
                ticks: {
                    display: true,
                    color: '#ccc',
                    padding: 20,
                    font: {
                        size: 11,
                        family: "Open Sans",
                        style: 'normal',
                        lineHeight: 2
                    },
                }
            },
        },
    },
});

let startvalue=0;
const getPayments = (start_from) =>{
let member_id=$("#member_id").val();
    $.ajax({
        url:"/mypayments/"+member_id+"/"+start_from,
        type:"GET",
        async:false,
        data:{},
        success:function (data) {
let txt="";
if(data.length>0) {
    for (key in data) {
        let funeral_name = data[key]["funeral_name"];
        let entered_by = data[key]["entered_by"];
        let status = data[key]["status"];
        let date_entered = data[key]["date_entered"];
let amount_paid = data[key]["amount_paid"];
        let clas = "text-danger";
        let icon = "ni-fat-remove";
        let sstatus = "Unpaid";
        if (status === "paid") {
            sstatus = "Paid";
            icon = "ni-check-bold";
            clas = "text-success";
        } else if (status === "home") {
            sstatus = "Home";
            icon = "ni-shop";
            clas = "text-warning";
        }
        txt += "<div class='alert' style='border: 1px solid lightgrey !important; background-color: floralwhite !important;' role='alert'>";
        txt += "<p class='text-dark'><i class=\"ni ni-circle-08 text-lg opacity-10\" aria-hidden=\"true\"></i> Funeral Name : <span class='text-dark text-sm font-weight-bolder'>" + funeral_name + "</span></p>";
        txt += "<p class='" + clas + "'><i class=\"ni " + icon + " text-lg opacity-10\" aria-hidden=\"true\"></i> Payment Status : <span class='" + clas + " text-sm font-weight-bolder'>" + sstatus + "</span></p>";
        txt += "<p class='text-dark'><i class=\"ni ni-single-02 text-lg opacity-10\" aria-hidden=\"true\"></i> Entered By : <span class='text-dark text-sm font-weight-bolder'>" + entered_by + "</span></p>";
        txt += "<p class='text-dark'><i class=\"ni ni-watch-time text-lg opacity-10\" aria-hidden=\"true\"></i> Date Entered : <span class='text-dark text-sm font-weight-bolder'>" + date_entered + "</span></p>";
txt += "<p class='text-dark'><i class=\"ni ni-credit-card text-lg opacity-10\" aria-hidden=\"true\"></i> Amount Charged : <span class='text-dark text-sm font-weight-bolder'>R" + amount_paid + "</span></p>";
        txt += "</div>";
    }
    $("#info").append(txt);
}
else {
    $("#loadmore").hide();
}
        },
        error:function(jqXHR, exception)
        {
            console.log("Connection error");
        }
    });
};
const loadfirst = () =>
{
    let mm=$("#member_idx").val();
    $("#member_id").val(mm);
    $("#info").empty();
    $("#loadmore").show();
    $(".num1").show();
    $(".num2").hide();
    startvalue=0;
    var ttli=startvalue*10;
    getPayments(ttli);
    startvalue++;
}
const loadMore = () =>
{
    var ttli=startvalue*10;
    getPayments(ttli);
    startvalue++;
}
$("#search_term_txt").keyup(function(){

    var obj={
        keyword:$(this).val()
    };
    $.ajax({
        type: "GET",
        url: "getothermembers",
        data:obj,
        beforeSend: function(){
            //$("#spinner").show();
        },
        success: function(data){

            if(data.length>0)
            {
                $("#suggesstion-box-member").empty();
                let msg="<ul id=\"country-list\" class=\"\">";
                for (key in data)
                {
                    let member_id=data[key]["member_id"];
                    let first_name=data[key]["first_name"];
                    let last_name=data[key]["last_name"];
                    let contact_number=data[key]["contact_number"];
                    let fullname=first_name+" "+last_name;
                    msg+="<li style=\"color: yellow;\" onClick=\"selectSearchedMember('"+member_id+"','"+fullname+"','"+contact_number+"')\">"+fullname+"<br><span style=\"color: #fff; font-size: small\">"+contact_number+"</span></li>";
                }
                msg+="</ul>";
                $("#suggesstion-box-member").show();
                $("#suggesstion-box-member").html(msg);
            }

        }
    });
});
const selectSearchedMember = (member_id,member_name,subphone) => {
    $("#member_id").val(member_id);
    $("#info").empty();
    $("#loadmore").show();
    $(".submember").text(member_name);
    $(".subphone").text(subphone);
    $("#loadother").click();
    startvalue=0;
    var ttli=startvalue*10;
    getPayments(ttli);
    startvalue++;
    $("#search_term_txt").val(member_name);
    $("#suggesstion-box-member").hide();
    $(".num1").hide();
    $(".num2").show();
}
