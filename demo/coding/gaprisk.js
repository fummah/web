$(function () {

    $('.select2').select2()
    $('.select2bs4').select2({
        theme: 'bootstrap4'
    })
    $('.daterange').daterangepicker({
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        startDate: moment().subtract(29, 'days'),
        endDate: moment()
    }, function (start, end) {
        $("#dat1").val(start.format('Y-MM-DD'));
        $("#dat2").val(end.format('YYYY-MM-DD'));
        $("#datetxt").text(start.format('D MMM YYYY') + ' - ' + end.format('D MMM YYYY'))
        objClass.loadData();

    });
    let objClass=new Proc();
    $('#sort-it ol').sortable({
        onDrop: function(item) {
            $(item).removeClass("dragged").removeAttr("style");
            $("body").removeClass("dragging");

            getInitialOrder('#sort-it li');
            objClass.loadData();
        }
    });

    getInitialOrder('#sort-it li');

    //bind stuff to number inputs
    $('#sort-it ol input[type="number"]').focus(function(){
        $(this).select();

    }).change(function(){
        updateAllNumbers($(this), '#sort-it input');
    }).keyup(function(){
        updateAllNumbers($(this), '#sort-it input');

    });
    //bind to form submission
    $('#sort-it').submit(function(e){
        reorderItems('#sort-it li', '#sort-it ol');
        e.preventDefault();
    })
    //$('.knob').knob();

    let client_obj={identity_number:1};
    let user_obj={identity_number:2};
    objClass.loadUsers(client_obj,"clients");
    objClass.loadUsers(user_obj,"users");
    objClass.loadPanel();
});

class Proc
{
    constructor() {
        Proc.myvar = [];
        Proc.myvar1 = [];
        Proc.myHierach = [];
    }
loadUsers(obj,rot)
{
    $.ajax({
        url: "gapriskajax.php",
        beforeSend:function (xhr)
        {
            $(".spinner").show("fast");
        },
        type:"POST",
        data:obj,
        success: function(data){
            let json=JSON.parse(data);
            $.each(json, function(index) {
                let obj_name=json[index].obj_name;
                $("#"+rot).append("<option value='"+obj_name+"'>"+obj_name+"</option>");
            });
            //console.log(json);
        },
        complete:function (xhr,status) {
            $(".spinner").hide("fast");
        },
        error:function (xhr,status,error) {
            alert("There is an error");
        }
    });
}
loadData()
{
    //console.log(sRange());
    var val_ticked=document.querySelector('input[name="r1"]:checked').value;
    var clients=$("#clients").val();
    var users=$("#users").val();
    clients=JSON.stringify(clients);
    users=JSON.stringify(users);
    var start_date=$("#dat1").val();
    var end_date=$("#dat2").val();
    var hierach=JSON.stringify(sRange());
    let columns=JSON.stringify(Proc.myvar);
    let obj={identity_number:3,columns:columns,val_ticked:val_ticked,clients:clients,users:users,start_date:start_date,end_date:end_date,hierach:hierach};

    $.ajax({
        url: "gapriskajax.php",
        beforeSend:function (xhr)
        {
            $(".spinner").show("fast");
        },
        type:"POST",
        data:obj,
        success: function(data){
$("#myTable").trigger("destroy");
            $("#infor").empty();
          $("#infor").html(data);
          //console.log(data);
        },
        complete:function (xhr,status) {
            $(".spinner").hide("fast");
            //$(".mybutton").click();
        },
        error:function (xhr,status,error) {
            alert("There is an error");
        }
    });

}
    loadPanel()
    {
        let obj={identity_number:4};
        $.ajax({
            url: "gapriskajax.php",
            beforeSend:function (xhr)
            {
                $(".spinner").show("fast");
            },
            type:"POST",
            data:obj,
            success: function(data){
console.log(data);
                let json=JSON.parse(data);
                let  totals=json.totals;
                let  top=json.top;
                let count=0;

                $.each(totals, function(index) {
                    let ui_name=totals[index].ui_name;
                    let field_name=totals[index].field_name;
                    let status=totals[index].status;
                    let type=totals[index].type;
                    if(status==="checked")
                    {
                        Proc.myvar.push(field_name);
                        Proc.myvar1.push(ui_name);
                    }

                    $("#totals_panel").append(" <div class=\"uk-margin uk-grid-small uk-child-width-auto uk-grid\">\n" +
                        "            <label><input id='"+field_name+"' class=\"uk-checkbox\" type=\"checkbox\" "+status+" onclick='addToList(\""+field_name+"\",\""+ui_name+"\")'> "+ui_name+"</label>\n" +
                        "        </div>");
                    count++;
                });
                rearrange(Proc.myvar1);
                let icount=1;
                $.each(top, function(index) {
                    let ui_name=top[index].ui_name;
                    let field_name=top[index].field_name;
                    let status=top[index].status;
                    let table=top[index].table;
                    let show=top[index].show;
                    Proc.myHierach.push(field_name);
                    let id_name="custom-number-"+icount;
                    if(show==="no")
                    {
                        return true;
                    }
                    $("#top_panel").append("   <li><span class=\"tct\" data-id=\""+field_name+"\">"+ui_name+"</span>\n" +
                        "                                    <input style='color: #0b8278' value='"+icount+"' id=\""+id_name+"\" class=\"in\" name=\""+id_name+"\" type=\"number\" min=\"1\">\n" +
                        "                                </li>");
                    icount++;
                });
            },
            complete:function (xhr,status) {
                $(".spinner").hide("fast");
            },
            error:function (xhr,status,error) {
                alert("There is an error");
            }
        });

    }


}
let objClass=new Proc();
    function addToList(check_name,check_val)
    {
        if(document.getElementById(check_name).checked)
        {
            Proc.myvar.push(check_name);
            Proc.myvar1.push(check_val);
        }
        else
        {
            Proc.myvar.remove(check_name);
            Proc.myvar1.remove(check_val);
        }


        rearrange(Proc.myvar1);
//console.log(Proc.myvar);
        objClass.loadData();


    }
    function rearrange(arr=[])
    {
        var tt=$("#tt");
        tt.empty();
        tt.append("<th width='30%'></th>");
        for(let i=0;i<arr.length;i++)
        {
            let item=arr[i];
            tt.append("<th>"+item+"</th>");
        }

    }
    function addTree(count,name)
    {
        let hierach_arr=sRange();
        let  hierach_str=JSON.stringify(hierach_arr);
        var clients=$("#clients").val();
        var users=$("#users").val();
        clients=JSON.stringify(clients);
        users=JSON.stringify(users);
        var start_date=$("#dat1").val();
        var end_date=$("#dat2").val();
        let columns=JSON.stringify(Proc.myvar);
        let obj={identity_number:5,columns:columns,clients:clients,users:users,start_date:start_date,end_date:end_date,hierach:hierach_str,counter:count,name:name};

        $.ajax({
            url: "gapriskajax.php",
            beforeSend:function (xhr)
            {
                $(".spinner").show("fast");
                $(".clas-"+count).remove();
            },
            type:"POST",
            data:obj,
            success: function(data){

                $("#groups-"+count).after(data);
            },
            complete:function (xhr,status) {
                $(".spinner").hide("fast");
            },
            error:function (xhr,status,error) {
                alert("There is an error");
            }
        });


    }
function getInitialOrder(obj){
    var num = 1;
    $(obj).each(function(){
        //set object initial order data based on order in DOM
        $(this).find('input[type="number"]').val(num).attr('data-initial-value', num);
        num++;
    });
    $(obj).find('input[type="number"]').attr('max', $(obj).length); //give it an html5 max attr based on num of objects

}
function sRange(){
    let main_arr=[];
    var slides = document.getElementsByClassName("tct");
    for (var i = 0; i < slides.length; i++) {
        var ff=$(".tct:eq("+i+")").attr("data-id");
        main_arr.push(ff);
    }
    main_arr = arrayUnique(main_arr.concat(Proc.myHierach));
    return main_arr;
}

function updateAllNumbers(currObj, targets){
    var delta = currObj.val() - currObj.attr('data-initial-value'), //if positive, the object went down in order. If negative, it went up.
        c = parseInt(currObj.val(), 10), //value just entered by user
        cI = parseInt(currObj.attr('data-initial-value'), 10), //original object val before change
        top = $(targets).length;

    //console.log("test");
    if(c > top){
        currObj.val(top);
    }else if(c < 1){
        currObj.val(1);
    }

    $(targets).not($(currObj)).each(function(){ //change all the other objects
        var v = parseInt($(this).val(), 10); //value of object changed

        if (v >= c && v < cI && delta < 0){ //object going up in order pushes same-numbered and in-between objects down
            $(this).val(v + 1);
        } else if (v <= c && v > cI && delta > 0){ //object going down in order pushes same-numbered and in-between objects up
            $(this).val(v - 1);
        }
    }).promise().done(function(){
        //after all the fields update based on new val, set their data element so further changes can be tracked
        //(but ignore if no value given yet)
        $(targets).each(function(){
            if($(this).val() !== ""){
                $(this).attr('data-initial-value', $(this).val());
            }
        });
    });
}

function reorderItems(things, parent){
    for(var i = 1; i <= $(things).length; i++){
        $(things).each(function(){
            var x = parseInt($(this).find('input').val(), 10);
            if(x === i){
                $(this).appendTo(parent);
            }
        });
    }

}
Array.prototype.remove = function() {
    var what, a = arguments, L = a.length, ax;
    while (L && this.length) {
        what = a[--L];
        while ((ax = this.indexOf(what)) !== -1) {
            this.splice(ax, 1);
        }
    }
    return this;
};
const myFunction = () => {
    const trs = document.querySelectorAll('#myTable tr:not(.header)')
    const filter = document.querySelector('#myInput').value
    const regex = new RegExp(filter, 'i')
    const isFoundInTds = td => regex.test(td.innerHTML)
    const isFound = childrenArr => childrenArr.some(isFoundInTds)
    const setTrStyleDisplay = ({ style, children }) => {
        style.display = isFound([
            ...children // <-- All columns
        ]) ? '' : 'none'
    }

    trs.forEach(setTrStyleDisplay)
}
function arrayUnique(array) {
    var a = array.concat();
    for(var i=0; i<a.length; ++i) {
        for(var j=i+1; j<a.length; ++j) {
            if(a[i] === a[j])
                a.splice(j--, 1);
        }
    }

    return a;
}
$(document).on('change','select',function() {
    objClass.loadData();
});
