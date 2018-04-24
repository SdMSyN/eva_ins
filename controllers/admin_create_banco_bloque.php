<?php

    include('../config/conexion.php');
    include('../config/variables.php');
    
    $idMateria = $_POST['inputIdMateria'];
    $name = $_POST['inputName'];
    $msg = '';

    $sqlCreateBloque = "INSERT INTO $tBBloq (nombre, banco_materia_id, creado, actualizado) "
            . "VALUES ('$name', '$idMateria', '$dateNow', '$dateNow') ";
    if($con->query($sqlCreateBloque) === TRUE){
        $msg = "Bloque añadido con éxito.";
        echo json_encode(array("error"=>0, "msgErr"=>$msg));
    }else{
        $msg = "No se pudo añadir el bloque -> ".$con->error;
        echo json_encode(array("error"=>1, "msgErr"=>$msg));
    }

?>