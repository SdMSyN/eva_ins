<?php

    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    include('../config/conexion.php');
    include('../config/variables.php');
    $exams = array();
    $msgErr = '';
    $ban = true;
    
    $idAsig = $_GET['idAsig']; 
    
    $arrIdsPregs = array();
    $arrValPregs = array();
    $arrStudents = array();
    
    //Obtenemos información de la asignación
    $sqlGetInfoAsig = "SELECT $tExaInfAsig.nombre as nombreAsig, $tExaInfAsig.id as idAsig, "
            . "$tExaInfAsig.exa_info_id as idExa, $tExaInf.nombre as nombreExa, $tBMat.nombre as mat, "
            . "$tGrupo.nombre as grupo, $tGrado.nombre as grado "
            . "FROM $tExaInfAsig "
            . "INNER JOIN $tGMatProfs ON $tGMatProfs.id=$tExaInfAsig.grupo_materia_profesor_id "
            . "INNER JOIN $tBMat ON $tBMat.id=$tGMatProfs.banco_materia_id "
            . "INNER JOIN $tGrupo ON $tGrupo.id=$tGMatProfs.grupo_info_id "
            . "INNER JOIN $tGrado ON $tGrado.id=$tGrupo.nivel_grado_id "
            . "INNER JOIN $tExaInf ON $tExaInf.id=$tExaInfAsig.exa_info_id "
            . "WHERE $tExaInfAsig.id='$idAsig' ";
    //echo $sqlGetInfoAsig;
    $resGetInfoAsig = $con->query($sqlGetInfoAsig);
    $rowGetInfoAsig = $resGetInfoAsig->fetch_assoc();
    $idAsig = $rowGetInfoAsig['idAsig'];
    $nameAsig = $rowGetInfoAsig['nombreAsig'];
    $idExa = $rowGetInfoAsig['idExa'];
    $nameExa = $rowGetInfoAsig['nombreExa'];
    $nameMat = $rowGetInfoAsig['mat'];
    $grado = $rowGetInfoAsig['grado'];
    $grupo = $rowGetInfoAsig['grupo'];
    
    // Creamos Excel
	include ('../classes/PHPExcel/PHPExcel.php');
	$objPHPExcel = new PHPExcel();
	//Propiedades del Excel
	$objPHPExcel->
            getProperties()
                ->setCreator("Business Software Solutions")
                ->setLastModifiedBy("Business Software Solutions")
                ->setTitle("Detalles de la clase")
                ->setSubject("E. V. A.")
                ->setDescription("Documento generado con PHPExcel")
                ->setKeywords("Business Software Solutions")
                ->setCategory("E. V. A."); 
    // Título y nombre de las columnas
	//$tituloReporte = "Reporte del Examen (".$nameExa.") del ".$grado."-".$grupo;
	$tituloReporte = "Evaluación Virtual para mejora de los Aprendizajes";
        $tituloColumnas = array('Nombre del alumno');
    // Obtener número de preguntas
    // Obtener alumnos
    // Obtener detalles de las preguntas respondidas
    // Obtener números generales
    $sqlGetPregs = "SELECT $tBPregs.id, $tBPregs.nombre, $tBPregs.valor_preg "
            . "FROM $tExaPregs "
            . "INNER JOIN $tBPregs ON $tBPregs.id=$tExaPregs.banco_pregunta_id "
            . "WHERE $tExaPregs.exa_info_id='$idExa' ";
    //echo $sqlGetPregs;
    $resGetPregs = $con->query($sqlGetPregs);
    $valorExa = 0; $numPregs = 0;
    if($resGetPregs->num_rows > 0){
        $numPregs = $resGetPregs->num_rows;
        while($rowGetPregs = $resGetPregs->fetch_assoc()){
            $idPreg = $rowGetPregs['id'];
            $namePreg = $rowGetPregs['nombre'];
            $valorPreg = $rowGetPregs['valor_preg'];
            $valorExa += $valorPreg;
            $arrIdsPregs[] = $idPreg;
            $arrValPregs[] = $valorPreg;
        }
    }else{
        $ban = false;
        $msgErr = 'No existen preguntas.<br>'.$con->error;
    }
    
    for($i = 1; $i <= $numPregs; $i++){
        $tituloColumnas[] = 'Preg '.$i;
    }
    $tituloColumnas[] =  'Buenas';
    $tituloColumnas[] = 'Malas';
    $tituloColumnas[] = 'Sin Respuesta';
    $tituloColumnas[] = 'Puntaje';
    $tituloColumnas[] = 'Calificación';
    $countColTit = $numPregs;
    $arrLetters = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N',
        'O','P','Q','R','S','T','U','V','W','X','Y','Z', 'AA','AB','AC','AD','AE',
        'AF','AG','AH','AI','AJ','AK','AL','AM','AN',
        'AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ','BA','BB','BC',
        'BD','BE','BF','BG','BH','BI','BJ','BK','BL','BM','BN',
        'BO','BP','BQ','BR','BS','BT','BU','BV','BW','BX','BY','BZ','CA','CB','CC',
        'CD','CE','CF','CG','CH','CI','CJ','CK','CL','CM','CN',
        'CO','CP','CQ','CR','CS','CT','CU','CV','CW','CX','CY','CZ','DA','DB','DC',
        'DD','DE','DF','DG','DH','DI','DJ','DK','DL','DM','DN',
        'DO','DP','DQ','DR','DS','DT','DU','DV','DW','DX','DY','DZ');
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A2',"Grado")
            ->setCellValue('B2', $grado)
            ->setCellValue('D2', "Grupo")
            ->setCellValue('E2', $grupo)
            ->setCellValue('A3',"Materia")
            ->setCellValue('B3', $nameMat)
            ->setCellValue('D3',"Examen")
            ->setCellValue('E3', $nameExa)
            ->setCellValue('A4', "Fecha")
            ->setCellValue('B4', $dateNow);
    
    $objPHPExcel->setActiveSheetIndex(0)
            ->mergeCells('A1:'.$arrLetters[count($tituloColumnas)].'1');
    $objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A1', $tituloReporte);
    
    
    $countColTit = count($tituloColumnas);
    for($j = 0; $j < $countColTit; $j++){
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue($arrLetters[$j].'8', $tituloColumnas[$j]);
    }
                    
    if($ban){
        $sqlGetStudents = "SELECT $tAlum.nombre as nombreAlum, $tExaInfAsigAlum.alumno_id as idAlum, "
                . "$tExaInfAsigAlum.id as idAsigAlum "
                . "FROM $tExaInfAsigAlum "
                . "INNER JOIN $tAlum ON $tAlum.id=$tExaInfAsigAlum.alumno_id "
                . "WHERE $tExaInfAsigAlum.exa_info_asig_id='$idAsig' ";
        $resGetStudents = $con->query($sqlGetStudents);
        if($resGetStudents->num_rows > 0){
            $count = 9;
            $numAlums = $resGetStudents->num_rows;
            $buenasCount = 0; $malasCount = 0; $sinRespCount = 0; $valorExaAlumCount = 0; $califCount = 0;
            $buenasArr = array(); $malasArr = array(); $sinRespArr = array(); 
            $aprobados = $reprobados = $numEvals = $numNoEvals = 0;
            for($z=0; $z<$numPregs; $z++){
                $buenasArr[$z] = 0;
                $malasArr[$z] = 0;
                $sinRespArr[$z] = 0;
            }
            while($rowGetStudents = $resGetStudents->fetch_assoc()){
                $idAlum = $rowGetStudents['idAlum'];
                $nameAlum = $rowGetStudents['nombreAlum'];
                $idAsigAlum = $rowGetStudents['idAsigAlum'];
                $arrCalifPregs = array();
                foreach($arrIdsPregs as $idPregArr){
                    $sqlGetResp = "SELECT calificacion "
                        . "FROM $tExaResultPregs "
                        . "WHERE exa_info_id='$idExa' AND exa_info_asig_alum_id='$idAsigAlum' "
                        . "AND alumno_id='$idAlum' AND pregunta_id='$idPregArr' ";
                    //echo $sqlGetResp; 
                    $resGetResp = $con->query($sqlGetResp);
                    if($resGetResp->num_rows > 0){
                        $rowGetResp = $resGetResp->fetch_assoc();
                        $calif = $rowGetResp['calificacion'];
                        $arrCalifPregs[] = $calif;
                    }else{
                        $arrCalifPregs[] = 2;
                    }
                }
                $sqlGetResultInfo = "SELECT * FROM $tExaResultInfo "
                        . "WHERE exa_info_id='$idExa' AND exa_info_asig_alum_id='$idAsigAlum' AND alumno_id='$idAlum' ";
                $resGetResultInfo = $con->query($sqlGetResultInfo);
                if($resGetResultInfo->num_rows > 0){
                    $rowGetResultInfo = $resGetResultInfo->fetch_assoc();
                    $buenas = $rowGetResultInfo['resp_buenas'];
                    $malas = $rowGetResultInfo['resp_malas'];
                    $sinResp = $rowGetResultInfo['preg_no_contestadas'];
                    $valorExaAlum = $rowGetResultInfo['valor_exa_alum'];
                    $calificacion = $rowGetResultInfo['calificacion'];
                    $numEvals++;
                }else{
                    $rowGetResultInfo = $resGetResultInfo->fetch_assoc();
                    $buenas = $rowGetResultInfo['resp_buenas'];
                    $malas = $rowGetResultInfo['resp_malas'];
                    $sinResp = $numPregs;
                    $valorExaAlum = $rowGetResultInfo['valor_exa_alum'];
                    $calificacion = $rowGetResultInfo['calificacion'];
                    $numNoEvals++;
                }

                if($calificacion >= 6) $aprobados++;
                else $reprobados++;
                
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$count, $nameAlum);
                $countArrCalifPreg = count($arrCalifPregs);
                for($m = 0; $m < $countArrCalifPreg; $m++){
                    if($arrCalifPregs[$m] == 0){
                        $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue($arrLetters[$m+1].''.$count, 'I');
                        $malasArr[$m] = $malasArr[$m] + 1;
                    }else if($arrCalifPregs[$m] == 1){
                        $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue($arrLetters[$m+1].''.$count, 'C');
                        $buenasArr[$m] = $buenasArr[$m] + 1;
                    }else if($arrCalifPregs[$m] == 2){
                        $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue($arrLetters[$m+1].''.$count, 'N');
                        $sinRespArr[$m] = $sinRespArr[$m] + 1;
                    }else{
                        $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue($arrLetters[$m+1].''.$count, 'Error');
                    }
                }
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue($arrLetters[$countArrCalifPreg+1].''.$count, $buenas)
                        ->setCellValue($arrLetters[$countArrCalifPreg+2].''.$count, $malas)
                        ->setCellValue($arrLetters[$countArrCalifPreg+3].''.$count, $sinResp)
                        ->setCellValue($arrLetters[$countArrCalifPreg+4].''.$count, $valorExaAlum)
                        ->setCellValue($arrLetters[$countArrCalifPreg+5].''.$count, $calificacion);
                $buenasCount += $buenas;
                $malasCount += $malas;
                $sinRespCount += $sinResp;
                $valorExaAlumCount += $valorExaAlum;
                $califCount += $calificacion;
                $count++;
            }
            $count++;
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$count, 'Total de alumnos: '.$numAlums);
            $count++;
            $promB = (($buenasCount/$numAlums)*100)/$numPregs;
            $promM = (($malasCount/$numAlums)*100)/$numPregs;
            $promSR = (($sinRespCount/$numAlums)*100)/$numPregs;
            $promV = $valorExaAlumCount/$numAlums;
            $promC = $califCount/$numAlums;
            //echo $buenasCount.'--'.$malasCount.'--'.$valorExaAlumCount.'--'.$califCount.'--'.$numAlums.'<br>';
            //echo $promB.'--'.$promM.'--'.$promV.'--'.$promC;
            $count+=2;
            $objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A'.$count, 'Correctas')
		->setCellValue('A'.($count+1), 'Incorrectas')
		->setCellValue('A'.($count+2), 'No contestadas');
            for($z=0; $z<$numPregs; $z++){
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue($arrLetters[$z+1].''.$count, $buenasArr[$z]);
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue($arrLetters[$z+1].''.($count+1),$malasArr[$z]);
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue($arrLetters[$z+1].''.($count+2),$sinRespArr[$z]);
            }
            $count-=2;
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$count, 'Promedios: ')
                    ->setCellValue($arrLetters[$numPregs+1].''.$count, $promB.' %')
                    ->setCellValue($arrLetters[$numPregs+2].''.$count, $promM.' %')
                    ->setCellValue($arrLetters[$numPregs+3].''.$count, $promSR.' %')
                    ->setCellValue($arrLetters[$numPregs+4].''.$count, $promV)
                    ->setCellValue($arrLetters[$numPregs+5].''.$count, $promC);
            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A5', "#Alumnos")
                    ->setCellValue('B5', $numAlums)
                    ->setCellValue('D5', "Promedio")
                    ->setCellValue('E5', $promC)
                    ->setCellValue('A6', "#Evaluados")
                    ->setCellValue('B6', $numEvals)
                    ->setCellValue('D6', "#No Evaluados")
                    ->setCellValue('E6', $numNoEvals)
                    ->setCellValue('A7', "#Aprobados")
                    ->setCellValue('B7', $aprobados)
                    ->setCellValue('D7', "#Reprobados")
                    ->setCellValue('E7', $reprobados);
            $objPHPExcel->getActiveSheet()->getStyle("A2")->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle("A3")->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle("A4")->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle("A5")->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle("A6")->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle("A7")->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle("D2")->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle("D3")->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle("D4")->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle("D5")->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle("D6")->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle("D7")->getFont()->setBold(true);
            
        }else{
            $ban = false;
            $msgErr .= 'No hay alumnos en éste grupo.';
        }
    }
    
     
    
    //Estilos para la hoja de Excel
	$estiloTituloReporte = array(
            'font' => array(
                'name'      => 'Verdana',
                'bold'      => true,
                'italic'    => false,
                'strike'    => false,
                'size' =>16,
                'color'     => array(
                    'rgb' => 'FFFFFF'
                )
            ),
            'fill' => array(
                'type'  => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array(
                    'argb' => 'FF220835')
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_NONE
                )
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'rotation' => 0,
                'wrap' => TRUE
            )
        );
 
        $estiloTituloColumnas = array(
            'font' => array(
                'name'  => 'Arial',
                'bold'  => true,
                'color' => array(
                    'rgb' => 'FFFFFF'
                )
            ),
            'fill' => array(
                'type'       => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
            'rotation'   => 90,
                'startcolor' => array(
                    'rgb' => 'c47cf2'
                ),
                'endcolor' => array(
                    'argb' => 'FF431a5d'
                )
            ),
            'borders' => array(
                'top' => array(
                    'style' => PHPExcel_Style_Border::BORDER_MEDIUM ,
                    'color' => array(
                        'rgb' => '143860'
                    )
                ),
                'bottom' => array(
                    'style' => PHPExcel_Style_Border::BORDER_MEDIUM ,
                    'color' => array(
                        'rgb' => '143860'
                    )
                )
            ),
            'alignment' =>  array(
                'horizontal'=> PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical'  => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap'      => TRUE
            )
        );
 
        $estiloInformacion = new PHPExcel_Style();
        $estiloInformacion->applyFromArray( array(
            'font' => array(
                'name'  => 'Arial',
                'color' => array(
                    'rgb' => '000000'
                )
            ),
            'fill' => array(
            'type'  => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array(
                    'argb' => 'FFd9b7f4')
            ),
            'borders' => array(
                'left' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN ,
                'color' => array(
                        'rgb' => '3a2a47'
                    )
                )
            )
        ));
	$objPHPExcel->getActiveSheet()->getStyle('A1:'.$arrLetters[$countColTit].'1')->applyFromArray($estiloTituloReporte);
	$objPHPExcel->getActiveSheet()->getStyle('A8:'.$arrLetters[$countColTit].'8')->applyFromArray($estiloTituloColumnas);
	$objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A9:".$arrLetters[$countColTit]."".($count-1));
        
        $objPHPExcel->getActiveSheet()->getStyle($arrLetters[$numPregs+1].''.$count)->getNumberFormat()->setFormatCode('#.##0');
        $objPHPExcel->getActiveSheet()->getStyle($arrLetters[$numPregs+2].''.$count)->getNumberFormat()->setFormatCode('#.##0');
        $objPHPExcel->getActiveSheet()->getStyle($arrLetters[$numPregs+3].''.$count)->getNumberFormat()->setFormatCode('#.##0');
        $objPHPExcel->getActiveSheet()->getStyle($arrLetters[$numPregs+4].''.$count)->getNumberFormat()->setFormatCode('#.##0');
        $objPHPExcel->getActiveSheet()->getStyle($arrLetters[$numPregs+5].''.$count)->getNumberFormat()->setFormatCode('#.##0');
	//Asignación de columnas
	for($m = 'A'; $m <= $arrLetters[$countColTit]; $m++){
            $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($m)->setAutoSize(TRUE);
	}


	if($msgErr != ''){
		echo $msgErr;
	}else{
            //echo $table;
            // Se asigna el nombre a la hoja
            $objPHPExcel->getActiveSheet()->setTitle('Reporte');
            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->getActiveSheet(0)->freezePaneByColumnAndRow(0,9);
            // Se manda el archivo al navegador web, con el nombre que se indica, en formato 2007
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="reporte_examen.xlsx"');
            header('Cache-Control: max-age=0');

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save('php://output');
            exit;
	}
	

?>