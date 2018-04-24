<?php
    session_start();
    include('../config/conexion.php');
    include('../config/variables.php');
    
    include('pagination.php');
    $idExam = $_GET['idExam'];
    $idExamAsig = $_GET['idExamAsig'];
    $idExamAsigAlum = $_GET['idExamAsigAlum'];
    $idUser = $_GET['idUser'];
    $pregs = array();
    $resps = array();
    $respAl = array();
    
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
    $reload = '../views/est_read_exam_details.php?idExam='.$idExam.'&idExamAsig='.$idExamAsig.'$idUser='.$idUser.'&idExamAsigAlum='.$idExamAsigAlum;
    
    //aleatorio
    //$idPregSess = $_SESSION['exaRand'][$page-1];
    $sqlGetIdPregs = "SELECT banco_pregunta_id FROM $tExaPregs WHERE exa_info_id='$idExam' LIMIT $offset, $per_page";
    $resGetIdPregs = $con->query($sqlGetIdPregs);
    while($rowGetIdPregs = $resGetIdPregs->fetch_assoc()){
        $idPregExa = $rowGetIdPregs['banco_pregunta_id'];
        //echo $idPregSess; 
        $sqlGetInfo = "SELECT id, nombre, archivo, tipo_resp, valor_preg "
                . "FROM $tBPregs WHERE id='$idPregExa' ";
        //print_r($_SESSION['exaRand']);
        $resGetInfo = $con->query($sqlGetInfo);
        while($rowGetInfo = $resGetInfo->fetch_assoc()){
            $idPreg = $rowGetInfo['id'];
            $nombrePreg = $rowGetInfo['nombre'];
            $archivoPreg = $rowGetInfo['archivo'];
            $tipoRespPreg = $rowGetInfo['tipo_resp'];
            $valorPreg = $rowGetInfo['valor_preg'];
            $sqlGetRespEst = "SELECT *, (SELECT nombre FROM $tBResp WHERE id=$tExaResultPregs.respuesta) as nombreResp "
                    . "FROM $tExaResultPregs "
                    . "WHERE exa_info_id='$idExam' AND exa_info_asig_alum_id='$idExamAsigAlum' "
                    . "AND alumno_id='$idUser' AND pregunta_id='$idPreg'  ";
            //echo $sqlGetRespEst;
            $resGetRespEst = $con->query($sqlGetRespEst);
            if($resGetRespEst->num_rows > 0){
                $rowGetRespEst = $resGetRespEst->fetch_assoc();
                $idResp = $rowGetRespEst['id'];
                $tipoResp = $rowGetRespEst['tipo_resp_id'];
                $respId = $rowGetRespEst['respuesta_id'];
                $resp = $rowGetRespEst['respuesta'];
                $respName = $rowGetRespEst['nombreResp'];
                $calif = $rowGetRespEst['calificacion'];
                $resps[] = array('id'=>$idResp,'tipoR'=>$tipoResp,'respId'=>$respId,'resp'=>$resp,'nombreResp'=>$respName,'calif'=>$calif);
            }else{
                $calif = 0;
            }
            $pregs[] = array('id'=>$idPreg, 'nombre'=>$nombrePreg, 'archivo'=>$archivoPreg, 'tipoR'=>$tipoRespPreg, 'valorPreg'=>$valorPreg, 'resps'=>$resps, 'calif'=>$calif);
        }
    }//end while
    $paginador = '</table><div class="table-pagination text-center">'.paginate($reload, $page, $totalPages, $adjacents).'</div>';
    
    echo json_encode(array("error"=>0, "dataPregs"=>$pregs, 'pags'=>$paginador, 'sql'=>$sqlGetInfo));
?>