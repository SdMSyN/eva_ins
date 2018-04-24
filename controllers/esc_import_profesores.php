<?php

    include('../config/conexion.php');
    include('../config/variables.php');

    $idUser = $_POST['inputIdEsc']; //idEscuela
    $file = $_FILES['inputFile']['name'];
    
    //Obtenemos clave del usuario que sube para colocarlo a su archivo
    $sqlGetClave = "SELECT clave FROM $tEsc WHERE id='$idUser' ";
    $resGetClave = $con->query($sqlGetClave);
    $rowGetClave = $resGetClave->fetch_assoc();
    $clave = $rowGetClave['clave'];
    
    //Asignamos nombre al archivo subido, con fecha
    $extFile = explode(".", $_FILES['inputFile']['name']);
    $nameFile = 'prof_'.$clave.'_'.$dateNow.".".$extFile[1];
    
    //Procesamos Excel
    //$destinoCsv = '../'.$csvUploads.'/'.$file;
    $destinoCsv = '../'.$csvUploads.'/'.$nameFile;
    $csv = @move_uploaded_file($_FILES["inputFile"]["tmp_name"], $destinoCsv);
    $msgErr = ''; $cad = '';
    $ban = true;
    $sustituye = array("\r\n", "\n\r", "\n", "\r");
    // Validamos archivo CSV (estructura)
    if($csv){
        $csvFile = file($destinoCsv);
        $i = 0;
        foreach($csvFile as $linea_num => $linea){
            $i++;
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
            if($contador > 3){
                $msgErr .= 'Tu archivo tiene más columnas de las requeridas.'.$i;
                $ban = false;
                break;
            }
            //Validamos solo letras en los campos
            $nombre = str_replace($sustituye, "", $datos[2]);
            if(!preg_match('/^[a-zA-Z ]+$/', $datos[0]) || !preg_match('/^[a-zA-Z ]+$/', $datos[1]) || !preg_match('/^[a-zA-Z ]+$/', $nombre)){
                $msgErr .= 'Los nombres y apellidos solo pueden contener letras (sin acentos), registro: '.$i.'--'.$datos[0].$datos[1].$nombre;
                $ban = false;
                break;
            }
        }
    }else{
        $msgErr .= "Error al subir el archivo CSV.";
        $ban = false;
    }
    
    
    if($ban){
        $csvFile = file($destinoCsv);
        $j = 0;
        foreach($csvFile as $linea_num => $linea){
            $j++;
            $linea = utf8_encode($linea);
            $datos = explode(",", $linea);
            if($j == 1) continue;
            else{
                //Obtenemos número de registros
                $sqlGetNumProfs = "SELECT id FROM $tProf ";
                $resGetNumProfs = $con->query($sqlGetNumProfs);
                $getNumProfs = $resGetNumProfs->num_rows;
                //Creamos clave usuario y contraseña
                $nombre2 = str_replace($sustituye, "", $datos[2]);
                $nombre = $datos[0].' '.$datos[1].' '.$nombre2;
                $apTmp = str_replace(' ', '', $datos[0]);
                $clave = strtolower($datos[2]{0}).strtolower($apTmp).strtolower($datos[1]{0}).$getNumProfs;
                $clave2 = generar_clave(10);
                //Insertamos informacion del profesor
                $sqlInsertInfoProf = "INSERT INTO $tInfo (foto_perfil, creado, actualizado) "
                        . "VALUES ('$fotoPerfil', '$dateNow', '$dateNow') ";
                if($con->query($sqlInsertInfoProf) === TRUE){
                    $idInfo = $con->insert_id;
                    //Insertamos alumno
                    $sqlInsertProf = "INSERT INTO $tProf "
                        . "(nombre, user, pass, clave, informacion_id, escuela_id, creado, actualizado, activo) "
                        . "VALUES "
                        . "('$nombre', '$clave', '$clave2', '$clave', '$idInfo', '$idUser', '$dateNow', '$dateNow', '1') ";
                    if($con->query($sqlInsertProf) === TRUE){
                        continue;
                    }else{
                        $msgErr .= 'Error al insertar profesor.'.$j;
                        $ban = false;
                        break;
                    }
                }else{
                    $msgErr .= 'Error al insertar información del profesor.'.$j;
                    $ban = false;
                    break;
                }
            }//end else
        }//end for each
    }else{
        $msgErr .= "Hubo un error al validar CSV.";
        $ban = false;
    }
    
    
    if($ban){
        $cad .= 'Profesores importados con éxito';
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