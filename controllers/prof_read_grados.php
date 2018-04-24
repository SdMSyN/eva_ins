<?php

    include('../config/conexion.php');
    include('../config/variables.php');
    $grado = array();
    $msgErr = '';
    $ban = false;
    $idProf = $_GET['idProf']; 
    
    //$sqlGetGrado = "SELECT nivel_grado_id as id FROM $tGrupo WHERE id='$idGrupo' ";
    $sqlGetGrado = "SELECT DISTINCT $tGrupo.nivel_grado_id as id, $tGrado.nombre as grado "
            . "FROM $tGMatProfs "
            . "INNER JOIN $tGrupo ON $tGrupo.id=$tGMatProfs.grupo_info_id "
            . "INNER JOIN $tGrado ON $tGrado.id=$tGrupo.nivel_grado_id "
            . "WHERE $tGMatProfs.usuario_profesor_id='$idProf'  ";
    $resGetGrado = $con->query($sqlGetGrado);
    if($resGetGrado->num_rows > 0){
        while($rowGetGrado = $resGetGrado->fetch_assoc()){
            $id = $rowGetGrado['id'];
            $nivel = $rowGetGrado['grado'];
            $grado[] = array('id'=>$id, 'nombre'=>$nivel);
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