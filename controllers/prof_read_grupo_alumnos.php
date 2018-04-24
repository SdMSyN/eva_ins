<?php
    include ('../config/conexion.php');
    include('../config/variables.php');
    
    $idGrupo = (isset($_POST['inputGrupo'])) ? $_POST['inputGrupo'] : $_GET['inputGrupo'];
    //$idMat = $_POST['inputMat'];

    $msgErr = '';
    $ban = true;
    $arrAlums = array();
    
    $sqlGetAlums = "SELECT $tAlum.id as id, $tAlum.nombre as nombre "
            . "FROM $tGrupoAlums "
            . "INNER JOIN $tAlum ON $tAlum.id=$tGrupoAlums.alumno_id "
            . "WHERE $tGrupoAlums.grupo_id='$idGrupo' ";
    //Ordenar ASC y DESC
    $vorder = (isset($_POST['orderby'])) ? $_POST['orderby'] : "";
    if($vorder != ''){
        $sqlGetAlums .= " ORDER BY ".$vorder;
    }
    $resGetAlums = $con->query($sqlGetAlums);
    if($resGetAlums->num_rows > 0){
        while($rowGetAlum = $resGetAlums->fetch_assoc()){
            $idAlum = $rowGetAlum['id'];
            $nombre = $rowGetAlum['nombre'];
            $arrAlums[] = array('id'=>$idAlum, 'nombre'=>$nombre);
        }
    }else{
        $ban = false;
        $msgErr .= 'Error al buscar alumnos en éste grupo.'.$con->error;
    }
    
    if($ban){
        //print_r($arrClass);
        echo json_encode(array("error"=>0, "dataRes"=>$arrAlums));
        //echo json_encode(array("error"=>0, "dataRes"=>"Holi"));
    }else{
        echo json_encode(array("error"=>1, "msgErr"=>$msgErr));
    }
?>