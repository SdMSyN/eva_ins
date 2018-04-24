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
    <body>
<?php
    include ('navbar.php');
    if (!isset($_SESSION['sessU'])){
        echo '<div class="row><div class="col-sm-12 text-center"><h2>No tienes permiso para entrar a esta sección. ━━[○･｀Д´･○]━━ </h2></div></div>';
    }else if($_SESSION['perfil'] != 1){
        echo '<div class="row"><div class="col-sm-12 text-center"><h2>¿Estás tratando de acceder? No es tu perfil o(´^｀)o </h2></div></div>';
    }else {
        $idUser = $_SESSION['userId'];
        $idPerfil = $_SESSION['perfil'];
        $idNivelEsc = $_SESSION['nivelEsc'];
          
?>

    <div class="container">
        <div class="row">
            <div id="loading">
                <img src="../assets/obj/loading.gif" height="300" width="400">
            </div>
        </div>
        <div class="row text-center"><h1>Notificaciones</h1></div>
        <div class="row placeholder text-center">
            <div class="col-sm-12 placeholder">
                <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#modalSelDest">
                    Añadir nueva notificación
                    <span class="glyphicon glyphicon-plus"></span>
                </button>
            </div>
        </div>
        <br>
        <div class="table-responsive">
            <table class="table table-striped" id="data">
                <thead>
                    <tr>
                        <th><span title="id">Id</span></th>
                        <th><span title="nombre">Nombre</span></th>
                        <th><span title="tipo">Tipo</span></th>
                        <th><span title="dirigido_a">Dirigido A</span></th>
                        <th><span title="creado">Fecha</span></th>
                        <th><span title="creado_por">Creador</span></th>
                        <th>Detalles</th>
                        <th># Alumnos</th>
                        <th># Tutores</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        
        <!-- modal para seleccionar a quien va la notificación -->
        <div class="modal fade bs-example-modal-lg" id="modalSelDest" tabindex="-1" role="dialog" aria-labellebdy="myModal" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
                        </button>
                        <h4 class="modal-title" id="exampleModalLabel"></h4>
                        <p class="msgModal"></p>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-3 text-center">
                                <a href="esc_create_notificacion_escuela.php" class="btn btn-primary">Escuela</a>
                                <p>Envía la notificación a todos los alumnos de la escuela.</p>
                            </div>
                            <div class="col-sm-3 text-center">
                                <a href="esc_create_notificacion_escuela_turno.php" class="btn btn-primary">Turnos</a>
                                <p>Envía la notificación solo a los alumnos de un turno de la escuela.</p>
                            </div>
                            <div class="col-sm-3 text-center">
                                <a href="esc_create_notificacion_grupos.php" class="btn btn-primary">Grupos</a>
                                <p>Selecciona solo algunos grupos a quienes quieres enviar la notificación.</p>
                            </div>
                            <div class="col-sm-3 text-center">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalSelGrupo" >Alumnos</button>
                                <p>Selecciona alumnos en especifico para notificar.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- modal para seleccionar a quien va la notificación -->
        <div class="modal fade" id="modalSelGrupo" tabindex="-1" role="dialog" aria-labellebdy="myModal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
                        </button>
                        <h4 class="modal-title" id="exampleModalLabel">Selecciona el grupo de búsqueda</h4>
                        <p class="msgModal"></p>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="esc_create_notificacion_alumnos.php" class="form-horizontal">
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="inputGrado">Grado</label>
                                <div class="col-sm-8">
                                    <select id="inputGrado" name="inputGrado" class="form-control" required></select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="inputGrupo">Grupo</label>
                                <div class="col-sm-8">
                                    <select id="inputGrupo" name="inputGrupo" class="form-control" required></select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-4 col-sm-8">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-primary">Crear notificación</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
    </div>

    <script type="text/javascript">
        $('#loading').hide();
        var ordenar = '';
        $(document).ready(function(){
           filtrar();
           function filtrar(){
               $.ajax({
                   type: "POST",
                   data: ordenar, 
                   url: "../controllers/esc_read_notificaciones.php?idEsc=<?=$idUser;?>",
                   success: function(msg){
                       console.log(msg);
                       var msg = jQuery.parseJSON(msg);
                       if(msg.error == 0){
                           $("#data tbody").html("");
                           $.each(msg.dataRes, function(i, item){
                               var newRow = '<tr>'
                                    +'<td>'+msg.dataRes[i].id+'</td>'   
                                    +'<td>'+msg.dataRes[i].nombre+'</td>'   
                                    +'<td>'+msg.dataRes[i].tipo+'</td>';
                                    if(msg.dataRes[i].dirigido == 1) newRow += '<td>Alumnos</td>';
                                    else if(msg.dataRes[i].dirigido == 2) newRow += '<td>Tutores</td>';
                                    else if(msg.dataRes[i].dirigido == 3) newRow += '<td>Alumnos y Tutores</td>';
                                    else newRow += '<td>Ninguno</td>';
                                    newRow += '<td>'+msg.dataRes[i].fecha+'</td>'
                                    +'<td>'+msg.dataRes[i].creador+'</td>'
                                    +'<td><a href="esc_read_notificaciones_details.php?idAv='+msg.dataRes[i].id+'" class="btn btn-default" >'
                                    +'<span class="glyphicon glyphicon-stats"></span></a></td>';
                                    newRow += '<td>'+msg.dataRes[i].numAlum+' ('+msg.dataRes[i].numAlumEnt+')</td>';
                                    newRow += '<td>'+msg.dataRes[i].numTut+' ('+msg.dataRes[i].numTutEnt+')</td>';
                                    newRow +='</tr>';
                                $(newRow).appendTo("#data tbody");
                           });
                           
                       }else{
                           var newRow = '<tr><td></td><td>'+msg.msgErr+'</td></tr>';
                           $("#data tbody").html(newRow);
                       }
                   }
               });
           }
           
           //Ordenar ASC y DESC header tabla
            $("#data th span").click(function(){
                if($(this).hasClass("desc")){
                    $("#data th span").removeClass("desc").removeClass("asc");
                    $(this).addClass("asc");
                    ordenar = "&orderby="+$(this).attr("title")+" asc";
                }else{
                    $("#data th span").removeClass("desc").removeClass("asc");
                    $(this).addClass("desc");
                    ordenar = "&orderby="+$(this).attr("title")+" desc";
                }
                filtrar();
            });
           
           //Obtenemos grados del nivel escolar
           $.ajax({
                type: "POST", 
                url: "../controllers/get_grados.php?idNivel=<?=$idNivelEsc;?>",
                success: function(msg){
                    console.log(msg);
                    var msg = jQuery.parseJSON(msg);
                    if(msg.error == 0){
                        $("#modalSelGrupo #inputGrado").html('<option></option>');
                        //var newRow;
                        $.each(msg.dataRes, function(i, item){
                            var newRow = '<option value="'+msg.dataRes[i].id+'">'+msg.dataRes[i].nombre+'</option>';
                            $(newRow).appendTo("#modalSelGrupo #inputGrado");
                        });
                    }else{
                        $("#modalSelGrupo #inputGrado").html('<option>'+msg.msgErr+'</option>');
                    }
                }
            });
           
           $("#modalSelGrupo #inputGrado").on("change", function(){
               var grado = $(this).val();
               $.ajax({
                    type: "POST", 
                    url: "../controllers/get_grupo_by_grado.php?idGrado="+grado+"&idEsc=<?=$idUser;?>",
                    success: function(msg){
                        console.log(msg);
                        var msg = jQuery.parseJSON(msg);
                        if(msg.error == 0){
                            $("#modalSelGrupo #inputGrupo").html('<option></option>');
                            //var newRow;
                            $.each(msg.dataRes, function(i, item){
                                var newRow = '<option value="'+msg.dataRes[i].id+'">'
                                        +msg.dataRes[i].nombre+' - '+msg.dataRes[i].turno+'</option>';
                                $(newRow).appendTo("#modalSelGrupo #inputGrupo");
                            });
                        }else{
                            $("#modalSelGrupo #inputGrupo").html('<option></option>');
                        }
                    }
                });
           })
           
        });
    </script>
    
<?php
    }//end if-else
    include ('footer.php');
?>
