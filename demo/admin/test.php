<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script>

  function kkk(t="cs")
  {
    return new Promise((resolve,reject)=>{
    $.ajax({
      url:"../ajax/reports.php",
      type:"GET",
      data:{
        identityNum:1,
        type:t
          },
      success:function (data) {  
        const json=JSON.parse(data);
        $("#r").html(data);
      console.log(json);
      console.log("Function 4 mid");    
        resolve(data);
      },
      error:function (jqXHR, exception) {
        reject(jqXHR.responseText);
      }
    });   
  }
);
  }
  async function getClaimsSummary()
  {
    return new Promise((resolve,reject)=>{
    $.ajax({
      url:"../ajax/reports.php",
      type:"GET",
      async:true,
      data:{
        identityNum:24
          },     
      success:function (data) {    
  
const json = JSON.parse(data);
$("#open_claims").text(json["open_claims"]);  
$("#closed_claims").text(json["closed_claims"]); 
$("#new_claims").text(json["new_claims"]); 
console.log("Claims getClaimsSummary");
resolve(data);
      },
      error:function (jqXHR, exception) {
        reject(jqXHR.responseText);
      }
    });
    console.log("Function 1 end");
   
  });
  }
  function test1()
  {
    $("#r1").html("<h1>Test 1</h1>");
    console.log("Test 1");
  }
  function test2()
  {
    $("#r2").html("<h1>Test 2</h1>");
    console.log("Test 2");
  }
$(document).ready(function(){
async function runAll(t="cs")
{
return new Promise((resolve, reject,myFunc) => { 
    resolve(kkk());
});
}
let myPromise = runAll();

myPromise.then(
  function(value) {   
    console.log("terunningst");    
   },
  function(error) {
    console.log(error);
  }
).then(
  function(value)
  {
    let x1=getClaimsSummary();
    x1.then(
  function(value)
  {
    test1();
  }
);
  }
).then(
  function(value)
  {
    test2();
  }
);
});


</script>
<button onclick="getClaimsSummary()">Tessss</button>
<div id="r"></div>
<div id="r1"></div>
<div id="r2"></div>

<div class="row">
<div class="col-4">
<div class="page_title_box d-flex align-items-center justify-content-between">
<div class="page_title_left">


</div>

</div>
</div>
<div class="col-2">
	<a href="#" class="white_btn3" id="analyse" title="Open claims, click to analyse">
		<i class="ti-shine"></i> <span id="open_claims">-</span></a>
	</div>
	<div class="col-2">
		<a href="#" class="white_btn3 claims_modal" data="average" title="Average Days to close a claim">
			<i class="ti-bar-chart"></i> <span id="average">-</span></a>
	</div>
	<div class="col-2">
		<a href="#" class="white_btn3" title="Closed Claims">
			<i class="ti-anchor"></i> <span id="closed_claims">-</span></a>
	</div>
	<div class="col-2">
		<a href="#" class="white_btn3" title="New Claims">
			<i class="ti-target"></i> <span id="new_claims">-</span></a>
	</div>
</div>