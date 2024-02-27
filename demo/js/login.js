$(document).ready(function(){

$("#login").click(function(){
	$("#login_info").show();
	$("#login_info").text("Please wait...");
var user=$("#username").val();
var pass=$("#password").val();
$.ajax({
	url:"validatelogin.php",
	type:"POST",
	data:{
		valid:99,
username:user,
password:pass
	},
	success:function(data)
	{
var validate=data;
if(validate=="Success")
{
	//$("#im").html();
    $("#login_info").css("color","#54bc9c");
    $("#login_info").html("<i class=\"fa fa-check\" aria-hidden=\"true\"></i> loading...");
	location.href = "index.php"
}
else if(data.indexOf("expired")>-1)
		{
			$("#login_info").html("<i class=\"fa fa-close\" aria-hidden=\"true\"></i> "+validate);
		}
else
{
$("#login_info").html("<i class=\"fa fa-close\" aria-hidden=\"true\"></i> "+validate);

}
	},
	error:function(jqXHR, exception)
                {
                     $("#login_info").html("There is an error : "+jqXHR.responseText);
                }
	
});
});
});