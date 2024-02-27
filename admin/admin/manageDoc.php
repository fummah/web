<?php
session_start();
require_once ("validateAdmin.php");
?>
<title>Manage Documents</title>
<link rel="stylesheet" href="../bootstrap3/css/bootstrap.min.css">
<script src="../jquery/jquery.min.js"></script>
<script src="../bootstrap3/js/bootstrap.min.js"></script>
<script src="../jquery/jquery.js"></script>
<link href="../css/sb-admin.css" rel="stylesheet">
<!-- Morris Charts CSS -->
<link href="../css/plugins/morris.css" rel="stylesheet">
<!-- Custom Fonts -->
<link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<style>

    .b{
        width:300px;
        border-color:#00b3ee;
    }
    table tr:nth-child(even) {
        background-color: #eee;
    }
    table tr:nth-child(odd) {
        background-color: #fff;
    }
</style>

<script>
    $(document).ready(function () {
        $('#cleanUp').click(function () {
            $('#info').text("Please wait...");
            var obj={

                start_date:$('#start').val(),
                end_date:$('#end').val()

            };
            $('#load').show();
            $.ajax({
                url: "../ajaxPhp/deleting.php?identity=11",
                type: "POST",
                data: obj,
                success: function (data) {

                    $('#info').html(data);
                },
                error: function (jqXHR, exception) {
                    $('#info').html(jqXHR.responseText);
                    $('#info').css('color','red');
                }
            });

        });

        $('#srchBtn').click(function () {
            $('#info1').text("Please wait...");
            var obj={

                claim:$('#search').val()

            };

            $.ajax({
                url: "../ajaxPhp/deleting.php?identity=12",
                type: "POST",
                data: obj,
                success: function (data) {

                    $('#info1').html(data);
                },
                error: function (jqXHR, exception) {
                    $('#info1').html(jqXHR.responseText);
                    $('#info1').css('color','red');
                }
            });

        });



    });


    function  deleteAll(claim) {

        $('#info1').text("Please wait...");
        var obj={

            claim:claim

        };

        $.ajax({
            url: "../ajaxPhp/deleting.php?identity=13",
            type: "POST",
            data: obj,
            success: function (data) {

                $('#info1').html(data);
            },
            error: function (jqXHR, exception) {
                $('#info1').html(jqXHR.responseText);
                $('#info1').css('color','red');
            }
        });


    }
    function  deletex(docID) {

        $('#info2').text("Please wait...");
        var obj={

            doc:docID

        };

        $.ajax({
            url: "../ajaxPhp/deleting.php?identity=14",
            type: "POST",
            data: obj,
            success: function (data) {

                $('#info2').html(data);
                if($('#info2').text()=="Deleted")
                {
                    $('#info2').html(data);
                    $('#info2').css('color','green');
                    $('#'+docID).hide();
                }
                else
                {
                    $('#info2').html(data);
                    $('#info2').css('color','red');
                }
            },
            error: function (jqXHR, exception) {
                $('#info2').html(jqXHR.responseText);
                $('#info2').css('color','red');
            }
        });


    }
</script>
<body>
<div id="wrapper">

    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="navbar-header">
            <a class="navbar-brand" href="../index.php" style="color:#00ffff;">Med Claim Assist</a>
        </div>

        <?php
        require_once ('admin_header.php');
        ?>
        <!-- /.navbar-collapse -->
    </nav>

    <div id="page-wrapper">

        <div class="container-fluid">

            <!-- Page Heading -->
            <div class="row">
                <div class="col-lg-12">

                    <h2 align="center"><i class="fa fa-fw fa-book"></i><i class="fa fa-fw fa-book"></i><i class="fa fa-fw fa-book"></i>Documents<i class="fa fa-fw fa-book"></i><i class="fa fa-fw fa-book"></i><i class="fa fa-fw fa-book"></i></h2>

                    <div class="">
                        <h2 align="center" style="color: #"><u><?php require_once('execDoc.php');?></u></h2>
                        <h3 align="center" style="color: #"><u>Stored in : <i style="color: #3C510C"> -----/------</i> Path</u></h3>
                        <hr>
                        <div style="font-weight: bold; border: double; border-color: #; border-radius: 11px">
                            <ul class="nav nav-tabs">
                                <li class="active"><a data-toggle="tab" href="#menu2"><b style='color: #00b3ee'><span>Upload Web Clients</span></b></a></li>
                                <li><a data-toggle="tab" href="#home"><b style='color: #00b3ee'>Delete Single Case</b></a></li>
                                <li><a data-toggle="tab" href="#menu1"><b style='color: #00b3ee'><span>Delete Multiple Cases</span></b></a></li>

                            </ul>

                            <div class="tab-content">
                                <div id="menu2" class="tab-pane fade in active">


                                    <iframe src="../PHPExcel/upload_excel.php" scrolling="yes" frameborder="0" width="100%" height="300"></iframe>



                                </div>
                                <div id="home" class="tab-pane fade">



                                    <p align="center"><b style="color: mediumseagreen">Enter Claim Number</b><br><input type="text" id="search" class="form-control b"> <br> <button class="btn btn-info" id="srchBtn">Search Case</button></p>
                                    <p align="center" id="info2"></p>
                                    <p align="center" id="info1"></p>




                                </div>
                                <div id="menu1" class="tab-pane fade">

                                    <table align="center" width="50%">
                                        <caption><h4 style="color: mediumseagreen" align="center">Select the period that you want to clean up</h4></caption>
                                        <tr>
                                            <td>Start Date : <input type="date" id="start" class="form-control b"></td>
                                            <td style="float: right">End Date<input type="date" id="end" class="form-control b"></td>

                                        </tr></table>
                                    <h4 style="color:#00b3ee" align="center"><b>Note :</b> Only documents of closed cases with more than 90 days will be removed.</h4>
                                    <hr>
                                    <p align="center"> <button class="btn btn-info" id="cleanUp"><span style="color: red" class="glyphicon glyphicon-trash"></span><b>Clean Up</b><span style="color: red" class="glyphicon glyphicon-trash"></span></button></p>
                                    <p align="center" id="info"></p>
                                </div>

                                <hr>
                            </div></div>
                        <?php
                        include('footer.php');
                        ?>
                    </div>
                    <!-- /.container-fluid -->

                </div>
                <!-- /#page-wrapper -->

            </div>
            <!-- /#wrapper -->

            <!-- jQuery -->
            <script src="../js/jquery.js"></script>

            <!-- Bootstrap Core JavaScript -->
            <script src="../js/bootstrap.min.js"></script>

            <!-- Morris Charts JavaScript -->
            <script src="../js/plugins/morris/raphael.min.js"></script>
            <script src="../js/plugins/morris/morris.min.js"></script>
            <script src="../js/plugins/morris/morris-data.js"></script>

</body>
</html>
