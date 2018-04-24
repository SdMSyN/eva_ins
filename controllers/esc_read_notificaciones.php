<?php
    include ('../config/conexion.php');
    include('../config/variables.php');
    
    $idEsc = $_GET['idEsc'];

    $msgErr = '';
    $ban = true;
    $arrNot = array();
    
    $sqlGetAvInfo = "SELECT $tAvInfo.nombre, $tAvTipo.nombre as tipo, $tAvInfo.dirigido_a, "
            . "$tAvInfo.perfil_creador, $tAvInfo.creado_por, $tAvInfo.id as id, $tAvInfo.creado "
            . "FROM $tAvInfo "
            . "INNER JOIN $tAvTipo ON $tAvTipo.id=$tAvInfo.tipo_aviso_id "
            . "WHERE $tAvInfo.escuela_id='$idEsc' ";
    //Ordenar ASC y DESC
    $vorder = (isset($_POST['orderby'])) ? $_POST['orderby'] : "";
    if($vorder != ''){
        $sqlGetAvInfo .= " ORDER BY ".$vorder;
    }
    $resGetAvInfo = $con->query($sqlGetAvInfo);
    if($resGetAvInfo->num_rows > 0){
        while($rowGetAvInfo = $resGetAvInfo->fetch_assoc()){
            $notId = $rowGetAvInfo['id'];
            $notName = $rowGetAvInfo['nombre'];
            $notType = $rowGetAvInfo['tipo'];
            $notDest = $rowGetAvInfo['dirigido_a'];
            $perfil = $rowGetAvInfo['perfil_creador'];
            $idUser = $rowGetAvInfo['creado_por'];
            $notDate = $rowGetAvInfo['creado'];
            //Obtenemos el nombre del creador del aviso
            $tCreador = ($perfil == 1) ? $tEsc : $tProf;
            $sqlGetNameCreador = "SELECT nombre FROM $tCreador WHERE id='$idUser' ";
            $resGetNameCreador = $con->query($sqlGetNameCreador);
            $rowGetNameCreador = $resGetNameCreador->fetch_assoc();
            $nameCreador = $rowGetNameCreador['nombre'];
            //Obtenemos números de informados Alumnos
            $sqlGetNumAlums = "SELECT COUNT(*) as numAlumsAv, "
                    . "(SELECT COUNT(*) FROM $tAvAsigA WHERE aviso_info_id='$notId' AND enterado IS NOT NULL) as numEnt "
                    . "FROM $tAvAsigA "
                    . "WHERE aviso_info_id='$notId' ";
            $resGetNumAlums = $con->query($sqlGetNumAlums);
            $rowGetNumAlums = $resGetNumAlums->fetch_assoc();
            $numAvAlum = $rowGetNumAlums['numAlumsAv'];
            $numAvAlumEnt = $rowGetNumAlums['numEnt'];
            //Obtenemos números de informados tutores
            $sqlGetNumTuts = "SELECT COUNT(*) as numTutAv, "
                    . "(SELECT COUNT(*) FROM $tAvAsigT WHERE aviso_info_id='$notId' AND enterado IS NOT NULL) as numEnt "
                    . "FROM $tAvAsigT "
                    . "WHERE aviso_info_id='$notId' ";
            $resGetNumTuts = $con->query($sqlGetNumTuts);
            $rowGetNumTuts = $resGetNumTuts->fetch_assoc();
            $numAvTuts = $rowGetNumTuts['numTutAv'];
            $numAvTutsEnt = $rowGetNumTuts['numEnt'];
            $arrNot[] = array('id'=>$notId, 'nombre'=>$notName, 'tipo'=>$notType, 
                'dirigido'=>$notDest, 'fecha'=>$notDate, 'creador'=>$nameCreador, 
                'numAlum'=>$numAvAlum, 'numAlumEnt'=>$numAvAlumEnt, 
                'numTut'=>$numAvTuts, 'numTutEnt'=>$numAvTutsEnt);
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