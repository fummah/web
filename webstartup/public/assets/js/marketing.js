$(document).ready(function() {        
        let page=$("#page").val();
        let arroob=[{ data: 'first_name' },{ data: 'last_name' },{ data: 'email' },{ data: 'contact_number' },{ data: 'actions' },];
        if(page=="campaigns")
        {
          arroob=[{ data: 'campaign_name' },];
        }
      
        //Table dta for subscribers
        $('#subscriberTable').DataTable({
      'processing': true,
      'serverSide': true,
      'serverMethod': 'post',
      'ajax': {
          'url':'/getmarketingtable',
           'data': { _token:$('input[name="_token"]').val(),page:page},

        error:function (xhr,status,error) {
            console.log(xhr);
        }
           
      },
      'columns':arroob 
   });
facebookAnalysis();
});
$(document).on('change','#campaign_type',function(e){
      
        var total_selected=0;
        var total_incremented=0; 
            var nna="No";
            $vval=0;
            var radios = document.getElementsByName('campaign_type');
            for (var i = 0, length = radios.length; i < length; i++) {
                if (radios[i].checked) {
                    nna=(radios[i].value);
                    break;
                }
            }
            if(nna=="1")
            {
                $vval=1;             
                $('#eem').hide();
            }
            else
            {
                $("#subscribers").empty();
                getSubscribers();
                $vval=0;                
                $('#eem').show();
            }
            return $vval;
        });
   const getSubscribers = () =>{
       
    const obj={    
      
    };
       $.ajax({
        url: "/getsubscribers",
        beforeSend:function (xhr)
        {
          
        },
        type:"GET",
        data:obj,
        success: function(data){  
            
            for(let key in data)
            {
            let subscriber_id=data[key]["id"];
            let subscriber_name=data[key]["first_name"]+" "+data[key]["last_name"];
            $("#subscribers").append("<option value='"+subscriber_id+"'>"+subscriber_name+"</option>");           
}
       
        },
        complete:function (xhr,status) {
           
        },
        error:function (xhr,status,error) {
            console.log(xhr);
        }
    });
        };
        //Genrate AI
           const generateAI = (val,prompt_name) =>{                  
    const obj={    
      _token:$('input[name="_token"]').val(),
      val:val,
      prompt_name:prompt_name
    };
       $.ajax({
        url: "/content_generator",
        beforeSend:function (xhr)
        {
          $("#info").text("Generating, please wait ...");
        },
        type:"POST",
        data:obj,
        success: function(data){   
        console.log("Test1"); 
        console.log(data); 
        console.log("Test2");        
            const json=JSON.parse(data);          
            if(val=="0")
            {
                let txt=json["choices"][0]["text"];
              $("#info").html("<h2><u>Generated Text</u></h2>"+txt);
            }
            else
            {
                let img=json["data"][0]["url"];             
               $("#info").html("<h2><u>Generated Image</u></h2><img src='"+img+"' height='256' height='256'><br/><a href='"+img+"' download><button class='btn btn-primary'>Download Image</button></a>"); 
            }
        },
        complete:function (xhr,status) {
           $("#regenerate").show();
        },
        error:function (xhr,status,error) {
            console.log(xhr);
            $("#info").html("There is an error");
        }
    });
        };
        $(document).on('click','.ai',function(e){
            let prompt_name=$("#prompt_name").val(); 
            if(prompt_name.length>1)
            {
            $("#info").empty();           
            let val=$(this).attr("data");      
            generateAI(val,prompt_name);             
            $("#regenerate").attr("data",val);
        }
        else
        {
            $("#info").html("Please enter valid text");
        }
        });
       //Facebook
        const facebookAnalysis = () =>{     
        let campaign_type=$("#campaign_type").val(); 
        console.log(campaign_type);
        if(campaign_type=="Social Post")   
        {
           let campaign_id=$("#campaign_id").val(); 
           const obj={  
      campaign_id:campaign_id
    };
              
    
       $.ajax({
        url: "/facebook_analysis",
        beforeSend:function (xhr)
        {
          $("#info").text("Loading summary...");
        },
        type:"GET",
        data:obj,
        success: function(data){   
        console.log(data);          
           $("#comments").text(data["comments"]);
           $("#shares").text(data["shares"]);
           $("#likes").text(data["likes"]);
           $("#engagements").text(data["engagements"]);
        },
        complete:function (xhr,status) {
            $("#info").hide();
        },
        error:function (xhr,status,error) {
            console.log(xhr);
            $("#info").html("There is an error");
        }
    });
         } 
        };
        $(document).on('click','.wait',function(e){
            $("#wait").show();
        });