$(document).ready(function (){

});

function openModal(claim_id)
{
    var obj={identity_number:1,claim_id:claim_id};
    $("#lod").show("fast");
    $("#lines").empty();
    $("#closed_by").empty();
    $.ajax({
        url:"ajax/split_ajax.php",
        type:"POST",
        data:obj,
        success:function(data){
            $("#lod").hide("fast");
            var json=JSON.parse(data);
            let loyalty_number=json["loyalty_number"];
            let membership_number=json["membership_number"];
            let beneficiary_name=json["beneficiary_name"];
            let date_entered=json["date_entered"];
            let claim_id=json["claim_id"];
            let status=json["status"];
            let date_closed=json["date_closed"];
            let closed_by=json["closed_by"];
            $(".loyalty_number").text(loyalty_number);
            $(".member_name").text(beneficiary_name);
            $(".member_number").text(membership_number);
            $("#xclaim_id").val(claim_id);
            $(".date").text(date_entered);
            console.log(status);
            if(status==="completed")
            {
                $("#closed_by").text("(Closed By : "+closed_by+" - "+date_closed+")");
                $("#fot").hide();

            }

            let hospital_lines=json["hospital_lines"];
            for (let key0 in hospital_lines)
            {
                let oicd10="";
                let hospital_name=hospital_lines[key0]["hospital_name"];
                //let status=hospital_lines[key0]["status"];
                let claim_lines=hospital_lines[key0]["claim_lines"];
                let forndata=" <form action=\"classes/downloadSplit.php\" method=\"POST\">\n" +
                    "            \n" +
                    "            <input type=\"hidden\" id=\"claim_id\" name=\"xclaim_id\" value='"+claim_id+"'>\n" +
                    "            <input type=\"hidden\" id=\"xhospital_name\" name=\"xhospital_name\" value='"+hospital_name+"'> <button class=\"uk-button uk-button-secondary\" type=\"submit\"><span uk-icon=\"cloud-download\"></span> Download</button>\n" +
                    "           </form>";
                if(status==="pending")
                {
                    $("#lines").append("<tr style='background-color: black; color: white'><td colspan='4'>"+hospital_name+"</td><td colspan='1'><span class='status pending'>Active</span></td><td></td><td colspan='2'>"+forndata+"</td></tr>");

                }
                else
                {
                    $("#lines").append("<tr style='background-color: black; color: white'><td colspan='4'>"+hospital_name+"</td><td colspan='1'><span class='status completed'>Completed</span></td><td></td><td colspan='2'>"+forndata+"</td></tr>");

                }
let totcharged=0;
let totrate=0;
let totscheme=0;
let totpart=0;
                for(let key in claim_lines)
                {
                    let servicedate=claim_lines[key]["servicedate"];
                    let icdcode=claim_lines[key]["icdcode"];
                    let txtcode=icdcode.split(",");
                    icdcode=txtcode[0];
                    oicd10=icdcode!==""?icdcode:oicd10;
                    if(icdcode==="")
                    {
                        icdcode=oicd10;
                    }
                    let procedurecode=claim_lines[key]["procedurecode"];
                    let amountcharged=parseFloat(claim_lines[key]["amountcharged"]);
                    let medicalschemerateinput=parseFloat(claim_lines[key]["medicalschemerateinput"]);
                    let medicalschemepaidinput=parseFloat(claim_lines[key]["medicalschemepaidinput"]);
                    let line_id=claim_lines[key]["id"];
                    let duplicate_claim=claim_lines[key]["duplicate_claim"];
                    let copayment=claim_lines[key]["copayment"];
                    let file_name=claim_lines[key]["file_name"];
                    let error_list=claim_lines[key]["errors"];
                    let portion=(amountcharged-medicalschemepaidinput);
                    totcharged+=amountcharged;
                    totrate+=medicalschemerateinput;
                    totscheme+=medicalschemepaidinput;
                    totpart+=portion;
                    portion=portion.toFixed(2);

                    let titlr="";
                    let clas="";
                    if(duplicate_claim>0)
                    {
                        titlr="Duplicate line for line number : "+duplicate_claim;
                        clas="uk-text-danger";
                    }

                    $("#lines").append("<tr class='"+clas+"'><td uk-tooltip='title: "+error_list+"'>"+servicedate+"</td><td title='"+file_name+"'>"+icdcode+"</td><td>"+procedurecode+"</td><td>"+amountcharged+"</td><td>"+medicalschemerateinput+"</td><td>"+medicalschemepaidinput+"</td><td>"+portion+"</td><td>"+copayment+"</td></tr>");

                }
                totcharged=totcharged.toFixed(2);
                totrate=totrate.toFixed(2);
                totscheme=totscheme.toFixed(2);
                totpart=totpart.toFixed(2);
                $("#lines").append("<tr style='color: cornflowerblue !important; font-weight: bolder !important;'><td colspan='3'>Totals : </td><td>"+totcharged+"</td><td>"+totrate+"</td><td>"+totscheme+"</td><td colspan='3'>"+totpart+"</td></tr>");

            }

        },
        error:function(jqXHR, exception)
        {
            console.log("Error here");
        }
    });
    console.log(claim_id+"--");
    $(".clickme").click();
}

function closeClaim() {
    if(confirm("You are about to close the claim, are you sure?")) {
        let note = "-";
        let claim_id = $("#xclaim_id").val();
        let claim_number = $("#claim_number").val();

        var obj = {identity_number: 2, claim_id: claim_id, note: note,claim_number:claim_number};

        $.ajax({
            url: "ajax/split_ajax.php",
            type: "POST",
            data: obj,
            success: function (data) {               
                 $('#'+ claim_id).closest('tr').remove();
                $(".uk-modal-close-default").click();
                UIkit.notification({message: data});
            },
            error: function (jqXHR, exception) {
                console.log("Error here");
            }
        });
    }
}

function viewElements() {
if (document.getElementById("closetick").checked)
{
    $(".hideclas").show("fast");
}
else
{
    $(".hideclas").hide("fast");
}
}
