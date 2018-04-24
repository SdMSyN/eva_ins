<?php

    include('../config/conexion.php');
    include('../config/variables.php');
    
    $idAlum = $_POST['inputIdAlum'];
    $idGrupo = $_POST['inputIdGrupo'];
    $msg = '';
    
    $sqlInsertAlumGrupo = "INSERT INTO $tGrupoAlums (grupo_id, alumno_id, creado) "
            . "VALUES ('$idGrupo', '$idAlum', '$dateNow')";
    if($con->query($sqlInsertAlumGrupo) === TRUE){
        $sqlGetMatsGrupo = "SELECT id FROM $tGMatProfs WHERE grupo_info_id='$idGrupo' ";
        $resGetMatsGrupo = $con->query($sqlGetMatsGrupo);
        if($resGetMatsGrupo->num_rows > 0){
            while($rowGetMatGrupo = $resGetMatsGrupo->fetch_assoc()){
                $idMatProf = $rowGetMatGrupo['id'];
                $sqlInsertMatAlum = "INSERT INTO $tGMatAlums (grupo_materia_profesor_id, usuario_alumno_id, creado) "
                        . "VALUES ('$idMatProf', '$idAlum', '$dateNow') ";
                if($con->query($sqlInsertMatAlum) === TRUE){
                    $ban = true;
                }else{
                    $msgErr .= 'Error al insertar materia del alumno.'.$con->error;
                    $ban = false;
                    break;
                }
            }
        }
    }else{
        $msgErr .= 'Error al insertar alumno dentro del grupo'.$con->error;
        $ban = false;
    }
    
    if($ban){
        echo json_encode(array("error"=>0));
    }else{
        echo json_encode(array("error"=>1, "msgErr"=>$msgErr));
    }

?>