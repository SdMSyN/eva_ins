<?php
    session_start();
    include('../config/conexion.php');
    include('../config/variables.php');
    
    include('pagination.php');
    $idExam = $_GET['idExam'];
    $pregs = array();
    $resps = array();
    
    //forma aleatoria 
    /*if(!isset($_SESSION['exaRand'])){
        $sqlGetIds = "SELECT id FROM $tExaPregs WHERE exa_info_id='$idExam' ";
        $resGetIds = $con->query($sqlGetIds);
        $arrIds = array();
        //echo $sqlGetIds;
        while($rowGetIds = $resGetIds->fetch_assoc()){
            $arrIds[] = $rowGetIds['id'];
        }
        //print_r($arrIds);
        shuffle($arrIds);
        //print_r($arrIds);
        $_SESSION['exaRand'] = $arrIds;
    }*/
    //print_r($_SESSION['exaRand']);
    
    $page = (isset($_REQUEST['page'])) ? $_REQUEST['page'] : 1;
    $per_page = 1;
    $adjacents = 4;
    $offset = ($page - 1) * $per_page;
    $sqlGetCount = "SELECT count(*) as count FROM $tExaPregs WHERE exa_info_id='$idExam' ";
    if($resGetCount = $con->query($sqlGetCount)){
        $rowGetCount = $resGetCount->fetch_assoc();
        $numRows = $rowGetCount['count'];
    }
    $totalPages = ceil($numRows/$per_page);
    $reload = '../views/prof_prev_exam.php?idExam='.$idExam;
    
    //aleatorio
    //$idPregSess = $_SESSION['exaRand'][$page-1];
    
    //echo $idPregSess; 
    $sqlGetIdPregs = "SELECT banco_pregunta_id FROM $tExaPregs WHERE exa_info_id='$idExam' LIMIT $offset, $per_page ";
    $resGetIdPregs = $con->query($sqlGetIdPregs);
    while($rowGetIdPreg = $resGetIdPregs->fetch_assoc()){
        $idPregExam = $rowGetIdPreg['banco_pregunta_id'];
        
        $sqlGetInfo = "SELECT id, nombre, archivo, tipo_resp FROM $tBPregs WHERE id='$idPregExam' ";
        //print_r($_SESSION['exaRand']);
        $resGetInfo = $con->query($sqlGetInfo);
        while($rowGetInfo = $resGetInfo->fetch_assoc()){
            $idPreg = $rowGetInfo['id'];
            $nombrePreg = $rowGetInfo['nombre'];
            $archivoPreg = $rowGetInfo['archivo'];
            $tipoRespPreg = $rowGetInfo['tipo_resp'];
            $sqlGetResp = "SELECT id, nombre, archivo, correcta, tipo_resp, palabras FROM $tBResp WHERE banco_pregunta_id='$idPreg' ";
            $resGetResp = $con->query($sqlGetResp);
            while($rowGetResp = $resGetResp->fetch_assoc()){
                $idResp = $rowGetResp['id'];
                $nombreResp = $rowGetResp['nombre'];
                $archivoResp = $rowGetResp['archivo'];
                $respCorr = $rowGetResp['correcta'];
                $tipoRespResp = $rowGetResp['tipo_resp'];
                $palabrasResp = $rowGetResp['palabras'];
                $resps[] = array('id'=>$idResp, 'nombre'=>$nombreResp, 'respCorr'=>$respCorr, 'archivo'=>$archivoResp, 'tipoR'=>$tipoRespResp, 'palabra'=>$palabrasResp);
            }
            $pregs[] = array('id'=>$idPreg, 'nombre'=>$nombrePreg, 'archivo'=>$archivoPreg, 'tipoR'=>$tipoRespPreg, 'resps'=>$resps);
        }
    }//end while idPregs
    
    $paginador = '</table><div class="table-pagination text-center">'.paginate($reload, $page, $totalPages, $adjacents).'</div>';

    echo json_encode(array("error"=>0, "dataPregs"=>$pregs, 'pags'=>$paginador, 'sql'=>$sqlGetInfo));
?>