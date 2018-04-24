<?php
    include ('../config/conexion.php');
    include('../config/variables.php');
    $idGroup = $_GET['idGrupo'];

    $msgErr = '';
    $ban = true;
    $arrAlumno = array();
    
    $sqlGetClass = "SELECT $tGrupoAlums.id as id, $tGrupoAlums.alumno_id as idAlum, "
            . "$tTut.id as idTut, $tTut.nombre as nombre, "
            . "$tTut.user as user, $tTut.pass as pass "
            . "FROM $tGrupoAlums "
            . "INNER JOIN $tAlum ON $tAlum.id=$tGrupoAlums.alumno_id "
            . "INNER JOIN $tTut ON $tTut.alumno_id=$tAlum.id "
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
            $idTut = $rowGetClass['idTut'];
            $nombre = $rowGetClass['nombre'];
            $user = $rowGetClass['user'];
            $pass = $rowGetClass['pass'];
            $arrAlumno[] = array('id'=>$id, 'idTut'=>$idTut, 'nombre'=>$nombre, 'user'=>$user, 'pass'=>$pass);
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