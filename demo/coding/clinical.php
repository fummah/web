<?php
session_start();
if(isset($_SESSION['logxged']) && !empty($_SESSION['logxged']))
{

}
else{
    //die("Connection error");
}
?>
<title>MCA BI Tool</title>
<link rel="stylesheet" href="../../admin/admin_main/plugins/fontawesome-free/css/all.min.css">
<link rel="stylesheet" href="../../admin/admin_main/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
<link rel="stylesheet" href="../../admin/admin_main/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="../../admin/admin_main/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<link rel="stylesheet" href="../../admin/admin_main/dist/css/adminlte.min.css">
<link rel="stylesheet" href="../../admin/admin_main/plugins/daterangepicker/daterangepicker.css">
<script src="../../admin/admin_main/plugins/jquery/jquery.min.js"></script>
<script src="../../admin/admin_main/plugins/jquery-ui/jquery-ui.min.js"></script>
<script src="../../admin/admin_main/plugins/moment/moment.min.js"></script>
<script src="../../admin/admin_main/plugins/daterangepicker/daterangepicker.js"></script>
<script src="../../admin/admin_main/plugins/select2/js/select2.full.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?sensor=false&callback=myMap"></script>
<link rel="stylesheet" href="../css/uikit.min.css" />
<script src="../js/uikit.min.js"></script>
<script src="../js/uikit-icons.min.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script src="clinical.js"></script>
<script src="dist/jquery-simple-tree-table.js"></script>
<script src="jquery-sortable-min.js"></script>
<script src="jquery.tablesorter.min.js"></script>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@250&display=swap');
    .in { font-size: 1em; } /* prevent zoom in mobile */

    #sort-it>ol {
        /* list style is faked with number inputs */
        list-style: none;
        padding: 0;
    }

    #sort-it>ol>li {
        position: relative;
        min-height: 1em;
        cursor: move;
        padding: .5em .5em .5em 2.5em;
        background: #eee;
        border: 1px solid #ccc;
        margin: .25em 0;
        border-radius: .25em;
        max-width: 14em;
    }

    #sort-it>ol>li .in {
        /* Move these to visually fake the ol numbers */
        position: absolute;
        width: 1.75em;
        left: .25em;
        top: .25em;
        border: 0;
        text-align: center;
        background: transparent;
        color: cadetblue;
    }

    #sort-it>ol>li label {
        /* visually hidden offscreen so it still benefits screen readers */
        position: absolute;
        left: -9999px;
    }

    /* sortable plugin styles when dragged */
    .dragged {
        position: absolute;
        opacity: 0.5;
        z-index: 2000;
    }

    #sort-it>ol>li.placeholder {
        position: relative;
        background: red;
    }
    .main{
        border: 1px solid #54bc9c;
        width: 100% !important;
        padding: 10px;
        position: relative;
        margin-left: auto;
        margin-right: auto;
        border-radius: 10px;
    }
    .firstPanel{
        background-color: #54bc9c
    }
    .daterange{
        border: 1px solid #54bc9c;
    }
    th{
        color: #54bc9c;
        font-weight: bolder;
        cursor: pointer;
    }
    .opt1{
        width: 100% !important;
        color: white;
        margin-bottom: 4px !important;
        margin-top: 4px !important;
    }
    .opt1:hover{
        color : darkblue !important;
        border:1px solid cadetblue !important;
        transition: 0.9s;
    }
    .downmenu{
        background-color: white !important;
        color: #54bf99;
        padding: 8px !important;
        display: none;
    }
    .togglein{
        display: block;
    }

    .main{
        font-family: 'Montserrat', sans-serif !important;
    }
    ::-webkit-scrollbar {
        width: 10px;
    }

    /* Track */
    ::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    /* Handle */
    ::-webkit-scrollbar-thumb {
        background: lightgrey;
    }

    /* Handle on hover */
    ::-webkit-scrollbar-thumb:hover {
        background: lightgrey;
    }
    table{
        border:1px solid black;border-collapse:collapse;
        width: 100%;
    }
    td,th{
        border:1px solid #54bf99;
        padding: 7px;
    }
    .mydivs{
        position: relative;
        margin-right: auto;
        margin-left: auto;
    }
    div.scroll {


        overflow-x: auto;
        overflow-y: hidden;
        white-space: nowrap;
    }
    .et_pb_texta{
        font-family: 'Montserrat',Helvetica,Arial,Lucida,sans-serif;
        font-weight: 300;
        line-height: 1.6em;
        font-size: 20px;

    }
    .et_pb_textr{
        font-family: 'Montserrat',Helvetica,Arial,Lucida,sans-serif;
        font-weight: 300;
        line-height: 1.6em;
        font-size: 14px;

    }
    #country-list{
        float:left;
        list-style:none;
        width:190px;
        z-index: 3;
        padding: 2px;
        position: absolute;
        border:#eee 1px solid;
    }
    #country-list li{
        padding: 10px;
        background: #54bf99;
        border-bottom: #E1E1E1 1px solid;
        z-index: 3;
    }
    #country-list li:hover{
        background:lightblue;
        cursor: pointer;
        -webkit-transition: background-color 300ms linear;
        -ms-transition: background-color 300ms linear;
        transition: background-color 300ms linear;
        color: #54bf99;
    }
    .upload-container {
            width: 100%;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .drag-area {
            border: 2px dashed #54bf99;
            padding: 30px;
            border-radius: 10px;
            background: #f1f8ff;
            cursor: pointer;
            transition: 0.3s;
        }

        .drag-area.active {
            background: #d5ebff;
        }

        .drag-area h3 {
            font-size: 16px;
            margin-bottom: 10px;
        }

        .drag-area input {
            display: none;
        }
        .file-preview {
            margin-top: 10px;
            font-size: 14px;
            color: #555;
        }
          table {
      border-collapse: collapse;
      width: 100%;
    }

    th, td {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: left;
    }

    th {
      position: sticky;
      top: 0;
      background-color: #f2f2f2;
      z-index: 1;
    }

    tbody tr:nth-child(even) {
      background-color: #f9f9f9;
    }

    tbody {
      max-height: 800px;
      overflow-y: auto;
      display: block;
    }

    thead, tbody tr {
      display: table;
      width: 100%;
      table-layout: fixed;
    }
</style>
<!--http://localhost/demo/coding/bi.php-->
<?php
define("access",true);
//include "../classes/apiClass.php";
//use mcaAPI\apiClass as myAPI;
//$api= new myAPI();
?>
<div class="main">
    <div class="row">
        <div class="col-sm-2 firstPanel">
            <div class="row" uk-sticky="offset: 10">
                <div class="col-sm-12">

                    <div class="form-group">
                        <hr>
                        <div class="input-group" style="width:100% !important;">
                            <button style="width:100% !important;" type="button" class="uk-button uk-button-secondary uk-button-small float-right daterange" id="daterange-btn">
                                <span uk-icon="calendar"></span> Date range picker
                                <span uk-icon="chevron-down"></span>
                            </button>
                        </div>
                    </div>
                    <input type="hidden" id="dat1">
                    <input type="hidden" id="dat2">
                    <p align="center" style="border-bottom: 1px solid mediumseagreen; text-align: center; color: white; font-weight: bolder;" class="text" id="datetxt"></p>
                    <hr>
                      <div class="uk-margin">
            <select class="uk-select" aria-label="Select">
                <option>Select File</option>
                <option value="Kaelo_Claims_Data_2025-06-15.csv">Kaelo_Claims_Data_2025-06-15.csv</option>
                <option value="Kaelo_Claims_Data_2025-06-14.csv">Kaelo_Claims_Data_2025-06-14.csv</option>
                <option value="Kaelo_Claims_Data_2025-06-13.csv">Kaelo_Claims_Data_2025-06-13.csv</option>
                <option value="Kaelo_Claims_Data_2025-06-12.csv">Kaelo_Claims_Data_2025-06-12.csv</option>
                <option value="Kaelo_Claims_Data_2025-06-11.csv">Kaelo_Claims_Data_2025-06-11.csv</option>
            </select>
        </div>
        <!--
                    <div class="uk-margin">
                        <form class="uk-search uk-search-default">

                            <span class="uk-search-icon-flip" uk-search-icon></span>
                             <input style="background-color: white !important; width:100% !important;" class="uk-search-input" type="search" id="myInput" placeholder="Search..." onkeyup="searchTable()" aria-label="Search">
                        </form>
                    </div>
                    -->
                    <div class="upload-container">
        <div class="drag-area" id="drop-area">
        
            <h3>Drag & Drop to Upload Flyer</h3>
            <p>or click to select Flyer</p>
            <input type="file" id="fileInput">
        </div>
        <div class="file-preview" id="file-name">No file selected</div>
    </div> 
                    <div class="uk-placeholder" style="color: #54bf99; font-weight: bolder; padding: 12px 12px 12px 12px !important; height: 550px !important; overflow-y:scroll !important;">

                        <ol id="top_panel1">
                        </ol>
<button class="uk-button uk-button-danger">Generate Report</button><hr/>
<button class="uk-button uk-button-danger">Analyse</button><hr/>
<button class="uk-button uk-button-danger">Download</button>
                    </div>

                </div>


            </div>
        </div>
        <div class="col-sm-10 uk-placeholder">

            <div class="row" style="width: 100% !important;">
                <div class="col-sm-12" style="background-color: white; width: 100% !important;">
                    <?php
include("pivot.php");
                    ?>
                  <p align="center"><button class="uk-button uk-button-secondary">View Claims</button></p>  
  <table style="display: none;">
    <thead>
      <tr>
        <th>Member Number</th>
        <th>Dependent Number</th>
        <th>Date of Birth</th>
        <th>Patient ID Number</th>
        <th>Patient Gender</th>
        <th>ICD10</th>
        <th>ICD10 Description</th>
        <th>Practice Name</th>
        <th>Practice Number</th>
        <th>Discipline Code</th>
        <th>Provider Group</th>
        <th>Practice Type</th>
      </tr>
    </thead>
    <tbody>
      <!-- Sample rows -->
      <tr><td>MN001</td><td>1</td><td>1990-01-01</td><td>PID001</td><td>Male</td><td>A00</td><td>Cholera</td><td>HealthCare Ltd</td><td>PN001</td><td>DC001</td><td>Group A</td><td>GP</td></tr>
      <tr><td>MN002</td><td>2</td><td>1985-05-12</td><td>PID002</td><td>Female</td><td>B01</td><td>Varicella</td><td>Wellness Inc</td><td>PN002</td><td>DC002</td><td>Group B</td><td>Dentist</td></tr>
      <tr><td>MN003</td><td>1</td><td>1978-07-08</td><td>PID003</td><td>Male</td><td>C02</td><td>Malignant neoplasm of other parts of tongue</td><td>Medic Plus</td><td>PN003</td><td>DC003</td><td>Group A</td><td>Specialist</td></tr>
      <tr><td>MN004</td><td>3</td><td>1992-03-25</td><td>PID004</td><td>Female</td><td>D03</td><td>Melanoma in situ</td><td>City Clinic</td><td>PN004</td><td>DC004</td><td>Group C</td><td>Dermatologist</td></tr>
      <tr><td>MN005</td><td>1</td><td>1989-11-30</td><td>PID005</td><td>Male</td><td>E11</td><td>Type 2 diabetes mellitus</td><td>Alpha Health</td><td>PN005</td><td>DC005</td><td>Group B</td><td>GP</td></tr>
      <tr><td>MN006</td><td>2</td><td>1994-06-15</td><td>PID006</td><td>Female</td><td>F32</td><td>Major depressive disorder</td><td>MindCare</td><td>PN006</td><td>DC006</td><td>Group D</td><td>Psychologist</td></tr>
      <tr><td>MN007</td><td>1</td><td>2001-09-23</td><td>PID007</td><td>Male</td><td>G40</td><td>Epilepsy</td><td>NeuroLife</td><td>PN007</td><td>DC007</td><td>Group A</td><td>Neurologist</td></tr>
      <tr><td>MN008</td><td>4</td><td>1997-12-05</td><td>PID008</td><td>Female</td><td>H10</td><td>Conjunctivitis</td><td>EyeCare</td><td>PN008</td><td>DC008</td><td>Group C</td><td>Ophthalmologist</td></tr>
      <tr><td>MN009</td><td>1</td><td>1980-08-19</td><td>PID009</td><td>Male</td><td>I10</td><td>Essential hypertension</td><td>Cardio Clinic</td><td>PN009</td><td>DC009</td><td>Group B</td><td>Cardiologist</td></tr>
      <tr><td>MN010</td><td>2</td><td>1995-02-28</td><td>PID010</td><td>Female</td><td>J20</td><td>Acute bronchitis</td><td>Respira Health</td><td>PN010</td><td>DC010</td><td>Group A</td><td>Pulmonologist</td></tr>
      <tr><td>MN011</td><td>3</td><td>1987-04-11</td><td>PID011</td><td>Male</td><td>K21</td><td>Gastro-esophageal reflux</td><td>GastroPlus</td><td>PN011</td><td>DC011</td><td>Group D</td><td>Gastroenterologist</td></tr>
      <tr><td>MN012</td><td>1</td><td>1991-10-07</td><td>PID012</td><td>Female</td><td>L40</td><td>Psoriasis</td><td>SkinLab</td><td>PN012</td><td>DC012</td><td>Group C</td><td>Dermatologist</td></tr>
      <tr><td>MN013</td><td>2</td><td>1983-06-03</td><td>PID013</td><td>Male</td><td>M54</td><td>Back pain</td><td>OrthoMed</td><td>PN013</td><td>DC013</td><td>Group B</td><td>Orthopedic</td></tr>
      <tr><td>MN014</td><td>1</td><td>1996-01-17</td><td>PID014</td><td>Female</td><td>N30</td><td>Cystitis</td><td>UroHealth</td><td>PN014</td><td>DC014</td><td>Group A</td><td>Urologist</td></tr>
      <tr><td>MN015</td><td>4</td><td>2000-07-21</td><td>PID015</td><td>Male</td><td>O80</td><td>Normal delivery</td><td>Maternity Care</td><td>PN015</td><td>DC015</td><td>Group D</td><td>Obstetrician</td></tr>
    <tr><td>MN005</td><td>1</td><td>1989-11-30</td><td>PID005</td><td>Male</td><td>E11</td><td>Type 2 diabetes mellitus</td><td>Alpha Health</td><td>PN005</td><td>DC005</td><td>Group B</td><td>GP</td></tr>
      <tr><td>MN006</td><td>2</td><td>1994-06-15</td><td>PID006</td><td>Female</td><td>F32</td><td>Major depressive disorder</td><td>MindCare</td><td>PN006</td><td>DC006</td><td>Group D</td><td>Psychologist</td></tr>
      <tr><td>MN007</td><td>1</td><td>2001-09-23</td><td>PID007</td><td>Male</td><td>G40</td><td>Epilepsy</td><td>NeuroLife</td><td>PN007</td><td>DC007</td><td>Group A</td><td>Neurologist</td></tr>
      <tr><td>MN008</td><td>4</td><td>1997-12-05</td><td>PID008</td><td>Female</td><td>H10</td><td>Conjunctivitis</td><td>EyeCare</td><td>PN008</td><td>DC008</td><td>Group C</td><td>Ophthalmologist</td></tr>
      <tr><td>MN009</td><td>1</td><td>1980-08-19</td><td>PID009</td><td>Male</td><td>I10</td><td>Essential hypertension</td><td>Cardio Clinic</td><td>PN009</td><td>DC009</td><td>Group B</td><td>Cardiologist</td></tr>
      <tr><td>MN010</td><td>2</td><td>1995-02-28</td><td>PID010</td><td>Female</td><td>J20</td><td>Acute bronchitis</td><td>Respira Health</td><td>PN010</td><td>DC010</td><td>Group A</td><td>Pulmonologist</td></tr>
      <tr><td>MN011</td><td>3</td><td>1987-04-11</td><td>PID011</td><td>Male</td><td>K21</td><td>Gastro-esophageal reflux</td><td>GastroPlus</td><td>PN011</td><td>DC011</td><td>Group D</td><td>Gastroenterologist</td></tr>
      <tr><td>MN012</td><td>1</td><td>1991-10-07</td><td>PID012</td><td>Female</td><td>L40</td><td>Psoriasis</td><td>SkinLab</td><td>PN012</td><td>DC012</td><td>Group C</td><td>Dermatologist</td></tr>
      <tr><td>MN013</td><td>2</td><td>1983-06-03</td><td>PID013</td><td>Male</td><td>M54</td><td>Back pain</td><td>OrthoMed</td><td>PN013</td><td>DC013</td><td>Group B</td><td>Orthopedic</td></tr>
      <tr><td>MN014</td><td>1</td><td>1996-01-17</td><td>PID014</td><td>Female</td><td>N30</td><td>Cystitis</td><td>UroHealth</td><td>PN014</td><td>DC014</td><td>Group A</td><td>Urologist</td></tr>
      <tr><td>MN015</td><td>4</td><td>2000-07-21</td><td>PID015</td><td>Male</td><td>O80</td><td>Normal delivery</td><td>Maternity Care</td><td>PN015</td><td>DC015</td><td>Group D</td><td>Obstetrician</td></tr>
 
    </tbody>
  </table>
         
            </div>
            </div>
        </div>
        <div class="col-sm-2 uk-placeholder sidebar" style="display: none">

            <div class="row" style="padding-bottom: 10px !important;">

                <div class="col-sm-12 sidebarcontent" style="color:cadetblue">
                  <p><u>Make selection for <span id="sidename"></span></u></p>
                    <div class="uk-margin"><form class="uk-search uk-search-default">
                        <span class="uk-search-icon-flip" uk-search-icon></span>
                        <input style="background-color: white !important; width:100% !important;" class="uk-search-input" type="search" id="searching" placeholder="Search">
                            <span id="suggesstion-box" class="et_pb_module et_pb_text et_pb_text_3  et_pb_text_align_left et_pb_bg_layout_light"></span>
                        </form>
                        </div>
<div class="myrad">
<label><input class="uk-radio" type="radio" name="myrad" value='Top 10' checked> Top 10</label><br>
<label><input class="uk-radio" type="radio" name="myrad" value='Bottom 10'> Bottom 10</label>
</div>

                </div>

            </div>
            <div class="row" style="border-top: 1px dashed lightgrey;padding-top: 10px !important;">
                <p>Select Medical Aid</p>
                <div class="col-sm-12 sidebarcontent1" >
                    <select class="uk-select select2bs4" multiple="multiple" id="medical_scheme" style="width: 100% !important;">

                    </select>
                </div>
                <input type="hidden" id="txtup" value="Top 10">
                <input type="hidden" id="txtstatus" value="All Claims">
            </div><hr>
            <p>Select Status</p>
            <div class="mystatus">
                <label><input class="uk-radio" type="radio" name="mystatus" value='All Claims' checked> All Claims</label><br>
                <label><input class="uk-radio" type="radio" name="mystatus" value='Approved Claims'> Approved Claims</label>
                <label><input class="uk-radio" type="radio" name="mystatus" value='Rejected Claims'> Rejected Claims</label>
            </div>
            <hr>
            <p align="center"></p> <a href="bi.php"><button title="Reset Everything" class="uk-button uk-button-secondary" uk-icon="refresh"></button></a> <a href="../logout.php"><button title="Logout" class="uk-button uk-button-danger" uk-icon="unlock"></button></a></div>
        </div>
    </div>

    <script>
        $('#myTable').simpleTreeTable({
            opened:'none',
        });
        $(document).on('click','.myTable th',function(){
            $("th").css("background-color", "white");
            $(this).css("background-color","black");
            console.log("click 1");
            $(".myTable").tablesorter();
        });
    </script>
    <script type="text/javascript">

const dropArea = document.getElementById('drop-area');
        const fileInput = document.getElementById('fileInput');
        const fileNameDisplay = document.getElementById('file-name');
        const uploadBtn = document.getElementById('uploadBtn');

        dropArea.addEventListener('click', () => fileInput.click());

        fileInput.addEventListener('change', (e) => {
            let file = e.target.files[0];
            if (file) {
                fileNameDisplay.textContent = `Selected File: ${file.name}`;
            }
        });

        dropArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropArea.classList.add('active');
        });

        dropArea.addEventListener('dragleave', () => {
            dropArea.classList.remove('active');
        });

        dropArea.addEventListener('drop', (e) => {
            e.preventDefault();
            dropArea.classList.remove('active');
            let file = e.dataTransfer.files[0];
            if (file) {
                fileNameDisplay.textContent = `Selected File: ${file.name}`;
                fileInput.files = e.dataTransfer.files;
            }
        });

        uploadBtn.addEventListener('click', () => {
            alert("Uploading file: " + (fileInput.files[0] ? fileInput.files[0].name : "No file selected"));
        });

    </script>
