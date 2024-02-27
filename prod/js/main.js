$(document).ready(function (){
    let f1=loadDetails();
    f1.then(function(value){
let f2=getTotals(value);
f2.then((value)=>{
let f3=getOtherTotals(value);
f3.then((value)=>{
    let f4= claimDetails(value);  
    f4.then((value)=>{
        let f5=getNotes(value);
    })  
})
})
    })
    
    

});
async function loadDetails()
{
    console.log("I m moving");
    return new Promise((resolve,reject)=>{
    var obj={identity_number:14};
    $.ajax({
        url:"ajax/claims.php",
        type:"POST",
        data:obj,
        success:function(data){
            var json=JSON.parse(data);
            console.log(data);
            var purple=json["purple_total"];
            var red=json["red_total"];
            var orange=json["orange_total"];
            var green=json["green_total"];
            var claim_id=json["next_claim_id"];
            console.log("-->"+claim_id);

            var total=purple+red+orange+green;

            var purple_perc=parseFloat((purple/total)*100).toFixed(2);
            var red_perc=parseFloat((red/total)*100).toFixed(2);
            var orange_perc=parseFloat((orange/total)*100).toFixed(2);
            var green_perc=parseFloat((green/total)*100).toFixed(2);

            $(".color_total").text(total);
            $("#purple_num").text(purple);
            $("#red_num").text(red);
            $("#orange_num").text(orange);
            $("#green_num").text(green);

            $("#purple").attr("style","--p:"+purple_perc+";--c:purple;");
            $("#red").attr("style","--p:"+red_perc+";--c:red;");
            $("#orange").attr("style","--p:"+orange_perc+";--c:orange;");
            $("#green").attr("style","--p:"+green_perc+";--c:lightgreen;");

            $("#purple").text(purple_perc+"%");
            $("#red").text(red_perc+"%");
            $("#orange").text(orange_perc+"%");
            $("#green").text(green_perc+"%");           
            resolve(claim_id);

        },
        error:function(jqXHR, exception)
        {
reject(0);
        }
    });
});
}

async function claimDetails(claim_id)
{
    console.log(claim_id);
    return new Promise((resolve,reject)=>{
    var obj={identity_number:15,claim_id:claim_id};
    $.ajax({
        url:"ajax/claims.php",
        type:"POST",
        data:obj,
        success:function(data){

            var json=JSON.parse(data);
            var client_name=json["client_name"];
            if(client_name=="Aspen")
            {
                $("#aspen_form").show();
                $("#other_form").hide();
            }
            else
            {
                $("#aspen_form").hide();
                $("#other_form").show();
            }
            $("#claim_number").text(json["claim_number"]);
            $("#full_name").text(json["full_name"]);
            $("#contact_number").text(json["contact_number"]);
            $("#email").text(json["email"]);
            $("#client_name").text(client_name);
            $("#policy_number").text(json["policy_number"]);
            $("#incident_date").text(json["Service_Date"]);
$("#notice").text(json["notice"]);
            $(".claim_id").attr("value",claim_id);
            resolve(claim_id);
        },
        error:function(jqXHR, exception)
        {
reject(0);
        }
    });
});
}
async function getNotes(claim_id)
{
    return new Promise((resolve,reject)=>{
    var obj={identity_number:16,claim_id:claim_id};
    $.ajax({
        url:"ajax/claims.php",
        type:"POST",
        data:obj,
        success:function(data){
            $("#mynotes").html(data);
            resolve(1);
        },
        error:function(jqXHR, exception)
        {
reject(0);
        }
    });
});
}
async function getTotals(claim_id)
{
    return new Promise((resolve,reject)=>{
    var obj={identity_number:34};
    $.ajax({
        url:"ajax/claims.php",
        type:"POST",
        data:obj,
        success:function(data){
 console.log("Tes");
            console.log(data);
            console.log("Tes1");
            var json=JSON.parse(data);
            $("#scheme_savings").text(json["scheme_savings"]);
            $("#discount_savings").text(json["discount_savings"]);
            $("#total_savings").text(json["total_savings"]);
            $("#average_closed").text(json["average"]);
            $("#closed_claims").text(json["closed_cases"]);
            $("#claims_entered").text(json["total_cases"]);
            console.log("===>"+claim_id);
resolve(claim_id);
        },
        error:function(jqXHR, exception)
        {
reject(0);
        }
    });
});
}
async function getOtherTotals(claim_id)
{
    return new Promise((resolve,reject)=>{
    var obj={identity_number:17};
    $.ajax({
        url:"ajax/claims.php",
        type:"POST",
        data:obj,
        success:function(data){
//console.log(data);
            var json=JSON.parse(data);
            var member_arr=json["members"]
            var file_arr=json["files"]
            var zero_arr=json["zeros"]
            $("#member_total").text(member_arr.length);
            $("#file_total").text(file_arr.length);
            $("#zero_total").text(zero_arr.length);

            for(var i=0;i<member_arr.length;i++)
            {
                if(i>10)
                {
                    break;
                }
                var claim_idin=member_arr[i]["claim_id"];
                var claim_number=member_arr[i]["claim_number"];
                $("#member_append").append("<form action='case_details.php' method='post'><input type='hidden' name='claim_id' value='"+claim_idin+"'/><input type='submit' class='linkbutton' name='btn' value='"+claim_number+"'>");
            }
            for(var j=0;j<file_arr.length;j++)
            {
                if(j>10)
                {
                    break;
                }
                var claim_idin=file_arr[j]["claim_id"];
                var claim_number=file_arr[j]["claim_number"];
                $("#file_append").append("<form action='case_details.php' method='post'><input type='hidden' name='claim_id' value='"+claim_idin+"'/><input type='submit' class='linkbutton' name='btn' value='"+claim_number+"'>");
            }
            for(var k=0;k<zero_arr.length;k++)
            {
                if(k>10)
                {
                    break;
                }
                var claim_idin=zero_arr[k]["claim_id"];
                var claim_number=zero_arr[k]["claim_number"];
                $("#zero_append").append("<form action='case_details.php' method='post'><input type='hidden' name='claim_id' value='"+claim_idin+"'/><input type='submit' class='linkbutton' name='btn' value='"+claim_number+"'>");
            }
            resolve(claim_id);
        },
        error:function(jqXHR, exception)
        {
            reject(0);
        }
    });
});
}

function loadClaim()
{
    var claim_id=$("#next_claim_id").val();
    var obj={claim_id:claim_id,"quick_view":1};
    $("#claim_details").empty();
    $("#close_case_modal").remove();
    $("#ddd").html("<p class=\"card-text placeholder-glow\">\n"+"<span class=\"placeholder col-6\" style=\"height: 100%\"></span><span class=\"placeholder col-6\" style=\"height: 100%\"></span>\n"+"\n"+"</p>");
    $.ajax({
        url:"case_details.php",
        type:"POST",
        data:obj,
        success:function(data){
            $("#claim_details").html(data);
            $("#ddd").empty();
        },
        error:function(jqXHR, exception)
        {

        }
    });
}
function clientSearch()
{
    $("#search_res").text("Please wait...")
    var search_term=$("#search_term").val();
    var obj={search_term:search_term,identity_number:23};
    $.ajax({
        url:"ajax/claims.php",
        type:"POST",
        data:obj,
        success:function(data){
            $("#search_res").html(data);
        },
        error:function(jqXHR, exception)
        {
            $("#search_res").html("There is an error");
        }
    });
}
function closeLoad()
{
   let f1=loadDetails();
    f1.then(function(value){
         let f4= claimDetails(value);
    });
    $(".uk-modal-close-full").click();
}
