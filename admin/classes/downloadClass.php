<?php
require "../PHPExcel/Classes/PHPExcel.php";
require "../PHPExcel/Classes/PHPExcel/Writer/Excel5.php";
session_start();
$arralph=["A","B","C","D","E","F","G","H","I","J","K","L","M","N","M","O","P","Q","R","S","T","V"];
$holidays=array("01-01","03-21","04-19","04-27","05-01","06-17","08-09","09-24","12-16","12-25","12-26");

if(isset($_POST["ctxt"])) {
    include ("../classes/reportsClass.php");
    $results=new reportsClass();
    $ctxt = $_POST["ctxt"];
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


    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'Year-Month')
        ->setCellValue('B1', 'Number of Claims')
        ->setCellValue('C1', 'Savings by Dr Discount')
        ->setCellValue('D1', 'Savings by Scheme Paid')
        ->setCellValue('E1', 'Total Savings')
        ->setCellValue('F1', 'Value of Claims Referred')
        ->setCellValue('G1', 'Percentage saved (of total referred)')
        ->setCellValue('H1', 'Average time to close (days)')
        ->setCellValue('I1', 'Claims Referred');

    $from = "A1";
    $to = "I1";
    $from1 = "A2";
    $to1 = "I2";
    $objPHPExcel->getActiveSheet()->getStyle("$from:$to")->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle("$from1:$to1")->getFont()->setBold(true);
    $rowCount = 2;

    try {

        $arr = json_decode($_POST['txt'], true);

        foreach ($arr as $row) {
            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount,"$row[month]");
            $objPHPExcel->getActiveSheet()->getStyle('A'.$rowCount)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::toFormattedString("$row[month]",PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD));
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, "$row[claims]");
            $objPHPExcel->getActiveSheet()->getStyle('B'.$rowCount)->getNumberFormat()->setFormatCode('0');
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, "$row[discount]");
            $objPHPExcel->getActiveSheet()->getStyle('C'.$rowCount)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, "$row[scheme]");
            $objPHPExcel->getActiveSheet()->getStyle('D'.$rowCount)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, "$row[total_savings]");
            $objPHPExcel->getActiveSheet()->getStyle('E'.$rowCount)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, "$row[charged]");
            $objPHPExcel->getActiveSheet()->getStyle('F'.$rowCount)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, ("$row[percentage]")/100);
            $objPHPExcel->getActiveSheet()->getStyle('G'.$rowCount)->getNumberFormat()->setFormatCode('0.0%');
            $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, "$row[average]");
            $objPHPExcel->getActiveSheet()->getStyle('H'.$rowCount)->getNumberFormat()->setFormatCode('0');
            $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, "$row[total_referred]");
            $objPHPExcel->getActiveSheet()->getStyle('I'.$rowCount)->getNumberFormat()->setFormatCode('0');
            $rowCount++;
            //echo "<span $vv>"."$row[month]" . "\t" . "$row[claims]" . "\t" . "$row[discount]" . "\t" . "$row[scheme]" . "\t" . "$row[total_savings]" . "\t" . "$row[charged]" . "\t" . "$row[percentage]" . "\t" . "$row[average]" . "\t" . "$row[total_referred] </span>" . "\n";

        }
    } catch (Exception $rr) {
        $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $rr->getMessage());

    }


// Rename worksheet
    $objPHPExcel->getActiveSheet()->setTitle($ctxt . " Report");
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $objPHPExcel->setActiveSheetIndex(0);
    header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
    header("Content-disposition: attachment; filename=$ctxt.xls");
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
elseif(isset($_POST["xxc"]))
{
    include ("../classes/reportsClass.php");
    $results=new reportsClass();
    $id = $_POST["xxc"];
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


    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'Claim number')
        ->setCellValue('B1', 'Claim number')
        ->setCellValue('C1', 'Day');
    $from = "A1";
    $to = "I1";
    $objPHPExcel->getActiveSheet()->getStyle("$from:$to")->getFont()->setBold(true);

    $rowCount = 2;

    try {
        foreach ($results->weekly($id) as $row) {
            $day=$row[1];
            $timestamp = strtotime($day);
            $day = date('l', $timestamp);
            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, "$row[0]");
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, "$row[1]");
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $day);

            $rowCount++;
            //echo "<span $vv>"."$row[month]" . "\t" . "$row[claims]" . "\t" . "$row[discount]" . "\t" . "$row[scheme]" . "\t" . "$row[total_savings]" . "\t" . "$row[charged]" . "\t" . "$row[percentage]" . "\t" . "$row[average]" . "\t" . "$row[total_referred] </span>" . "\n";

        }
    } catch (Exception $rr) {
        $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $rr->getMessage());

    }


// Rename worksheet
    $objPHPExcel->getActiveSheet()->setTitle("Weekly Report");
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $objPHPExcel->setActiveSheetIndex(0);
    header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
    header("Content-disposition: attachment; filename=Weekly_Report.xls");
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
elseif(isset($_POST["kpi"]))
{
    include ("../classes/reportsClass.php");
    $results=new reportsClass();
    $objPHPExcel = new PHPExcel();
    $mytitle="Savings_KPI";
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
        ->setCellValue('A1', 'Month')
        ->setCellValue('B1', 'Monthly Target')
        ->setCellValue('C1', 'Monthly Scores');

    $objPHPExcel->getActiveSheet()->mergeCells('C1:G1');
    $sheet = $objPHPExcel->getActiveSheet();
    $sheet->getStyle('C1')->getAlignment()->applyFromArray(
        array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
    );
    $cell=2;
    $arrusers=[];
    foreach ($results->getSpecialists() as $row) {
        $username=$row[0];
        array_push($arrusers,$username);
        $objPHPExcel->getActiveSheet()->SetCellValue($arralph[$cell].'2', $username);
        $cell++;
    }

    $from = "A1";
    $to = "G1";
    $from1 = "A2";
    $to1 = "G2";
    $objPHPExcel->getActiveSheet()->getStyle("$from:$to")->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle("$from1:$to1")->getFont()->setBold(true);

    $rowCount = 3;

    try {
        $row=$results->showTarget();

        $month=$row[0]["month"];
        for($j=0;$j<12;$j++)
        {
            $newdate = date("Y-m", strtotime("-$j months"));
            $cc=$j+3;
            if(isset($_POST["savings"]))
            {
                $target=number_format((double)$row[0]["savings_target"],2,'.',' ');
                $str="SELECT SUM(savings_scheme + savings_discount) as total FROM claim WHERE date_closed like :closed AND username=:user1 AND Open<>2";
                $objPHPExcel->getActiveSheet()->SetCellValue("A".$cc, $newdate);
                $objPHPExcel->getActiveSheet()->SetCellValue("B".$cc, $target);
                $cc1=2;
                for($k=0;$k<count($arrusers);$k++)
                {
                    $myuser=$arrusers[$k];
                    $tot=number_format($results->showExcelKPI($myuser,$newdate,$str),2,'.',' ');
                    $objPHPExcel->getActiveSheet()->SetCellValue($arralph[$cc1].$cc, $tot);
                    $cc1+=1;
                }
            }
            elseif (isset($_POST["closed"]))
            {
                $mytitle="Closed_Cases_KPI";
                $target=(int)$row[0]["closed_cases_target"];
                $str="SELECT COUNT(*) as total FROM claim WHERE date_closed like :closed AND username=:user1 AND Open=0";
                $objPHPExcel->getActiveSheet()->SetCellValue("A".$cc, $newdate);
                $objPHPExcel->getActiveSheet()->SetCellValue("B".$cc, $target);
                $cc1=2;
                for($k=0;$k<count($arrusers);$k++)
                {
                    $myuser=$arrusers[$k];
                    $tot=$results->showExcelKPI($myuser,$newdate,$str);
                    $objPHPExcel->getActiveSheet()->SetCellValue($arralph[$cc1].$cc, $tot);
                    $cc1+=1;
                }
            }
            elseif (isset($_POST["entered"]))
            {
                $mytitle="Cases-Entered_KPI";
                $target=(int)$row[0]["entered_cases_target"];
                $str="SELECT COUNT(*)  as total FROM claim WHERE date_entered like :closed AND username=:user1 AND Open<>2";
                $objPHPExcel->getActiveSheet()->SetCellValue("A".$cc, $newdate);
                $objPHPExcel->getActiveSheet()->SetCellValue("B".$cc, $target);
                $cc1=2;
                for($k=0;$k<count($arrusers);$k++)
                {
                    $myuser=$arrusers[$k];
                    $tot=$results->showExcelKPI($myuser,$newdate,$str);
                    $objPHPExcel->getActiveSheet()->SetCellValue($arralph[$cc1].$cc, $tot);
                    $cc1+=1;
                }
            }


        }

    } catch (Exception $rr) {
        $objPHPExcel->getActiveSheet()->SetCellValue('I1', $rr->getMessage());

    }


// Rename worksheet
    $objPHPExcel->getActiveSheet()->setTitle("$mytitle");
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $objPHPExcel->setActiveSheetIndex(0);
    header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
    header("Content-disposition: attachment; filename=$mytitle.xls");
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
    ve('kpi.xlsx');
}

elseif (isset($_POST["claims_client"]))
{
    include ("reportsClass.php");
    $results=new reportsClass();
    $frm=$_POST["from_client"]."-01";
    $to=$_POST["to_client"]."-31";
    $objPHPExcel = new PHPExcel();
    $status=(int)$_POST["status"];
    $opn=$status==1?"Open_Claims":"Closed_Claims";
    $mda=$status==1?"a.date_entered":"a.date_closed";
    $name_file="MCA_".$opn."_".$_POST["from_client"]."_to_".$_POST["to_client"].".xls";

// Set document properties
    $objPHPExcel->getProperties()->setCreator("Govinda")
        ->setLastModifiedBy("Govinda")
        ->setTitle("Office 2007 XLSX Test Document")
        ->setSubject("Office 2007 XLSX Test Document")
        ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
        ->setKeywords("office 2007 openxml php")
        ->setCategory("Test result file");
    //$date="2020-05";
    $client=$_SESSION['user_id'];
    $i=0;
    foreach ($results->selectDates($frm,$to) as $row)
    {
        $mydate=$row[0];
        claimsExcel($mydate,$client,$results,$objPHPExcel,$i,$mda);
        $i++;
    }

    $objPHPExcel->setActiveSheetIndex(0);
     header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
    header("Content-disposition: attachment; filename=$name_file");
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
    ve('claims.xlsx');

}
elseif (isset($_POST["myaverage"]))
{
    include ("../classes/reportsClass.php");
    $results=new reportsClass();
    $frmdate=$_POST["from_client"]."-01";
    $todate=$_POST["to_client"]."-31";
    $objPHPExcel = new PHPExcel();
    //echo $_POST["myaverage"];
// Set document properties
    $objPHPExcel->getProperties()->setCreator("Govinda")
        ->setLastModifiedBy("Govinda")
        ->setTitle("Office 2007 XLSX Test Document")
        ->setSubject("Office 2007 XLSX Test Document")
        ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
        ->setKeywords("office 2007 openxml php")
        ->setCategory("Test result file");

// Add some data
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);


//$html = "<b style='font-weight: bolder'>".'Year-Month' . "\t" . 'Claims Closed' . "\t" . 'Savings by Dr Discount' . "\t" . 'Savings by Scheme Paid' . "\t" . 'Total Savings' . "\t" . 'Value of Claims Referred' . "\t" . 'Percentage saved (of total referred)' . "\t" . 'Average time to close (days)' . "\t" . 'Claims Referred'  . "<b>" . "\n";


    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'Date Closed')
        ->setCellValue('B1', 'User')
        ->setCellValue('C1', 'Total Claims Closed')
        ->setCellValue('D1', 'Total claims received per user')
        ->setCellValue('E1', 'Average claims per day closed')
        ->setCellValue('F1', 'Average claims worked per month')
        ->setCellValue('G1', 'Average claims worked per day')
        ->setCellValue('H1', 'Working Days')
        ->setCellValue('I1', 'Avg Days')
        ->setCellValue('J1', 'Value of Claims')
        ->setCellValue('K1', 'Savings')
        ->setCellValue('L1', 'Percentage');
    $from = "A1";
    $to = "K1";
    $objPHPExcel->getActiveSheet()->getStyle("$from:$to")->getFont()->setBold(true);
    $rowCount = 2;

    //$arrq=array_reverse($results->selectDates($frmdate,$todate));

    // print_r($results->selectDates($frmdate,$todate));
    try {
        foreach ($results->selectDates($frmdate,$todate) as $row)
        {
            $mydate=$row[0];
            $strdate=$mydate."-01";
            $startDate=$strdate;
            $d = new DateTime( $strdate );
            $endDate=$d->format( 'Y-m-t' );

            if($mydate==date("Y-m"))
            {
                $startDate=date("Y-m")."-01";
                $endDate=date("Y-m-d");
            }
            $days=getWorkingDaysx($startDate,$endDate,$holidays);

            foreach ($results->getSpecialists() as $row1)
            {
                $myuser=$row1[0];
                $number_closed=$results->closedthisClaims($mydate,"username=:username",$myuser);
                $number_entered=$results->enteredthisClaims($mydate,"username=:username",$myuser);
                $number_workedon=$results->casesWorkedOn($mydate,"owner=:username",$myuser);
                $average_closed=round($number_closed/$days);
                $average_worked=round($number_workedon/$days);
                $claims_value=$results->claimValue($mydate,"username=:username",$myuser);
                //$claims_value=number_format($claims_value,2,',','');
                $savings=$results->savingsMain($mydate,"username=:username",$myuser);
                $perc=$claims_value>0?round(($savings/$claims_value)*100):0;
                $numclosed=$results->closedDate("username=:username",$myuser,$mydate);
                $average_days=$number_closed>0?round($numclosed/$number_closed):0;
                $average_days=(int)$average_days;
//$savings=number_format($savings,2,',','');

                $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $mydate);
                $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $myuser);
                $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $number_closed);
                $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $number_entered);
                $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $average_closed);
                $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $number_workedon);
                $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $average_worked);
                $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $days);
                $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $average_days);
                $objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, $claims_value);
                $objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, $savings);
                $objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, $perc);
                $rowCount++;
            }

        }

    } catch (Exception $rr) {
        $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $rr->getMessage());

    }


// Rename worksheet
    $objPHPExcel->getActiveSheet()->setTitle("Report");
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $objPHPExcel->setActiveSheetIndex(0);
    header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
    header("Content-disposition: attachment; filename=Report.xls");
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
elseif (isset($_POST["web_clients"]))
{
    include ("../classes/reportsClass.php");
    $results=new reportsClass();
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
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'First Name')
        ->setCellValue('B1', 'Last Name')
        ->setCellValue('C1', 'Broker Name')
        ->setCellValue('D1', 'Email Address')
        ->setCellValue('E1', 'Contact Number')
        ->setCellValue('F1', 'Medical scheme Name')
        ->setCellValue('G1', 'Date Entered');
    $from = "A1";
    $to = "G1";
    $objPHPExcel->getActiveSheet()->getStyle("$from:$to")->getFont()->setBold(true);
    $rowCount = 2;
    $broker_name=$_POST['broker_name'];
//print_r($results->web_brokers($broker_name));
    try {
        foreach ($results->web_brokers($broker_name) as $row)
        {
            $first_name=$row[0];
            $surname=$row[1];
            $broker_name=$row[2];
            $email=$row[3];
            $contact_number=$row[4];
            $scheme=$row[5];
            $date_entered=$row[6];

            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $first_name);
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $surname);
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $broker_name);
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $email);
            $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $contact_number);
            $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $scheme);
            $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $date_entered);

            $rowCount++;
        }



    } catch (Exception $rr) {
        $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $rr->getMessage());

    }


// Rename worksheet
    $objPHPExcel->getActiveSheet()->setTitle("Broker Report");
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $objPHPExcel->setActiveSheetIndex(0);
    header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
    header("Content-disposition: attachment; filename=broker_report.xls");
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
    ve('broker_report.xlsx');
}
elseif (isset($_POST["billing"]))
{
    $obj=$_POST["myobj"];
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
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'Client Name')
        ->setCellValue('B1', 'Recorded Savings')
        ->setCellValue('C1', 'Actual Savings')
        ->setCellValue('D1', 'VAT.Ex(15%)')
        ->setCellValue('E1', 'Base Fee')
        ->setCellValue('F1', 'Threshold 1')
        ->setCellValue('G1', 'Threshold 2')
        ->setCellValue('H1', 'Threshold 1 Value')
        ->setCellValue('I1', 'Threshold 2 Value')
        ->setCellValue('J1', '25%')
        ->setCellValue('K1', '30%/33%')
        ->setCellValue('L1', 'Switch Claims No.');
    $from = "A1";
    $to = "L1";
    $objPHPExcel->getActiveSheet()->getStyle("$from:$to")->getFont()->setBold(true);
    $rowCount = 2;
    $decodejson=json_decode($obj,true);
    try {
        foreach($decodejson as $row) {
            $client_name=$row["client"];
            $savings=$row["savings"];
            $cl=$row["cl"];
            $caret=$row["caret"];
            $actualsavings=$row["actualsavings"];
            $threshold=$row["threshold"];
            $vatexcl=$row["vatexcl"];
            $base_fee=$row["base_fee"];
            $threshold1=$row["threshold1"];
            $variance1=$row["variance1"];
            $variance=$row["variance"];
            $perc25=$row["perc25"];
            $perc30=$row["perc30"];
            $switch_number=$row["switch_number"];
            $client_id=$row["client_id"];

            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $client_name);
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $savings);
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $actualsavings);
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $vatexcl);
            $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $base_fee);
            $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $threshold1);
            $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $threshold);
            $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $variance1);
            $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $variance);
            $objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, $perc25);
            $objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, $perc30);
            $objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, $switch_number);
            $rowCount++;
        }

    } catch (Exception $rr) {
        $objPHPExcel->getActiveSheet()->SetCellValue('I1', $rr->getMessage());

    }
// Rename worksheet
    $objPHPExcel->getActiveSheet()->setTitle("Billing");
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $objPHPExcel->setActiveSheetIndex(0);
  header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
    header("Content-disposition: attachment; filename=billing.xls");
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
    ve('kpi.xlsx');
}
elseif (isset($_POST["reop"]))
{
    include ("../classes/reportsClass.php");
    $results=new reportsClass();
    $client_name=$_POST["client_name"];
	if($client_name=="GapRisk")
          {
            $client_name="Gaprisk_administrators";
          }
          else if($client_name=="TotalRisk")
          {
            $client_name="Total_risk_administrators";
          }
    $date=$_POST["month"];

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
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
    $objPHPExcel->setActiveSheetIndex(0)
        //claim_number	date_entered	1st date_closed	1st_savings	date_reopened	final_date_closed	final_savings	Client
        ->setCellValue('A1', 'Claim Number')
        ->setCellValue('B1', 'Date Entered')
        ->setCellValue('C1', '1st Date Closed')
        ->setCellValue('D1', '1st Savings')
        ->setCellValue('E1', 'Date Reopened')
        ->setCellValue('F1', 'Final Date Closed')
        ->setCellValue('G1', 'Final Savings')
        ->setCellValue('H1', 'Client Name')
        ->setCellValue('I1', 'Username');
    $from = "A1";
    $to = "I1";
    $objPHPExcel->getActiveSheet()->getStyle("$from:$to")->getFont()->setBold(true);
    $rowCount = 2;
    //print_r($results->getReopenedClaimsPerClient($client_name,$date));
    try {
        foreach($results->getReopenedClaimsPerClient($client_name,$date) as $row) {
            $client_name=$row["client_name"];
            $claim_number=$row["claim_number"];
            $username=$row["username"];
            $date_entered=$row["date_entered"];
            $date_closed=$row["date_closed"];
            $first_savings=$row["first_savings"];
            $reopened_date=$row["reopened_date"];
            $final_date_closed=$row["final_date_closed"];
            $final_savings=$row["final_savings"];

            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $claim_number);
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $date_entered);
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $date_closed);
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $first_savings);
            $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $reopened_date);
            $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $final_date_closed);
            $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $final_savings);
            $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $client_name);
            $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $username);
            $rowCount++;
        }

    } catch (Exception $rr) {
        $objPHPExcel->getActiveSheet()->SetCellValue('I1', $rr->getMessage());

    }
if($client_name=="Gaprisk_administrators")
          {
            $client_name="GapRisk";
          }
          else if($client_name=="Total_risk_administrators")
          {
            $client_name="TotalRisk";
          }
// Rename worksheet
    $objPHPExcel->getActiveSheet()->setTitle("$client_name Reopened Cases");
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $objPHPExcel->setActiveSheetIndex(0);
   header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
    header("Content-disposition: attachment; filename=$client_name reopened_cases.xls");
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
    ve('kpi.xlsx');
}
elseif (isset($_POST["cinagi_c"]))
{
    include ("../classes/reportsClass.php");
    $results=new reportsClass();
    $date=$_POST["month"];

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
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
    $objPHPExcel->setActiveSheetIndex(0)
        //claim_number	date_entered	1st date_closed	1st_savings	date_reopened	final_date_closed	final_savings	Client
        ->setCellValue('A1', 'Claim Number')
        ->setCellValue('B1', 'Date Entered')
        ->setCellValue('C1', 'Discount Savings')
        ->setCellValue('D1', 'Scheme Savings')
        ->setCellValue('E1', 'Date Closed')
        ->setCellValue('F1', 'Claim Type')
        ->setCellValue('G1', 'Open')
        ->setCellValue('H1', 'Username');

    $from = "A1";
    $to = "H1";
    $objPHPExcel->getActiveSheet()->getStyle("$from:$to")->getFont()->setBold(true);
    $rowCount = 2;
    //print_r($results->getReopenedClaimsPerClient($client_name,$date));
    try {
        foreach($results->cinagiClaims(35,$date) as $row) {
            $claim_number=$row["claim_number"];
            $username=$row["username"];
            $date_entered=$row["date_entered"];
            $date_closed=$row["date_closed"];
            $savings_discount=$row["savings_discount"];
            $savings_scheme=$row["savings_scheme"];
            $open=$row["Open"];
            $claim_type=$row["claim_type"];

            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $claim_number);
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $date_entered);
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $savings_discount);
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $savings_scheme);
            $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $date_closed);
            $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $claim_type);
            $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $open);
            $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $username);
            $rowCount++;
        }

    } catch (Exception $rr) {
        $objPHPExcel->getActiveSheet()->SetCellValue('I1', $rr->getMessage());

    }
// Rename worksheet
    $objPHPExcel->getActiveSheet()->setTitle("Cinagi_Claims");
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $objPHPExcel->setActiveSheetIndex(0);
   header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
    header("Content-disposition: attachment; filename=Cinagi_Claims.xls");
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
    ve('kpi.xlsx');
}
elseif (isset($_POST["switchdwn"]))
{
    include ("../classes/reportsClass.php");
    $results=new reportsClass();
    $client_name=$_POST["client_name"];
    $month=$_POST["month"];
    $objPHPExcel = new PHPExcel();
// Set document properties
    $objPHPExcel->getProperties()->setCreator("Govinda")
        ->setLastModifiedBy("Govinda")
        ->setTitle("Office 2007 XLSX Test Document")
        ->setSubject("Office 2007 XLSX Test Document")
        ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
        ->setKeywords("office 2007 openxml php")
        ->setCategory("Test result file");

    $arr=["All","Gap_3000"];
    for($j=0;$j<count($arr);$j++)
    {
        getSwich($arr[$j],$results,$objPHPExcel,$j,$client_name,$month);
    }
    $objPHPExcel->setActiveSheetIndex(0);
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
    ve('kpi.xlsx');
}
elseif (isset($_POST["vap"]))
{
    include ("../classes/reportsClass.php");
    $results=new reportsClass();
    $role=$_POST["role"];
    //$month=$_POST["month"];
    $objPHPExcel = new PHPExcel();
// Set document properties
    $objPHPExcel->getProperties()->setCreator("Govinda")
        ->setLastModifiedBy("Govinda")
        ->setTitle("Office 2007 XLSX Test Document")
        ->setSubject("Office 2007 XLSX Test Document")
        ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
        ->setKeywords("office 2007 openxml php")
        ->setCategory("Test result file");
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'Name')
        ->setCellValue('B1', 'Surname')
        ->setCellValue('C1', 'Email')
        ->setCellValue('D1', 'Contact Number')
        ->setCellValue('E1', 'Medical Scheme')
        ->setCellValue('F1', 'Date Entered')
        ->setCellValue('G1', 'Broker Name');
    $from = "A1";
    $to = "G1";
    $objPHPExcel->getActiveSheet()->getStyle("$from:$to")->getFont()->setBold(true);
    $rowCount = 2;
    try {
        foreach($results->webClients($role,0,2) as $row) {
            $name=$row["name"];
            $surname=$row["surname"];
            $email=$row["email"];
            $contact_number=$row["contact_number"];
            $medical_scheme=$row["medical_scheme"];
            $date_entered=$row["date_entered"];
            $broker_name=$row["broker_name"];

            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $name);
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $surname);
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $email);
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $contact_number);
            $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $medical_scheme);
            $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $date_entered);
            $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $broker_name);

            $rowCount++;
        }

    } catch (Exception $rr) {
        $objPHPExcel->getActiveSheet()->SetCellValue('I1', $rr->getMessage());

    }
// Rename worksheet
    $objPHPExcel->getActiveSheet()->setTitle("active_vap $role");
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $objPHPExcel->setActiveSheetIndex(0);

    header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
    header("Content-disposition: attachment; filename=active_vap $role.xls");
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
    ve('kpi.xlsx');
}
elseif (isset($_POST["broker_vap"]))
{
    include ("../classes/reportsClass.php");
    $results=new reportsClass();
    $objPHPExcel = new PHPExcel();

// Set document properties
    $objPHPExcel->getProperties()->setCreator("Govinda")
        ->setLastModifiedBy("Govinda")
        ->setTitle("Office 2007 XLSX Test Document")
        ->setSubject("Office 2007 XLSX Test Document")
        ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
        ->setKeywords("office 2007 openxml php")
        ->setCategory("Test result file");

    $i=0;
    $rr=$results->webClients("broker",0,2);
    foreach($rr as $rowx)
    {
        $broker_name=$rowx["name"];
        $broker_id=$rowx["client_id"];
        getClientsPerBroker($broker_name,$broker_id,$results,$objPHPExcel,$i);
        $i++;
    }

    $objPHPExcel->setActiveSheetIndex(0);
    // Redirect output to a client web browser (Excel5)
    header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
    header("Content-disposition: attachment; filename=broker_clients.xls");
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
    ve('claims.xlsx');

}
function getSwich($sheet_name,$results,$objPHPExcel,$i,$client_name,$month)
{
// Add some data
    $objWorksheet = new PHPExcel_Worksheet($objPHPExcel);
    $objPHPExcel->addSheet($objWorksheet);
    //$objWorksheet->setTitle(''. $date);
    $objPHPExcel->setActiveSheetIndex($i);
    // Rename worksheet
    $objPHPExcel->getActiveSheet()->setTitle("$sheet_name");
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
    //$objPHPExcel->setActiveSheetIndex($i);

    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
    $objPHPExcel->setActiveSheetIndex($i)
        ->setCellValue('A1', 'Claim Number')
        ->setCellValue('B1', 'Service_Date')
        ->setCellValue('C1', 'ICD10')
        ->setCellValue('D1', 'Charged Amount')
        ->setCellValue('E1', 'Scheme Amount')
        ->setCellValue('F1', 'Gap')
        ->setCellValue('G1', 'Policy Number')
        ->setCellValue('H1', 'First Name')
        ->setCellValue('I1', 'Last Name')
        ->setCellValue('J1', 'ID Number')
        ->setCellValue('K1', 'Scheme Number')
        ->setCellValue('L1', 'Medical Scheme Name')
        ->setCellValue('M1', 'Scheme Option')
        ->setCellValue('N1', 'Client Name')
        ->setCellValue('O1', 'Provider')
        ->setCellValue('P1', 'Date Entered');
    $from = "A1";
    $to = "P1";
    $objPHPExcel->getActiveSheet()->getStyle("$from:$to")->getFont()->setBold(true);
    $rowCount = 2;
    $col="";
    $all="1 AND";
    $ext=$sheet_name=="Gap_3000"?"AND a.gap<3000":"";
    $arr=array();
    if($client_name=="--")
    {
        $arr=$results->getAllSwitch($client_name,$month,$all,$ext);
    }
    else
    {
        $arr=$results->getAllSwitch($client_name,$month,"c.client_name=:client_name AND",$ext);
    }

    try {
        foreach($arr as $row) {
            $claim_number=$row["claim_number"];
            $Service_Date=$row["Service_Date"];
            $icd10=$row["icd10"];
            $charged_amnt=$row["charged_amnt"];
            $scheme_paid=$row["scheme_paid"];
            $gap=$row["gap"];
            $policy_number=$row["policy_number"];
            $first_name=$row["first_name"];
            $surname=$row["surname"];
            $id_number=$row["id_number"];
            $scheme_number=$row["scheme_number"];
            $medical_scheme=$row["medical_scheme"];
            $scheme_option=$row["scheme_option"];
            $client_name=$row["client_name"];
            $senderId=$row["senderId"];
            $date_entered=$row["date_entered"];
            $provider="";
            if($senderId==10)
            {
                $provider="Medswitch";
            }
            elseif($senderId==1)
            {
                $provider="Mededi";
            }
            elseif($senderId==11)
            {
                $provider="Healthbridge";
            }
            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $claim_number." ");
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $Service_Date);
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $icd10);
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $charged_amnt);
            $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $scheme_paid);
            $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $gap);
            $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $policy_number);
            $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $first_name);
            $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $surname);
            $objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, $id_number);
            $objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, $scheme_number);
            $objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, $medical_scheme);
            $objPHPExcel->getActiveSheet()->SetCellValue('M' . $rowCount, $scheme_option);
            $objPHPExcel->getActiveSheet()->SetCellValue('N' . $rowCount, $client_name);
            $objPHPExcel->getActiveSheet()->SetCellValue('O' . $rowCount, $provider);
            $objPHPExcel->getActiveSheet()->SetCellValue('P' . $rowCount, $date_entered);
            $rowCount++;
        }

    } catch (Exception $rr) {
        $objPHPExcel->getActiveSheet()->SetCellValue('I1', $rr->getMessage());

    }

}
function getClientsPerBroker($broker_name,$broker_id,$results,$objPHPExcel,$i)
{
// Add some data
    $objWorksheet = new PHPExcel_Worksheet($objPHPExcel);
    $objPHPExcel->addSheet($objWorksheet);
    //$objWorksheet->setTitle(''. $date);
    $objPHPExcel->setActiveSheetIndex($i);
    // Rename worksheet
    $objPHPExcel->getActiveSheet()->setTitle("$broker_name");
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
    //$objPHPExcel->setActiveSheetIndex($i);

    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);

    $objPHPExcel->setActiveSheetIndex($i)
        ->setCellValue('A1', 'Name'.$broker_name)
        ->setCellValue('B1', 'Surname')
        ->setCellValue('C1', 'Email')
        ->setCellValue('D1', 'Contact Number')
        ->setCellValue('E1', 'Medical Scheme')
        ->setCellValue('F1', 'Date Entered')
        ->setCellValue('G1', 'Broker Name');
    $from = "A1";
    $to = "G1";
    $objPHPExcel->getActiveSheet()->getStyle("$from:$to")->getFont()->setBold(true);
    $rowCount = 2;
    try {
        foreach($results->webClients("client",$broker_id,2) as $row) {
            $name=$row["name"];
            $surname=$row["surname"];
            $email=$row["email"];
            $contact_number=$row["contact_number"];
            $medical_scheme=$row["medical_scheme"];
            $date_entered=$row["date_entered"];
            $broker_name=$row["broker_name"];

            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $name);
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $surname);
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $email);
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $contact_number);
            $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $medical_scheme);
            $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $date_entered);
            $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $broker_name);

            $rowCount++;
        }

    } catch (Exception $rr) {
        $objPHPExcel->getActiveSheet()->SetCellValue('I1', $rr->getMessage());

    }

}

function claimsExcel($date,$client,$results,$objPHPExcel,$i,$status)
{
// Add some data
    $objWorksheet = new PHPExcel_Worksheet($objPHPExcel);
    $objPHPExcel->addSheet($objWorksheet);
    //$objWorksheet->setTitle(''. $date);
    $objPHPExcel->setActiveSheetIndex($i);
    // Rename worksheet
    $objPHPExcel->getActiveSheet()->setTitle("$date");
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
    //$objPHPExcel->setActiveSheetIndex($i);

    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
    $objPHPExcel->setActiveSheetIndex($i)
        ->setCellValue('A1', 'Policy_Number')
        ->setCellValue('B1', 'Claim_Number')
        ->setCellValue('C1', 'Savings_By_Scheme')
        ->setCellValue('D1', 'Savings_By_Discount')
        ->setCellValue('E1', 'Total_Savings')
        ->setCellValue('F1', 'Date_Closed')
        ->setCellValue('G1', 'Date_Entered')
        ->setCellValue('H1', 'Value_Of_Claim')
        ->setCellValue('I1', 'Created By')
        ->setCellValue('J1', 'MCA Username');

    $from = "A1";
    $to = "J1";
    $objPHPExcel->getActiveSheet()->getStyle("$from:$to")->getFont()->setBold(true);
    $rowCount = 2;
    try {
        foreach ($results->selectData($date,$client,$status) as $row)
        {
            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, "$row[0]");
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, "$row[1]");
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, "$row[2]");
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, "$row[3]");
            $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, "$row[4]");
            $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, "$row[5]");
            $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, "$row[6]");
            $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, "$row[7]");
            $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, "$row[10]");
            $objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, "$row[11]");

            $rowCount++;
        }

    } catch (Exception $rr) {
        $objPHPExcel->getActiveSheet()->SetCellValue('I1', $rr->getMessage());

    }

}
function getWorkingDaysx($startDate,$endDate,$holidays){
    // do strtotime calculations just once
    $endDate = strtotime($endDate);
    $startDate = strtotime($startDate);


    //The total number of days between the two dates. We compute the no. of seconds and divide it to 60*60*24
    //We add one to inlude both dates in the interval.
    $days = ($endDate - $startDate) / 86400 + 1;

    $no_full_weeks = floor($days / 7);
    $no_remaining_days = fmod($days, 7);

    //It will return 1 if it's Monday,.. ,7 for Sunday
    $the_first_day_of_week = date("N", $startDate);
    $the_last_day_of_week = date("N", $endDate);

    //---->The two can be equal in leap years when february has 29 days, the equal sign is added here
    //In the first case the whole interval is within a week, in the second case the interval falls in two weeks.
    if ($the_first_day_of_week <= $the_last_day_of_week) {
        if ($the_first_day_of_week <= 6 && 6 <= $the_last_day_of_week) $no_remaining_days--;
        if ($the_first_day_of_week <= 7 && 7 <= $the_last_day_of_week) $no_remaining_days--;
    }
    else {
        // (edit by Tokes to fix an edge case where the start day was a Sunday
        // and the end day was NOT a Saturday)

        // the day of the week for start is later than the day of the week for end
        if ($the_first_day_of_week == 7) {
            // if the start date is a Sunday, then we definitely subtract 1 day
            $no_remaining_days--;

            if ($the_last_day_of_week == 6) {
                // if the end date is a Saturday, then we subtract another day
                $no_remaining_days--;
            }
        }
        else {
            // the start date was a Saturday (or earlier), and the end date was (Mon..Fri)
            // so we skip an entire weekend and subtract 2 days
            $no_remaining_days -= 2;
        }
    }

    //The no. of business days is: (number of weeks between the two dates) * (5 working days) + the remainder
//---->february in none leap years gave a remainder of 0 but still calculated weekends between first and last day, this is one way to fix it
    $workingDays = $no_full_weeks * 5;
    if ($no_remaining_days > 0 )
    {
        $workingDays += $no_remaining_days;
    }

    //We subtract the holidays
    foreach($holidays as $holiday){
        $myholiday=date("Y")."-";
        $time_stamp=strtotime($myholiday.$holiday);
        //If the holiday doesn't fall in weekend
        if ($startDate <= $time_stamp && $time_stamp <= $endDate && date("N",$time_stamp) != 6 && date("N",$time_stamp) != 7)
            $workingDays--;
    }

    return $workingDays;
}


