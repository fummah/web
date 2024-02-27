$(function () {
  //naly();
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
    var analy_array=[];
    var analy_array1=[];
    var sum=0;
    var val=document.querySelector('input[name="r1"]:checked').value;
    var users=$("#users").val();
    var start_date=$("#dat1").val();
    var end_date=$("#dat2").val();
    var tti="QA Claim Percentages";
    $(".mychart").empty();
    $.ajax({
      url:"../ajax/reports.php",
      type:"GET",
      data:{
        identityNum:21,
        start_date:start_date,
        end_date:end_date,
        val:val,
        clients:"",
        users:JSON.stringify(users),
        val1:""
      },
      async: false,
      success:function (data) {

        var json2= JSON.parse(data);
        $.each(json2, function(key, value){
          var client=json2[key].client_name;
          var total=json2[key].total;
          sum+=parseInt(total);
          analy_array.push(client);
          analy_array1.push(total);

        });

      },
      error:function (jqXHR, exception) {
        alert(jqXHR.responseText);
      }
    });

    analy_array.reverse();
    analy_array1.reverse();
    var options = {
          series: [{
          name: 'QA Percentage',
          data: analy_array1
        }],
          chart: {
          height: 350,
          type: 'bar',
        },
        plotOptions: {
          bar: {
            borderRadius: 10,
            dataLabels: {
              position: 'top', // top, center, bottom
            },
          }
        },
        fill: {
  colors: ['#2e6da4']
},
        dataLabels: {
          enabled: true,
          formatter: function (val) {
            return val + "%";
          },
          offsetY: -20,
          style: {
            fontSize: '12px',
            colors: ["#304758"]
          }
        },
        
        xaxis: {
          categories: analy_array,
          position: 'bottom',
          axisBorder: {
            show: false
          },
          axisTicks: {
            show: false
          },
          crosshairs: {
            fill: {
              type: 'gradient',
              gradient: {
                colorFrom: '#D8E3F0',
                colorTo: '#BED1E6',
                stops: [0, 100],
                opacityFrom: 0.4,
                opacityTo: 0.5,
              }
            }
          },
          tooltip: {
            enabled: true,
          }
        },
        yaxis: {
          axisBorder: {
            show: false
          },
          axisTicks: {
            show: false,
          },
          labels: {
            show: false,
            formatter: function (val) {
              return val + "%";
            }
          }
        
        },
        title: {
          text: 'QA Percentages',
          floating: true,
          offsetY: 330,
          align: 'center',
          style: {
            color: 'red'
          }
        }
        };

        var chart = new ApexCharts(document.querySelector("#mychart"), options);
        chart.render()

  }

