<?php

    include('../config/conexion.php');
    include('../config/variables.php');
    $arrExaInfoAsigs = array();
    $msgErr = '';
    $ban = true;
    $idProf = $_GET['idProf']; 
    
    $sqlGetInfoAsig = "SELECT $tGMatProfs.id as idMatProf, $tGrupo.nombre as grupo, "
            . "$tGrado.nombre as grado, $tBMat.nombre as mat, "
            . "$tExaInfAsig.id as idAsig, $tExaInfAsig.nombre as nombreAsig, $tExaInfAsig.inicio as inicio, "
            . "$tExaInfAsig.fin as fin, $tExaInf.nombre as nombreExa, "
            . "(SELECT COUNT(*) FROM $tExaPregs WHERE exa_info_id=$tExaInf.id) as numPregs "
            . "FROM $tGMatProfs "
            . "INNER JOIN $tGrupo ON $tGrupo.id=$tGMatProfs.grupo_info_id "
            . "INNER JOIN $tGrado ON $tGrado.id=$tGrupo.nivel_grado_id "
            . "INNER JOIN $tBMat ON $tBMat.id=$tGMatProfs.banco_materia_id "
            . "INNER JOIN $tExaInfAsig ON $tExaInfAsig.grupo_materia_profesor_id=$tGMatProfs.id "
            . "INNER JOIN $tExaInf ON $tExaInf.id=$tExaInfAsig.exa_info_id "
            . "WHERE $tGMatProfs.usuario_profesor_id='$idProf' ";
    
    //Ordenar ASC y DESC
    $vorder = (isset($_POST['orderby'])) ? $_POST['orderby'] : "";
    if($vorder != ''){
        $sqlGetInfoAsig .= " ORDER BY ".$vorder;
    }
    //echo $sqlGetInfoAsig;
    //Ejecutamos query
    $resGetInfoAsig = $con->query($sqlGetInfoAsig);
    while($rowGetInfoAsig = $resGetInfoAsig->fetch_assoc()){
        $idMatProf = $rowGetInfoAsig['idMatProf'];
        $nombreMat = $rowGetInfoAsig['mat'];
        $grado = $rowGetInfoAsig['grado'];
        $grupo = $rowGetInfoAsig['grupo'];
        $idAsig = $rowGetInfoAsig['idAsig'];
        $nombreAsig = $rowGetInfoAsig['nombreAsig'];
        $inicio = $rowGetInfoAsig['inicio'];
        $fin = $rowGetInfoAsig['fin'];
        $nombreExa = $rowGetInfoAsig['nombreExa'];
        $numPregs = $rowGetInfoAsig['numPregs'];
        $numAlums = 0;
        $numEvals = 0;
        //Obtenemos los alumnos asignados al examen
        $sqlGetAlumsAsig = "SELECT * FROM $tExaInfAsigAlum WHERE exa_info_asig_id='$idAsig' ";
        $resGetAlumsAsig = $con->query($sqlGetAlumsAsig);
        $numAlums = $resGetAlumsAsig->num_rows;
        while($rowGetAlumsAsig = $resGetAlumsAsig->fetch_assoc()){
            $idExaInfAsigAlum = $rowGetAlumsAsig['id'];
            $sqlGetAlumsEval = "SELECT * FROM $tExaResultInfo WHERE exa_info_asig_alum_id='$idExaInfAsigAlum' ";
            $resGetAlumsEval = $con->query($sqlGetAlumsEval);
            if($resGetAlumsEval->num_rows > 0) $numEvals++;
        }
        
        $arrExaInfoAsigs[] = array('idMatProf'=>$idMatProf, 'nombreMat'=>$nombreMat, 
            'idAsig'=>$idAsig, 'nombreAsig'=>$nombreAsig, 'inicio'=>$inicio, 'fin'=>$fin, 
            'nombreExa'=>$nombreExa, 'numPregs'=>$numPregs, 'numAlums'=>$numAlums, 
            'numEvals'=>$numEvals, 'grado'=>$grado, 'grupo'=>$grupo);
    }
    
    if($ban){
        echo json_encode(array("error"=>0, "dataRes"=>$arrExaInfoAsigs));
    }else{
        echo json_encode(array("error"=>1, "msgErr"=>$msgErr));
    }

?>