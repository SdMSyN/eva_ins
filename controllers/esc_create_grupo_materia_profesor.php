<?php

    include('../config/conexion.php');
    include('../config/variables.php');

    $mat = $_POST['mat']; //idEscuela
    $prof = $_POST['prof'];
    $idGrupo = $_POST['inputGrupo'];
    $idEsc = $_POST['inputIdEsc'];
    
    $ban = true;
    $msgErr = '';
    //falta validar que la materia y el profesor no ésten asignados
    $sqlSearchMatProf = "SELECT * FROM $tGMatProfs "
            . "WHERE banco_materia_id='$mat' AND usuario_profesor_id='$prof' AND grupo_info_id='$idGrupo' ";
    $resSearchMatProf = $con->query($sqlSearchMatProf);
    if($resSearchMatProf->num_rows > 0){
        $ban = false;
        $msgErr .= 'Error: Esta materia y éste profesor ya existen en éste grupo.';
    }else{
        $sqlInsertGrupoMatProf = "INSERT INTO $tGMatProfs (banco_materia_id, usuario_profesor_id, grupo_info_id, creado) "
                . "VALUES ('$mat', '$prof', '$idGrupo', '$dateNow')";
        if($con->query($sqlInsertGrupoMatProf) === TRUE){
            $idGrupoMatProf = $con->insert_id;
            //Obtenemos todos los alumnos del grupo y les insertamos en GrupoMateriaAlumnos
            $sqlGetAlums = "SELECT alumno_id FROM $tGrupoAlums WHERE grupo_id='$idGrupo' ";
            $resGetAlums = $con->query($sqlGetAlums);
            if($resGetAlums->num_rows > 0){
                while($rowGetAlum = $resGetAlums->fetch_assoc()){
                    $idAlum = $rowGetAlum['alumno_id'];
                    $sqlInsertGrupoMatAlum = "INSERT INTO $tGMatAlums (grupo_materia_profesor_id, usuario_alumno_id, creado) "
                            . "VALUES ('$idGrupoMatProf', '$idAlum', '$dateNow')";
                    if($con->query($sqlInsertGrupoMatAlum) === TRUE){
                        $ban = true;
                        $msgErr = 'Matería añadida con éxito.';
                    }else{
                        $ban = false;
                        $msgErr .= 'Error al insertar Materia y Alumno al Grupo.'.$con->error;
                        break;
                    }
                }
            }else{
                $ban = false;
                $msgErr .= '¿No existen alumnos en éste grupo?';
            }
        }else{
            $ban = false;
            $msgErr .= 'Error al insertar materia y profesor al grupo.'.$con->error;
        }
    }//end else  
            
    if($ban){
        echo json_encode(array("error"=>0, "msgErr"=>$msgErr));
    }else{
        echo json_encode(array("error"=>1, "msgErr"=>$msgErr));
    }        
    
    
?>