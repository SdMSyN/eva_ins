<?php

    include('../config/conexion.php');
    include('../config/variables.php');
    $infoAsig = array();
    $msgErr = '';
    $ban = false;
    $idExam = $_GET['idExam'];
    
    $sqlGetExaAsig = "SELECT $tExaInfAsig.id, $tExaInfAsig.nombre,  "
            . "DATE_FORMAT($tExaInfAsig.inicio, '%d-%m-%Y %H:%i') as inicio, DATE_FORMAT($tExaInfAsig.fin, '%d-%m-%Y %H:%i') as fin, "
            . "$tExaInfAsig.tiempo as tiempo, $tExaInfAsig.aleatorio, $tExaInfAsig.creado, "
            . "$tGrupo.nombre as grupo, $tGrado.nombre as grado, "
            . "$tExaInfAsig.grupo_materia_profesor_id, $tGMatProfs.banco_materia_id, $tBMat.nombre as materia "
            . "FROM $tExaInfAsig "
            . "INNER JOIN $tGMatProfs ON $tGMatProfs.id=$tExaInfAsig.grupo_materia_profesor_id "
            . "INNER JOIN $tGrupo ON $tGrupo.id=$tGMatProfs.grupo_info_id "
            . "INNER JOIN $tGrado ON $tGrado.id=$tGrupo.nivel_grado_id "
            . "INNER JOIN $tBMat ON $tBMat.id=$tGMatProfs.banco_materia_id "
            . "WHERE $tExaInfAsig.exa_info_id='$idExam' ";
    //if(isset($_GET['idExamAsig'])){ $idExamAsig=$_GET['idExamAsig']; $sqlGetExaAsig .= " AND $tExaAsig.id='$idExamAsig' ";}     
    $resGetExaAsig = $con->query($sqlGetExaAsig);
    if($resGetExaAsig->num_rows > 0){
        while($rowGetExaAsig = $resGetExaAsig->fetch_assoc()){
            $id = $rowGetExaAsig['id'];
            $nombre = $rowGetExaAsig['nombre'];
            $grupo = $rowGetExaAsig['grupo'];
            $grado = $rowGetExaAsig['grado'];
            $materia = $rowGetExaAsig['materia'];
            $inicio = $rowGetExaAsig['inicio'];
            $fin = $rowGetExaAsig['fin'];
            $tiempo = $rowGetExaAsig['tiempo'];
            $aleatorio = $rowGetExaAsig['aleatorio'];
            $created = $rowGetExaAsig['creado'];
            $infoAsig[] = array('id'=>$id, 'nombre'=>$nombre, 'grupo'=>$grupo, 'grado'=>$grado,
                'materia'=>$materia, 'inicio'=>$inicio, 'fin'=>$fin, 
                'tiempo'=>$tiempo, 'aleatorio'=>$aleatorio, 'creado'=>$created);
            $ban = true;
        }
    }else{
        $ban = false;
        $msgErr = 'No has asignado éste examen.'.$con->error;
    }
    
    if($ban){
        echo json_encode(array("error"=>0, "dataRes"=>$infoAsig));
    }else{
        echo json_encode(array("error"=>1, "msgErr"=>$msgErr));
    }

?>