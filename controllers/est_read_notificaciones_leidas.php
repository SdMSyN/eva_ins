<?php
    include ('../config/conexion.php');
    include('../config/variables.php');
    
    $idEst = $_GET['idEst'];

    $msgErr = '';
    $ban = true;
    $arrNot = array();
    
    $sqlGetAvInfo = "SELECT $tAvAsigA.id as id, $tAvAsigA.enterado as enterado, $tAvInfo.nombre, $tAvTipo.nombre as tipo, $tAvInfo.dirigido_a, "
            . "$tAvInfo.perfil_creador, $tAvInfo.creado_por, $tAvInfo.id as idAvInfo, $tAvInfo.creado "
            . "FROM $tAvAsigA "
            . "INNER JOIN $tAvInfo ON $tAvInfo.id=$tAvAsigA.aviso_info_id "
            . "INNER JOIN $tAvTipo ON $tAvTipo.id=$tAvInfo.tipo_aviso_id "
            . "WHERE $tAvAsigA.alumno_id='$idEst' AND $tAvAsigA.enterado IS NOT NULL "
            . "ORDER BY creado ";
    //Ordenar ASC y DESC
    $vorder = (isset($_POST['orderby'])) ? $_POST['orderby'] : "";
    if($vorder != ''){
        $sqlGetAvInfo .= ",".$vorder;
    }
    $resGetAvInfo = $con->query($sqlGetAvInfo);
    if($resGetAvInfo->num_rows > 0){
        while($rowGetAvInfo = $resGetAvInfo->fetch_assoc()){
            $notIdAsig = $rowGetAvInfo['id'];
            $notName = $rowGetAvInfo['nombre'];
            $notType = $rowGetAvInfo['tipo'];
            $notDest = $rowGetAvInfo['dirigido_a'];
            $perfil = $rowGetAvInfo['perfil_creador'];
            $idUser = $rowGetAvInfo['creado_por'];
            $notDate = $rowGetAvInfo['creado'];
            $enterado = $rowGetAvInfo['enterado'];
            $tCreador = ($perfil == 1) ? $tEsc : $tProf;
            $sqlGetNameCreador = "SELECT nombre FROM $tCreador WHERE id='$idUser' ";
            $resGetNameCreador = $con->query($sqlGetNameCreador);
            $rowGetNameCreador = $resGetNameCreador->fetch_assoc();
            $nameCreador = $rowGetNameCreador['nombre'];
            $arrNot[] = array('id'=>$notIdAsig, 'nombre'=>$notName, 'tipo'=>$notType, 
                'dirigido'=>$notDest, 'fecha'=>$notDate, 'creador'=>$nameCreador, 'enterado'=>$enterado);
        }
    }else{
        $ban = false;
        $msgErr .= 'No existen notificaciones.';
    }
    

    if($ban){
        echo json_encode(array("error"=>0, "dataRes"=>$arrNot));
    }else{
        echo json_encode(array("error"=>1, "msgErr"=>$msgErr));
    }
?>