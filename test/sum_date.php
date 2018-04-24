<?php

date_default_timezone_set('America/Mexico_City');
$fecha = date('Y-m-d H:i');
$nuevafecha = strtotime ( '+7 day' , strtotime ( $fecha ) ) ;
$nuevafecha = date ( 'Y-m-d H:i' , $nuevafecha );
 
echo $nuevafecha;

?>