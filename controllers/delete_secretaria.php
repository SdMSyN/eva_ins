<?php

    include('../config/conexion.php');
    include('../config/variables.php');

    $idSec = $_GET['idSec'];
    $ban = false;
    $banTmp = false;
    $msgErr = '';

    $sqlDeleteSec = "UPDATE $tSec SET activo=0 WHERE id='$idSec' ";
    if($con->query($sqlDeleteSec) === TRUE){
        $ban = true;
    }else{
        $banTmp = false;
        $msgErr .= 'Error al borrar secretaria.'.$con->error;
    }

    
    if($ban){
        $msgErr = 'Se borro con éxito.';
        echo json_encode(array("error"=>0, "dataRes"=>$msgErr));
    }else{
        echo json_encode(array("error"=>1, "dataRes"=>$msgErr));
    }
?>