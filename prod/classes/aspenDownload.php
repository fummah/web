<?php
require "../PHPExcel/Classes/PHPExcel.php";
require "../PHPExcel/Classes/PHPExcel/Writer/Excel5.php";
session_start();
$arralph=["A","B","C","D","E","F","G","H","I","J","K","L","M","N","M","O","P","Q","R","S","T","V"];
$holidays=array("01-01","03-21","04-19","04-27","05-01","06-17","08-09","09-24","12-16","12-25","12-26");
include ("reportsClass.php");
if (isset($_POST["aspen_download"]))
{

    $results=new reportsClass();
    $date=$_POST['download'];
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
        ->setCellValue('A1', 'Row Labels')
        ->setCellValue('E1', 'Ferinject')
        ->setCellValue('F1', 'Venofer')
        ->setCellValue('G1', 'Grand Total');
    $objPHPExcel->getActiveSheet()->mergeCells('A1:D1');
    $from = "A1";
    $to = "G1";
    $objPHPExcel->getActiveSheet()->getStyle("$from:$to")->getFont()->setBold(true);
    $rowCount = 2;

    $stasarr=$results->getAspenStatus($date);
    $gge=["Female","Male",""];
    $ferinject="Ferinject";
    $venofer="Venofer";

    $schemevalfg=0;$schemevalv=0;$totalscheme=0;
    foreach($stasarr as $xc)
    {
        $sstus=$xc[0];
        $stadisplay=strlen($sstus)>1?$sstus:"Pending";
        $ferinjectval=$results->getAspenValue($sstus,$ferinject,$date);

        $venoferval=$results->getAspenValue($sstus,$venofer,$date);
        $total=$ferinjectval+$venoferval;
        $schemevalfg+=$ferinjectval;$schemevalvg+=$venoferval;$totalschemeg+=$total;
        $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $stadisplay);
        $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $ferinjectval);
        $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $venoferval);
        $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $total);
        $x1="A".$rowCount;$xx1="D".$rowCount;
        $objPHPExcel->getActiveSheet()->mergeCells("$x1:$xx1");
        $rowCount++;
        $arrgender=$results->getAspenGender($sstus,$date);
        for($xx=0;$xx<3;$xx++)
        {
            $gender=ucfirst($gge[$xx]);
            $genderdisplay=strlen($gender)>1?$gender:"Unknown";
            $gendervalf=$results->getAspenGenderValue($sstus,$ferinject,$gender,$date);
            $gendervalv=$results->getAspenGenderValue($sstus,$venofer,$gender,$date);
            $totalgender=$gendervalf+$gendervalv;
            if($totalgender<1)
            {
                continue;
            }
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $genderdisplay);
            $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $gendervalf);
            $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $gendervalv);
            $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $totalgender);
            $x1="B".$rowCount;$xx1="D".$rowCount;
            $objPHPExcel->getActiveSheet()->mergeCells("$x1:$xx1");
            $rowCount++;
            $schemearr=$results->getAspenScheme($sstus,$gender,$date);
            foreach($schemearr as $xc2)
            {
                $scheme_name=$xc2[0];
                $schemevalf=$results->getAspenSchemeValue($sstus,$ferinject,$gender,$scheme_name,$date);
                $schemevalv=$results->getAspenSchemeValue($sstus,$venofer,$gender,$scheme_name,$date);
                $totalscheme=$schemevalf+$schemevalv;
                $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $scheme_name);
                $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $schemevalf);
                $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $schemevalv);
                $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $totalscheme);
                $x1="C".$rowCount;$xx1="D".$rowCount;
                $objPHPExcel->getActiveSheet()->mergeCells("$x1:$xx1");
                $rowCount++;
                $icdarr=$results->getAspenIcd10($sstus,$gender,$scheme_name,$date);
                foreach($icdarr as $xc3)
                {
                    $icd10=$xc3[0];
                    $v9=$results->getAspenIcd10Value($sstus,$ferinject,$gender,$scheme_name,$date,$icd10);
                    $v10=$results->getAspenIcd10Value($sstus,$venofer,$gender,$scheme_name,$date,$icd10);
                    $schemevalx=count($v9);
                    $schemevaly=count($v10);
                    $totalschemeicd10=$schemevalx+$schemevaly;

                    $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $icd10);
                    $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $schemevalx);
                    $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $schemevaly);
                    $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $totalschemeicd10);

                    $rowCount++;

                }
            }

        }

    }
    $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, 'Grand Total');
    $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $schemevalfg);
    $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $schemevalvg);
    $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $totalschemeg);
    $x1="A".$rowCount;$xx1="D".$rowCount;
    $objPHPExcel->getActiveSheet()->mergeCells("$x1:$xx1");
    $df='A' . $rowCount;
    $df1='G' . $rowCount;
    $objPHPExcel->getActiveSheet()->getStyle("$df:$df1")->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0);
    // Redirect output to a client’s web browser (Excel5)
    header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
    header("Content-disposition: attachment; filename=Aspen_Report.xls");
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





