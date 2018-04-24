<?php

    include('../config/conexion.php');
    include('../config/variables.php');

    $idPreg = $_GET['idPreg'];
    $ban = false;
    $banTmp = false;
    $msgErr = '';

    $sqlDeletePreg = "UPDATE $tBPregs SET activo=0, actualizado='$dateNow' WHERE id='$idPreg' ";
    if($con->query($sqlDeletePreg) === TRUE){
        $ban = true;
    }else{
        $banTmp = false;
        $msgErr .= 'Error al borrar pregunta.'.$con->error;
    }

    
    if($ban){
        $msgErr = 'Se borro con éxito.';
        echo json_encode(array("error"=>0, "dataRes"=>$msgErr));
    }else{
        echo json_encode(array("error"=>1, "dataRes"=>$msgErr));
    }
?>