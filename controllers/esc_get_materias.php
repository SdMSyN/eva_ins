<?php

    include('../config/conexion.php');
    include('../config/variables.php');
    $materia = array();
    $msgErr = '';
    $ban = false;
    
    $idEsc = $_GET['idEsc'];
    $idNivelEsc = $_GET['idNivelEsc'];
    
    $sqlGetMateria = "SELECT $tBMat.id as id, $tBMat.nombre as nombre, "
            . "$tGrado.nombre as grado, $tBMat.creado_por as creador "
            . "FROM $tBMat INNER JOIN $tGrado ON $tGrado.id=$tBMat.nivel_grado_id "
            . "WHERE $tBMat.nivel_escolar_id='$idNivelEsc' AND ($tBMat.creado_por='$idEsc' OR $tBMat.creado_por IS NULL) ";
    
    //Ordenar ASC y DESC
    $vorder = (isset($_POST['orderby'])) ? $_POST['orderby'] : "";
    if($vorder != ''){
        $sqlGetMateria .= " ORDER BY ".$vorder;
    }
                
    $resGetMateria = $con->query($sqlGetMateria);
    if($resGetMateria->num_rows > 0){
        while($rowGetMateria = $resGetMateria->fetch_assoc()){
            //$cadRes .= '<option value="'.$rowGetMateria['id'].'">'.$rowGetMateria['nombre'].'</option>';
            $id = $rowGetMateria['id'];
            $name = $rowGetMateria['nombre'];
            $grado = $rowGetMateria['grado'];
            $created = $rowGetMateria['creador'];
            $materia[] = array('id'=>$id, 'nombre'=>$name, 'grado'=>$grado,'creador'=>$created);
            $ban = true;
        }
    }else{
        $ban = false;
        $msgErr = 'No existen materias   （┬┬＿┬┬） '.$con->error;
    }
    
    if($ban){
        echo json_encode(array("error"=>0, "dataRes"=>$materia));
    }else{
        echo json_encode(array("error"=>1, "msgErr"=>$msgErr));
    }

?>