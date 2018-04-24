<?php
    include ('../config/conexion.php');
    include('../config/variables.php');
    
    $idAv = $_GET['idAv'];

    $msgErr = '';
    $ban = true;
    $arrNot = array();
    
    $sqlGetAvInfo = "SELECT $tAvAsigT.id as id, $tTut.nombre as nombreTut, "
            . "$tGrupo.nombre as grupo, $tGrado.nombre as grado, "
            . "$tAvAsigT.enterado, DATE_FORMAT($tAvAsigT.fecha_enterado, '%Y-%m-%d') as fecha_enterado "
            . "FROM $tAvAsigT "
            . "INNER JOIN $tTut ON $tTut.id=$tAvAsigT.tutor_id "
            . "INNER JOIN $tAlum ON $tAlum.id=$tTut.alumno_id "
            . "INNER JOIN $tGrupoAlums ON $tGrupoAlums.alumno_id=$tAlum.id "
            . "INNER JOIN $tGrupo ON $tGrupo.id=$tGrupoAlums.grupo_id "
            . "INNER JOIN $tGrado ON $tGrado.id=$tGrupo.nivel_grado_id "
            . "WHERE $tAvAsigT.aviso_info_id='$idAv' ";
    //Ordenar ASC y DESC
    $vorder = (isset($_POST['orderby'])) ? $_POST['orderby'] : "";
    if($vorder != ''){
        $sqlGetAvInfo .= ",".$vorder;
    }
    $resGetAvInfo = $con->query($sqlGetAvInfo);
    if($resGetAvInfo->num_rows > 0){
        while($rowGetAvInfo = $resGetAvInfo->fetch_assoc()){
            $idAvAsig = $rowGetAvInfo['id'];
            $nameTutor = $rowGetAvInfo['nombreTut'];
            $grado = $rowGetAvInfo['grado'];
            $grupo = $rowGetAvInfo['grupo'];
            $enteradoAvAsig = $rowGetAvInfo['enterado'];
            $enteradoFechaAvAsig = $rowGetAvInfo['fecha_enterado'];
            $arrNot[] = array('id'=>$idAvAsig, 'nombre'=>$nameTutor, 
                'grado'=>$grado, 'grupo'=>$grupo, 
                'enterado'=>$enteradoAvAsig, 'fechaEnterado'=>$enteradoFechaAvAsig);
        }
    }else{
        $ban = false;
        $msgErr .= 'No notificaste a los tutores.';
    }
    

    if($ban){
        echo json_encode(array("error"=>0, "dataRes"=>$arrNot));
    }else{
        echo json_encode(array("error"=>1, "msgErr"=>$msgErr));
    }
?>