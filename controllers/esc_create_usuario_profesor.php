<?php

    include('../config/conexion.php');
    include('../config/variables.php');
    
    $idEsc = $_POST['inputIdEsc'];
    $name = $_POST['inputName'];
    $ap = $_POST['inputAP'];
    $am = $_POST['inputAM'];

    $cadErr = '';
    $ban = false;
    
    //Obtenemos número de registros
    $sqlGetNumProfs = "SELECT id FROM $tProf ";
    $resGetNumProfs = $con->query($sqlGetNumProfs);
    $getNumProfs = $resGetNumProfs->num_rows;
    //Creamos clave usuario y contraseña
    $nombre = $ap.' '.$am.' '.$name;
    $apTmp = str_replace(' ', '', $ap);
    $clave = strtolower($name{0}).strtolower($apTmp).strtolower($am{0}).$getNumProfs;
    $clave2 = generar_clave(10);
    
    $sqlInsertInfo = "INSERT INTO $tInfo (foto_perfil, creado, actualizado) "
            . "VALUES ('$fotoPerfil', '$dateNow', '$dateNow') ";
    if($con->query($sqlInsertInfo) === TRUE){
        $idInfo = $con->insert_id;
        $sqlInsertUser = "INSERT INTO $tProf "
            ."(nombre, user, pass, clave, informacion_id, escuela_id, creado, actualizado, activo) "
            . "VALUES ('$nombre', '$clave', '$clave2', '$clave', '$idInfo', '$idEsc', '$dateNow', '$dateNow', '1') ";
        if($con->query($sqlInsertUser) === TRUE){
            $ban = true;
        }else{
            $ban = false;
            $cadErr .= 'Error al crear nuevo profesor<br>'.$con->error;
        }
    }else{
        $ban = false;
        $cadErr .= 'Error al insertar información.<br>'.$con->error;
    }
    
    //$ban = true;
    if($ban){
        echo json_encode(array("error"=>'0'));
    }else{
        echo json_encode(array("error"=>1, "msgErr"=>$cadErr));
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