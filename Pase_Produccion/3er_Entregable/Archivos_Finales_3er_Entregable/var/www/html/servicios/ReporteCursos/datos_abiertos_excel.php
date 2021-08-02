<?php
session_start();

require_once '../../app/libs/excel/PHPExcel.php';

$objPHPExcel = new PHPExcel();

$objPHPExcel->getProperties()->setCreator("LUIS BROWN")->setDescription("REPORTE DATOS ABIERTOS");
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setTitle("DATOS_ABIERTOS");

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

$datos = isset($_SESSION['reporte_datos_abiertos']) ? $_SESSION['reporte_datos_abiertos'] : die();

$objPHPExcel->getActiveSheet()->setCellValue('A2', 'MODALIDAD');
$objPHPExcel->getActiveSheet()->setCellValue('B2', 'NOMBRE CURSO');
$objPHPExcel->getActiveSheet()->setCellValue('C2', 'FECHA INICIO');
$objPHPExcel->getActiveSheet()->setCellValue('D2', 'FECHA FIN');
$objPHPExcel->getActiveSheet()->setCellValue('E2', 'HORAS LECTIVAS');
$objPHPExcel->getActiveSheet()->setCellValue('F2', 'DNI');
$objPHPExcel->getActiveSheet()->setCellValue('G2', 'NOMBRES');
$objPHPExcel->getActiveSheet()->setCellValue('H2', 'AP. PATERNO');
$objPHPExcel->getActiveSheet()->setCellValue('I2', 'AP. MATERNO');
$objPHPExcel->getActiveSheet()->setCellValue('J2', 'PROFESION');
$objPHPExcel->getActiveSheet()->setCellValue('K2', 'ENTIDAD');
$objPHPExcel->getActiveSheet()->setCellValue('L2', 'DEPARTAMENTO');
$objPHPExcel->getActiveSheet()->setCellValue('M2', 'PROVINCIA');
$objPHPExcel->getActiveSheet()->setCellValue('N2', 'DISTRITO');
$objPHPExcel->getActiveSheet()->setCellValue('O2', 'NIVEL GOBIERNO');

$objPHPExcel->getActiveSheet()->getStyle('A2:O2')->applyFromArray($estiloTituloColumnas);

$fila = 3;


// echo json_encode($datos);
// die();

foreach ($datos['datos'] as $data) {

    if($data[0]!=''){

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
    
        $fila++;
        
    }

}

$fila = $fila - 1;

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth("15");
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth("90");
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth("18");
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth("18");
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth("18");
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth("18");
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth("25");
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth("20");
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth("20");
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth("25");
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth("90");
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth("20");
$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth("20");
$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth("20");
$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth("20");



$objPHPExcel->getActiveSheet()->setCellValue('A1', 'CURSO "' . strtoupper($datos['curso']) . '"');
$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
$objPHPExcel->getActiveSheet()->getStyle('A1:O1')->applyFromArray($styleTitulo);
$objPHPExcel->getActiveSheet()->mergeCells('A1:O1');

unset($_SESSION['reporte_datos_abiertos']);

header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header('Content-Disposition: attachment;filename="Reporte_Datos_Abiertos.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$objWriter->save('php://output');
exit();
