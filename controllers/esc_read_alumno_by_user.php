<?php
    include ('../config/conexion.php');
    include('../config/variables.php');
    
    $userAlum = $_POST['inputUser'];

    $msgErr = '';
    $ban = true;
    $arrAlum = array();
    
    $sqlGetAlum = "SELECT id, nombre FROM $tAlum WHERE user='$userAlum' ";
    $resGetAlum = $con->query($sqlGetAlum);
    if($resGetAlum->num_rows > 0){
        $ban = true;
        $rowGetAlum = $resGetAlum->fetch_assoc();
        $idAlum = $rowGetAlum['id'];
        $nameAlum = $rowGetAlum['nombre'];
        $arrAlum[] = array('idAlum'=>$idAlum, 'nombreAlum'=>$nameAlum);
    }else{
        $ban = false;
        $msgErr .= 'No existe el alumno.';
    }
    

    if($ban){
        echo json_encode(array("error"=>0, "dataRes"=>$arrAlum));
    }else{
        echo json_encode(array("error"=>1, "msgErr"=>$msgErr));
    }
?>