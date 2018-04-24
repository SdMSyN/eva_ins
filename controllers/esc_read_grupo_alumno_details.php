<?php
    include ('../config/conexion.php');
    include('../config/variables.php');
    $idGroup = $_GET['idGrupo'];

    $msgErr = '';
    $ban = true;
    $arrAlumno = array();
    
    $sqlGetClass = "SELECT $tGrupoAlums.id as id, $tGrupoAlums.alumno_id as idAlum, $tAlum.nombre as nombre, "
            . "$tAlum.user as user, $tAlum.pass as pass "
            . "FROM $tGrupoAlums INNER JOIN $tAlum ON $tAlum.id=$tGrupoAlums.alumno_id "
            . "WHERE $tGrupoAlums.grupo_id='$idGroup' AND $tAlum.activo=1  ";
    //echo $sqlGetClass;
    //Ordenar ASC y DESC
    $vorder = (isset($_POST['orderby'])) ? $_POST['orderby'] : "";
    if($vorder != ''){
        $sqlGetClass .= " ORDER BY ".$vorder;
    }
    $resGetClass = $con->query($sqlGetClass);
    if($resGetClass->num_rows > 0){
        while($rowGetClass = $resGetClass->fetch_assoc()){
            $id = $rowGetClass['id'];
            $idAlumno = $rowGetClass['idAlum'];
            $nombre = $rowGetClass['nombre'];
            $user = $rowGetClass['user'];
            $pass = $rowGetClass['pass'];
            $arrAlumno[] = array('id'=>$id, 'idAlumno'=>$idAlumno, 'nombre'=>$nombre, 'user'=>$user, 'pass'=>$pass);
        }
    }else{
        $ban = false;
        $msgErr .= 'No tienes alumnos.';
    }
    
    if($ban){
        echo json_encode(array("error"=>0, "dataRes"=>$arrAlumno));
    }else{
        echo json_encode(array("error"=>1, "msgErr"=>$msgErr));
    }
?>