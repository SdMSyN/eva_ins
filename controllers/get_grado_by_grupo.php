<?php

    include('../config/conexion.php');
    include('../config/variables.php');
    $grado = array();
    $msgErr = '';
    $ban = false;
    $idGrupo = $_GET['idGrupo']; 
    
    $sqlGetGrado = "SELECT nivel_grado_id as id FROM $tGrupo WHERE id='$idGrupo' ";
    $resGetGrado = $con->query($sqlGetGrado);
    if($resGetGrado->num_rows > 0){
        while($rowGetGrado = $resGetGrado->fetch_assoc()){
            $id = $rowGetGrado['id'];
            $grado[] = array('id'=>$id);
            $ban = true;
        }
    }else{
        $ban = false;
        $msgErr = 'No existen grados en este grupo   （┬┬＿┬┬） '.$con->error;
    }
    
    if($ban){
        echo json_encode(array("error"=>0, "dataRes"=>$grado));
    }else{
        echo json_encode(array("error"=>1, "msgErr"=>$msgErr));
    }

?>