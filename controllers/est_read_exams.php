<?php

    include('../config/conexion.php');
    include('../config/variables.php');
    $estExam = array();
    $msgErr = '';
    $ban = true;
    $idEstudiante = $_GET['idUser'];

    $sqlGetExaInfoAsig = "SELECT $tExaInfAsigAlum.id as idAsigAlum, $tExaInfAsig.id as idAsig,  "
            . "$tExaInfAsig.exa_info_id as idExam, $tExaInfAsig.nombre as nombreExa, "
            . "DATE_FORMAT($tExaInfAsig.inicio, '%Y-%m-%d') as inicio, DATE_FORMAT($tExaInfAsig.fin, '%Y-%m-%d') as fin, "
            . "DATE_FORMAT($tExaInfAsig.inicio, '%H:%i') as inicioT, DATE_FORMAT($tExaInfAsig.fin, '%H:%i') as finT, "
            . "$tBMat.nombre as nombreMat, $tProf.nombre as nombreProf, "
            . "(SELECT count(*) FROM $tExaPregs WHERE exa_info_id=$tExaInfAsig.exa_info_id ) as numPregs "
            . "FROM $tExaInfAsigAlum "
            . "INNER JOIN $tExaInfAsig ON $tExaInfAsig.id=$tExaInfAsigAlum.exa_info_asig_id "
            . "INNER JOIN $tGMatProfs ON $tGMatProfs.id=$tExaInfAsig.grupo_materia_profesor_id "
            . "INNER JOIN $tBMat ON $tBMat.id=$tGMatProfs.banco_materia_id "
            . "INNER JOIN $tProf ON $tProf.id=$tGMatProfs.usuario_profesor_id "
            . "WHERE $tExaInfAsigAlum.alumno_id='$idEstudiante' ";
    //echo $sqlGetExaInfoAsig;
    $resGetExaInfoAsig = $con->query($sqlGetExaInfoAsig);
    if($resGetExaInfoAsig->num_rows > 0){
        while($rowGetExaInfoAsig = $resGetExaInfoAsig->fetch_assoc()){
            $idAsigAlum = $rowGetExaInfoAsig['idAsigAlum'];
            $idAsig = $rowGetExaInfoAsig['idAsig'];
            $idExa = $rowGetExaInfoAsig['idExam'];
            $nameExa = $rowGetExaInfoAsig['nombreExa'];
            $begin = $rowGetExaInfoAsig['inicio'];
            $end = $rowGetExaInfoAsig['fin'];
            $beginT = $rowGetExaInfoAsig['inicioT'];
            $endT = $rowGetExaInfoAsig['finT'];
            $nameMat = $rowGetExaInfoAsig['nombreMat'];
            $nameProf = $rowGetExaInfoAsig['nombreProf'];
            $numPregs = $rowGetExaInfoAsig['numPregs'];
            
            //Checamos fecha para comparar si aún esta disponible el examen
            $inicio = new DateTime($rowGetExaInfoAsig['inicio']);
            $fin = new DateTime($rowGetExaInfoAsig['fin']);
            $dateNowTmp = new DateTime($dateNow);
            $interval1 = $dateNowTmp->diff($inicio);
            $interval2 = $dateNowTmp->diff($fin);
            $tmp = $interval1->format('%R%a dias').'--'.$interval2->format('%R%a dias');
            $timeB = strtotime($rowGetExaInfoAsig['inicioT']);
            $timeE = strtotime($rowGetExaInfoAsig['finT']);
            $timeN = strtotime($timeNow);
            $tmp .= 'Time: '.$timeB.'**'.$timeE.'**'.$timeN;
            /*if( $interval1->format('%R%a dias') > 0){
                $disponible = 0; //aun no
            }else if($interval1->format('%R%a dias') < 0 && $interval2->format('%R%a dias') <= 0){
                $disponible = 1; //se paso
            }else{
                if($timeN >= $timeB && $timeN <= $timeE)
                    $disponible = 2; //ya!
                else
                    $disponible = 0;
            }*/
            //$disponible = ( ($interval1->format('%R%a dias') <= 0 && $interval2->format('%R%a dias') >= 0) 
              //&& ($timeB < $timeN && $timeN < $timeE)) ? true : false;
            if( ($interval1->format('%R%a dias') <= 0 && $interval2->format('%R%a dias') >= 0)  ){
                if($interval1->format('%R%a dias') == 0 && $timeB > $timeN)
                    $disponible = false;
                else if($interval2->format('%R%a dias') == 0 && $timeN > $timeE)
                    $disponible = false;
                else
                    $disponible = true;
            }else{
                $disponible = false;
            }
            //Buscamos calificación
            $sqlGetExaResult = "SELECT calificacion FROM $tExaResultInfo "
                    . "WHERE exa_info_id='$idExa' AND exa_info_asig_alum_id='$idAsigAlum' AND alumno_id='$idEstudiante' ";
            $resGetExaResult = $con->query($sqlGetExaResult);
            if($resGetExaResult->num_rows > 0){
                $rowGetExaResult=$resGetExaResult->fetch_assoc();
                $calif = $rowGetExaResult['calificacion'];
            }else{
                $calif = null;
            }
            
            $estExam[] = array('idExa'=>$idExa, 'nombre'=>$nameExa, 'inicio'=>$begin, 
                'fin'=>$end, 'inicioT'=>$beginT, 'finT'=>$endT, 'materia'=>$nameMat, 
                'prof'=>$nameProf, 'numPregs'=>$numPregs, 'idAsig'=>$idAsig, 'idAsigAlum'=>$idAsigAlum,
                'disp'=>$disponible, 'calif'=>$calif, 'tmp'=>$tmp);
        }
    }else{
        $ban = false;
        $msgErr .= 'No tienes examenes asignados';
    }  
    
    if($ban){
        echo json_encode(array("error"=>0, "dataRes"=>$estExam));
        //echo json_encode(array("error"=>0, "dataRes"=>"Holi"));
    }else{
        echo json_encode(array("error"=>1, "msgErr"=>$msgErr));
    }

?>