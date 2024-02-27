let months_arr=[];
let values_arr=[];


// start
const addUsername = () =>{
	let email=$("#email").val();
	$("#username").val(email);
};
const confirmPassword = () =>{
	let password=$("#password").val();
	let password_confirm=$("#confirm_password").val();	
	if(password===password_confirm)
	{	
		return true;
	}
	else
	{
		alert("Password does not match");	
		return false;
	}
};
function groupBy(objectArray, property) {
  return objectArray.reduce(function (acc, obj) {
    var key = obj[property];
    if (!acc[key]) {
      acc[key] = [];
    }
    acc[key].push(obj);
    return acc;
  }, {});
}

//click vote
$(document).on('click','.vote',function() {
    let vote=$(this).val();
    let legislation_id=$(this).attr('name');
	const myArray = legislation_id.split("_");
	legislation_id=myArray[1];
	let leg_name=$("#n"+legislation_id).text();
	$(".wait").empty();	
	$("#confirm").text(vote);
	$("#modalbody").text(leg_name);
	
	$("#legislation_id").val(legislation_id);
	$("#openvote").click();
	 $(".confirm").show();
	$(".cancel").text("Cancel");
});
//click Vote 2
$(document).on('click','.vote2',function() {
    let vote=$(this).val();
    let legislation_id=$("#legislation_id").val();
	let option="Are you sure you want to vote "+vote+"?";
    $("#confirm").text(vote);
    if(confirm(option))
    {
        voteNow();
		$("input[name=l_"+legislation_id+"][value=" + vote + "]").prop('checked', 'checked');
    }
});
//click Vote 3
$(document).on('click','.vote3',function() {
    let vote=$(this).val();
    let legislation_id=$("#legislation_id").val();
	let option="Are you sure you want to vote "+vote+"?";
    $("#confirm").text(vote);
    if(confirm(option))
    {
        voteElectionNow();
		$("input[name=e_"+legislation_id+"][value=" + vote + "]").prop('checked', 'checked');
    }
});
//click Vote 4
$(document).on('click','.vote4',function() {
    let vote=$(this).val();
    let legislation_id=$("#legislation_id").val();
	let option="Are you sure you want to vote "+vote+"?";
    $("#confirm").text(vote);
    if(confirm(option))
    {
        voteTopicNow();
		$("input[name=t_"+legislation_id+"][value=" + vote + "]").prop('checked', 'checked');
    }
});
//vote now
const voteNow = () => {
    let legislation_id=$("#legislation_id").val();
    let confirmval=$("#confirm").text(); 
let token=$('input[name="_token"]').val();	

    const obj={
        legislation_id:legislation_id,
        vote:confirmval,
_token:token		
    };
    console.log(obj);
    $.ajax({
        url: "/vote",
        type: "POST",
        data: obj,
        beforeSend:function (xhr){
            $(".wait").text("please wait...");
        },
        success: function (data) {
         console.log(data);
		 if(data==="1")
		 {
			 $(".confirm").hide();
			 $(".cancel").text("Close Window");
			$(".wait").html(" <div class=\"alert alert-success alert-dismissible fade show\" role=\"alert\"><p class=\"text-white mb-0\">Your vote has been successfully casted</p></div>"); 
		 }
		 else{
			 $(".wait").html(" <div class=\"alert alert-danger alert-dismissible fade show\" role=\"alert\"><p class=\"text-white mb-0\">Failed</p></div>"); 
	
		 }
        },
        error: function (jqXHR, exception) {
			const err=JSON.parse(jqXHR.responseText);
			let errmsg="There is an error : "+err["message"];
            console.log("There is an error : "+jqXHR.responseText);
			$(".wait").html(" <div class=\"alert alert-danger alert-dismissible fade show\" role=\"alert\"><p class=\"text-white mb-0\">"+errmsg+" </p></div>"); 
        }
    });
};

const voteElectionNow = () => {
    let election_id=$("#legislation_id").val();
	console.log(election_id);
    let confirmval=$("#confirm").text(); 
let token=$('input[name="_token"]').val();	

    const obj={
        election_id:election_id,
        vote:confirmval,
_token:token		
    };
    console.log(obj);
    $.ajax({
        url: "/vote_election",
        type: "POST",
        data: obj,
        beforeSend:function (xhr){
            $(".wait").text("please wait...");
        },
        success: function (data) {
         console.log(data);
		 if(data==="1")
		 {
			 $(".confirm").hide();
			 $(".cancel").text("Close Window");
			$(".wait").html(" <div class=\"alert alert-success alert-dismissible fade show\" role=\"alert\"><p class=\"text-white mb-0\">Your vote has been successfully casted</p></div>"); 
		 }
		 else{
			 $(".wait").html(" <div class=\"alert alert-danger alert-dismissible fade show\" role=\"alert\"><p class=\"text-white mb-0\">Failed</p></div>"); 
	
		 }
        },
        error: function (jqXHR, exception) {
			const err=JSON.parse(jqXHR.responseText);
			let errmsg="There is an error : "+err["message"];
            console.log("There is an error : "+jqXHR.responseText);
			$(".wait").html(" <div class=\"alert alert-danger alert-dismissible fade show\" role=\"alert\"><p class=\"text-white mb-0\">"+errmsg+" </p></div>"); 
        }
    });
};

const voteTopicNow = () => {
    let topic_id=$("#legislation_id").val();
	console.log(topic_id);
    let confirmval=$("#confirm").text(); 
let token=$('input[name="_token"]').val();	

    const obj={
        topic_id:topic_id,
        vote:confirmval,
_token:token		
    };
    console.log(obj);
    $.ajax({
        url: "/vote_topic",
        type: "POST",
        data: obj,
        beforeSend:function (xhr){
            $(".wait").text("please wait...");
        },
        success: function (data) {
         console.log(data);
		 if(data==="1")
		 {
			 $(".confirm").hide();
			 $(".cancel").text("Close Window");
			$(".wait").html(" <div class=\"alert alert-success alert-dismissible fade show\" role=\"alert\"><p class=\"text-white mb-0\">Your vote has been successfully casted</p></div>"); 
		 }
		 else{
			 $(".wait").html(" <div class=\"alert alert-danger alert-dismissible fade show\" role=\"alert\"><p class=\"text-white mb-0\">Failed</p></div>"); 
	
		 }
        },
        error: function (jqXHR, exception) {
			const err=JSON.parse(jqXHR.responseText);
			let errmsg="There is an error : "+err["message"];
            console.log("There is an error : "+jqXHR.responseText);
			$(".wait").html(" <div class=\"alert alert-danger alert-dismissible fade show\" role=\"alert\"><p class=\"text-white mb-0\">"+errmsg+" </p></div>"); 
        }
    });
};


$(document).on('click','.whovote',function() { 
$("#det").empty(); 
    let id=$(this).attr('data');
    let vote=$(this).attr('title');
	let page=$("#page").val();
	let token=$('input[name="_token"]').val();
	$("#w").text(vote);
const obj = {
	id:id,
	vote:vote,
	page:page,
	_token:token
};
console.log(obj);
    $.ajax({
        url: "/whovote",
        type: "POST",
        data: obj,
        beforeSend:function (xhr){
      
        },
        success: function (data) {
			var groupedPeople = groupBy(data, 'state');
       let dd="";
		 for(keyx in groupedPeople)		 
{
	let hdata=groupedPeople[keyx];
	let cc=hdata.length;
	dd+="<h5 class='text-danger'>State : "+keyx+" <span class='uk-badge'>"+cc+"</span></h5>";
	 dd+="<table class='table align-items-center mb-0 uk-table-responsive uk-table-divider'>";
		 dd+="<thead><tr><th>Full Name</th><th>Date Entered</th></tr><thead>";
		 dd+="<tbody>";
for(key in hdata)		 
{
	let name = hdata[key]['firstname']+" "+hdata[key]['lastname'];
	let date_entered = hdata[key]['date_entered'];
	 dd+="<tr><td>"+name+"</td><td>"+date_entered+"</td></tr>";
}
dd+="</tbody></table>";
	
}
		 
		
$("#det").append(dd);
        },
        error: function (jqXHR, exception) {
			const err=JSON.parse(jqXHR.responseText);
			console.log(err);
        }
    });
	$("#whovoted").click();
});
//view full legislation
$(document).on('click','.ln',function() {  
$(".wait").empty();
    let legislation_name=$(this).attr('data-name');
    let legislation_descr=$(this).attr('title');
    let legislation_trans=$(this).attr('data-trans');
    let legislation_id=$(this).attr('id');
    legislation_id = legislation_id.replace("n", "");
    var vote = document.querySelector("input[name='l_"+legislation_id+"']:checked")?.value || 'x';  
    $("input[name=l_mast][value=" + vote + "]").prop('checked', 'checked');
	$("#legislation_name_m").text(legislation_name);
	$("#legislation_description_m").text(legislation_descr);
    $("#legislation_description_t").text(legislation_trans);
    $("#legislation_id").val(legislation_id);
	$("#openlegi").click();
});
$(document).on('click','.jx',function() {     
$(".wait").empty();
	let election_name=$(this).attr('data-name');
    let election_descr=$(this).attr('title');
    let election_trans=$(this).attr('data-trans');
    let legislation_id=$(this).attr('id');
    legislation_id = legislation_id.replace("n", "");
    var vote = document.querySelector("input[name='e_"+legislation_id+"']:checked")?.value || 'x';  	
    $("input[name=e_mast][value=" + vote + "]").prop('checked', 'checked');
	$("#election_name_m").text(election_name);
	$("#election_description_m").text(election_descr);
    $("#election_description_t").text(election_trans);
    $("#legislation_id").val(legislation_id);	
	$("#openelect").click();
});
$(document).on('click','.topi',function() {  
$(".wait").empty();
//$('input[name=t_mast]').val('checked',false);
    let topic_name=$(this).attr('data-name');
	console.log(topic_name);
    let topic_descr=$(this).attr('title');
    let topic_trans=$(this).attr('data-trans');
    let legislation_id=$(this).attr('id');
    legislation_id = legislation_id.replace("n", "");
    var vote = document.querySelector("input[name='t_"+legislation_id+"']:checked")?.value || 'x';  	
    $("input[name=t_mast][value=" + vote + "]").prop('checked', 'checked');
	$("#topic_name_m").text(topic_name);
	$("#topic_description_m").text(topic_descr);
    $("#topic_description_t").text(topic_trans);
    $("#legislation_id").val(legislation_id);	
	console.log(vote);
	console.log(legislation_id);
	$("#opentopic").click();
});

//search textbox
$("#search_term_txt").keyup(function(){
let category=$("#category").val();
let keyword=$(this).val();
if(category!=="")
{
	if(keyword==="")
	{
		$("#suggesstion-box").empty();
	}
    var obj={
		category:category,
        keyword:keyword
    };
    $.ajax({
        type: "GET",
        url: "getcategorylist",
        data:obj,
        beforeSend: function(){
            //$("#spinner").show();
        },
        success: function(data){
console.log(data);
            if(data.length>0)
            {
                $("#suggesstion-box").empty();
                let msg="<ul id=\"country-list\" class=\"\">";
                for (key in data)
                {
                    let id=data[key]["member_id"];
                let name=data[key]["name"];
                let description=data[key]["description"].substr(0, 90);;
                    msg+="<li style=\"color: yellow;\" onClick=\"selectSearched('"+id+"','"+name+"')\">"+name+"<br><span style=\"color: #fff; font-size: small\">"+description+"</span></li>";
                }
                msg+="</ul>";
                $("#suggesstion-box").show();
                $("#suggesstion-box").html(msg);
            }

        }
    });
}
});
const selectSearched = (id,name) => {
    $("#searched_id").val(id);    
    $("#search_term_txt").val(name);
    $("#suggesstion-box").hide();
	executeAnalysis();
  
}

//execute analysis
const executeAnalysis = () => {
	let dat1=$("#dat1").val();
	let dat2=$("#dat2").val();
	let category=$("#category").val();
	let searched_id=$("#searched_id").val();
	let state=$("#state").val();
	let congressional=$("#congressional").val();
    var obj={
		dat1:dat1,
		dat2:dat2,
		category:category,
        searched_id:searched_id,
        state:state,
        congressional:congressional
    };
    $.ajax({
        type: "GET",
        url: "getanalysis",
        data:obj,
        beforeSend: function(){
            //$("#spinner").show();
        },
        success: function(data){
console.log(data);

            if(data.length>0)
            {
				let len=data.length;
let txt="<h3>Total Number : <span class='uk-badge'>"+len+"</span>";
txt+="<table class='table align-items-center mb-0 uk-table-responsive uk-table-divider'>";
				for(key in data)
					
					{
					let firstname = data[key]['firstname'];;
	let lastname = data[key]['lastname'];
	 txt+="<tr><td>"+firstname+"</td><td>"+lastname+"</td></tr>";
}
txt+="</tbody></table>";
                $("#result").html(txt);
            }
			else{
				$("#result").html("<p class='text-danger' align='center'>No Results</p>");
			}

        }
    });
}
//dropdown
$(document).on('change','.ochang',function() {  
  executeAnalysis();
});

$(document).on('click','.editc',function() {  
let id=$(this).attr("data-id");
$("#legislation_id").val(id);
let myname=$(".myname").text();
let mydescription=$(".mydescription").text();
let myvote_date=$("#myvote_date").val();
$("#dvote_date").val(myvote_date);
$("#dname").val(myname);
$("#ddescription").text(mydescription);
  $("#editmodal").click();
});

$(document).on('click','.saveedit',function() {  
$("#save").empty();
let id=$("#legislation_id").val();
let page=$("#page").val();
let dvote_date=$("#dvote_date").val();
let dname=$("#dname").val();
let ddescription=$("#ddescription").val();
let typep=$("#typep").val();
let token=$('input[name="_token"]').val();
const obj={
	id:id,
	page:page,
	typep:typep,
	dvote_date:dvote_date,
	dname:dname,
	ddescription:ddescription,
	_token:token
};
console.log(obj);
$.ajax({
        type: "POST",
        url: "/editcategory",
        data:obj,
        beforeSend: function(){
            $("#save").text("please wait...");
        },
        success: function(data){
console.log(data);

             if(data==="1")
		 {	
$(".myname").text(dname);
$(".mydescription").text(ddescription);	 
			$("#save").html(" <div class=\"alert alert-success alert-dismissible fade show\" role=\"alert\"><p class=\"text-white mb-0\">Your vote has been successfully casted</p></div>"); 
		 }
		 else{
			 $("#save").html(" <div class=\"alert alert-danger alert-dismissible fade show\" role=\"alert\"><p class=\"text-white mb-0\">Failed</p></div>"); 
	
		 }

        },
        error: function (jqXHR, exception) {
			const err=JSON.parse(jqXHR.responseText);
			console.log(err);
        }
    });
});

$(document).on('click','.delete',function() {  
 if(confirm("Are you sure you want to delete this?"))
    {
let id=$(this).attr("data-id");
let page=$("#page").val();
let token=$('input[name="_token"]').val();
const obj={
	id:id,
	page:page,
	_token:token
};
console.log(obj);
$.ajax({
        type: "POST",
        url: "/deletecategory",
        data:obj,
        beforeSend: function(){            
        },
        success: function(data){
console.log(data);

             if(data==="1")
		 {	
$(".myname").addClass("bg-danger");
$(".mydescription").addClass("bg-danger");
				 }

        },
        error: function (jqXHR, exception) {
			const err=JSON.parse(jqXHR.responseText);
			console.log(err);
        }
    });
	}
});
$(document).on('change','input[name="changepass"]',function() {
  if($(this).prop('checked') == true){    
	$(".pp").show();
	$("#passstatus").val(1);
}
else{
	$("#passstatus").val(0);
	$(".pp").hide();
}
});

$(document).on('click','.edit',function() {  
   $("#passstatus").val(0);
   $(".pp").hide();
   $(".wait").empty();
   $("input[name='changepass']").prop('checked','');
    let user_id=$(this).attr('data');
	$("#user_id").val(user_id);
    let name=$(this).attr('data-name');
    let surname=$(this).attr('data-surname');
    let email=$(this).attr('data-email');
    let status=$(this).attr('data-status');
    let username=$(this).attr('data-username');
    let address=$(this).attr('data-address');
    let role=$(this).attr('data-role');
    let city=$(this).attr('data-city');
    let country=$(this).attr('data-country');
    let postal=$(this).attr('data-postal');
    let congressional=$(this).attr('data-congressional');	
	//enter values
	$("#firstnameedit").val(name);
	$("#lastnameedit").val(surname);
	$("#emailedit").val(email);
	$("#usernameedit").val(username);
	$("#addressedit").val(address);
	$("#cityedit").val(city);
	$("#postaledit").val(postal);
	$("#countryedit").val(country);
	$("#congressionaledit").val(congressional);
	$("#roleedit").val(role);
	
	if(status==="1")
	{
		$("input[name='ustatus']").prop('checked','checked');
		console.log("test");
	}
	else{
		$("input[name='ustatus']").prop('checked','');
		console.log("pppp");
	}
	$("#userstatus").click();
});

const saveedit = () =>{
	let user_id=$("#user_id").val();
	let firstname=$("#firstnameedit").val();
	let lastname=$("#lastnameedit").val();
	let email=$("#emailedit").val();
	let username=$("#usernameedit").val();
	let address=$("#addressedit").val();
	let city=$("#cityedit").val();
	let postal=$("#postaledit").val();
	let country=$("#countryedit").val();
	let congressional=$("#congressionaledit").val();
	let role=$("#roleedit").val();
	let password=$("#password").val();
	let passstatus=$("#passstatus").val();
	let token=$('input[name="_token"]').val();
	let status=0;
	if($("input[name='ustatus']").prop('checked') == true){ 
		status=1;
	}
	   const obj={
        user_id:user_id,
        firstname:firstname,
        lastname:lastname,
        email:email,
        username:username,
        address:address,
        city:city,
        postal:postal,
        country:country,
        congressional:congressional,
        role:role,
        passstatus:passstatus,
        password:password,
        status:status,
_token:token		
    };
    console.log(obj);
    $.ajax({
        url: "/saveedit",
        type: "POST",
        data: obj,
        beforeSend:function (xhr){
            $(".wait").text("please wait...");
        },
        success: function (data) {
         console.log(data);
		 if(data==="1")
		 {			
			$(".wait").html(" <div class=\"alert alert-success alert-dismissible fade show\" role=\"alert\"><p class=\"text-white mb-0\">Record Successfully saved</p></div>"); 
		 }
		 else{
			 $(".wait").html(" <div class=\"alert alert-danger alert-dismissible fade show\" role=\"alert\"><p class=\"text-white mb-0\">Failed</p></div>"); 
	
		 }
        },
        error: function (jqXHR, exception) {
			const err=JSON.parse(jqXHR.responseText);
			let errmsg="There is an error : "+err["message"];
            console.log("There is an error : "+jqXHR.responseText);
			$(".wait").html(" <div class=\"alert alert-danger alert-dismissible fade show\" role=\"alert\"><p class=\"text-white mb-0\">"+errmsg+" </p></div>"); 
        }
    });
	
}

$(document).on('click','.deleteuser',function() {  
 if(confirm("Are you sure you want to delete this user?"))
    {
let user_id=$(this).attr("data");
let token=$('input[name="_token"]').val();
const obj={
	user_id:user_id,
	_token:token
};
console.log(obj);
$.ajax({
        type: "POST",
        url: "/deleteuser",
        data:obj,
        beforeSend: function(){            
        },
        success: function(data){
console.log(data);

             if(data==="1")
		 {
$("#x"+user_id).addClass("bg-danger");
				 }

        },
        error: function (jqXHR, exception) {
			const err=JSON.parse(jqXHR.responseText);
			console.log(err);
        }
    });
	}
});