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
        
        $optH = '';
        for($i = 0; $i < 6; $i++){
            $optH .= '<option value="'.$i.'">'.$i.'</option>';
        }
        
        $optM = '';
        for($i = 0; $i < 60; $i++){
            $optM .= '<option value="'.$i.'">'.$i.'</option>';
        }
?>

    <div class="container">
        <div class="row">
            <div id="loading">
                <img src="../assets/obj/loading.gif" height="300" width="400">
            </div>
        </div>

        <div class="row placeholder text-center">
            <div class="col-sm-12 placeholder">
                <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#modalAdd">
                    Crear nuevo Examen
                    <span class="glyphicon glyphicon-plus"></span>
                </button>
            </div>
        </div>
        <br>
        
        <div class="table-responsive">
            <table class="table table-striped" id="data">
                <caption>Tus exámenes</caption>
                <thead>
                    <tr>
                        <th><span title="id">Id</span></th>
                        <th><span title="materia">Materia</span></th>
                        <th><span title="nombre">Nombre</span></th>
                        <th><span title="created">Creado</span></th>
                        <th><span title="numPregs"># preguntas</span></th>
                        <th>Añadir pregunta</th>
                        <th>Ver examen</th>
                        <th>Asignar</th>
                        <th>Ver asignaciones</th>
                        <th>Descargar</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <!-- modal para añadir exa_info -->
        <div class="modal fade" id="modalAdd" tabindex="-1" role="dialog" aria-labellebdy="myModal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
                        </button>
                        <h4 class="modal-title" id="exampleModalLabel">Crear nuevo examen</h4>
                        <p class="msgModal"></p>
                    </div>
                    <form id="formAdd" name="formAdd">
                        <div class="modal-body">
                            <div class="form-group">
                                <input type="hidden" name="inputIdUser" value="<?= $idUser; ?>" >
                                <label for="inputName">Nombre: </label>
                                <input type="text" class="form-control" id="inputName" name="inputName" >
                            </div>
                            <div class="form-group">
                                <label for="inputMat">Materia: </label>
                                <select class="form-control" id="inputMat" name="inputMat"></select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Crear</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- modal para añadir pregunta -->
        <div class="modal fade" id="modalAddPreg" tabindex="-1" role="dialog" aria-labellebdy="myModal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
                        </button>
                        <h4 class="modal-title" id="exampleModalLabel">Añadir pregunta(s)</h4>
                        <p class="msgModal"></p>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-6 text-center">
                                <a id="createPreg" class="btn btn-primary">Crear Pregunta</a>
                                <p>Crea tu propia pregunta para añadirla a éste examen.</p>
                            </div>
                            <div class="col-sm-6 text-center">
                                <a id="bancoPreg" class="btn btn-primary">Banco preguntas</a>
                                <p>Busca en nuestro banco de preguntas y añade las de tu preferencia.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- modal para ver asignaciones -->
        <div class="modal fade bs-example-modal-lg" id="modalViewAsig" tabindex="-1" role="dialog" aria-labellebdy="myModal" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
                        </button>
                        <h4 class="modal-title" id="exampleModalLabel">Asignaciones</h4>
                        <p class="msgModal"></p>
                    </div>
                    <div class="modal-body">
                           
                    </div>
                </div>
            </div>
        </div>
        
        <!-- modal para ver detalles de las asignaciones -->
        <div class="modal fade bs-example-modal-lg" id="modalViewAsigDetails" tabindex="-1" role="dialog" aria-labellebdy="myModal" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
                        </button>
                        <h4 class="modal-title" id="exampleModalLabel">Detalles de la Asignación</h4>
                        <p class="msgModal"></p>
                    </div>
                    <div class="modal-body">
                           
                    </div>
                </div>
            </div>
        </div>
        
    </div>

    <script type="text/javascript">
        $('#loading').hide();
        var ordenar = '';
        $(document).ready(function(){
            //obtenemos las materias del profesor
            $.ajax({
                type: "POST",
                data: {idProf: <?=$idUser;?>}, 
                url: "../controllers/prof_read_grupo_materia_profesor.php",
                success: function(msg){
                    var msg = jQuery.parseJSON(msg);
                    if(msg.error == 0){
                        $("#modalAdd #inputMat").html("<option></option>");
                        $.each(msg.dataRes, function(i, item){
                            var newRow = '<option value="'+msg.dataRes[i].id+'">'+msg.dataRes[i].mat+'</option>';
                            $(newRow).appendTo("#modalAdd #inputMat");
                        });
                    }else{
                        var newRow = '<option></option>';
                        $("#modalAdd #inputMat").html(newRow);
                    }
                }
            });
            
            filtrar();
            function filtrar(){
               $.ajax({
                   type: "POST",
                   data: ordenar, 
                   url: "../controllers/prof_read_exas_info.php?idProf="+<?=$idUser;?>,
                   success: function(msg){
                       //alert(msg);
                       console.log(msg);
                       var msg = jQuery.parseJSON(msg);
                       if(msg.error == 0){
                           $("#data tbody").html("");
                           $.each(msg.dataRes, function(i, item){
                               var newRow = '<tr>'
                                    +'<td>'+msg.dataRes[i].id+'</td>'   
                                    +'<td>'+msg.dataRes[i].materia+'</td>'   
                                    +'<td>'+msg.dataRes[i].nombre+'</td>'   
                                    +'<td>'+msg.dataRes[i].creado+'</td>' 
                                    +'<td>'+msg.dataRes[i].numPregs+'</td>'
                                    +'<td><button type="button" class="btn btn-default" '
                                        +'data-toggle="modal" data-target="#modalAddPreg" data-whatever="'+msg.dataRes[i].id+'">'
                                            +'<span class="glyphicon glyphicon-plus-sign"></span>'
                                            +'<span class="glyphicon glyphicon-question-sign"></span>'
                                    +'</button></td>'
                                    //+'<td></td>'
                                    +'<td><a href="prof_prev_exam.php?idExam='+msg.dataRes[i].id+'" class="btn btn-default"><span class="glyphicon glyphicon-eye-open"></span></a></td>'
                                    +'<td><a href="prof_create_asignacion.php?idExam='+msg.dataRes[i].id+'" class="btn btn-default"><span class="glyphicon glyphicon-gift"></span></a></td>'
                                    +'<td><button type="button" class="btn btn-default" data-whatever="'+msg.dataRes[i].id+'" data-toggle="modal" data-target="#modalViewAsig"><span class="glyphicon glyphicon-eye-open"></span></button></td>'
                                    +'<td><a href="../controllers/print_exam.php?idExam='+msg.dataRes[i].id+'" class="btn btn-default" ><span class="glyphicon glyphicon-download"></span></a></td>'
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
            
            //añadir nuevo examen
            $('#formAdd').validate({
                rules: {
                    inputName: {required: true},
                    inputMat: {required: true}
                },
                messages: {
                    inputName: "Nombre del examen obligatorio",
                    inputMat: "Selecciona una materia"
                },
                tooltip_options: {
                    inputName: {trigger: "focus", placement: "bottom"},
                    inputMat: {trigger: "focus", placement: "bottom"}
                },
                submitHandler: function(form){
                    $('#loading').show();
                    $.ajax({
                        type: "POST",
                        url: "../controllers/prof_create_exa_info.php",
                        data: $('form#formAdd').serialize(),
                        success: function(msg){
                            var msg = jQuery.parseJSON(msg);
                            if(msg.error == 0){
                                $('#loading').empty();
                                $('#loading').append('<img src="../assets/obj/success_256.png" height="300" width="400" >');
                                $('.msgModal').css({color: "#77DD77"});
                                $('.msgModal').html(msg.msgErr);
                                setTimeout(function () {
                                  location.href = 'prof_read_exams.php';
                                }, 1500);
                            }else{
                                $('#loading').empty();
                                $('#loading').append('<img src="../assets/obj/error.png" height="300" width="400" >');
                                $('.msgModal').css({color: "#FF0000"});
                                $('.msgModal').html(msg.msgErr);
                            }
                        }, error: function(){
                            $('#loading').empty();
                                $('#loading').append('<img src="../assets/obj/error.png" height="300" width="400" >');
                            alert("Error al crear nuevo examen");
                        }
                    });
                }
            }); // end añadir nuevo examen

            //Modal crear pregunta
            $('#modalAddPreg').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget)
                var recipient = button.data('whatever')
                $("#createPreg").attr('href','prof_create_preg.php?idExam='+recipient);
                $("#bancoPreg").attr('href','prof_read_banco_pregs.php?idExam='+recipient);
            });
            
            //función modal para ver asignaciones del examen
            $('#modalViewAsig').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget)
                var recipient = button.data('whatever') 
                //var modal = $(this)
                //var idExam = modal.find('.modal-body #inputIdExam').val(recipient);
                //alert(recipient);
                //obtenemos grupo_id
                //con método onChange obtenemos 
                $.ajax({
                    type: "POST",
                    url: "../controllers/prof_read_asignaciones.php?idExam="+recipient,
                    success: function(msg){
                        //console.log(msg);
                        var msg = jQuery.parseJSON(msg);
                        var infoAsig = '<table class="table table-hover"><thead>';
                        infoAsig += '<tr><th>Nombre</th><th>Grado-Grupo</th><th>Materia</th><th>Rango</th><th>Tiempo</th>'
                                +'<th>Aleatorio</th><th>Asignado</th><th>Eliminar</th><th>Detalles</th></tr><tbody>';
                        if(msg.error == 0){
                            $.each(msg.dataRes, function(i, item){
                                infoAsig += '<tr>';
                                    infoAsig += '<td>'+msg.dataRes[i].nombre+'</td>';
                                    infoAsig += '<td>'+msg.dataRes[i].grado+'-'+msg.dataRes[i].grupo+'</td>';
                                    infoAsig += '<td>'+msg.dataRes[i].materia+'</td>';
                                    infoAsig += '<td>('+msg.dataRes[i].inicio+') - ('+msg.dataRes[i].fin+')</td>';
                                    infoAsig += '<td>'+msg.dataRes[i].tiempo+'</td>';
                                    infoAsig += (msg.dataRes[i].aleatorio == 1) ? '<td>Si</td>' : '<td>No</td>';
                                    infoAsig += '<td>'+msg.dataRes[i].creado+'</td>';
                                    infoAsig += '<td><button class="btn btn-danger" id="delete" value="'+msg.dataRes[i].id+'" ><span class="glyphicon glyphicon-remove"></span></button></td>';
                                    infoAsig += '<td><button type="button" class="btn btn-default" data-whatever="'+msg.dataRes[i].id+'" data-toggle="modal" data-target="#modalViewAsigDetails"><span class="glyphicon glyphicon-eye-open"></span></button></td>';
                                infoAsig += '</tr>';
                            });
                            infoAsig += '</tbody></table>';
                            $("#modalViewAsig .modal-body").html(infoAsig);
                        }else{
                            var newRow = '<div class="row">'+msg.msgErr+'</div>';
                            $("#modalViewAsig .modal-body").html(newRow);
                        }
                    }
                });
            });
            
            //función modal para ver detalles de la asignación
            $('#modalViewAsigDetails').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget)
                var recipient = button.data('whatever') 
                //var modal = $(this)
                //var idExam = modal.find('.modal-body #inputIdExam').val(recipient);
                //alert(recipient);
                //obtenemos grupo_id
                //con método onChange obtenemos 
                $.ajax({
                    type: "POST",
                    url: "../controllers/prof_read_detalles_asignaciones.php?idAsigInfo="+recipient,
                    success: function(msg){
                        console.log(msg);
                        var msg = jQuery.parseJSON(msg);
                        var infoAsig = '<table class="table table-hover"><thead>';
                        infoAsig += '<tr><th>Nombre</th></tr><tbody>';
                        if(msg.error == 0){
                            $.each(msg.dataRes, function(i, item){
                                infoAsig += '<tr>';
                                    infoAsig += '<td>'+msg.dataRes[i].nombre+'</td>';
                                infoAsig += '</tr>';
                            });
                            infoAsig += '</tbody></table>';
                            $("#modalViewAsigDetails .modal-body").html(infoAsig);
                        }else{
                            var newRow = '<div class="row">'+msg.msgErr+'</div>';
                            $("#modalViewAsigDetails .modal-body").html(newRow);
                        }
                    }
                });
            });
            
            //Eliminar asignación
            $("#modalViewAsig").on("click", "#delete", function(){
                var idAsig = $(this).val();
                //alert("Hola: "+idAsig);
                if(confirm("¿Seguro que deseas eliminar esta asignación?")){
                    $.ajax({
                         method: "POST",
                         url: "../controllers/prof_delete_asignacion.php?idAsig="+idAsig,
                         success: function(data){
                            alert(data);
                            console.log(data);
                            var msg = jQuery.parseJSON(data);
                            if(msg.error == 0){
                                $('#loading').empty();
                                $('#loading').append('<img src="../assets/obj/success_256.png" height="300" width="400" >');
                                $('#loading').append('<p>'+msg.dataRes+'</p>');
                                setTimeout(function () {
                                  location.href = 'prof_read_exams.php';
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