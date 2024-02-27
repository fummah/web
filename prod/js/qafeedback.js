const getfeedbackmenu = (status) => {
    let dates=$("#dates").val();
    const obj={
        dates:dates,
        status:status,
        identity_number:40
    };
    console.log(obj);
    $.ajax({
        url: "ajax/claims.php",
        type: "POST",
        data: obj,
        beforeSend:function (xhr){
            $("#menu1").text("please wait...");
        },
        success: function (data) {
$("#menu1").html(data);
        },
        error: function (jqXHR, exception) {
            console.log("Error here");
        }
    });
};
const pushButtons = (btn) => {
    let dates=$("#dates").val();
    let username=$("#dusername").val();
    const obj={
        dates:dates,
        username:username,
        button:btn,
        identity_number:43
    };
    console.log(obj);
    $.ajax({
        url: "ajax/claims.php",
        type: "POST",
        data: obj,
        beforeSend:function (xhr){
            $("#wait").text("please wait...");
        },
        success: function (data) {
            if(data==="1")
            {
                $("#wait").text("Record Successfully Updated");
            }
            else {
                $("#wait").text("Failed to update"+data);
            }
        },
        error: function (jqXHR, exception) {
            console.log("Error here");
        }
    });
};
const updateInfor = (id,action_plan,comment) => {
    const obj = {
        id: id,
        action_plan: action_plan,
        comment: comment,
        identity_number: 42
    };
    console.log(obj);
    $.ajax({
        url: "ajax/claims.php",
        type: "POST",
        data: obj,
        beforeSend: function (xhr) {

        },
        success: function (data) {
            console.log("---"+data);
            if (data === "1") {
                UIkit.notification({message: "Record successfully updated"});
            } else {
                UIkit.notification({message: "Failed to update"});
            }

        },
        error: function (jqXHR, exception) {
            console.log("Error here");
        }
    });
};
const moveToCompleted = (claim_id) => {
    const obj = {
        claim_id: claim_id,
        identity_number: 44
    };
    console.log(obj);
    $.ajax({
        url: "ajax/claims.php",
        type: "POST",
        data: obj,
        beforeSend: function (xhr) {

        },
        success: function (data) {
            if (data === "1") {
                UIkit.notification({message: "Record successfully updated"});
            } else {
                UIkit.notification({message: "Failed to update"});
            }

        },
        error: function (jqXHR, exception) {
            console.log("Error here");
        }
    });
};
const viewDetails = (username) => {
    $("#wait").text("");
    let dates=$("#dates").val();
    let status=$('input[name="status"]:checked').val();
    const obj={
        dates:dates,
        username:username,
        identity_number:41
    };
    console.log(obj);
    $.ajax({
        url: "ajax/claims.php",
        type: "POST",
        data: obj,
        beforeSend:function (xhr){
            $("#dda").html("<tr><td colspan='5'>please wait...</td></tr>");
        },
        success: function (data) {
$("#dda").html(data);

    $("#bbotm").show();
    if (data.indexOf("first controller activex")>-1)
    {
        $("#resultstxt").text("Waiting for the CS");
        $("#close_qa").hide();
        $("#controller_action").hide();
        console.log("1x");
    }
    else {
        console.log("2x");
        $("#resultstxt").text("")
    }
    if (data.indexOf("cs activex")>-1)
    {
        $("#resultstxt").text("");
        $("#close_qa").show();
        $("#controller_action").hide();
    }
            if (data.indexOf("initial controller activex")>-1)
            {
                $("#controller_action").show();
                $("#close_qa").hide();
            }
    if (data.indexOf("closed activex")>-1)
    {
        $("#bbotm").hide();

    }



        },
        error: function (jqXHR, exception) {
            console.log("Error here");
        }
    });
};
$(document).ready(function () {
    let status=$('input[name="status"]:checked').val();
    getfeedbackmenu(status);
    const clickfirst = function() {
        $(".users:first").click();
    }
    setTimeout(clickfirst, 1000);
});
$(document).on('change','input[name="status"]',function() {
    $("#bbotm").hide();
    $("#wait").text("");
    let status=$('input[name="status"]:checked').val();
    console.log(status);
    getfeedbackmenu(status);
    $("#dda").html("<tr><td colspan='5'>No User Selected</td></tr>");
    const clickfirst = function() {
        $(".users:first").click();
    }
    setTimeout(clickfirst, 1000);
});
$(document).on('click','.users',function() {
    $(".users").removeClass("userclass");
    $(this).addClass("userclass");
  let user=$(this).attr("data");
  $("#dusername").val(user);
    viewDetails(user);

});
$(document).on('click','.addnotes',function() {
  let id=$(this).attr("data");
  let action_plan=$("#action_plan"+id).val();
  let comment=$("#comment"+id).val();
    updateInfor(id,action_plan,comment);
    $(this).removeClass("uk-button-primary");
    $(this).addClass("uk-button-danger");

});
$(document).on('click','.mybtns',function() {
  let btnname=$(this).attr("data");
    pushButtons(btnname);
});
$(document).on('click','.move_to_completed',function() {
    let claim_id=$(this).attr("data");
    $("#"+claim_id).hide();
    $(this).text("Updated")
    $(this).css("background-color","yellow !important")
    moveToCompleted(claim_id);
});