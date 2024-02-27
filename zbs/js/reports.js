let arr_trend=[['Funeral Name', 'Total Members', 'Paid','Unpaid','Home']];
let USDollar = new Intl.NumberFormat('za-za', {
    style: 'currency',
    currency: 'Zar',
});
$(document).ready(function () {
    getTrend();
    console.log(arr_trend);
});
const getTrend = () =>  {

        $.ajax({
            url:"ajax/process.php",
            type:"POST",
            async:false,
            beforeSend:function () {
            },
            data:{
                identity_number:22
            },
            success:function(data)
            {
                const  json=JSON.parse(data);
                for(key in json)
                {
                    let funeral_name=json[key]["funeral_name"];
                    let total_members=parseInt(json[key]["total"]);
                    let total_paid=parseInt(json[key]["total_paid"]);
                    let total_unpaid=parseInt(json[key]["total_unpaid"]);
                    let total_home=parseInt(json[key]["total_home"]);
                    let inarr=[funeral_name,total_members,total_paid,total_unpaid,total_home];
                    arr_trend.push(inarr);
                }
            },
            error:function(jqXHR, exception)
            {
                console.log("There is an error : "+jqXHR.responseText);
            }
        });
    }
const insertValues = (funeral_id,location_id,amount,expenses,ex) =>  {
    $.ajax({
        url:"ajax/process.php",
        type:"POST",
        async:false,
        beforeSend:function () {
        },
        data:{
            identity_number:23,
            funeral_id:funeral_id,
            location_id:location_id,
            amount:amount,
            expenses:expenses,
            ex:ex
        },
        success:function(data)
        {

if(data.indexOf("1")>-1)
{

    UIkit.notification({message: "Record Successfully Updated-",pos: 'bottom-right'});
}
else {
    UIkit.notification({message: "Record Failed to Update",pos: 'bottom-right'});

}
        },
        error:function(jqXHR, exception)
        {
            console.log("There is an error : "+jqXHR.responseText);
        }
    });
}
const updateUndertaker = (funeral_id,undertaker_name,undertaker_cost,other_costs,bank_charges,system_cost) =>  {
    $.ajax({
        url:"ajax/process.php",
        type:"POST",
        async:false,
        beforeSend:function () {
        },
        data:{
            identity_number:26,
            funeral_id:funeral_id,
            undertaker_name:undertaker_name,
            undertaker_cost:undertaker_cost,
            other_costs:other_costs,
bank_charges:bank_charges,
system_cost:system_cost
        },
        success:function(data)
        {
            console.log(data);

            if(data.indexOf("1")>-1)
            {

                UIkit.notification({message: "Record Successfully Updated",pos: 'bottom-right'});
            }
            else {
                UIkit.notification({message: "Record Failed to Update",pos: 'bottom-right'});

            }
        },
        error:function(jqXHR, exception)
        {
            console.log("There is an error : "+jqXHR.responseText);
        }
    });
}
$(document).on('keyup','.inp',function() {
let location_id=$(this).attr("data");
let amount=parseFloat($("#amt"+location_id).val());
let expenses=parseFloat($("#exp"+location_id).val());
let net = amount-expenses;
    net=`${USDollar.format(net)}`;
    net=net.replace("ZAR","R");
$("#ov"+location_id).text(net);
});
$(document).on('click','.mybtn',function() {
let location_id=$(this).attr("data");
let funeral_id=$(this).attr("data-abide");
let amount=parseFloat($("#amt"+location_id).val());
let expenses=parseFloat($("#exp"+location_id).val());
let ex=parseFloat($("#exid"+location_id).val());
    insertValues(funeral_id,location_id,amount,expenses,ex);
    $(this).text("Updated")
    $(this).css("background-color","yellow !important")
});
$(document).on('click','.undertaker',function() {
    let funeral_id=$(this).attr("data");
    let undertaker_name=$("#undertaker_name").val();
    let undertaker_cost=$("#undertaker_cost").val();
    let other_costs=$("#other_costs").val();
let bank_charges=$("#bank_charges").val();
let system_cost=$("#system_cost").val();
    updateUndertaker(funeral_id,undertaker_name,undertaker_cost,other_costs,bank_charges,system_cost);
    $(this).text("Updated")
    $(this).css("background-color","yellow !important")
});
$(document).on('click','.open',function() {
  $(".open").css("background-color", "red").css("padding","10px");

  $(this).css("background-color", "black");
  let kk=$(this).attr("data");
 $(".xop").hide();
    $("#"+kk).show();
    console.log("testing....");
});

