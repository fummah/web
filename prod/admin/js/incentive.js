$(function () {
 $('.select2users').select2();  
  naly()
  $(".hid").hide();
  $(".cc").click(function () {
    naly();
  });
  $(".select2users").change(function () {
    naly();
  });
 
});
function naly() {
    var sum=0;
    var val=document.querySelector('input[name="r1"]:checked').value;
    var users=$("#users").val();
    var start_date=$("#dat1").val();
    var end_date=$("#dat2").val();
    const obj={
        identityNum:22,
        start_date:start_date,
        end_date:end_date,
        val:val,
        clients:"",
        users:JSON.stringify(users),
        val1:""
      };
      console.log(obj);

    $.ajax({
      url:"../ajax/reports.php",
      type:"GET",
      data:obj,
      async: false,
      success:function (data) {
$("#incentive").html(data);

      },
      error:function (jqXHR, exception) {
        alert(jqXHR.responseText);
      }
    });

  }





