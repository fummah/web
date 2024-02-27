var fload=0;
let created_funerals=[];
$(document).ready(function ()
{
    loadLocations();
    loadLastestFuneralDetails();
    $("#state_dep1").click(function (){
        $("#ddep").hide();
    });
    $("#state_dep2").click(function (){
        $("#ddep").show();
    });
    $("#search_term_txt").keyup(function(){
        var obj={
            identity_number:11,
            keyword:$(this).val()
        };
        $.ajax({
            type: "POST",
            url: "ajax/process.php",
            data:obj,
            beforeSend: function(){
                //$("#spinner").show();
            },
            success: function(data){
                $("#spinner").hide();
                $("#suggesstion-box-member").show();
                $("#suggesstion-box-member").html(data);
            }
        });
    });
    $("#search_term_funeral").keyup(function(){
        var obj={
            identity_number:16,
            keyword:$(this).val()
        };
        $.ajax({
            type: "POST",
            url: "ajax/process.php",
            data:obj,
            beforeSend: function(){
                //$("#spinner").show();
            },
            success: function(data){
                $("#spinner").hide();
                $("#suggesstion-box-funeral").show();
                $("#suggesstion-box-funeral").html(data);
            }
        });
    });
});
function tickMark(fid)
{
    var fidarr=fid.split("_");
    var funeral_id=fidarr[0];
    var member_id=fidarr[1];
    var ticked=0;
    if(document.getElementById(fid).checked)
    {
        ticked=1;
    }
    var obj={
        funeral_id:funeral_id,
        member_id:member_id,
        ticked:ticked,
        identity_number:9
    };
    $.ajax({
        url:"ajax/process.php",
        type:"POST",
        data:obj,
        success:function(data)
        {
            console.log(data);
            if(data.indexOf("deactivated")>0)
            {
                alert(data);
            }
            else {
                UIkit.notification({message: data, pos: 'bottom-right'});
                loadLastestFuneralDetails();
                $("#search_term_txt").val("");
                $("#search_term_txt").focus();
            }
        },
        error:function(jqXHR, exception)
        {
            console.log("There is an error : "+jqXHR.responseText);
        }
    });

}
function tickHome(fid)
{
    var fidarr=fid.split("_");
    var funeral_id=fidarr[0];
    var member_id=fidarr[1];
    var ticked=0;
    if(document.getElementById(fid).checked)
    {
        ticked=1;
    }
    var obj={
        funeral_id:funeral_id,
        member_id:member_id,
        ticked:ticked,
        identity_number:10
    };
    $.ajax({
        url:"ajax/process.php",
        type:"POST",
        data:obj,
        success:function(data)
        {
            console.log(data);
            UIkit.notification({message: data,pos: 'bottom-right'});
            $("#search_term_txt").val("");
            $("#search_term_txt").focus();
        },
        error:function(jqXHR, exception)
        {
            console.log("There is an error : "+jqXHR.responseText);
        }
    });

}
function loadDependncies(member_id)
{
    $("#mydependencies").empty();
    $.ajax({
        url:"ajax/process.php",
        type:"POST",
        data:{
            identity_number:6,
            member_id:member_id
        },
        success:function(data)
        {
            var json = JSON.parse(data);
            for(var key in json)
            {
                $("#mydependencies").append("<tr style='color: indianred !important;' id='"+json[key]["dependency_id"]+"'><td>"+json[key]["first_name"]+"</td><td>"+json[key]["surname"]+"</td><td>"+json[key]["d_o_b"]+"</td><td>"+json[key]["status"]+"</td><td><button class='uk-button uk-button-danger del' del='"+json[key]["dependency_id"]+"'>Delete</button></td></tr>");
            }
        },
        error:function(jqXHR, exception)
        {
            console.log("There is an error : "+jqXHR.responseText);
        }
    });
}
function loadLocations()
{
    $.ajax({
        url:"ajax/process.php",
        type:"POST",
        data:{
            identity_number:1
        },
        success:function(data)
        {
            var json = JSON.parse(data);
            //console.log(json);
            for(var key in json)
            {
                $(".location").append("<option value='"+json[key]["location_id"]+"'>"+json[key]["location_name"]+"</option>");
            }
        },
        error:function(jqXHR, exception)
        {
            console.log("There is an error : "+jqXHR.responseText);
        }
    });
}
function vFuneral(member_id,first_name,last_name)
{
    $("#member_id").val(member_id);
    $("#dependent").empty();
    $("#dependent").append("<option>Select Dependent</option>");
    $("#fullname1").text(first_name+" "+last_name);
    $.ajax({
        url:"ajax/process.php",
        type:"POST",
        data:{
            identity_number:6,
            member_id:member_id
        },
        success:function(data)
        {
            var json = JSON.parse(data);
            for(var key in json)
            {
                $("#dependent").append("<option value='"+json[key]["first_name"]+"'>"+json[key]["first_name"]+" "+json[key]["surname"]+"</option>");
            }
        },
        error:function(jqXHR, exception)
        {
            console.log("There is an error : "+jqXHR.responseText);
        }
    });
    $("#clickFuneral").click();
}
function editMember(member_id,first_name,last_name)
{
    $("#member_id").val(member_id);
    $("#edit_fullname").text(first_name+" "+last_name);
    $.ajax({
        url:"ajax/process.php",
        type:"POST",
        data:{
            identity_number:4,
            member_id:member_id
        },
        success:function(data)
        {
            var json = JSON.parse(data);
            $("#edit_first_name").val(json["first_name"]);
            $("#edit_last_name").val(json["last_name"]);
            $("#edit_contact_number").val(json["contact_number"]);
            $("#edit_id_number").val(json["id_number"]);
            $("#edit_email_address").val(json["email_number"]);
            $("#edit_location").val(json["location_id"]);
            $("#edit_status").val(json["status"]);
        },
        error:function(jqXHR, exception)
        {
            console.log("There is an error : "+jqXHR.responseText);
        }
    });
    $("#clickEditDetails").click();
}
function funeralOption(fullname,funeral_id)
{
    $("#funeral_idx").val(funeral_id);
    $("#funeral_name").text(fullname);
    $("#clickFuneralOptions").click();
}
function  viewMyFunerals(member_id,start_from,limit)
{
    let obj={
        member_id:member_id,
        start_from:start_from,
        limit:limit,
        identity_number:8
    };
    $.ajax({
        url:"ajax/process.php",
        type:"POST",
        data:obj,
        success:function(data)
        {
            //console.log(data);
            $("#myfunerals").append(data);
        },
        error:function(jqXHR, exception)
        {
            console.log("There is an error : "+jqXHR.responseText);
        }
    });
}
function  addDependent()
{
    let member_id=$("#member_id").val();
    let dependency_first_name=$("#dependency_first_name").val();
    let dependency_last_name=$("#dependency_last_name").val();
    let dependency_dob=$("#dependency_dob").val();
    let dependency_status=$("#dependency_status").val();
    let obj={
        dependency_first_name:dependency_first_name,
        dependency_last_name:dependency_last_name,
        dependency_dob:dependency_dob,
        dependency_status:dependency_status,
        member_id:member_id,
        identity_number:5
    };
    $.ajax({
        url:"ajax/process.php",
        type:"POST",
        data:obj,
        success:function(data)
        {
            console.log(data);
            if (data.indexOf("success")>-1)
            {

                $("#ddp").text("Added");
                loadDependncies(member_id);
            }
            else
            {
                $("#ddp").text("Failed");
            }
        },
        error:function(jqXHR, exception)
        {
            console.log("There is an error : "+jqXHR.responseText);
        }
    });
}
function saveEdit()
{
    $("#edit_msg").empty();
    let edit_first_name=$("#edit_first_name").val();
    let edit_last_name=$("#edit_last_name").val();
    let edit_contact_number=$("#edit_contact_number").val();
    let edit_id_number=$("#edit_id_number").val();
    let edit_email_address=$("#edit_email_address").val();
    let edit_location=$("#edit_location").val();
    let edit_status=$("#edit_status").val();
    let member_id=$("#member_id").val();
    //`member_id`,`first_name`,`last_name`,`contact_number`,`id_number`,`email_number`,a.status,a.entered_by,a.date_entered,b.location_name,a.location_id
    let obj={
        member_id:member_id,
        first_name:edit_first_name,
        last_name:edit_last_name,
        contact_number:edit_contact_number,
        id_number:edit_id_number,
        email_number:edit_email_address,
        location_id:edit_location,
        status:edit_status,
        identity_number:7
    };

    $.ajax({
        url:"ajax/process.php",
        type:"POST",
        data:obj,
        success:function(data)
        {
            $("#edit_msg").html(data);
        },
        error:function(jqXHR, exception)
        {
            console.log("There is an error : "+jqXHR.responseText);
        }
    });

}
function loadMemberDetails(member_id)
{
    fload=0;
    $("#member_id").val(member_id);
    $("#myfunerals").empty();
    $.ajax({
        url:"ajax/process.php",
        type:"POST",
        data:{
            identity_number:4,
            member_id:member_id
        },
        success:function(data)
        {
            var json = JSON.parse(data);
            $("#details_first_name").text(json["first_name"]);
            $("#details_last_name").text(json["last_name"]);
            $("#details_contact_number").text(json["contact_number"]);
            $("#details_id_number").text(json["id_number"]);
            $("#details_email_address").text(json["email_number"]);
            $("#details_date_entered").text(json["date_entered"]);
            $("#details_status").text(json["status"]);
            $("#details_entered_by").text(json["entered_by"]);
            $("#details_location").text(json["location_name"]);
            loadDependncies(member_id);
            viewMyFunerals(member_id,fload,10);
            fload++;
        },
        error:function(jqXHR, exception)
        {
            console.log("There is an error : "+jqXHR.responseText);
        }
    });

    $("#clickDetails").click();
}
function loadMore()
{
    var ttli=fload*10;
    var member_id=$("#member_id").val();
    viewMyFunerals(member_id,ttli,10);
    fload++;
}
function loadLastestFuneralDetails()
{
    $.ajax({
        url:"ajax/process.php",
        type:"POST",
        data:{
            identity_number:3
        },
        success:function(data)
        {
            console.log(data);
            var json = JSON.parse(data);
            let last_funeral_name=json["funeral_name"];
            let last_funeral_status=json["status"];
            let last_amount=json["amount_paid"];
            let last_tik=json["green_tick"];
            $("#last_funeral_name").text(last_funeral_name);
            $("#last_funeral_status").text(last_funeral_status);
            $("#last_amount").text(last_amount);
            $("#last_tik").text(last_tik);
            if(last_funeral_status==="Open")
            {
                $("#last_funeral_status").addClass("status-open");
                $("#last_funeral_status").removeClass("status-closed");
            }
            else
            {
                $("#last_funeral_status").addClass("status-closed");
                $("#last_funeral_status").removeClass("status-open");
            }

        },
        error:function(jqXHR, exception)
        {
            console.log("There is an error : "+jqXHR.responseText);
        }
    });
}
function loadMember(obj)
{
    $.ajax({
        url:"ajax/process.php",
        type:"POST",
        data:obj,
        success:function(data)
        {
            $("#member_msg").html(data);
            loadLastestFuneralDetails();
        },
        error:function(jqXHR, exception)
        {
            console.log("There is an error : "+jqXHR.responseText);
        }
    });
}
function addFuneral()
{
    $("#funeral_msg").empty();
    let mms=0;
    let role=$("#role").val();
    let confirm_password=$("#confirm_password").val();
    let password=$("#password").val();
    let location=$("#location").val();
    let contact_number=$("#contact_number").val();
    let last_name=$("#last_name").val();
    let first_name=$("#first_name").val();
    if(first_name.length<3)
    {
        $("#funeral_msg").append("<ul><li>Invalid first name</li></ul>");
        mms=1;
    }
    if(last_name.length<3)
    {
        $("#funeral_msg").append("<ul><li>Invalid last name</li></ul>");
        mms=1;
    }
    if(contact_number!=="")
    {
        if((contact_number.length<10 && contact_number.length>11) || !containsOnlyNumbers(contact_number))
        {
            $("#funeral_msg").append("<ul><li>Invalid Phone Number</li></ul>");
            mms=1;
        }
    }
    if(location.length<1)
    {
        $("#funeral_msg").append("<ul><li>Invalid Location</li></ul>");
        mms=1;
    }
    if(role.length<3)
    {
        $("#funeral_msg").append("<ul><li>Please select role</li></ul>");
        mms=1;
    }

    if(mms===1)
    {

        return false;
    }
    else
    {

        return true;
    }

}
function addUsers()
{
    $("#msg").empty();
    let mms=0;
    let role=$("#role").val();
    let confirm_password=$("#confirm_password").val();
    let password=$("#password").val();
    let location=$("#location").val();
    let contact_number=$("#contact_number").val();
    let last_name=$("#last_name").val();
    let first_name=$("#first_name").val();
    if(first_name.length<3)
    {
        $("#msg").append("<ul><li>Invalid first name</li></ul>");
        mms=1;
    }
    if(last_name.length<3)
    {
        $("#msg").append("<ul><li>Invalid last name</li></ul>");
        mms=1;
    }
    if((contact_number.length<10 && contact_number.length>11) || !containsOnlyNumbers(contact_number))
    {
        $("#msg").append("<ul><li>Invalid Phone Number</li></ul>");
        mms=1;
    }
    if(location.length<1)
    {
        $("#msg").append("<ul><li>Invalid Location</li></ul>");
        mms=1;
    }
    if(role.length<3)
    {
        $("#msg").append("<ul><li>Please select role</li></ul>");
        mms=1;
    }
    if(password.length<7)
    {
        $("#msg").append("<ul><li>Short password is not allowed</li></ul>");
        mms=1;
    }
    if(password!==confirm_password)
    {
        $("#msg").append("<ul><li>Password does not match</li></ul>");
        mms=1;
    }
    if(mms===1)
    {

        return false;
    }
    else
    {

        return true;
    }

}
function addMember()
{
    var checkBox = document.getElementById("paid");
    let tick_funeral=0;
    if (checkBox.checked === true){
        tick_funeral=1;
    }
    $("#member_msg").empty();
    let mms=0;
    let contact_number=$("#member_contact_number").val();
    let member_last_name=$("#member_last_name").val();
    let member_first_name=$("#member_first_name").val();
    let member_location=$("#member_location").val();
    let member_email_address=$("#member_email_address").val();
    let member_id_number=$("#member_id_number ").val();
    if(member_first_name.length<3)
    {
        $("#member_msg").append("<ul><li>Invalid first name</li></ul>");
        mms=1;
    }
    if(member_last_name.length<3)
    {
        $("#member_msg").append("<ul><li>Invalid last name</li></ul>");
        mms=1;
    }
    if(contact_number!=="") {
        if ((contact_number.length < 10 && contact_number.length > 11) || !containsOnlyNumbers(contact_number)) {
            $("#member_msg").append("<ul><li>Invalid Phone Number</li></ul>");
            mms = 1;
        }
    }
    if(member_location.length<1)
    {
        $("#member_msg").append("<ul><li>Invalid Location</li></ul>");
        mms=1;
    }
    if(mms===1)
    {

    }
    else
    {
        let obj={
            contact_number:contact_number,
            member_last_name:member_last_name,
            member_first_name:member_first_name,
            member_location:member_location,
            member_email_address:member_email_address,
            member_id_number:member_id_number,
            tick_funeral:tick_funeral,
            identity_number: 2
        };
        //console.log(obj);
        loadMember(obj);

    }
    return false;
}
function decideHere(wch,member_id)
{
    let funeral_id=$("#funeral_idx").val();
    //console.log(funeral_id+"--"+member_id+"-->"+wch);
    $.ajax({
        url:"ajax/process.php",
        type:"POST",
        data:{
            identity_number:15,
            member_id:member_id,
            funeral_id:funeral_id,
            wch:wch
        },
        success:function(data)
        {
            
            UIkit.notification({message: data,pos: 'bottom-right'});
        },
        error:function(jqXHR, exception)
        {
            console.log("There is an error : "+jqXHR.responseText);
        }
    });
}
function selectSearchedMember(member_id,member_name) {
$("#member_id").val(member_id);
    $.ajax({
        url:"ajax/process.php",
        type:"POST",
        data:{
            identity_number:12,
            member_id:member_id,
            funeral_id:$("#funeral_idx").val(),
            pos:$("#orv").val()
        },
        beforeSend: function(){
            $("#spinner").show();
        },
        success:function(data)
        {
            $("#spinner").hide();
            $("#serached_member_infor").html(data);
            $(".simple-pagination").hide();
$("#maintxt").hide();
            $("#search_term_txt").val(member_name);
$("#fuinfo").show();
        },
        error:function(jqXHR, exception)
        {
            console.log("There is an error : "+jqXHR.responseText);
        }
    });

    $("#suggesstion-box-member").hide();
}
function selectSearchedFuneral(funeral_id,member_name) {
    $.ajax({
        url:"ajax/process.php",
        type:"POST",
        data:{
            identity_number:17,
            funeral_id:funeral_id
        },
        beforeSend: function(){
            $("#spinner").show();
        },
        success:function(data)
        {
            $("#spinner").hide();
            $("#detf").html(data);
            $("#search_term_funeral").val(member_name);
        },
        error:function(jqXHR, exception)
        {
            console.log("There is an error : "+jqXHR.responseText);
        }
    });

    $("#suggesstion-box-funeral").hide();
}
function containsOnlyNumbers(str) {
    return /^\d+$/.test(str);
}
function myFOp(funeral_id)
{
    $("#funeral_idx").val(funeral_id);
    $.ajax({
        url:"ajax/process.php",
        type:"POST",
        data:{
            identity_number:14,
            funeral_id:funeral_id
        },
        success:function(data)
        {
            var json = JSON.parse(data);
            for(var key in json)
            {
                var usFormat = json[key]["total_amount"].toLocaleString('en-US');
                var usFormat1 = json[key]["expenses"].toLocaleString('en-US');
                $("#xfuneral").append("<tr id=\"mainx\" style=\"background-color: whitesmoke\"><td>"+json[key]["date_entered"]+"<td><div class=\"uk-margin\">R "+usFormat+"</div></td><td> R"+usFormat1+"</td><td></td></tr>");
            }
        },
        error:function(jqXHR, exception)
        {
            console.log("There is an error : "+jqXHR.responseText);
        }
    });
    $("#clickFunerals").click();
}
function addAmounts()
{
    let funeral_id=$("#funeral_idx").val();
    let paid_amount=$("#paid_amount").val();
    let paid_expenses=$("#paid_expenses").val();
    $.ajax({
        url:"ajax/process.php",
        type:"POST",
        data:{
            identity_number:13,
            funeral_id:funeral_id,
            paid_amount:paid_amount,
            paid_expenses:paid_expenses
        },
        success:function(data)
        {
            $("#ffuneral_msg").html(data);
        },
        error:function(jqXHR, exception)
        {
            console.log("There is an error : "+jqXHR.responseText);
        }
    });
    return false;
}
function runPDF()
{
    $("#prepdf").text("please wait...");    
    $.ajax({
        url:"prepare_download.php",
        type:"POST",
        beforeSend:function () {
            $("#prepdf").text("please wait...");
        },
        data:{

        },
        async:false,
        success:function(data)
        {
            $(".ppr").hide();
            $("#pfd").show();
            console.log(data);
        },
        error:function(jqXHR, exception)
        {
            console.log("There is an error : "+jqXHR.responseText);
        }
    });
}
function preparePdf() {
    $("#prepdf").text("please wait...");
    let total=0;
    $.ajax({
        url:"ajax/process.php",
        type:"POST",
        beforeSend:function () {
            $("#prepdf").text("please wait...");
        },
        data:{
            identity_number:21
        },
        async:false,
        success:function(data)
        {
            total=data;
            console.log(data);
        },
        error:function(jqXHR, exception)
        {
            console.log("There is an error : "+jqXHR.responseText);
        }
    });
    console.log("This is the total -- "+total);
    let runs=total/1000;
    let cel=Math.ceil(runs);
    console.log("Total Rumns -- "+cel);
    for (let i=0;i<cel+1;i++)
    {

        console.log("Run number : "+i);
        $("#ttt").text(i+" / "+cel);
        runPDF();
    }
    $(".ppr").hide();
    $("#pfd").show();


}
function getPrepare() {

}
function deleteMember(member_id,first_name,last_name) {
    let text = "Are you sure that you to delete "+first_name+" "+last_name+"?";
    if (confirm(text) === true) {
        $.ajax({
            url:"ajax/process.php",
            type:"POST",
            beforeSend:function () {

            },
            data:{
                member_id:member_id,
                identity_number:18
            },
            success:function(data)
            {
                $("."+member_id).empty();
                alert(data)
            },
            error:function(jqXHR, exception)
            {
                console.log("There is an error : "+jqXHR.responseText);
            }
        });
    }
}
function openChange(user_id) {
    $("#user_id").val(user_id);
    $("#c_change_pass").click();
}
function action(user_id,status) {
    $.ajax({
        url:"ajax/process.php",
        type:"POST",
        beforeSend:function () {

        },
        data:{
            user_id:user_id,
            status:status,
            identity_number:20
        },
        success:function(data)
        {
            alert(data)
        },
        error:function(jqXHR, exception)
        {
            console.log("There is an error : "+jqXHR.responseText);
        }
    });
}
function changePassword() {
    let user_id=$("#user_id").val();
    let password=$("#c_password").val();
    let confirm_password=$("#c_confirm_password").val();
    $.ajax({
        url:"ajax/process.php",
        type:"POST",
        beforeSend:function () {
        },
        data:{
            user_id:user_id,
            password:password,
            confirm_password:confirm_password,
            identity_number:19
        },
        success:function(data)
        {
            $("#c_msg").html(data);
        },
        error:function(jqXHR, exception)
        {
            console.log("There is an error : "+jqXHR.responseText);
        }
    });
}
const getuser = (user_id) => {
    $.ajax({
        url:"ajax/process.php",
        type:"POST",
        beforeSend:function () {
        },
        data:{
            user_id:user_id,
            identity_number:24
        },
        success:function(data)
        {
            console.log(data);
            let json=JSON.parse(data);
            let first_name=json["first_name"];
            let last_name=json["last_name"];
            let role=json["role"];
            let contact_number=json["contact_number"];
            let location_id=json["location_id"];
            let location_name=json["location_name"];
            $("#edit_first_name").val(first_name);
            $("#edit_last_name").val(last_name);

            $("#edit_contact_number").val(contact_number);
          $("#edit_location").prepend("<option value='"+location_id+"' selected>"+location_name+"</option>");
          $("#edit_role").prepend("<option value='"+role+"' selected>"+role+"</option>");
        },
        error:function(jqXHR, exception)
        {
            console.log("There is an error : "+jqXHR.responseText);
        }
    });
};
const saveUser = () => {
    let user_id=$("#user_id").val();
    let first_name=$("#edit_first_name").val();
    let last_name=$("#edit_last_name").val();
    let role=$("#edit_role").val();
    let contact_number=$("#edit_contact_number").val();
    let location_id=$("#edit_location").val();
    $.ajax({
        url:"ajax/process.php",
        type:"POST",
        beforeSend:function () {
        },
        data:{
            user_id:user_id,
            identity_number:25,
            first_name:first_name,
            last_name:last_name,
            role:role,
            contact_number:contact_number,
            location_id:location_id
        },
        success:function(data)
        {
       alert(data);
        },
        error:function(jqXHR, exception)
        {
            console.log("There is an error : "+jqXHR.responseText);
        }
    });
};
function updateUser(user_id) {
    $("#user_id").val(user_id);
    getuser(user_id);
    $("#c_update_user").click();
}

const addFuneralList = () => {   
   
    let member_id=$("#member_id").val();
    if(member_id!=="")
    {
    let d_o_d=$("#d_o_d").val();
    let search_term_txt=$("#search_term_txt").val();
    let family_member=$("#family_member").val();
    let family_member_phone=$("#family_member_phone").val();
    let state_mem=$("#state_mem").val();
    let obj={member_id:member_id,d_o_d:d_o_d,family_member:family_member,family_member_phone,state_mem:state_mem};
      let ocount =  created_funerals.filter(function(creature) {
                        return creature.member_id === member_id;
                    });
                    if(ocount.length<1)
                    {
                            created_funerals.push(obj);
    console.log(created_funerals);
    console.log(ocount);
    $("#deceased_names").append("<tr><td>"+search_term_txt+"</td></tr>");
    
    $("#d_o_d").val("");
    $("#search_term_txt").val("");
    $("#family_member").val("");
    $("#family_member_phone").val("");
    $("#serached_member_infor").empty();
    $("#fuinfo").hide();
    $("#xbtn").show();
                    }

    }
    
};
const createFuneral = () => {   
    let funeral_type=$("#funeral_type").val();
    let price=$("#price").val();
    let final_payment_date=$("#final_payment_date").val();  
    if(price==="")
    {
        alert("Please enter the price");
    }
    else if(created_funerals.length<1)
    {
        alert("Please enter deceased name(s)");
    }
    else
    {
        let obj={
            funeral_type:funeral_type,
            price:price,
            final_payment_date:final_payment_date,
            deceased:created_funerals,
            identity_number:27
        };
        $.ajax({
        url:"ajax/process.php",
        type:"POST",
        beforeSend:function () {
        },
        data:obj,
        success:function(data)
        {
       console.log(data);
       $("#result").html(data);
        },
        error:function(jqXHR, exception)
        {
            console.log("There is an error : "+jqXHR.responseText);
        }
    });
        
    }
        
};
$(document).on('click','.del',function(e){
    let dep_id=$(this).attr("del");
     let obj={          
            identity_number:29,
            dep_id:dep_id
        };
     
        if(confirm("Are you sure you want to delete this dependent?"))
        {
    $.ajax({
        url:"ajax/process.php",
        type:"POST",
        beforeSend:function () {
        },
        data:obj,
        success:function(data)
        {
       if(data.indexOf("success")>-1)
       {
        $("#"+dep_id).remove();
        alert("Dependent successfully removed");
       }
       else{
        alert("Failed");
       }
        },
        error:function(jqXHR, exception)
        {
            console.log("There is an error : "+jqXHR.responseText);
        }
    });
}
})
