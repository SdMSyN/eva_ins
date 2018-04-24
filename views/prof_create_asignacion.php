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
            
        //Obtenemos los grupos del profesor
        $sqlGetGrupos = "SELECT DISTINCT $tGMatProfs.grupo_info_id as idGrupo, "
                . "$tGrupo.nombre as grupo, $tGrado.nombre as grado  "
                . "FROM $tGMatProfs "
                . "INNER JOIN $tGrupo ON $tGrupo.id=$tGMatProfs.grupo_info_id "
                . "INNER JOIN $tGrado ON $tGrado.id=$tGrupo.nivel_grado_id "
                . "WHERE $tGMatProfs.usuario_profesor_id='$idUser' ";
        $resGetGrupos = $con->query($sqlGetGrupos);
        $optGrupos = '<option></option>';
        if($resGetGrupos->num_rows > 0){
            while($rowGetGrupos = $resGetGrupos->fetch_assoc()){
                $optGrupos .= '<option value="'.$rowGetGrupos['idGrupo'].'">'
                        .$rowGetGrupos['grado'].' - '.$rowGetGrupos['grupo'].'</option>';
            }
        }else{
            $optGrupos .= '<option>No tienes materias</option>';
        }
        
        //Horas
        $optH = '';
        for($i = 0; $i < 6; $i++){
            $optH .= '<option value="'.$i.'">'.$i.'</option>';
        }
        //Minutos
        $optM = '';
        for($i = 5; $i < 60; $i+=5){
            $optM .= '<option value="'.$i.'">'.$i.'</option>';
        }
        
?>

    <div class="container">
        <div class="row">
            <div id="loading">
                <img src="../assets/obj/loading.gif" height="300" width="400">
            </div>
        </div>
        
        <div class="row">
            <form id="formSearchAlums" class="form-horizontal">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="inputGrupo">Grupo</label>
                            <div class="col-sm-8">
                                <select id="inputGrupo" name="inputGrupo" class="form-control">
                                    <?=$optGrupos;?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="inputMat">Materia</label>
                            <div class="col-sm-8">
                                <select id="inputMat" name="inputMat" class="form-control"></select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                    <button type="submit" id="formSearchAlums" class="btn btn-success">
                        Buscar <span class="glyphicon glyphicon-filter"></span>
                    </button>
                </div>
                </div><!-- end row -->
            </form>
        </div>
        
        <form id="formAddAsig" class="form-horizontal">
            <fieldset>
                <legend>Información</legend>
                <div class="row">
                    <div class="col-sm-8">
                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="inputNombreAsig">Nombre Asignación</label>
                            <div class="col-sm-8">
                                <input typ="text" id="inputNombreAsig" name="inputNombreAsig" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="inputMat">¿Preguntas aleatorias?</label>
                            <div class="col-sm-8">
                                <input type="checkbox" class="form-control" id="inputAle" name="inputAle" >
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>
            <fieldset>
                <legend>Tiempo</legend>
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="inputBeginF">Fecha de inicio</label>
                            <div class="col-sm-8">
                                <input type="date" class="form-control" id="inputBeginF" name="inputBeginF" >
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="inputBeginH">Hora de inicio</label>
                            <div class="col-sm-8">
                                <input type="time" class="form-control" id="inputBeginH" name="inputBeginH" >
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="inputEndF">Fecha de finalización</label>
                            <div class="col-sm-8">
                                <input type="date" class="form-control" id="inputEndF" name="inputEndF" >
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="inputEndH">Hora de finalización</label>
                            <div class="col-sm-8">
                                <input type="time" class="form-control" id="inputEndH" name="inputEndH" >
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="inputH">¿Cuántas horas durará el examen?</label>
                            <div class="col-sm-8">
                                <select class="form-control" id="inputH" name="inputH" ><?=$optH;?></select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="inputM">¿Cuántos minutos durará el examen?</label>
                            <div class="col-sm-8">
                                <select class="form-control" id="inputM" name="inputM" ><?=$optM;?></select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="inputShowResult">Mostrar retroalimentación ¿cuándo?</label>
                            <div class="col-sm-8">
                                <select class="form-control" id="inputShowResult" name="inputShowResult" >
                                    <option value="0">Al concluir examen</option>
                                    <option value="1">Al finalizar fecha de asignación</option>
                                    <option value="2">1 día después de la finalización</option>
                                    <option value="3">1 semana después de la finalización</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>
            <div class="row text-center"><br>
                <button class="btn btn-primary" type="submit" disabled id="buttonAsig">Asignar</button>
                <input type="hidden" name="inputIdExam" value="<?=$idExam;?>" >
                <input type="hidden" name="inputGrupoId" id="inputGrupoId" >
                <input type="hidden" name="inputIdGrupoMatProf" id="inputIdGrupoMatProf" >
            </div>
            <div class="table-responsive">
                <table class="table table-striped" id="data">
                    <caption>Alumnos del Grupo</caption>
                    <thead>
                        <tr>
                            <th><label for="checkTodos"><input type="checkbox" id="checkTodos" ></label></th>
                            <th><span title="id">Id</span></th>
                            <th><span title="nombre">Nombre</span></th>
                        </tr>
                    </thead>
                        <tbody></tbody>
                </table>
            </div>
        </form>
    </div>

    <script type="text/javascript">
        $('#loading').hide();
        var ordenar = '';
        $(document).ready(function(){
                
           //Obtener materias a partir de grupo
            $("#inputGrupo").on("change", function(){
                $.ajax({
                    url:"../controllers/prof_read_materias_por_grupo.php?idGrupo="+$("#inputGrupo").val()+"&idProf=<?=$idUser;?>",
                    type: "POST",
                    success: function(opciones){
                        console.log(opciones);
                        var msg = jQuery.parseJSON(opciones);
                        if(msg.error == 0){
                            $("#inputMat").html("");
                            $("#inputMat").html('<option></option>');
                            $.each(msg.dataRes, function(i, item){
                                var newOpt = '<option value="'+msg.dataRes[i].id+'">'+msg.dataRes[i].mat+'</option>';
                                $(newOpt).appendTo("#inputMat");
                            });
                        }else{
                            $("#inputMat").html("");
                            $("#inputMat").html("<option>"+msg.msgErr+"</option>");
                        }
                    }
                })
            });


            $('#formSearchAlums').validate({
                rules: {
                    inputGrupo: {required: true},
                    inputMat: {required: true}
                },
                messages: {
                    inputGrupo: "Selecciona un grupo",
                    inputMat: "¿Qué materia impartes en ese grupo?"
                },
                tooltip_options: {
                    inputGrupo: {trigger: "focus", placement: "bottom"},
                    inputMat: {trigger: "focus", placement: "bottom"}
                },
                submitHandler: function(form){
                    $('#loading').show();
                    $.ajax({
                        type: "POST",
                        url: "../controllers/prof_read_grupo_alumnos.php",
                        data: $('form#formSearchAlums').serialize(),
                        success: function(msg){
                            console.log(msg);
                            var msg = jQuery.parseJSON(msg);
                            if(msg.error == 0){
                                $('#loading').empty();
                                $('#loading').append('<img src="../assets/obj/success_256.png" height="300" width="400" >');
                                setTimeout(function () {
                                  $('#loading').hide();
                                }, 1500);
                                //Seteos Asignación
                                $("#inputGrupoId").val($("#inputGrupo").val());
                                $("#inputIdGrupoMatProf").val($("#inputMat").val());
                                $("#inputBeginF").val("<?=$dateNow;?>");
                                $("#inputBeginH").val("00:01");
                                $("#inputEndF").val("<?=$dateNow;?>");
                                $("#inputEndH").val("23:59");
                                $('#buttonAsig').attr("disabled", false);
                                $("#data tbody").html("");
                                $.each(msg.dataRes, function(i, item){
                                    var newRow = '<tr>'
                                        +'<td><input type="checkbox" id="checkIdAlum" '
                                            +'name="checkIdAlum[]" value="'+msg.dataRes[i].id+'" ></td>'
                                        +'<td>'+msg.dataRes[i].id+'</td>'
                                        +'<td>'+msg.dataRes[i].nombre+'</td>'
                                        +'</tr>';
                                    $(newRow).appendTo("#data tbody");
                                });
                            }else{
                                $('#loading').empty();
                                $('#loading').append('<img src="../assets/obj/error.png" height="300" width="400" ><p>'+msg.msgErr+'</p>');
                                setTimeout(function () {
                                  $('#loading').hide();
                                }, 1500);
                            }
                        }, error: function(){
                            alert("Error al buscar alumnos.");
                        }
                    });
                }
            }); 
            
            //Marcar/desmarcar Todos checkbox
            $("#checkTodos").change(function () {
                $("#data input:checkbox").prop('checked', $(this).prop("checked"));
            });
            
            jQuery.validator.addMethod("dateRange", function() {
                var date1 = new Date($("#inputBeginF").val());
                var date2 = new Date($("#inputEndF").val());
                return (date1 <= date2);
            }, "Please check your dates. The start date must be before the end date.");
            $('#formAddAsig').validate({
                rules: {
                    inputNombreAsig: {required: true},
                    inputBeginF: {required: true},
                    inputBeginH: {required: true},
                    inputEndF: {required: true, dateRange: true},
                    inputEndH: {required: true},
                    inputH: {required: true},
                    inputM: {required: true},
                    'checkIdAlum[]': {required: true}
                },
                messages: {
                    inputNombreAsig: "Nombre de asginación obligatorio",
                    inputBeginF: "Debes escoger que día aplicarán",
                    inputBeginH: "Por defecto te hemos puesto el inicio del día",
                    inputEndF:{ 
                        required: "¿Algún día debe de terminar, no crees?",
                        dateRange: "El fin no puede ser antes del inicio"
                    },
                    inputEndH: "La hora de finalización no puede ir vacía",
                    inputH: "Esto no puede ir vacio",
                    inputM: "Esto no puede ir vacio",
                    'checkIdAlum[]': "Tu examen no puede ir vacio."
                },
                tooltip_options: {
                    inputBeginF: {trigger: "focus", placement: "bottom"},
                    inputBeginH: {trigger: "focus", placement: "bottom"},
                    inputEndF: {trigger: "focus", placement: "bottom"},
                    inputEndH: {trigger: "focus", placement: "bottom"},
                    inputH: {trigger: "focus", placement: "bottom"},
                    inputM: {trigger: "focus", placement: "bottom"},
                    'checkIdAlum[]': {trigger: "focus", placement: "bottom"}
                },
                submitHandler: function(form){
                    $('#loading').show();
                    $.ajax({
                        type: "POST",
                        url: "../controllers/prof_create_asignacion.php",
                        data: $('form#formAddAsig').serialize(),
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
        });
    </script>
    
<?php
    }//end if-else
    include ('footer.php');
?>