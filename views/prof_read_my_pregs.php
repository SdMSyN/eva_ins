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
               
?>

    <div class="container">
        <div class="row">
            <div id="loading">
                <img src="../assets/obj/loading.gif" height="300" width="400">
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table table-striped" id="data">
                <caption>Tus exámenes</caption>
                <thead>
                    <tr>
                        <th><span title="id">Id</span></th>
                        <th><span title="nombre">Nombre</span></th>
                        <th><span title="valor_preg">Valor pregunta</span></th>
                        <th><span title="tipo_resp">Tipo de respuesta</span></th>
                        <th>Ver pregunta</th>
                        <th>Eliminar</th>
                    </tr>
                </thead>
                    <tbody></tbody>
            </table>
        </div>
        
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
                   url: "../controllers/prof_read_my_pregs.php?idUser="+<?=$idUser;?>,
                   success: function(msg){
                       //alert(msg);
                       console.log(msg);
                       var msg = jQuery.parseJSON(msg);
                       if(msg.error == 0){
                           $("#data tbody").html("");
                           $.each(msg.dataRes, function(i, item){
                               var newRow = '<tr>'
                                    +'<td>'+msg.dataRes[i].id+'</td>'   
                                    +'<td>'+msg.dataRes[i].nombre+'</td>'   
                                    +'<td>'+msg.dataRes[i].valorPreg+'</td>';
                            
                                    if(msg.dataRes[i].tipoResp == 1) newRow += '<td>Opción Multiple</td>';
                                    else if(msg.dataRes[i].tipoResp == 2) newRow += '<td>Multiopción Multirespuesta</td>';
                                    else if(msg.dataRes[i].tipoResp == 3) newRow += '<td>Respuesta abierta</td>';
                                    else if(msg.dataRes[i].tipoResp == 4) newRow += '<td>Respuesta exacta</td>';
                                    else newRow += '<td></td>';
                                    
                                    newRow += '<td><button type="button" class="btn btn-default" id="viewPreg" value="'+msg.dataRes[i].id+'" data-toggle="modal" data-target="#modalViewPreg"><span class="glyphicon glyphicon-eye-open"></span></button></td>' 
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
           
           //Ordenar por formulario
            $("#btnfiltrar").click(function(){ 
                filtrar();
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
            
            $("#data").on("click", "#delete", function(){
                var idPreg = $(this).val();
                //alert("Hola: "+idProf);
                if(confirm("¿Seguro que deseas eliminar esta pregunta? Ya no se volverá a mostrar.")){
                    $('#loading').show();
                    $.ajax({
                         method: "POST",
                         url: "../controllers/delete_pregunta.php?idPreg="+idPreg,
                         success: function(data){
                            //alert(data);
                            console.log(data);
                            var msg = jQuery.parseJSON(data);
                            if(msg.error == 0){
                                $('#loading').empty();
                                $('#loading').append('<img src="../assets/obj/success_256.png" height="300" width="400" >');
                                $('#loading').append('<p>'+msg.dataRes+'</p>');
                                setTimeout(function () {
                                  location.href = 'prof_read_my_pregs.php';
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
            
        });
    </script>
    
<?php
    }//end if-else
    include ('footer.php');
?>