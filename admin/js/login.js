$(document).ready(function(){

$("#login").click(function(){
	$("#im").show();
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
    $("#im").text("loading...");
	location.href = "index.php"
}
else
{
	$("#show").show();
$("#details").html(validate);
$("#im").hide();
}
	},
	error:function(jqXHR, exception)
                {
                	$("#show").show();
                	$("#im").hide();
                     $("#details").html("There is an error : "+jqXHR.responseText);
                }
	
});
});
});