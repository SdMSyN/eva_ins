<?php
    include ('../config/conexion.php');
    include('../config/variables.php');
    
    $idExam = $_POST['inputIdExam'];
    //echo $idExam; 
    $msgErr = '';
    $ban = true;
    $arrPregs = array();
    $countChecks = count($_POST['checkIdPreg']);
    for($i = 0; $i < $countChecks; $i++){
        $idPreg = $_POST['checkIdPreg'][$i];
        $sqlInsertPregExa = "INSERT INTO $tExaPregs (banco_pregunta_id, exa_info_id, creado) "
                . "VALUES ('$idPreg', '$idExam', '$dateNow') ";
        if($con->query($sqlInsertPregExa) === TRUE){
            continue;
        }else{
            $ban = false;
            $msgErr .= 'Error al insertar pregunta en el examen.';
            break;
        }
    }
    
    if($ban){
        echo json_encode(array("error"=>0));
    }else{
        echo json_encode(array("error"=>1, "msgErr"=>$msgErr));
    }
?>