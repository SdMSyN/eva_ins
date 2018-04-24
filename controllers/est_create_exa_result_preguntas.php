<?php

    error_reporting(E_ALL);
    ini_set('display_errors', 1);
        
    include('../config/conexion.php');
    include('../config/variables.php');
    
    $idUser = $_GET['idUser'];
    $idExam = $_GET['idExam'];
    $idExamAsig = $_GET['idExamAsig'];
    $idExamAsigAlum = $_GET['idExamAsigAlum'];
    $idExamTime = $_GET['idExaTime'];
    //echo $idUser.'--'.$idExam;
    $ban = true;
    $msgErr = '';
    $msgEx = '';
    $cadCheck = '';
    $countPregs = 0;
    $numCorr = 0; 
    $numErr = 0;
    $numSinResp = 0;
    $valorEst = 0;
    $valorExam = 0;
    $arrCalRespTmp = array();
    $arrSqls = array();
    $arrResp = array();
    //Buscamos las preguntas del examen en base a la asignación; [exa_info_id]
    $sqlGetPregs = "SELECT $tBPregs.* "
            . "FROM $tExaPregs "
            . "INNER JOIN $tBPregs ON $tBPregs.id=$tExaPregs.banco_pregunta_id "
            . "WHERE $tExaPregs.exa_info_id='$idExam' ";
    $resGetPregs = $con->query($sqlGetPregs);
    if($resGetPregs->num_rows > 0){
        $countPregsResp = 0;
        $countNumPregs = $resGetPregs->num_rows;
        while($rowGetPreg = $resGetPregs->fetch_assoc()){
            $idPreg = $rowGetPreg['id'];
            $nombrePreg = $rowGetPreg['nombre'];
            $valorPreg = $rowGetPreg['valor_preg'];
            $valorExam += $valorPreg;
            $tipoRespPreg = $rowGetPreg['tipo_resp'];
            //$arrResp[$countPregsResp] = ['idPreg'=>$idPreg];
            $sqlGetRespTmp = "SELECT * FROM $tExaTmp "
                    . "WHERE alumno_id='$idUser' AND exa_info_id='$idExam' "
                    . "AND exa_info_asig_alum_id='$idExamAsigAlum' AND pregunta_id='$idPreg' ";
            $resGetRespTmp = $con->query($sqlGetRespTmp);
            if($resGetRespTmp->num_rows > 0){
                $rowGetRespTmp = $resGetRespTmp->fetch_assoc();
                $idRespTmp = $rowGetRespTmp['id'];
                $tipoRespResp = $rowGetRespTmp['tipo_resp_id'];
                $respIdResp = $rowGetRespTmp['respuesta_id'];
                $respResp = $rowGetRespTmp['respuesta'];
                //$msgErr .= $tipoRespResp.'--'.$respIdResp.'--'.$respResp;
                if($tipoRespResp == 1){ //si es opción multiple 
                    $sqlGetCalifResp = "SELECT correcta FROM $tBResp WHERE id='$respResp' ";
                    $resGetCalifResp = $con->query($sqlGetCalifResp);
                    $rowGetCalifResp = $resGetCalifResp->fetch_assoc();
                    $califResp = $rowGetCalifResp['correcta'];
                    //$msgErr .= '--'.$califResp.'<br>';
                    $arrResp[] = array('idPreg'=>$idPreg, 'typeResp'=>$tipoRespPreg, 'idRespTmp'=>$idRespTmp, 'idResp'=>'', 'resp'=>$respResp, 'calif'=>$califResp);
                    if($califResp == 1){
                        $numCorr++;
                        $valorEst += $valorPreg;
                    }else{
                        $numErr++;
                    }
                }else if($tipoRespResp == 2){
                    $respResp2 = explode(",",$respResp);
                    $banResp = true;
                    for($i =0; $i < count($respResp2); $i++){
                        $idRespResp = $respResp2[$i];
                        $sqlGetCalifResp = "SELECT correcta FROM $tBResp WHERE id='$idRespResp' ";
                        $resGetCalifResp = $con->query($sqlGetCalifResp);
                        $rowGetCalifResp = $resGetCalifResp->fetch_assoc();
                        $califRespTmp = $rowGetCalifResp['correcta'];
                        if($califRespTmp == 0){
                            $banResp = false;
                            break;
                        }else continue;
                        //$msgErr .= '--'.$califResp.'<br>';
                    }
                    if($banResp){
                        $arrResp[] = array('idPreg'=>$idPreg, 'typeResp'=>$tipoRespPreg, 'idRespTmp'=>$idRespTmp, 'idResp'=>'', 'resp'=>$respResp, 'calif'=>1);
                        $numCorr++;
                        $valorEst += $valorPreg;
                    }else{
                        $arrResp[] = array('idPreg'=>$idPreg, 'typeResp'=>$tipoRespPreg, 'idRespTmp'=>$idRespTmp, 'idResp'=>'', 'resp'=>$respResp, 'calif'=>0);
                        $numErr++;
                    }
                }else if($tipoRespResp == 3){
                    $sqlGetWordResp = "SELECT palabras FROM $tBResp WHERE id='$respIdResp' ";
                    $resGetWordResp = $con->query($sqlGetWordResp);
                    $rowGetWordResp = $resGetWordResp->fetch_assoc();
                    $arrPalabras = explode(",", $rowGetWordResp['palabras']);
                    $banWord = true;
                    for($i = 0; $i < count($arrPalabras); $i++){
                       if(!preg_match('/'.$arrPalabras[$i].'/i', $respResp)){
                           $banWord = false;
                           break;
                       }else continue;
                    }
                    if($banWord){
                        $califResp = 1;
                        $numCorr++;
                        $valorEst += $valorPreg;
                    }else{
                        $califResp = 0;
                        $numErr++;
                    }
                    //$msgErr .= '--'.$califResp.'<br>';
                    $arrResp[] = array('idPreg'=>$idPreg, 'typeResp'=>$tipoRespPreg, 'idRespTmp'=>$idRespTmp, 'idResp'=>$respIdResp, 'resp'=>$respResp, 'calif'=>$califResp);
                }else if($tipoRespResp == 4){
                    $sqlGetWordResp = "SELECT palabras FROM $tBResp WHERE id='$respIdResp' ";
                    $resGetWordResp = $con->query($sqlGetWordResp);
                    $rowGetWordResp = $resGetWordResp->fetch_assoc();
                    $palabraResp = $rowGetWordResp['palabras'];
                    if($palabraResp == $respResp){
                        $califResp = 1;
                        $numCorr++;
                        $valorEst += $valorPreg;
                    }else{
                        $califResp = 0;
                        $numErr++;
                    }
                    //$msgErr .= '--'.$califResp.'<br>';
                    $arrResp[] = array('idPreg'=>$idPreg, 'typeResp'=>$tipoRespPreg, 'idRespTmp'=>$idRespTmp, 'idResp'=>$respIdResp, 'resp'=>$respResp, 'calif'=>$califResp);
                }else{// No existe ese tipo de respuestas
                    $califResp = 3;
                    $msgErr .= '--'.$califResp.'<br>';
                }
                //$msgErr .= '--'.$califResp.'<br>';
            }else{
                //$ban = false;
                $msgErr .= 'No contestaste esta pregunta, burro.'.$idPreg.'<br>'.$con->error;
                $arrResp[] = array('idPreg'=>$idPreg, 'typeResp'=>$tipoRespPreg, 'idRespTmp'=>'', 'idResp'=>'', 'resp'=>'', 'calif'=>2);
                $numSinResp++;
                continue;
            }
            $countPregsResp++;
        }
    }else{
        $ban = false;
        $msgErr .= 'No existen preguntas en éste examen.<br>'.$con->error;
    }
    
    if($ban){//Siquiera contesto, entonces
        //$msgErr = $countPregsResp.'--'.$numCorr.'--'.$numErr.'--'.$numSinResp.'--'.$valorEst.'<br>';
        
        $banTime = false;
        $sqlInsertHoraFinal = "UPDATE $tExaTime SET hora_fin='$timeNow' WHERE id='$idExamTime' ";
        if($con->query($sqlInsertHoraFinal) === TRUE){
            $banTime = true;
        }else{
            $banTime = false;
        }
        if($banTime){
            //Obtenemos información de los tiempos del examen del usuario
            $sqlGetInfoExaTime = "SELECT * FROM $tExaTime WHERE id='$idExamTime' ";
            $resGetInfoExaTime = $con->query($sqlGetInfoExaTime);
            $rowGetInfoExaTime = $resGetInfoExaTime->fetch_assoc();
            $hInicio = $rowGetInfoExaTime['hora_inicio'];
            $hFin = $rowGetInfoExaTime['hora_fin'];
        }
        $porc = ($valorEst * 100) / $valorExam; 
        $califTmp = $porc / 10;
        $sqlInsertResultInfo = "INSERT INTO $tExaResultInfo "
                . "(exa_info_id, exa_info_asig_alum_id, alumno_id, num_pregs, preg_contestadas, preg_no_contestadas, "
                . "resp_buenas, resp_malas, valor_exa, valor_exa_alum, calificacion, porcentaje, "
                . "hora_inicio, hora_fin, creado, actualizado) "
                . "VALUES ('$idExam', '$idExamAsigAlum', '$idUser', '$countNumPregs', '$countPregsResp', '$numSinResp', "
                . " '$numCorr', '$numErr', '$valorExam', '$valorEst', '$califTmp', '$porc', "
                . "'$hInicio', '$hFin', '$dateNow', '$dateNow' )";
        $arrSqls[] = $sqlInsertResultInfo;
        if($con->query($sqlInsertResultInfo) === TRUE){
            $idResultExam = $con->insert_id;
            foreach($arrResp as $datosResp => $datosRespPreg){
                $idPregData = $datosRespPreg['idPreg'];
                $typeRespData = $datosRespPreg['typeResp'];
                $idRespTmpData = $datosRespPreg['idRespTmp'];
                $idRespData = $datosRespPreg['idResp'];
                $respData = $datosRespPreg['resp'];
                $califArr = $datosRespPreg['calif'];
                if($datosRespPreg['typeResp'] == 1 || $datosRespPreg['typeResp'] == 2){
                    $sqlInsertResultPreg = "INSERT INTO $tExaResultPregs "
                            . "(exa_info_id, exa_info_asig_alum_id, alumno_id, pregunta_id, tipo_resp_id, "
                            . "respuesta_id, respuesta, exa_result_info_id, calificacion, creado, actualizado) "
                            . "VALUES ('$idExam','$idExamAsigAlum','$idUser','$idPregData','$typeRespData',"
                            . "'','$respData','$idResultExam','$califArr','$dateNow','$dateNow') ";
                }else if($datosRespPreg['typeResp'] == 3 || $datosRespPreg['typeResp'] == 4){
                    $sqlInsertResultPreg = "INSERT INTO $tExaResultPregs "
                            . "(exa_info_id, exa_info_asig_alum_id, alumno_id, pregunta_id, tipo_resp_id, "
                            . "respuesta_id, respuesta, exa_result_info_id, calificacion, creado, actualizado) "
                            . "VALUES ('$idExam','$idExamAsigAlum','$idUser','$idPregData','$typeRespData',"
                            . "'$idRespData','$respData','$idResultExam','$califArr','$dateNow','$dateNow') ";
                }else{
                    $ban = false;
                    $msgErr .= 'No existe este tipo de respuesta';
                    break;
                }
                if($con->query($sqlInsertResultPreg) === TRUE){
                    $sqlDeletePregTmp = "DELETE FROM $tExaTmp WHERE id='$idRespTmpData' ";
                    if($con->query($sqlDeletePregTmp) === TRUE){
                        $ban = true;
                    }else{
                        $ban = false;
                        $msgErr .= 'Error al borrar respuesta de la pregunta temporal.<br>'.$con->error;
                        break;
                    }
                }else{
                    $ban = false;
                    $msgErr .= 'Error al insertar respuesta de la pregunta.<br>'.$con->error;
                    break;
                }
            }//end foreach
        }else{
            $ban = false;
            $msgErr .= 'Error al insertar información del resultado.<br>'.$con->error;
        }
        
        
    }//end ban
    
    
    /*$msgEx .= 'Numero de preguntas: '.$numPregs.', Valor del examen: '.$valorExa.', '
            . 'Número de preguntas respondidas: '.$countPregs.', '
            . 'Correctas: '.$numCorr.', Incorrectas: '.$numErr.', valor obtenido: '.$valorEst.'<br>Checks: '.$cadCheck;*/
    if($ban){
        $msgErr .= 'Éxito al calificar tu examen';
        echo json_encode(array("error"=>0, "dataRes"=>$msgErr, "sqls"=>$arrResp));
    }else{
        echo json_encode(array("error"=>1, "dataRes"=>$msgErr, "sqls"=>$arrResp));
    }
    
?>