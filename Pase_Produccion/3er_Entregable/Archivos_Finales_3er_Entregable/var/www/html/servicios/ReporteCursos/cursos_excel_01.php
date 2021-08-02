<?php
session_start();

require_once '../../app/libs/excel/PHPExcel.php';

$objPHPExcel = new PHPExcel();

$objPHPExcel->getProperties()->setCreator("JOSE ALFREDO")->setDescription("Reporte");
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setTitle("Cursos");

$styleTitulo = array(
    'font' => array(
        'name'  => 'Calibri',
        'bold'  => true,
        'size' => 12,
        'color' => array(
            'rgb' => '000000'
        )
    ),
    'alignment' =>  array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical'  => PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
);

$styleHead01 = array(
    'font' => array(
        'name'  => 'Calibri',
        'bold'  => true,
        'size' => 10,
        'color' => array(
            'rgb' => '000000'
        )
    ),
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array('rgb' => 'd1c4e9')
    ),
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN
        )
    ),
    'alignment' =>  array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical'  => PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
);

$styleHead02 = array(
    'font' => array(
        'name'  => 'Calibri',
        'bold'  => true,
        'size' => 10,
        'color' => array(
            'rgb' => '000000'
        )
    ),
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array('rgb' => 'ede7f6')
    ),
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN
        )
    ),
    'alignment' =>  array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical'  => PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
);

$styleMain = new PHPExcel_Style();
$styleMain->applyFromArray(array(
    'font' => array(
        'name'  => 'Calibri',
        'size' => 10,
        'bold'  => false,
        'color' => array(
            'rgb' => '000000'
        )
    ),
    'fill' => array(
        'type'  => PHPExcel_Style_Fill::FILL_SOLID
    ),
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('argb' => '766f6e')
        )
    ),
    'alignment' =>  array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical'  => PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
));

$styleFooter = new PHPExcel_Style();
$styleFooter->applyFromArray(array(
    'font' => array(
        'name'  => 'Calibri',
        'size' => 10,
        'bold'  => true,
        'color' => array(
            'rgb' => '000000'
        )
    ),
    'fill' => array(
        'type'  => PHPExcel_Style_Fill::FILL_SOLID
    ),
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('argb' => '766f6e')
        )
    ),
    'alignment' =>  array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical'  => PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
));


$datos = isset($_SESSION['reporte_cursos']) ? $_SESSION['reporte_cursos'] : die();

$fechaIni = $datos['rep_cursoIni'];
$fechaFin = $datos['rep_cursoFin'];


// $objPHPExcel->getActiveSheet()->getStyle('B1:D1')->applyFromArray($styleTitulo);
$objPHPExcel->getActiveSheet()->setCellValue('B1', strtoupper($datos['rep_cursoNombre']));
// $objPHPExcel->getActiveSheet()->mergeCells('B1:D1');

// $objPHPExcel->getActiveSheet()->getStyle('B2:D2')->applyFromArray($styleHead01);
// $objPHPExcel->getActiveSheet()->setCellValue('B2', strtoupper($datos['rep_lugar']));
// $objPHPExcel->getActiveSheet()->mergeCells('B2:D2');

$objPHPExcel->getActiveSheet()->getStyle('B3:F3')->applyFromArray($styleHead01);
$objPHPExcel->getActiveSheet()->setCellValue('B3', 'CURSO "' . strtoupper($datos['rep_cursoNombre']) . '"');
$objPHPExcel->getActiveSheet()->mergeCells('B3:F3');

$objPHPExcel->getActiveSheet()->getStyle('B4:F4')->applyFromArray($styleHead02);
$objPHPExcel->getActiveSheet()->setCellValue('B4', 'REALIZADO DEL ' . $fechaIni . ' al ' . $fechaFin);
$objPHPExcel->getActiveSheet()->mergeCells('B4:F4');

$objPHPExcel->getActiveSheet()->getStyle('B5:F5')->applyFromArray($styleHead01);
$objPHPExcel->getActiveSheet()->setCellValue('B5', 'ENTIDAD');
$objPHPExcel->getActiveSheet()->setCellValue('C5', 'N° DE FUNCIONARIOS');
$objPHPExcel->getActiveSheet()->setCellValue('D5', 'N° DE SERVIDORES');
$objPHPExcel->getActiveSheet()->setCellValue('E5', 'N° DE FUNCIONARIOS & CERTIFICADO');
$objPHPExcel->getActiveSheet()->setCellValue('F5', 'N° DE SERVIDORES & CERTIFICADO');

$fila = 6;

if (count($datos['rep_detalle']) > 0) {

    $dataCertifcaciones = $datos['rep_detalle_certificado'];
/*
    echo "<pre>";
    print_r($dataCertifcaciones);
    echo "</pre>";
    */

    $entidades = array_unique(array_column($datos['rep_detalle'], 0));
    
    $sumFuncPublicoCERT = 0;
    $sumServPublicoCERT = 0;
    $sumCERTGRAL = 0;

    foreach ($entidades as $entidad) {
        $numFunc = 0;
        $numServ = 0;
        $valTotFuncPublicoCERT = 0;
        $valTotServPublicoCERT = 0;

        foreach ($datos['rep_detalle'] as $detalle) {
            if (in_array($entidad, $detalle)) {
                switch ($detalle[1]) {
                    case 'funcionario público':
                        $numFunc += $detalle[2];
                        break;
                    case 'servidor público':
                        $numServ += $detalle[2];
                        break;
                }
            }
        }


        foreach ($datos['rep_detalle_certificado'] as $detCERT) {
            if (in_array($entidad, $detCERT)) {
                $valTotFuncPublicoCERT = ($entidad == $detCERT['mdl_entidad']) ? $detCERT['TotFuncPublicoCERT'] : '';
                $valTotServPublicoCERT = ($entidad == $detCERT['mdl_entidad']) ? $detCERT['TotServPublicoCERT'] : '';
            }
        }


        $objPHPExcel->getActiveSheet()->setCellValue('B' . $fila, strtoupper($entidad));
        $objPHPExcel->getActiveSheet()->setCellValue('C' . $fila, $numFunc);
        $objPHPExcel->getActiveSheet()->setCellValue('D' . $fila, $numServ);
        $objPHPExcel->getActiveSheet()->setCellValue('E' . $fila, $valTotFuncPublicoCERT);
        $objPHPExcel->getActiveSheet()->setCellValue('F' . $fila, $valTotServPublicoCERT);

        $sumFuncPublicoCERT = $sumFuncPublicoCERT+$valTotFuncPublicoCERT;
        $sumServPublicoCERT = $sumServPublicoCERT+$valTotServPublicoCERT;
        
        $fila++;
    }
} else {
    $objPHPExcel->getActiveSheet()->setCellValue('B' . $fila, 'No se encontro matriculas');
    $objPHPExcel->getActiveSheet()->mergeCells('B' . $fila . ':F' . $fila);
    $fila++;
}

$sumCERTGRAL = $sumFuncPublicoCERT+$sumServPublicoCERT;

$fila = $fila - 1;

$objPHPExcel->getActiveSheet()->setSharedStyle($styleMain, "B6:F" . $fila);

// $objPHPExcel->getActiveSheet()->setCellValue('B6', 'Gobierno Regional Junín');
// $objPHPExcel->getActiveSheet()->setCellValue('C6', '1');
// $objPHPExcel->getActiveSheet()->setCellValue('D6', '10');

// $objPHPExcel->getActiveSheet()->setCellValue('B7', 'Gobierno Regional');
// $objPHPExcel->getActiveSheet()->setCellValue('C7', '1');
// $objPHPExcel->getActiveSheet()->setCellValue('D7', '10');

// $objPHPExcel->getActiveSheet()->setCellValue('B8', 'Gobierno Local');
// $objPHPExcel->getActiveSheet()->setCellValue('C8', '1');
// $objPHPExcel->getActiveSheet()->setCellValue('D8', '10');

// $objPHPExcel->getActiveSheet()->setSharedStyle($styleMain, "B6:D8");

$fila++;
$iniFooter = $fila;
$filaFin = $fila + 1;
$objPHPExcel->getActiveSheet()->setCellValue('B' . $fila, 'TOTAL CAPACITADOS');
$objPHPExcel->getActiveSheet()->mergeCells('B' . $fila . ':B' . $filaFin);
$objPHPExcel->getActiveSheet()->setCellValue('C' . $fila, $datos['rep_numTotalFunc']);
$objPHPExcel->getActiveSheet()->setCellValue('D' . $fila, $datos['rep_numTotalServ']);
$objPHPExcel->getActiveSheet()->setCellValue('E' . $fila, $sumFuncPublicoCERT);
$objPHPExcel->getActiveSheet()->setCellValue('F' . $fila, $sumServPublicoCERT);


$fila++;
$objPHPExcel->getActiveSheet()->setCellValue('C' . $fila, $datos['rep_totalCapac']);
$objPHPExcel->getActiveSheet()->mergeCells('C' . $fila . ':D' . $fila);

$objPHPExcel->getActiveSheet()->setCellValue('E' . $fila, $sumCERTGRAL);
$objPHPExcel->getActiveSheet()->mergeCells('E' . $fila . ':F' . $fila);

$objPHPExcel->getActiveSheet()->setSharedStyle($styleFooter, "B" . $iniFooter . ":F" . $fila);
$objPHPExcel->getActiveSheet()->getStyle('C' . $fila . ':F' . $fila)->applyFromArray($styleHead02);

// $objPHPExcel->getActiveSheet()->setCellValue('B9', 'Total Capacitados');
// $objPHPExcel->getActiveSheet()->mergeCells('B9:B10');
// $objPHPExcel->getActiveSheet()->setCellValue('C9', $datos['rep_numTotalFunc']);
// $objPHPExcel->getActiveSheet()->setCellValue('D9', $datos['rep_numTotalServ']);

// $objPHPExcel->getActiveSheet()->setCellValue('C10', $datos['rep_totalCapac']);
// $objPHPExcel->getActiveSheet()->mergeCells('C10:D10');

// $objPHPExcel->getActiveSheet()->setSharedStyle($styleFooter, "B9:D10");
// $objPHPExcel->getActiveSheet()->getStyle('C10:D10')->applyFromArray($styleHead02);

$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(40);
$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(0);
$objPHPExcel->getActiveSheet()->getRowDimension('3')->setRowHeight(20);
$objPHPExcel->getActiveSheet()->getRowDimension('4')->setRowHeight(20);
$objPHPExcel->getActiveSheet()->getRowDimension('5')->setRowHeight(30);
$objPHPExcel->getActiveSheet()->getRowDimension('6')->setRowHeight(20);

$objPHPExcel->getActiveSheet()->getStyle('B1:F1')->applyFromArray($styleTitulo);
$objPHPExcel->getActiveSheet()->mergeCells('B1:F1');


$objPHPExcel->getActiveSheet()->getStyle("B1:F{$fila}")->getAlignment()->setWrapText(true);

foreach (range('B', 'F') as $columnID) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(false);
}

$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth("40");
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth("25");
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth("25");
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth("25");
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth("25");


unset($_SESSION['reporte_cursos']);

header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header('Content-Disposition: attachment;filename="Reporte_Entidades.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$objWriter->save('php://output');
exit();
