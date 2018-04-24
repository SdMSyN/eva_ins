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
        $idEsc = $idUser;
        
        //Obtenemos tipos de aviso
        $optAvTipo = '<option></option>';
        $sqlGetAvTipo = "SELECT * FROM $tAvTipo ";
        $resGetAvTipo = $con->query($sqlGetAvTipo);
        while($rowGetAvTipo = $resGetAvTipo->fetch_assoc()){
            $optAvTipo .= '<option value="'.$rowGetAvTipo['id'].'">'.$rowGetAvTipo['nombre'].'</option>';
        }
          
        //Obtenemos turnos
        $optTurn = '<option></option>';
        $sqlGetTurn = "SELECT * FROM $tTurn ";
        $resGetTurn = $con->query($sqlGetTurn);
        while($rowGetTurn = $resGetTurn->fetch_assoc()){
            $optTurn .= '<option value="'.$rowGetTurn['id'].'">'.$rowGetTurn['nombre'].'</option>';
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
                <div class="col-sm-3">
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="inputAvTipo" >Tipo de aviso</label>
                        <div class="col-sm-8">
                            <select class="form-control" id="inputAvTipo" name="inputAvTipo"><?=$optAvTipo;?></select>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
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
                <div class="col-sm-3">
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="inputAvTurn" >Turno: </label>
                        <div class="col-sm-8">
                            <select class="form-control" id="inputAvTurn" name="inputAvTurn"><?=$optTurn;?></select>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3 text-center">
                    <button type="submit" class="btn btn-primary">Notificar</button>
                </div>
            </div><!-- end row -->
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
        
        
        $(document).ready(function(){
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
                    inputAvTurn: {required: true}
                },
                messages: {
                    inputAviso: "Notificación obligatoria",
                    inputAvTipo: "Tipo de notificación obligatoria",
                    inputAvDest: "Para alguien debe de ir la información",
                    inputAvTurn: "¿Para que turno de tu institución va la información?"
                },
                tooltip_options: {
                    inputAviso: {trigger: "focus", placement: "bottom"},
                    inputAvTipo: {trigger: "focus", placement: "bottom"},
                    inputAvDest: {trigger: "focus", placement: "bottom"},
                    inputAvTurn: {trigger: "focus", placement: "bottom"}
                },
                submitHandler: function(form){
                    $('#loading').show();
                    $.ajax({
                        type: "POST",
                        url: "../controllers/esc_create_notificacion_escuela_turno.php",
                        data: $('form#formAdd').serialize(),
                        success: function(msg){
                            console.log(msg);
                            var msg = jQuery.parseJSON(msg);
                            if(msg.error == 0){
                                $('#loading').empty();
                                $('#loading').append('<img src="../assets/obj/success_256.png" height="300" width="400" >');
                                setTimeout(function () {
                                  location.href = 'esc_read_notificaciones.php';
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
