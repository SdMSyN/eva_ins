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
    }else if($_SESSION['perfil'] != 1.2){
        echo '<div class="row"><div class="col-sm-12 text-center"><h2>¿Estás tratando de acceder? No es tu perfil o(´^｀)o </h2></div></div>';
    }else {
        $idUser = $_SESSION['userId'];
        $idPerfil = $_SESSION['perfil'];
        $idNivelEsc = $_SESSION['nivelEsc'];
        //$idEsc = $idUser;
        $idEsc = $_SESSION['idEsc'];
        
        $optAvTipo = '<option></option>';
        $sqlGetAvTipo = "SELECT * FROM $tAvTipo ";
        $resGetAvTipo = $con->query($sqlGetAvTipo);
        while($rowGetAvTipo = $resGetAvTipo->fetch_assoc()){
            $optAvTipo .= '<option value="'.$rowGetAvTipo['id'].'">'.$rowGetAvTipo['nombre'].'</option>';
        }
          
?>

    <div class="container">
        <div class="row">
            <div id="loading">
                <img src="../assets/obj/loading.gif" height="300" width="400">
            </div>
        </div>
        <div class="row text-center"><h1>Crear Notificación</h1></div>
        <br>
        <form id="formAdd" class="form-horizontal">
            <input type="hidden" name="idEsc" value="<?=$idEsc;?>" >
            <div class="row">
                <div class="form-group">
                    <label class="col-sm-4 control-label" for="inputAviso">
                        Notificación<br><p id="countAv"><i>0/999</i></p>
                    </label>
                    <div class="col-sm-8">
                        <textarea typ="text" id="inputAviso" name="inputAviso" class="form-control" rows="5"></textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="inputAvTipo" >Tipo de aviso</label>
                        <div class="col-sm-8">
                            <select class="form-control" id="inputAvTipo" name="inputAvTipo"><?=$optAvTipo;?></select>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="inputAvDest" >¿Información para?</label>
                        <div class="col-sm-8">
                            <select class="form-control" id="inputAvDest" name="inputAvDest">
                                <option></option>
                                <option value="1">Alumnos/as</option>
                                <option value="2">Tutores/as</option>
                                <option value="3">Ambos</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 text-center">
                    <button type="submit" class="btn btn-primary">Notificar</button>
                </div>
            </div><!-- end row -->
            <div class="table-responsive">
                <table class="table table-striped" id="data">
                    <caption>Notificar a</caption>
                    <thead>
                        <tr>
                            <th><label for="checkTodos"><input type="checkbox" id="checkTodos" ></label></th>
                            <th><span title="id">Id</span></th>
                            <th><span title="grado">Grado</span></th>
                            <th><span title="nombre">Grupo</span></th>
                            <th><span title="turno">Turno</span></th>
                        </tr>
                    </thead>
                        <tbody></tbody>
                </table>
            </div>
        </form>
        
    </div>

    <script type="text/javascript">
        $('#loading').hide();
        /* Script para contar caracteres faltantes:
        http://mysticalpotato.wordpress.com/2012/10/27/contador-de-caracteres-para-textarea-al-estilo-twitter-con-jquery/ */
        init_contadorTa("inputAviso","countAv", 999);
        function init_contadorTa(idtextarea, idcontador, max){
            $("#"+idtextarea).keyup(function(){
                    updateContadorTa(idtextarea, idcontador, max);
            });
            $("#"+idtextarea).change(function(){
                    updateContadorTa(idtextarea, idcontador, max);
            });
        }
        function updateContadorTa(idtextarea, idcontador, max){
            var contador= $("#"+idcontador);
            var ta= $("#"+idtextarea);
            contador.html("0/"+max);
            contador.html(ta.val().length+"/"+max);
            if(parseInt(ta.val().length) > max){
                    ta.val(ta.val().substring(0, max-1));
                    contador.html(max+"/"+max);
            }
        }
        
        var ordenar = '';
        $(document).ready(function(){
            filtrar();
            function filtrar(){
                $.ajax({
                    type: "POST",
                    data: ordenar,
                    url: "../controllers/get_grupos.php?idEsc=<?=$idEsc;?>",
                    success: function(msg){
                        console.log(msg);
                        var msg = jQuery.parseJSON(msg);
                        if(msg.error == 0){
                            $('#loading').empty();
                            $('#loading').append('<img src="../assets/obj/success_256.png" height="300" width="400" >');
                            setTimeout(function (){$('#loading').hide();},1500);
                            $("#data tbody").html("");
                            $.each(msg.dataRes, function(i, item){
                                var newRow = '<tr>'
                                    +'<td><input type="checkbox" id="checkIdGrupo" '
                                        +'name="checkIdGrupo[]" value="'+msg.dataRes[i].id+'" ></td>'
                                    +'<td>'+msg.dataRes[i].id+'</td>'
                                    +'<td>'+msg.dataRes[i].grado+'</td>'
                                    +'<td>'+msg.dataRes[i].nombre+'</td>'
                                    +'<td>'+msg.dataRes[i].turno+'</td>'
                                    +'</tr>';
                                $(newRow).appendTo("#data tbody");
                            });
                        }else{
                            $('#loading').empty();
                            $('#loading').append('<img src="../assets/obj/error.png" height="300" width="400" ><p>'+msg.msgErr+'</p>');
                            setTimeout(function(){$('#loading').hide();},1500);
                        }
                    }, error: function(){
                        alert("Error al buscar alumnos.");
                    }
                });
            }//end filtrar
            
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
            
            //Marcar/desmarcar Todos checkbox
            $("#checkTodos").change(function () {
                $("#data input:checkbox").prop('checked', $(this).prop("checked"));
            });
            
            //añadir nuevo examen
            $('#formAdd').validate({
                rules: {
                    inputAviso: {required: true},
                    inputAvTipo: {required: true},
                    inputAvDest: {required: true},
                    'checkIdGrupo[]': {required: true}
                },
                messages: {
                    inputAviso: "Notificación obligatoria",
                    inputAvTipo: "Tipo de notificación obligatoria",
                    inputAvDest: "Para alguien debe de ir la información",
                    'checkIdGrupo[]': "Selecciona al menos un grupo",
                },
                tooltip_options: {
                    inputAviso: {trigger: "focus", placement: "bottom"},
                    inputAvTipo: {trigger: "focus", placement: "bottom"},
                    inputAvDest: {trigger: "focus", placement: "bottom"},
                    'checkIdGrupo[]': {trigger: "focus", placement: "bottom"}
                },
                submitHandler: function(form){
                    $('#loading').show();
                    $.ajax({
                        type: "POST",
                        url: "../controllers/esc_create_notificacion_grupos.php",
                        data: $('form#formAdd').serialize(),
                        success: function(msg){
                            console.log(msg);
                            var msg = jQuery.parseJSON(msg);
                            if(msg.error == 0){
                                $('#loading').empty();
                                $('#loading').append('<img src="../assets/obj/success_256.png" height="300" width="400" >');
                                setTimeout(function () {
                                  location.href = 'sec_read_notificaciones.php';
                                }, 1500);
                            }else{
                                $('#loading').empty();
                                $('#loading').append('<img src="../assets/obj/error.png" height="300" width="400" >');
                                setTimeout(function(){$('#loading').hide();},1500);
                            }
                        }, error: function(){
                            $('#loading').empty();
                            $('#loading').append('<img src="../assets/obj/error.png" height="300" width="400" >');
                            setTimeout(function(){$('#loading').hide();},1500);
                        }
                    });
                }
            }); // end añadir nuevo examen
            
        });
    </script>
    
<?php
    }//end if-else
    include ('footer.php');
?>
