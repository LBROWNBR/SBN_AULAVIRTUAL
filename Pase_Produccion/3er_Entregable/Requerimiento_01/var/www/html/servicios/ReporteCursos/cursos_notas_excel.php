<?php
session_start();

require_once '../../app/libs/excel/PHPExcel.php';

$objPHPExcel = new PHPExcel();

$objPHPExcel->getProperties()->setCreator("JOSE ALFREDO")->setDescription("REPORTE NOTAS");

$objPHPExcel->setActiveSheetIndex(0);
$datos = isset($_SESSION['reporte_alumn_notas']) ? $_SESSION['reporte_alumn_notas'] : die();
$objPHPExcel->getActiveSheet()->setTitle(strtoupper($datos['curso']));

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

$styleNamesPruebas = array(
    'font' => array(
        'name'  => 'Calibri',
        'bold'  => true,
        'size' => 11,
        'color' => array(
            'rgb' => '000000'
        )
    ),
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array('rgb' => 'bbdefb')
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

// var_dump($datos['prueb']);
// die();

$lastColumn = 'E';
$countTitle = 3;
foreach ($datos['prueb'] as $col) {
    $lastColumn++;
    $iniCol = $lastColumn;
    $objPHPExcel->getActiveSheet()->setCellValue($lastColumn . '1', strtoupper($col));
    $lastColumn++;
    $lastColumn++;
    $finCol = $lastColumn;
    // $objPHPExcel->getActiveSheet()->mergeCells("G1:I1");
    $objPHPExcel->getActiveSheet()->mergeCells("{$iniCol}1:{$finCol}1");
    $objPHPExcel->getActiveSheet()->getStyle("{$iniCol}1:{$finCol}1")->applyFromArray($styleNamesPruebas);
}

$objPHPExcel->getActiveSheet()->setCellValue('A2', 'N°');
$objPHPExcel->getActiveSheet()->setCellValue('B2', 'APELLIDO PATERNO');
$objPHPExcel->getActiveSheet()->setCellValue('C2', 'APELLIDO MATERNO');
$objPHPExcel->getActiveSheet()->setCellValue('D2', 'NOMBRES');
$objPHPExcel->getActiveSheet()->setCellValue('E2', 'USUARIO');
$objPHPExcel->getActiveSheet()->setCellValue('F2', 'ENTIDAD');

// die();

$fila = 3;

foreach ($datos['datos'] as $data) {
    $objPHPExcel->getActiveSheet()->setCellValue('A' . $fila, strtoupper($data[0]));
    $objPHPExcel->getActiveSheet()->setCellValue('B' . $fila, strtoupper($data[1]));
    $objPHPExcel->getActiveSheet()->setCellValue('C' . $fila, strtoupper($data[2]));
    $objPHPExcel->getActiveSheet()->setCellValue('D' . $fila, strtoupper($data[3]));
    $objPHPExcel->getActiveSheet()->setCellValue('E' . $fila, strtoupper($data[4]));
    // $objPHPExcel->getActiveSheet()->setCellValue('F' . $fila, strtoupper($data[5]));
    $lastColumn = 'E';
    $campos = [];
    $notas = [];
    foreach ($data[6] as $nota) {
        $notas[] = $nota['fech'];
        $notas[] = $nota['nota'];
        $notas[] = $nota['estd'];
        $campos[] = 'FECHA';
        $campos[] = 'NOTA';
        $campos[] = 'ESTADO';
    }
    foreach ($notas as $key => $col) {
        $lastColumn++;
        $objPHPExcel->getActiveSheet()->setCellValue($lastColumn . $fila, $col);
        $objPHPExcel->getActiveSheet()->setCellValue($lastColumn . '2', $campos[$key]);
        $objPHPExcel->getActiveSheet()->getColumnDimension($lastColumn)->setWidth("15");
    }

    $lastColumn++;
    $PromedioFinal = ($data[7]) ? $data[7][0]['PROM_FINAL'] : '0';
    $objPHPExcel->getActiveSheet()->setCellValue($lastColumn . $fila, $PromedioFinal);
    $objPHPExcel->getActiveSheet()->setCellValue($lastColumn . '2', 'PROM. FINAL');
    $objPHPExcel->getActiveSheet()->getColumnDimension($lastColumn)->setWidth("15");

    $lastColumn++;
    $objPHPExcel->getActiveSheet()->setCellValue($lastColumn . $fila, strtoupper($data[8]));
    $objPHPExcel->getActiveSheet()->setCellValue($lastColumn . '2', 'CURSO');
    $objPHPExcel->getActiveSheet()->getColumnDimension($lastColumn)->setWidth("110");

    $lastColumn++;
    $objPHPExcel->getActiveSheet()->setCellValue($lastColumn . $fila, strtoupper($data[9]));
    $objPHPExcel->getActiveSheet()->setCellValue($lastColumn . '2', 'FECHA INI');
    $objPHPExcel->getActiveSheet()->getColumnDimension($lastColumn)->setWidth("15");

    $lastColumn++;
    $objPHPExcel->getActiveSheet()->setCellValue($lastColumn . $fila, strtoupper($data[10]));
    $objPHPExcel->getActiveSheet()->setCellValue($lastColumn . '2', 'FECHA FIN');
    $objPHPExcel->getActiveSheet()->getColumnDimension($lastColumn)->setWidth("15");

    $lastColumn++;
    $objPHPExcel->getActiveSheet()->setCellValue($lastColumn . $fila, strtoupper($data[11]));
    $objPHPExcel->getActiveSheet()->setCellValue($lastColumn . '2', 'MODALIDAD');
    $objPHPExcel->getActiveSheet()->getColumnDimension($lastColumn)->setWidth("15");

    $lastColumn++;
    $objPHPExcel->getActiveSheet()->setCellValue($lastColumn . $fila, strtoupper($data[12]));
    $objPHPExcel->getActiveSheet()->setCellValue($lastColumn . '2', 'HORA LECTIVA');
    $objPHPExcel->getActiveSheet()->getColumnDimension($lastColumn)->setWidth("15");

    $lastColumn++;
    $objPHPExcel->getActiveSheet()->setCellValue($lastColumn . $fila, strtoupper($data[13]));
    $objPHPExcel->getActiveSheet()->setCellValue($lastColumn . '2', 'CODIGO POI');
    $objPHPExcel->getActiveSheet()->getColumnDimension($lastColumn)->setWidth("15");

    $lastColumn++;
    $objPHPExcel->getActiveSheet()->setCellValue($lastColumn . $fila, strtoupper($data[14]));
    $objPHPExcel->getActiveSheet()->setCellValue($lastColumn . '2', '¿TIENE CERTIFICADO?');
    $objPHPExcel->getActiveSheet()->getColumnDimension($lastColumn)->setWidth("15");

    $lastColumn++;
    $objPHPExcel->getActiveSheet()->setCellValue($lastColumn . $fila, strtoupper($data[15]));
    $objPHPExcel->getActiveSheet()->setCellValue($lastColumn . '2', 'CODIGO CERTIFICADO');
    $objPHPExcel->getActiveSheet()->getColumnDimension($lastColumn)->setWidth("15");

    $fila++;
}

$fila = $fila - 1;

$ultColum = $objPHPExcel->getActiveSheet()->getHighestColumn();

$objPHPExcel->getActiveSheet()->getStyle("A2:{$ultColum}2")->applyFromArray($estiloTituloColumnas);
$objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A3:{$ultColum}" . $fila);

$objPHPExcel->getActiveSheet()->getStyle("A2:{$ultColum}{$fila}")->getAlignment()->setWrapText(true);

foreach (range('A', $ultColum) as $columnID) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(false);
}

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth("5");
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth("25");
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth("25");
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth("40");
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth("20");
// $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth("20");

/*
foreach (range('F', $ultColum) as $columnID) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setWidth("15");
}
*/

$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->applyFromArray($styleTitulo);
$objPHPExcel->getActiveSheet()->mergeCells('A1:E1');

foreach (range(2, $fila) as $row) {
    $objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(20);
}


unset($_SESSION['reporte_alumn_notas']);

header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header('Content-Disposition: attachment;filename="Reporte_notas_alumnos.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$objWriter->save('php://output');
exit();