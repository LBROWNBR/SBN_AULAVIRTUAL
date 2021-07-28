<?php
session_start();

require_once '../../app/libs/excel/PHPExcel.php';

$objPHPExcel = new PHPExcel();

$objPHPExcel->getProperties()->setCreator("JOSE ALFREDO")->setDescription("REPORTE ENTIDADES INSCRITAS");
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setTitle("ENTIDADES INSCRITAS");

$styleTitulo = array(
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
        'color' => array('rgb' => 'ffffff')
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
        'color' => array('rgb' => '90caf9')
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

$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(30);
$objPHPExcel->getActiveSheet()->getStyle('A2:Q2')->applyFromArray($estiloTituloColumnas);

$objPHPExcel->getActiveSheet()->setCellValue('A2', 'N°');
$objPHPExcel->getActiveSheet()->setCellValue('B2', 'NRO EVENTO');
$objPHPExcel->getActiveSheet()->setCellValue('C2', 'DEPARTAMENTO LABORAL');
$objPHPExcel->getActiveSheet()->setCellValue('D2', 'PROVINCIA LABORAL');
$objPHPExcel->getActiveSheet()->setCellValue('E2', 'DISTRITO LABORAL');
$objPHPExcel->getActiveSheet()->setCellValue('F2', 'FECHA INICIO');
$objPHPExcel->getActiveSheet()->setCellValue('G2', 'FECHA FIN');
$objPHPExcel->getActiveSheet()->setCellValue('H2', 'DOMINACIÓN DEL CURSO');
$objPHPExcel->getActiveSheet()->setCellValue('I2', 'NOMBRE DE LA ENTIDAD');
$objPHPExcel->getActiveSheet()->setCellValue('J2', 'NIVEL DE GOBIERNO');
$objPHPExcel->getActiveSheet()->setCellValue('K2', 'NOMBRES Y APELLIDOS');
$objPHPExcel->getActiveSheet()->setCellValue('L2', 'GRADO ACADEMICO');
$objPHPExcel->getActiveSheet()->setCellValue('M2', 'PROFESION');
$objPHPExcel->getActiveSheet()->setCellValue('N2', 'CARGO');
$objPHPExcel->getActiveSheet()->setCellValue('O2', 'TIPO CONTRATO');
$objPHPExcel->getActiveSheet()->setCellValue('P2', 'AREA LABORAL');
$objPHPExcel->getActiveSheet()->setCellValue('Q2', 'COD. POI');

$fila = 3;

$entInscritas = isset($_SESSION['reporte_ent_inscritas']) ? $_SESSION['reporte_ent_inscritas'] : die();


$objPHPExcel->getActiveSheet()->setCellValue('A1', 'DETALLE DEL CURSO "' . strtoupper($entInscritas['dataEntidades'][0]['course_name']) . '" A ENTIDADES SNBE (SDNC)');
$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
$objPHPExcel->getActiveSheet()->mergeCells("A1:Q1");
$objPHPExcel->getActiveSheet()->getStyle('A1:Q1')->applyFromArray($styleTitulo);

$acu = 0;
foreach ($entInscritas['dataCertificacion'] as $data) {
    $inicio = $fila;
    $acu++;
    $objPHPExcel->getActiveSheet()->setCellValue('A' . $fila, strtoupper($acu));
    $objPHPExcel->getActiveSheet()->setCellValue('B' . $fila, strtoupper($data['mat_nroevento']));
    $objPHPExcel->getActiveSheet()->setCellValue('C' . $fila, strtoupper($data['mdl_depa_lab']));
    $objPHPExcel->getActiveSheet()->setCellValue('D' . $fila, strtoupper($data['mdl_prov_lab']));
    $objPHPExcel->getActiveSheet()->setCellValue('E' . $fila, strtoupper($data['mdl_dist_lab']));
    $objPHPExcel->getActiveSheet()->setCellValue('F' . $fila, strtoupper($data['mat_fechaini'])); 
    $objPHPExcel->getActiveSheet()->setCellValue('G' . $fila, strtoupper($data['mat_fechafin']));
    $objPHPExcel->getActiveSheet()->setCellValue('H' . $fila, strtoupper($data['mdl_cur_fullname'].' ('.$data['mdl_cur_shortname'].')')); 
    $objPHPExcel->getActiveSheet()->setCellValue('I' . $fila, strtoupper($data['mdl_entidad'])); 
    $objPHPExcel->getActiveSheet()->setCellValue('J' . $fila, strtoupper($data['mdl_nivelgrado'])); 
    $objPHPExcel->getActiveSheet()->setCellValue('K' . $fila, strtoupper(trim($data['mdl_apepat']).' '.trim($data['mdl_apemat']).' '.trim($data['mdl_nombres'])));
    $objPHPExcel->getActiveSheet()->setCellValue('L' . $fila, strtoupper($data['mdl_gradoacademico'])); 
    $objPHPExcel->getActiveSheet()->setCellValue('M' . $fila, strtoupper($data['mdl_profesion'])); 
    $objPHPExcel->getActiveSheet()->setCellValue('N' . $fila, strtoupper($data['mdl_cargo'])); 
    $objPHPExcel->getActiveSheet()->setCellValue('O' . $fila, strtoupper($data['mdl_tipocontrato'])); 
    $objPHPExcel->getActiveSheet()->setCellValue('P' . $fila, strtoupper($data['mdl_area_lab'])); 
    $objPHPExcel->getActiveSheet()->setCellValue('Q' . $fila, strtoupper($data['mat_codpoi'])); 


    /*
    foreach ($data['servidores'] as $key => $servidor) {
        $objPHPExcel->getActiveSheet()->setCellValue('G' . $fila, strtoupper($key));
        $objPHPExcel->getActiveSheet()->setCellValue('H' . $fila, strtoupper($servidor));
        $fila++;
    }
    $final = $fila - 1;
    */
    

    /*
    $objPHPExcel->getActiveSheet()->mergeCells("A{$inicio}:A{$final}");
    $objPHPExcel->getActiveSheet()->mergeCells("B{$inicio}:B{$final}");
    $objPHPExcel->getActiveSheet()->mergeCells("C{$inicio}:C{$final}");
    $objPHPExcel->getActiveSheet()->mergeCells("D{$inicio}:D{$final}");
    $objPHPExcel->getActiveSheet()->mergeCells("E{$inicio}:E{$final}");
    $objPHPExcel->getActiveSheet()->mergeCells("F{$inicio}:F{$final}");
    $objPHPExcel->getActiveSheet()->mergeCells("G{$inicio}:G{$final}");
    $objPHPExcel->getActiveSheet()->mergeCells("H{$inicio}:H{$final}");
    $objPHPExcel->getActiveSheet()->mergeCells("I{$inicio}:I{$final}");
    $objPHPExcel->getActiveSheet()->mergeCells("J{$inicio}:J{$final}");
    $objPHPExcel->getActiveSheet()->mergeCells("K{$inicio}:K{$final}");
    $objPHPExcel->getActiveSheet()->mergeCells("L{$inicio}:L{$final}");
    $objPHPExcel->getActiveSheet()->mergeCells("M{$inicio}:M{$final}");
    $objPHPExcel->getActiveSheet()->mergeCells("N{$inicio}:N{$final}");
    $objPHPExcel->getActiveSheet()->mergeCells("O{$inicio}:O{$final}");
    $objPHPExcel->getActiveSheet()->mergeCells("P{$inicio}:P{$final}");
    $objPHPExcel->getActiveSheet()->mergeCells("Q{$inicio}:Q{$final}");
    */

    $fila++;
}

$fila = $fila - 1;

$objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A3:Q" . $fila);
$objPHPExcel->getActiveSheet()->getStyle("A3:A" . $fila)->applyFromArray($styleCenter);
$objPHPExcel->getActiveSheet()->getStyle("F3:G" . $fila)->applyFromArray($styleCenter);
$objPHPExcel->getActiveSheet()->getStyle("J3:J" . $fila)->applyFromArray($styleCenter);
$objPHPExcel->getActiveSheet()->getStyle("L3:L" . $fila)->applyFromArray($styleCenter);
$objPHPExcel->getActiveSheet()->getStyle("M3:M" . $fila)->applyFromArray($styleCenter);

$objPHPExcel->getActiveSheet()->getStyle('A1:Q999')->getAlignment()->setWrapText(true);

foreach (range('A', 'Q') as $columnID) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(false);
}

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth("10");
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth("15");
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth("18");
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth("30");
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth("30");
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth("15");
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth("15");
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth("180");
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth("150");
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth("20");
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth("80");
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth("20");
$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth("100");
$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth("120");
$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth("50");
$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth("80");
$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth("15");

unset($_SESSION['reporte_ent_inscritas']);

header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header('Content-Disposition: attachment;filename="Reporte_entidades_inscritas.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$objWriter->save('php://output');
exit();
