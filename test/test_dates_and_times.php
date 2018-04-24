<?php

    include('../config/conexion.php');
    include('../config/variables.php');
    
    $sqlGetDate = "SELECT "
            . "DATE_FORMAT($tExaInfAsig.inicio, '%Y-%m-%d') as inicio, "
            . "DATE_FORMAT($tExaInfAsig.fin, '%Y-%m-%d') as fin, "
            . "DATE_FORMAT($tExaInfAsig.inicio, '%H:%i') as inicioT, "
            . "DATE_FORMAT($tExaInfAsig.fin, '%H:%i') as finT, "
            . "TIMESTAMPDIFF(day, NOW(), $tExaInfAsig.inicio) as diferenciaB, "
            . "TIMESTAMPDIFF(day, NOW(), $tExaInfAsig.fin) as diferenciaE, "
            . "NOW() as hoy "
            . "FROM $tExaInfAsig ";
    $resGetExaInfoAsig = $con->query($sqlGetDate);
    if($resGetExaInfoAsig->num_rows > 0){
        while($rowGetExaInfoAsig = $resGetExaInfoAsig->fetch_assoc()){
            $begin = $rowGetExaInfoAsig['inicio'];
            $beginT = $rowGetExaInfoAsig['inicioT'];
            $end = $rowGetExaInfoAsig['fin'];
            $endT = $rowGetExaInfoAsig['finT'];
            $diffB = $rowGetExaInfoAsig['diferenciaB'];
            $diffE = $rowGetExaInfoAsig['diferenciaE'];
            $hoy = $rowGetExaInfoAsig['hoy'];
            $timeB = strtotime($beginT);
            $timeE = strtotime($endT);
            $timeN = strtotime($timeNow);
            //echo $begin."--".$end."++".$beginT." a ".$endT."--".$diffB."--".$diffE."<br>";
            echo $beginT."--".$endT."--".$timeNow."<br>";
            echo $timeB."--".$timeE."--".$timeN."--";
            //if($timeN >= )
            echo ($timeB <= $timeN && $timeN <= $timeE)? "Dentro" : "Fuera";
            echo "<br>";
        }
    }

?>