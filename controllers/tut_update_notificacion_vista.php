<?php
    include ('../config/conexion.php');
    include('../config/variables.php');
    
    $idAv = $_GET['idAv'];

    $msgErr = '';
    $ban = true;
    
    $sqlUpdateAv = "UPDATE $tAvAsigT SET enterado='1', fecha_enterado='$dateNow $timeNow' WHERE id='$idAv' ";
    if($con->query($sqlUpdateAv) === TRUE){
        $ban = true;
    }else{
        $ban = false;
        $msgErr .= 'Error al actualizsr aviso asignación.'.$con->error;
    }
    

    if($ban){
        echo json_encode(array("error"=>0));
    }else{
        echo json_encode(array("error"=>1, "msgErr"=>$msgErr));
    }
?>