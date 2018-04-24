<?php

    include('../config/conexion.php');
    include('../config/variables.php');

    $idPreg = $_GET['idPreg'];
    $idExam = $_GET['idExam'];
    
    $ban = false;
    $banTmp = true;
    $msgErr = '';
    
    //borramos pregunta si todo salio bien borrando respuestas
    $sqlDeletePreg = "DELETE FROM $tExaPregs WHERE banco_pregunta_id='$idPreg' AND exa_info_id='$idExam' ";
    if($con->query($sqlDeletePreg) === TRUE){
        $ban = true;
    }else{
        $ban = false;
        $msgErr .= 'Error al borrar la pregunta.'.$idPreg.'--'.$con->error;
    }
    
    if($ban){
        $msgErr = 'Se borro con éxito.';
        echo json_encode(array("error"=>0, "dataRes"=>$msgErr));
    }else{
        echo json_encode(array("error"=>1, "dataRes"=>$msgErr));
    }
?>