var settings=null;//global variable (settings) to be accessed anywhere in the program
//create a separate function for api settings so that you can reuse it anywhere in your code
//parameter x is the field name e.g in this case it is a claim number
var doctor_arr=[];
var patientArray=[];

function validateClaimNo() {
    $(".mybtn").removeAttr('disabled');
    $("#claim_number").css("border-color","grey");
    toUpper('claim_number');
    validateClient();
    var claim_number = $("#claim_number").val();
    var client_id = $("#client_id").val();
    if(claim_number!="") {
        var obj = {
            claim_number: claim_number,
            client_id: client_id,
            identity_number: 6
        };
        $.ajax({
            url: "ajax/claims.php",
            type: "POST",
            data: obj,
            success: function (data) {
                if(data.indexOf("Duplicate")>-1)
                {
                    $( ".mybtn" ).prop( "disabled", true );
                    $("#show_info").html("<span style='color: purple'>"+data+"</span>");
                    $("#claim_number").css("border-color","red");
                    $("#claim_number").focus();
                }
            },
            error: function (jqXHR, exception) {
                alert("Connection error");

            }
        });
    }




}

function editValidate() {
    toUpper('claim_number');
    validateClient();
}
function validatePolicyNo() {


    var x = document.getElementById("policy_number").value;

    if(x!="") {
        var obj = {
            policy_number: x,
            identityNum: 10
        };
        $.ajax({

            url: "ajaxPhp/ajaxRetrieve.php",
            type: "GET",
            data: obj,
            success: function (data) {

                var myjson=JSON.parse(data);


                if(myjson.stat.indexOf("Success")>=0)
                {

                    var member_name=$("#member_name").val();
                    var member_surname=$("#member_surname").val();
                    var memb_telephone=$("#memb_telephone").val();
                    var memb_cell=$("#memb_cell").val();
                    var memb_email=$("#memb_email").val();
                    var id_number=$("#id_number").val();
                    if(member_name=="")
                    {
                        document.getElementById('member_name').value=myjson.details[0][0];
                    }
                    if(member_surname=="")
                    {
                        document.getElementById('member_surname').value=myjson.details[0][1];
                    }
                    if(memb_telephone=="")
                    {
                        document.getElementById('memb_telephone').value=myjson.details[0][2];
                    }
                    if(memb_cell=="")
                    {
                        document.getElementById('memb_cell').value=myjson.details[0][3];
                    }
                    if(memb_email=="")
                    {
                        document.getElementById('memb_email').value=myjson.details[0][4];
                    }
                    if(id_number=="")
                    {
                        document.getElementById('id_number').value=myjson.details[0][5];
                    }
                }

            },
            error: function (jqXHR, exception) {
                alert("Connection error");

            }
        });
    }




}
function validateForm()
{
    if (confirm('Do you really want to save this Case?')) {

        return true;

    } else {

        return false;
    }
}



function checkDoctor()
{

    var practice_number=$("#practice_number").val();
    if(practice_number!="")
    {
        $("#show_info").text("Please wait...");
        $("#show_info").css("color","red");
        $.ajax({
            url:"ajax/claims.php",
            type:"POST",
            data:{
                practice_number:practice_number,
                identity_number:1
            },
            success:function(data)
            {
                if(data.indexOf("doctor_surname")>-1)
                {
                    if(doctor_arr.indexOf(practice_number)<0)
                    {
                        var doctors = JSON.parse(data);
                        var fullname=doctors["doctor_name"]+" "+doctors["doctor_surname"];
                        var doctor_id=doctors["doctor_id"];
                        doctor_arr.push(practice_number);
                        var doctor_form="<form action=\"edit_doctor.php\" method=\"post\" target=\"print_popup\" onsubmit=\"window.open('edit_doctor.php','print_popup','width=1000,height=800');\"><input type=\"hidden\" name=\"doc_id\" value=\""+doctor_id+"\"><button class=\"linkbutton\" name=\"btn\" title=\"edit claim\"> "+practice_number+"</button> </form>";
                        $("#my_doctors").append("<tr><td>"+doctor_form+"</td><td>"+doctors["gives_discount"]+"</td><td>"+doctors["contact"]+"</td><td>"+fullname+"</td></tr>");
                        $("#practice_number").val("");

                    }
                    $('#doctors').val(doctor_arr.join());
                    $("#show_info").text("");
                }
                else
                {
                    $("#show_info").html("<div uk-alert class=\"uk-alert-danger\"><a class=\"uk-alert-close\" uk-close></a><p>"+data+" </p> <a target=\"popup\" onclick=\"window.open('add_doctor.php','popup','width=800,height=600'); return false;\"><button class=\"uk-button uk-button-default uk-button-small\">Add Doctor</button></a> </div>");

                }

            },
            error:function(jqXHR, exception)
            {
                $("#show_info").html("There is an error : "+jqXHR.responseText);
            }
        });

    }
}



function Codes()
{
    var icd10_code=$("#icd10").val();
    $("#show_info").text("Please wait...");
    $("#show_info").css("color","red");
    if(icd10_code!="")
    {
        $.ajax({

            url:"ajax/claims.php",
            type:"POST",
            data:{
                icd10_code:icd10_code,
                identity_number:4
            },
            success:function(data)
            {
                $("#show_info").html(data);
            },
            error:function(jqXHR, exception)
            {
                alert("There is an error : "+jqXHR.responseText);
            }
        });
    }
}

function Schemes()
{
    var medical_scheme=$("#medical_scheme").val();
    $("#options").empty();
    $("#scheme_option").val("");
    $.ajax({
        url:"ajax/claims.php",
        type:"POST",
        data:{
            medical_scheme:medical_scheme,
            identity_number:2
        },
        success:function(data)
        {
            if(data.indexOf("option_name")>-1)
            {
                var json = JSON.parse(data);
                for(var key in json)
                {
                    $("#options").append("<option value='"+json[key]["option_name"]+"'>");

                }
            }
        },
        error:function(jqXHR, exception)
        {
            $("#details").html("There is an error : "+jqXHR.responseText);
        }
    });
}

function validateClient()
{
    var client=document.getElementById("client_id").value;
    var claimNumber=document.getElementById("claim_number").value;
    var message = "";
    claimNumber=claimNumber.toUpperCase();
    if(claimNumber!="") {

        if (client == 1) {
            claimNumber = Number(claimNumber);
            if (Number.isInteger(claimNumber)) {

                if (claimNumber.length > 13 || claimNumber.length < 7) {
                    message = "Invalid Claim Number";
                    $( ".mybtn" ).prop( "disabled", true );
                }
                else {
                    $(".mybtn").removeAttr('disabled');
                }

            }
            else {
                message = "Invalid Claim Number";
                $( ".mybtn" ).prop( "disabled", true );
            }
        }
        else if (client == 2 || client == 5) {
            if (claimNumber.substring(0, 2) == "20" && claimNumber.indexOf('/') > 3) {

                if (claimNumber.length > 13 || claimNumber.length < 7) {
                    message = "Invalid Claim Number";
                    $( ".mybtn" ).prop( "disabled", true );
                }
                else {
                    $(".mybtn").removeAttr('disabled');
                }

            }
            else {
                message = "Invalid Claim Number";
                $( ".mybtn" ).prop( "disabled", true );

            }
        }
        else if (client == 3) {
            if (claimNumber.substring(0, 4) == "AUTH" || claimNumber.substring(0, 2) == "XC") {
                if (claimNumber.length > 22 || claimNumber.length < 5) {
                    message = "Invalid Claim Number";
                    $( ".mybtn" ).prop( "disabled", true );
                }
                else {
                    var p = claimNumber.replace('-', '');
                    document.getElementById("claim_number").value = p;
                    $(".mybtn").removeAttr('disabled');
                }

            }
            else {
                message = "Invalid Claim Number";
                $( ".mybtn" ).prop( "disabled", true );
            }
        }
        else if (client == 4) {
            $(".mybtn").removeAttr('disabled');
        }

        else if (client == 6) {
            claimNumber = Number(claimNumber);
            if (Number.isInteger(claimNumber)) {

                if (claimNumber.length > 13 || claimNumber.length < 7) {
                    message = "Invalid Claim Number";
                    $( ".mybtn" ).prop( "disabled", true );
                }
                else {
                    $(".mybtn").removeAttr('disabled');
                }

            }
            else {
                message = "Invalid Claim Number";
                $( ".mybtn" ).prop( "disabled", true );
            }
        }
        else if (client == 7 || client == 8) {

            if (claimNumber.substring(0, 3) == "CLM") {

                if (claimNumber.length > 17 || claimNumber.length < 5) {
                    message = "Invalid Claim Number";
                    $( ".mybtn" ).prop( "disabled", true );
                }
                else {
                    $(".mybtn").removeAttr('disabled');
                }

            }
            else {
                message = "Invalid Claim Number";
                $( ".mybtn" ).prop( "disabled", true );
            }

        }
    }
    document.getElementById("show_info").innerHTML = message;

}



function validatePolicy(num) {
    toUpper('policy_number');
    var client = document.getElementById("client_id").value;
    var message = "";
    var policyNumber = document.getElementById("policy_number").value.toUpperCase();
    if (policyNumber != "") {
        //validatePolicyNo();
        if(num===1) {

        }
        else
        {

            $('#policy_number').prop('readonly', true);
            if(client == 1 || client == 6)
            {
                $('#policy_number').prop('readonly', false);

            }
        }


        if (client == 1) {
            if (policyNumber.substring(0, 3) == "GAP" || policyNumber.substring(0, 2) == "00") {
                if (policyNumber.length > 14 || policyNumber.length < 6) {
                    message = "Invalid Policy Number";
                    $( ".mybtn" ).prop( "disabled", true );
                }
                else {
                    $(".mybtn").removeAttr('disabled');
                }

            }
            else {
                message = "Invalid Policy Number";
                $( ".mybtn" ).prop( "disabled", true );
            }

        }
        else if (client == 2) {
            if (policyNumber.substring(0, 4) == "GAPT" || policyNumber.substring(0, 4) == "TKGP" || policyNumber.substring(0, 4) == "TKGD" || policyNumber.substring(0, 4) == "EXGP" || policyNumber.substring(0, 4) == "AMBL" || policyNumber.substring(0, 2) == "12" || policyNumber.substring(0, 2) == "13") {
                if (policyNumber.length > 14 || policyNumber.length < 7) {
                    message = "Invalid Policy Number";
                    $( ".mybtn" ).prop( "disabled", true );
                }
                else {
                    $(".mybtn").removeAttr('disabled');
                }

            }
            else {
                message = "Invalid Policy Number";
                $( ".mybtn" ).prop( "disabled", true );
            }
        }
        else if (client == 3) {
            policyNumber = Number(policyNumber);
            if (Number.isInteger(policyNumber)) {
                if (policyNumber.length > 14 || policyNumber.length < 7) {
                    message = "Invalid Policy Number";
                    $( ".mybtn" ).prop( "disabled", true );
                }
                else {
                    $(".mybtn").removeAttr('disabled');
                }

            }
            else {
                message = "Invalid Policy Number";
                $( ".mybtn" ).prop( "disabled", true );
            }
        }
        else if (client == 4) {
            $(".mybtn").removeAttr('disabled');
        }
        else if (client == 5) {
            if (policyNumber.substring(0, 3) == "MED" || policyNumber.substring(0, 1) == "H" || policyNumber.substring(0, 2) == "RE") {
                if (policyNumber.length > 14 || policyNumber.length < 9) {
                    message = "Invalid Policy Number";
                    $( ".mybtn" ).prop( "disabled", true );
                }
                else {
                    $(".mybtn").removeAttr('disabled');
                }

            }
            else {
                message = "Invalid Policy Number";
                $( ".mybtn" ).prop( "disabled", true );
            }
        }
        else if (client == 6) {
            if (policyNumber.substring(0, 2) == "AP" || policyNumber.substring(0, 2) == "00") {
                if (policyNumber.length > 15 || policyNumber.length < 7) {
                    message = "Invalid Policy Number";
                    $( ".mybtn" ).prop( "disabled", true );
                }
                else {
                    $(".mybtn").removeAttr('disabled');
                }

            }
            else {
                message = "Invalid Policy Number";
                $( ".mybtn" ).prop( "disabled", true );
            }

        }
    }
    document.getElementById("show_info").innerHTML = message;

}

function  discount(pracN,val,type) {
    var pNum=document.getElementById("icd10").value;
    $(document).ready(function(){

        $.ajax({

            url:"ajaxPhp/ajaxRetrieve.php",
            type:"GET",
            data:{
                pracN:pracN,
                identityNum:5,
                val:val,
                type:type
            },
            success:function(data)
            {
                $("#conf").html(data);
            },
            error:function(jqXHR, exception)
            {
                $("#details3").html("There is an error : "+jqXHR.responseText);
            }
        });

    });
}

function toUpper(val) {
    var str=document.getElementById(val).value;
    document.getElementById(val).value=str.toUpperCase().trim();
    var patient=$('#patient_name').val();
    addpatient();

}

function addpatient() {
    var patient = $('#patient_name').val();
    if (patient != "") {
        if (patientArray.indexOf(patient) < 0) {
            $('#myp').append("<ul><li>" + patient + "</li></ul>");
            patientArray.push(patient);
        }

        $('#myPatient').val(patientArray.join());
    }
}
function clearPatient() {
    $('#patient_name').val("");

}
function clearDoctor() {
    $('#prac_num_1').val("");
}
function deletePatient(claim_id,patient_name) {
    var obj={
        claim_id:claim_id,
        patient_name:patient_name
    };
    $.ajax({
        url:"ajaxPhp/deleting.php?identity=20",
        type:"GET",
        data:obj,
        success:function(data){

            alert(data);
        },
        error:function(jqXHR, exception)
        {
            alert("Connection error");
        }
    });
}
function deleteDoctor(claim_id,prac) {
    var obj={
        claim_id:claim_id,
        practice_number:prac
    };
    $.ajax({
        url:"ajax/deleting.php?identity=21",
        type:"GET",
        data:obj,
        success:function(data){

            alert(data);
        },
        error:function(jqXHR, exception)
        {
            alert("Connection error");
        }
    });
}

function checkDoctors(prac)
{

    var pNum=prac;
    if(pNum==0)
    {
        pNum="";
    }
    if(pNum!="")
    {
        $(document).ready(function(){

            $("#search").show();
            $("#details").empty();
            $.ajax({

                url:"ajaxPhp/ajaxRetrieve.php",
                type:"GET",
                data:{
                    practiceNumber:pNum,
                    identityNum:1
                },
                success:function(data)
                {

                    var errorp=data;
                    $("#myHide").val(errorp);
                    var hid=$("#myHide").val();
                    var str="Invalid Practice Number";
                    if(hid==str)                   {

                        document.getElementById('userAccess').style.display = 'block';
                        $("#"+prac).focus();
                    }
                    else
                    {

                        $("#details").append("<header class=\"w3-container\"><span onclick=\"document.getElementById('details').style.display='none',document.getElementById('details3').style.display='none'\" class=\"w3-button w3-red w3-xl w3-display-topright\">\n" +
                            "                        &times;\n" +
                            "                    </span>");
                        $("#details").slideDown();
                        $("#details").append(data);
                        var docName=$("#pname").text();

                        $("#pname").attr('id',pNum);


                        $('#doctors').val(pracArray.join());
                    }
                    $("#search").hide();
                },
                error:function(jqXHR, exception)
                {
                    $("#details").html("There is an error : "+jqXHR.responseText);
                }
            });

        });

    }
}

function amountCalc() {
    var client_id=$("#client_id").val();
    var charged_amnt=$("#charged_amount").val();
    var scheme_paid=$("#scheme_paid").val();
    var gap=parseFloat(charged_amnt)-parseFloat(scheme_paid);
    if(client_id==14)
    {
        var client_gap=5*scheme_paid;
        $("#client_gap_amount").val(client_gap);
    }
    $("#member_portion").val(gap);
}
function validateNumber(cntrl,min,max) {
    var field=$("#"+cntrl).val();
    $(".mybtn").removeAttr('disabled');
    $("#" + cntrl).css("border-color", "grey");
    if(field!="") {
        var minx = parseInt(min);
        var maxx = parseInt(max);
        var tot = field.length;
        if (tot >= minx && tot < maxx) {

        }
        else {
            $( ".mybtn" ).prop( "disabled", true );
            $("#" + cntrl).css("border-color", "red");
        }
    }
}
function generateClaimNomber() {
    var client = document.getElementById("client_id").value;
    if (client == 4 || client==31 || client==33) {
        $(document).ready(function () {
            $.ajax({
                url: "ajax/claims.php",
                type: "POST",
                data: {
                    identity_number: 5,
                    client_id:client
                },
                success: function (data) {
                    $('#claim_number').val(data);
                    $("#claim_number").focus();
                },
                error: function (jqXHR, exception) {
                    alert(jqXHR.responseText);
                }
            });

        });
    }
    checkAspen(client);
}
function checkAspen(client)
{
    if(client == 31)
    {
        $(".showcl").show();
        $(".hidecl").hide();
        $(".claimn").text("MCA Request Number");
        $(".incid").text("(Infusion Date)");

    }
    else {
        $(".showcl").hide();
        $(".hidecl").show();
        $(".claimn").text("Claim Number");
        $(".incid").text("(Incident Date)");
    }
}
function selectICD(icd10) {
    $("#icd10").val(icd10);
    $("#suggesstion-box2").hide();
    Codes();
}

 function addPatientChange(){
        let claim_id=$("#claim_id").val();
        let patient_email=$("#patient_email").val();
        let patient_contact=$("#patient_contact").val();
        
        let obj={claim_id,patient_email,patient_contact,identity_number:48};
           $.ajax({
            url:"ajax/claims.php",
            type:"POST",
            data:obj,
            success:function(data){
               $("#patientinfo").show();
               $("#patientinfo").html(data);

            },
            error:function(jqXHR, exception)
            {
                alert(jqXHR.responseText);
            }
        });
  }


$(document).ready(function ()
{
    $("#icd10").keyup(function(){
        var obj={
            identity_number:35,
            keyword:$(this).val()
        };
        $.ajax({
            type: "POST",
            url: "ajax/claims.php",
            data:obj,
            beforeSend: function(){
                //$("#search-box").css("background","#FFF url(LoaderIcon.gif) no-repeat 165px");
            },
            success: function(data){
                console.log(data);
                $("#suggesstion-box2").show();
                $("#suggesstion-box2").html(data);
            }
        });
    });
var client=$("#client_id").val();
    if(client == 31)
    {
        $(".showcl").show();
        $(".hidecl").hide();
        $(".claimn").text("MCA Request Number");
        $(".incid").text("(Infusion Date)");

    }
    else {
        $(".showcl").hide();
        $(".hidecl").show();
        $(".claimn").text("Claim Number");
        $(".incid").text("(Incident Date)");
    }
});