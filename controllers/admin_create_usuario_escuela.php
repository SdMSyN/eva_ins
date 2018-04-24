<?php

    include('../config/conexion.php');
    include('../config/variables.php');
    
    $name = $_POST['inputName'];
    $user = $_POST['inputUser'];
    $pass = $_POST['inputPass'];
    $level = $_POST['inputLevel'];
    // Dirección
    $street = (isset($_POST['inputStreet'])) ? $_POST['inputStreet'] : NULL;
    $num = (isset($_POST['inputNum'])) ? $_POST['inputNum'] : NULL;
    $col = (isset($_POST['inputCol'])) ? $_POST['inputCol'] : NULL;
    $mun = (isset($_POST['inputMun'])) ? $_POST['inputMun'] : NULL;
    $cp = (isset($_POST['inputCP'])) ? $_POST['inputCP'] : NULL;
    $edo = (isset($_POST['inputEdo'])) ? $_POST['inputEdo'] : NULL;
    // Contacto
    $tel = (isset($_POST['inputTel'])) ? $_POST['inputTel'] : NULL;
    $cel = (isset($_POST['inputCel'])) ? $_POST['inputCel'] : NULL;
    $mail = $_POST['inputMail'];
    $face = (isset($_POST['inputFace'])) ? $_POST['inputFace'] : NULL;
    $twi = (isset($_POST['inputTwi'])) ? $_POST['inputTwi'] : NULL;

    //echo $type.'<br>'.$name.'<br>'.$user.'<br>'.$pass.'<br>'.$street.'<br>'.$num.'<br>'.$col.'<br>'.$mun.'<br>'.$edo.'<br>'.$tel.'<br>'.$cel.'<br>'.$mail.'<br>'.$face.'<br>'.$twi;
    $cadErr = '';
    $ban = false;
    
    $sqlInsertInfo = "INSERT INTO $tInfo "
        . "(calle, numero, colonia, municipio, cp, estado, telefono, celular, correo, "
            . "facebook, twitter, foto_perfil, creado, actualizado) "
        . "VALUES"
        . "('$street', '$num', '$col', '$mun', '$cp', '$edo', '$tel', '$cel', '$mail', "
            . "'$face', '$twi', 'eva.jpg','$dateNow', '$dateNow') ";
    if($con->query($sqlInsertInfo) === TRUE){
        $idInfo = $con->insert_id;
        //Obtenemos el número de escuelas
        $sqlGetNumEsc = "SELECT * FROM $tEsc ";
        $resGetNumEsc = $con->query($sqlGetNumEsc);
        $numEsc = $resGetNumEsc->num_rows;
        $clave = $user.($numEsc+1);
        
        $sqlInsertUser = "INSERT INTO $tEsc "
            ."(nombre, user, pass, clave, informacion_id, nivel_escolar_id, creado, actualizado) "
            . "VALUES ('$name', '$user', '$pass', '$clave', '$idInfo', '$level', '$dateNow', '$dateNow') ";
        if($con->query($sqlInsertUser) === TRUE){
            $ban = true;
        }else{
            $ban = false;
            $cadErr .= 'Error al crear nueva escuela<br>'.$con->error;
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
?>