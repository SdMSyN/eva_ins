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
        <div class="row text-center"><h1>Profesores</h1></div>
        <div class="row placeholder text-center">
            <div class="col-sm-12 placeholder">
                <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#modalImp">
                    Importar profesores
                    <span class="glyphicon glyphicon-cloud-upload"></span>
                </button>
                <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#modalAdd">
                    Añadir nuevo profesor
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
                        <th><span title="grado">Usuario</span></th>
                        <th><span title="creador">Contraseña</span></th>
                        <th>Eliminar</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        
        <div class="modal fade" id="modalImp" tabindex="-1" role="dialog" aria-labellebdy="myModal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
                        </button>
                        <h4 class="modal-title" id="exampleModalLabel">Importar profesores</h4>
                        <p class="msgModal"></p>
                    </div>
                    <form id="formImp" name="formImp" >
                        <div class="modal-body">
                            <div class="form-group">
                                <input type="hidden" name="inputIdEsc" value="<?=$idUser;?>">
                                <label for="inputFile">Archivo CSV 
                                    <a href="#" data-toggle="tooltip" title="Archivo Excel en formato CSV (archivo separado por comas), 3 campos: Apellido paterno, Apellido Materno y Nombre(s)">
                                        <span class="glyphicon glyphicon-question-sign"></span>
                                    </a>
                                    <a href="../uploads/plantilla.csv" data-toggle="tooltip" title="Descargar formato">
                                        <span class="glyphicon glyphicon-download-alt"></span>
                                    </a>
                                : </label>
                                <input type="file" class="form-control" id="inputFile" name="inputFile" >
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Añadir</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
                            
        <div class="modal fade" id="modalAdd" tabindex="-1" role="dialog" aria-labellebdy="myModal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
                        </button>
                        <h4 class="modal-title" id="exampleModalLabel">Añadir nuevo profesor</h4>
                        <p class="msgModal"></p>
                    </div>
                    <form id="formSignUp" name="formSignUp" >
                        <div class="modal-body">
                            <small><i><span class="glyphicon glyphicon-asterisk obligatorio"></span> Campo obligatorio</i></small><br>
                            <div class="form-group">
                                <input type="hidden" name="inputIdEsc" value="<?=$idUser;?>">
                                <label for="inputAP">Apellido Paterno: </label>
                                <input class="form-control" id="inputAP" name="inputAP">
                            </div>
                            <div class="form-group">
                                <label for="inputAM">Apellido Materno: </label>
                                <input class="form-control" id="inputAM" name="inputAM">
                            </div>
                            <div class="form-group">
                                <label for="inputName">Nombre: </label>
                                <input class="form-control" id="inputName" name="inputName">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Añadir</button>
                        </div>
                    </form>
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
                   url: "../controllers/esc_get_profesores.php?idEsc="+<?=$idUser;?>,
                   success: function(msg){
                       console.log(msg);
                       var msg = jQuery.parseJSON(msg);
                       if(msg.error == 0){
                           //alert(msg.dataRes[0].id);
                           $("#data tbody").html("");
                           $.each(msg.dataRes, function(i, item){
                               var newRow = '<tr>'
                                    +'<td>'+msg.dataRes[i].id+'</td>'   
                                    +'<td>'+msg.dataRes[i].nombre+'</td>'   
                                    +'<td>'+msg.dataRes[i].user+'</td>'
                                    +'<td>'+msg.dataRes[i].pass+'</td>'
                                    +'<td><button type="button" class="btn btn-danger" id="delete" value="'+msg.dataRes[i].id+'"><span class="glyphicon glyphicon-remove"></span></button></td>'  
                                    +'</tr>';
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
           
           $("#data").on("click", "#delete", function(){
                var idProf = $(this).val();
                //alert("Hola: "+idProf);
                if(confirm("¿Seguro que deseas eliminar a este profesor? Recuerda que no debe estar asignado a ninguna materia")){
                    $('#loading').show();
                    $.ajax({
                         method: "POST",
                         url: "../controllers/delete_profesor.php?idProf="+idProf,
                         success: function(data){
                            //alert(data);
                            console.log(data);
                            var msg = jQuery.parseJSON(data);
                            if(msg.error == 0){
                                $('#loading').empty();
                                $('#loading').append('<img src="../assets/obj/success_256.png" height="300" width="400" >');
                                $('#loading').append('<p>'+msg.dataRes+'</p>');
                                setTimeout(function () {
                                  location.href = 'esc_read_profesores.php';
                                }, 1500);
                            }else{
                                $('#loading').empty();
                                $('#loading').append('<img src="../assets/obj/error.png" height="300" width="400" >'
                                        +'<p><b>'+msg.dataRes+'</b></p>');
                                setTimeout(function () {
                                  $('#loading').hide();
                                }, 1500);
                            }
                         }
                     })
                }else{
                    alert("Ten cuidado.");
                }
            });
            
           //importar profesores
           $('#formImp').validate({
                rules: {
                    inputFile: {required: true, extension: "csv"}
                },
                messages: {
                    inputFile: { 
                        required: "Se requiere un archivo",
                        extension: "Solo se permite archivos *.csv (archivo separado por comas de Excel)"
                    }
                },
                tooltip_options: {
                    inputFile: {trigger: "focus", placement: "bottom"}
                },
                submitHandler: function(form){
                    $('#loading').show();
                    $.ajax({
                        type: "POST",
                        url: "../controllers/esc_import_profesores.php",
                        data: new FormData($("form#formImp")[0]),
                        //data: $('form#formAdd').serialize(),
                        contentType: false,
                        processData: false,
                        success: function(msg){
                            console.log(msg);
                            var msg = jQuery.parseJSON(msg);
                            if(msg.error == 0){
                                $('#loading').empty();
                                $('#loading').append('<img src="../assets/obj/success_256.png" height="300" width="400" >');
                                $('.msgModal').css({color: "#77DD77"});
                                $('.msgModal').html(msg.msgErr);
                                setTimeout(function () {
                                  location.href = 'esc_read_profesores.php';
                                }, 1500);
                            }else{
                                $('#loading').empty();
                                $('#loading').append('<img src="../assets/obj/error.png" height="300" width="400" ><p>'+msg.msgErr+'</p>');
                                $('.msgModal').css({color: "#FF0000"});
                                $('.msgModal').html(msg.msgErr);
                                setTimeout(function () {
                                  $('#loading').hide();
                                }, 1500);
                            }
                        }, error: function(){
                            alert("Error al importsr profesores");
                        }
                    });
                }
            }); // end importar profesores
            //
           //añadir nuevo
           $('#formSignUp').validate({
                rules: {
                    inputName: {required: true},
                    inputAP: {required: true},
                    inputAM: {required: true}
                },
                messages: {
                    inputName: "Nombre obligatorio",
                    inputAP: "Nombre obligatorio",
                    inputAM: "Nombre obligatorio"
                },
                tooltip_options: {
                    inputName: {trigger: "focus", placement: "bottom"},
                    inputAP: {trigger: "focus", placement: "bottom"},
                    inputAM: {trigger: "focus", placement: "bottom"}
                },
                submitHandler: function(form){
                    $('#loading').show();
                    $.ajax({
                        type: 'POST',
                        url: "../controllers/esc_create_usuario_profesor.php", 
                        data: $('form#formSignUp').serialize(),
                        success: function(msg){
                            //alert(msg);
                            var msg = jQuery.parseJSON(msg);
                            if(msg.error == 0){
                                $('#loading').empty();
                                $('#loading').append('<img src="../assets/obj/success_256.png" height="300" width="400" >');
                                setTimeout(function(){
                                    location.href='esc_read_profesores.php';
                                }, 2000);
                            }else{
                                $('.msgModal').css({color: "#FF0000"});
                                $('.msgModal').html(msg.msgErr);
                                $('#loading').empty();
                                $('#loading').append('<img src="../assets/obj/error.png" height="300" width="400" ><p>'+msg.msgErr+'</p>');
                                setTimeout(function (){
                                    $('#loading').hide();
                                },1500);
                            }
                        },
                        error: function(){
                            alert("Error al crear usuario profesor");
                        }
                    });
                }
            });
           
        });
    </script>
    
<?php
    }//end if-else
    include ('footer.php');
?>
