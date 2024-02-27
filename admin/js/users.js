$(document).ready(function () {
    $('#myBtn').click(function () {
        var client_name=$("#client_name").val();
        var name=$("#name").val();
        var surname=$("#surname").val();
        var email=$("#email").val();
        if(name=='' || surname=='' || email=='')
        {
            $("#details").text("Please Complete blanks");
        }


        else {
            $('#load').show();
            $.ajax({
                url: "../admin/add_users.php",
                type: "POST",
                data: {
                    name: $("#name").val(),
                    surname: $("#surname").val(),
                    email: $("#email").val(),
                    phone: $("#phone").val(),
                    role: $("#role").val(),
                    client_name: $("#client_name").val()
                },
                success: function (data) {
                    $('#load').hide();
                    $('#details').html(data);
                    if(data.indexOf("New User successfully added.")>-1)
                    {
                        $("#details").css("color","gray");
                    }
                    else
                    {
                        $("#details").css("color","red");
                    }
                },
                error: function (jqXHR, exception) {

                    $("#details").text("An Error occured");
                }
            });
        }
    });

    $('#myClear').click(function () {
        $("#name").val('');
        $("#surname").val('');
        $("#password").val('');
        $("#email").val('');
        $("#phone").val('');
        $("#passwordC").val('');
    });
});


function  action(id,tt) {
    var pid='';
    if(tt==0)
    {
        pid="44";

    }

    var display1 = id + "x";
    document.getElementById(display1).style.display = "block";
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {

            var display2 = id + "y";
            var mess = this.responseText;
            if (mess == "Updated") {
                document.getElementById(display1).innerHTML = "Deactivated";
                document.getElementById(display2).style.backgroundColor = "pink";
                document.getElementById(display1).style.color = "Green";
                if(pid=="44")
                {
                    document.getElementById(display1).innerHTML = "Activated";
                    document.getElementById(display2).style.backgroundColor = "green";
                    document.getElementById(display1).style.color = "Blue";
                }


                document.getElementById(id).style.display = "none";
            }
            else {
                document.getElementById(display1).innerHTML = this.responseText;
            }

        }
    };
    xhttp.open("GET", "../ajaxPhp/deleting.php?id=" + id + "&identity=3&pid="+pid, true);
    xhttp.send();
}

function  action1(id,tt) {
    var pid='';
    if(tt==0)
    {
        pid="44";

    }

    var display1 = id + "x";
    document.getElementById(display1).style.display = "block";
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {

            var display2 = id + "y";
            var mess = this.responseText;
            if (mess.indexOf("Updated")>-1) {
                document.getElementById(display1).innerHTML = "Deactivated";
                document.getElementById(display2).style.backgroundColor = "pink";
                document.getElementById(display1).style.color = "Green";
                if(pid.indexOf("44")>-1)
                {
                    document.getElementById(display1).innerHTML = "Activated";
                    document.getElementById(display2).style.backgroundColor = "green";
                    document.getElementById(display1).style.color = "Blue";
                }


                document.getElementById(id).style.display = "none";
            }
            else {
                document.getElementById(display1).innerHTML = this.responseText;
            }

        }
    };
    xhttp.open("GET", "../ajaxPhp/deleting.php?id=" + id + "&identity=6&pid="+pid, true);
    xhttp.send();
}

function pass(nname)
{
    //alert(nname);
    $('#myName').text(nname);
    $('#xsx').modal('show');
    $('#pp').val(nname);
}
function submitPass() {
    document.getElementById("modShow").style.display = "block";
    var id=document.getElementById("pp").value;
    var pass=document.getElementById("password").value;
    var cpass=document.getElementById("passwordC").value;
    if(pass.length<7 || pass!=cpass)
    {
        document.getElementById("modShow").innerHTML = "Invalid Password";
        document.getElementById("modShow").style.color = "Red";
    }
    else {
        var myObj={
            id:id,
            identity:4,
            pass:pass
        };
        $.ajax({
            url:'../ajaxPhp/pas2.php',
            type:'POST',
            data:myObj,
            success:function (data) {
                document.getElementById("modShow").style.color = "grey";

                $('#modShow').html(data);

            },
            error:function(jqXHR, exception)
            {
                alert("Error : "+jqXHR);
            }
        });


    }
}

function reset() {
    document.getElementById("modShow").style.display = "none";
    document.getElementById("password").value="";
    document.getElementById("passwordC").value="";
}

$(document).ready(function () {
    $('#duplicates').click(function () {

        $('#users').hide('slide');
        $('#dup').show('slide');
    });
    $('#close1').click(function () {

        $('#users').show('slide');
        $('#dup').hide('slide');
    });

    $('#saveChanges').click(function () {
        $('#load').show();
        var email=document.getElementById("email").value;
        var password=document.getElementById("password").value;
        var cc=document.getElementById("cc").value;
        var folder=document.getElementById("folder").value;
        var smtp=document.getElementById("smtp").value;
        var imap=document.getElementById("imap").value;
        var notemail=document.getElementById("notemail").value;
        var notpass=document.getElementById("notpass").value;
        var myObj={
            email:email,
            password:password,
            cc:cc,
            folder:folder,
            smtp:smtp,
            notemail:notemail,
            notpass:notpass,
            imap:imap,
            identity:5
        };
        $.ajax({
            url:'../ajaxPhp/deleting.php',
            type:'GET',
            data:myObj,
            success:function (data) {
                $('#load').hide();
                $('#details').html(data);
                if(data.indexOf("Updated")>-1)
                {
                    $('#details').css("color","blue");
                }
                else {
                    $('#details').css("color","red");
                }

            },
            error:function(jqXHR, exception)
            {
                $('#details').html(jqXHR);
                $('#details').css("color","red")
            }
        });

    });
});                        