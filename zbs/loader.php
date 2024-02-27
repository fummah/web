<?php
session_start();
define("access",true);
$page_name="Loader";

include ("classes/DBConnect.php");
$db=new DBConnect();
/*
if(!in_array($db->myRole(),$db->topRoles()))
{    
        header("Location: logout.php");
        die();   

}
*/
require_once ("header.php");
 if(!$db->isOpenFuneral())
    {
        die("No open funeral");
    }
?>
<style>

.et_pb_texta{
    font-family: 'Montserrat',Helvetica,Arial,Lucida,sans-serif;
    font-weight: 300;
    line-height: 1.6em;
    font-size: 20px;

}
.et_pb_textr{
    font-family: 'Montserrat',Helvetica,Arial,Lucida,sans-serif;
    font-weight: 300;
    line-height: 1.6em;
    font-size: 14px;

}
table{
    font-family: 'Montserrat',Helvetica,Arial,Lucida,sans-serif;
    font-weight: 300;
    line-height: 1.6em;

}
@media only screen and (max-width: 949px) {
    .et_pb_texta{
        padding-left: 20px !important;
    }
    table{
        width: 100% !important;
    }
    .uk-card-body {
        padding: 10px 10px !important;
    }
    #main1{
        padding-left: 10px !important;
    }
    .uk-icon-button{
        width: 25px !important;
        height: 25px !important;
    }

}
@media only screen and (max-width: 949px) {
    .uk-search-large .uk-search-input{
        font-size: 1.625rem;
    }
    .f{
        color: #0b0b0b;
        border: 1px solid whitesmoke !important;
    }
    .f1{
        border: 1px solid whitesmoke !important;
    }
    .f2{
        border: 1px solid whitesmoke !important;
    }
    .et_pb_textb {
        font-family: 'Montserrat', Helvetica, Arial, Lucida, sans-serif;
        font-weight: 600;
        font-size: 30px !important;
    }
    #page-container,.et-animated-content {
        padding-top: 35px !important;
    }
    .not_mobile {
        display:none !important;
    }
    .isCenter{
        text-align: center !important;
    }
    #last_funeral_name{
        color: #0b8278 !important;
    }
    .mobileLine{
        border-bottom: 1px solid cadetblue;
    }
    #serached_member_infor tr:nth-child(odd){
        background-color: gold !important;
    }
    .uk-checkbox:checked{
        background-color: limegreen !important;
    }
}
.maintxt{
    font-family: 'Montserrat',Helvetica,Arial,Lucida,sans-serif !important;
    font-weight: 300 !important;
    line-height: 1.6em !important;
    color: green;
}


.uk-badge{
    background-color: #54bf99 !important;
    border: 1px solid white !important;
    font-family: 'Montserrat',Helvetica,Arial,Lucida,sans-serif !important;
    font-weight: 300 !important;
    font-size: 14px !important;
}

.rowdetails{
    border-bottom: 1px solid whitesmoke;
    padding-bottom: 15px !important;
}
.boldd{
    font-weight: 400;
}
@media only screen and (min-width: 480px) {
    .f{
        color: #0b0b0b;
        border: 1px solid indianred !important;
    }
    .f1{
        border: 1px solid whitesmoke !important;
    }
    .f2{
        border: 1px solid goldenrod !important;
    }
    .mycheck {
        display: block;
        position: relative;
        padding-left: 35px;
        margin-bottom: 12px;
        cursor: pointer;
        font-size: 22px;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    /* Hide the browser's default checkbox */
    .mycheck input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
        height: 0;
        width: 0;
    }

    /* Create a custom checkbox */
    .checkmark {
        position: absolute;
        top: 0;
        left: 0;
        height: 25px;
        width: 25px;
        background-color: #eee;
    }

    /* On mouse-over, add a grey background color */
    .mycheck:hover input ~ .checkmark {
        background-color: #ccc;
    }

    /* When the checkbox is checked, add a blue background */
    .mycheck input:checked ~ .checkmark {
        background-color: #2196F3;
    }

    /* Create the checkmark/indicator (hidden when not checked) */
    .checkmark:after {
        content: "";
        position: absolute;
        display: none;
    }

    /* Show the checkmark when checked */
    .mycheck input:checked ~ .checkmark:after {
        display: block;
    }

    /* Style the checkmark/indicator */
    .mycheck .checkmark:after {
        left: 9px;
        top: 5px;
        width: 5px;
        height: 10px;
        border: solid white;
        border-width: 0 3px 3px 0;
        -webkit-transform: rotate(45deg);
        -ms-transform: rotate(45deg);
        transform: rotate(45deg);
    }
    .homeck{
        background-color: #cf4522 !important;
    }
    .marktick{
        background-color: green !important;
    }
}
.boldd{
    font-weight: bolder;
}

</style>

<div class="et_pb_code_inner " style="padding: 1px !important; border: 1px solid #cf4522 !important;border-radius: 10px; width: 98%; position: relative; margin-left: auto !important; margin-right: auto !important;">
   <h3 align="center">Loader</h3>
    <div class="">
       <div class="col-md-12">
	    <div class="uk-margin">
            <textarea class="uk-textarea" id="area" rows="5" onpaste="myPaste()" placeholder="Paste here" aria-label="Textarea"></textarea>
        </div>
	   </div>
	    <p align="center" id="wait" style="color:red !important; display:none">Processing, please wait ...</button></p>
	    <p align="center"><button class="uk-button uk-button-secondary" onclick="myFunction()">Paste</button> <button class="uk-button uk-button-primary" onclick="loader()">Load</button></p>
		<div class="uk-child-width-1-2@s" uk-grid>
    <div>
        <div class="uk-card uk-card-default uk-card-small uk-card-body" style="background-color:#20c5b03b !important">
            <h3 class="uk-card-title">Success <span class="uk-badge succ"></span></h3>
           <div id="success">
		   </div>
        </div>
    </div>
    <div>
        <div class="uk-card uk-card-default uk-card-large uk-card-body" style="background-color:#e5837d3b !important">
            <h3 class="uk-card-title">Failed <span class="uk-badge fail"></span></h3>
   <div id="failed">
		   </div>
        </div>
    </div>
	   <div>
        <div class="uk-card uk-card-default uk-card-large uk-card-body" style="background-color:#f4efef !important">
            <h3 class="uk-card-title">Duplicates <span class="uk-badge dupl"></span></h3>
   <div id="duplicates">
		   </div>
        </div>
    </div>
</div>
</div>

</div>
<script>
function loader() {
	let aar=[];
var lines = $('#area').val().split('\n');
console.log(lines);
for(var i = 0;i < lines.length;i++){
	let cname = lines[i].replace(/\s+/g,' ').trim();
	let fullname=cname.split(' ');
	let len=fullname.length;
	let first_name="";
	let last_name="";
	console.log(len);
	if(len===2)
	{
		first_name=fullname[0];
	    last_name=fullname[1];
	}
	else if(len>2)
	{
		first_name=fullname[0]+" "+fullname[1];
	    last_name=fullname[2];
	}
	if(first_name!=="")
	{
		let nax={
			first_name:first_name,
			last_name:last_name
		};
		aar.push(nax);
	}
	   
}
let successxt="";
let failedxt="";
let duplicatesxt="";
$(".succ").text("");
$(".fail").text("");
$(".dupl").text("");
let obj={
			identity_number:28,
			members:aar
		};
		$.ajax({
        url:"ajax/process.php",
        type:"POST",
        beforeSend:function () {
			$("#wait").show();
        },
        data:obj,
        success:function(data)
        {
			
			const json=JSON.parse(data);
			const success=json["success"];
			const failed=json["failed"];
			const duplicates=json["duplicates"];
            $(".succ").text(success.length);
$(".fail").text(failed.length);
$(".dupl").text(duplicates.length);

			for(key in success)
			{
				let fname=success[key]["first_name"];
				let lname=success[key]["last_name"];
				successxt+="<p><span class='uk-margin-small-right' style='color:green' uk-icon='check'></span> "+fname+" "+lname+"</p>";
				
			}
				for(key in failed)
			{
				let fname=failed[key]["first_name"];
				let lname=failed[key]["last_name"];
				failedxt+="<p><span class='uk-margin-small-right' style='color:red' uk-icon='close'></span> "+fname+" "+lname+"</p>";
				
			}		
			for(key in duplicates)
			{
				let fname=duplicates[key]["first_name"];
				let lname=duplicates[key]["last_name"];
				duplicatesxt+="<p><span class='uk-margin-small-right' style='color:grey' uk-icon='minus-circle'></span> "+fname+" "+lname+"</p>";
				
			}
			$("#success").html(successxt);
			$("#failed").html(failedxt);
			$("#duplicates").html(duplicatesxt);
       $("#wait").hide();
	   
        },
        error:function(jqXHR, exception)
        {
            console.log("There is an error : "+jqXHR.responseText);
        }
    });
//console.log(aar);
}

const myPaste = () =>{
	$("#success").empty();
			$("#failed").empty();
			$("#duplicates").empty();
}

function myFunction() {
   $("#area").empty();
   navigator.clipboard.readText()
.then(text => {
document.getElementById("area").innerHTML = text;

})
.catch(err => {
document.getElementById("area").innerHTML = 'Failed to read clipboard contents: '+err;
});
   
    
}
</script>
    <script type="text/javascript" id="divi-custom-script-js-extra">
        /* <![CDATA[ */
        var DIVI = {"item_count":"%d Item","items_count":"%d Items"};
        var et_shortcodes_strings = {"previous":"Previous","next":"Next"};
        var et_pb_custom = {
            "ajaxurl":"",
            "images_uri":"",
            "builder_images_uri":"",
            "et_frontend_nonce":"f5e56487fb","subscription_failed":"Please, check the fields below to make sure you entered the correct information.",
            "et_ab_log_nonce":"a297160c78","fill_message":"Please, fill in the following fields:",
            "contact_error_message":"Please, fix the following errors:","invalid":"Invalid email","captcha":"Captcha",
            "prev":"Prev","previous":"Previous","next":"Next","wrong_captcha":"You entered the wrong number in captcha.",
            "wrong_checkbox":"Checkbox","ignore_waypoints":"no","is_divi_theme_used":"1","widget_search_selector":".widget_search",
            "ab_tests":[],"is_ab_testing_active":"","page_id":"994","unique_test_id":"","ab_bounce_rate":"5","is_cache_plugin_active":"yes",
            "is_shortcode_tracking":"","tinymce_uri":""};
        var et_builder_utils_params = {"condition":{"diviTheme":true,"extraTheme":false},
            "scrollLocations":["app","top"],"builderScrollLocations":{"desktop":"app","tablet":"app","phone":"app"},"onloadScrollLocation":"app",
            "builderType":"fe"};
        var et_frontend_scripts = {"builderCssContainerPrefix":"#et-boc","builderCssLayoutPrefix":"#et-boc .et-l"};
        var et_pb_box_shadow_elements = [];
        var et_pb_motion_elements = {"desktop":[],"tablet":[],"phone":[]};
        var et_pb_sticky_elements = [];
        /* ]]> */
    </script>
    <script type="text/javascript" src="js/custom.unified.js?ver=4.7.6" id="divi-custom-script-js"></script>

    <!-- This is the modal -->

</div>
</body></html>

<!-- Add Member -->

