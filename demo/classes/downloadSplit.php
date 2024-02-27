<?php
//error_reporting(0);
require "../../admin/PHPExcel/Classes/PHPExcel.php";
require "../../admin/PHPExcel/Classes/PHPExcel/Writer/Excel5.php";
session_start();
define("access",true);
require "controls.php";

$control=new controls();
$arralph=["A","B","C","D","E","F","G","H","I","J","K","L","M","N","M","O","P","Q","R","S","T","V"];
if(isset($_POST["xclaim_id"])) {
    $claim_id = (int)$_POST["xclaim_id"];
    $hospital_name = $_POST["xhospital_name"];
    $objPHPExcel = new PHPExcel();
// Set document properties
    $objPHPExcel->getProperties()->setCreator("Govinda")
        ->setLastModifiedBy("Govinda")
        ->setTitle("Office 2007 XLSX Test Document")
        ->setSubject("Office 2007 XLSX Test Document")
        ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
        ->setKeywords("office 2007 openxml php")
        ->setCategory("Test result file");

// Add some data
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
//$html = "<b style='font-weight: bolder'>".'Year-Month' . "\t" . 'Number of Claims' . "\t" . 'Savings by Dr Discount' . "\t" . 'Savings by Scheme Paid' . "\t" . 'Total Savings' . "\t" . 'Value of Claims Referred' . "\t" . 'Percentage saved (of total referred)' . "\t" . 'Average time to close (days)' . "\t" . 'Claims Referred'  . "<b>" . "\n";

    //``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, `
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'claiminsureditemadditionalinfo.claiminsureditemadditionalinfoid')
        ->setCellValue('B1', 'claiminsureditemadditionalinfo.servicedate')
        ->setCellValue('C1', 'claiminsureditemadditionalinfo.icdcode')
        ->setCellValue('D1', 'claiminsureditemadditionalinfo.codedescription')
        ->setCellValue('E1', 'claiminsureditemadditionalinfo.procedurecode')
        ->setCellValue('F1', 'claiminsureditemadditionalinfo.procedurename')
        ->setCellValue('G1', 'claiminsureditemadditionalinfo.proceduretype')
        ->setCellValue('H1', 'claiminsureditemadditionalinfo.linkedprocedurecodedescription')
        ->setCellValue('I1', 'claiminsureditemadditionalinfo.medicalschemepaidconcat')
        ->setCellValue('J1', 'claiminsureditemadditionalinfo.medicalschemepaidinkedprocedurecodesum')
        ->setCellValue('K1', 'claiminsureditemadditionalinfo.modifierpercentage')
        ->setCellValue('L1', 'claiminsureditemadditionalinfo.modifiervalue')
        ->setCellValue('M1', 'claiminsureditemadditionalinfo.amountcharged')
        ->setCellValue('N1', 'claiminsureditemadditionalinfo.medicalschemerateinput')
        ->setCellValue('O1', 'claiminsureditemadditionalinfo.medicalschemepaidinput')
        ->setCellValue('P1', 'claiminsureditemadditionalinfo.medicalschemerejectioncode')
        ->setCellValue('Q1', 'claiminsureditemadditionalinfo.medicalschemerejectionreason')
        ->setCellValue('R1', 'claiminsureditemadditionalinfo.rejectionreason');

    $from = "A1";
    $to = "R1";
    $objPHPExcel->getActiveSheet()->getStyle("$from:$to")->getFont()->setBold(true);
    $rowCount = 2;
    try {

        foreach ($control->viewSplitClaimlinesDoctor($claim_id,$hospital_name) as $row) {

            $icdarr=explode(",",$row["icdcode"]);
            $icd=$icdarr[0];
            $charged_amnt=str_replace(',', '.', $row["amountcharged"]);
            $scheme_rate=str_replace(',', '.',$row["medicalschemerateinput"]);
            $scheme_amnt=str_replace(',', '.',$row["medicalschemepaidinput"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount,"");
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount,$row["servicedate"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount,$icd);
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount,"");
            $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount,$row["procedurecode"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount,"");
            $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount,"");
            $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount,"");
            $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount,"");
            $objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount,"");
            $objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount,"");
            $objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount,"");
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('M'. $rowCount, $charged_amnt,PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('N'. $rowCount, $scheme_rate,PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('O'. $rowCount, $scheme_amnt,PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->SetCellValue('P' . $rowCount,"");
            $objPHPExcel->getActiveSheet()->SetCellValue('Q' . $rowCount,"");
            $objPHPExcel->getActiveSheet()->SetCellValue('R' . $rowCount,"");
            $rowCount++;
            //echo "<span $vv>"."$row[month]" . "\t" . "$row[claims]" . "\t" . "$row[discount]" . "\t" . "$row[scheme]" . "\t" . "$row[total_savings]" . "\t" . "$row[charged]" . "\t" . "$row[percentage]" . "\t" . "$row[average]" . "\t" . "$row[total_referred] </span>" . "\n";

        }
    } catch (Exception $rr) {
        $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $rr->getMessage());

    }


// Rename worksheet
    $objPHPExcel->getActiveSheet()->setTitle("claim_lines Report");
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $objPHPExcel->setActiveSheetIndex(0);

// Redirect output to a clients web browser (Excel5)
    header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
    header("Content-disposition: attachment; filename=claim_lines.xls");
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


elseif(isset($_POST["pracdwn"])) {
    $claim_id = (int)$_POST["claim_id"];
    $practice_number = $_POST["practice_number"];
    $policy_number = $_POST["policy_number"];
    $objPHPExcel = new PHPExcel();
// Set document properties
    $objPHPExcel->getProperties()->setCreator("Govinda")
        ->setLastModifiedBy("Govinda")
        ->setTitle("Office 2007 XLSX Test Document")
        ->setSubject("Office 2007 XLSX Test Document")
        ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
        ->setKeywords("office 2007 openxml php")
        ->setCategory("Test result file");

// Add some data
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
//$html = "<b style='font-weight: bolder'>".'Year-Month' . "\t" . 'Number of Claims' . "\t" . 'Savings by Dr Discount' . "\t" . 'Savings by Scheme Paid' . "\t" . 'Total Savings' . "\t" . 'Value of Claims Referred' . "\t" . 'Percentage saved (of total referred)' . "\t" . 'Average time to close (days)' . "\t" . 'Claims Referred'  . "<b>" . "\n";

    //``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, `
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'claiminsureditemadditionalinfo.claiminsureditemadditionalinfoid')
        ->setCellValue('B1', 'claiminsureditemadditionalinfo.servicedate')
        ->setCellValue('C1', 'claiminsureditemadditionalinfo.icdcode')
        ->setCellValue('D1', 'claiminsureditemadditionalinfo.codedescription')
        ->setCellValue('E1', 'claiminsureditemadditionalinfo.procedurecode')
        ->setCellValue('F1', 'claiminsureditemadditionalinfo.procedurename')
        ->setCellValue('G1', 'claiminsureditemadditionalinfo.proceduretype')
        ->setCellValue('H1', 'claiminsureditemadditionalinfo.linkedprocedurecodedescription')
        ->setCellValue('I1', 'claiminsureditemadditionalinfo.medicalschemepaidconcat')
        ->setCellValue('J1', 'claiminsureditemadditionalinfo.medicalschemepaidinkedprocedurecodesum')
        ->setCellValue('K1', 'claiminsureditemadditionalinfo.modifierpercentage')
        ->setCellValue('L1', 'claiminsureditemadditionalinfo.modifiervalue')
        ->setCellValue('M1', 'claiminsureditemadditionalinfo.amountcharged')
        ->setCellValue('N1', 'claiminsureditemadditionalinfo.medicalschemerateinput')
        ->setCellValue('O1', 'claiminsureditemadditionalinfo.medicalschemepaidinput')
        ->setCellValue('P1', 'claiminsureditemadditionalinfo.medicalschemerejectioncode')
        ->setCellValue('Q1', 'claiminsureditemadditionalinfo.medicalschemerejectionreason')
        ->setCellValue('R1', 'claiminsureditemadditionalinfo.rejectionreason');

    $from = "A1";
    $to = "R1";
    $objPHPExcel->getActiveSheet()->getStyle("$from:$to")->getFont()->setBold(true);
    $rowCount = 2;
    try {

        foreach ($control->viewSwitcClaims($claim_id,$practice_number) as $row) {

            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount,"");
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount,$row["treatmentDate1"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount,$row["primaryICDCode"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount,"");
            $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount,$row["tariff_code"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount,"");
            $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount,"");
            $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount,"");
            $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount,"");
            $objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount,"");
            $objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount,"");
            $objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount,"");
            $objPHPExcel->getActiveSheet()->SetCellValue('M' . $rowCount,$row["clmnline_charged_amnt"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('N' . $rowCount,$row["memberLiability"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('O' . $rowCount,$row["clmline_scheme_paid_amnt"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('P' . $rowCount,$row["reason_code"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('Q' . $rowCount,$row["reason_description"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('R' . $rowCount,"None");
            $rowCount++;
            //echo "<span $vv>"."$row[month]" . "\t" . "$row[claims]" . "\t" . "$row[discount]" . "\t" . "$row[scheme]" . "\t" . "$row[total_savings]" . "\t" . "$row[charged]" . "\t" . "$row[percentage]" . "\t" . "$row[average]" . "\t" . "$row[total_referred] </span>" . "\n";

        }
    } catch (Exception $rr) {
        $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $rr->getMessage());

    }


// Rename worksheet
    $objPHPExcel->getActiveSheet()->setTitle($policy_number);
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $objPHPExcel->setActiveSheetIndex(0);

// Redirect output to a client web browser (Excel5)
    header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
    header("Content-disposition: attachment; filename=$policy_number.xls");
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

elseif(isset($_POST["spldownload"])) {
    $startdate = $_POST["d1"];
    $enddate = $_POST["d2"];
    $status = $_POST["status"];
    $objPHPExcel = new PHPExcel();
// Set document properties
    $objPHPExcel->getProperties()->setCreator("Govinda")
        ->setLastModifiedBy("Govinda")
        ->setTitle("Office 2007 XLSX Test Document")
        ->setSubject("Office 2007 XLSX Test Document")
        ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
        ->setKeywords("office 2007 openxml php")
        ->setCategory("Test result file");

// Add some data
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'MCA ID')
        ->setCellValue('B1', 'Loyalty Number')
        ->setCellValue('C1', 'Member Name')
        ->setCellValue('D1', 'Procedure Date')
        ->setCellValue('E1', 'Admission date')
        ->setCellValue('F1', 'Discharge Date')
        ->setCellValue('G1', 'Date Entered')
        ->setCellValue('H1', 'File Name')
        ->setCellValue('I1', 'Status')
        ->setCellValue('J1', 'Date Completed')
        ->setCellValue('K1', 'Completed By')
        ->setCellValue('L1', 'Claim Number');

    $from = "A1";
    $to = "L1";
    $objPHPExcel->getActiveSheet()->getStyle("$from:$to")->getFont()->setBold(true);
    $rowCount = 2;
    try {

        foreach ($control->viewDownloadSplitCompleted($startdate,$enddate,$status) as $row) {

            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount,$row["claim_id"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount,$row["loyalty_number"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount,$row["beneficiary_name"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount,$row["procedure_date"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount,$row["procedure_date"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount,$row["discharge_date"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount,$row["date_entered"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount,$row["file_name"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount,$row["status"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount,$row["date_closed"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount,$row["closed_by"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount,$row["claim_number"]);
            $rowCount++;
        }
        
    } catch (Exception $rr) {
        $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $rr->getMessage());

    }


// Rename worksheet
    $objPHPExcel->getActiveSheet()->setTitle("Completed Claims");
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $objPHPExcel->setActiveSheetIndex(0);

// Redirect output to a client web browser (Excel5)
    header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
    header("Content-disposition: attachment; filename=completed_claims.xls");
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

elseif(isset($_POST["swittc"])) {
    $objPHPExcel = new PHPExcel();
// Set document properties
    $objPHPExcel->getProperties()->setCreator("Govinda")
        ->setLastModifiedBy("Govinda")
        ->setTitle("Office 2007 XLSX Test Document")
        ->setSubject("Office 2007 XLSX Test Document")
        ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
        ->setKeywords("office 2007 openxml php")
        ->setCategory("Test result file");
// Add some data
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
//$html = "<b style='font-weight: bolder'>".'Year-Month' . "\t" . 'Number of Claims' . "\t" . 'Savings by Dr Discount' . "\t" . 'Savings by Scheme Paid' . "\t" . 'Total Savings' . "\t" . 'Value of Claims Referred' . "\t" . 'Percentage saved (of total referred)' . "\t" . 'Average time to close (days)' . "\t" . 'Claims Referred'  . "<b>" . "\n";

    //``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, `
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'POLICY NUMBER')
        ->setCellValue('B1', 'MEMBERSHIP NUMBER')
        ->setCellValue('C1', 'BENEFICIARY NAME')
        ->setCellValue('D1', 'BENEFICIARY ID NUMBER')
        ->setCellValue('E1', 'ADMISSION DATE')
        ->setCellValue('F1', 'DISCHARGE DATE')
        ->setCellValue('G1', 'DATE ENTERED');


    $from = "A1";
    $to = "G1";
    $objPHPExcel->getActiveSheet()->getStyle("$from:$to")->getFont()->setBold(true);
    $rowCount = 2;
    try {

        foreach($control->viewSeamlessClaims(0 ,1000000,"",0,$control->loggedAs(),2) as $row) {

            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount,$row["policy_number"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount,$row["scheme_number"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount,$row["first_name"]." ".$row["surname"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount,$row["id_number"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount,$row["Service_Date"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount,$row["end_date"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount,$row["date_entered"]);
            $rowCount++;
            //echo "<span $vv>"."$row[month]" . "\t" . "$row[claims]" . "\t" . "$row[discount]" . "\t" . "$row[scheme]" . "\t" . "$row[total_savings]" . "\t" . "$row[charged]" . "\t" . "$row[percentage]" . "\t" . "$row[average]" . "\t" . "$row[total_referred] </span>" . "\n";

        }
    } catch (Exception $rr) {
        $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $rr->getMessage());

    }


// Rename worksheet
    $objPHPExcel->getActiveSheet()->setTitle("switch_claims Report");
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $objPHPExcel->setActiveSheetIndex(0);

// Redirect output to a clie web browser (Excel5)
    header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
    header("Content-disposition: attachment; filename=switch_claims.xls");
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

//////////////////////////////////////////////////////////////////////////////////////////





