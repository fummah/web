$("#save_target").click(function () {
  var target=$("#target").val();
  var closed_target=$("#closed_target").val();
  var entered_target=$("#entered_target").val();
  $.ajax({
    url:"../ajaxPhp/reports.php",
    type:"GET",
    data:{identityNum:14,target:target,closed_target:closed_target,entered_target:entered_target},
    async: false,
    success:function (data) {
      $("#target_info").html(data);

    },
    error:function (jqXHR, exception) {
      alert(jqXHR.responseText);
    }
  });
});

function view(id) {
  $("#users1x"+id).slideToggle();
}

function view1(id) {
  $(".b").slideToggle();
  //alert(id);
}