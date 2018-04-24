<?php
    include ('header.php');
    include('../config/variables.php');
?>

<title><?=$tit;?></title>
<meta name="author" content="Luigi Pérez Calzada (GianBros)" />
<meta name="description" content="Descripción de la página" />
<meta name="keywords" content="etiqueta1, etiqueta2, etiqueta3" />
</head>
    <body>
<?php
    include ('navbar.php');
    if (!isset($_SESSION['sessU'])){
        echo '<div class="row"><div class="col-sm-12 text-center"><h2>No tienes permiso para entrar a esta sección. ━━[○･｀Д´･○]━━ </h2></div></div>';
    }else if($_SESSION['perfil'] != 2){
        echo '<div class="row"><div class="col-sm-12 text-center"><h2>¿Estás tratando de acceder? No es tu perfil o(´^｀)o </h2></div></div>';
    }
    else {
        $idPerfil = $_SESSION['perfil'];
        $idUser = $_SESSION['userId'];
        $idAv = $_GET['idAv'];
?>

    <div class="container">
        <div class="row">
            <div id="loading">
                <img src="../assets/obj/loading.gif" height="300" width="400">
            </div>
        </div>
        <br>
        <div class="panel panel-default">
            <div class="panel-heading">Detalles de notificación</div>
            <div class="panel-body">
                <div>
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                      <li role="presentation" class="active"><a href="#alumnos" aria-controls="por_leer" role="tab" data-toggle="tab">Alumnos</a></li>
                      <li role="presentation"><a href="#tutores" aria-controls="leidos" role="tab" data-toggle="tab">Tutores</a></li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="alumnos">
                            <div class="table-responsive">
                                <table class="table table-hover" id="dataAlum">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nombre Alumno</th>
                                            <th>Enterado</th>
                                            <th>Fecha de enterado</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="tutores">
                            <div class="table-responsive">
                                <table class="table table-hover" id="dataTut">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nombre Tutor</th>
                                            <th>Enterado</th>
                                            <th>Fecha de enterado</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>

    <script type="text/javascript">
        $('#loading').hide();
        $(document).ready(function(){
            
            //Leemos las notificaciones no leídas
            $.ajax({
                type: "POST",
                url: "../controllers/read_notificaciones_alumnos_detalles.php?idAv="+<?=$idAv;?>,
                success: function(msg){
                    console.log(msg);
                    var msg = jQuery.parseJSON(msg);
                    if(msg.error == 0){
                        $("#dataAlum tbody").html("");
                        $.each(msg.dataRes, function(i, item){
                            var newRow = '<tr>'
                                +'<td>'+msg.dataRes[i].id+'</td>'
                                +'<td>'+msg.dataRes[i].nombre+'</td>';
                                newRow += (msg.dataRes[i].enterado == 1) ? '<td>Si</td>' : '<td>No</td>';
                                newRow += (msg.dataRes[i].enterado == 1) ? '<td>'+msg.dataRes[i].fechaEnterado+'</td>' : '<td>   </td>';
                            newRow += '</tr>';
                            $(newRow).appendTo("#dataAlum");
                        });
                    }else{
                        $("#dataAlum tbody").html('<tr><td colspan="5">'+msg.msgErr+'</td></tr>');
                    }
                }
            });
            
            //Leemos las notificaciones leídas
            $.ajax({
                type: "POST",
                url: "../controllers/read_notificaciones_tutores_detalles.php?idAv="+<?=$idAv;?>,
                success: function(msg){
                    console.log(msg);
                    var msg = jQuery.parseJSON(msg);
                    if(msg.error == 0){
                        $("#dataTut tbody").html("");
                        $.each(msg.dataRes, function(i, item){
                            var newRow = '<tr>'
                                +'<td>'+msg.dataRes[i].id+'</td>'
                                +'<td>'+msg.dataRes[i].nombre+'</td>';
                                newRow += (msg.dataRes[i].enterado == 1) ? '<td>Si</td>' : '<td>No</td>';
                                newRow += (msg.dataRes[i].enterado == 1) ? '<td>'+msg.dataRes[i].fechaEnterado+'</td>' : '<td>   </td>';
                            newRow += '</tr>';
                            $(newRow).appendTo("#dataTut");
                        });
                    }else{
                        $("#dataTut tbody").html('<tr><td colspan="4">'+msg.msgErr+'</td></tr>');
                    }
                }
            });
            
        });
    </script>
    
<?php
    }//end if-else
    include ('footer.php');
?>
