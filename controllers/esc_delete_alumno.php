<?php

    include('../config/conexion.php');
    include('../config/variables.php');

    $idGrupo = $_GET['idGrupo'];
    $idAlum = $_GET['idAlum'];
    $ban = false;
    $banTmp = false;
    $msgErr = '';
    
    $sqlGetIdGrupoAlum = "SELECT id FROM $tGrupoAlums WHERE grupo_id='$idGrupo' AND alumno_id='$idAlum' ";
    $resGetIdGAlum = $con->query($sqlGetIdGrupoAlum);
    if($resGetIdGAlum->num_rows > 0){
        $rowGetIdGAlum = $resGetIdGAlum->fetch_assoc();
        $idGAlum = $rowGetIdGAlum['id'];
        $sqlDeleteIdGAlum = "DELETE FROM $tGrupoAlums WHERE id='$idGAlum' ";
        if($con->query($sqlDeleteIdGAlum) === TRUE){
            $ban = true;
        }else{
            $banTmp = false;
            $msgErr .= 'Error al borrar alumno.'.$con->error;
        }
    }else{
        $ban = false;
        $msgErr .= 'Error: No existe el alumno en éste grupo.'.$con->error;
    }

    if($ban){
        $msgErr = 'Se borro con éxito.';
        echo json_encode(array("error"=>0, "dataRes"=>$msgErr));
    }else{
        echo json_encode(array("error"=>1, "dataRes"=>$msgErr));
    }
?>