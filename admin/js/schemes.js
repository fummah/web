function Schemes(txtId,id)
{

    $(document).ready(function(){
        var txt=$("#"+txtId).val();
        $.ajax({
            url:"ajaxPhp/schemesx.php",
            type:"GET",
            data:{
                identityNum:1,
                schemeid:id,
                txt:txt
            },
            success:function(data)
            {
                $("#"+id+"txt").html(data);
            },
            error:function(jqXHR, exception)
            {
                $("#"+id+"txt").text(jqXHR.responseText);
            }
        });

    });


}
