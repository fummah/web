
<?php
ini_set('max_execution_time', 2700);
// Database configuration
   //$host = "sql14.cpt1.host-h.net";
   //$username = "greenwhc_11";
   //$password = "H5T2PCj6Evn3B0ieBqH0";
   //$database_name = "testing";

$host = "sql2.cpt1.host-h.net";
$username = "greenwhc_8";
$password = "CpN4WKc0UBmm0PrgN7Zx";
$database_name = "web_clients";

$time_start = microtime(true);
$host1 = "sql19.cpt1.host-h.net";
$username1 = "greenwhc_6";
$password1 = "Tw0Ocean$2018!";
    $database_name1 = "MCA_admin";
// Get connection object and set the charset
    $conn = mysqli_connect($host, $username, $password, $database_name);
    $conn1 = mysqli_connect($host1, $username1, $password1, $database_name1);
    $conn->set_charset("utf8");
    $conn1->set_charset("utf8");

    function download($arrr)
    {
        $arr_table=array();
        array_push($arr_table,$arrr);
        global $conn,$database_name1;
// Get All Table Names From the Database
//$arr_table=["users_information","doctor_details","schemes","scheme_options","clients","member","claim","doctors","claim_line","intervention","patient","feedback"];
        //$arr_table=["users_information","doctor_details","schemes","scheme_options","clients","member"];
        $tables = array();
        $sql = "SHOW TABLES";
        $result = mysqli_query($conn, $sql);

        while ($row = mysqli_fetch_row($result)) {
            $xtable=$row[0];
            if(in_array($xtable,$arr_table))
            {
                $tables[] = $xtable;
            }

        }
       //print_r($tables);
        $sqlScript = "";
        foreach ($arr_table as $table) {
            // Prepare SQLscript for creating table structure
            $query = "SHOW CREATE TABLE $table";
            $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_row($result);

$frn="ALTER TABLE $table drop foreign key ".$table."_ibfk_1";
$frn1="ALTER TABLE $table drop foreign key ".$table."_ibfk_2";
$frn2="ALTER TABLE $table drop foreign key ".$table."_ibfk_3";
            $sqlScript .= "\n\n" . $row[1] . ";\n\n";
            $sqlScript .= "\n\n" . $frn . ";\n\n";
            $sqlScript .= "\n\n" . $frn1 . ";\n\n";
            $sqlScript .= "\n\n" . $frn2 . ";\n\n";
            $query = "SELECT * FROM $table";
            $result = mysqli_query($conn, $query);
            $columnCount = mysqli_num_fields($result);

            // Prepare SQLscript for dumping data for each table
            for ($i = 0; $i < $columnCount; $i++) {
                while ($row = mysqli_fetch_row($result)) {
                    $sqlScript .= "INSERT INTO $table VALUES(";
                    for ($j = 0; $j < $columnCount; $j++) {
                        $row[$j] = $row[$j];

                        if (isset($row[$j])) {
                            $sqlScript .= '"' . $row[$j] . '"';
                        } else {
                            $sqlScript .= '""';
                        }
                        if ($j < ($columnCount - 1)) {
                            $sqlScript .= ',';
                        }
                    }
                    $sqlScript .= ");\n";
                }
            }

            $sqlScript .= "\n";

        }
  //echo $sqlScript;

        if (!empty($sqlScript)) {
// Save the SQL script to a backup file
            $backup_file_name = $database_name1.'.sql';
            $fileHandler = fopen($backup_file_name, 'w+');
            $number_of_lines = fwrite($fileHandler, $sqlScript);
            fclose($fileHandler);
        }

    }

    function upload($tablename)
    {
        global $conn1,$database_name1;

// Name of the file
        $filename = $database_name1.".sql";

//echo $filename;
// Connect to MySQL server

// Temporary variable, used to store current query
        $templine = '';
// Read in entire file
        $lines = file($filename);
// Loop through each line
        foreach ($lines as $line) {
// Skip it if it's a comment
            if (substr($line, 0, 2) == '--' || $line == '')
                continue;

// Add this line to the current segment
            $templine .= $line;
// If it has a semicolon at the end, it's the end of the query
            if (substr(trim($line), -1, 1) == ';') {
                // Perform the query
                $result = mysqli_query($conn1, $templine);
                //$res=mysqli_query($templine) or print('Error performing query \'<strong>' . $templine . '\': ' . mysql_error() . '<br /><br />');
                // Reset temp variable to empty
                $templine = '';
            }
        }
        echo "<hr><hr><h1 align='center' style='color: #0d92e1'><span style='color: yellow'> $tablename </span> table imported successfully</h1>";

    }

    function clearDB()
    {
        global  $conn1;

        $sql = "SHOW TABLES";
        $result = mysqli_query($conn1, $sql);

        while ($row = mysqli_fetch_row($result)) {
            $table = $row[0];

            $sql="DROP TABLE ".$table;
            if(mysqli_query($conn1, $sql))
            {
//echo "Sucessss";
            }
            else{
                echo "Failed";
            }
        }
    }

clearDB();
$arr_table=["users_information","doctor_details","schemes","scheme_options","clients","member","claim","doctors","claim_line","intervention","patient","feedback"];
    for($h=0;$h<count($arr_table);$h++)
    {
        download($arr_table[$h]);
        upload($arr_table[$h]);

        sleep(60);
    }

$time_end = microtime(true);
$execution_time = round(($time_end - $time_start)/60);

echo "<h1 align='center' style='color: red'>Processing time : <span style='color: green'> $execution_time </span>Mins</h1>"
?>
