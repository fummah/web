<html>
<head>

    <title>Unsubscribe</title>
    <meta charset="utf-8">
      <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets') }}/img/apple-icon.png">
  <link rel="icon" type="image/png" href="{{ asset('assets') }}/img/favicon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{ asset('assets') }}/css/bootstrap.min.css" rel="stylesheet" /> 
    <link href="{{ asset('assets') }}/css/uikit.min.css" rel="stylesheet" />
     <script src="{{ asset('assets') }}/js/core/jquery.min.js"></script>
  <script src="{{ asset('assets') }}/js/core/bootstrap.min.js"></script> 
   <script src="{{ asset('assets') }}/js/core/uikit.min.js"></script> 
    <script src="{{ asset('assets') }}/js/core/uikit-icons.min.js"></script> 
  <script>
        function send() {
            $("#pl").show();
            var email=$("#email").val();
            var subject=$("#subject").val();
            var body=hid();

            var obj={

                email:email,
                subject:subject,
                body:body
            };
            $.ajax({
                url:"admin/ajaxPhp/sendBulk.php?identity=2",
                type:"GET",
                data:obj,
                success:function(data){
                    $("#info").html(data);
                    $("#pl").hide();
                },
                error:function(jqXHR, exception)
                {
                    $("#pl").hide();
                    alert(jqXHR.responseText);
                }
            });
        }

      function hid()
        {
            var nna="No";

            var radios = document.getElementsByName('radio2');
            for (var i = 0, length = radios.length; i < length; i++) {
                if (radios[i].checked) {
                    // do whatever you want with the checked radio
                    nna=(radios[i].value);

                    // only one radio can be logically checked, don't check the rest
                    break;
                }
            }

            return nna;
        }
    </script>
    <style>
        .na{
            width: 70%;
            position: center;
            margin: auto;
            padding: 20px;
        }
       
    </style>
</head>
<body>

<div class="na" style="border-bottom: 1px solid rgba(33, 114, 112, 0.97);border-top: 1px solid rgba(33, 114, 112, 0.97);border-right: 1px solid rgba(33, 114, 112, 0.97);border-left: 1px solid rgba(33, 114, 112, 0.97);margin-top: 20px;border-radius: 3px; align-content: center !important;text-align:center !important;">

    <fieldset class="uk-fieldset">

        <legend class="uk-legend" align="center">Unsubscribe</legend>
      <div class="uk-margin">
            <label style="color: rgba(33, 114, 112, 0.97);">Are you sure you want to Unsubscribe now? </label><br>
            <label><input class="uk-radio" type="radio" name="radio2" value="Yes" checked> Yes</label>
            <label><input class="uk-radio" type="radio" name="radio2" value="No"> No</label>
        </div>
        <button class="uk-button uk-button-default uk-button-small" onclick="send()"><span uk-icon="icon: play"></span> Send Now</button>
        <span id="pl" style="color: red;display: none">please wait....</span>
    </fieldset>
    <span id="info" class="na">

</span>
</div>

</body>

</html>