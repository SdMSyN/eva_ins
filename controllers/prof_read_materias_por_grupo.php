<?php
    include ('../config/conexion.php');
    include('../config/variables.php');
    $idGrupo = $_GET['idGrupo'];
    $idProf = $_GET['idProf'];

    $msgErr = '';
    $ban = true;
    $arrMats = array();
    
    $sqlGetMats = "SELECT $tGMatProfs.id as idMat, $tBMat.nombre as nombre "
            . "FROM $tGMatProfs INNER JOIN $tBMat ON $tBMat.id=$tGMatProfs.banco_materia_id "
            . "WHERE $tGMatProfs.grupo_info_id='$idGrupo' AND $tGMatProfs.usuario_profesor_id='$idProf' ";
    //echo $sqlGetClass;

    $resGetMats = $con->query($sqlGetMats);
    if($resGetMats->num_rows > 0){
        while($rowGetMats = $resGetMats->fetch_assoc()){
            $idMat = $rowGetMats['idMat'];
            $nombreMat = $rowGetMats['nombre'];
            $arrMats[] = array('id'=>$idMat,'mat'=>$nombreMat);
        }
    }else{
        $ban = false;
        $msgErr .= 'No tienes materias asignadas.';
    }
    
    if($ban){
        //print_r($arrClass);
        echo json_encode(array("error"=>0, "dataRes"=>$arrMats));
        //echo json_encode(array("error"=>0, "dataRes"=>"Holi"));
    }else{
        echo json_encode(array("error"=>1, "msgErr"=>$msgErr));
    }
?>