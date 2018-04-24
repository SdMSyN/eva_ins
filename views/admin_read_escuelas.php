<?php
    include ('header.php');
    include('../config/conexion.php');
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
        echo '<div class="row><div class="col-sm-12 text-center"><h2>No tienes permiso para entrar a esta sección. ━━[○･｀Д´･○]━━ </h2></div></div>';
    }else if($_SESSION['perfil'] != 10){
        echo '<div class="row"><div class="col-sm-12 text-center"><h2>¿Estás tratando de acceder? No es tu perfil o(´^｀)o </h2></div></div>';
    }else {
        
        //Obtenemos los diferentes niveles
        $sqlGetNiveles = "SELECT id, nombre FROM $tNivEsc ";
        $resGetNiveles = $con->query($sqlGetNiveles);
        $optNiveles = '<option></option>';
        while($rowGetNiveles = $resGetNiveles->fetch_assoc()){
            $optNiveles .= '<option value="'.$rowGetNiveles['id'].'">'.$rowGetNiveles['nombre'].'</option>';
        }
        
?>

    <div class="container">
        <div class="row"><div id="loading"><img src="../assets/obj/loading.gif" height="300" width="400"></div></div>
        <div class="row text-center"><h1>Escuelas</h1></div>
        <div class="row placeholder text-center">
            <div class="col-sm-12 placeholder">
                <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#modalAdd">
                    Añadir nueva escuela
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
                        <th><span title="nombre">Usuario</span></th>
                        <th><span title="nombre">Contraseña</span></th>
                        <th><span title="nombre">Nivel</span></th>
                        <th><span title="nombre">Creada</span></th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        
        <div class="modal fade" id="modalAdd" tabindex="-1" role="dialog" aria-labellebdy="myModal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
                        </button>
                        <h4 class="modal-title" id="exampleModalLabel">Añadir nueva escuela</h4>
                        <p class="msgModal"></p>
                    </div>
                    <form id="formSignUp" name="formSignUp" >
                        <div class="modal-body">
                            <small><i><span class="glyphicon glyphicon-asterisk obligatorio"></span> Campo obligatorio</i></small><br>
                            <fielset>
                                <legend>Acceso</legend>
                                <div class="form-group">
                                    <label for="inputName" class="control-label">Nombre <span class="glyphicon glyphicon-asterisk obligatorio"></span></label>
                                    <input type="text" class="form-control" id="inputName" name="inputName" >
                                </div>
                                <div class="form-group">
                                    <label for="inputUser" class="control-label">Usuario <span class="glyphicon glyphicon-asterisk obligatorio"></span></label>
                                    <input type="text" class="form-control" id="inputUser" name="inputUser" >
                                </div>
                                <div class="form-group">
                                    <label for="inputPass" class="control-label">Contraseña <span class="glyphicon glyphicon-asterisk obligatorio"></span></label>
                                    <input type="text" class="form-control" id="inputPass" name="inputPass" >
                                </div>
                                <div class="form-group">
                                    <label for="inputLevel" class="control-label">Nivel Escolar <span class="glyphicon glyphicon-asterisk obligatorio"></span></label>
                                    <select class="form-control" id="inputLevel" name="inputLevel" ><?=$optNiveles;?></select>
                                </div>
                            </fielset>
                            <fielset>
                                <legend>Dirección</legend>
                                <div class="form-group">
                                    <label for="inputStreet" class="control-label">Calle</label>
                                    <input type="text" class="form-control" id="inputStreet" name="inputStreet" >
                                </div>
                                <div class="form-group">
                                    <label for="inputNum" class="control-label">Número</label>
                                    <input type="text" class="form-control" id="inputNum" name="inputNum" >
                                </div>
                                <div class="form-group">
                                    <label for="inputCol" class="control-label">Colonia</label>
                                    <input type="text" class="form-control" id="inputCol" name="inputCol" >
                                </div>
                                <div class="form-group">
                                    <label for="inputMun" class="control-label">Municipio</label>
                                    <input type="text" class="form-control" id="inputMun" name="inputMun" >
                                </div>
                                <div class="form-group">
                                    <label for="inputCP" class="control-label">Código Postal</label>
                                    <input type="text" class="form-control" id="inputCP" name="inputCP" >
                                </div>
                                <div class="form-group">
                                    <label for="inputEdo" class="control-label">Estado</label>
                                    <input type="text" class="form-control" id="inputEdo" name="inputEdo" >
                                </div>
                            </fielset>
                            <fielset>
                                <legend>Contacto</legend>
                                <div class="form-group">
                                    <label for="inputTel" class="control-label">Teléfono</label>
                                    <input type="number" class="form-control" id="inputTel" name="inputTel" >
                                </div>
                                <div class="form-group">
                                    <label for="inputCel" class="control-label">Celular</label>
                                    <input type="number" class="form-control" id="inputCel" name="inputCel" >
                                </div>
                                <div class="form-group">
                                    <label for="inputMail" class="control-label">Correo electrónico <span class="glyphicon glyphicon-asterisk obligatorio"></span></label>
                                    <input type="mail" class="form-control" id="inputMail" name="inputMail" >
                                </div>
                                <div class="form-group">
                                    <label for="inputFace" class="control-label">Facebook</label>
                                    <input type="text" class="form-control" id="inputFace" name="inputFace" >
                                </div>
                                <div class="form-group">
                                    <label for="inputTwi" class="control-label">Twitter</label>
                                    <input type="text" class="form-control" id="inputTwi" name="inputTwi" >
                                </div>
                            </fielset>
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
                   url: "../controllers/get_escuelas.php",
                   success: function(msg){
                       //alert(msg);
                       $("#data tbody").html(msg);
                       var msg = jQuery.parseJSON(msg);
                       if(msg.error == 0){
                           $("#data tbody").html("");
                           $.each(msg.dataRes, function(i, item){
                               var newRow = '<tr>'
                                    +'<td>'+msg.dataRes[i].id+'</td>'   
                                    +'<td>'+msg.dataRes[i].nombre+'</td>' 
                                    +'<td>'+msg.dataRes[i].user+'</td>' 
                                    +'<td>'+msg.dataRes[i].pass+'</td>' 
                                    +'<td>'+msg.dataRes[i].nivel+'</td>' 
                                    +'<td>'+msg.dataRes[i].creado+'</td>' 
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
           
            $('#formSignUp').validate({
                rules:{
                    inputName: {required: true},
                    inputUser: {required: true},
                    inputPass: {required: true},
                    inputLevel: {required: true},
                    inputCP: {minlength: 5, maxlength: 5, digits: true},
                    inputTel: {minlength: 10, maxlength: 10, digits: true},
                    inputCel: {minlength: 10, maxlength: 10, digits: true},
                    inputMail: {required: true, email: true}
                },
                messages:{
                    inputName: "Nombre obligatorio",
                    inputUser: "El usuario es importante",
                    inputPass: "La seguridad es lo primero",
                    inputLevel: "¿A qué nivel pertenece la institución?",
                    inputCP: {
                        minlength: "El CP suele tener al menos 5 dígitos", 
                        maxlength: "El CP no tiene más de 10 dígitos", 
                        digits: "Solo números, ningún caracter más"
                    },
                    inputTel: {
                        minlength: "El teléfono suele tener al menos 10 dígitos", 
                        maxlength: "El teléfono no tiene más de 10 dígitos", 
                        digits: "Solo números, ningún caracter más"
                    },
                    inputCel:{
                        minlength: "El celular suele tener al menos 10 dígitos", 
                        maxlength: "El celular no tiene más de 10 dígitos", 
                        digits: "Solo números, ningún caracter más"
                    },
                    inputMail: {
                        required: "Necesitamos tu correo para contactarte", 
                        email: "El formato de tu correo es invalido ¿Nos quieres engañar?"
                    }
                },
                tooltip_options:{
                    inputName: {trigger: "focus", placement: "bottom"},
                    inputUser: {trigger: "focus", placement: "bottom"},
                    inputPass: {trigger: "focus", placement: "bottom"},
                    inputLevel: {trigger: "focus", placement: "bottom"},
                    inputCP: {trigger: "focus", placement: "bottom"},
                    inputTel: {trigger: "focus", placement: "bottom"},
                    inputCel: {trigger: "focus", placement: "bottom"},
                    inputMail: {trigger: "focus", placement: "bottom"}
                },
                submitHandler: function(form){
                    $('#loading').show();
                    $.ajax({
                        type: 'POST',
                        url: "../controllers/admin_create_usuario_escuela.php", 
                        data: $('form#formSignUp').serialize(),
                        success: function(msg){
                            //alert(msg);
                            var msg = jQuery.parseJSON(msg);
                            if(msg.error == 0){
                                $('#loading').empty();
                                $('#loading').append('<img src="../assets/obj/success_256.png" height="300" width="400" >');
                                setTimeout(function(){
                                    location.href='admin_read_escuelas.php';
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
                            alert("Error al crear usuario escuela");
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
