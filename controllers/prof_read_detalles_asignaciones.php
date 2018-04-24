<?php

    include('../config/conexion.php');
    include('../config/variables.php');
    $infoAsig = array();
    $msgErr = '';
    $ban = true;
    $idAsigInfo = $_GET['idAsigInfo'];
    
    $sqlGetAlumAsig = "SELECT $tAlum.nombre as nombre FROM $tExaInfAsigAlum "
            . "INNER JOIN $tAlum ON $tAlum.id=$tExaInfAsigAlum.alumno_id  "
            . "WHERE $tExaInfAsigAlum.exa_info_asig_id='$idAsigInfo' ";
    $resGetAlumAsig = $con->query($sqlGetAlumAsig);
    if($resGetAlumAsig->num_rows > 0){
        while($rowGetAlumAsig = $resGetAlumAsig->fetch_assoc()){
            $name = $rowGetAlumAsig['nombre'];
            $infoAsig[] = array('nombre'=>$name);
        }
    }else{
        $ban = false;
        $msgErr .= 'Error: No existen alumnos en ésta asignación.';
    }

    
    if($ban){
        echo json_encode(array("error"=>0, "dataRes"=>$infoAsig));
    }else{
        echo json_encode(array("error"=>1, "msgErr"=>$msgErr));
    }

?>