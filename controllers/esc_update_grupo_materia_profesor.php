<?php

    include('../config/conexion.php');
    include('../config/variables.php');

    $mat = $_POST['mat']; //idEscuela
    $prof = $_POST['prof'];
    $idGMatProf = $_POST['inputIdGMatProf'];
    
    $ban = true;
    $msgErr = '';
    
    $sqlUpdGMatProf = "UPDATE $tGMatProfs SET banco_materia_id='$mat', usuario_profesor_id='$prof' WHERE id='$idGMatProf' ";
    if($con->query($sqlUpdGMatProf) === TRUE){
        $ban = true;
    }else{
        $ban = false;
        $msgErr .= 'Error al actualizar asignación.'.$con->error;
    }
            
    if($ban){
        echo json_encode(array("error"=>0, "msgErr"=>$msgErr));
    }else{
        echo json_encode(array("error"=>1, "msgErr"=>$msgErr));
    }        
    
    
?>