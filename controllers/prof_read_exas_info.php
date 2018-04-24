<?php

    include('../config/conexion.php');
    include('../config/variables.php');
    $exams = array();
    $msgErr = '';
    $ban = false;
    $idProf = $_GET['idProf']; 
    
    $sqlGetExams = "SELECT $tExaInf.id, $tExaInf.nombre, $tExaInf.creado, $tBMat.nombre as materia, "
            . "(SELECT count(*) FROM $tExaPregs WHERE exa_info_id=$tExaInf.id) as numPregs "
            . "FROM $tExaInf "
            . "INNER JOIN $tBMat ON $tBMat.id=$tExaInf.banco_materia_id "
            . "WHERE $tExaInf.creado_por='$idProf' ";
    //Ordenar ASC y DESC
    $vorder = (isset($_POST['orderby'])) ? $_POST['orderby'] : "";
    if($vorder != ''){
        $sqlGetExams .= " ORDER BY ".$vorder;
    }
                
    $resGetExams = $con->query($sqlGetExams);
    if($resGetExams->num_rows > 0){
        while($rowGetExam = $resGetExams->fetch_assoc()){
            $id = $rowGetExam['id'];
            $name = $rowGetExam['nombre'];
            $mat = $rowGetExam['materia'];
            $created = $rowGetExam['creado'];
            $numPregs = $rowGetExam['numPregs'];
            $exams[] = array('id'=>$id, 'nombre'=>$name, 'materia'=>$mat, 'creado'=>$created, 'numPregs'=>$numPregs);
            $ban = true;
        }
    }else{
        $ban = false;
        $msgErr = 'No tienes examenes creados   （┬┬＿┬┬） '.$con->error;
    }
    
    if($ban){
        echo json_encode(array("error"=>0, "dataRes"=>$exams));
    }else{
        echo json_encode(array("error"=>1, "msgErr"=>$msgErr));
    }

?>