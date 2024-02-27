<?php
session_start();
define("access",true);
require "PHPExcel/Classes/PHPExcel.php";
require "PHPExcel/Classes/PHPExcel/Writer/Excel5.php";
include ("classes/controls.php");
$control=new controls();
$role=$control->myRole();
$username=$control->loggedAs();
    if (isset($_POST['download'])) {
        $search_value = validateXss($_POST['download_input']);
        $txt_catergory = (int)$_POST['cattext'];
        header("Content-Type: application/vnd.ms-excel");
        header("Content-disposition: attachment; filename=MCA_Claims.xls");
        $r = $_SESSION['user_id'];

         try {
             echo 'GAP COVER' . "\t" . 'POLICY NUMBER' . "\t" . 'CLAIM NUMBER' . "\t" . 'NAME' . "\t" . 'SURNAME' . "\t" . 'SCHEME' . "\t" . 'OPTION' . "\t" . 'PMB' . "\t" . 'EMERGENCY STATUS' . "\t" . 'TOTAL SAVINGS' . "\t" . 'DATE ENTERED' . "\t" . 'DATE CLOSED' . "\t" . 'USERNAME' . "\t" . 'CASE STATUS' . "\t" . 'GAP VALUE' . "\t" . 'UPLOADED BY' . "\t\n";
             foreach($control->viewAllClaims($role,0 ,100000000000,$username,$search_value,0,$txt_catergory) as $row)
             {
                 $name = htmlspecialchars(strtoupper($row[0]));
                 $surname = htmlspecialchars(strtoupper($row[1]));
                 $policy = htmlspecialchars(strtoupper($row[2]));
                 $claim_number = htmlspecialchars(strtoupper($row[3]));
                 $scheme_savings = htmlspecialchars($row[4]);
                 $medical_scheme = htmlspecialchars($row[5]);
                 $date_entered = htmlspecialchars($row["date_entered"]);
                 $date_closed = htmlspecialchars($row["date_closed"]);
                 $entered = htmlspecialchars($row[9]);
                 $client_name = htmlspecialchars($row["client_name"]);
                 $option = htmlspecialchars($row[6]);
                 $claim_status = htmlspecialchars($row[10]);
                 $claim_id = htmlspecialchars($row[11]);
                 $owner = htmlspecialchars($row[12]);
                 $discount_savings = htmlspecialchars($row[13]);
                 $scheme_number = htmlspecialchars($row[14]);
                 $pmb = htmlspecialchars($row["pmb"]);
                 $open = htmlspecialchars($row["Open"]);
                 $emergency = htmlspecialchars($row["emergency"]);
                 $uploaded_by = htmlspecialchars($row["entered_by"]);
                 $chargedamnt = htmlspecialchars($row["charged_amnt"]);
                 $schemevalue = htmlspecialchars($row["scheme_paid"]);
                 $gapvalue = $chargedamnt-$schemevalue;
                 $total="";
                     $pmb1 = "Yes";
                     if ($pmb == "0") {
                         $pmb1 = "No";
                     }
                     $open1 = "Closed";
                     $date_closed1 = $date_closed;
                     if ($open == 1) {
                         $open1 = "Open";
                         $date_closed1 = "Still Open";
                     }
                     $emergency1 = "---";
                     if ($emergency == "1") {
                         $emergency1 = "Yes";
                     } elseif ($emergency == "0") {
                         $emergency1 = "No";
                     } else {
                         $emergency1 = "Not Sure";
                     }

                     echo $client_name . "\t" . $policy . "\t" . $claim_number . "\t" . $name . "\t" . $surname . "\t" . $medical_scheme . "\t" . $option . "\t" . $pmb1 . "\t" . $emergency1 . "\t" . $total . "\t" . $date_entered . "\t" . $date_closed1 . "\t" . $username . "\t" . $open1 . "\t" . $gapvalue . "\t" . $uploaded_by . "\t\n";

                 }

         }
         catch (Exception $rr)
         {
             echo "There is an error ".$rr->getMessage();
         }
        }



    elseif ((isset($_POST['medswitch'])))
    {
        $from_date=$_POST["from"];
        $to_date=$_POST["to"];
        $det="1";
        if(!empty($from_date) && !empty($to_date))
        {
            $date = new DateTime($to_date);
            $date->modify('+1 day');
            $end_date=$date->format('Y-m-d');
            $det="a.date_entered>='$from_date' AND a.date_entered<'$end_date'";
        }
        $conn = connection("mca", "MCA_admin");
        $objPHPExcel = new PHPExcel();
// Set document properties
        $objPHPExcel->getProperties()->setCreator("Govinda")
            ->setLastModifiedBy("Govinda")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Result file");

// Add some data
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
//$html = "<b style='font-weight: bolder'>".'Year-Month' . "\t" . 'Number of Claims' . "\t" . 'Savings by Dr Discount' . "\t" . 'Savings by Scheme Paid' . "\t" . 'Total Savings' . "\t" . 'Value of Claims Referred' . "\t" . 'Percentage saved (of total referred)' . "\t" . 'Average time to close (days)' . "\t" . 'Claims Referred'  . "<b>" . "\n";


        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Claim Number')
            ->setCellValue('B1', 'Policy Number')
            ->setCellValue('C1', 'First Name')
            ->setCellValue('D1', 'Surname')
            ->setCellValue('E1', 'Cell Phone')
            ->setCellValue('F1', 'Telephone')
            ->setCellValue('G1', 'Email')
            ->setCellValue('H1', 'Medical Scheme')
            ->setCellValue('I1', 'Scheme Option')
            ->setCellValue('J1', 'Practice Number')
            ->setCellValue('K1', 'Doctor First Name')
            ->setCellValue('L1', 'Doctor Surname')
            ->setCellValue('M1', 'Tarrif Code')
            ->setCellValue('N1', 'Primary ICD10')
            ->setCellValue('O1', 'Primary ICD10 Description')
            ->setCellValue('P1', 'Treatment date')
            ->setCellValue('Q1', 'Claim Line Charged Amount')
            ->setCellValue('R1', 'Claim Line Scheme Amount Paid')
            ->setCellValue('S1', 'Date Entered')
            ->setCellValue('T1', 'Patient Name')
            ->setCellValue('U1', 'Reason Code')
            ->setCellValue('V1', 'Reason Description')
            ->setCellValue('W1', 'Medical Scheme Number')
            ->setCellValue('X1', 'GAP Insurer');
        $from = "A1";
        $to = "W1";
        $objPHPExcel->getActiveSheet()->getStyle("$from:$to")->getFont()->setBold(true);

        $rowCount = 2;
        try {
            //
            $searched="R";
            $str="SELECT b.claim_number,c.policy_number,c.first_name,c.surname,c.cell,c.telephone,c.email,c.medical_scheme,c.scheme_option,
a.practice_number,j.name_initials as doctor_first_name,j.surname as doctor_surname,tariff_code,primaryICDCode,primaryICDDescr,treatmentDate,
clmnline_charged_amnt,clmline_scheme_paid_amnt,a.date_entered,c.productName,c.product_code,c.scheme_number,b.charged_amnt,b.scheme_paid,b.gap,b.icd10,
b.icd10_desc,b.savings_scheme,b.savings_discount,a.id,b.Open,b.claim_type,kk.patient_name,a.reason_code,a.reason_description,c.scheme_number FROM `claim_line` as a inner join claim as b on a.mca_claim_id=b.claim_id inner join member as c on 
b.member_id=c.member_id INNER JOIN doctor_details as j ON a.practice_number=j.practice_number INNER JOIN patient as kk ON a.mca_claim_id=kk.claim_id WHERE c.client_id=1 AND b.senderId=10 AND b.claim_type<>:rr AND $det";
            //$sql->bindParam(':num', $r, PDO::PARAM_STR);
            $sql = $conn->prepare($str);
            $sql->bindParam(':rr', $searched, PDO::PARAM_STR);
            $sql->execute();
            $nu = $sql->rowCount();

            foreach ($sql->fetchAll() as $row) {
                $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, "$row[claim_number]");
                $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, "$row[policy_number]");
                $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, "$row[first_name]");
                $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, "$row[surname]");
                $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, "$row[cell]");
                $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, "$row[telephone]");
                $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, "$row[email]");
                $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, "$row[medical_scheme]");
                $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, "$row[scheme_option]");
                $objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, "$row[practice_number]");
                $objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, "$row[doctor_first_name]");
                $objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, "$row[surname]");
                $objPHPExcel->getActiveSheet()->SetCellValue('M' . $rowCount, "$row[tariff_code]");
                $objPHPExcel->getActiveSheet()->SetCellValue('N' . $rowCount, "$row[primaryICDCode]");
                $objPHPExcel->getActiveSheet()->SetCellValue('O' . $rowCount, "$row[primaryICDDescr]");
                $objPHPExcel->getActiveSheet()->SetCellValue('P' . $rowCount, "$row[treatmentDate]");
                $objPHPExcel->getActiveSheet()->SetCellValue('Q' . $rowCount, "$row[clmnline_charged_amnt]");
                $objPHPExcel->getActiveSheet()->SetCellValue('R' . $rowCount, "$row[clmline_scheme_paid_amnt]");
                $objPHPExcel->getActiveSheet()->SetCellValue('S' . $rowCount, "$row[date_entered]");
                $objPHPExcel->getActiveSheet()->SetCellValue('T' . $rowCount, "$row[patient_name]");
                $objPHPExcel->getActiveSheet()->SetCellValue('U' . $rowCount, "$row[reason_code]");
                $objPHPExcel->getActiveSheet()->SetCellValue('V' . $rowCount, "$row[reason_description]");
                $objPHPExcel->getActiveSheet()->SetCellValue('W' . $rowCount, "$row[scheme_number]");
                $objPHPExcel->getActiveSheet()->SetCellValue('X' . $rowCount, "Zestilife");

                $rowCount++;
                //echo "<span $vv>"."$row[month]" . "\t" . "$row[claims]" . "\t" . "$row[discount]" . "\t" . "$row[scheme]" . "\t" . "$row[total_savings]" . "\t" . "$row[charged]" . "\t" . "$row[percentage]" . "\t" . "$row[average]" . "\t" . "$row[total_referred] </span>" . "\n";

            }
        } catch (Exception $rr) {
            $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $rr->getMessage());

        }


// Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle("Claims from Mediswitch");
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
        header("Content-disposition: attachment; filename=Claims_from_Mediswitch.xls");
        header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        ve('report.xlsx');
    }

