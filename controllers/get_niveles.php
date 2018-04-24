<?php

    include('../config/conexion.php');
    include('../config/variables.php');
    $nivel = array();
    $msgErr = '';
    $ban = false;
    
    $sqlGetNiveles = "SELECT * FROM $tNivEsc ";
    //Ordenar ASC y DESC
    $vorder = (isset($_POST['orderby'])) ? $_POST['orderby'] : "";
    if($vorder != ''){
        $sqlGetNiveles .= " ORDER BY ".$vorder;
    }
                
    $resGetNiveles = $con->query($sqlGetNiveles);
    if($resGetNiveles -> num_rows > 0){
        while($rowGetNiveles = $resGetNiveles->fetch_assoc()){
            //$cadRes .= '<option value="'.$rowGetMateria['id'].'">'.$rowGetMateria['nombre'].'</option>';
            $id = $rowGetNiveles['id'];
            $name = $rowGetNiveles['nombre'];
            $created = $rowGetNiveles['creado'];
            $nivel[] = array('id'=>$id, 'nombre'=>$name, 'creado'=>$created);
            $ban = true;
        }
    }else{
        $ban = false;
        $msgErr = 'No existen niveles   （┬┬＿┬┬） '.$con->error;
    }
    
    if($ban){
        echo json_encode(array("error"=>0, "dataRes"=>$nivel));
    }else{
        echo json_encode(array("error"=>1, "msgErr"=>$msgErr));
    }

?>