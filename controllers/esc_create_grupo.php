<?php

    include('../config/conexion.php');
    include('../config/variables.php');

    $idUser = $_POST['inputIdUser']; //idEscuela
    $nivel = $_POST['inputNivel'];
    $grado = $_POST['inputGrado'];
    $grupo = $_POST['inputGrupo'];
    $turno = $_POST['inputTurno'];
    $file = $_FILES['inputFile']['name'];
    
    $msgErr = ''; $cad = '';
    $ban = true;
    $arrIdMatsIns = array();
    
    //Buscamos si el grupo ya existe
    $sqlSearchGrupo = "SELECT id FROM $tGrupo "
            . "WHERE nivel_escolar_id='$nivel' AND nivel_turno_id='$turno' "
            . "AND nivel_grado_id='$grado' AND nombre='$grupo' AND usuario_escuela_id='$idUser'  ";
    $resSearchGrupo = $con->query($sqlSearchGrupo);
    if($resSearchGrupo->num_rows >= 1){
        $ban = false;
        $msgErr .= 'El grupo ya existe, no lo puedes volver a crear.';
    }
    
    //Recorremos los campos de materias y profesores que no haya ninguno vacio
    $countMats = count($_POST['mat']);
    for($i=0; $i < $countMats; $i++){
        $matTmp = $_POST['mat'][$i];
        $profTmp = $_POST['prof'][$i];
        if($matTmp == "" || $profTmp == ""){
            $msgErr .= 'No pueden ir materias o profesores vacios.';
            $ban = false;
            break;
        }
    }
    
    
    //Obtenemos clave del usuario que sube para colocarlo a su archivo
    $sqlGetClave = "SELECT clave FROM $tEsc WHERE id='$idUser' ";
    $resGetClave = $con->query($sqlGetClave);
    $rowGetClave = $resGetClave->fetch_assoc();
    $clave = $rowGetClave['clave'];
    
    //Asignamos nombre al archivo subido, con fecha
    $extFile = explode(".", $_FILES['inputFile']['name']);
    $nameFile = 'grupo_'.$clave.'_'.$dateNow.".".$extFile[1];
    
    //Procesamos Excel
    $destinoCsv = '../'.$csvUploads.'/'.$nameFile;
    $csv = @move_uploaded_file($_FILES["inputFile"]["tmp_name"], $destinoCsv);
    $sustituye = array("\r\n", "\n\r", "\n", "\r");
    // Validamos archivo CSV (estructura)
    if($csv){
        $csvFile = file($destinoCsv);
        $i = 0;
        foreach($csvFile as $linea_num => $linea){
            $i++;
            if($i == 1) continue;
            $linea = utf8_encode($linea);
            $datos = explode(",", $linea);
            $contador = count($datos);
            //Número de campos menor
            if($contador < 3){
                $msgErr .= 'Tu archivo tiene menos columnas de las requeridas.'.$i;
                $ban = false;
                break;
            }
            //Se excede el número de campos
            if($contador > 4){
                $msgErr .= 'Tu archivo tiene más columnas de las requeridas.'.$i;
                $ban = false;
                break;
            }
            //Buscamos los usuarios, si no existe alguno mandamos mensaje de error
            $usuario = trim(str_replace($sustituye, "", $datos[3]));
            if($usuario != ""){
                $sqlSearchUser = "SELECT id FROM $tAlum WHERE user='$usuario' ";
                $resSearchUser = $con->query($sqlSearchUser);
                if($resSearchUser->num_rows < 1){
                    $msgErr .= 'El usuario: '.$usuario.', no existe.'.$i;
                    $ban = false;
                    break;
                }
            }  
            //Validamos solo letras en los campos
            if(!preg_match('/^[a-zA-Z ]+$/', $datos[0]) || !preg_match('/^[a-zA-Z ]+$/', $datos[1]) || !preg_match('/^[a-zA-Z ]+$/', $datos[2])){
                $msgErr .= 'Los nombres y apellidos solo pueden contener letras (sin acentos), registro: '.$i.'--'.$datos[0].$datos[1].$datos[2];
                $ban = false;
                break;
            }
        }
    }else{
        $msgErr .= "Error al subir el archivo CSV.";
        $ban = false;
    }
    
    if($ban){
        $sqlInsertGrupo = "INSERT INTO $tGrupo "
                . "(nombre, nivel_escolar_id, nivel_turno_id, nivel_grado_id, usuario_escuela_id, creado, actualizado) "
                . "VALUES ('$grupo', '$nivel', '$turno', '$grado', '$idUser', '$dateNow', '$dateNow')";
        if($con->query($sqlInsertGrupo) === TRUE){
            $idGrupo = $con->insert_id;
            //Insertamos las materias y guardamos el id insertado en el arreglo
            for($j = 0; $j < $countMats; $j++){
                $mat = $_POST['mat'][$j];
                $prof = $_POST['prof'][$j];
                $sqlInsertMatProf = "INSERT INTO $tGMatProfs (banco_materia_id, usuario_profesor_id, grupo_info_id, creado) "
                        . "VALUES ('$mat', '$prof', '$idGrupo', '$dateNow')";
                if($con->query($sqlInsertMatProf) === TRUE){
                    $arrIdMatsIns[] = $con->insert_id;
                }else{
                    $ban = false;
                    $msgErr .= 'Error al insertar materia y profesor.'.$con->error;
                    break;
                }
            }
            
            //Recorremos Excel para insertar alumnos
            $csvFile = file($destinoCsv);
            $j = 0;
            foreach($csvFile as $linea_num => $linea){
                $j++;
                if($j == 1) continue;
                $linea = utf8_encode($linea);
                $datos = explode(",", $linea);
                $usuario = str_replace($sustituye, "", $datos[3]);
                if($usuario != ""){ //si hay algo en el campo de usuario
                    $sqlSearchUser = "SELECT id FROM $tAlum WHERE user='$usuario' ";
                    $resSearchUser = $con->query($sqlSearchUser);
                    $rowGetUser = $resSearchUser->fetch_assoc();
                    $idAlumno = $rowGetUser['id'];
                    $sqlInsertAlumnoGrupo = "INSERT INTO $tGrupoAlums (grupo_id, alumno_id, creado) "
                                . "VALUES ('$idGrupo', '$idAlumno','$dateNow')";
                        if($con->query($sqlInsertAlumnoGrupo) === TRUE){
                            $countArrIdMats = count($arrIdMatsIns);
                            for($k = 0; $k < $countArrIdMats; $k++){
                                $idMatProf = $arrIdMatsIns[$k];
                                $sqlInsertMatAlum = "INSERT INTO $tGMatAlums "
                                        . "(grupo_materia_profesor_id, usuario_alumno_id, creado) "
                                        . "VALUES ('$idMatProf', '$idAlumno', '$dateNow')";
                                if($con->query($sqlInsertMatAlum) === TRUE){
                                    continue;
                                }else{
                                    $ban = false;
                                    $msgErr .= 'Error al insertar Materia del Alumno.'.$j.'.'.$con->error;
                                    break;
                                }
                            }//end for countArrMatsIns
                        }else{
                            $msgErr .= 'Error al insertar grupo alumno.'.$j.'.'.$con->error;
                            $ban = false;
                            break;
                        }
                    //... proceso aquí
                }//end if usuario != null
                else{
                    //Obtenemos número de registros
                    $sqlGetNumAlum = "SELECT id FROM $tAlum";
                    $resGetNumAlum = $con->query($sqlGetNumAlum);
                    $getNumAlum = $resGetNumAlum->num_rows;
                    //Creamos clave usuario y contraseña
                    $nombre = $datos[0].' '.$datos[1].' '.$datos[2];
                    $apTmp = str_replace(' ', '', $datos[0]);
                    $clave = strtolower($datos[2]{0}).strtolower($apTmp).strtolower($datos[1]{0}).$getNumAlum;
                    $clave2 = generar_clave(10);
                    //Insertamos informacion del profesor
                    $sqlInsertInfoAlumno = "INSERT INTO $tInfo (foto_perfil, creado, actualizado) "
                            . "VALUES ('$fotoPerfil', '$dateNow', '$dateNow') ";
                    if($con->query($sqlInsertInfoAlumno) === TRUE){
                        $idInfo = $con->insert_id;
                        //Insertamos alumno
                        $sqlInsertAlumno = "INSERT INTO $tAlum "
                            . "(nombre, user, pass, clave, informacion_id, escuela_id, creado, actualizado, activo) "
                            . "VALUES "
                            . "('$nombre', '$clave', '$clave2', '$clave', '$idInfo', '$idUser', '$dateNow', '$dateNow', '1') ";
                        if($con->query($sqlInsertAlumno) === TRUE){
                            $idAlumno = $con->insert_id;
                            //Creamos información del tutor
                            $sqlInsertInfoTutor = "INSERT INTO $tInfo (foto_perfil, creado, actualizado) "
                                . "VALUES ('$fotoPerfil', '$dateNow', '$dateNow') ";
                            if($con->query($sqlInsertInfoTutor) === TRUE){
                                $idInfoTut = $con->insert_id;
                                $claveT = $clave.'t';
                                $clave2T = generar_clave(10);
                                $sqlInsertTutor = "INSERT INTO $tTut "
                                    . "(nombre, user, pass, clave, alumno_id, informacion_id, creado, actualizado) "
                                    . "VALUES "
                                    . "('$nombre', '$claveT', '$clave2T', '$claveT', '$idAlumno', '$idInfoTut', '$dateNow', '$dateNow')";
                                if($con->query($sqlInsertTutor) === TRUE){
                                    $sqlInsertAlumnoGrupo = "INSERT INTO $tGrupoAlums (grupo_id, alumno_id, creado) "
                                            . "VALUES ('$idGrupo', '$idAlumno','$dateNow')";
                                    if($con->query($sqlInsertAlumnoGrupo) === TRUE){
                                        $countArrIdMats = count($arrIdMatsIns);
                                        for($k = 0; $k < $countArrIdMats; $k++){
                                            $idMatProf = $arrIdMatsIns[$k];
                                            $sqlInsertMatAlum = "INSERT INTO $tGMatAlums "
                                                    . "(grupo_materia_profesor_id, usuario_alumno_id, creado) "
                                                    . "VALUES ('$idMatProf', '$idAlumno', '$dateNow')";
                                            if($con->query($sqlInsertMatAlum) === TRUE){
                                                continue;
                                            }else{
                                                $ban = false;
                                                $msgErr .= 'Error al insertar Materia del Alumno.'.$j.'.'.$con->error;
                                                break;
                                            }
                                        }//end for countArrMatsIns
                                    }else{
                                        $msgErr .= 'Error al insertar grupo alumno.'.$j.'.'.$con->error;
                                        $ban = false;
                                        break;
                                    }
                                }else{
                                    $msgErr .= 'Error al insertar Tutor.'.$j.'.'.$con->error;
                                    $ban = false;
                                    break;
                                }
                            }else{
                                $msgErr .= 'Error al insertar información del tutor..'.$j.'.'.$con->error;
                                $ban = false;
                                break;
                            }
                            
                            
                        }else{
                            $msgErr .= 'Error al insertar alumno.'.$j.'.'.$con->error;
                            $ban = false;
                            break;
                        }
                    }else{
                        $msgErr .= 'Error al insertar información del alumno.'.$j.'.'.$con->error;
                        $ban = false;
                        break;
                    }
                }//end else usuario existe o no
            }//end foreach csvFile
        }else{
            $msgErr .= 'Error al insertar grupo.'.$con->error;
            $ban = false;
        }
    }else{
        $msgErr .= "Hubo un error al validar CSV.";
        $ban = false;
    }
    
    if($ban){
        $cad .= 'Grupo añadido con éxito';
        echo json_encode(array("error"=>0, "msgErr"=>$cad));
    }else{
        echo json_encode(array("error"=>1, "msgErr"=>$msgErr));
    }

    //Función para generar password usuario
    // http://www.leonpurpura.com/tutoriales/generar-claves-aleatorias.html
    function generar_clave($longitud){ 
       $cadena="[^A-Z0-9]"; 
       return substr(eregi_replace($cadena, "", md5(rand())) . 
       eregi_replace($cadena, "", md5(rand())) . 
       eregi_replace($cadena, "", md5(rand())), 
       0, $longitud); 
    } 
    
?>