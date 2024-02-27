<?php
session_start();
?>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="bootstrap3/css/bootstrap.min.css">
    <script src="bootstrap3/js/bootstrap.min.js"></script>
    <script src="jquery/jquery.min.js"></script>
    <script src="js/jquery-1.12.4.js"></script>
 <link rel="stylesheet" href="admin_main/plugins/datatables-bs4/css/dataTables.bootstrap4.css">  <!-- Theme style -->
    <script src="admin_main/plugins/datatables/jquery.dataTables.js"></script>
    <script src="admin_main/plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
    <script src="js/newCase.js"></script>
    <script src="js/search.js"></script>
    <link href="w3/w3.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="uikit/css/uikit.min.css" />
    <script src="uikit/js/uikit.min.js"></script>
    <script src="uikit/js/uikit-icons.min.js"></script>
    <style type="text/css">
        <!--
        .tab1 { margin-left: 200px; }
        -->

        .linkButton {
            background: none;
            border: none;
            color: #0066ff;
            text-decoration: underline;
            cursor: pointer;
        }
        .xx{
            font-weight: bolder;
        }
        #tb{
        width: 70%;
       margin-left: 200px;
    }
    </style>
    <title>Med ClaimAssist: List of Cases</title>
</head>

<body>

<?php
//error_reporting(0);
include("header.php");
echo"<br><br><br><br><br><br>";
include("searchClass.php");

$openCases=new Search();
$openCases->quickSearch();
?>
<hr>
<?php
include('footer.php');
?>
</body>
</html>