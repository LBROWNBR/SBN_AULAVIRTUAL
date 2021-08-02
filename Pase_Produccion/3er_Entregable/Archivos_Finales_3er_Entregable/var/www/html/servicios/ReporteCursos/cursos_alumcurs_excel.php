<?php
session_start();

require_once '../../app/libs/excel/PHPExcel.php';

$objPHPExcel = new PHPExcel();

$objPHPExcel->getProperties()->setCreator("JOSE ALFREDO")->setDescription("REPORTE");
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setTitle("CURSOS");

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
        'color' => array('rgb' => '9ccc65')
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

$styleCenter = array(
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


$datos = isset($_SESSION['reporte_alumnos_cursos']) ? $_SESSION['reporte_alumnos_cursos'] : die();


$objPHPExcel->getActiveSheet()->getStyle('A2:AF2')->applyFromArray($estiloTituloColumnas);

$objPHPExcel->getActiveSheet()->setCellValue('A2', 'CURSO');
$objPHPExcel->getActiveSheet()->setCellValue('B2', 'NOMBRE COMPLETO');
$objPHPExcel->getActiveSheet()->setCellValue('C2', 'FECHA INICIO');
$objPHPExcel->getActiveSheet()->setCellValue('D2', 'FECHA FIN');
$objPHPExcel->getActiveSheet()->setCellValue('E2', 'N° INSCRITOS');
$objPHPExcel->getActiveSheet()->setCellValue('F2', 'CODIGO');
$objPHPExcel->getActiveSheet()->setCellValue('G2', 'APELLIDO PATERNO');
$objPHPExcel->getActiveSheet()->setCellValue('H2', 'APELLIDO MATERNO');
$objPHPExcel->getActiveSheet()->setCellValue('I2', 'NOMBRES');
$objPHPExcel->getActiveSheet()->setCellValue('J2', 'DNI');
$objPHPExcel->getActiveSheet()->setCellValue('K2', 'ENTIDAD');
$objPHPExcel->getActiveSheet()->setCellValue('L2', 'FECHA REGISTRO');
$objPHPExcel->getActiveSheet()->setCellValue('M2', 'NIVEL GOBIERNO');
$objPHPExcel->getActiveSheet()->setCellValue('N2', 'RUBRO');
$objPHPExcel->getActiveSheet()->setCellValue('O2', 'DEPARTAMENTO');
$objPHPExcel->getActiveSheet()->setCellValue('P2', 'PROVINCIA');
$objPHPExcel->getActiveSheet()->setCellValue('Q2', 'DISTRITO');
$objPHPExcel->getActiveSheet()->setCellValue('R2', 'GENERO');
$objPHPExcel->getActiveSheet()->setCellValue('S2', 'PROFESIÓN');
$objPHPExcel->getActiveSheet()->setCellValue('T2', 'RUC');
$objPHPExcel->getActiveSheet()->setCellValue('U2', 'ÁREA');
$objPHPExcel->getActiveSheet()->setCellValue('V2', 'CARGO');
$objPHPExcel->getActiveSheet()->setCellValue('W2', 'MODALIDAD');
$objPHPExcel->getActiveSheet()->setCellValue('X2', 'DESCRIP. MODALIDAD');
$objPHPExcel->getActiveSheet()->setCellValue('Y2', 'EMAIL');
$objPHPExcel->getActiveSheet()->setCellValue('Z2', 'TELÉFONO');
$objPHPExcel->getActiveSheet()->setCellValue('AA2', 'N° OFICION DE INSCRIPCION');
$objPHPExcel->getActiveSheet()->setCellValue('AB2', '¿ADJUNTÓ ARCHIVO?');

$objPHPExcel->getActiveSheet()->setCellValue('AC2', 'HORA LECTIVAS');
$objPHPExcel->getActiveSheet()->setCellValue('AD2', 'CODIGO POI');
$objPHPExcel->getActiveSheet()->setCellValue('AE2', '¿TIENE CERTIFICADO?');
$objPHPExcel->getActiveSheet()->setCellValue('AF2', 'CODIGO CERTIFICADO');



$lastColumn = 'AF';
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




$fila = 3;



//$datos = array_merge($datos, $datos);

for ($i = 0; $i < count($datos['datos']); $i++) {

    $ini = $fila;

    $curso_codigo = '';
    $curso_nombre = '';
    $curso_fecIni = '';
    $curso_fecFin = '';
    $curso_numIns = count($datos['datos'][$i]);
    foreach ($datos['datos'][$i] as $data) {
        $curso_codigo = strtoupper($data[0]);
        $curso_nombre = strtoupper($data[1]);
        $curso_fecIni = strtoupper($data[2]);
        $curso_fecFin = strtoupper($data[3]);

        $objPHPExcel->getActiveSheet()->setCellValue('A' . $fila, $curso_codigo);
        $objPHPExcel->getActiveSheet()->setCellValue('B' . $fila, $curso_nombre);
        $objPHPExcel->getActiveSheet()->setCellValue('C' . $fila, $curso_fecIni);
        $objPHPExcel->getActiveSheet()->setCellValue('D' . $fila, $curso_fecFin);
        $objPHPExcel->getActiveSheet()->setCellValue('E' . $fila, $curso_numIns);
        
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
        $objPHPExcel->getActiveSheet()->setCellValue('W' . $fila, strtoupper($data[22]));
        $objPHPExcel->getActiveSheet()->setCellValue('X' . $fila, strtoupper($data[23]));
        $objPHPExcel->getActiveSheet()->setCellValue('Y' . $fila, strtoupper($data[24]));
        $objPHPExcel->getActiveSheet()->setCellValue('Z' . $fila, strtoupper($data[25]));
        $objPHPExcel->getActiveSheet()->setCellValue('AA' . $fila, strtoupper($data[26]));
        $objPHPExcel->getActiveSheet()->setCellValue('AB' . $fila, strtoupper($data[27]));

        $objPHPExcel->getActiveSheet()->setCellValue('AC' . $fila, strtoupper($data[28]));
        $objPHPExcel->getActiveSheet()->setCellValue('AD' . $fila, strtoupper($data[29]));
        $objPHPExcel->getActiveSheet()->setCellValue('AE' . $fila, strtoupper($data[30]));
        $objPHPExcel->getActiveSheet()->setCellValue('AF' . $fila, strtoupper($data[31]));

        $lastColumn = 'AF';
        $campos = [];
        $notas = [];
        foreach ($data[32] as $nota) {
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
        $PromedioFinal = ($data[33]) ? $data[33][0]['PROM_FINAL'] : '0';
        $objPHPExcel->getActiveSheet()->setCellValue($lastColumn . $fila, $PromedioFinal);
        $objPHPExcel->getActiveSheet()->setCellValue($lastColumn . '2', 'PROM. FINAL');
        $objPHPExcel->getActiveSheet()->getColumnDimension($lastColumn)->setWidth("15");

        $fila++;
    }

    /*
    $objPHPExcel->getActiveSheet()->setCellValue('A' . $ini, $curso_codigo);
    $objPHPExcel->getActiveSheet()->setCellValue('B' . $ini, $curso_nombre);
    $objPHPExcel->getActiveSheet()->setCellValue('C' . $ini, $curso_fecIni);
    $objPHPExcel->getActiveSheet()->setCellValue('D' . $ini, $curso_fecFin);
    $objPHPExcel->getActiveSheet()->setCellValue('E' . $ini, $curso_numIns);
    */

    $fin = $fila - 1;

    /*
    $objPHPExcel->getActiveSheet()->mergeCells("A{$ini}:A{$fin}");
    $objPHPExcel->getActiveSheet()->mergeCells("B{$ini}:B{$fin}");
    $objPHPExcel->getActiveSheet()->mergeCells("C{$ini}:C{$fin}");
    $objPHPExcel->getActiveSheet()->mergeCells("D{$ini}:D{$fin}");
    $objPHPExcel->getActiveSheet()->mergeCells("E{$ini}:E{$fin}");
    */
}

$fila = $fila - 1;

$ultColum = $objPHPExcel->getActiveSheet()->getHighestColumn();

$objPHPExcel->getActiveSheet()->getStyle("A2:{$ultColum}2")->applyFromArray($estiloTituloColumnas);
$objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A3:{$ultColum}" . $fila);

$objPHPExcel->getActiveSheet()->getStyle("A2:{$ultColum}{$fila}")->getAlignment()->setWrapText(true);

foreach (range('A', $ultColum) as $columnID) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(false);
}


$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth("25");
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth("90");
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth("20");
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth("20");
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth("20");
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth("20");
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth("20");
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth("20");
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth("35");
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth("15");
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth("90");
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth("20");
$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth("20");
$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth("20");
$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth("20");
$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth("20");
$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth("20");
$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth("15");
$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth("25");
$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth("20");
$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth("90");
$objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth("20");
$objPHPExcel->getActiveSheet()->getColumnDimension('W')->setWidth("20");
$objPHPExcel->getActiveSheet()->getColumnDimension('X')->setWidth("30");
$objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setWidth("80");
$objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setWidth("20");
$objPHPExcel->getActiveSheet()->getColumnDimension('AA')->setWidth("20");
$objPHPExcel->getActiveSheet()->getColumnDimension('AB')->setWidth("20");

$objPHPExcel->getActiveSheet()->getColumnDimension('AC')->setWidth("20");
$objPHPExcel->getActiveSheet()->getColumnDimension('AD')->setWidth("20");
$objPHPExcel->getActiveSheet()->getColumnDimension('AE')->setWidth("20");
$objPHPExcel->getActiveSheet()->getColumnDimension('AF')->setWidth("20");

unset($_SESSION['reporte_alumnos_cursos']);

header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header('Content-Disposition: attachment;filename="Reporte_general_cursos.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$objWriter->save('php://output');
exit();
