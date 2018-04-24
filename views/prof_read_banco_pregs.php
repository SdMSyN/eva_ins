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
        
        //Obtenemos los tipos de respuesta
        $optTypeResp = '<option></option>';
        $optTypeResp .= '<option value="1">Opción multiple</option>';
        $optTypeResp .= '<option value="2">Multiopción Multirespuesta</option>';
        $optTypeResp .= '<option value="3">Respuesta abierta</option>';
        $optTypeResp .= '<option value="4">Respuesta exacta</option>';
        //Optenemos los creadores
        $sqlGetCreadores = "SELECT DISTINCT $tBPregs.creado_por_id, $tBPregs.perfil_creador "
                . "FROM $tBPregs WHERE $tBPregs.compartir=1 AND $tBPregs.banco_materia_id='$idMateria' ";
        $resGetCreadores = $con->query($sqlGetCreadores);
        $optCreador = '<option></option>';
        if($resGetCreadores->num_rows > 0){
            while($rowGetCreador = $resGetCreadores->fetch_assoc()){
                $idCreador = $rowGetCreador['creado_por_id'];
                $idPerfil = $rowGetCreador['perfil_creador'];
                $tableUser = ($idPerfil == 10) ? $tAdm : $tProf;
                $sqlGetNameCreador = "SELECT nombre FROM $tableUser WHERE id='$idCreador' ";
                $resGetNameCreador = $con->query($sqlGetNameCreador);
                $rowGetNameCreador = $resGetNameCreador->fetch_assoc();
                $optCreador .= ($idPerfil == 10) ? '<option value="'.$idCreador.'">Plataforma</option>' : '<option value="'.$idCreador.'">'.$rowGetNameCreador['nombre'].'</option>';
            }
        }
        //Obtenemos los bloques de la materia
        $sqlGetBloques = "SELECT id, nombre FROM $tBBloq WHERE banco_materia_id='$idMateria' ";
        $resGetBloques = $con->query($sqlGetBloques);
        $optBloque = '<option></option>';
        while($rowGetBloque = $resGetBloques->fetch_assoc()){
            $optBloque .= '<option value="'.$rowGetBloque['id'].'">'.$rowGetBloque['nombre'].'</option>';
        }
        
?>

    <div class="container">
        <div class="row">
            <div id="loading">
                <img src="../assets/obj/loading.gif" height="300" width="400">
            </div>
        </div>
        
        <div class="row">
            <form id="frm_filtro" method="post" action="" class="form-horizontal">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <input type="hidden" name="inputMateria" value="<?=$idMateria;?>" >
                            <label class="col-sm-4 control-label" for="inputNombre">Nombre</label>
                            <div class="col-sm-8"><input type="text" class="form-control" name="inputNombre" id="inputNombre"></div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="inputTypeResp">Tipo de respuesta</label>
                            <div class="col-sm-8">
                                <select id="inputTypeResp" name="inputTypeResp" class="form-control">
                                    <?=$optTypeResp;?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="inputCreador">Creador</label>
                            <div class="col-sm-8">
                                <select id="inputCreador" name="inputCreador" class="form-control">
                                    <?=$optCreador;?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div><!-- end row -->
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="inputBloque">Bloques</label>
                            <div class="col-sm-8">
                                <select id="inputBloque" name="inputBloque" class="form-control">
                                    <?=$optBloque;?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="inputTema">Temas</label>
                            <div class="col-sm-8">
                                <select id="inputTema" name="inputTema" class="form-control"></select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="inputSubtema">Subtemas</label>
                            <div class="col-sm-8">
                                <select id="inputSubtema" name="inputSubtema" class="form-control"></select>
                            </div>
                        </div>
                    </div>
                </div><!-- end row -->
                <div class="col-sm-offset-5 col-sm-7">
                    <button type="button" id="btnfiltrar" class="btn btn-success">Filtrar <span class="glyphicon glyphicon-filter"></span></button>
                    <a href="javascript:;" id="btncancel" class="btn btn-default">Todos</a>
                </div>
            </form>
        </div>
        
        <form id="formAddPreg">
            <div class="row text-center"><br>
                <button class="btn btn-primary" type="submit">Añadir a examen</button>
                <input type="hidden" name="inputIdExam" value="<?=$idExam;?>" >
            </div>
            <div class="table-responsive">
                <table class="table table-striped" id="data">
                    <caption>Tus exámenes</caption>
                    <thead>
                        <tr>
                            <th><label for="checkTodos"><input type="checkbox" id="checkTodos" ></label></th>
                            <th><span title="id">Id</span></th>
                            <th><span title="nombre">Nombre</span></th>
                            <th><span title="valor_preg">Valor pregunta</span></th>
                            <th><span title="tipo_resp">Tipo de respuesta</span></th>
                            <th><span title="creado_por_id">Creador</span></th>
                            <th>Ver pregunta</th>
                        </tr>
                    </thead>
                        <tbody></tbody>
                </table>
            </div>
        </form>
        
        <!-- Modal para ver preguntas  -->
        <div class="modal fade" id="modalViewPreg" tabindex="-1" role="dialog" aria-labellebdy="myModal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
                        </button>
                        <h4 class="modal-title" id="exampleModalLabel">Pregunta:</h4>
                        <p class="msgModal"></p>
                    </div>
                    <div class="modal-body">
                        <div class="row textPreg"></div>
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
                   data: $("#frm_filtro").serialize()+ordenar,
                   url: "../controllers/prof_read_banco_pregs.php?idExam="+<?=$idExam;?>+"&idUser="+<?=$idUser;?>,
                   success: function(msg){
                       //alert(msg);
                       console.log(msg);
                       var msg = jQuery.parseJSON(msg);
                       if(msg.error == 0){
                           $("#data tbody").html("");
                           $.each(msg.dataRes, function(i, item){
                               var newRow = '<tr>'
                                    +'<td><input type="checkbox" id="checkIdPreg" '
                                        +'name="checkIdPreg[]" value="'+msg.dataRes[i].id+'" ></td>'
                                    +'<td>'+msg.dataRes[i].id+'</td>'   
                                    +'<td>'+msg.dataRes[i].nombre+'</td>'   
                                    +'<td>'+msg.dataRes[i].valorPreg+'</td>';
                                if(msg.dataRes[i].tipoResp == 1) newRow += '<td>Opción Multiple</td>';
                                else if(msg.dataRes[i].tipoResp == 2) newRow += '<td>Multiopción Multirespuesta</td>';
                                else if(msg.dataRes[i].tipoResp == 3) newRow += '<td>Respuesta abierta</td>';
                                else if(msg.dataRes[i].tipoResp == 4) newRow += '<td>Respuesta exacta</td>';
                                else newRow += '<td></td>';
                                    
                                    newRow += '<td>'+msg.dataRes[i].creadorNombre+'</td>'
                                    +'<td><button type="button" class="btn btn-default" id="viewPreg" value="'+msg.dataRes[i].id+'" data-toggle="modal" data-target="#modalViewPreg"><span class="glyphicon glyphicon-eye-open"></span></button></td>' 
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
           
           //Ordenar por formulario
            $("#btnfiltrar").click(function(){ 
                filtrar();
            });
        
            // boton cancelar
            $("#btncancel").click(function(){ 
                //$("#frm_filtro #calle").find("option[value='0']").attr("selected",true);
                $("#frm_filtro #inputNombre").val('');
                $("#frm_filtro #inputTypeResp").val('');
                $("#frm_filtro #inputCreador").val('');
                $("#frm_filtro #inputBloque").val('');
                $("#frm_filtro #inputTema").val('');
                $("#frm_filtro #inputSubtema").val('');
                filtrar() 
            });
                
           //Obtener Temas apartir del bloque
            $("#inputBloque").on("change", function(){
                $.ajax({
                    url:"../controllers/get_temas.php?id="+$("#inputBloque").val(),
                    type: "POST",
                    success: function(opciones){
                        var msg = jQuery.parseJSON(opciones);
                        if(msg.error == 0){
                            $("#inputTema").html("");
                            $("#inputSubtema").html("");
                            $("#inputTema").html('<option></option>');
                            $.each(msg.dataRes, function(i, item){
                                var newOpt = '<option value="'+msg.dataRes[i].id+'">'+msg.dataRes[i].nombre+'</option>';
                                $(newOpt).appendTo("#inputTema");
                            });
                        }else{
                            $("#inputTema").html("");
                            $("#inputSubtema").html("");
                            $("#inputTema").html("<option>"+msg.msgErr+"</option>");
                        }
                    }
                })
            });
            
            //Obtener SubTemas apartir del Tema
            $("#inputTema").on("change", function(){
                $.ajax({
                    url:"../controllers/get_subtemas.php?id="+$("#inputTema").val(),
                    type: "POST",
                    success: function(opciones){
                        var msg = jQuery.parseJSON(opciones);
                        if(msg.error == 0){
                            $("#inputSubtema").html("");
                            $("#inputSubtema").html('<option></option>');
                            $.each(msg.dataRes, function(i, item){
                                var newOpt = '<option value="'+msg.dataRes[i].id+'">'+msg.dataRes[i].nombre+'</option>';
                                $(newOpt).appendTo("#inputSubtema");
                            });
                        }else{
                            $("#inputSubtema").html("");
                            $("#inputSubtema").html("<option>"+msg.msgErr+"</option>");
                        }
                    }
                })
            });
            
            //Marcar/desmarcar Todos checkbox
            $("#checkTodos").change(function () {
                $("input:checkbox").prop('checked', $(this).prop("checked"));
            });
                
            
            
            $('#formAddPreg').validate({
                rules: {
                    'checkIdPreg[]': {required: true}
                },
                messages: {
                    'checkIdPreg[]': "Tu examen no puede ir vacio."
                },
                tooltip_options: {
                    'checkIdPreg[]': {trigger: "focus", placement: "bottom"}
                },
                submitHandler: function(form){
                    $('#loading').show();
                    $.ajax({
                        type: "POST",
                        url: "../controllers/prof_create_exa_preguntas.php",
                        data: $('form#formAddPreg').serialize(),
                        success: function(msg){
                            console.log(msg);
                            var msg = jQuery.parseJSON(msg);
                            if(msg.error == 0){
                                $('#loading').empty();
                                $('#loading').append('<img src="../assets/obj/success_256.png" height="300" width="400" >');
                                setTimeout(function () {
                                  location.href =  'prof_read_exams.php';
                                }, 1500);
                            }else{
                                $('#loading').empty();
                                $('#loading').append('<img src="../assets/obj/error.png" height="300" width="400" ><p>'+msg.msgErr+'</p>');
                                setTimeout(function () {
                                  $('#loading').hide();
                                }, 1500);
                            }
                        }, error: function(){
                            alert("Error al añadir pregunta(s)");
                        }
                    });
                }
            }); 
            
            //Cargar pregunta
            $("#data").on("click", "#viewPreg", function(){
                var idPreg = $(this).val();
                $.ajax({
                    type: "POST",
                    url: "../controllers/admin_read_banco_pregunta.php?idPreg="+idPreg,
                    success: function(msg){
                        var msg = jQuery.parseJSON(msg);
                        if(msg.error == 0){
                            var newRow = '';
                            $("#modalViewPreg .modal-body .textPreg").html("");
                            $.each(msg.dataPregs, function(i, item){
                                var newPreg = '<div class="row"><div class="col-sm-12 text-center">'
                                        +'<p class="text-center">'+msg.dataPregs[i].nombre+'</p>'
                                    +'</div></div>';
                                if(msg.dataPregs[i].archivo != null){ 
                                    var splitFile = msg.dataPregs[i].archivo;
                                    var extFile = splitFile.split(".");
                                    //console.log(splitFile+'--'+extFile[1]);
                                    if(extFile[1] == "mp3"){
                                        newPreg += '<div class="row">'
                                            +'<audio src="../<?=$filesExams;?>/'+msg.dataPregs[i].archivo+'" preload="auto" controls class="center-block"></audio>'
                                            +'</div>';
                                    }else{
                                        newPreg += '<div class="row">'
                                            +'<img src="../<?=$filesExams;?>/'+msg.dataPregs[i].archivo+'" class="img-responsive center-block" width="60%">'
                                            +'</div>';
                                    }
                                }
                                $(newPreg).appendTo("#modalViewPreg .modal-body .textPreg");
                                $.each(msg.dataPregs[i].resps, function(j, item2){
                                    var newResp = '';
                                    if(msg.dataPregs[i].resps[j].tipoR == 1){
                                        newResp += '<div class="col-sm-6 text-center">';
                                        console.log(msg.dataPregs[i].resps[j].archivo);
                                        if(msg.dataPregs[i].resps[j].archivo != null){
                                            newResp += '<img src="../<?=$filesExams;?>/'+msg.dataPregs[i].resps[j].archivo+'" class="img-responsive center-block" >';
                                        }
                                        newResp += '<label>'+msg.dataPregs[i].resps[j].nombre+'</label>';
                                        newResp += (msg.dataPregs[i].resps[j].respCorr == 1) ? '<input type="radio" class="form-control" name="radio[]" id="radio" value="'+msg.dataPregs[i].resps[j].id+'" checked disabled>' : '<input type="radio" class="form-control" name="radio[]" id="radio" value="'+msg.dataPregs[i].resps[j].id+'" disabled>';
                                        newResp += '</div>';
                                    }else if(msg.dataPregs[i].resps[j].tipoR == 2){
                                        newResp += '<div class="col-sm-6 text-center">';
                                        if(msg.dataPregs[i].resps[j].archivo != null){
                                            newResp += '<img src="../<?=$filesExams;?>/'+msg.dataPregs[i].resps[j].archivo+'" class="img-responsive center-block" >';
                                        }
                                        newResp += '<label>'+msg.dataPregs[i].resps[j].nombre+'</label>';
                                        newResp += (msg.dataPregs[i].resps[j].respCorr == 1) ? '<input type="checkbox" class="form-control" name="check[]" id="check" value="'+msg.dataPregs[i].resps[j].id+'" checked disabled>' : '<input type="checkbox" class="form-control" name="check[]" id="check" value="'+msg.dataPregs[i].resps[j].id+'" disabled>';
                                        newResp += '</div>';
                                    }else if(msg.dataPregs[i].resps[j].tipoR == 3){
                                        newResp += '<div class="col-sm-12">';
                                            //newResp += (msg.dataPregs[i].resps[j].respCorr == 1) ? '<input type="text" class="form-control" name="text[]" id="text" value="'+msg.dataPregs[i].resp[j].palabra+'">' : '<input type="text" class="form-control" name="text[]" id="text" >';
                                            newResp += '<input type="text" class="form-control" name="text[]" id="text" value="'+msg.dataPregs[i].resps[j].palabra+'" disabled>';
                                        newResp += '</div>';
                                    }else if(msg.dataPregs[i].resps[j].tipoR == 4){
                                        newResp += '<div class="col-sm-12">';
                                            //newResp += (msg.dataPregs[i].resps[j].respCorr == 1) ? '<input type="text" class="form-control" name="text[]" id="text" value="'+msg.dataPregs[i].resp[j].palabra+'">' : '<input type="text" class="form-control" name="text[]" id="text" >';
                                            newResp += '<input type="text" class="form-control" name="text[]" id="text" value="'+msg.dataPregs[i].resps[j].palabra+'" disabled>';
                                        newResp += '</div>';
                                    }else{
                                        newResp += '<div class="row">Tipo de respuesta inexistente.</div>';
                                    }
                                    //newResp += '</div><!-- end row -->';
                                    $(newResp).appendTo("#modalViewPreg .modal-body .textPreg");
                                })
                           });
                        }else{
                            var newRow = msg.msgErr;
                            $(newRow).appendTo("#modalViewPreg .modal-body .textPreg");
                        }
                    }
                });
            });
            
        });
    </script>
    
<?php
    }//end if-else
    include ('footer.php');
?>