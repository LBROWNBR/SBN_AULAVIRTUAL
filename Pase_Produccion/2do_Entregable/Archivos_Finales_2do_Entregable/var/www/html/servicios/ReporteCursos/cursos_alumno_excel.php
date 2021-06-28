<?php
session_start();

require_once '../../app/libs/excel/PHPExcel.php';

$objPHPExcel = new PHPExcel();

$objPHPExcel->getProperties()->setCreator("JOSE ALFREDO")->setDescription("REPORTE ALUMNOS");
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setTitle("ALUMNOS");

$styleTitulo = array(
    'font' => array(
        'name'  => 'Calibri',
        'bold'  => true,
        'size' => 12,
        'color' => array(
            'rgb' => '000000'
        )
    ),
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array('rgb' => 'ffca28')
    ),
    'alignment' =>  array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical'  => PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
);

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

$styleCenter = array(
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


$objPHPExcel->getActiveSheet()->getStyle('A2:AK2')->applyFromArray($estiloTituloColumnas);

$objPHPExcel->getActiveSheet()->setCellValue('A2', 'N°');
$objPHPExcel->getActiveSheet()->setCellValue('B2', 'COD. CERTIFICADO');
$objPHPExcel->getActiveSheet()->setCellValue('C2', 'CODIGO');
$objPHPExcel->getActiveSheet()->setCellValue('D2', 'APELLIDO PATERNO');
$objPHPExcel->getActiveSheet()->setCellValue('E2', 'APELLIDO MATERNO');
$objPHPExcel->getActiveSheet()->setCellValue('F2', 'NOMBRES');
$objPHPExcel->getActiveSheet()->setCellValue('G2', 'DNI');
$objPHPExcel->getActiveSheet()->setCellValue('H2', 'ENTIDAD');
$objPHPExcel->getActiveSheet()->setCellValue('I2', 'FECHA REGISTRO');
$objPHPExcel->getActiveSheet()->setCellValue('J2', 'NIVEL GOBIERNO');
$objPHPExcel->getActiveSheet()->setCellValue('K2', 'RUBRO');
$objPHPExcel->getActiveSheet()->setCellValue('L2', 'DEPARTAMENTO LABORAL');
$objPHPExcel->getActiveSheet()->setCellValue('M2', 'PROVINCIA LABORAL');
$objPHPExcel->getActiveSheet()->setCellValue('N2', 'DISTRITO LABORAL');
$objPHPExcel->getActiveSheet()->setCellValue('O2', 'GENERO');
$objPHPExcel->getActiveSheet()->setCellValue('P2', 'PROFESIÓN');
$objPHPExcel->getActiveSheet()->setCellValue('Q2', 'RUC');
$objPHPExcel->getActiveSheet()->setCellValue('R2', 'ÁREA');
$objPHPExcel->getActiveSheet()->setCellValue('S2', 'CARGO');
$objPHPExcel->getActiveSheet()->setCellValue('T2', 'MODALIDAD');
$objPHPExcel->getActiveSheet()->setCellValue('U2', 'DESCRIP. MODALIDAD');
$objPHPExcel->getActiveSheet()->setCellValue('V2', 'EMAIL');
$objPHPExcel->getActiveSheet()->setCellValue('W2', 'TELÉFONO');
$objPHPExcel->getActiveSheet()->setCellValue('X2', 'N° OFICION DE INSCRIPCION');
$objPHPExcel->getActiveSheet()->setCellValue('Y2', '¿ADJUNTÓ ARCHIVO?');

$objPHPExcel->getActiveSheet()->setCellValue('Z2', 'NOMBRE CURSO');
$objPHPExcel->getActiveSheet()->setCellValue('AA2', 'FECHA INICIO');
$objPHPExcel->getActiveSheet()->setCellValue('AB2', 'FECHA FIN');
$objPHPExcel->getActiveSheet()->setCellValue('AC2', 'MODALIDAD');
$objPHPExcel->getActiveSheet()->setCellValue('AD2', 'HORAS LECTIVAS');
$objPHPExcel->getActiveSheet()->setCellValue('AE2', 'NOTA PRACTICA');
$objPHPExcel->getActiveSheet()->setCellValue('AF2', 'FORO');
$objPHPExcel->getActiveSheet()->setCellValue('AG2', 'EXAMEN FINAL');
$objPHPExcel->getActiveSheet()->setCellValue('AH2', 'PROMEDIO FINAL');
$objPHPExcel->getActiveSheet()->setCellValue('AI2', 'CODIGO POI');
$objPHPExcel->getActiveSheet()->setCellValue('AJ2', '¿TIENE CERTIFICADO?');
$objPHPExcel->getActiveSheet()->setCellValue('AK2', 'NRO CERTIFICADO');

$fila = 3;

$datos = isset($_SESSION['reporte_alu_inscritos']) ? $_SESSION['reporte_alu_inscritos'] : die();

// echo json_encode($datos);
// die();

foreach ($datos['datos'] as $data) {
    $objPHPExcel->getActiveSheet()->setCellValue('A' . $fila, strtoupper($data[0]));
    $objPHPExcel->getActiveSheet()->setCellValue('B' . $fila, strtoupper($data[24]));
    $objPHPExcel->getActiveSheet()->setCellValue('C' . $fila, strtoupper($data[1]));
    $objPHPExcel->getActiveSheet()->setCellValue('D' . $fila, strtoupper($data[2]));
    $objPHPExcel->getActiveSheet()->setCellValue('E' . $fila, strtoupper($data[3]));
    $objPHPExcel->getActiveSheet()->setCellValue('F' . $fila, strtoupper($data[4]));
    $objPHPExcel->getActiveSheet()->setCellValue('G' . $fila, strtoupper($data[5]));
    $objPHPExcel->getActiveSheet()->setCellValue('H' . $fila, strtoupper($data[6]));
    $objPHPExcel->getActiveSheet()->setCellValue('I' . $fila, strtoupper($data[7]));
    $objPHPExcel->getActiveSheet()->setCellValue('J' . $fila, strtoupper($data[8]));
    $objPHPExcel->getActiveSheet()->setCellValue('K' . $fila, strtoupper($data[9]));
    $objPHPExcel->getActiveSheet()->setCellValue('L' . $fila, strtoupper($data[10]));
    $objPHPExcel->getActiveSheet()->setCellValue('M' . $fila, strtoupper($data[11]));
    $objPHPExcel->getActiveSheet()->setCellValue('N' . $fila, strtoupper($data[12]));
    $objPHPExcel->getActiveSheet()->setCellValue('O' . $fila, strtoupper($data[13]));
    $objPHPExcel->getActiveSheet()->setCellValue('P' . $fila, strtoupper($data[14]));
    $objPHPExcel->getActiveSheet()->setCellValue('Q' . $fila, strtoupper($data[15]));
    $objPHPExcel->getActiveSheet()->setCellValue('R' . $fila, strtoupper($data[16]));
    $objPHPExcel->getActiveSheet()->setCellValue('S' . $fila, strtoupper($data[17]));
    $objPHPExcel->getActiveSheet()->setCellValue('T' . $fila, strtoupper($data[18]));
    $objPHPExcel->getActiveSheet()->setCellValue('U' . $fila, strtoupper($data[19]));
    $objPHPExcel->getActiveSheet()->setCellValue('V' . $fila, strtoupper($data[20]));
    $objPHPExcel->getActiveSheet()->setCellValue('W' . $fila, strtoupper($data[21]));
    $objPHPExcel->getActiveSheet()->setCellValue('X' . $fila, strtoupper($data[22]));
    $objPHPExcel->getActiveSheet()->setCellValue('Y' . $fila, strtoupper($data[23]));

    $objPHPExcel->getActiveSheet()->setCellValue('Z' . $fila, strtoupper($data[25]));
    $objPHPExcel->getActiveSheet()->setCellValue('AA' . $fila, strtoupper($data[26]));
    $objPHPExcel->getActiveSheet()->setCellValue('AB' . $fila, strtoupper($data[27]));
    $objPHPExcel->getActiveSheet()->setCellValue('AC' . $fila, strtoupper($data[28]));
    $objPHPExcel->getActiveSheet()->setCellValue('AD' . $fila, strtoupper($data[29]));
    $objPHPExcel->getActiveSheet()->setCellValue('AE' . $fila, strtoupper($data[30]));
    $objPHPExcel->getActiveSheet()->setCellValue('AF' . $fila, strtoupper($data[31]));
    $objPHPExcel->getActiveSheet()->setCellValue('AG' . $fila, strtoupper($data[32]));
    $objPHPExcel->getActiveSheet()->setCellValue('AH' . $fila, strtoupper($data[33]));
    $objPHPExcel->getActiveSheet()->setCellValue('AI' . $fila, strtoupper($data[34]));
    $objPHPExcel->getActiveSheet()->setCellValue('AJ' . $fila, strtoupper($data[35]));
    $objPHPExcel->getActiveSheet()->setCellValue('AK' . $fila, strtoupper($data[36]));
    $fila++;
}

$fila = $fila - 1;

$objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A3:AK" . $fila);
$objPHPExcel->getActiveSheet()->getStyle("A2:B{$fila}")->applyFromArray($styleCenter);

$objPHPExcel->getActiveSheet()->getStyle("A2:AK{$fila}")->getAlignment()->setWrapText(true);

foreach (range('A', 'AK') as $columnID) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(false);
}

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth("5");
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth("25");
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth("25");
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth("25");
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth("40");
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth("20");
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth("20");
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth("40");
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth("20");
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth("20");
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth("20");
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth("20");
$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth("20");
$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth("20");
$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth("15");
$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth("30");
$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth("20");
$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth("20");
$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth("20");
$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth("20");
$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth("30");
$objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth("30");
$objPHPExcel->getActiveSheet()->getColumnDimension('W')->setWidth("20");
$objPHPExcel->getActiveSheet()->getColumnDimension('X')->setWidth("20");
$objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setWidth("20");

$objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setWidth("120");
$objPHPExcel->getActiveSheet()->getColumnDimension('AA')->setWidth("15");
$objPHPExcel->getActiveSheet()->getColumnDimension('AB')->setWidth("15");
$objPHPExcel->getActiveSheet()->getColumnDimension('AC')->setWidth("20");
$objPHPExcel->getActiveSheet()->getColumnDimension('AD')->setWidth("20");
$objPHPExcel->getActiveSheet()->getColumnDimension('AE')->setWidth("15");
$objPHPExcel->getActiveSheet()->getColumnDimension('AF')->setWidth("15");
$objPHPExcel->getActiveSheet()->getColumnDimension('AG')->setWidth("15");
$objPHPExcel->getActiveSheet()->getColumnDimension('AH')->setWidth("15");
$objPHPExcel->getActiveSheet()->getColumnDimension('AI')->setWidth("20");
$objPHPExcel->getActiveSheet()->getColumnDimension('AJ')->setWidth("20");
$objPHPExcel->getActiveSheet()->getColumnDimension('AK')->setWidth("20");

foreach (range(1, 1) as $fila) {
    $objPHPExcel->getActiveSheet()->getRowDimension($fila)->setRowHeight(30);
}

$objPHPExcel->getActiveSheet()->setCellValue('A1', 'CURSO "' . strtoupper($datos['curso']) . '"');
$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
$objPHPExcel->getActiveSheet()->getStyle('A1:AK1')->applyFromArray($styleTitulo);
$objPHPExcel->getActiveSheet()->mergeCells('A1:AK1');

unset($_SESSION['reporte_alu_inscritos']);

header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header('Content-Disposition: attachment;filename="Reporte_alumnos_inscritos.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$objWriter->save('php://output');
exit();
