<?php

    include('../config/conexion.php');
    include('../config/variables.php');
    $arr = array();
    $msgErr = '';
    $ban = true;
    $idProf = $_GET['idProf']; 
    
    $sqlGetInfo = "";
    //Ordenar ASC y DESC
    $vorder = (isset($_POST['orderby'])) ? $_POST['orderby'] : "";
    if($vorder != ''){
        $sqlGetInfo .= " ORDER BY ".$vorder;
    }
    //Ejecutamos query
    
    if($ban){
        echo json_encode(array("error"=>0, "dataRes"=>$arr));
    }else{
        echo json_encode(array("error"=>1, "msgErr"=>$msgErr));
    }

?>