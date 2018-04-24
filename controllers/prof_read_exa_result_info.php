<?php

    include('../config/conexion.php');
    include('../config/variables.php');
    $arrExaInfoAsigs = array();
    $msgErr = '';
    $ban = true;
    $idAsig = $_GET['idAsig']; 
    
    $sqlGetIDAlums = "SELECT $tExaInfAsigAlum.id as idExaInfAsigAlum, $tAlum.nombre as nombreAlum "
            . "FROM $tExaInfAsigAlum "
            . "INNER JOIN $tAlum ON $tAlum.id=$tExaInfAsigAlum.alumno_id "
            . "WHERE $tExaInfAsigAlum.exa_info_asig_id='$idAsig'  ";
    $resGetIDAlums = $con->query($sqlGetIDAlums);
    if($resGetIDAlums->num_rows > 0){
        while($rowGetIDAlum = $resGetIDAlums->fetch_assoc()){
            $idAsigAlum = $rowGetIDAlum['idExaInfAsigAlum'];
            $sqlGetResultInfo = "SELECT * FROM $tExaResultInfo WHERE exa_info_asig_alum_id='$idAsigAlum' ";
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
        echo json_encode(array("error"=>0, "dataRes"=>$arrExaInfoAsigs));
    }else{
        echo json_encode(array("error"=>1, "msgErr"=>$msgErr));
    }

?>