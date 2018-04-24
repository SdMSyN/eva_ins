<?php
    include ('../config/conexion.php');
    include('../config/variables.php');
    
    $nameAsig = $_POST['inputNombreAsig'];
    $beginF = $_POST['inputBeginF'];
    $beginH = $_POST['inputBeginH'];
    $endF = $_POST['inputEndF'];
    $endH = $_POST['inputEndH'];
    $hora = $_POST['inputH'];
    $min = $_POST['inputM'];
        $begin = $beginF.' '.$beginH;
        $end = $endF.' '.$endH;
        $timeExa = $hora.':'.$min;
    $showResult = $_POST['inputShowResult'];
    $aleatorio = (isset($_POST['inputAle'])) ? 1 : 0;
    $idExam = $_POST['inputIdExam'];
    $idGrupo = $_POST['inputGrupoId'];
    $idGrupoMatProf = $_POST['inputIdGrupoMatProf'];
    //echo $idExam; 
    $msgErr = '';
    $ban = true;
    $arrPregs = array();
    $countChecks = count($_POST['checkIdAlum']);
    
    $fechaResult = '';
    if($showResult == 0){
        $fechaResult = NULL;
    }else if($showResult == 1){
        $fechaResult = $end;
    }else if($showResult == 2){
        $nuevafecha = strtotime('+1 day', strtotime($end));
        $fechaResult = date('Y-m-d H:i', $nuevafecha);
    }else if($showResult == 3){
        $nuevafecha = strtotime('+7 day', strtotime($end));
        $fechaResult = date('Y-m-d H:i', $nuevafecha);
    }else{
        $fechaResult = NULL;
    }
    
    $sqlInsertAsigInfo = "INSERT INTO $tExaInfAsig "
            . "(nombre, grupo_materia_profesor_id, inicio, fin, mostrar_resultado, tiempo, aleatorio, exa_info_id, creado, actualizado) "
            . "VALUES "
            . "('$nameAsig', '$idGrupoMatProf', '$begin', '$end', '$fechaResult', '$timeExa', '$aleatorio', '$idExam', '$dateNow', '$dateNow') ";
    if($con->query($sqlInsertAsigInfo) === TRUE){
        $idAsigInfo = $con->insert_id;
        for($i = 0; $i < $countChecks; $i++){
            $idAlum = $_POST['checkIdAlum'][$i];
            $sqlInsertAsigAlum = "INSERT INTO $tExaInfAsigAlum "
                    . "(grupo_id, alumno_id, exa_info_asig_id, creado, actualizado) "
                    . "VALUES "
                    . "('$idGrupo', '$idAlum', '$idAsigInfo', '$dateNow', '$dateNow') ";
            if($con->query($sqlInsertAsigAlum) === TRUE){
                continue;
            }else{
                $ban = false;
                $msgErr .= 'Error al insertar asignación de examen al alumno.';
                break;
            }
        }
    }else{
        $ban = false;
        $msgErr .= 'Error al insertar información de la asignación.'.$con->error;
    }
    
    if($ban){
        echo json_encode(array("error"=>0));
    }else{
        echo json_encode(array("error"=>1, "msgErr"=>$msgErr));
    }
?>