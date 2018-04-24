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

        //Obtenemos los grados en base al nivel escolar
        $sqlGetGrados = "SELECT id, nombre FROM $tGrado WHERE nivel_escolar_id='$idNivelEsc' ";
        $resGetGrados = $con->query($sqlGetGrados);
        $optGrados = '<option></option>';
        while($rowGetGrado = $resGetGrados->fetch_assoc()){
            $optGrados .= '<option value="'.$rowGetGrado['id'].'">'.$rowGetGrado['nombre'].'</option>';
        }
        
        //Obtenemos los profesores de la escuela
        $sqlGetProfes = "SELECT id, nombre FROM $tProf WHERE escuela_id='$idUser' ";
        $resGetProfes = $con->query($sqlGetProfes);
        $optProf = '<option></option>';
        if($resGetProfes->num_rows > 0){
            while($rowGetProfes = $resGetProfes->fetch_assoc()){
                $optProf .= '<option value="'.$rowGetProfes['id'].'">'.$rowGetProfes['nombre'].'</option>';
            }
        }else{
            $optProf .= '<option>No existen maestros</option>';
        }
?>

    <div class="container">
        <div class="row">
            <div id="loading">
                <img src="../assets/obj/loading.gif" height="300" width="400">
            </div>
        </div>
        <div class="row text-center"><h1>Grupos</h1></div>
        <div class="row placeholder text-center">
            <div class="col-sm-12 placeholder">
                <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#modalAdd">
                    Añadir nuevo grupo
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
                        <th><span title="grado">Grado</span></th>
                        <th><span title="nombre">Grupo</span></th>
                        <th><span title="turno">Turno</span></th>
                        <th>Ver alumnos</th>
                        <th>Ver materias</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        
        <!-- Modal para ver materias  -->
        <div class="modal fade" id="modalViewMats" tabindex="-1" role="dialog" aria-labellebdy="myModal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
                        </button>
                        <h4 class="modal-title" id="exampleModalLabel">Ver materias</h4>
                        <p class="msgModal"></p>
                    </div>
                    <div class="modal-body">
                        <div class="row text-center">
                            <button type="button" class="btn btn-primary" id="addMat" data-toggle="modal" data-target="#modalAddMat">
                                Asignar materia
                            </button>
                        </div>
                        <br>
                        <table class="table table-striped matsProfs">
                            <thead>
                                <tr><th>Nombre Materia</th><th>Nombre Profesor</th><th>Actualizar</th></tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Modal para actualizar asignación de materia y profesor -->
        <div class="modal fade" id="modalUpdMat" tabindex="-1" role="dialog" aria-labellebdy="myModal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
                        </button>
                        <h4 class="modal-title" id="exampleModalLabel">Actualizar materia</h4>
                        <p class="msgModal"></p>
                    </div>
                    <form id="formUpdMat" name="formUpdMat">
                        <div class="modal-body">
                            <div class="form-group">
                                <input type="hidden" name="inputIdGMatProf" id="inputIdGMatProf" >
                            </div>
                            <div class="col-sm-5">
                                <label for="inputMat1">Materia: </label>
                                <select class="form-control materia" id="inputMat" name="mat"></select>
                            </div>
                            <div class="col-sm-6">
                                <label for="inputProf1">Profesor: </label>
                                <select class="form-control" id="inputProf" name="prof"><?=$optProf;?></select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Actualizar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Modal para añadir materia  -->
        <div class="modal fade" id="modalAddMat" tabindex="-1" role="dialog" aria-labellebdy="myModal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
                        </button>
                        <h4 class="modal-title" id="exampleModalLabel">Asignar nueva materia</h4>
                        <p class="msgModal"></p>
                    </div>
                    <form id="formAddMat" name="formAddMat">
                        <div class="modal-body">
                            <div class="form-group">
                                <input type="text" name="inputGrupo" id="inputGrupo" >
                                <input type="hidden" name="inputIdEsc" value="<?= $idUser; ?>" >
                            </div>
                            <div class="col-sm-5">
                                <label for="inputMat1">Materia: </label>
                                <select class="form-control materia" id="inputMat" name="mat"></select>
                            </div>
                            <div class="col-sm-6">
                                <label for="inputProf1">Profesor: </label>
                                <select class="form-control" id="inputProf" name="prof"><?=$optProf;?></select>
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
        
        <!-- Modal para añadir clase -->
        <div class="modal fade" id="modalAdd" tabindex="-1" role="dialog" aria-labellebdy="myModal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
                        </button>
                        <h4 class="modal-title" id="exampleModalLabel">Añadir nuevo grupo</h4>
                        <p class="msgModal"></p>
                    </div>
                    <form id="formAdd" name="formAdd">
                        <div class="modal-body">
                            <div class="form-group">
                                <input type="hidden" name="inputIdUser" value="<?= $idUser; ?>" >
                                <input type="hidden" name="inputNivel" value="<?= $idNivelEsc; ?>">
                            </div>
                            <div class="form-group">
                                <label for="inputGrado">Grado: </label>
                                <select class="form-control" id="inputGrado" name="inputGrado"><?=$optGrados;?></select>
                            </div>
                            <div class="form-group">
                                <label for="inputGrupo">Grupo: </label>
                                <input type="text" class="form-control" id="inputGrupo" name="inputGrupo" >
                            </div>
                            <div class="form-group">
                                <label for="inputTurno">Turno: </label>
                                <label class="radio-inline">
                                    <input type="radio" name="inputTurno" id="inputTurno" value="1"> Matutino
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="inputTurno" id="inputTurno" value="2"> Vespertino
                                </label>
                            </div>
                            <br>
                            <div class="form-group">
                                <label for=""></label>
                                <a id="addCampo" class="btn btn-info" href="#">Añadir nueva materia</a>
                            </div>
                            <div id="contenedor">
                                <div class="row">
                                    <div class="col-sm-5">
                                        <label for="inputMat1">Materia: </label>
                                        <select class="form-control materia" id="inputMat1" name="mat[]"></select>
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="inputProf1">Profesor: </label>
                                        <select class="form-control" id="inputProf1" name="prof[]"><?=$optProf;?></select>
                                    </div>
                                    <div class="col-sm-1">
                                        <a href="#" class="eliminar">&times;</a>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="form-group">
                                <label for="inputFile">Archivo CSV 
                                    <a href="#" data-toggle="tooltip" title="Archivo Excel en formato CSV (archivo separado por comas), 3 o 4 campos: Apellido paterno, Apellido Materno, Nombre(s) y Usuario [opcional]">
                                        <span class="glyphicon glyphicon-question-sign"></span>
                                    </a>
                                    <a href="../uploads/plantillaGrupo.csv" data-toggle="tooltip" title="Descargar formato">
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
        
    </div>

        
    <script type="text/javascript">
        function getMaterias(){
            $.ajax({
                url:"../controllers/get_materias.php?idNivel="+<?=$idNivelEsc;?>+"&idGrado="+$("#inputGrado").val(),
                type: "POST",
                success: function(opciones){
                    var msg = jQuery.parseJSON(opciones);
                    if(msg.error == 0){
                        $("#modalAdd .materia").html("<option></option>");
                        $.each(msg.dataRes, function(i, item){
                            var newOpt = '<option value="'+msg.dataRes[i].id+'">'+msg.dataRes[i].nombre+'</option>';
                            $(newOpt).appendTo("#modalAdd .materia");
                        });
                    }else{
                        $("#modalAdd #inputMat1").html("");
                        $("#modalAdd #inputMat1").html("<option>"+msg.msgErr+"</option>");
                    }
                }
            })
        }
    </script>
    <script type="text/javascript">
        $('#loading').hide();
        var ordenar = '';
        $(document).ready(function(){
           filtrar();
           function filtrar(){
               $.ajax({
                   type: "POST",
                   data: ordenar, 
                   url: "../controllers/get_grupos.php?idEsc="+<?=$idUser;?>,
                   success: function(msg){
                       var msg = jQuery.parseJSON(msg);
                       if(msg.error == 0){
                           //alert(msg.dataRes[0].id);
                           $("#data tbody").html("");
                           $.each(msg.dataRes, function(i, item){
                               var newRow = '<tr>'
                                    +'<td>'+msg.dataRes[i].id+'</td>'   
                                    +'<td>'+msg.dataRes[i].grado+'</td>'   
                                    +'<td>'+msg.dataRes[i].nombre+'</td>' 
                                    +'<td>'+msg.dataRes[i].turno+'</td>' 
                                    +'<td><a href="esc_read_grupo_alumno.php?idGrupo='+msg.dataRes[i].id+'" class="btn btn-default"><span class="glyphicon glyphicon-list"></span></a></td>'
                                    //+'<td><a href="esc_read_grupo_materias.php?idGrupo='+msg.dataRes[i].id+'"><span class="glyphicon glyphicon-list"></span></a></td>'
                                    +'<td><button type="button" class="btn btn-default" id="viewMats" value="'+msg.dataRes[i].id+'" data-toggle="modal" data-target="#modalViewMats"><span class="glyphicon glyphicon-book"></span></button></td>'
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
            
            //Cargar materias a ventana modal
            $("#data").on("click", "#viewMats", function(){
                var idGrupo = $(this).val();
                $.ajax({
                    type: "POST",
                    url: "../controllers/esc_read_grupo_materias.php?idGrupo="+idGrupo,
                    success: function(msg){
                        var msg = jQuery.parseJSON(msg);
                        if(msg.error == 0){
                            var newRow = '';
                            $("#modalViewMats .matsProfs tbody").html("");
                            //$("#modalAddMat #inputGrupo").val(idGrupo);
                            $("#modalViewMats #addMat").data("whatever", idGrupo);
                            $.each(msg.dataRes, function(i, item){
                                newRow += '<tr>';
                                    newRow += '<td>'+msg.dataRes[i].nameMat+'</td>';
                                    newRow += '<td>'+msg.dataRes[i].nameProf+'</td>';
                                    newRow += '<td>'
                                            +'<button type="button" class="btn btn-primary" id="updMat" data-whatever="'+msg.dataRes[i].idMatProf+'" data-grupo="'+idGrupo+'" data-toggle="modal" data-target="#modalUpdMat">'
                                                +'Actualizar materia'
                                            +'</button></td>';
                                newRow += '</tr>';
                            });
                            //$("#modalViewMats .matsProfs tbody").html(newRow);
                            $(newRow).appendTo("#modalViewMats .matsProfs tbody");
                        }else{
                            var newRow = '<tr><td>'+msg.msgErr+'</td></tr>';
                            $(newRow).appendTo("#modalViewMats .matsProfs tbody");
                        }
                    }
                });
            });
            
            //Actualizar asignación de materias y profesores
            $('#modalUpdMat').on('show.bs.modal', function (event){
                var button = $(event.relatedTarget)
                var idGMatProf = button.data('whatever') 
                var idGrupo = button.data('grupo') 
                alert(idGMatProf+"--"+idGrupo);
                //var modal = $(this)
                //modal.find('.modal-body #inputGrupo').val(recipient);
                $.ajax({
                    type: "POST",
                    url: "../controllers/get_grado_by_grupo.php?idGrupo="+idGrupo,
                    success: function(msg){
                        console.log(msg);
                        var msg = jQuery.parseJSON(msg);
                        var idGrado = msg.dataRes[0].id;
                        $.ajax({
                            url:"../controllers/get_materias.php?idNivel="+<?=$idNivelEsc;?>+"&idGrado="+idGrado,
                            type: "POST",
                            success: function(opciones){
                                var msg = jQuery.parseJSON(opciones);
                                if(msg.error == 0){
                                    $("#modalUpdMat #inputIdGMatProf").val(idGMatProf);
                                    $("#modalUpdMat .materia").html("<option></option>");
                                    $.each(msg.dataRes, function(i, item){
                                        var newOpt = '<option value="'+msg.dataRes[i].id+'">'+msg.dataRes[i].nombre+'</option>';
                                        $(newOpt).appendTo("#modalUpdMat .materia");
                                    });
                                }else{
                                    $("#modalUpdMat #inputMat1").html("");
                                    $("#modalUpdMat #inputMat1").html("<option>"+msg.msgErr+"</option>");
                                }
                            }
                        })
                    }//end seccess
                });//end ajax
            });//end modal add mat
            
            // Añadir nueva materia a la asignación
            $('#modalAddMat').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget)
                var recipient = button.data('whatever') 
                var modal = $(this)
                modal.find('.modal-body #inputGrupo').val(recipient);
                $.ajax({
                    type: "POST",
                    url: "../controllers/get_grado_by_grupo.php?idGrupo="+recipient,
                    success: function(msg){
                        console.log(msg);
                        var msg = jQuery.parseJSON(msg);
                        var idGrado = msg.dataRes[0].id;
                        $.ajax({
                            url:"../controllers/get_materias.php?idNivel="+<?=$idNivelEsc;?>+"&idGrado="+idGrado,
                            type: "POST",
                            success: function(opciones){
                                var msg = jQuery.parseJSON(opciones);
                                if(msg.error == 0){
                                    $("#modalAddMat .materia").html("<option></option>");
                                    $.each(msg.dataRes, function(i, item){
                                        var newOpt = '<option value="'+msg.dataRes[i].id+'">'+msg.dataRes[i].nombre+'</option>';
                                        $(newOpt).appendTo("#modalAddMat .materia");
                                    });
                                }else{
                                    $("#modalAddMat #inputMat1").html("");
                                    $("#modalAddMat #inputMat1").html("<option>"+msg.msgErr+"</option>");
                                }
                            }
                        })
                    }//end seccess
                });//end ajax
            });//end modal add mat
            
            //Actualizar materia
            $('#formUpdMat').validate({
                rules: {
                    inputMat: {required: true},
                    inputProf: {required: true}
                },
                messages: {
                    inputMat: "Nombre obligatorio",
                    inputProf: "Nombre obligatorio"
                },
                tooltip_options: {
                    inputMat: {trigger: "focus", placement: "bottom"},
                    inputProf: {trigger: "focus", placement: "bottom"}
                },
                submitHandler: function(form){
                    $('#loading').show();
                    $.ajax({
                        type: "POST",
                        url: "../controllers/esc_update_grupo_materia_profesor.php",
                        data: $('form#formUpdMat').serialize(),
                        success: function(msg){
                            console.log(msg);
                            var msg = jQuery.parseJSON(msg);
                            if(msg.error == 0){
                                $('#loading').empty();
                                $('#loading').append('<img src="../assets/obj/success_256.png" height="300" width="400" >');
                                $('.msgModal').css({color: "#77DD77"});
                                $('.msgModal').html(msg.msgErr);
                                setTimeout(function () {
                                  location.href =  'esc_read_grupos_info.php';
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
                            alert("Error al actualizar asignación de materia");
                        }
                    });
                }
            }); // end añadir nuevo alumno
            
             //añadir nueva materia al grupo
           $('#formAddMat').validate({
                rules: {
                    inputMat: {required: true},
                    inputProf: {required: true}
                },
                messages: {
                    inputMat: "Nombre obligatorio",
                    inputProf: "Nombre obligatorio"
                },
                tooltip_options: {
                    inputMat: {trigger: "focus", placement: "bottom"},
                    inputProf: {trigger: "focus", placement: "bottom"}
                },
                submitHandler: function(form){
                    $('#loading').show();
                    $.ajax({
                        type: "POST",
                        url: "../controllers/esc_create_grupo_materia_profesor.php",
                        data: $('form#formAddMat').serialize(),
                        success: function(msg){
                            console.log(msg);
                            var msg = jQuery.parseJSON(msg);
                            if(msg.error == 0){
                                $('#loading').empty();
                                $('#loading').append('<img src="../assets/obj/success_256.png" height="300" width="400" >');
                                $('.msgModal').css({color: "#77DD77"});
                                $('.msgModal').html(msg.msgErr);
                                setTimeout(function () {
                                  location.href =  'esc_read_grupos_info.php';
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
                            alert("Error al añadir alumno nuevo");
                        }
                    });
                }
            }); // end añadir nuevo alumno
            
            
            //Selec dinamico obtenemos materias del nivel y grado
            $("#inputGrado").change(function(){
                getMaterias();
            });
            
            //añadir nuevos campos pzara materias y profesores
            var maxInputs = 10;
            var contenedor = $("#contenedor");
            var addButton = $("#addCampo");
            var x = $("#contenedor").length + 1;
            var FieldCount = x-1;
            
            $(addButton).click(function (e){
               if(x <= maxInputs){
                   FieldCount ++;
                   var mat = '<div class="row">'
                           +'<div class="col-sm-5">'
                            +'<label for="campo_m_'+FieldCount+'">Materia: </label>'
                            +'<select class="form-control materia" name="mat[]" id="campo_m_'+FieldCount+'" ></select>'
                           +'</div>';
                   var prof = '<div class="col-sm-6">'
                            +'<label for="campo_p_'+FieldCount+'">Profesor: </label>'
                            +'<select class="form-control" name="prof[]" id="campo_p_'+FieldCount+'" ><?=$optProf;?></select>'
                           +'</div>';
                   var eliminar = '<div class="col-sm-1">'
                            +'<a href="#" class="eliminar">&times;</a>'
                           +'</div>'
                        +'</div>';
                   $(contenedor).append(mat+prof+eliminar);
                   x++;
                   getMaterias();
               } 
               return false;
            });
            $(".modal-body").on("click",".eliminar", function(e){
               if(x > 2){
                   $(this).parent().parent().remove();
                   x--;
                }
                return false;
            });
           
           //añadir nuevo grupo
           $('#formAdd').validate({
                rules: {
                    inputGrado: {required: true},
                    inputGrupo: {required: true},
                    inputTurno: {required: true},
                    inputFile: {required: true, extension: "csv"}
                },
                messages: {
                    inputGrado: "Grado obligatorio",
                    inputGrupo: "¿De qué grupo es?",
                    inputTurno: "¿Cuál es el turno?",
                    inputFile: { 
                        required: "Se requiere un archivo",
                        extension: "Solo se permite archivos *.csv (archivo separado por comas de Excel)"
                    }
                },
                tooltip_options: {
                    inputGrado: {trigger: "focus", placement: "bottom"},
                    inputGrupo: {trigger: "focus", placement: "bottom"},
                    inputTurno: {trigger: "focus", placement: "bottom"},
                    inputFile: {trigger: "focus", placement: "bottom"}
                },
                submitHandler: function(form){
                    $('#loading').show();
                    $.ajax({
                        type: "POST",
                        url: "../controllers/esc_create_grupo.php",
                        data: new FormData($("form#formAdd")[0]),
                        //data: $('form#formAdd').serialize(),
                        contentType: false,
                        processData: false,
                        success: function(msg){
                            console.log(msg);
                            //alert(msg);
                            var msg = jQuery.parseJSON(msg);
                            if(msg.error == 0){
                                $('#loading').empty();
                                $('#loading').append('<img src="../assets/obj/success_256.png" height="300" width="400" >');
                                $('.msgModal').css({color: "#77DD77"});
                                $('.msgModal').html(msg.msgErr);
                                setTimeout(function () {
                                  location.href = 'esc_read_grupos_info.php';
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
                            alert("Error al crear grupo");
                        }
                    });
                }
            }); // end añadir nuevo grupo
            
        });
        
    </script>
    
<?php
    }//end if-else
    include ('footer.php');
?>
