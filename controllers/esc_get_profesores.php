<?php

    include('../config/conexion.php');
    include('../config/variables.php');
    $profesores = array();
    $msgErr = '';
    $ban = false;
    
    $idEsc = $_GET['idEsc'];
    
    $sqlGetProfesores = "SELECT * FROM $tProf WHERE escuela_id='$idEsc' AND activo=1 ";
    
    //Ordenar ASC y DESC
    $vorder = (isset($_POST['orderby'])) ? $_POST['orderby'] : "";
    if($vorder != ''){
        $sqlGetProfesores .= " ORDER BY ".$vorder;
    }
                
    $resGetProfesores = $con->query($sqlGetProfesores);
    if($resGetProfesores->num_rows > 0){
        while($rowGetProfesor = $resGetProfesores->fetch_assoc()){
            //$cadRes .= '<option value="'.$rowGetMateria['id'].'">'.$rowGetMateria['nombre'].'</option>';
            $id = $rowGetProfesor['id'];
            $name = $rowGetProfesor['nombre'];
            $user = $rowGetProfesor['user'];
            $pass = $rowGetProfesor['pass'];
            $profesores[] = array('id'=>$id, 'nombre'=>$name, 'user'=>$user,'pass'=>$pass);
            $ban = true;
        }
    }else{
        $ban = false;
        $msgErr = 'No existen profesores en tu institución   （┬┬＿┬┬） '.$con->error;
    }
    
    if($ban){
        echo json_encode(array("error"=>0, "dataRes"=>$profesores));
    }else{
        echo json_encode(array("error"=>1, "msgErr"=>$msgErr));
    }

?>