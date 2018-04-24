<?php

    include('../config/conexion.php');
    include('../config/variables.php');
    $escuelas = array();
    $msgErr = '';
    $ban = false;
    
    $sqlGetEscuelas = "SELECT $tEsc.*, $tNivEsc.nombre as nivel "
            . "FROM $tEsc "
            . "INNER JOIN $tNivEsc ON $tNivEsc.id=$tEsc.nivel_escolar_id ";
    
    //Ordenar ASC y DESC
    $vorder = (isset($_POST['orderby'])) ? $_POST['orderby'] : "";
    if($vorder != ''){
        $sqlGetEscuelas .= " ORDER BY ".$vorder;
    }
                
    $resGetEscuelas = $con->query($sqlGetEscuelas);
    if($resGetEscuelas->num_rows > 0){
        while($rowGetEscuela = $resGetEscuelas->fetch_assoc()){
            //$cadRes .= '<option value="'.$rowGetMateria['id'].'">'.$rowGetMateria['nombre'].'</option>';
            $id = $rowGetEscuela['id'];
            $name = $rowGetEscuela['nombre'];
            $user = $rowGetEscuela['user'];
            $pass = $rowGetEscuela['pass'];
            $nivel = $rowGetEscuela['nivel'];
            $created = $rowGetEscuela['creado'];
            $escuelas[] = array('id'=>$id, 'nombre'=>$name, 'user'=>$user,'pass'=>$pass,'nivel'=>$nivel,'creado'=>$created);
            $ban = true;
        }
    }else{
        $ban = false;
        $msgErr = 'No existen escuelas   （┬┬＿┬┬） '.$con->error;
    }
    
    if($ban){
        echo json_encode(array("error"=>0, "dataRes"=>$escuelas));
    }else{
        echo json_encode(array("error"=>1, "msgErr"=>$msgErr));
    }

?>