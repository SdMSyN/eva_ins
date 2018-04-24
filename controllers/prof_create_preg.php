<?php

    include('../config/conexion.php');
    include('../config/variables.php');
    
    ///Inputs ocultos
    $idMateria = $_POST['idMateria'];
    //$idBloque = $_POST['idBloque'];
    //$idTema = $_POST['idTema'];
    //$idSubtema = $_POST['idSubtema'];
    $idUser = $_POST['idProf'];
    $idPerfil = $_POST['idPerfil'];
    $idExam = $_POST['idExam'];
    
    //Información de la pregunta
    $preg1 = addslashes($_POST['inputPreg']);
    $filePreg1 = (isset($_FILES['files'])) ? $_FILES['files']['name'] : null;//imagen o audio opcional
    $compartir = $_POST['inputCompartir'];
    $valorPreg = $_POST['inputValor'];
    $typeResp = $_POST['respType'];
    $ban = true; $banImg = true; $msgErr = '';
    $respPreg1 = array();
    $respFilePreg1 = array();
    $respWordsPreg1 = array();
    $respCorrsPreg1 = array();
    
    
    $permitidos = array("image/jpg", "image/jpeg", "image/gif", "image/png", "audio/mp3", "audio/mpeg");
    $limite_kb = 2048;
    //Obtenemos las respuestas de la pregunta principal
    if($typeResp == 1){//opcion multiple
        if(isset($_POST['input1Radio'])){
            $respCorr1 = $_POST['input1Radio'][0];
            $countRespPreg1 = count($_POST['input1Resp']);
            for($i = 0; $i<$countRespPreg1; $i++){
                $respPreg1[] = addslashes($_POST['input1Resp'][$i]);
                $respFilePreg1[] = (isset($_FILES['input1File'])) ? $_FILES['input1File']['name'][$i] : null;//imagen resp opcional
                $respWordsPreg1[] = null;
                $respCorrsPreg1[] = ( ($i+1) == $respCorr1) ? "1" : null; 
            }
        }else $banImg = false;
    }else if($typeResp == 2){//multirespuesta
        $countRespPreg1 = count($_POST['input2Resp']);
        if($countRespPreg1 < 1 ) $ban = false;
        for($i = 0; $i<$countRespPreg1; $i++){
            $respPreg1[] = addslashes($_POST['input2Resp'][$i]);
            $respFilePreg1[] = (isset($_FILES['input2File'])) ? $_FILES['input2File']['name'][$i] : null;//imagen resp opcional
            $respWordsPreg1[] = null;
        }
        $banCheck = false;
        if(isset($_POST['input2Check'])){
            for($j = 0; $j < $countRespPreg1; $j++){
                for($k = 0; $k < count($_POST['input2Check']); $k++){
                    if(($j + 1) == $_POST['input2Check'][$k]){ $banCheck = true; break;}
                    else{ $banCheck = false; continue;}
                }
                $respCorrsPreg1[] = ($banCheck) ? "1" : null;
            }
        }else $banImg=false;
    }else if($typeResp == 3){//respuesta abierta
        $respPreg1[] = null;
        $respFilePreg1[] = null;
        $respCorrsPreg1[] = null;
        $respWordsPreg1[] = addslashes($_POST['inputResp']);
        if($_POST['inputResp'] == null || $_POST['inputResp'] == "" ) $banImg = false;
    }else if($typeResp == 4){//respuesta exacta
        $respPreg1[] = null;
        $respFilePreg1[] = null;
        $respCorrsPreg1[] = null;
        $respWordsPreg1[] = addslashes($_POST['inputResp']);
        if($_POST['inputResp'] == null || $_POST['inputResp'] == "" ) $banImg = false;
    }
    /*print_r($respPreg1); echo 'Archivos'; print_r($respFilePreg1); echo 'Palabras'; 
    print_r($respWordsPreg1); echo 'Radio Buttons'; print_r($respCorrsPreg1);*/
    if($filePreg1 != null){//validamos si hay imagen en el archivo de pregunta
        if($_FILES['files']['error'] > 0){
            $msgErr .= 'Ha ocurrido un error al procesar archivo.<br>'.$_FILES['files']['error'];
            $ban = false;
        }else{
            if(in_array($_FILES['files']['type'], $permitidos)){
                if($_FILES['files']['size'] <= $limite_kb * 1024){
                    $ban = true;
                }else{
                    $msgErr .= 'Tamaño de archivo excede el límite de 2MB. Archivo pregunta';
                    $ban = false;
                }
            }else{
                $msgErr .= 'Formato de archivo no valido. Archivo pregunta';
                $ban = false;
            }
        }
    }
    
    if($banImg && ( ($typeResp == 1 && $respFilePreg1[0] != null) || ($respFilePreg1[0] != null && $typeResp == 2 ) ) ){
        //Validamos las imágenes
        if($typeResp == 1){
            $countFilesResp1 = count($respFilePreg1);
            for($i = 0; $i < $countFilesResp1; $i++){
                if(in_array($_FILES['input1File']['type'][$i], $permitidos)){
                    if($_FILES['input1File']['size'][$i] <= $limite_kb * 1024){
                        $ban = true;
                    }else{
                        $msgErr .= 'El archivo excede el límite para las respuestas 2MB.'.($i+1);
                        $ban = false;
                        break;
                    }
                }else{
                    $msgErr .= 'Formato de archivo no valido para las respuestas.'.($i+1);
                    $ban = false;
                    break;
                }
            }
        }else if($typeResp == 2){
            $countFilesResp1 = count($respFilePreg1);
            for($i = 0; $i < $countFilesResp1; $i++){
                if(in_array($_FILES['input2File']['type'][$i], $permitidos)){
                    if($_FILES['input2File']['size'][$i] <= $limite_kb * 1024){
                        $ban = true;
                    }else{
                        $msgErr .= 'El archivo excede el límite para las respuestas 1MB.'.($i+1);
                        $ban = false;
                        break;
                    }
                }else{
                    $msgErr .= 'Formato de archivo no valido para las respuestas.'.($i+1);
                    $ban = false;
                    break;
                }
            }
        }    
    }else{
        $msgErr .= 'No se admiten campos vacios.';
    }
    
    if($ban){
        //Obtenemos la llave y 
        //Si es correcto empezamos a mover las imagenes
        //Si movemos bien las imagenes empezamos a insertar en la base de datos
        $sqlGetKey = "SELECT clave FROM $tProf WHERE id='$idUser' ";
        $resGetKey = $con->query($sqlGetKey);
        $rowGetKey = $resGetKey->fetch_assoc();
        $key = $rowGetKey['clave'];
        $sqlGetNumPregs = "SELECT * FROM $tBPregs ";
        $resGetNumPregs = $con->query($sqlGetNumPregs);
        $countNumPregs = $resGetNumPregs->num_rows;
        $keyPregExam = $key.'_idPreg_'.($countNumPregs+1);
        //echo '--'.$keyPregExam;
        if($filePreg1 != null){//si existe la imagen obtenemos la extensión y la guardamos
            $extPreg1 = explode(".", $_FILES['files']['name']);
            $nameFile1 = $keyPregExam.".".$extPreg1[1];
            $ruta1 = "../".$filesExams."/".$nameFile1;
            $move1 = @move_uploaded_file($_FILES['files']['tmp_name'], $ruta1);
            $sqlInsertPreg = "INSERT INTO $tBPregs "
                    . "(nombre, archivo, valor_preg, tipo_resp, "
                    . "banco_materia_id, "
                    . "creado_por_id, perfil_creador, compartir, activo, creado, actualizado) "
                    . "VALUES "
                    . "('$preg1', '$nameFile1', '$valorPreg', '$typeResp', "
                    . "'$idMateria', "
                    . "'$idUser', '$idPerfil', '$compartir', '1', '$dateNow', '$dateNow')";
        }else{ //si no hay imagen
            $sqlInsertPreg = "INSERT INTO $tBPregs "
                    . "(nombre, valor_preg, tipo_resp, "
                    . "banco_materia_id, "
                    . "creado_por_id, perfil_creador, compartir, activo, creado, actualizado) "
                    . "VALUES "
                    . "('$preg1', '$valorPreg', '$typeResp', "
                    . "'$idMateria', "
                    . "'$idUser', '$idPerfil', '$compartir', '1', '$dateNow', '$dateNow')";
        }
        if($con->query($sqlInsertPreg) === TRUE){
            $idPreg = $con->insert_id;
            $sqlInsertExaPregInfo = "INSERT INTO $tExaPregs (banco_pregunta_id, exa_info_id, creado) "
                    . "VALUES ('$idPreg', '$idExam', '$dateNow')";
            if($con->query($sqlInsertExaPregInfo) === TRUE){
                //Insertamos las respuestas según el tipo de respuesta
                for($m = 0; $m < count($respPreg1); $m++){
                    if($respFilePreg1[$m] != null){//hay imagen
                        $extPreg2 = ($typeResp == 1) ? explode(".", $_FILES['input1File']['name'][$m]) : explode(".", $_FILES['input2File']['name'][$m]);
                        $nameFile2 = $keyPregExam."_resp_".$m.".".$extPreg2[1];
                        $ruta2 = "../".$filesExams."/".$nameFile2;
                        $move2 = ($typeResp == 1) ? @move_uploaded_file($_FILES['input1File']['tmp_name'][$m], $ruta2) : @move_uploaded_file($_FILES['input2File']['tmp_name'][$m], $ruta2);
                        $sqlInsertResp = "INSERT INTO $tBResp "
                            . "(nombre, archivo, correcta, tipo_resp, banco_pregunta_id, creado, actualizado) "
                            . "VALUES "
                            . "('$respPreg1[$m]', '$nameFile2', '$respCorrsPreg1[$m]', '$typeResp', '$idPreg', '$dateNow', '$dateNow')";
                    }else{//no hay imagen
                        $sqlInsertResp = "INSERT INTO $tBResp "
                            . "(nombre, correcta, tipo_resp, palabras, banco_pregunta_id, creado, actualizado) "
                            . "VALUES "
                            . "('$respPreg1[$m]', '$respCorrsPreg1[$m]', '$typeResp', '$respWordsPreg1[$m]', '$idPreg', '$dateNow', '$dateNow')";
                    }
                    if($con->query($sqlInsertResp) === TRUE){
                        $ban = true;
                    }else{
                        $ban = false;
                        $msgErr .= 'Error al insertar respuesta.<br>'.$con->error;
                        break;
                    }
                }//end for
            }else{
                $ban = false;
                $msgErr .= 'Error al insertar información de la pregunta.'.$con->error;
            }
        }else{
            $ban = false;
            $msgErr .= 'Error al guardar pregunta.<br>'.$con->error;
        }
        
    }
    
    if($ban){
        $cad = 'Se añadio con éxito la pregunta con sus respuestas';
        echo json_encode(array("error"=>0, "msgErr"=>$cad));
    }else{
        echo json_encode(array("error"=>1, "msgErr"=>$msgErr));
    }
    
?>
