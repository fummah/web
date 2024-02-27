$(function () {

  var colors=['#f56954','#3c8dbc','#483D8B', '#00a65a', '#f39c12', '#00c0ef', '#2F4F4F','#CD853','#006400','#ADFF2F','#F0E68C','#FF6347','#8B0000','#FA8072','#0000CD','#d2d6de','#7B68EE','#EE82EE','#F0FFF0'];
  var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
  var open_user_array=[];
  var open_user_total_array=[];
//open array for clients
  var open_client_array=[];
  var open_client_total_array=[];
//trend claims
  var trend_claim_array=[];
  var trend_claim_total_array=[];
//trend stacked
  var trend_main_array=[];
  var trend_main_total_array=[];
//
  var trend_main_array1=[];
  var trend_main_total_array1=[];
  var build_array=[];
  var current_clients=[];
//

// Make the dashboard widgets sortable Using jquery UI
  $('.connectedSortable').sortable({
    placeholder         : 'sort-highlight',
    connectWith         : '.connectedSortable',
    handle              : '.card-header, .nav-tabs',
    forcePlaceholderSize: true,
    zIndex              : 999999
  })
  $('.connectedSortable .card-header, .connectedSortable .nav-tabs-custom').css('cursor', 'move')

// jQuery UI sortable for the todo list
  $('.todo-list').sortable({
    placeholder         : 'sort-highlight',
    handle              : '.handle',
    forcePlaceholderSize: true,
    zIndex              : 999999
  })
//Initialize Select2 Elements
  $('.select2').select2()

//Initialize Select2 Elements
  $('.select2bs4').select2({
    theme: 'bootstrap4'
  })
// bootstrap WYSIHTML5 - text editor
  $('.textarea').summernote()

  $('.daterange').daterangepicker({
    ranges   : {
      'Today'       : [moment(), moment()],
      'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
      'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
      'Last 30 Days': [moment().subtract(29, 'days'), moment()],
      'This Month'  : [moment().startOf('month'), moment().endOf('month')],
      'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    },
    startDate: moment().subtract(29, 'days'),
    endDate  : moment()
  }, function (start, end) {
    $("#dat1").val(start.format('Y-MM-DD'));
    $("#dat2").val(end.format('YYYY-MM-DD'));
    $("#datetxt").text(start.format('D MMMM YYYY') + ' - ' + end.format('D MMMM YYYY'))
    naly();
  });

  /* jQueryKnob */
  $('.knob').knob();

  naly()

  $(".hid").hide();
  $(".ccval").click(function () {
    naly();
  });
  $(".cc").click(function () {
    naly();
  });
  $(".select2bs4").change(function () {
    naly();
  });
  $("#checkboxSuccess1").click(function () {
    naly();
  });

  function naly() {
    var sum=0;
    var val=document.querySelector('input[name="r1"]:checked').value;
    var users=$("#users").val();
    var start_date=$("#dat1").val();
    var end_date=$("#dat2").val();

    $.ajax({
      url:"../ajaxPhp/reports.php",
      type:"GET",
      data:{
        identityNum:22,
        start_date:start_date,
        end_date:end_date,
        val:val,
        clients:"",
        users:users,
        val1:""
      },
      async: false,
      success:function (data) {
$("#incentive").html(data);

      },
      error:function (jqXHR, exception) {
        alert(jqXHR.responseText);
      }
    });

  }


});





