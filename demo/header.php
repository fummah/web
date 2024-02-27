<?php
if(!defined('access')) {
    die('Access not permited');
}
?>
<link rel="shortcut icon" href="images/favicon.ico"/>
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<link rel="stylesheet" href="css/materialize.css">
<link rel="stylesheet" href="css/materialize.min.css">
<link rel="stylesheet" href="css/ghpages-materialize.css">
<link href="css/bootstrap.min.css" rel="stylesheet" integrity="" crossorigin="anonymous">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<script src="js/bootstrap.bundle.min.js" integrity="" crossorigin="anonymous"></script>
<script src="js/bootstrap.min.js" integrity="" crossorigin="anonymous"></script>
<link rel="stylesheet" type="text/css" href="css/datatables.min.css"/>
<link rel="stylesheet" href="css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="css/dataTables.bootstrap4.css">
<script type="text/javascript" src="js/datatables.min.js"></script>
<script src="js/materialize.js"></script>
<script src="js/materialize.min.js"></script>

<script src="js/search.js"></script>
<script src="js/init.js"></script>
<link rel="stylesheet" href="css/uikit.min.css" />
<script src="js/uikit.min.js"></script>
<script src="js/uikit-icons.min.js"></script>

<script>
    let allcate=[];
    $(document).ready(function(){
        getSubMenu();
        async function getSubMenu()
        {           
            var obj={identity_number:33};
            $.ajax({
                url:"ajax/claims.php",
                type:"POST",
                beforeSend:function(){
                    $(".load-bar").show();
                },
                data:obj,
                success:function(data){
                    var json = JSON.parse(data);
                    $("#open_claims").text(json["open_claims"]);
                    $("#clinical").text(json["clinical"]);
                    $("#owls_error").text(json["owls_error"]);
                    $("#pre_assessment").text(json["preassessment"]);
                    $(".leads").text(json["leads"]);
                    $("#qa").text(json["qa"]);
                    $("#new_case").text(json["new_claims"]);
                    //$(".spin").hide();
                    $(".load-bar").hide();

                },
                error:function(jqXHR, exception)
                {

                }
            });
        }
        //http://localhost/new_site/index.php
        $(".dropdown-trigger").dropdown();
    });
    function viewClaim(claim_id)
    {
        $("#claim_id").val(claim_id);
        let sourrceelement=$("#source");
        let categoryeelement=$("#catergory");
        let subcategoryelement=$("#sub_catergory");
        sourrceelement.empty();
        categoryeelement.empty();
        subcategoryelement.empty();
//console.log("done...");
        if(allcate.length<1)
        {
            getViewReasons();
        }
        sourrceelement.append("<option value='' disabled selected>Source</option>");
        categoryeelement.append("<option value='' disabled selected>Catergory</option>");
        subcategoryelement.append("<option value='' disabled selected>Sub-Catergory</option>");
        let arr=[];
        for(let key in allcate)
        {
            let source=allcate[key]["Source"];
            if(arr.indexOf(source)<0)
            {
                sourrceelement.append("<option value='"+source+"'>"+source+"</option>");
            }
            arr.push(source);
        }
        $('.escl').formSelect();
    }
    $(document).on('change','#source',function() {
        let categoryeelement=$("#catergory");
        let subcategoryelement=$("#sub_catergory");
        categoryeelement.empty();
        subcategoryelement.empty();
        let source=$(this).val();
        categoryeelement.append("<option value='' disabled selected>Catergory</option>");
        subcategoryelement.append("<option value='' disabled selected>Sub-Catergory</option>");
        let arr=[];
        const filteredArray = allcate.filter(item => item.Source.indexOf(source) > -1);
        for(let key in filteredArray)
        {
            let catergory=filteredArray[key]["Catergory"];
            if(arr.indexOf(catergory)<0)
            {
                categoryeelement.append("<option value='"+catergory+"'>"+catergory+"</option>");
            }
            arr.push(catergory);
        }
        $('.escl').formSelect();
    });
    $(document).on('change','#catergory',function() {
        let subcategoryelement=$("#sub_catergory");
        subcategoryelement.empty();
        let source=$("#source").val();
        let catergory=$(this).val();
        subcategoryelement.append("<option value='' disabled selected>Sub-Catergory</option>");
        let arr=[];
        const filteredArray = allcate.filter(item => item.Source.indexOf(source) > -1 && item.Catergory.indexOf(catergory) > -1);
        console.log(filteredArray);
        for(let key in filteredArray)
        {
            let subcatergory=filteredArray[key]["Sub-Catergory"];
            if(arr.indexOf(subcatergory)<0)
            {
                subcategoryelement.append("<option value='"+subcatergory+"'>"+subcatergory+"</option>");
            }
            arr.push(subcatergory);
        }
        $('.escl').formSelect();
    });
    function getViewReasons() {
        let obj={identity_number:39};
        $.ajax({
            url: "ajax/claims.php",
            type:"POST",
            data:obj,
            async:false,
            success: function(data){
//console.log(data);
                allcate = JSON.parse(data);
            },
            error:function (xhr,status,error) {
                alert("There is an error");
            }
        });
    }
</script>
<style>
    nav{
        background-color: #fff !important;

    }
    nav ul a{color:#54bc9c !important;}
    .nav-wrapper{border-bottom: 1px solid #7494a4}
    .maiin{background-color:#54bc9c !important;border-bottom: 2px solid #fff; width: 100%}
    .nav-content ul a{color:#fff !important;}
    .rowdetails{border-bottom: 1px solid whitesmoke}
    .sub_badge{background-color: #0b8278}
    .bg-info{color: white !important; background-color: #54bf99 !important;}
    .col-md-5>.uk-badge{padding: 5px !important;}
    .indicator{background-color: #54bc9c !important;}
    ::-webkit-scrollbar {
        width: 7px;
        height: 7px;
    }

    /* Track */
    ::-webkit-scrollbar-track {
        box-shadow: inset 0 0 5px grey;
        border-radius: 10px;
    }

    /* Handle */
    ::-webkit-scrollbar-thumb {
        background: #54bc9c;
        border-radius: 10px;
    }

    /* Handle on hover */
    ::-webkit-scrollbar-thumb:hover {
        background: #84c4dc;
    }
    nav ul li a:hover, nav ul li.active {
        border-bottom: 4px solid #84c4dc !important;
        border-top: 4px solid #54bf99 !important;
        transition: 400ms !important;
        background-color: transparent !important;
        text-decoration: none;
    }
    div .uk-dropdown-nav li a:hover {
        padding-left:10px !important;
        transition: 400ms !important;

    }

    @media (max-width: 991.98px) {
        .hidde{display:none}
        .other{padding-top: 10px;}
    }
    @media (min-width:450px) and (max-width: 1850px) {
        .laptophid{display:none}
    }
    @media (min-width:450px) and (max-width: 1500px) {
        .tablethid{display:none}
    }
    .linkbutton{
        background: none;
        border: none;
        color: #54bc9c;
        text-decoration: underline;
        cursor: pointer;
    }

    ul li ul.dropdown {
        min-width: auto; /* Set width of the dropdown */
        background: #fff;
        display: none;
        position: absolute;
        z-index: 999;
        right: 0;
        top:60px;
    }
    ul li:hover ul.dropdown {
        display: block; /* Display the dropdown */
    }
    ul li ul.dropdown li {
        display: block;
    }
    .activex{
        border-bottom: 2px solid white;padding-bottom: 2px
    }
    .load-bar {
  position: relative; 
  width: 100%;
  height: 6px;
  background-color: #fdba2c;
}
.bar {
  content: "";
  display: inline;
  position: absolute;
  width: 0;
  height: 100%;
  left: 50%;
  text-align: center;
}
.bar:nth-child(1) {
  background-color: #54bc9c;
  animation: loading 3s linear infinite;
}
.bar:nth-child(2) {
  background-color: #84c4dc;
  animation: loading 3s linear 1s infinite;
}
.bar:nth-child(3) {
  background-color: #fdba2c;
  animation: loading 3s linear 2s infinite;
}
@keyframes loading {
    from {left: 0; width: 0;z-index:100;}
    33.3333% {left: 0; width: 100%;z-index: 10;}
    to {left: 0; width: 100%;}
}
</style>
<?php
$script_addclaim="";
$script_adddoctor="";
$script_consent="";
$script_icd10="";
$script_search="";
$script_user="";
$script_leads="";
$script_qa="";
$script_preassment="";
$script_clinical="";
$script_openclaims="";
$script_splitclaims="";
$script_oldsplits="";
$script_dashsplits="";
$script_im="";
$script_oldswitch="";
$script_switch="";
$script=basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);
if($script=="add_case.php" || $script=="gap_claim.php"){$script_addclaim="activex";}
else if($script=="add_doctor.php"){$script_adddoctor="activex";}
else if($script=="consent_forms.php"){$script_consent="activex";}
else if($script=="icd10_lookup.php"){$script_icd10="activex";}
else if($script=="leads.php"){$script_leads="activex";}
else if($script=="view_quality.php"){$script_qa="activex";}
else if($script=="preassessed.php"){$script_preassment="activex";}
else if($script=="clinical_review.php"){$script_clinical="activex";}
else if($script=="open_claims.php"){$script_openclaims="activex";}
else if($script=="search.php" || $script=="search_doctor.php" || $script=="issues.php"){$script_search="activex";}
else if($script=="mca_change_pass.php"){$script_user="activex";}
else if($script=="splits.php"){$script_splitclaims="activex";}
else if($script=="old_splits.php"){$script_oldsplits="activex";}
else if($script=="split_dashboard.php"){$script_dashsplits="activex";}
else if($script=="interface_manager.php"){$script_im="activex";}
else if($script=="old_switch.php"){$script_oldswitch="activex";}
else if($script=="switch_claims.php"){$script_switch="activex";}
?>

<nav class="nav-extended" uk-sticky="offset: 0">
    <div class="nav-wrapper">
        <a href="index.php" class="brand-logo"><img src="images/Med%20ClaimAssist%20Logo_1000px.png" width="200" height="auto"/></a>
        <a href="#" data-target="mobile-demo" class="sidenav-trigger"><i class="material-icons">menu</i></a>

        <ul id="nav-mobile" class="right hide-on-med-and-down">
            <?php
            if($control->isGapCover())
            {
                ?>
                <li><a href="index.php">Home</a></li>
                <li class="<?php echo $script_addclaim;?>"><a href="gap_claim.php">Add New Claim</a></li>
                <?php
            }
            if($control->isInternal())
            {
                ?>
                <li><a href="../admin/admin_main/claims.php">Reports Dashboard</a></li>
                <li class="<?php echo $script_addclaim;?>"><a href="add_case.php">Add New Claim</a></li>
                <li class="<?php echo $script_adddoctor;?>"><a href="add_doctor.php">Add New Doctor</a></li>
                <li class="<?php echo $script_consent;?>"><a href="consent_forms.php">Consent Forms</a></li>
                <?php
            }
            ?>
            <li class="<?php echo $script_icd10;?>"><a href="icd10_lookup.php">ICD10 Lookup</a></li>
            <li class="<?php echo $script_search;?>"><a class="#">Search <i class="material-icons right">arrow_drop_down</i></a>
                <div uk-dropdown>
                    <ul class="uk-nav uk-dropdown-nav">
                        <li class="" style="border-bottom: 1px solid #54bf99"><a href="search.php"><span uk-icon="search"></span> Search Claim</a></li>
                        <?php
                        if($control->isInternal())
                        {
                            ?>
                            <br>
                            <li class="" style="border-bottom: 1px solid #54bf99"><a href="search_doctor.php"><span uk-icon="git-branch"></span> Search Doctor</a></li><br>
                            <li class="" style="border-bottom: 1px solid #54bf99"><a href="issues.php"><span uk-icon="future"></span> MCA Help Desk</a></li>
                            <?php
                        }
                        ?>
                    </ul>
                </div></li>
            <li class="<?php echo $script_user;?>"><a class="#"><span style="color: #84c4dc !important;">| <?php echo $control->loggedAs();?> <i class="material-icons right">account_circle</i></span> </a>

                <ul class="dropdown">
                    <li class="" style="border-bottom: 1px solid #54bf99"><a href="mca_change_pass.php"><span uk-icon="lock"></span> Change Password</a></li><br>
                    <li class="" style="border-bottom: 1px solid #54bf99"><a href="logout.php"><span uk-icon="sign-out"></span> Logout</a></li>
                </ul>
            </li>

        </ul>
    </div>
    <div class="maiin">
        <div class="nav-content">
            <ul class="" style="padding-bottom: 35px;padding-top: 15px">
                <?php
                if($control->isInternal())
                {
                    ?>
                    <li class="hidde <?php echo $script_leads;?>"><a href="leads.php">Leads <span class="uk-badge sub_badge leads"> 0</span></a></li>
                    <li class="hidde <?php echo $script_qa;?>"><a href="view_quality.php">QA <span class="uk-badge sub_badge" id="qa"> 0</span></a></li>

                    <?php
                    if($control->isAssessor() || $control->isTopLevel())
                    {
                        ?>
                        <li class="hidde <?php echo $script_preassment;?>"><a href="preassessed.php">Pre-Assessment <span class="uk-badge sub_badge" id="pre_assessment"> 0</span></a></li>
                        <?php
                    }
                    if($control->isTopLevel())
                    {
                        ?>
                        <li class="<?php echo $script_clinical;?>"><a href="clinical_review.php">Clinical <span class="uk-badge sub_badge" id="clinical"> 0</span></a></li>
                        <li class="<?php echo $script_im;?>"><a href="interface_manager.php">Interface Manager <span class="uk-badge sub_badge" id="owls_error"> 0</span></a></li>

                        <?php
                    }
                }
                ?>
                <li class="<?php echo $script_openclaims;?>"><a href="open_claims.php">Open Claims <span class="uk-badge sub_badge" id="open_claims"> 0</span></a></li>
                <li class="hidde">New Claims <span class="uk-badge sub_badge" style="background-color: #84c4dc !important;" id="new_case"> 0</span></li>
                <?php

                if($control->isSplit())
                {

                    ?>
                    <li class="<?php echo $script_splitclaims;?>"><a href="splits.php">Claims </a></li>
                    <li class="<?php echo $script_oldsplits;?>"><a href="old_splits.php">Old Claims </a></li>
                    <?php
                    if($control->isGapCoverAdmin())
                    {
                        ?>
                        <li class="<?php echo $script_dashsplits;?>"><a href="split_dashboard.php">Claims Dashboard </a></li>
                        <?php
                    }
                }
                if($control->loggedAs()=="Kaelo" || $control->loggedAs()=="Western")
                {
                    ?>
                    <li class="<?php echo $script_switch;?>"><a href="switch_claims.php">Switch Claims </a></li>
                    <li class="<?php echo $script_oldswitch;?>"><a href="old_switch.php">Old Switch Claims</a></li>
                    <?php
                    if($control->isBi())
                    {
                    ?>
                <li class="<?php echo $script_oldswitch;?>"><a href="coding/bi.php">BI Tool</a></li>

                <?php
                    }
                }
                   if($control->isTopLevel())
                    {
                        ?>
                        <li class=""><a href="admin/claims-dash.php">New Dash</a></li>                      

                        <?php
                    }
                ?>
            </ul>
        </div>

    </div>
    <div class="load-bar">
  <div class="bar"></div>
  <div class="bar"></div>
  <div class="bar"></div>
</div>
</nav>

<ul class="sidenav" id="mobile-demo">
    <?php
    if($control->isGapCover())
    {
        ?>
        <li class="<?php echo $script_addclaim;?>"><a href="gap_claim.php">Add New Claim</a></li>
        <?php
    }
    if($control->isInternal())
    {
        ?>
        <li><a href="../admin/admin_main/claims.php">Reports Dashboard</a></li>
        <li class="<?php echo $script_addclaim;?>"><a href="add_case.php">Add New Claim</a></li>
        <li class="<?php echo $script_adddoctor;?>"><a href="add_doctor.php">Add New Doctor</a></li>
        <li class="<?php echo $script_openclaims;?>"><a href="open_claims.php">Open Claims</a></li>
        <li class="<?php echo $script_consent;?>"><a href="consent_forms.php">Consent Forms</a></li>
        <?php
    }
    ?>
    <li class="<?php echo $script_icd10;?>"><a href="icd10_lookup.php">ICD10 Lookup</a></li>
    <li class="<?php echo $script_search;?>"><a class="#">Search <i class="material-icons right">arrow_drop_down</i></a>
        <div uk-dropdown>
            <ul class="uk-nav uk-dropdown-nav">
                <li class="" style="border-bottom: 1px solid #54bf99"><a href="search.php"><span uk-icon="search"></span> Search Claim</a></li>
                <?php
                if($control->isInternal())
                {
                    ?>
                    <br>
                    <li class="" style="border-bottom: 1px solid #54bf99"><a href="search_doctor.php"><span uk-icon="git-branch"></span> Search Doctor</a></li><br>
                    <li class="" style="border-bottom: 1px solid #54bf99"><a href="issues.php"><span uk-icon="future"></span>MCA Help Desk</a></li>
                    <?php
                }
                ?>
            </ul>
        </div></li>
    <li class="<?php echo $script_user;?>"><a class="#"><span style="color: #84c4dc !important;">| <?php echo $control->loggedAs();?> <i class="material-icons right">account_circle</i></span> </a>
        <div uk-dropdown style="float: right; width: 100% !important;">
            <ul class="uk-nav uk-dropdown-nav">
                <li class="" style="border-bottom: 1px solid #54bf99"><a href="mca_change_pass.php"><span uk-icon="lock"></span> Change Password</a></li><br>
                <li class="" style="border-bottom: 1px solid #54bf99"><a href="logout.php"><span uk-icon="sign-out"></span> Logout</a></li>
            </ul>
        </div></li>
</ul>



