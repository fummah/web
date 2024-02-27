$(document).ready(function()
        {
            
           
            $("#btn").click(function(){
                var entity=$("#entity").val();
                var search=$("#search").val();
                $("#vis").show();
                
$.ajax({
    url:"ajaxPhp/searchAjaxPhP.php",
    type:"GET",
    data:{id:entity,
        search1:search
    },
    success:function(data){
      $("#vis").hide();
      $("#data").html(data);
    },
    error:function(jqXHR, exception)
                {
                   $("#vis").hide();
                   $("#data").text("An Error occured");
                 }
});
        
    });


 });


function myFunction() {
    var input, filter, ul, li, a, i;
    input = document.getElementById("myInput");
    filter = input.value.toUpperCase();
    div = document.getElementById("myDIV");
    a = div.getElementsByTagName("a");
    for (i = 0; i < a.length; i++) {
        if (a[i].innerHTML.toUpperCase().indexOf(filter) > -1) {
            a[i].style.display = "";
        } else {
            a[i].style.display = "none";
        }
    }
}

