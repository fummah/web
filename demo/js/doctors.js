doctor_details=[];
function hid()
{
    var nna="No";
    var radios = document.getElementsByName('discount');
    for (var i = 0, length = radios.length; i < length; i++) {
        if (radios[i].checked) {
            // do whatever you want with the checked radio
            nna=(radios[i].value);

            // only one radio can be logically checked, don't check the rest
            break;
        }
    }

    if(nna=="Yes")
    {
        document.getElementById('hhid').style.display='block';

    }
    else
    {
        document.getElementById('hhid').style.display='none';
    }

}
function hi12()
{

    var nna="R";
    var radios = document.getElementsByName('discount_v');
    for (var i = 0, length = radios.length; i < length; i++) {
        if (radios[i].checked) {
            // do whatever you want with the checked radio
            nna = (radios[i].value);

            // only one radio can be logically checked, don't check the rest
            break;
        }
    }

    if(nna=="P")
    {
        document.getElementById('discount_perc').style.display='block';
        document.getElementById('discount_value').style.display='none';

    }
    else
    {
        document.getElementById('discount_value').style.display='block';
        document.getElementById('discount_perc').style.display='none';

    }

}
function show123()
{
    //alert("Alert");


    if(document.getElementById('signed').checked==true)
    {
        document.getElementById('mmd').style.display='block';
    }
    else
    {
        document.getElementById('mmd').style.display='none';
    }

}

function add()
{
    var element = {};
    var vl=$("#discount_perc").val();
    var vl1=$("#discount_value").val();
    var days_number=$("#days_number").val();
    var nna="R";
    var mesg="";
    var radios = document.getElementsByName('discount_v');
    for (var i = 0, length = radios.length; i < length; i++) {
        if (radios[i].checked) {
            // do whatever you want with the checked radio
            nna = (radios[i].value);

            // only one radio can be logically checked, don't check the rest
            break;
        }
    }

    element.main_value =nna;
    element.discount_perc = vl;
    element.discount_value = vl1;
    element.days_number = days_number;

    var count=0;
    for (var key in doctor_details) {
        if(days_number==doctor_details[key].days_number)
        {
            count=1;
        }
    }
    if(nna=="P")
    {
        if(count<1 && vl.length>0)
        {
            doctor_details.push(element);
            mesg="<br><span uk-icon=\"check\"></span> "+vl+"% discount if the claim is "+days_number+" days";
        }


    }
    else
    {
        if(count<1 && vl1.length>0)
        {
            doctor_details.push(element);
            mesg="<br><span uk-icon=\"check\"></span> R "+vl1+" discount if the claim is "+days_number+" days";
        }

    }

    var json = JSON.stringify(doctor_details);
    $("#dr_value").val(json);
    $("#txt").append(mesg);
}

function del(id) {
    var obj={

        del_id:id
    };
    $.ajax({
        url:"ajax/deleting.php?identity=33",
        type:"GET",
        data:obj,
        success:function(data){
            if(data==1)
            {
                $("#x"+id).css("color","red")
            }
            else
            {
                alert("Failed to delete");
            }

        },
        error:function(jqXHR, exception)
        {
            alert(jqXHR.responseText);
        }
    });

}

function addNote(author) {

    var notes= $("#mynotes").val();
    var mytime=currentDate();
    var doc_id=$("#doctor_id").val();


    if(notes!="") {

        var obj={

            doc_id:doc_id,
            author:author,
            notes:notes
        };
        $.ajax({
            url:"ajax/deleting.php?identity=36",
            type:"GET",
            data:obj,
            success:function(data){
                if(data==1)
                {
                    $("#artsec").append("<article class=\"uk-comment\"><header class=\"uk-comment-header\"><div class=\"uk-grid-medium uk-flex-middle\" uk-grid><div class=\"uk-width-expand\">" +
                        "<ul class=\"uk-comment-meta uk-subnav uk-subnav-divider uk-margin-remove-top\">" +
                        "<li><a href=\"#\" id=\"mytime\">" + mytime + "</a></li><li><a href=\"#\" id=\"mytime\">" + author + "</a></li></ul></div></div></header><div class=\"uk-comment-body\"><p>" + notes + "</p></div></article><hr>");

                    $("#mynotes").val("");
                }
                else
                {
                    alert("Failed to update");
                }

            },
            error:function(jqXHR, exception)
            {
                alert(jqXHR.responseText);
            }
        });


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