<?php
error_reporting(0);
session_start();
define("access",true);
require "../PHPExcel/Classes/PHPExcel.php";
require "../PHPExcel/Classes/PHPExcel/Writer/Excel2007.php";
$arralph=["A","B","C","D","E","F","G","H","I","J","K","L","M","N","M","O","P","Q","R","S","T","V"];
if(isset($_POST["ctxt"])) {
  $ctxt = $_POST["ctxt"];
  $ctxt=$ctxt=="Total_risk_administrators"?"Total_risk":$ctxt;
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
//$html = "<b style='font-weight: bolder'>".'Year-Month' . "\t" . 'Claims Closed' . "\t" . 'Savings by Dr Discount' . "\t" . 'Savings by Scheme Paid' . "\t" . 'Total Savings' . "\t" . 'Value of Claims Referred' . "\t" . 'Percentage saved (of total referred)' . "\t" . 'Average time to close (days)' . "\t" . 'Claims Referred'  . "<b>" . "\n";


  $objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A1', 'Year-Month')
    ->setCellValue('B1', 'Claims Closed')
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

// Redirect output to a clientâs web browser (Excel5)
  header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
  //header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=UTF-8');
  header('Content-Transfer-Encoding: binary');
  header("Content-disposition: attachment; filename=$ctxt.xlsx");
  header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
  header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
  header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
  header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
  header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
  header('Pragma: public'); // HTTP/1.0

  $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
  //$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
  $objWriter->save('php://output');
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

//$html = "<b style='font-weight: bolder'>".'Year-Month' . "\t" . 'Claims Closed' . "\t" . 'Savings by Dr Discount' . "\t" . 'Savings by Scheme Paid' . "\t" . 'Total Savings' . "\t" . 'Value of Claims Referred' . "\t" . 'Percentage saved (of total referred)' . "\t" . 'Average time to close (days)' . "\t" . 'Claims Referred'  . "<b>" . "\n";


  $objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A1', 'Claim number')
    ->setCellValue('B1', 'Date')
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

// Redirect output to a clientÃ¢ÂÂs web browser (Excel5)
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
        $str="SELECT COUNT(*) as total FROM claim WHERE date_closed like :closed AND username=:user1 AND Open<>2";
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

// Redirect output to a clientÃ¢ÂÂs web browser (Excel5)
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

elseif(isset($_POST["txt3"]))
{
  $objPHPExcel = new PHPExcel();
  $txt=$_POST["txt11"];
  $t=json_decode($txt,true);
  $nu=count($t);

  if ($nu > 0) {
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
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);

//$html = "<b style='font-weight: bolder'>".'Year-Month' . "\t" . 'Claims Closed' . "\t" . 'Savings by Dr Discount' . "\t" . 'Savings by Scheme Paid' . "\t" . 'Total Savings' . "\t" . 'Value of Claims Referred' . "\t" . 'Percentage saved (of total referred)' . "\t" . 'Average time to close (days)' . "\t" . 'Claims Referred'  . "<b>" . "\n";


    $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('A1', 'Full Name')
      ->setCellValue('B1', 'Email')
      ->setCellValue('C1', 'Broker Name')
      ->setCellValue('D1', 'Subscription')
      ->setCellValue('E1', 'First Date')
      ->setCellValue('F1', 'Amount(Zar)');
    $from = "A1";
    $to = "I1";
    $objPHPExcel->getActiveSheet()->getStyle("$from:$to")->getFont()->setBold(true);

    $rowCount = 2;

    try {
      foreach ($t as $row) {
        $name=$row["name"];
        $email=$row["email"];
        $broker=$row["broker"];
        $subscription=$row["subscription"];
        $date=$row["date"];
        $amount=$row["amount"];
        $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $name);
        $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $email);
        $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $broker);
        $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $subscription);
        $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $date);
        $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $amount);

        $rowCount++;
          }
    } catch (Exception $rr) {
      $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $rr->getMessage());

    }
  }

// Rename worksheet
  $objPHPExcel->getActiveSheet()->setTitle("Broker_Report");
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
  $objPHPExcel->setActiveSheetIndex(0);

// Redirect output to a clientâs web browser (Excel5)
  header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
  header("Content-disposition: attachment; filename=Broker_Report.xls");
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
  ve('Broker_Report.xlsx');
}

elseif(isset($_POST["txt4"]))
{
  $objPHPExcel = new PHPExcel();
  $txt=$_POST["txt12"];
  $t=json_decode($txt,true);
  $nu=count($t);

  if ($nu > 0) {
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
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);

//$html = "<b style='font-weight: bolder'>".'Year-Month' . "\t" . 'Claims Closed' . "\t" . 'Savings by Dr Discount' . "\t" . 'Savings by Scheme Paid' . "\t" . 'Total Savings' . "\t" . 'Value of Claims Referred' . "\t" . 'Percentage saved (of total referred)' . "\t" . 'Average time to close (days)' . "\t" . 'Claims Referred'  . "<b>" . "\n";


    $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('A1', 'Full Name')
      ->setCellValue('B1', 'Email')
      ->setCellValue('C1', 'Broker Name')
      ->setCellValue('D1', 'Subscription')
      ->setCellValue('E1', 'Transaction Date')
      ->setCellValue('F1', 'Amount(Zar)');
    $from = "A1";
    $to = "I1";
    $objPHPExcel->getActiveSheet()->getStyle("$from:$to")->getFont()->setBold(true);

    $rowCount = 2;

    try {
      foreach ($t as $row) {
        $name=$row["name"];
        $email=$row["email"];
        $broker=$row["broker"];
        $subscription=$row["subscription"];
        $date=$row["date"];
        $amount=$row["amount"];
        $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $name);
        $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $email);
        $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $broker);
        $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $subscription);
        $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $date);
        $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $amount);

        $rowCount++;
      }
    } catch (Exception $rr) {
      $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $rr->getMessage());

    }
  }

// Rename worksheet
  $objPHPExcel->getActiveSheet()->setTitle("Invoices");
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
  $objPHPExcel->setActiveSheetIndex(0);

// Redirect output to a clientâs web browser (Excel5)
  header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
  header("Content-disposition: attachment; filename=Invoices.xls");
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
  ve('Invoices.xlsx');
}
