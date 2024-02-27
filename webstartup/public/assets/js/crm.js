
$(document).ready(function() {
        getCustomers();
        let page=$("#page").val();
        let arroob=[{ data: 'name' },{ data: 'email' },{ data: 'contact_number' },{ data: 'role' },{ data: 'actions' },];
        if(page=="quotes" || page=="invoices" || page=="orders")
        {
          arroob=[{ data: 'name' },{ data: 'item_number' },{ data: 'date_entered' },{ data: 'status' },{ data: 'actions' },];
        }
        //Table dta for customers
        $('#empTable').DataTable({
      'processing': true,
      'serverSide': true,
      'serverMethod': 'post',
      'ajax': {
          'url':'/getfortabledata',
           'data': { _token:$('input[name="_token"]').val(),page:page},
           
      },
      'columns':arroob 
   });

});
let items=[];
let swalconfirm=false;
let count=parseFloat($("#tot").val())+1;

const addItem = () =>{
    let total_amount=getTotalAmount();
    
    let item_name =$("#item_name").val();
    let cost =$("#cost").val();
    if(item_name=="" || cost=="")
    {
        alert("Please fill in empty input");
    }
    else
    {
    let item_obj={
id:count,item_name:item_name,cost:cost
    };   

     let inarr =  items.filter(function(creature) {
                        return creature.item_name === item_name;
                    });
     if(inarr.length>0)
     {
        alert("Duplicate item, please try a differrent item");
     }
     else
     {    total_amount=total_amount+parseFloat(cost);   
        $("#items").append("<tr id='"+count+"'><td><i class='now-ui-icons ui-1_simple-remove remove' cost='"+cost+"' data='"+count+"' style='color:red; cursor:pointer'></i> "+count+".</td><td>"+item_name+"</td><td>$"+cost+"</td></tr>");
        items.push(item_obj);
        count=count+1;
      
        setTotalAmount(total_amount);
        $("#close").click();
     }

}
};

//Get total Amount
const getTotalAmount=()=>{
let total_amount=$("#total_amount").text();
total_amount=parseFloat(total_amount);
return total_amount;
};

//Set Total Amount
const setTotalAmount=(amount)=>{
let setamount=amount.toFixed(2);
$("#total_amount").text(setamount);
let strarr=JSON.stringify(items);
$("#item_obj").val(strarr);
};

//remove Item
$(document).on('click','.remove',function() {
    let total_amount=getTotalAmount();
    let id=$(this).attr("data");
    let incost=$(this).attr("cost");
 
    let removeid=parseFloat(id);
    incost=parseFloat(incost);
    total_amount=total_amount-incost; 
    items =  items.filter(function(creature) {
                        return creature.id !== removeid;
                    }); 
    setTotalAmount(total_amount);
    $("#"+id).empty();


});

//
$(document).on('click','.remove-db',function() {
   deleteItemList(item_id);
});

//
     // Get Customers
     const getCustomers = () =>{
        //let token=$('input[name="_token"]').val();  
    const obj={     
//_token:token        
    };
       $.ajax({
        url: "/getcustomers",
        beforeSend:function (xhr)
        {
          
        },
        type:"GET",
        data:obj,
        success: function(data){        
            for(let key in data)
            {
            let customer_id=data[key]["id"];
            let customer_name=data[key]["name"];
            $("#client_name").append("<option value='"+customer_id+"'>"+customer_name+"</option>");
           
}
       
        },
        complete:function (xhr,status) {
           
        },
        error:function (xhr,status,error) {
            console.log("There is an error");
        }
    });
        };

//On Customer Change
$(document).on('change','#client_name',function() {
let customer_id=$(this).val();
getclientDetails(customer_id);
    });

//Get Client Details
let getclientDetails=(customer_id)=>{
    let token=$('input[name="_token"]').val();  
    const obj={     
_token:token,
customer_id:customer_id       
    };
$.ajax({
        url: "/getotherdetails",
        beforeSend:function (xhr)
        {
          
        },
        type:"POST",
        data:obj,
        success: function(data){  
               
        let details=data["customer"];
        let quotes=data["quotes"];
        let order=data["order"];
        let company_name=details["company_name"]==="" || details["company_name"]===null?"N/A":details["company_name"];
        let address=details["address"]==="" || details["address"]===null?"N/A":details["address"];
        let contact_number=details["contact_number"]==="" || details["contact_number"]===null?"N/A":details["contact_number"];
        let email=details["email"]==="" || details["email"]===null?"N/A":details["email"];
        $("#company_name").text(company_name);
        $("#address").text(address);
        $("#contact_number").text(contact_number);
       $("#email").text(email);
       $("#order").empty();
        $("#quote").empty();
        $("#order").append("<option>[Select Order]</option>");
        $("#quote").append("<option>[Select Quote]</option>");

       for(let key in quotes)
       {
        let quote=quotes[key]["quote_number"];
        $("#quote").append("<option value='"+quote+"'>"+quote+"</option>");
       }
      
        for(let key in order)
       {
       
        let myorder=order[key]["order_number"];
        if(myorder.length>5)
        {
        $("#order").append("<option value='"+myorder+"'>"+myorder+"</option>");
    }
       }
        },
        complete:function (xhr,status) {
           
        },
        error:function (xhr,status,error) {
            console.log("There is an error");
        }
    });
};

//Delete itm List

 const deleteItemList = (item_id) =>{
        let token=$('input[name="_token"]').val();  
        let item=$('input[name="item"]').val();
    const obj={     
_token:token,
item_id:item_id,
item:item       
    };
       $.ajax({
        url: "/deleteitemlist",
        beforeSend:function (xhr)
        {
          
        },
        type:"POST",
        data:obj,
        success: function(data){   
        
           if(data.indexOf("deleted")>-1)
           {
            $("#"+item_id).css("background-color","red");
             let total_amount=getTotalAmount();
    let item_id=$(this).attr("data");
    let incost=$(this).attr("cost");    
    total_amount=total_amount-incost;
    setTotalAmount(total_amount);
           }
           else
           {
            alert("Failed to remove an item");
           }

       
        },
        complete:function (xhr,status) {
           
        },
        error:function (xhr,status,error) {
            console.log("There is an error");
        }
    });
        };
        $(document).on('change','.statuses',function() {
   let status=$(this).val();
   console.log(status);
   $("#status").val(status);
});
        $(document).on('click','.edit-customer',function() {
let customer_id=$(this).attr("data");
$("#customer_id").val(customer_id);
console.log(customer_id);
editcustomer(customer_id);
$(".pps").show();
$("#spannae").text("Edit");
$("#edit_customer").click();

    });
         $(document).on('click','#new_customer',function() {
$("#customer_id").val(0);
   $("#customer_name").val("");
        $("#company_name").val("");
        $("#customer_address").val("");
        $("#contact_number").val("");
       $("#customer_email").val(""); 
       $("#spannae").text("Add New");
$(".pps").hide();
    });
        const editcustomer=(customer_id)=>{
            let token=$('input[name="_token"]').val();  
    const obj={     
_token:token,
customer_id:customer_id       
    };
$.ajax({
        url: "/getotherdetails",
        beforeSend:function (xhr)
        {
          
        },
        type:"POST",
        data:obj,
        success: function(data){  
        let details=data["customer"]; 
        let customer_name=details["name"];      
        let company_name=details["company_name"];
        let address=details["address"];
        let contact_number=details["contact_number"];
        let email=details["email"];
        $("#customer_name").val(customer_name);
        $("#company_name").val(company_name);
        $("#customer_address").val(address);
        $("#contact_number").val(contact_number);
       $("#customer_email").val(email);      
        },
        complete:function (xhr,status) {
           
        },
        error:function (xhr,status,error) {
            console.log("There is an error");
        }
    });
};
$(document).on('submit','form',function(e) {

         let url=window.location.toLocaleString();
        
         if(url.indexOf("login")>-1 || url.indexOf("register")>-1 || url.indexOf("enable2fa")>-1 || url.indexOf("forgot")>-1 || url.indexOf("two-factor-challenge")>-1)
         {

         }
         else{
        if (!swalconfirm) {
            e.preventDefault();
            Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirm',
            closeOnConfirm: true
}).then((result) => {
  if (result.isConfirmed) {   
    swalconfirm=true;
    $(this).submit();      
  }
  else {
swalconfirm=false;
            }
})
        }
        else
        {

        }
   }
});