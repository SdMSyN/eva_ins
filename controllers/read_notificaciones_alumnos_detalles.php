<?php
    include ('../config/conexion.php');
    include('../config/variables.php');
    
    $idAv = $_GET['idAv'];

    $msgErr = '';
    $ban = true;
    $arrNot = array();
    
    $sqlGetAvInfo = "SELECT $tAvAsigA.id as id, $tAlum.nombre as nombreAlum, "
            . "$tGrupo.nombre as grupo, $tGrado.nombre as grado, "
            . "$tAvAsigA.enterado, DATE_FORMAT($tAvAsigA.fecha_enterado, '%Y-%m-%d') as fecha_enterado "
            . "FROM $tAvAsigA "
            . "INNER JOIN $tAlum ON $tAlum.id=$tAvAsigA.alumno_id "
            . "INNER JOIN $tGrupoAlums ON $tGrupoAlums.alumno_id=$tAlum.id "
            . "INNER JOIN $tGrupo ON $tGrupo.id=$tGrupoAlums.grupo_id "
            . "INNER JOIN $tGrado ON $tGrado.id=$tGrupo.nivel_grado_id "
            . "WHERE $tAvAsigA.aviso_info_id='$idAv' ";
    //echo $sqlGetAvInfo;
    //Ordenar ASC y DESC
    $vorder = (isset($_POST['orderby'])) ? $_POST['orderby'] : "";
    if($vorder != ''){
        $sqlGetAvInfo .= ",".$vorder;
    }
    $resGetAvInfo = $con->query($sqlGetAvInfo);
    if($resGetAvInfo->num_rows > 0){
        while($rowGetAvInfo = $resGetAvInfo->fetch_assoc()){
            $idAvAsig = $rowGetAvInfo['id'];
            $nameAlum = $rowGetAvInfo['nombreAlum'];
            $grado = $rowGetAvInfo['grado'];
            $grupo = $rowGetAvInfo['grupo'];
            $enteradoAvAsig = $rowGetAvInfo['enterado'];
            $enteradoFechaAvAsig = $rowGetAvInfo['fecha_enterado'];
            $arrNot[] = array('id'=>$idAvAsig, 'nombre'=>$nameAlum, 
                'grado'=>$grado, 'grupo'=>$grupo, 
                'enterado'=>$enteradoAvAsig, 'fechaEnterado'=>$enteradoFechaAvAsig);
        }
    }else{
        $ban = false;
        $msgErr .= 'No notificaste a los alumnos.';
    }
    

    if($ban){
        echo json_encode(array("error"=>0, "dataRes"=>$arrNot));
    }else{
        echo json_encode(array("error"=>1, "msgErr"=>$msgErr));
    }
?>