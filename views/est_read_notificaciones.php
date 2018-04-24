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
    }else if($_SESSION['perfil'] != 3){
        echo '<div class="row"><div class="col-sm-12 text-center"><h2>¿Estás tratando de acceder? No es tu perfil o(´^｀)o </h2></div></div>';
    }
    else {
        $idPerfil = $_SESSION['perfil'];
        $idUser = $_SESSION['userId'];
        unset ( $_SESSION['exaRand'] );
?>

    <div class="container">
        <div class="row">
            <div id="loading">
                <img src="../assets/obj/loading.gif" height="300" width="400">
            </div>
        </div>
        <br>
        <div class="panel panel-default">
            <div class="panel-heading">Historial de notificaciones</div>
            <div class="panel-body">
                <div>
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                      <li role="presentation" class="active"><a href="#por_leer" aria-controls="por_leer" role="tab" data-toggle="tab">Pendientes</a></li>
                      <li role="presentation"><a href="#leidos" aria-controls="leidos" role="tab" data-toggle="tab">Leídos</a></li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="por_leer">
                            <div class="table-responsive">
                                <table class="table table-hover" id="dataNotPorLeer">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Fecha</th>
                                            <th>Notificación</th>
                                            <th>Creador</th>
                                            <th>Enterado</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="leidos">
                            <div class="table-responsive">
                                <table class="table table-hover" id="dataNotLeidas">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Fecha</th>
                                            <th>Notificación</th>
                                            <th>Creador</th>
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
            //Contar notificaciones
            $.ajax({
                type: "POST",
                url: "../controllers/est_read_notificaciones_no_leidas.php?idEst="+<?=$idUser;?>,
                success: function(msg){
                    console.log(msg);
                    var msg = jQuery.parseJSON(msg);
                    var countNot = 0;
                    if(msg.error == 0){
                        $.each(msg.dataRes, function(i, item){
                            if(msg.dataRes[i].enterado == null)
                                countNot++; 
                        });
                    }
                    $("#numNots").html(countNot);
                }
            });
            
            //Leemos las notificaciones no leídas
            $.ajax({
                type: "POST",
                url: "../controllers/est_read_notificaciones_no_leidas.php?idEst="+<?=$idUser;?>,
                success: function(msg){
                    console.log(msg);
                    var msg = jQuery.parseJSON(msg);
                    if(msg.error == 0){
                        $("#dataNotPorLeer tbody").html("");
                        $.each(msg.dataRes, function(i, item){
                            var newRow = '<tr>'
                                +'<td>'+msg.dataRes[i].id+'</td>'
                                +'<td>'+msg.dataRes[i].fecha+'</td>'
                                +'<td>'+msg.dataRes[i].nombre+'</td>'
                                +'<td>'+msg.dataRes[i].creador+'</td>'
                                +'<td><button type="button" class="btn btn-primary" id="marcarLeido" value="'+msg.dataRes[i].id+'">'
                                    +'<span class="glyphicon glyphicon-saved"></span></button></td>'
                            +'</tr>';
                            $(newRow).appendTo("#dataNotPorLeer");
                        });
                    }else{
                        $("#dataNotPorLeer tbody").html('<tr><td colspan="5">'+msg.msgErr+'</td></tr>');
                    }
                }
            });
            
            //Leemos las notificaciones leídas
            $.ajax({
                type: "POST",
                url: "../controllers/est_read_notificaciones_leidas.php?idEst="+<?=$idUser;?>,
                success: function(msg){
                    console.log(msg);
                    var msg = jQuery.parseJSON(msg);
                    if(msg.error == 0){
                        $("#dataNotLeidas tbody").html("");
                        $.each(msg.dataRes, function(i, item){
                            var newRow = '<tr>'
                                +'<td>'+msg.dataRes[i].id+'</td>'
                                +'<td>'+msg.dataRes[i].fecha+'</td>'
                                +'<td>'+msg.dataRes[i].nombre+'</td>'
                                +'<td>'+msg.dataRes[i].creador+'</td>'
                            +'</tr>';
                            $(newRow).appendTo("#dataNotLeidas");
                        });
                    }else{
                        $("#dataNotLeidas tbody").html('<tr><td colspan="4">'+msg.msgErr+'</td></tr>');
                    }
                }
            });
            
            $("#dataNotPorLeer").on("click", "#marcarLeido", function(){
                var idAv = $(this).val();
                console.log("Hola: "+idAv);
                if(confirm("¿Seguro que ya haz leído la información?")){
                    $('#loading').show();
                    $.ajax({
                         method: "POST",
                         url: "../controllers/est_update_notificacion_vista.php?idAv="+idAv,
                         success: function(data){
                            console.log(data);
                            var msg = jQuery.parseJSON(data);
                            if(msg.error == 0){
                                $('#loading').empty();
                                $('#loading').append('<img src="../assets/obj/success_256.png" height="300" width="400" >');
                                $('#loading').append('<p>'+msg.dataRes+'</p>');
                                setTimeout(function () {
                                  location.href = 'est_read_notificaciones.php';
                                }, 1500);
                            }else{
                                $('#loading').empty();
                                $('#loading').append('<img src="../assets/obj/error.png" height="300" width="400" ><p>'+msg.msgErr+'</p>');
                                setTimeout(function(){$('#loading').hide();}, 1500);
                            }
                         }
                     })
                }else{
                    alert("Ten cuidado.");
                }
            });
        });
    </script>
    
<?php
    }//end if-else
    include ('footer.php');
?>
