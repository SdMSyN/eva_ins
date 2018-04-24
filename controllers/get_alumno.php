<?php
    include ('../config/conexion.php');
    include('../config/variables.php');
    $idAlum = $_POST['idAlum'];

    $msgErr = '';
    $ban = true;
    $arrAlumno = array();
    
    $sqlGetAlum = "SELECT * FROM $tAlum WHERE id='$idAlum'  ";
    //echo $sqlGetClass;

    $resGetAlum = $con->query($sqlGetAlum);
    if($resGetAlum->num_rows > 0){
        while($rowGetAlum = $resGetAlum->fetch_assoc()){
            $idAlumno = $rowGetAlum['id'];
            $nombre = $rowGetAlum['nombre'];
            $arrAlumno[] = array('id'=>$idAlumno,'nombre'=>$nombre);
        }
    }else{
        $ban = false;
        $msgErr .= 'No existe el alumno.';
    }
    
    if($ban){
        echo json_encode(array("error"=>0, "dataRes"=>$arrAlumno));
    }else{
        echo json_encode(array("error"=>1, "msgErr"=>$msgErr));
    }
?>