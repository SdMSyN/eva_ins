<?php

    include('../config/conexion.php');
    include('../config/variables.php');

    $idAlum = $_GET['idAlum'];
    $ban = false;
    $banTmp = false;
    $msgErr = '';
    //borramos preguntas respondidas por el alumno
    $sqlDeleteAlum = "UPDATE $tAlum SET activo=0 WHERE id='$idAlum' ";
    if($con->query($sqlDeleteAlum) === TRUE){
        $ban = true;
    }else{
        $banTmp = false;
        $msgErr .= 'Error al borrar alumno.'.$con->error;
    }

    if($ban){
        $msgErr = 'Se borro con éxito.';
        echo json_encode(array("error"=>0, "dataRes"=>$msgErr));
    }else{
        echo json_encode(array("error"=>1, "dataRes"=>$msgErr));
    }
?>