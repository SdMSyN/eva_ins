<?php

    include('../config/conexion.php');
    include('../config/variables.php');
    $grupos = array();
    $msgErr = '';
    $ban = false;
    
    $idProf = $_GET['idProf'];
    
    $sqlGetGrupos = "SELECT $tGrupo.id as id, $tGrupo.nombre as nombre, "
            . "$tTurn.nombre as turno, $tGrado.nombre as grado "
            . "FROM $tGMatProfs "
            . "INNER JOIN $tGrupo ON $tGrupo.id=$tGMatProfs.grupo_info_id "
            . "INNER JOIN $tGrado ON $tGrado.id=$tGrupo.nivel_grado_id "
            . "INNER JOIN $tTurn ON $tTurn.id=$tGrupo.nivel_turno_id "
            . "WHERE $tGMatProfs.usuario_profesor_id='$idProf' ";
    
    //Ordenar ASC y DESC
    $vorder = (isset($_POST['orderby'])) ? $_POST['orderby'] : "";
    if($vorder != ''){
        $sqlGetGrupos .= " ORDER BY ".$vorder;
    }
                
    $resGetGrupos = $con->query($sqlGetGrupos);
    if($resGetGrupos->num_rows > 0){
        while($rowGetGrupo = $resGetGrupos->fetch_assoc()){
            //$cadRes .= '<option value="'.$rowGetMateria['id'].'">'.$rowGetMateria['nombre'].'</option>';
            $id = $rowGetGrupo['id'];
            $name = $rowGetGrupo['nombre'];
            $grado = $rowGetGrupo['grado'];
            $turno = $rowGetGrupo['turno'];
            $grupos[] = array('id'=>$id, 'nombre'=>$name, 'grado'=>$grado, 'turno'=>$turno);
            $ban = true;
        }
    }else{
        $ban = false;
        $msgErr = 'No existen grupos   （┬┬＿┬┬） '.$con->error;
    }
    
    if($ban){
        echo json_encode(array("error"=>0, "dataRes"=>$grupos));
    }else{
        echo json_encode(array("error"=>1, "msgErr"=>$msgErr));
    }

?>