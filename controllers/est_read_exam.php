<?php
    session_start();
    include('../config/conexion.php');
    include('../config/variables.php');
    include('pagination2.php');
    $idExam = $_GET['idExam'];
    $idExamAsig = $_GET['idExamAsig'];
    $idExamAsigAlum = $_GET['idExamAsigAlum'];
    $idUser = $_GET['idUser'];
    $pregs = array();
    $resps = array();
    $sql = array();
    
    //forma aleatoria 
    if(!isset($_SESSION['exaRand'])){
        $sqlGetIds = "SELECT banco_pregunta_id as id FROM $tExaPregs WHERE exa_info_id='$idExam' ";
        $resGetIds = $con->query($sqlGetIds);
        $arrIds = array();
        //echo $sqlGetIds;
        while($rowGetIds = $resGetIds->fetch_assoc()){
            $arrIds[] = $rowGetIds['id'];
        }
        //print_r($arrIds);
        //Buscamos si es aleatorio o no
        $sqlGetAleatorio = "SELECT aleatorio FROM $tExaInfAsig WHERE id='$idExamAsig' ";
        $resGetAeatorio = $con->query($sqlGetAleatorio);
        $rowGetAleatorio = $resGetAeatorio->fetch_assoc();
        $aleatorio = $rowGetAleatorio['aleatorio'];
        if($aleatorio == 1)
            shuffle($arrIds);
        //print_r($arrIds);
        $_SESSION['exaRand'] = $arrIds;
    }
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
    $reload = '../views/est_read_exam.php?idExam='.$idExam.'&idExamAsig='.$idExamAsig;
    //aleatorio
    $idPregSess = $_SESSION['exaRand'][$page-1];
    
    //$sqlGetInfo = "SELECT id, nombre, archivo, valor_preg, tipo_resp FROM $tBPregs WHERE exa_info_id='$idExam' AND id='$idPregSess' LIMIT 0, 1";
    $sqlGetInfo = "SELECT id, nombre, archivo, valor_preg, tipo_resp "
            . "FROM $tBPregs WHERE id='$idPregSess' LIMIT 0, 1";
    
    $resGetInfo = $con->query($sqlGetInfo);
    while($rowGetInfo = $resGetInfo->fetch_assoc()){
        $idPreg = $rowGetInfo['id'];
        $nombrePreg = $rowGetInfo['nombre'];
        $archivoPreg = $rowGetInfo['archivo'];
        $valorPreg = $rowGetInfo['valor_preg'];
        $tipoRespPreg = $rowGetInfo['tipo_resp'];
        $sqlGetResp = "SELECT id, nombre, archivo, tipo_resp, palabras FROM $tBResp WHERE banco_pregunta_id='$idPreg' ";
        $sql[] = $sqlGetResp;
        $resGetResp = $con->query($sqlGetResp);
        while($rowGetResp = $resGetResp->fetch_assoc()){
            $idResp = $rowGetResp['id'];
            $nombreResp = $rowGetResp['nombre'];
            $archivoResp = $rowGetResp['archivo'];
            $tipoRespResp = $rowGetResp['tipo_resp'];
            $palabrasResp = $rowGetResp['palabras'];
            //Buscamos si la respuesta ya ha sido dada de alta 
            $sqlGetRespTmp = "SELECT id, tipo_resp_id, respuesta, respuesta_id FROM $tExaTmp "
                    . "WHERE alumno_id='$idUser' AND exa_info_id='$idExam' AND exa_info_asig_alum_id='$idExamAsigAlum' "
                    . "AND pregunta_id='$idPreg' AND tipo_resp_id='$tipoRespResp' ";
            $resGetRespTmp = $con->query($sqlGetRespTmp);
            $respSelect = '';
            $respTexto = '';
            $idRespTmp = null;
            if($resGetRespTmp->num_rows > 0){//si ya han respondido anteriormente
                $rowGetRespTmp = $resGetRespTmp->fetch_assoc();
                $idRespTmp = $rowGetRespTmp['respuesta_id'];
                if($tipoRespResp == 1){
                    if($rowGetRespTmp['respuesta'] == $idResp)
                        $respSelect = true;
                }else if($tipoRespResp == 2){
                    $checksSelect = explode(',',$rowGetRespTmp['respuesta']);
                    for($i = 0; $i < count($checksSelect); $i++){
                        if($checksSelect[$i] == $idResp){
                            $respSelect = true;
                        }
                    }
                }else if($tipoRespResp == 3 || $tipoRespResp == 4){
                    $respTexto = $rowGetRespTmp['respuesta'];
                }
            }else{//no existe

            }
            $resps[] = array('id'=>$idResp, 'nombre'=>$nombreResp, 'archivo'=>$archivoResp, 'tipoR'=>$tipoRespResp, 'palabra'=>$palabrasResp, 'seleccionada'=>$respSelect, 'texto'=>$respTexto, 'idResp'=>$idRespTmp);
        }
        //aleatorio
        $pregs[] = array('id'=>$idPreg, 'nombre'=>$nombrePreg, 'archivo'=>$archivoPreg, 'valorPreg'=>$valorPreg, 'tipoR'=>$tipoRespPreg, 'resps'=>$resps, 'tmp'=>$idPregSess);
        //$pregs[] = array('id'=>$idPreg, 'nombre'=>$nombrePreg, 'archivo'=>$archivoPreg, 'tipoR'=>$tipoRespPreg, 'resps'=>$resps, 'tmp'=>$sqlGetRespTmp);
    }
    $paginador = '<div class="table-pagination text-center">'.paginate($reload, $page, $totalPages, $adjacents).'</div>';

    echo json_encode(array("error"=>0, "dataPregs"=>$pregs, 'pags'=>$paginador, 'sql'=>$sql));
?>