<?php
session_start();

require_once '../../app/libs/excel/PHPExcel.php';

$objPHPExcel = new PHPExcel();

$objPHPExcel->getProperties()->setCreator("JOSE ALFREDO")->setDescription("REPORTE ALUMNOS");
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setTitle("ALUMNOS");

$estiloTituloColumnas = array(
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
        'color' => array('rgb' => 'ffccbc')
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

$objPHPExcel->getActiveSheet()->getStyle('A1:AG1')->applyFromArray($estiloTituloColumnas);

$objPHPExcel->getActiveSheet()->setCellValue('A1', 'N°');
$objPHPExcel->getActiveSheet()->setCellValue('B1', 'APELLIDO PATERNO');
$objPHPExcel->getActiveSheet()->setCellValue('C1', 'APELLIDO MATERNO');
$objPHPExcel->getActiveSheet()->setCellValue('D1', 'NOMBRES');
$objPHPExcel->getActiveSheet()->setCellValue('E1', 'DNI');
$objPHPExcel->getActiveSheet()->setCellValue('F1', 'USUARIO');
$objPHPExcel->getActiveSheet()->setCellValue('G1', 'ENTIDAD');
$objPHPExcel->getActiveSheet()->setCellValue('H1', 'CARGO');
$objPHPExcel->getActiveSheet()->setCellValue('I1', 'ÁREA');
$objPHPExcel->getActiveSheet()->setCellValue('J1', 'PROFESIÓN');
$objPHPExcel->getActiveSheet()->setCellValue('K1', 'NIVEL DE GOBIERNO');
$objPHPExcel->getActiveSheet()->setCellValue('L1', 'RUBRO');
$objPHPExcel->getActiveSheet()->setCellValue('M1', 'MODALIDAD');
$objPHPExcel->getActiveSheet()->setCellValue('N1', 'DESCRIP. MODALIDAD');
$objPHPExcel->getActiveSheet()->setCellValue('O1', 'CORREO ELECTRONICO');
$objPHPExcel->getActiveSheet()->setCellValue('P1', 'TELEFONO');
$objPHPExcel->getActiveSheet()->setCellValue('Q1', 'DEPARTAMENTO LABORAL');
$objPHPExcel->getActiveSheet()->setCellValue('R1', 'PROVINCIA LABORAL');
$objPHPExcel->getActiveSheet()->setCellValue('S1', 'DISTRITO LABORAL');
$objPHPExcel->getActiveSheet()->setCellValue('T1', 'N° OFICIO');
$objPHPExcel->getActiveSheet()->setCellValue('U1', 'GÉNERO');
$objPHPExcel->getActiveSheet()->setCellValue('V1', 'GRADO ACADEMICO');

$objPHPExcel->getActiveSheet()->setCellValue('W1', 'NOMBRE CURSO');
$objPHPExcel->getActiveSheet()->setCellValue('X1', 'FECHA INICIO');
$objPHPExcel->getActiveSheet()->setCellValue('Y1', 'FECHA FIN');
$objPHPExcel->getActiveSheet()->setCellValue('Z1', 'MODALIDAD');
$objPHPExcel->getActiveSheet()->setCellValue('AA1', 'HORAS LECTIVAS');
$objPHPExcel->getActiveSheet()->setCellValue('AB1', 'NOTA PRACTICA');
$objPHPExcel->getActiveSheet()->setCellValue('AC1', 'FORO');
$objPHPExcel->getActiveSheet()->setCellValue('AD1', 'EXAMEN FINAL');
$objPHPExcel->getActiveSheet()->setCellValue('AE1', 'PROMEDIO FINAL');
$objPHPExcel->getActiveSheet()->setCellValue('AF1', '¿TIENE CERTIFICADO?');
$objPHPExcel->getActiveSheet()->setCellValue('AG1', 'NRO CERTIFICADO');

// $objPHPExcel->getActiveSheet()->setCellValue('W1', 'DISCAPACIDAD');
// $objPHPExcel->getActiveSheet()->setCellValue('X1', 'DESCR. DISCAPACIDAD');

$fila = 2;

$datos = isset($_SESSION['reporte_alumnos']) ? $_SESSION['reporte_alumnos'] : die();

foreach ($datos as $data) {
    // echo strtoupper($data[2]);
    // die();
    $objPHPExcel->getActiveSheet()->setCellValue('A' . $fila, strtoupper($data[0]));
    $objPHPExcel->getActiveSheet()->setCellValue('B' . $fila, strtoupper($data[1]));
    $objPHPExcel->getActiveSheet()->setCellValue('C' . $fila, strtoupper($data[2]));
    $objPHPExcel->getActiveSheet()->setCellValue('D' . $fila, strtoupper($data[3]));
    $objPHPExcel->getActiveSheet()->setCellValue('E' . $fila, strtoupper($data[4]));
    $objPHPExcel->getActiveSheet()->setCellValue('F' . $fila, strtoupper($data[5]));
    $objPHPExcel->getActiveSheet()->setCellValue('G' . $fila, strtoupper($data[6]));
    $objPHPExcel->getActiveSheet()->setCellValue('H' . $fila, strtoupper($data[7]));
    $objPHPExcel->getActiveSheet()->setCellValue('I' . $fila, strtoupper($data[8]));
    $objPHPExcel->getActiveSheet()->setCellValue('J' . $fila, strtoupper($data[9]));
    $objPHPExcel->getActiveSheet()->setCellValue('K' . $fila, strtoupper($data[10]));
    $objPHPExcel->getActiveSheet()->setCellValue('L' . $fila, strtoupper($data[11]));
    $objPHPExcel->getActiveSheet()->setCellValue('M' . $fila, strtoupper($data[12]));
    $objPHPExcel->getActiveSheet()->setCellValue('N' . $fila, strtoupper($data[13]));
    $objPHPExcel->getActiveSheet()->setCellValue('O' . $fila, strtoupper($data[14]));
    $objPHPExcel->getActiveSheet()->setCellValue('P' . $fila, strtoupper($data[15]));
    $objPHPExcel->getActiveSheet()->setCellValue('Q' . $fila, strtoupper($data[16]));
    $objPHPExcel->getActiveSheet()->setCellValue('R' . $fila, strtoupper($data[17]));
    $objPHPExcel->getActiveSheet()->setCellValue('S' . $fila, strtoupper($data[18]));
    $objPHPExcel->getActiveSheet()->setCellValue('T' . $fila, strtoupper($data[19]));
    $objPHPExcel->getActiveSheet()->setCellValue('U' . $fila, strtoupper($data[20]));
    $objPHPExcel->getActiveSheet()->setCellValue('V' . $fila, strtoupper($data[21]));

    $objPHPExcel->getActiveSheet()->setCellValue('W' . $fila, strtoupper($data[24]));
    $objPHPExcel->getActiveSheet()->setCellValue('X' . $fila, strtoupper($data[25]));
    $objPHPExcel->getActiveSheet()->setCellValue('Y' . $fila, strtoupper($data[26]));
    $objPHPExcel->getActiveSheet()->setCellValue('Z' . $fila, strtoupper($data[27]));
    $objPHPExcel->getActiveSheet()->setCellValue('AA' . $fila, strtoupper($data[28]));
    $objPHPExcel->getActiveSheet()->setCellValue('AB' . $fila, strtoupper($data[29]));
    $objPHPExcel->getActiveSheet()->setCellValue('AC' . $fila, strtoupper($data[30]));
    $objPHPExcel->getActiveSheet()->setCellValue('AD' . $fila, strtoupper($data[31]));
    $objPHPExcel->getActiveSheet()->setCellValue('AE' . $fila, strtoupper($data[32]));
    $objPHPExcel->getActiveSheet()->setCellValue('AF' . $fila, strtoupper($data[33]));
    $objPHPExcel->getActiveSheet()->setCellValue('AG' . $fila, strtoupper($data[34]));

    // $objPHPExcel->getActiveSheet()->setCellValue('W' . $fila, strtoupper($data[22]));
    // $objPHPExcel->getActiveSheet()->setCellValue('X' . $fila, strtoupper($data[23]));
    $fila++;
}

$fila = $fila - 1;

$objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A2:AG" . $fila);

$objPHPExcel->getActiveSheet()->getStyle("A1:U{$fila}")->getAlignment()->setWrapText(true);

foreach (range('A', 'AG') as $columnID) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(false);
}

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth("5");
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth("25");
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth("25");
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth("40");
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth("20");
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth("20");
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth("40");
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth("20");
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth("20");
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth("20");
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth("20");
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth("20");
$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth("20");
$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth("30");
$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth("30");
$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth("20");
$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth("20");
$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth("20");
$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth("20");
$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth("20");
$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth("15");
$objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth("20");

$objPHPExcel->getActiveSheet()->getColumnDimension('W')->setWidth("50");
$objPHPExcel->getActiveSheet()->getColumnDimension('X')->setWidth("15");
$objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setWidth("15");
$objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setWidth("15");
$objPHPExcel->getActiveSheet()->getColumnDimension('AA')->setWidth("15");
$objPHPExcel->getActiveSheet()->getColumnDimension('AB')->setWidth("15");
$objPHPExcel->getActiveSheet()->getColumnDimension('AC')->setWidth("15");
$objPHPExcel->getActiveSheet()->getColumnDimension('AD')->setWidth("15");
$objPHPExcel->getActiveSheet()->getColumnDimension('AE')->setWidth("15");
$objPHPExcel->getActiveSheet()->getColumnDimension('AF')->setWidth("15");
$objPHPExcel->getActiveSheet()->getColumnDimension('AG')->setWidth("15");

// $objPHPExcel->getActiveSheet()->getColumnDimension('W')->setWidth("20");
// $objPHPExcel->getActiveSheet()->getColumnDimension('X')->setWidth("30");

foreach (range(1, 1) as $fila) {
    $objPHPExcel->getActiveSheet()->getRowDimension($fila)->setRowHeight(30);
}

unset($_SESSION['reporte_alumnos']);

header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header('Content-Disposition: attachment;filename="Reporte_alumnos.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$objWriter->save('php://output');
$objPHPExcel->disconnectWorksheets();
$objPHPExcel->garbageCollect();
unset($objPHPExcel);
exit();
