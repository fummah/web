var settings=null;//global variable (settings) to be accessed anywhere in the program
//create a separate function for api settings so that you can reuse it anywhere in your code
//parameter x is the field name e.g in this case it is a claim number
var pracArray=[];
var patientArray=[];
function validateClaimNo() {
    document.getElementById("btn").disabled = false;
    $("#claim_number").css("border-color","grey");
    toUpper('claim_number');
    validateClient();
    var x = document.getElementById("claim_number").value;
    if(x!="") {
        var obj = {
            claim_number: x,
            identityNum: 9
        };
        $.ajax({

            url: "ajaxPhp/ajaxRetrieve.php",
            type: "GET",
            data: obj,
            success: function (data) {
                if(data.indexOf("Duplicate")>=0)
                {
                    document.getElementById("btn").disabled = true;
                    $("#claim1").append("<span style='color: purple'>("+data+")</span>");
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



function Doctors(prac,place)
{
    $("#"+place).val("");
    var pNum=document.getElementById(prac).value;
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
                        $("#"+place).val(docName);
                        $("#pname").attr('id',pNum);
                        if(pracArray.indexOf(pNum)<0)
                        {
                            $('#mydoctors').append("<ul><li>"+pNum+"</li></ul>");
                            pracArray.push(pNum);
                        }

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



function Codes()
{
    var pNum=document.getElementById("icd10").value;
    if(pNum!="")
    {
        $(document).ready(function(){
            $("#search").show();

            $.ajax({

                url:"ajaxPhp/ajaxRetrieve.php",
                type:"GET",
                data:{
                    icdoCode:pNum,
                    identityNum:2
                },
                success:function(data)
                {
                    $("#confirmCode").html(data);
                    var spl=data.split("xxxq");

                    $("#pmbx").val(spl[0]);
                    $("#icdDetails").text(spl[1]);
                    $("#icdDetails").show("slow");
                    $("#pmbP").show();
                    if($("#pmbx").val()=="Invalid Code")
                    {
                        $("#icdDetails").hide();
                    }
                    if($("#pmbx").val()=="Yes")
                    {
                        document.getElementById("pmb3").checked = true;
                    }
                    else
                    {
                        document.getElementById("pmb2").checked = true;
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

function Schemes()
{
    var pNum=document.getElementById("medical_scheme").value;
    $("#options").empty();
    $("#scheme_option").val("");
    $("#schemeDiv").hide();
    $("#schemeDiv").show("slow");
    $(document).ready(function(){

        $.ajax({

            url:"ajaxPhp/ajaxRetrieve.php",
            type:"GET",
            data:{
                schemeId:pNum,
                identityNum:3
            },
            success:function(data)
            {

                var json1 = JSON.parse(data);

                for(value in json1)
                {
                    $("#options").append("<option value='"+json1[value]+"'>");

                }

            },
            error:function(jqXHR, exception)
            {
                $("#details").html("There is an error : "+jqXHR.responseText);
            }
        });

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
                    document.getElementById("btn").disabled = true;
                }
                else {
                    document.getElementById("btn").disabled = false;
                }

            }
            else {
                message = "Invalid Claim Number";
                document.getElementById("btn").disabled = true;
            }
        }
        else if (client == 2 || client == 5) {
            if (claimNumber.substring(0, 2) == "20" && claimNumber.indexOf('/') > 3) {

                if (claimNumber.length > 13 || claimNumber.length < 7) {
                    message = "Invalid Claim Number";
                    document.getElementById("btn").disabled = true;
                }
                else {
                    document.getElementById("btn").disabled = false;
                }

            }
            else {
                message = "Invalid Claim Number";
                document.getElementById("btn").disabled = true;

            }
        }
        else if (client == 3) {
            if (claimNumber.substring(0, 4) == "AUTH" || claimNumber.substring(0, 2) == "XC") {
                if (claimNumber.length > 22 || claimNumber.length < 5) {
                    message = "Invalid Claim Number";
                    document.getElementById("btn").disabled = true;
                }
                else {
                    var p = claimNumber.replace('-', '');
                    document.getElementById("claim_number").value = p;
                    document.getElementById("btn").disabled = false;
                }

            }
            else {
                message = "Invalid Claim Number";
                document.getElementById("btn").disabled = true;
            }
        }
        else if (client == 4) {
            document.getElementById("btn").disabled = false;
        }

        else if (client == 6) {
            claimNumber = Number(claimNumber);
            if (Number.isInteger(claimNumber)) {

                if (claimNumber.length > 13 || claimNumber.length < 7) {
                    message = "Invalid Claim Number";
                    document.getElementById("btn").disabled = true;
                }
                else {
                    document.getElementById("btn").disabled = false;
                }

            }
            else {
                message = "Invalid Claim Number";
                document.getElementById("btn").disabled = true;
            }
        }
        else if (client == 7 || client == 8) {

            if (claimNumber.substring(0, 3) == "CLM") {

                if (claimNumber.length > 17 || claimNumber.length < 5) {
                    message = "Invalid Claim Number";
                    document.getElementById("btn").disabled = true;
                }
                else {
                    document.getElementById("btn").disabled = false;
                }

            }
            else {
                message = "Invalid Claim Number";
                document.getElementById("btn").disabled = true;
            }

        }
    }
    document.getElementById("claim1").innerHTML = message;

}



function validatePolicy(num) {
    toUpper('policy_number');
    var client = document.getElementById("client_id").value;
    var message = "";
    var policyNumber = document.getElementById("policy_number").value.toUpperCase();
    if (policyNumber != "") {
 validatePolicyNo();
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
                    document.getElementById("btn").disabled = true;
                }
                else {
                    document.getElementById("btn").disabled = false;
                }

            }
            else {
                message = "Invalid Policy Number";
                document.getElementById("btn").disabled = true;
            }

        }
        else if (client == 2) {
            if (policyNumber.substring(0, 4) == "GAPT" || policyNumber.substring(0, 4) == "TKGP" || policyNumber.substring(0, 4) == "TKGD" || policyNumber.substring(0, 4) == "EXGP" || policyNumber.substring(0, 4) == "AMBL" || policyNumber.substring(0, 2) == "12" || policyNumber.substring(0, 2) == "13") {
                if (policyNumber.length > 14 || policyNumber.length < 7) {
                    message = "Invalid Policy Number";
                    document.getElementById("btn").disabled = true;
                }
                else {
                    document.getElementById("btn").disabled = false;
                }

            }
            else {
                message = "Invalid Policy Number";
                document.getElementById("btn").disabled = true;
            }
        }
        else if (client == 3) {
            policyNumber = Number(policyNumber);
            if (Number.isInteger(policyNumber)) {
                if (policyNumber.length > 14 || policyNumber.length < 7) {
                    message = "Invalid Policy Number";
                    document.getElementById("btn").disabled = true;
                }
                else {
                    document.getElementById("btn").disabled = false;
                }

            }
            else {
                message = "Invalid Policy Number";
                document.getElementById("btn").disabled = true;
            }
        }
        else if (client == 4) {
            document.getElementById("btn").disabled = false;
        }
        else if (client == 5) {
            if (policyNumber.substring(0, 3) == "MED" || policyNumber.substring(0, 1) == "H" || policyNumber.substring(0, 2) == "RE") {
                if (policyNumber.length > 14 || policyNumber.length < 9) {
                    message = "Invalid Policy Number";
                    document.getElementById("btn").disabled = true;
                }
                else {
                    document.getElementById("btn").disabled = false;
                }

            }
            else {
                message = "Invalid Policy Number";
                document.getElementById("btn").disabled = true;
            }
        }
        else if (client == 6) {
            if (policyNumber.substring(0, 2) == "AP" || policyNumber.substring(0, 2) == "00") {
                if (policyNumber.length > 15 || policyNumber.length < 7) {
                    message = "Invalid Policy Number";
                    document.getElementById("btn").disabled = true;
                }
                else {
                    document.getElementById("btn").disabled = false;
                }

            }
            else {
                message = "Invalid Policy Number";
                document.getElementById("btn").disabled = true;
            }

        }
    }
    document.getElementById("gap1").innerHTML = message;

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
        url:"ajaxPhp/deleting.php?identity=21",
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
    var charged_amnt=$("#charged_amnt").val();
    var scheme_paid=$("#scheme_paid").val();
    var gap=parseFloat(charged_amnt)-parseFloat(scheme_paid);
    if(client_id==14)
    {
        var client_gap=5*scheme_paid;
        $("#client_gap").val(client_gap);
    }


    $("#gap").val(gap);
}
function validateNumber(cntrl,min,max) {
    var field=$("#"+cntrl).val();
    document.getElementById("btn").disabled = false;
    $("#" + cntrl).css("border-color", "grey");
    if(field!="") {
        var minx = parseInt(min);
        var maxx = parseInt(max);
        var tot = field.length;
        if (tot >= minx && tot < maxx) {

        }
        else {
            document.getElementById("btn").disabled = true;
            $("#" + cntrl).css("border-color", "red");
        }
    }
}