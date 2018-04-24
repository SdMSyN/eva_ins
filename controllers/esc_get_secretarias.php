<?php

    include('../config/conexion.php');
    include('../config/variables.php');
    $secs = array();
    $msgErr = '';
    $ban = false;
    
    $idEsc = $_GET['idEsc'];
    
    $sqlGetSec = "SELECT * FROM $tSec WHERE escuela_id='$idEsc' AND activo=1 ";
    
    //Ordenar ASC y DESC
    $vorder = (isset($_POST['orderby'])) ? $_POST['orderby'] : "";
    if($vorder != ''){
        $sqlGetSec .= " ORDER BY ".$vorder;
    }
                
    $resGetSec = $con->query($sqlGetSec);
    if($resGetSec->num_rows > 0){
        while($rowGetSec = $resGetSec->fetch_assoc()){
            //$cadRes .= '<option value="'.$rowGetMateria['id'].'">'.$rowGetMateria['nombre'].'</option>';
            $id = $rowGetSec['id'];
            $name = $rowGetSec['nombre'];
            $user = $rowGetSec['user'];
            $pass = $rowGetSec['pass'];
            $secs[] = array('id'=>$id, 'nombre'=>$name, 'user'=>$user,'pass'=>$pass);
            $ban = true;
        }
    }else{
        $ban = false;
        $msgErr = 'No existen secretarias en tu institución   （┬┬＿┬┬） '.$con->error;
    }
    
    if($ban){
        echo json_encode(array("error"=>0, "dataRes"=>$secs));
    }else{
        echo json_encode(array("error"=>1, "msgErr"=>$msgErr));
    }

?>