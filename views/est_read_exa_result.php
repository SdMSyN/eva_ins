<?php
    include ('header.php');
    include('../config/variables.php');
    include('../config/conexion.php');
?>

<title><?=$tit;?></title>
<meta name="author" content="Luigi Pérez Calzada (GianBros)" />
<meta name="description" content="Descripción de la página" />
<meta name="keywords" content="etiqueta1, etiqueta2, etiqueta3" />
</head>
    <body onload="notBack()">
<?php
    include ('navbar.php');
    if (!isset($_SESSION['sessU'])){
        echo '<div class="row"><div class="col-sm-12 text-center"><h2>No tienes permiso para entrar a esta sección. ━━[○･｀Д´･○]━━ </h2></div></div>';
    }else if($_SESSION['perfil'] != 3){
        echo '<div class="row"><div class="col-sm-12 text-center"><h2>¿Estás tratando de acceder? No es tu perfil o(´^｀)o </h2></div></div>';
    }
    else {
        $idPerfil = $_SESSION['perfil'];
        $idUser = $_SESSION['userId'];
        $idExam = $_GET['idExam'];
        $idExamAsig = $_GET['idExamAsig'];
        $idExamAsigAlum = $_GET['idExamAsigAlum'];
        
        //Obtenemos fecha de finalización del examen
        $sqlGetFechaFin = "SELECT mostrar_resultado FROM $tExaInfAsig WHERE id='$idExamAsig' ";
        $resGetFechaFin = $con->query($sqlGetFechaFin);
        $rowGetFechaFin = $resGetFechaFin->fetch_assoc();
        $fechaFin = strtotime($rowGetFechaFin['mostrar_resultado']);
        $buttonViewDetailsResult = '';
        $hoy = strtotime($dateNow.' '.$timeNow);
        echo $fechaFin."--".$hoy;
        if($hoy > $fechaFin || $fechaFin == NULL){
            $buttonViewDetailsResult = '<a href="est_read_exam_details.php?idExam='.$idExam.'&idExamAsig='.$idExamAsig.'&idUser='.$idUser.'&idExamAsigAlum='.$idExamAsigAlum.'" '
                    . 'class="btn btn-success">Comprobar examen</a>';
        }else{
            $buttonViewDetailsResult = '<a href="#" '
                    . 'class="btn btn-success" disabled>Comprobar examen</a>';
        }
        
?>

    <div class="container">
        <div class="row text-center">
            <h1>Resultado de tu examen</h1>
        </div>
        
        <div class="row">
            <div class="col-sm-6" id="resultLeft">
                <table class="table table-striped text-right"> 
                    <tr><td>Número de preguntas</td></tr>
                    <tr><td>Preguntas contestadas</td></tr>
                    <tr><td>Preguntas no contestadas</td></tr>
                    <tr><td>Respuestas correctas</td></tr>
                    <tr><td>Respuestas incorrectas</td></tr>
                    <tr><td>Valor del examen</td></tr>
                    <tr><td>Valor obtenido</td></tr>
                    <tr><td>Porcentaje</td></tr>
                    <tr><td>Calificación Final</td></tr>
                    <tr><td>(Hora inicio - Hora fin)</td></tr>
                </table>
            </div>
            <div class="col-sm-6" id="resultRight">
                <table class="table table-striped text-left" id="resultRightTable"></table>
            </div>
        </div>
        <div class="row">
            <?= $buttonViewDetailsResult; ?>
        </div>
    </div>

    <script type="text/javascript">
        function notBack(){
            window.location.hash="no-back-button";
            window.location.hash="Again-No-back-button" //chrome
            window.onhashchange=function(){window.location.hash="no-back-button";}
        }
    </script>
    
    <script language="JavaScript" type="text/javascript">
         $('#loading').hide();
        var ordenar = '';
        $(document).ready(function(){
            $.ajax({
                method: "POST",
                url: "../controllers/est_read_exa_result.php?idUser=<?=$idUser;?>&idExam=<?=$idExam;?>&idExamAsig=<?=$idExamAsig;?>&idExamAsigAlum=<?=$idExamAsigAlum;?>",
                success: function(data){
                   //alert(data);
                   console.log(data);
                   var msg = jQuery.parseJSON(data);
                   if(msg.error == 0){
                        var newRow = '';
                            newRow += '<tr><td>'+msg.dataRes[0].numPregs+'</td></tr>';
                            newRow += '<tr><td>'+msg.dataRes[0].numPregsResp+'</td></tr>';
                            newRow += '<tr><td>'+msg.dataRes[0].numPregsNoResp+'<span class="glyphicon glyphicon-ban-circle"></span></td></tr>';
                            newRow += '<tr><td>'+msg.dataRes[0].numCorr+' <span class="glyphicon glyphicon-ok-circle"></span></td></tr>';
                            newRow += '<tr><td>'+msg.dataRes[0].numInco+' <span class="glyphicon glyphicon-remove-circle"></span></td></tr>';
                            newRow += '<tr><td>'+msg.dataRes[0].valorExa+'</td></tr>';
                            newRow += '<tr><td>'+msg.dataRes[0].valorAlum+'</td></tr>';
                            newRow += '<tr><td>'+msg.dataRes[0].porc+' <b>%</b></td></tr>';
                            newRow += '<tr><td>'+msg.dataRes[0].calif+'</td></tr>';
                            newRow += '<tr><td>('+msg.dataRes[0].hi+' - '+msg.dataRes[0].hf+')</td></tr>';
                        $("#resultRight #resultRightTable").html(newRow);
                   }else{
                       var newRow = '<tr><td></td><td>'+msg.msgErr+'</td></tr>';
                        $("#resultRight #resultRightTable").html(newRow);
                   }
                }
            })
        });
    </script>
    
<?php
    }//end if-else
    include ('footer.php');
?>