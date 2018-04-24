<?php
    include ('../config/conexion.php');
    include ('../config/variables.php');
    
    $idExam = $_GET['idExam'];
    $sqlGetExaInfo = "SELECT $tExaInf.nombre as nombreExa, $tBMat.nombre as nombreMat "
            . "FROM $tExaInf INNER JOIN $tBMat ON $tBMat.id=$tExaInf.banco_materia_id "
            . "WHERE $tExaInf.id='$idExam' ";
    $resGetExaInfo = $con->query($sqlGetExaInfo);
    $rowGetExaInfo = $resGetExaInfo->fetch_assoc();
    
    //echo $idExam;
    
    
    require ('../classes/fpdf/fpdf.php');
    class PDF extends FPDF{
        function Footer(){
            $this->SetY(-15);
            $this->SetFont('Arial','I',9);
            $this->Cell(0,10,'Examen generado en la plataforma EVA, desarrollado por Softlutions | http://softlutions.biz','T',0,'C');
        }

        function Header(){ }
    }//Fin class PDF
    $pdf = new PDF();
    $pdf->AddPage('P', 'Letter');
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(40,7,utf8_decode("Nombre del Examen:"),1,0,'L');
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(80,7,utf8_decode($rowGetExaInfo['nombreExa']),1,0,'L');
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(30,7,utf8_decode("Materia:"),1,0,'L');
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(50,7,utf8_decode($rowGetExaInfo['nombreMat']),1,1,'L');
    $pdf->Ln(7);
    //$pdf->Output();
    /*$countPregs = 0;
    foreach($pregs as $info){
        $countPregs++;
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(50,7,utf8_decode($countPregs),1,1,'L');
    }*/
    $pregs = array();
    $resps = array();
    $sqlGetIdPregs = "SELECT banco_pregunta_id FROM $tExaPregs WHERE exa_info_id='$idExam' ";
    $resGetIdPregs = $con->query($sqlGetIdPregs);
    $countPregs = 0;
    while($rowGetIdPreg = $resGetIdPregs->fetch_assoc()){
        $countPregs++;
        $idPregExam = $rowGetIdPreg['banco_pregunta_id'];
        $sqlGetInfo = "SELECT id, nombre, archivo, tipo_resp FROM $tBPregs WHERE id='$idPregExam' ";
        $resGetInfo = $con->query($sqlGetInfo);
        while($rowGetInfo = $resGetInfo->fetch_assoc()){
            $idPreg = $rowGetInfo['id'];
            $nombrePreg = $rowGetInfo['nombre'];
            //echo $countPregs.'.-'.$nombrePreg.'<br>';
            $archivoPreg = $rowGetInfo['archivo'];
            $tipoRespPreg = $rowGetInfo['tipo_resp'];
            $pdf->SetFont('Arial','B',10);
            $pdf->MultiCell(200,7,utf8_decode($countPregs.".- ".$nombrePreg),1,'L',0);
            if($archivoPreg != null){
                $extArchPreg = explode(".",$archivoPreg);
                if($extArchPreg[1] == "mp3"){
                    $pdf->Cell(200,7,"Archivo de audio",1,1,'C');
                }else{
                    //$pdf->Cell(200,7,'',1,1,'C',$pdf->Image('../'.$filesExams.'/'.$archivoPreg, null, null, 50));
                    $pdf->Cell(200,50,$pdf->Image('../'.$filesExams.'/'.$archivoPreg, $pdf->GetX()+75, $pdf->GetY()+1, 50), 1, 1, 'C', false);
                }
            } 
            $sqlGetResp = "SELECT id, nombre, archivo, correcta, tipo_resp, palabras FROM $tBResp WHERE banco_pregunta_id='$idPreg' ";
            $resGetResp = $con->query($sqlGetResp);
            while($rowGetResp = $resGetResp->fetch_assoc()){
                $idResp = $rowGetResp['id'];
                $nombreResp = $rowGetResp['nombre'];
                $archivoResp = $rowGetResp['archivo'];
                $respCorr = $rowGetResp['correcta'];
                $tipoRespResp = $rowGetResp['tipo_resp'];
                $palabrasResp = $rowGetResp['palabras'];
                $pdf->SetFont('Arial','I',9);
                if($tipoRespResp == 1){
                    //echo 'R='.$respCorr.'.-'.$tipoRespResp.'++'.$nombreResp.'<br>';
                    if($archivoResp != null){
                        $pdf->Cell(200,50,$pdf->Image('../'.$filesExams.'/'.$archivoResp, $pdf->GetX()+75, $pdf->GetY()+1, 50), 1, 1, 'C', false);
                    }
                    if($respCorr == 1){
                        $pdf->Cell(10,7,$pdf->Image('../assets/obj/selected-radiobutton-128.png', $pdf->GetX()+2, $pdf->GetY()+1, 5), 1, 0, 'C', false);
                        $pdf->Cell(190,7,utf8_decode($nombreResp),1,1,'L');
                    }else{
                        $pdf->Cell(10,7,$pdf->Image('../assets/obj/empty-radiobutton-128.png', $pdf->GetX()+2, $pdf->GetY()+1, 5), 1, 0, 'C', false);
                        $pdf->Cell(190,7,utf8_decode($nombreResp),1,1,'L');
                    }
                }else if($tipoRespResp == 2){
                    if($archivoResp != null){
                        $pdf->Cell(200,50,$pdf->Image('../'.$filesExams.'/'.$archivoResp, $pdf->GetX()+75, $pdf->GetY()+1, 50), 1, 1, 'C', false);
                    }
                    if($respCorr == 1){
                        $pdf->Cell(10,7,$pdf->Image('../assets/obj/check-box-outline-128.png', $pdf->GetX()+2, $pdf->GetY()+1, 5), 1, 0, 'C', false);
                        $pdf->Cell(190,7,utf8_decode($nombreResp),1,1,'L');
                    }else{
                        $pdf->Cell(10,7,$pdf->Image('../assets/obj/unchecked_checkbox.png', $pdf->GetX()+2, $pdf->GetY()+1, 5), 1, 0, 'C', false);
                        $pdf->Cell(190,7,utf8_decode($nombreResp),1,1,'L');
                    }
                }else if($tipoRespResp == 4){
                    $pdf->Cell(30,7,utf8_decode("Respuesta exacta: "),1,0,'L');
                    $pdf->Cell(170,7,utf8_decode($palabrasResp),1,1,'L');
                }else if($tipoRespResp == 3){
                    $pdf->Cell(80,7,utf8_decode("La respuesta debe de contener las siguientes palabras: "),1,0,'L');
                    $pdf->Cell(120,7,utf8_decode($palabrasResp),1,1,'L');
                }
                else{
                    //echo 'R='.$tipoRespResp.'++'.$palabrasResp.'<br>';
                }
                $resps[] = array('id'=>$idResp, 'nombre'=>$nombreResp, 'respCorr'=>$respCorr, 'archivo'=>$archivoResp, 'tipoR'=>$tipoRespResp, 'palabra'=>$palabrasResp);
            }
            //echo '<br>';
            $pregs[] = array('id'=>$idPreg, 'nombre'=>$nombrePreg, 'archivo'=>$archivoPreg, 'tipoR'=>$tipoRespPreg, 'resps'=>$resps);
        }
        $pdf->Ln(7);
    }//end while idPregs
    
    $pdf->Output();
    //print_r($pregs);
    //print_r($resps);
    
?>
