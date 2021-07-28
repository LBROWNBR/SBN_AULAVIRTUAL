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

$styleRosa01 = array(
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
        'color' => array('rgb' => 'ffcdd2')
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

$styleRosa02 = array(
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
        'color' => array('rgb' => 'ffebee')
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

$styleBlanco = array(
    'font' => array(
        'name'  => 'Calibri',
        'bold'  => false,
        'size' => 10,
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

$styleBlancoNegrita = array(
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


$datos = isset($_SESSION['reporte_cursos']) ? $_SESSION['reporte_cursos'] : die();

// $objPHPExcel->getActiveSheet()->getStyle('B1:D1')->applyFromArray($styleTitulo);
$objPHPExcel->getActiveSheet()->setCellValue('C1', strtoupper($datos['rep_cursoNombre']));
// $objPHPExcel->getActiveSheet()->mergeCells('B1:D1');

$objPHPExcel->getActiveSheet()->getStyle('C2:G2')->applyFromArray($styleHead01);
$objPHPExcel->getActiveSheet()->setCellValue('C2', 'CUADRO DE RESULTADOS - CURSO "' . strtoupper($datos['rep_cursoNombre']) . '"');
$objPHPExcel->getActiveSheet()->mergeCells('C2:G2');

// $objPHPExcel->getActiveSheet()->getStyle('B3:B4')->applyFromArray($styleHead02);
// $objPHPExcel->getActiveSheet()->setCellValue('B3', 'SEDE');
// $objPHPExcel->getActiveSheet()->mergeCells('B3:B4');
$objPHPExcel->getActiveSheet()->getStyle('C3:D4')->applyFromArray($styleRosa01);
$objPHPExcel->getActiveSheet()->setCellValue('C3', 'ENTIDADES ASISTENTES');
$objPHPExcel->getActiveSheet()->mergeCells('C3:C4');
$objPHPExcel->getActiveSheet()->setCellValue('D3', 'ENTIDADES CON UN CERTIFICADO');
$objPHPExcel->getActiveSheet()->mergeCells('D3:D4');

$objPHPExcel->getActiveSheet()->getStyle('E3:G3')->applyFromArray($styleHead01);
$objPHPExcel->getActiveSheet()->setCellValue('E3', 'PARTICIPANTES ASISTENTES');
$objPHPExcel->getActiveSheet()->mergeCells('E3:G3');
$objPHPExcel->getActiveSheet()->getStyle('E4:F4')->applyFromArray($styleHead02);
$objPHPExcel->getActiveSheet()->setCellValue('E4', '');
$objPHPExcel->getActiveSheet()->setCellValue('F4', 'N°');
$objPHPExcel->getActiveSheet()->getStyle('G4:G4')->applyFromArray($styleRosa01);
$objPHPExcel->getActiveSheet()->setCellValue('G4', 'TOTAL ASISTENTES');

$objPHPExcel->getActiveSheet()->getStyle('E5:F6')->applyFromArray($styleBlanco);
$objPHPExcel->getActiveSheet()->setCellValue('E5', 'Funcionarios');
$objPHPExcel->getActiveSheet()->setCellValue('F5', $datos['rep_particiAsis'][0]);
$objPHPExcel->getActiveSheet()->setCellValue('E6', 'Servidores');
$objPHPExcel->getActiveSheet()->setCellValue('F6', $datos['rep_particiAsis'][1]);
$objPHPExcel->getActiveSheet()->getStyle('G5:G6')->applyFromArray($styleRosa02);
$objPHPExcel->getActiveSheet()->setCellValue('G5', $datos['rep_particiAsis'][2]);
$objPHPExcel->getActiveSheet()->mergeCells('G5:G6');


// $objPHPExcel->getActiveSheet()->getStyle('B5:B10')->applyFromArray($styleBlancoNegrita);
// $objPHPExcel->getActiveSheet()->setCellValue('B5', strtoupper($datos['rep_lugar']));
// $objPHPExcel->getActiveSheet()->mergeCells('B5:B10');
$objPHPExcel->getActiveSheet()->getStyle('C5:C10')->applyFromArray($styleRosa02);
$objPHPExcel->getActiveSheet()->setCellValue('C5', $datos['rep_entidadAsis']);
$objPHPExcel->getActiveSheet()->mergeCells('C5:C10');
$objPHPExcel->getActiveSheet()->getStyle('D5:D10')->applyFromArray($styleRosa02);
$objPHPExcel->getActiveSheet()->setCellValue('D5', $datos['rep_entidadCert']);
$objPHPExcel->getActiveSheet()->mergeCells('D5:D10');



$objPHPExcel->getActiveSheet()->getStyle('E7:G7')->applyFromArray($styleHead01);
$objPHPExcel->getActiveSheet()->setCellValue('E7', 'PROFESIONALES CAPACITADOS QUE OBTUVIERON CERTIFICADOS');
$objPHPExcel->getActiveSheet()->mergeCells('E7:G7');


$objPHPExcel->getActiveSheet()->getStyle('E8:F8')->applyFromArray($styleHead02);
$objPHPExcel->getActiveSheet()->setCellValue('E8', '');
$objPHPExcel->getActiveSheet()->setCellValue('F8', 'N°');
$objPHPExcel->getActiveSheet()->getStyle('G8:G8')->applyFromArray($styleRosa01);
$objPHPExcel->getActiveSheet()->setCellValue('G8', 'TOTAL CERTIFICADOS');

$objPHPExcel->getActiveSheet()->getStyle('E9:F10')->applyFromArray($styleBlanco);
$objPHPExcel->getActiveSheet()->setCellValue('E9', 'Funcionarios');
$objPHPExcel->getActiveSheet()->setCellValue('F9', $datos['rep_TotalCertificados'][0]);
$objPHPExcel->getActiveSheet()->setCellValue('E10', 'Servidores');
$objPHPExcel->getActiveSheet()->setCellValue('F10', $datos['rep_TotalCertificados'][1]);
$objPHPExcel->getActiveSheet()->getStyle('G9:G10')->applyFromArray($styleRosa02);
$objPHPExcel->getActiveSheet()->setCellValue('G9', $datos['rep_TotalCertificados'][2]);
$objPHPExcel->getActiveSheet()->mergeCells('G9:G10');


$objPHPExcel->getActiveSheet()->getStyle('C1:G999')->getAlignment()->setWrapText(true);

foreach (range('C', 'G') as $columnID) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(false);
}

$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth("18");
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth("18");
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth("15");
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth("15");
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth("15");

$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(40);
$objPHPExcel->getActiveSheet()->getStyle('C1:G1')->applyFromArray($styleTitulo);
$objPHPExcel->getActiveSheet()->mergeCells('C1:G1');

$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(30);
$objPHPExcel->getActiveSheet()->getRowDimension('7')->setRowHeight(40);

unset($_SESSION['reporte_cursos']);


header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header('Content-Disposition: attachment;filename="Reporte_cuadro_resultados.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$objWriter->save('php://output');
exit();
