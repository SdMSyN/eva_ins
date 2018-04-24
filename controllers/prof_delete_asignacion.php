<?php

    include('../config/conexion.php');
    include('../config/variables.php');

    $idAsig = $_GET['idAsig'];
    $ban = false;
    $banTmp = false;
    $msgErr = '';
    //borramos preguntas respondidas por el alumno
    $sqlDeleteAsigAlum = "DELETE FROM $tExaInfAsigAlum WHERE exa_info_asig_id='$idAsig' ";
    if($con->query($sqlDeleteAsigAlum) === TRUE){
        $sqlDeleteAsigInfo = "DELETE FROM $tExaInfAsig WHERE id='$idAsig' ";
        if($con->query($sqlDeleteAsigInfo) === TRUE){
            $ban = true;
            $msgErr = 'Asignación eliminada con éxito.';
        }else{
            $ban = false;
            $msgErr .= 'Error al borrar asignación.'.$con->error;
        }
    }else{
        $ban = false;
        $msgErr .= 'Error al borrar asignación del alumno.'.$con->error;
    }

    if($ban){
        echo json_encode(array("error"=>0, "dataRes"=>$msgErr));
    }else{
        echo json_encode(array("error"=>1, "dataRes"=>$msgErr));
    }
?>