<?php

    include('../config/conexion.php');
    include('../config/variables.php');
    
    $idNivel = $_POST['inputNivel'];
    $idGrado = $_POST['inputGrado'];
    $name = $_POST['inputName'];
    $msg = '';
    
    $sqlAddMat = "INSERT INTO $tBMat (nombre, nivel_escolar_id, nivel_grado_id, creado, actualizado) "
            . "VALUES ('$name', '$idNivel', '$idGrado', '$dateNow', '$dateNow') ";
    if($con->query($sqlAddMat) === TRUE){
        $msg = "Materia añadida con éxito.";
        echo json_encode(array("error"=>0, "msgErr"=>$msg));
    }else{
        $msg = "No se pudo añadir la materia -> ".$con->error;
        echo json_encode(array("error"=>1, "msgErr"=>$msg));
    }

?>