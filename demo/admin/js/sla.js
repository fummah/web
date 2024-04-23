$(function () {  
 $('.select2bs4').select2();  
  naly()
  $(".select2bs4").change(function () {
    naly();
  });
  $(".cc").click(function () {
    naly();
  });
});


 function naly()
  {
     var table = $('#example').DataTable();
table.destroy();
$("#info").show();
   var clients=JSON.stringify($("#clients").val());
    var users=JSON.stringify($("#users").val());
    var start_date=$("#dat1").val();
    var end_date=$("#dat2").val();
    const obj = {identityNum:46,clients,users,start_date,end_date};
    $.ajax({
      url:"../ajax/reports.php",
      type:"GET",
      data:obj,
      async: true,
     success:function (data) { 
      console.log(data);
const json_claims = JSON.parse(data);
let txtval ="";
const json = json_claims["claims"];
const total = json_claims["totalout"];
$("#os").text(total);
for(let key in json)
{
    const claim_id =json[key]["claim_id"];
                    const claim_number =json[key]["claim_number"];
                    const date_entered =json[key]["date_entered"];
                    const username =json[key]["username"];
                    const client_name =json[key]["client_name"];
                     const sal_date =json[key]["sal_date"];
                      const hours =json[key]["hours"];
                      const status =json[key]["open"];
                      const bg =json[key]["bg"];
                      const color =json[key]["color"];                    
                    const txt = `<tr style='background-color:${bg};color:${color}'><td>${claim_number}</td><td>${username}</td><td>${client_name}</td><td>${sal_date}</td><td>${date_entered}</td>
                    <td>${hours}</td><td>${status}</td><td><form action='../case_details.php' method='post' target='_blank'/><input type='hidden' name='claim_id'
                     value='${claim_id}' /><button title='View Claim' name='btn' class='btn ti-angle-double-right'></button></form></td></tr>`;
                    txtval+=txt;
                }
                $("#claims").html(txtval);
                $("#info").hide();
      },
      complete: function () {
        $('#example').DataTable(); 
     },
      error:function (jqXHR, exception) {
        console.log(jqXHR.responseText);
      }
    });

  }
 
