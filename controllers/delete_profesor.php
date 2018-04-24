<?php

    include('../config/conexion.php');
    include('../config/variables.php');

    $idProf = $_GET['idProf'];
    $ban = false;
    $banTmp = false;
    $msgErr = '';
    //buscamos si el profesor aún existe en la asignación de materias
    $sqlSearchProf = "SELECT id FROM $tGMatProfs WHERE usuario_profesor_id='$idProf' ";
    $resSearchProf = $con->query($sqlSearchProf);
    if($resSearchProf->num_rows > 0){
        $ban = false;
        $msgErr .= "Error: El profesor aún esta asignado a una materia. \nPrimero quita su asignación";
    }else{
        $sqlDeleteProf = "UPDATE $tProf SET activo=0 WHERE id='$idProf' ";
        if($con->query($sqlDeleteProf) === TRUE){
            $ban = true;
        }else{
            $banTmp = false;
            $msgErr .= 'Error al borrar profesor.'.$con->error;
        }
    }
    
    if($ban){
        $msgErr = 'Se borro con éxito.';
        echo json_encode(array("error"=>0, "dataRes"=>$msgErr));
    }else{
        echo json_encode(array("error"=>1, "dataRes"=>$msgErr));
    }
?>