<?php
    include ('../config/conexion.php');
    include('../config/variables.php');
    
    $idUser = $_GET['idUser'];

    $msgErr = '';
    $ban = true;
    $arrPregs = array();
    
    $sqlGetPregs = "SELECT * FROM $tBPregs WHERE perfil_creador='2' AND creado_por_id='$idUser' AND activo='1' ";

    //Ordenar ASC y DESC
    $vorder = (isset($_POST['orderby'])) ? $_POST['orderby'] : "";
    if($vorder != ''){
        $sqlGetPregs .= " ORDER BY ".$vorder;
    }
    //echo $sqlGetPregs;
    $resGetPregs = $con->query($sqlGetPregs);
    if($resGetPregs->num_rows > 0){
        while($rowGetPregs = $resGetPregs->fetch_assoc()){
            $id = $rowGetPregs['id'];
            $nombre = $rowGetPregs['nombre'];
            $valorPreg = $rowGetPregs['valor_preg'];
            $tipoResp = $rowGetPregs['tipo_resp'];
            $compartir = $rowGetPregs['compartir'];
            $arrPregs[] = array('id'=>$id, 'nombre'=>$nombre, 'valorPreg'=>$valorPreg, 
                'tipoResp'=>$tipoResp);
        }
    }else{
        $ban = false;
        $msgErr .= 'No existen preguntas.';
    }
    
    if($ban){
        echo json_encode(array("error"=>0, "dataRes"=>$arrPregs));
    }else{
        echo json_encode(array("error"=>1, "msgErr"=>$msgErr));
    }
?>