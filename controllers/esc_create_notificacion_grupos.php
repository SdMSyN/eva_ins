<?php
    include ('../config/conexion.php');
    include('../config/variables.php');
    
    $idEsc = $_POST['idEsc'];
    $aviso = $_POST['inputAviso'];
    $avisoTipo = $_POST['inputAvTipo'];
    $avisoDest = $_POST['inputAvDest']; //1=alums, 2=Tutos y 3=Ambos
    $countGrupos = count($_POST['checkIdGrupo']);
    //echo $idEsc.'--'.$aviso.'--'.$avisoTipo.'--'.$avisoDest;
    
    $msgErr = '';
    $ban = true;
    $arrNot = array();

    $sqlInsertAvisoInfo = "INSERT INTO $tAvInfo "
            . "(nombre, tipo_aviso_id, dirigido_a, creado_por, perfil_creador, escuela_id, creado) "
            . "VALUES ('$aviso', '$avisoTipo', '$avisoDest', '$idEsc', '1', '$idEsc', '$dateNow')";
    if($con->query($sqlInsertAvisoInfo) === TRUE){
        $idAviso = $con->insert_id;
        //Recorrer grupos y obtener los id de los alumnos
        for($i = 0; $i < $countGrupos; $i++){
            $idGrupo = $_POST['checkIdGrupo'][$i];
            $sqlGetAlums = "SELECT alumno_id FROM $tGrupoAlums WHERE grupo_id='$idGrupo' ";
            $resGetAlums = $con->query($sqlGetAlums);
            if($resGetAlums->num_rows > 0){
                while($rowGetAlum = $resGetAlums->fetch_assoc()){
                    $idAlum = $rowGetAlum['alumno_id'];
                    if($avisoDest == 1){//Alumnos
                        $sqlInsertAvAsigAlum = "INSERT INTO $tAvAsigA (aviso_info_id, alumno_id, creado) "
                                . "VALUES ('$idAviso', '$idAlum', '$dateNow')";
                        if($con->query($sqlInsertAvAsigAlum) === TRUE){
                            continue;
                        }else{
                            $ban = false;
                            $msgErr .= 'Error al insertar aviso asignación del alumno.'.$con->error;
                            break;
                        }
                    }else if($avisoDest == 2){//Tutores
                        //Buscamos a los tutores del alumno
                        $sqlGetTutores = "SELECT id FROM $tTut WHERE alumno_id='$idAlum' ";
                        $resGetTutores = $con->query($sqlGetTutores);
                        if($resGetTutores->num_rows > 0){
                            $rowGetTutor = $resGetTutores->fetch_assoc();
                            $idTutor = $rowGetTutor['id'];
                            $sqlInsertAvAsigTut = "INSERT INTO $tAvAsigT (aviso_info_id, tutor_id, creado) "
                                    . "VALUES ('$idAviso', '$idTutor', '$dateNow') ";
                            if($con->query($sqlInsertAvAsigTut) === TRUE){
                                continue;
                            }else{
                                $ban = false;
                                $msgErr .= 'Error al insertar aviso asignación del tutor.'.$con->error;
                                break;
                            }
                        }else{
                            continue; //Debería de tener tutor cada alumno
                        }
                    }else if($avisoDest == 3){//Ambos
                        $sqlInsertAvAsigAlum = "INSERT INTO $tAvAsigA (aviso_info_id, alumno_id, creado) "
                                . "VALUES ('$idAviso', '$idAlum', '$dateNow')";
                        if($con->query($sqlInsertAvAsigAlum) === TRUE){
                            $sqlGetTutores = "SELECT id FROM $tTut WHERE alumno_id='$idAlum' ";
                            $resGetTutores = $con->query($sqlGetTutores);
                            if($resGetTutores->num_rows > 0){
                                $rowGetTutor = $resGetTutores->fetch_assoc();
                                $idTutor = $rowGetTutor['id'];
                                $sqlInsertAvAsigTut = "INSERT INTO $tAvAsigT (aviso_info_id, tutor_id, creado) "
                                        . "VALUES ('$idAviso', '$idTutor', '$dateNow') ";
                                if($con->query($sqlInsertAvAsigTut) === TRUE){
                                    continue;
                                }else{
                                    $ban = false;
                                    $msgErr .= 'Error al insertar aviso asignación del tutor.'.$con->error;
                                    break;
                                }
                            }else{
                                continue;
                            }
                        }else{
                            $ban = false;
                            $msgErr .= 'Error al insertar aviso asignación del alumno.'.$con->error;
                            break;
                        }
                    }else{
                        $ban = false;
                        $msgErr .= 'Error: ¿De dónde vienes?';
                    }
                }
            }else{
                $ban = false;
                $msgErr .= 'Error: No existen alumnos en ésta escuela.';
            }
        }//end for
        
    }else{
        $ban = false;
        $msgErr .= 'Error: No se pudo insertar la información del aviso.'.$con->error;
    }
        
    if($ban){
        echo json_encode(array("error"=>0));
    }else{
        echo json_encode(array("error"=>1, "msgErr"=>$msgErr));
    }
?>