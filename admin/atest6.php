<html>
<head>

    <title>MCA Administration System</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <style type="text/css">
        .bg-danger{
            background-color: red;
        }
        .bg-warning{
            background-color: yellow;
        }
        .bg-gray-light{
            background-color: gray;
        }
    </style>

    <script>
        var table="";
       var obj={identityNum:20};
       $.ajax({
           url:"ajaxPhp/test7.php",
           type:"GET",
           data:obj,
           success:function(data){
               var xx2=JSON.stringify(data);
               var json2= JSON.parse(xx2);
               var vv = JSON.parse(json2);

               for (let qa in vv) {
                   var username=vv[qa]["username"];
                   table+="<table class=\"table table-head-fixed\"><caption>"+username+"</caption><thead><tr><th>Grade</th><th>Description</th></tr></thead><tbody>";
                   var dd=vv[qa]["data"];
                   for (let mydata in dd) {
                       var descr=dd[mydata]["descr"];
                       var total=dd[mydata]["total"];
                       var clas="";
                       if(total<5){clas="bg-gray-light"}
                       else if(total>=5 && total <= 10){clas="bg-warning"}
                       else if(total>10){clas="bg-danger"}

                       table+="<tr><td class='"+clas+"'></td><td>"+descr+"</td></tr>";
                   }
                   table+="</tbody></table>";
               }
               $("#disply").html(table);

           },
           error:function(jqXHR, exception)
           {

           }
       });
    </script>

</head>
<div id="disply">
</div>
