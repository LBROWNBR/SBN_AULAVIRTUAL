<?php
session_start();

require_once '../../app/libs/excel/PHPExcel.php';

$objPHPExcel = new PHPExcel();

$objPHPExcel->getProperties()->setCreator("JOSE ALFREDO")->setDescription("REPORTE DE OBRAS");
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setTitle("OBRAS");

$estiloTituloColumnas = array(
    'font' => array(
        'name'  => 'Calibri',
        'bold'  => true,
        'size' => 10,
        'color' => array(
            'rgb' => 'FFFFFF'
        )
    ),
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array('rgb' => '538DD5')
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


$estiloInformacion = new PHPExcel_Style();
$estiloInformacion->applyFromArray(array(
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
        // 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical'  => PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
));

$objPHPExcel->getActiveSheet()->getStyle('A1:Y1')->applyFromArray($estiloTituloColumnas);

$objPHPExcel->getActiveSheet()->setCellValue('A1', 'N°');
$objPHPExcel->getActiveSheet()->setCellValue('B1', 'CODIGO');
$objPHPExcel->getActiveSheet()->setCellValue('C1', 'NOMBRE DE OBRA');
$objPHPExcel->getActiveSheet()->setCellValue('D1', 'ESTADO');
$objPHPExcel->getActiveSheet()->setCellValue('E1', 'AREA');
$objPHPExcel->getActiveSheet()->setCellValue('F1', 'CLIENTE');
$objPHPExcel->getActiveSheet()->setCellValue('G1', 'GESTOR DEL CLIENTE');
$objPHPExcel->getActiveSheet()->setCellValue('H1', 'CONTRATA');
$objPHPExcel->getActiveSheet()->setCellValue('I1', 'GESTOR EECC');
$objPHPExcel->getActiveSheet()->setCellValue('J1', 'OT');
$objPHPExcel->getActiveSheet()->setCellValue('K1', 'CAL');
$objPHPExcel->getActiveSheet()->setCellValue('L1', 'REMEDY');
$objPHPExcel->getActiveSheet()->setCellValue('M1', 'TRABAJADORES');
$objPHPExcel->getActiveSheet()->setCellValue('N1', 'FECHA CREACION');
$objPHPExcel->getActiveSheet()->setCellValue('O1', 'FECHA INICIO');
$objPHPExcel->getActiveSheet()->setCellValue('P1', 'FECHA FIN');
$objPHPExcel->getActiveSheet()->setCellValue('Q1', 'INF. FOTO');
$objPHPExcel->getActiveSheet()->setCellValue('R1', 'DESCRIPCION');
$objPHPExcel->getActiveSheet()->setCellValue('S1', 'OBSERVACION');
$objPHPExcel->getActiveSheet()->setCellValue('T1', 'ZONAL');
$objPHPExcel->getActiveSheet()->setCellValue('U1', 'DEPARTAMENTO');
$objPHPExcel->getActiveSheet()->setCellValue('V1', 'PROVINCIA');
$objPHPExcel->getActiveSheet()->setCellValue('W1', 'DISTRITO');
$objPHPExcel->getActiveSheet()->setCellValue('X1', 'DIRECCION');
$objPHPExcel->getActiveSheet()->setCellValue('Y1', 'NOMBRE LOCAL');

// $objPHPExcel->getActiveSheet()->setAutoFilter("A1:S1");

$fila = 2;

foreach ($_SESSION['reporte_obras_excel'] as $obra) {
    $objPHPExcel->getActiveSheet()->setCellValue('A' . $fila, strtoupper($obra[0]));
    $objPHPExcel->getActiveSheet()->setCellValue('B' . $fila, strtoupper($obra[1]));
    $objPHPExcel->getActiveSheet()->setCellValue('C' . $fila, strtoupper($obra[2]));
    $objPHPExcel->getActiveSheet()->setCellValue('D' . $fila, strtoupper($obra[3]));
    $objPHPExcel->getActiveSheet()->setCellValue('E' . $fila, strtoupper($obra[4]));
    $objPHPExcel->getActiveSheet()->setCellValue('F' . $fila, strtoupper($obra[5]));
    $objPHPExcel->getActiveSheet()->setCellValue('G' . $fila, strtoupper($obra[6]));
    $objPHPExcel->getActiveSheet()->setCellValue('H' . $fila, strtoupper($obra[7]));
    $objPHPExcel->getActiveSheet()->setCellValue('I' . $fila, strtoupper($obra[8]));
    $objPHPExcel->getActiveSheet()->setCellValue('J' . $fila, strtoupper($obra[9]));
    $objPHPExcel->getActiveSheet()->setCellValue('K' . $fila, strtoupper($obra[10]));
    $objPHPExcel->getActiveSheet()->setCellValue('L' . $fila, strtoupper($obra[11]));
    $objPHPExcel->getActiveSheet()->setCellValue('M' . $fila, strtoupper($obra[12]));
    $fechaCre = !empty($obra[13]) ? (PHPExcel_Shared_Date::PHPToExcel($obra[13])) : '';
    $fechaIni = !empty($obra[14]) ? (PHPExcel_Shared_Date::PHPToExcel($obra[14])) : '';
    $fechaFin = !empty($obra[15]) ? (PHPExcel_Shared_Date::PHPToExcel($obra[15])) : '';
    $objPHPExcel->getActiveSheet()->setCellValue('N' . $fila, $fechaCre);
    $objPHPExcel->getActiveSheet()->setCellValue('O' . $fila, $fechaIni);
    $objPHPExcel->getActiveSheet()->setCellValue('P' . $fila, $fechaFin);
    $objPHPExcel->getActiveSheet()->setCellValue('Q' . $fila, strtoupper($obra[16]));
    $objPHPExcel->getActiveSheet()->setCellValue('R' . $fila, strtoupper($obra[17]));
    $objPHPExcel->getActiveSheet()->setCellValue('S' . $fila, strtoupper($obra[18]));
    $objPHPExcel->getActiveSheet()->setCellValue('T' . $fila, strtoupper($obra[19]));
    $objPHPExcel->getActiveSheet()->setCellValue('U' . $fila, strtoupper($obra[20]));
    $objPHPExcel->getActiveSheet()->setCellValue('V' . $fila, strtoupper($obra[21]));
    $objPHPExcel->getActiveSheet()->setCellValue('W' . $fila, strtoupper($obra[22]));
    $objPHPExcel->getActiveSheet()->setCellValue('X' . $fila, strtoupper($obra[23]));
    $objPHPExcel->getActiveSheet()->setCellValue('Y' . $fila, strtoupper($obra[24]));
    $fila++;
}

$fila = $fila - 1;

$objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A2:Y" . $fila);
$objPHPExcel->getActiveSheet()->getStyle('M2:M' . $fila)->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('T2:T' . $fila)->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('U2:U' . $fila)->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('V2:V' . $fila)->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('W2:W' . $fila)->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('X2:X' . $fila)->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('Y2:Y' . $fila)->getAlignment()->setWrapText(true);

$objPHPExcel->getActiveSheet()->getStyle('N2:N' . $fila)->getNumberFormat()->setFormatCode("dd/mm/yyyy");
$objPHPExcel->getActiveSheet()->getStyle('O2:O' . $fila)->getNumberFormat()->setFormatCode("dd/mm/yyyy");
$objPHPExcel->getActiveSheet()->getStyle('P2:P' . $fila)->getNumberFormat()->setFormatCode("dd/mm/yyyy");

// $styleArray = array(
//     'font'  => array(
//         'bold'  => false,
//         'color' => array('rgb' => '000000'),
//         'size'  => 10,
//         'name'  => 'Calibri'
//     )
// );

// $border_style = array(
//     'borders' => array(
//         'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => '766f6e'))
//     ),
//     'fill' => array(
//         'type'  => PHPExcel_Style_Fill::FILL_SOLID
//     ),
//     'alignment' =>  array(
//         'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
//         'vertical'  => PHPExcel_Style_Alignment::VERTICAL_CENTER
//     )
// );

// $objPHPExcel->getActiveSheet()->getStyle("A2:S" . $fila)->applyFromArray($border_style);
// $objPHPExcel->getActiveSheet()->getStyle("A2:S" . $fila)->applyFromArray($styleArray);

// foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
//     $objPHPExcel->setActiveSheetIndex($objPHPExcel->getIndex($worksheet));
//     $sheet = $objPHPExcel->getActiveSheet();
//     $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
//     $cellIterator->setIterateOnlyExistingCells(true);
//     /** @var PHPExcel_Cell $cell */
//     foreach ($cellIterator as $cell) {
//         $sheet->getColumnDimension($cell->getColumn())->setAutoSize(true);
//     }
// }

foreach (range('A', 'Y') as $columnID) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
}

unset($_SESSION['cursos_reporte_excel']);

header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header('Content-Disposition: attachment;filename="Reporte_cursos.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$objWriter->save('php://output');
exit();
