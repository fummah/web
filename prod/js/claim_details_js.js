    $(document).ready(function()
    {
// Animate loader off screen
        $(".se-pre-con").fadeOut("slow");
        $(".w3-card").addClass("w3-animate-zoom");
        $("#hid").click(function()
        {
            $("#notifications").hide("slide");
        });
        function addFeedback(claim_id){

            var fxdfeedback=$("#fxd").val();
            var txtfeedback=$("#feedback_desc").val();
            var feedback=fxdfeedback+". "+txtfeedback;
            $("#feedbackShow").show();

            var obj={
                claim_id:claim_id,
                feedback:feedback
            };

            $.ajax({
                url:"feedback.php",
                type:"POST",
                data:obj,
                success:function(data){
                    $("#feedbackShow").hide();
                    $("#alert1").html(data);

                    if(data.indexOf("Your feedback have been added to the system")>-1)
                    {

                        $("#alert1").removeClass("uk-alert-danger");
                        $("#alert1").addClass("uk-alert-success");
                        $("#alert1").show();
                        $(".nothing").hide();
                        $("#t02").append("<article class=\"uk-comment\"><header class=\"uk-comment-header\"><div class=\"uk-grid-medium uk-flex-middle\" uk-grid><div class=\"uk-width-expand\">" +
                            "<ul class=\"uk-comment-meta uk-subnav uk-subnav-divider uk-margin-remove-top\">" +
                            "<li><a href=\"#\" id=\"mytime\">" + currentDate() + "</a></li><li><a href=\"#\" id=\"mytime\">You</a></li></ul></div></div></header><div class=\"uk-comment-body\"><p>" + txtfeedback + "</p></div></article><hr>");

                        $("#feedback_desc").val(" ");
//$("#addFeedback").attr("disabled", "disabled");
                    }

                    else
                    {
                        $("#alert1").addClass("uk-alert-danger");
                        $("#alert1").removeClass("uk-alert-success");

                        $("#alert1").show();
                    }


                },
                error:function(jqXHR, exception)
                {
                    $("#feedbackShow").hide();
                    $("#alert1").show();
                    $("#alert1").html("There is an error : "+jqXHR.responseText);
                }
            });

        }

        $(".zeroc").blur(function(){
            var schemesavings = $("#savings_scheme").val();
            var discountsavings = $("#savings_discount").val();
            var tot_scheme = parseFloat(schemesavings);
            var tot_discount = parseFloat(discountsavings);
            if((tot_discount+tot_scheme) > 1)
            {
                $("#spanzero").hide();
            }
            else {
                $("#spanzero").show();
            }
        });

        $('.qa_tick').on('click', function(event) {
            var myid=$(this).attr("id");
            var splitid=myid.split("_");
            var disnabl=splitid[0];
            var claim_id=splitid[1];
            if(disnabl=="no")
            {
                var qa_status=$(this).is(":checked")?1:0;
                var obj={
                    identity_number: 36,
                    claim_id:claim_id,
                    qa_status:qa_status
                };
                $.ajax({
                    url:"ajax/claims.php",
                    type:"POST",
                    data:obj,
                    success:function(data){
                        UIkit.notification({message: data});
                    },
                    error:function(jqXHR, exception)
                    {
                        alert("Connection error");
                    }
                });
                return true;
            }
            else {
                UIkit.notification({message: "Not Allowed"});
                event.preventDefault();
                event.stopPropagation();
                return false;
            }

        });
        $('.clinical_review').on('click', function(event) {
            var myid=$(this).attr("id");
            var splitid=myid.split("_");
            var disnabl=splitid[0];
            var claim_id=splitid[1];
            if(disnabl=="no")
            {
                var qa_status=$(this).is(":checked")?1:0;
                $("#sendclinicalx").click();
                $("#hidclinical").val(claim_id)
                return true;
            }
            else {
                UIkit.notification({message: "Not Allowed"});
                event.preventDefault();
                event.stopPropagation();
                return false;
            }

        });
    });
    function newClinical()
    {
        $("#clinicalText").text("please wait...");
        $("#clinicalText").css("color","red");
        var txtclinicalnote=$("#clinicalnote1").val();
        if(txtclinicalnote.length>2) {
            var ref1 = 77;
            var claim_id=$("#hidclinical").val();
            $("#clinicalText").show();
            var obj = {
                claim_id: claim_id,
                txtclinicalnote: txtclinicalnote,
                ref1: ref1
            };
            $.ajax({
                url: "clinical_notes.php",
                type: "POST",
                data: obj,
                success: function (data) {
                    $("#clinicalText").css("color","green");
                    $("#clinicalText").html(data);
                },
                error: function (jqXHR, exception) {
                    alert("There is an error");
                }
            });
        }
    }
    function addclinicalNotes(claim_id){

        var txtclinicalnote=$("#cnotes").val();
        if(txtclinicalnote.length>2) {
            var ref1 = 0;
            if (document.getElementById("refback").checked) {
                ref1 = 1;
            }
            $("#clinicalShow").show();
            var obj = {
                claim_id:claim_id,
                txtclinicalnote: txtclinicalnote,
                ref1: ref1
            };

            $.ajax({
                url: "clinical_notes.php",
                type: "POST",
                data: obj,
                success: function (data) {

                    $("#clinicalShow").hide();
                    $("#clinicalAlert").html(data);


                    if (data.indexOf("Your note have been added to the system") > -1) {

                        $("#clinicalAlert").removeClass("uk-alert-danger");
                        $("#clinicalAlert").addClass("uk-alert-success");
                        $("#clinicalAlert").show();
                        $(".clinicalnotesDiv").append("<h4 class='feedbackHeader'><b style='color: #3e8f3e'>You</b> posted on <i style=\"color: #0d92e1\">" + currentDate() + "</i></h4><p class='feedbackParagraph'>" + obj.txtclinicalnote + "</p>");
                        $("#t01").first().append("<tr class='alert-danger'><td>"+currentDate()+"</td><td>"+obj.txtclinicalnote+myApn+"</td><td>0</td></tr>");
                        $("#cnotes").val(" ");
//$("#addFeedback").attr("disabled", "disabled");
                    } else {
                        $("#clinicalAlert").addClass("uk-alert-danger");
                        $("#clinicalAlert").removeClass("uk-alert-success");
                        $("#clinicalAlert").html("There is an error. "+data);

                        $("#clinicalAlert").show();
                    }


                },
                error: function (jqXHR, exception) {
                    $("#clinicalShow").hide();
                    $("#clinicalAlert").show();
// $("#alert1").html("There is an error : "+jqXHR.responseText);
                }
            });
        }
    }
    function getNum(val) {
        if (isNaN(val)) {
            return 0;
        }
        return val;
    }

    function addNotes(claim_id,sla=0){
        $("#meshow").empty();
        $("#meshow").text("Please wait...");
        $("#meshow").css("color","red");
        $("#meshow").removeClass("uk-alert-success");
        $("#meshow").removeClass("uk-alert-danger");
        var notes=$("#intervention_desc").val();
        var consent_dest=$("#consent_dest").val();
        var em=$("#emmrg").val();
        if(em=="1" || em=="0") {
            $("#meshow").show();
            var op=radio();
            var op1=radio1();

            var practice_number=$("#doc_practiceno").text();
            var doc_name=$("#doc_name").text();
            var schemesavings=$("#doc_schemesavings").val();
            var discountsavings=$("#doc_discountsavings").val();
            var vas=$("#doc_vas").val();
            var xjson=$("#xjson").val();
            var ssid="scd"+practice_number;
            var disid="dsd"+practice_number;
            var vasid="vas"+practice_number;
            $("#"+ssid).text(schemesavings);
            $("#"+disid).text(discountsavings);
            $("#"+vasid).text(vas);
            var tot_scheme= parseFloat("0.0");
            var tot_discount=parseFloat("0.0");
            var tot_vas=parseFloat("0.0");
            var tt=$("#allmydoc").val();

            var xarray=tt.split(',');
            for (var i = 0; i < xarray.length-1; i++) {

                var ssid1="scd"+xarray[i];
                var disid1="dsd"+xarray[i];
                var vas1="vas"+xarray[i];
                var s=parseFloat($("#"+ssid1).text());
                var d=parseFloat($("#"+disid1).text());
                var v=parseFloat($("#"+vas1).text());
                s=getNum(s);
                d=getNum(d);
                v=getNum(v);
                tot_scheme+=s;
                tot_discount+=d;
                tot_vas+=v;
            }
            $("#savings_scheme").val(tot_scheme);
            $("#savings_discount").val(tot_discount);
            $("#vas_savings").val(tot_vas);
            var obj={
                open:op,
                notes:notes,
                remin:"",
                remSt:"",
                claim_id:claim_id,
                practice_number:practice_number,
                schemesavings:schemesavings,
                discountsavings:discountsavings,
                vas:vas,
                doc_name:doc_name,
                sla:sla,
                pay_doctor:op1,
                xjson:xjson,
                consent_dest:consent_dest
            };
            $.ajax({
                url:"case_update.php",
                type:"POST",
                data:obj,
                success:function(data){

                    if(data.indexOf("Your notes have been added to the system")>-1)
                    {
                        $("#meshow").removeClass("uk-alert-danger");
                        $("#meshow").addClass("uk-alert-success");
                        $("#meshow").css("color","green");
                        $("#meshow").show();
                        $(".nothing").hide();
                        $("#meshow").html(data);
                        $("#t01").append("<article class=\"uk-comment\"><header class=\"uk-comment-header\"><div class=\"uk-grid-medium uk-flex-middle\" uk-grid><div class=\"uk-width-expand\">" +
                            "<ul class=\"uk-comment-meta uk-subnav uk-subnav-divider uk-margin-remove-top\">" +
                            "<li><a href=\"#\" id=\"mytime\">" + currentDate() + "</a></li><li><a href=\"#\" id=\"mytime\">You</a></li></ul></div></div></header><div class=\"uk-comment-body\"><p>" + notes + "</p></div></article><hr>");
                        $("#donenext").show();
                        document.getElementById("addNotes").disabled = true;
                        $("#intervention_desc").val(" ");
                    }
                    else if(data.indexOf("Close")>-1)
                    {
                        $("#meshow").hide();
                        $(".nothing").hide();
                        $("#not_closed").hide();
                        $("#donenext").show();
                        if((tot_discount+tot_scheme) > 1)
                        {
                            $("#spanzero").hide();
                        }
                        else {
                            $("#spanzero").show();
                        }
                        $("#closesavings").click();
//$('#close_case_modal').modal('show');
                        $("#t01").append("<article class=\"uk-comment\"><header class=\"uk-comment-header\"><div class=\"uk-grid-medium uk-flex-middle\" uk-grid><div class=\"uk-width-expand\">" +
                            "<ul class=\"uk-comment-meta uk-subnav uk-subnav-divider uk-margin-remove-top\">" +
                            "<li><a href=\"#\" id=\"mytime\">" + currentDate() + "</a></li><li><a href=\"#\" id=\"mytime\">You</a></li></ul></div></div></header><div class=\"uk-comment-body\"><p>" + notes + "</p></div></article><hr>");

                        document.getElementById("addNotes").disabled = true;
                        $("#intervention_desc").val(" ");
                    }
                    else
                    {
                        $("#meshow").removeClass("uk-alert-success");
                        $("#meshow").addClass("uk-alert-danger");
                        $("#meshow").html(data);

                    }


                },
                error:function(jqXHR, exception)
                {
                    $("#meshow").hide();
                    $("#alert").show();
                    $("#alert").html("There is an error : "+jqXHR.responseText);
                }
            });
        }
        else {
            alert("Please update emergency field before you make any note");
        }
    }


    ///end here

    function addSavings(claim_id){

        $("#modShow").show();
        var scheme=$("#savings_scheme").val();
        var discount=$("#savings_discount").val();
        var vas=$("#vas_savings").val();
        var catergory=$("#zerosavings").val();
        var op=radio();
        var obj={
            scheme:scheme,
            discount:discount,
            catergory:catergory,
            vas:vas,
            claim_id:claim_id
        };

        $.ajax({
            url:"close_case.php",
            type:"POST",
            data:obj,
            success:function(data){
                $("#modShow").hide();
                $("#modAlert").show();
                $("#modAlert").html(data);
                $("#xxadmed1").hide();
                document.getElementById("intervention_desc").disabled=true;

            },
            error:function(jqXHR, exception)
            {
                $("#modShow").hide();
                $("#modAlert").show();
                $("#modAlert").html("There is an error : "+jqXHR.responseText);
            }
        });

    }
    //============Feedback Code Starts here
    function getCalim(claim_id)
    {

        var obj={
            claim_id:claim_id
        };

        $.ajax({
            url:"post_api.php",
            type:"GET",
            data:obj,
            success:function(data){

                $("#modAlert").append(data);
            },
            error:function(jqXHR, exception)
            {
                alert("An error");
            }
        });
    }
    function radio()
    {
        var radios = document.getElementsByName('Open');
        var open="";

        for (var i = 0, length = radios.length; i < length; i++) {
            if (radios[i].checked) {
// do whatever you want with the checked radio
                open=(radios[i].value);

// only one radio can be logically checked, don't check the rest
                break;
            }
        }
        return open;
    }

    function valid()
    {
        var nn=document.getElementById("intervention_desc").value;
        document.getElementById("addNotes").disabled = false;
        if(nn=="")
        {
            document.getElementById("addNotes").disabled = true;
        }
    }
    function valid1()
    {
        var nn=document.getElementById("feedback_desc").value;
        document.getElementById("addFeedback").disabled = false;
        if(nn=="")
        {
            document.getElementById("addFeedback").disabled = true;
        }
    }

    function currentDate()
    {
        var currentTime = new Date();
        hour = currentTime.getHours();
        min = currentTime.getMinutes();
        mon = currentTime.getMonth() + 1;
        day = currentTime.getDate();
        year = currentTime.getFullYear();
        if (mon.toString().length == 1) {
            var mon = '0' + mon;
        }
        if (day.toString().length == 1) {
            var day = '0' + day;
        }
        if (hour.toString().length == 1) {
            var hour = '0' + hour;
        }
        if (min.toString().length == 1) {
            var min = '0' + min;
        }

        var gg = year + "-" + mon + "-" + day + " " + hour + ":" + min;

        return gg;
    }


    function delete_note(note_id) {

        if(confirm("Are you sure you want to delete this note?"))
        {
            var obj={identity_number:11,note_id:note_id};
            $.ajax({
                url:"ajax/claims.php",
                type:"POST",
                data:obj,
                success:function(data){
                    if(data.indexOf("Deleted")>-1)
                    {
                        UIkit.notification({message: "Note successfully deleted"});
                        $("#"+note_id).css("background-color","pink");
                    }
                    else {
                        alert(data);
                    }
                },
                error:function(jqXHR, exception)
                {
                    alert("There is an error");
                }
            });
        }

    }
    function editNoteModal(id)

    {
        var txt=$("#"+id).text();
        $("#editnote").val(txt);
        $("#hid").val(id);

    }

    function updateFeedback() {
//alert("Test");
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {

            }
        };
        xhttp.open("GET", "ajaxPhp/deleting.php?identity=7", true);
        xhttp.send();
    }
    /////////////////////////////////////////////////////
    function updateText() {
        $('#resultText').show();
        var text= $('#editnote').val();
        var textid=$('#hid').val();
        if(text==""){
            $('#resultText').html("<b style='color: red'>Please write something</b>");
        }
        else {
            $('#resultText').html("<b style='color: red'>Please wait...</b>");
            var obj = {identity_number: 13, textid: textid, text: text};
            $.ajax({
                url: "ajax/claims.php",
                type: "POST",
                data: obj,
                success: function (data) {
                    $('#resultText').html(data)
                    var resT= $('#resultText').text();
                    if(data.indexOf("Updated!!!")>-1)
                    {
                        $("#"+textid).text(text);
                        $("#"+textid).addClass("uk-alert-success");
                    }


                },
                error: function (jqXHR, exception) {
                    $('#resultText').html(jqXHR.responseText);
                }
            });
        }
    }
    ////////////////////////////////////////////////

    $(document).ready(function () {
        $(".midx").hide();
    });

    $(function () {
        $('*[name=date10]').appendDtpicker({
            "closeOnSelected": true
        });
    });
    $(function () {
        $('*[name=date11]').appendDtpicker({
            "closeOnSelected": true
        });
    });

    function vbv() {
        var vala=$('#reminder').is(':checked');
        if(vala)
        {

            $("#reminder1").slideDown();
        }
        else
        {

            $('#reminder1').slideUp();
        }
    }
    function updateReminder(id) {
        var obj={
            intId:id
        };
        $.ajax({
            url:"ajaxPhp/deleting.php?identity=9",
            type:"GET",
            data:obj,
            success:function(data){

                alert("Completed!!!" );
            },
            error:function(jqXHR, exception)
            {

            }
        });
    }
    function sendConsent(claim_id,consent_descr)
    {

        if(consent_descr.indexOf("_")>0)
        {
            if (confirm('Do you really want to resend consent form?('+consent_descr+')')) {
                sendConsent1(claim_id);
            } else {

                return false;
            }
        }
        else
        {
            sendConsent1(claim_id);
        }


    }
    function sendConsent1(claim_id) {
        $('#consentID').text("Please wait...");
        var obj={
            claim_id:claim_id
        };
        $.ajax({
            url:"sendConsent.php",
            type:"POST",
            data:obj,
            success:function(data){
                $("#t01").append("<article class=\"uk-comment\"><header class=\"uk-comment-header\"><div class=\"uk-grid-medium uk-flex-middle\" uk-grid><div class=\"uk-width-expand\">" +
                    "<ul class=\"uk-comment-meta uk-subnav uk-subnav-divider uk-margin-remove-top\">" +
                    "<li><a href=\"#\" id=\"mytime\">" + currentDate() + "</a></li><li><a href=\"#\" id=\"mytime\">You</a></li></ul></div></div></header><div class=\"uk-comment-body\"><p>" + data + "</p></div></article><hr>");
                $('#consentID').text("Send Consent");
                alert(data);
            },
            error:function(jqXHR, exception)
            {
                $('#consentID').text("Send Consent");
                alert(jqXHR.responseText);
            }
        });
    }
    function doctor(id) {
        var doc=0;
        var x = document.getElementById("doctor").checked;
        if(x)
        {
            doc=1;
        }

        var obj={
            id:id,
            doc:doc
        };
        $.ajax({
            url:"ajaxPhp/deleting.php?identity=15",
            type:"GET",
            data:obj,
            success:function(data){
                if(data=="Done!!!")
                {
                    alert(data);
                }
                else
                {
                    alert(data);
                }

            },
            error:function(jqXHR, exception)
            {
                alert(jqXHR.responseText);
            }
        });
    }
    function CopyToClipboard(containerid) {
        if (document.selection) {
            var range = document.body.createTextRange();
            range.moveToElementText(document.getElementById(containerid));
            range.select().createTextRange();
            document.execCommand("copy");

        } else if (window.getSelection) {
            var range = document.createRange();
            range.selectNode(document.getElementById(containerid));
            window.getSelection().addRange(range);
            document.execCommand("copy");

        }}

    function hideSection(section) {
        $('.'+section).slideToggle();
    }

    var checkx=0;
    function  myClaimLine(myid,claim_id,level) {

        checkx++;
        var sd="s"+checkx+myid;
        var tc="t"+checkx+myid;
        var icd="i"+checkx+myid;
        var ca="c"+checkx+myid;
        var ss="ss"+checkx+myid;
        var gap="g"+checkx+myid;
        var btn="btn"+checkx+myid;
        var rw="rw"+checkx+myid;
        var cpt="cpt"+checkx+myid;
        var res="res"+checkx+myid;
        var trt="trt"+checkx+myid;
        var inv="inv"+checkx+myid;
        var act_gap="act_gap"+checkx+myid;
        var newrow="new"+myid;
        var whitesmoke="whitesmoke";
        var uniq=8;
        if(level=="claim_line")
        {
            newrow="main"+myid;
            whitesmoke="floralwhite";
            uniq=9;
            $("#"+newrow).hide();
        }
        $("<tr class='me uk-animation-fade' id='"+rw+"' style='background-color:"+whitesmoke+"'><td></td><td><input type='text' class='form-control' id='"+cpt+"'></td><td colspan='2'><input type=\"date\" id='"+inv+"' class='form-control' title='Invoice date'></td><td><select style='width: 75% !important;' id='"+res+"' class='form-control'><option></option><option value='001 - copayment'>001 - copayment</option><option value='002- Prosthesis'>002- Prosthesis</option><option value='003 - Casualty'>003 - Casualty</option><option value='004 - Benefit Exclusion'>004 - Benefit Exclusion</option><option value='005 - Rejected'>005 - Rejected</option><option value='006 - Oncology'>006 - Oncology</option><option value='007 - Materials'>007 - Materials</option></select></td><td><input  type=\"date\" id='"+trt+"' class='form-control'></td><td><span id='"+sd+"'></span></td><td><input style='width: 60px' type=\"text\" id='"+tc+"' class='form-control'></td><td><input style='width: 60px' type=\"text\" id='"+icd+"' class='form-control'></td><td><input style='width: 100px' type=\"number\" id='"+ca+"' class='form-control'></td><td><input type=\"number\" id='"+ss+"' class='form-control'></td><td><input type=\"number\" id='"+gap+"' class='form-control'></td><td><input style='width: 100px' type=\"number\" id='"+act_gap+"' class='form-control'></td><td><button id='"+btn+"' onclick=\"myClaimView('"+myid+"','"+claim_id+"','"+checkx+"','"+level+"','"+uniq+"')\" class=\"uk-button uk-button-primary uk-button-small\">Save</button><span class='done'></span></td><td><span onclick=\"hideRow('"+myid+"','"+checkx+"','"+level+"')\" style='color:red; cursor: pointer' uk-icon=\"icon: close\"></span></td></tr>").insertAfter("#"+newrow).fadeIn("slow");
        if(level=="claim_line")
        {
            var obj={identity_number:7,claim_line_id: myid};
            $.ajax({
                url: "ajax/claims.php",
                type: "POST",
                data: obj,
                success: function (data) {
                    console.log(data);
                    if(data.indexOf("There is an error")<0)
                    {
                        var json=JSON.parse(data);
                        $("#"+tc).val(json["tariff_code"]);
                        $("#"+icd).val(json["primaryICDCode"]);
                        $("#"+ca).val(json["clmnline_charged_amnt"]);
                        $("#"+ss).val(json["clmline_scheme_paid_amnt"]);
                        $("#"+gap).val(json["gap"]);
                        $("#"+cpt).val(json["cptCode"]);
                        $("#"+res).prepend("<option value='"+json["msg_code"]+"'>"+json["msg_code"]+"</option>");
                        $("#"+trt).val(json["treatmentDate"]);
                        $("#"+act_gap).val(json["gap_aamount_line"]);
                        $("#"+inv).val(json["benefit_description"]);
                    }
                    else {
                        alert(data);
                    }

                },
                error: function (jqXHR, exception) {
                    alert("Connection error");

                }
            });
        }
    }
    function  myClaimView(myid,claim_id,ch,level,uniq) {
        var sd=$("#s"+ch+myid).val();
        var tc=$("#t"+ch+myid).val();
        var icd=$("#i"+ch+myid).val();
        var ca=$("#c"+ch+myid).val();
        var ss=$("#ss"+ch+myid).val();
        var gap=$("#g"+ch+myid).val();
        var rw="rw"+ch+myid;
        var cpt=$("#cpt"+ch+myid).val();
        var inv=$("#inv"+ch+myid).val();
        var res=$("#res"+ch+myid).val();
        var trt=$("#trt"+ch+myid).val();
        var act_gap=$("#act_gap"+ch+myid).val();
        $(".done").text("please wait...");
        var obj={sd:sd,tc:tc,icd:icd,ca:ca,ss:ss,gap:gap,identity_number:uniq,myid:myid,claim_id:claim_id,cpt:cpt,practice_number:myid,invoice_date:inv,act_gap:act_gap,res:res,trt:trt};
        $.ajax({
            url:"ajax/claims.php",
            type:"POST",
            data:obj,
            success:function(data){
                if(data.indexOf("Done!!!")>-1)
                {
                    $("#"+rw).css("background-color","#d1ece4");
                    $("#"+rw).addClass("uk-alert-success");
                    $(".done").text("");
//var rw="rw"+ch+myid;
                    $("#rw"+ch+myid).hide();
                    var newrow="new"+myid;
                    var cllc=gap-act_gap;
                    if(level=="claim_line")
                    {
                        newrow="main"+myid;
                    }
                    $("<tr class='uk-animation-fade' style='background-color:#d1ece4'><td></td><td>"+cpt+"</td><td colspan='2'>"+inv+"</td><td>"+res+"</td><td>"+trt+"</td><td>-</td><td>"+tc+"</td><td>["+icd+"]</td><td>"+ca+"</td><td>"+ss+"</td><td>"+gap+"</td><td>"+act_gap+"</td><td>"+cllc+"</td><td></span></td></tr>").insertAfter("#"+newrow).fadeIn("slow");
                }
                else
                {
                    alert(data);
                }

            },
            error:function(jqXHR, exception)
            {
                alert("There is an error");
            }
        });
//alert(sd+"---"+tc+"---"+icd+"---"+ca+"---"+ss+"---"+gap);
    }
    function hideRow(myid,ch,level) {
        var rw="rw"+ch+myid;
        $("#"+rw).fadeOut("slow");
        if(level=="claim_line")
        {
            var newrow="main"+myid;
            $("#"+newrow).show();
        }

    }
    function showEnteries(pracno){
        $("#main"+pracno).hide("fast");
        $("#txt"+pracno).show();
        var obj={identity:23};
        var obj1={identity:24};
        $.ajax({
            url:"ajaxPhp/deleting.php",
            type:"GET",
            data:obj,
            success:function(data){
                var t=JSON.parse(data);
                console.log(t);
                var x=t.length;
                var i=0;
                for(i=0;i<x;i++)
                {
                    var name=t[i]["name"];
                    $(".ben").append("<option value='"+name+"'>"+name+"</option>");
                }

            },
            error:function(jqXHR, exception)
            {

            }
        });
        $.ajax({
            url:"ajaxPhp/deleting.php",
            type:"GET",
            data:obj1,
            success:function(data){
                var t=JSON.parse(data);
                console.log(t);
                var x=t.length;
                var i=0;
                for(i=0;i<x;i++)
                {
                    var code=t[i]["remark_code"];
                    var descr=t[i]["long_description"];
                    var all=code+"---"+descr;
                    $(".rej").append("<option value='"+code+"'>"+all+"</option>");
                }

            },
            error:function(jqXHR, exception)
            {

            }
        });
    }
    function editStuff(myid,claim_id,pracno_1,disciplinecode) {

        var ic=$("#ic"+myid).val();
        var tar=$("#tar"+myid).val();
        var char=$("#char"+myid).val();
        var sch=$("#sch"+myid).val();
        var ga=$("#ga"+myid).val();
//var bt=$("#bt"+myid).val();
        var be=$("#be"+myid).val();
        var re=$("#re"+myid).val();
        var su=$("#su"+myid).val();
        var tr=$("#tr"+myid).val();
        var cpt=$("#cpt"+myid).val();
        var ga1=$("#ga1"+myid).val();
        $("#practyp"+pracno_1).empty();
        var obj={tc:tar,icd:ic,ca:char,ss:sch,gap:ga,ben:be,rej:re,sus:su,trt:tr,identity:19,claim_line_id:myid,claim_id:claim_id,pracno_1:pracno_1,disciplinecode:disciplinecode,cpt:cpt,act_gap:ga1};
        $.ajax({
            url:"ajaxPhp/deleting.php",
            type:"GET",
            data:obj,
            success:function(data){

                if(data.indexOf("Done!!!")>-1)
                {
                    $("#txt"+myid).css("background-color","green");
                    $("#bt"+myid).hide();
//  alert("Record Successfully Saved");
                    if(data.indexOf("Check")>-1)
                    {

                        $("#practyp"+pracno_1).text("This provider may not have applied modifier 0005 properly. Please check the invoice and call the provider if needed");
                    }

                }
                else
                {
                    alert(data);
                }

            },
            error:function(jqXHR, exception)
            {
                alert("There is an error 1");
            }
        });
    }

    function updateClaimNumber(claim_id) {
        var claim_number=$("#claim_number").val();

        var obj={identity:30,claim_id:claim_id,claim_number:claim_number};
        $.ajax({
            url:"ajaxPhp/deleting.php",
            type:"GET",
            data:obj,
            success:function(data){

                alert(data);

            },
            error:function(jqXHR, exception)
            {
                alert("There is an error");
            }
        });
    }
    function deleteLIne(claimline_id) {

        var obj={identity_number:10,claimline_id:claimline_id};
        $.ajax({
            url:"ajax/claims.php",
            type:"POST",
            data:obj,
            success:function(data){
                if(data.indexOf("Deleted")>-1)
                {
                    UIkit.notification({message: "Claim Line successfully deleted"});
                    $("#main"+claimline_id).css("background-color","pink");
                }
                else {
                    alert(data);
                }

            },
            error:function(jqXHR, exception)
            {
                alert("There is an error");
            }
        });
    }

    function highlitDoctor(practice_number,doc_name,zesthid) {

        $("#doc_detail1").show();
        var ssid="scd"+practice_number;
        var disid="dsd"+practice_number;
        var pydid="pyd"+practice_number;
        var vasid="vas"+practice_number;
        var txt_schemesavings=$("#"+ssid).text();
        var txt_discountsavings=$("#"+disid).text();
        var txt_vas=$("#"+vasid).text();
        var xcx=$("#"+pydid).text();

        $("#doc_name").text(doc_name);
        $("#doc_practiceno").text(practice_number);
        $("#doc_schemesavings").val(txt_schemesavings);
        $("#doc_discountsavings").val(txt_discountsavings);
        $("#doc_vas").val(txt_vas);
//$("#intervention_desc").val("test");
        var e=$("#intervention_desc");
        setTimeout(function(){e.focus();}, 50);
        if(zesthid=="hidden")
        {
            $("#doc_discountsavings").hide();
        }
        if(xcx=="yes")
        {
            document.getElementById("pay_doctor1").checked = true;
        }
        else
        {
            document.getElementById("pay_doctor2").checked = true;
        }
        $(".doc_class").removeClass("alert-info");
        $("."+practice_number).addClass("alert-info");
//$("."+practice_number).removeClass("w3-hover-white");
    }

    function checkDates(claim_id) {

        $.ajax({
            url: "ajax/claims.php",
            type: "POST",
            data: {
                identity_number:21,
                claim_id:claim_id
            },
            success: function (data) {
                $(".not").html(data);
            },
            error: function (jqXHR, exception) {
                $('#resultText').html(jqXHR.responseText);
            }
        });
    }
    function loadValidations(claim_id) {

        $.ajax({
            url: "ajax/claims.php",
            type: "POST",
            data: {
                identity_number:24,
                claim_id:claim_id
            },
            success: function (data) {
                $("#validations").html(data);
            },
            error: function (jqXHR, exception) {
                $('#resultText').html(jqXHR.responseText);
            }
        });
    }
    function updateMember(id) {
        var doc=0;
        var x = document.getElementById("contactmember").checked;
        var txt=$("#memtxt").val();
        if(txt!="") {
            var def = "Member contacted : " + txt;
            if (x) {
                doc = 1;
            }
            var obj = {
                identity_number:22,
                claim_id: id,
                doc: doc,
                txt:def
            };
            $.ajax({
                url: "ajax/claims.php",
                type: "POST",
                data: obj,
                success: function (data) {
                    $(".nothing1").hide();
                    $("#t01").append("<article class=\"uk-comment\"><header class=\"uk-comment-header\"><div class=\"uk-grid-medium uk-flex-middle\" uk-grid><div class=\"uk-width-expand\">" +
                        "<ul class=\"uk-comment-meta uk-subnav uk-subnav-divider uk-margin-remove-top\">" +
                        "<li><a href=\"#\" id=\"mytime\">" + currentDate() + "</a></li><li><a href=\"#\" id=\"mytime\">You</a></li></ul></div></div></header><div class=\"uk-comment-body\"><p>" + txt + "</p></div></article><hr>");
                    UIkit.notification({message: data});

                },
                error: function (jqXHR, exception) {
                    alert(jqXHR.responseText);
                }
            });
        }
        else
        {
            alert("Invalid text");
        }
    }

    function radio1()
    {
        var radios = document.getElementsByName('pay_doctor');
        var open="";

        for (var i = 0, length = radios.length; i < length; i++) {
            if (radios[i].checked) {
// do whatever you want with the checked radio
                open=(radios[i].value);

// only one radio can be logically checked, don't check the rest
                break;
            }
        }
        return open;
    }

    function admedcheck() {

        if(document.getElementById("xxadmed").checked)
        {
            $("#xxadmed2").show();
        }
        else {
            $("#xxadmed2").hide();
        }

    }

    function updateCoding(id) {
        var doc=0;
        var x = document.getElementById("codingcptcodingcpt").checked;
        if(x)
        {
            doc=1;
        }
        var txt=$("#memtxt4").val();
        var idx =4;
        var obj={
            claim_id:id,
            doc:doc,
            txt:txt,
            idx:idx,
            identity_number:32
        };
        $.ajax({
            url:"ajax/claims.php",
            type:"POST",
            data:obj,
            success:function(data){
                $("#myspan4").append("<ul><li>"+txt+"</li></ul>");
                $("#memtxt4").val("");
                alert(data);

            },
            error:function(jqXHR, exception)
            {
                alert(jqXHR.responseText);
            }
        });
    }
    function icd10_emergency(id) {
        var doc=0;
        var x = document.getElementById("icd10_emergency").checked;
        var txt=$("#memtxt3").val();
        if(txt.length>1) {
            var idx=3;
            if(x)
            {
                doc=1;
            }
            var obj={
                claim_id:id,
                doc:doc,
                txt:txt,
                idx:idx,
                identity_number:25
            };
            $.ajax({
                url:"ajax/claims.php",
                type:"POST",
                data:obj,
                success:function(data){
                    $("#myspan3").append("<ul><li>"+txt+"</li></ul>");
                    alert(data);
                    $("#memtxt3").val("");


                },
                error:function(jqXHR, exception)
                {
                    alert(jqXHR.responseText);
                }
            });
        }
        else {
            alert("invalid input")
        }
    }
    function provider_zf(id) {
        var doc=0;
        var x = document.getElementById("provider_zf").checked;
        var txt=$("#memtxt2").val();
        if(txt.length>1) {
            var idx=2;
            if(x)
            {
                doc=1;
            }
            var obj={
                claim_id:id,
                doc:doc,
                txt:txt,
                idx:idx,
                identity_number:26
            };
            $.ajax({
                url:"ajax/claims.php",
                type:"POST",
                data:obj,
                success:function(data){
                    $("#myspan2").append("<ul><li>"+txt+"</li></ul>");
                    $("#memtxt2").val("");
                    alert(data);

                },
                error:function(jqXHR, exception)
                {
                    alert(jqXHR.responseText);
                }
            });
        }
        else {
            alert("invalid input")
        }
    }
    function is_atheniest(id) {
        var doc=0;
        var x = document.getElementById("is_atheniest").checked;
        var txt=$("#memtxt1").val();
//alert(txt);
        if(txt!="") {
            var idx = 1;
            if (x) {
                doc = 1;
            }
            var obj = {
                claim_id: id,
                doc: doc,
                txt: txt,
                idx: idx,
                identity_number:27
            };
            $.ajax({
                url: "ajax/claims.php",
                type: "POST",
                data: obj,
                success: function (data) {
                    $("#myspan1").append("<ul><li>" + txt + "</li></ul>");
                    alert(data);
                    $("#memtxt1").val("");

                },
                error: function (jqXHR, exception) {
                    alert(jqXHR.responseText);
                }
            });
        }
        else {
            alert("invalid input")
        }
    }

    function showhide(ele,itemem)
    {

        if( document.getElementById(ele).checked)
        {
            $("#"+itemem).show();

        }
        else {
            $("#"+itemem).hide();
        }
    }
    function chekconfirm(claim_id) {
        $.ajax({
            url: "ajax/claims.php",
            type: "POST",
            data: {
                identity_number:20,
                claim_id:claim_id
            },
            success: function (data) {
                var t=JSON.parse(data);
                var x=t.length;
                var i=0;
                for(i=0;i<x;i++)
                {
                    var id=t[i]["option_id"];
                    var note=t[i]["notes"];
                    $("#myspan"+id).append("<ul><li>" + note + "</li></ul>");
                }
            },
            error: function (jqXHR, exception) {
                $('#resultText').html(jqXHR.responseText);
            }
        });
    }

    function promotrClaim(claim_id) {
        if (confirm('Do you really want to Allocate this Case?')) {

            $.ajax({
                url: "ajax/claims.php",
                type: "POST",
                data: {
                    identity_number: 18,
                    claim_id: claim_id
                },
                success: function (data) {
                    if(data.indexOf("Successfully")>-1)
                    {
                        $("#hideme").hide();
                    }
                    $("#prinfo").html(data);


                },
                error: function (jqXHR, exception) {
                    alert(jqXHR.responseText);
                }
            });
        }
        else {
            return false;
        }
    }

    function saoa(id) {
        var doc=0;
        var x = document.getElementById("saoa").checked;
        var txt=$("#memtxt5").val();
        if(txt.length>1) {
            var idx=5;
            if(x)
            {
                doc=1;
            }
            var obj={
                claim_id:id,
                doc:doc,
                txt:txt,
                idx:idx
            };
            $.ajax({
                url:"ajax/deleting.php?identity=50",
                type:"GET",
                data:obj,
                success:function(data){
                    $("#myspan5").append("<ul><li>"+txt+"</li></ul>");
                    alert(data);


                },
                error:function(jqXHR, exception)
                {
                    alert(jqXHR.responseText);
                }
            });
        }
        else {
            alert("invalid input")
        }
    }
    function tarrif_0614(id) {
        var doc=0;
        var x = document.getElementById("tarrif_0614").checked;
        var txt=$("#memtxt7").val();
        if(txt!="") {
            var idx = 1;
            if (x) {
                doc = 1;
            }
            var obj = {
                claim_id: id,
                doc: doc,
                txt: txt,
                idx: idx
            };
            $.ajax({
                url: "ajax/deleting.php?identity=51",
                type: "GET",
                data: obj,
                success: function (data) {
                    $("#myspan7").append("<ul><li>" + txt + "</li></ul>");
                    alert(data);

                },
                error: function (jqXHR, exception) {
                    alert(jqXHR.responseText);
                }
            });
        }
        else {
            alert("invalid input")
        }
    }
    function insert8days(claim_id) {

        $('#eightShow').show();
//$('#eightShow').html("<b style='color: red'>Sending, please wait ...</b>");
        var text= $('#eightnotes').val();

        if(text==""){
            $('#eightAlert').html("<b style='color: red'>Please write something</b>");
        }
        else {
            var obj = {identity_number: 30, claim_id: claim_id, text: text};
            $.ajax({
                url: "ajax/claims.php",
                type: "POST",
                data: obj,
                success: function (data) {
                    if(data.indexOf("Successfully Added!!!")>-1)
                    {
                        $(".div8").prepend("<hr><div class=\"uk-grid-medium uk-flex-middle uk-grid uk-grid-stack\" style='background-color: lightblue'><div class=\"uk-width-expand uk-first-column\"><ul class=\"uk-comment-meta uk-subnav uk-subnav-divider uk-margin-remove-top\"><li>Posted By :<span style='color: green'>You</span></li><li><span>Now</span></li></ul><div class=\"uk-comment-body\" style=\"background-color: whitesmoke; padding: 10px; border-radius: 10px\"><p>"+text+"</p> </div> </div></div></div>");
                        $("#eightAlert").html(data);
                        $("#eightAlert").addClass("uk-alert-success");
                        $('#eightAlert').show();
                    }
                    else {
                        $("#eightAlert").html(data);
                        $("#eightAlert").addClass("uk-alert-danger");
                        $('#eightAlert').show();
                    }
                    $('#eightShow').hide();
                },
                error: function (jqXHR, exception) {
                    $('#eightAlert').html(jqXHR.responseText);
                    $('#eightShow').hide();
                    $('#eightAlert').show();
                }
            });
        }
//$('#eightShow').hide();
    }