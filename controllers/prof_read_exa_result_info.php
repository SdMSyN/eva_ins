<?php

    include('../config/conexion.php');
    include('../config/variables.php');
    $arrExaInfoAsigs = array();
    $msgErr = '';
    $ban = true;
    $idAsig = $_GET['idAsig']; 
    
    $sqlGetIDAlums = "SELECT exa_info_asig_alum.id as idExaInfAsigAlum, usuarios_alumnos.nombre as nombreAlum, "
            . "exa_info_asig.exa_info_id AS idExam "
            . "FROM exa_info_asig_alum "
            . "INNER JOIN usuarios_alumnos ON usuarios_alumnos.id=exa_info_asig_alum.alumno_id "
            . "INNER JOIN exa_info_asig ON exa_info_asig_alum.exa_info_asig_id = exa_info_asig.id "
            . "WHERE exa_info_asig_alum.exa_info_asig_id='$idAsig'  ";
    $resGetIDAlums = $con->query($sqlGetIDAlums);
    if($resGetIDAlums->num_rows > 0){
        while($rowGetIDAlum = $resGetIDAlums->fetch_assoc()){
            $idAsigAlum = $rowGetIDAlum['idExaInfAsigAlum'];
            $idExam = $rowGetIDAlum['idExam'];
            $sqlGetResultInfo = " SELECT * FROM est_exa_result_info "
                . "WHERE exa_info_asig_alum_id = '$idAsigAlum' "
                . " AND est_exa_result_info.exa_info_id = '$idExam' "
                . "ORDER BY est_exa_result_info.id DESC ";
            $resGetResultInfo = $con->query($sqlGetResultInfo);
            $nombreAlum = $rowGetIDAlum['nombreAlum'];
            $pregResp = '';
            $pregNoResp = '';
            $pregCorr = '';
            $pregIncorr = '';
            $valorAlum = '';
            $califAlum = '';
            if($resGetResultInfo->num_rows > 0){
                $rowGetResultInfo = $resGetResultInfo->fetch_assoc();
                $pregResp = $rowGetResultInfo['preg_contestadas'];
                $pregNoResp = $rowGetResultInfo['preg_no_contestadas'];
                $pregCorr = $rowGetResultInfo['resp_buenas'];
                $pregIncorr = $rowGetResultInfo['resp_malas'];
                $valorAlum = $rowGetResultInfo['valor_exa_alum'];
                $califAlum = $rowGetResultInfo['calificacion'];
            }else{
                //No ha respondido
                $pregResp = null;
                $pregNoResp = null;
                $pregCorr = null;
                $pregIncorr = null;
                $valorAlum = null;
                $califAlum = null;
            }
            $arrExaInfoAsigs[] = array('idAsigAlum'=>$idAsigAlum, 'nombreAlum'=>$nombreAlum, 
                'pregResp'=>$pregResp, 'pregNoResp'=>$pregNoResp, 'pregCorr'=>$pregCorr, 
                'pregIncorr'=>$pregIncorr, 'valorAlum'=>$valorAlum, 'califAlum'=>$califAlum);
        }
    }else{
        $ban = false;
        $msgErr .= 'Error no existen alumnos en la asignación.';
    }
  
    if($ban){
        echo json_encode(array("error"=>0, "dataRes"=>$arrExaInfoAsigs, "sql"=>$sqlGetIDAlums));
    }else{
        echo json_encode(array("error"=>1, "msgErr"=>$msgErr));
    }

?>