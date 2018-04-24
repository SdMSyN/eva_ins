<?php

    include('../config/conexion.php');
    include('../config/variables.php');
    
    $idUser = $_GET['idUser'];
    $idExam = $_GET['idExam'];
    $idExamAsig = $_GET['idExamAsig'];
    $idExamAsigAlum = $_GET['idExamAsigAlum'];
    //echo $idUser.'--'.$idExam;
    $ban = true;
    $msgErr = '';
    $arrResult = array();
    
    $sqlGetResultInfo = "SELECT * FROM $tExaResultInfo WHERE exa_info_id='$idExam' "
            ."AND exa_info_asig_alum_id='$idExamAsigAlum' AND alumno_id='$idUser' ";
    //echo $sqlGetResultInfo;
    $resGetResultInfo = $con->query($sqlGetResultInfo);
    //echo $sqlGetResultInfo;
    if($resGetResultInfo->num_rows > 0){
        while($rowGetResultInfo = $resGetResultInfo->fetch_assoc()){
            $numPregs = $rowGetResultInfo['num_pregs'];
            $numPregsResp = $rowGetResultInfo['preg_contestadas'];
            $numPregsNoResp = $rowGetResultInfo['preg_no_contestadas'];
            $numRespCorr = $rowGetResultInfo['resp_buenas'];
            $numRespInco = $rowGetResultInfo['resp_malas'];
            $valorExa = $rowGetResultInfo['valor_exa'];
            $valorExaAlum = $rowGetResultInfo['valor_exa_alum'];
            $calif = $rowGetResultInfo['calificacion'];
            $porc = $rowGetResultInfo['porcentaje'];
            $hi = $rowGetResultInfo['hora_inicio'];
            $hf = $rowGetResultInfo['hora_fin'];
            $arrResult[] = array('numPregs'=>$numPregs, 'numPregsResp'=>$numPregsResp, 
                'numPregsNoResp'=>$numPregsNoResp, 'numCorr'=>$numRespCorr, 'numInco'=>$numRespInco, 
                'valorExa'=>$valorExa, 'valorAlum'=>$valorExaAlum, 'calif'=>$calif, 'porc'=>$porc, 
                'hi'=>$hi, 'hf'=>$hf);
        }
    }else{
        $ban = false;
        $msgErr .= 'Error al obtener información.'.$con->error;
    }
                          
    if($ban){
        $msgErr = 'Éxito al calificar tu examen';
        echo json_encode(array("error"=>0, "dataRes"=>$arrResult));
    }else{
        echo json_encode(array("error"=>1, "dataRes"=>$msgErr));
    }
    
?>