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
        echo '<div class="row"><div class="col-sm-12 text-center"><h2>No tienes permiso para entrar a esta sección. ━━[○･｀Д´･○]━━ </h2></div></div>';
    }else if($_SESSION['perfil'] != 2){
        echo '<div class="row"><div class="col-sm-12 text-center"><h2>¿Estás tratando de acceder? No es tu perfil o(´^｀)o </h2></div></div>';
    }
    else {
        
        $idPerfil = $_SESSION['perfil'];
        $idUser = $_SESSION['userId'];

        $idExam = $_GET['idExam'];
        
        //Obtenemos información del examen
        $sqlGetExaInfo = "SELECT * FROM $tExaInf WHERE id='$idExam' ";
        $resGetExaInfo = $con->query($sqlGetExaInfo);
        $rowGetExaInfo = $resGetExaInfo->fetch_assoc();
        $idMateria = $rowGetExaInfo['banco_materia_id'];
        $nameExa = $rowGetExaInfo['nombre'];
        
        $optValor = '';
        for($i=1; $i<=10; $i++){
            $optValor .= '<option value="'.$i.'">'.$i.'</option>';
        }
?>

    <div class="container">
         <div class="row">
            <div id="loading">
                <img src="../assets/obj/loading.gif" height="300" width="400">
            </div>
        </div>
        <div class="" id="contenedor" >
            <form id="formAdd" name="formAdd" class="form-horizontal">
                <div class="form-group">
                    <input type="hidden" name="idExam" value="<?=$idExam;?>">
                    <input type="hidden" name="idProf" value="<?=$idUser;?>">
                    <input type="hidden" name="idPerfil" value="<?=$idPerfil;?>">
                    <input type="hidden" name="idMateria" value="<?=$idMateria;?>">
                    
                    <label for="input" class="col-sm-3 control-label">
                        Pregunta <p id="countPreg"><i>0/250</i></p>
                    </label>
                    <div class="col-sm-9">
                        <textarea class="form-control" id="inputPreg" name="inputPreg"></textarea>
                    </div>
                </div><!-- end form-group -->
                <div class="form-group">
                    <label for="input" class="col-sm-3 control-label">Archivo</label>
                    <div class="col-sm-9">
                        <input type="file" class="form-control" id="files" name="files">
                    </div>
                </div><!-- end form-group -->
                <div class="form-group">
                    <label for="input" class="col-sm-3 control-label">¿Compartir?</label>
                    <div class="col-sm-9">
                        <label class="radio-inline">
                            <input type="radio" name="inputCompartir" id="inlineRadio1" value="1" checked> Si
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="inputCompartir" id="inlineRadio2" value="0"> No
                        </label>
                    </div>
                </div><!-- end form-group -->
                <div class="form-group">
                    <label for="input" class="col-sm-3 control-label">Valor de la pregunta</label>
                    <div class="col-sm-9">
                        <select class="form-control" name="inputValor" id="inputValor"><?= $optValor; ?></select> 
                    </div>
                </div><!-- end form-group -->
                <div class="form-group">
                    <label class="col-sm-3 control-label">Selecciona tipo de respuestas</label>
                    <div class="col-sm-9">
                        <select class="form-control" id="respType" name="respType">
                            <option></option>    
                            <option value="1">Opción multiple</option>    
                            <option value="2">Opción multirespuesta</option>    
                            <option value="3">Respuesta abierta</option>    
                            <option value="4">Respuesta exacta</option>  
                        </select>
                        <button type="button" class="addResp" id="addResp">Añadir nueva respuesta</button>
                    </div>
                </div><!-- end form-group -->
                <div class="col-sm-offset-3 col-sm-9"  id="contenedorPregs">
                    
                </div>
                <div class="col-sm-12">
                     <button type="submit" class="btn btn-primary">Crear</button>
                </div>
            </form>
        </div>
    </div>

    <script type="text/javascript">
        $('#loading').hide();
        /* Script para contar caracteres faltantes:
        http://mysticalpotato.wordpress.com/2012/10/27/contador-de-caracteres-para-textarea-al-estilo-twitter-con-jquery/ */
        init_contadorTa("inputPreg","countPreg", 250);
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
            $("#formAdd").validate({
                rules: {
                    inputPreg: {required: true},
                    inputValor: {required: true},
                    respType: {required: true}
                },
                messages: {
                    inputPreg: "Campo obligatorio",
                    inputValor: "¿No tienen ningún valor tus preguntas?",
                    respType: "Selecciona que tipo de respuestas son"
                },
                tooltip_options:{
                    inputPreg: {trigger: "focus", placement: "bottom"},
                    inputValor: {trigger: "focus", placement: "bottom"},
                    respType: {trigger: "focus", placement: "bottom"}
                },
                submitHandler: function(form){
                    $('#loading').show();
                    $.ajax({
                        type: "POST",
                        url: "../controllers/prof_create_preg.php",
                        data: new FormData($("form#formAdd")[0]),
                        //data: $('form#formAdd').serialize(),
                        contentType: false,
                        processData: false,
                        success: function(msg){
                            console.log(msg);
                            var msg = jQuery.parseJSON(msg);
                            if(msg.error == 0){
                                //console.log(msg);
                                $('#loading').empty();
                                $('#loading').append('<img src="../assets/obj/success_256.png" height="300" width="400" >');
                                setTimeout(function () {
                                  location.href = 'prof_create_preg.php?idExam=<?=$idExam?>';
                                }, 1500);
                            }else{
                                $('#loading').empty();
                                $('#loading').append('<img src="../assets/obj/error.png" height="300" width="400" ><p>'+msg.msgErr+'</p>');
                                setTimeout(function () {
                                  //location.reload;
                                  $('#loading').hide();
                                }, 1500);
                            }
                        }, error: function(){
                            alert("Error");
                        }
                    });
                }
            })
             
        });
        </script>
        <script type="text/javascript">
            //añadir nuevos campos pzara materias y profesores
            var lineCols = new Array(null, 1);
            var maxInputs = 10;
            var contenedor = $("#contenedor");
            var contenedorPregs = $("#contenedorPregs");
            var addCampo = $("#addCampo");
            var x = 1;
            var FieldCount = x-1;
            var respType = 0;
            
            $(contenedor).on("change", "#respType", function(e){
                var opc = $(this).val();
                respType = opc;
                $(contenedorPregs).empty();
                x = 1;
                lineCols = new Array(null, 1);
            })
            
            // Añadir campos dinamicos sobre campos dinamicos
            // http://jsfiddle.net/1ryvy98r/4/
            $(contenedor).on("click", ".addResp", function(e){
                var nline = 0; x++;
                var nCols = (++lineCols[nline]);
                var respu = '';
                if(respType == 1){ 
                    respu = '<div class="row"><div class="col-sm-6">';
                        respu += '<label for="inputResp">Respuesta</label>';
                        respu += '<input type="text" class="form-control" id="input1Resp" name="input1Resp[]">';
                    respu += '</div><!-- end col-sm-6 -->';
                    respu += '<div class="col-sm-4">';
                            respu += '<label for="inputResp">Imagen</label>';
                            respu += '<input type="file" class="form-control" id="input1File" name="input1File[]">';
                    respu += '</div><!-- end col-sm-6 -->';
                    respu += '<div class="col-sm-2">';
                        respu += '<label for="inputResp">¿Correcta?</label>';
                        respu += '<input type="radio" class="form-control" name="input1Radio[]" id="input1Radio" value="'+nCols+'" required ">'+nCols;
                    respu += '</div></div>'; 
                }
                else if(respType == 2){ 
                    respu = '<div class="row"><div class="col-sm-6">';
                        respu += '<label for="inputResp">Respuesta</label>';
                        respu += '<input type="text" class="form-control" id="input2Resp" name="input2Resp[]">';
                    respu += '</div><!-- end col-sm-6 -->';
                    respu += '<div class="col-sm-4">';
                            respu += '<label for="inputResp">Imagen</label>';
                            respu += '<input type="file" class="form-control" id="input2File" name="input2File[]">';
                    respu += '</div><!-- end col-sm-6 -->';
                    respu += '<div class="col-sm-2">';
                        respu += '<label for="inputResp">¿Correcta?</label>';
                        respu += '<input type="checkbox" class="form-control" name="input2Check[]" id="input2Check" value="'+nCols+'" required>'+nCols;
                    respu += '</div></div>'; 
                }
                else if(respType == 3) { 
                    respu = '<div class="row"><div class="col-sm-12">';
                        respu += '<label for="inputResp">Palabras que debe contener (separadas por coma)</label>';
                        respu += '<input type="text" class="form-control" id="inputResp" name="inputResp" required>';
                    respu += '</div></div><!-- end col-sm-12 -->';
                }else if(respType == 4){
                    respu = '<div class="row"><div class="col-sm-12">';
                        respu += '<label for="inputResp">Respuesta exacta</label>';
                        respu += '<input type="text" class="form-control" id="inputResp" name="inputResp" required>';
                    respu += '</div></div><!-- end col-sm-12 -->';
                }
                else respu = '<div>Other '+nline+'</div>'; 
                //$(this).parent('div').append(respu);
                $(contenedorPregs).append(respu);
                return false;
            })
            //Obtenemos valor del input radio
            $(contenedor).on("click", "#input1Radio", function(e){
                var rad = $(this).val();
                //alert(rad);
            });
    
            $(contenedor).on("click",".eliminar", function(e){
                alert("eliminando..."+$(this).parent().parent().parent()+"--"+x);
               if(x > 0){
                   $(this).parent().parent().remove();
                   x--;
                }
                return false;
            });
           
    </script>
    
<?php
    }//end if-else
    include ('footer.php');
?>