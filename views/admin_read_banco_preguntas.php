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
    }else if($_SESSION['perfil'] != 10){
        echo '<div class="row"><div class="col-sm-12 text-center"><h2>¿Estás tratando de acceder? No es tu perfil o(´^｀)o </h2></div></div>';
    }else {
        //Obtenemos Nombre del nivel
        $idNivel = $_GET['idNivel'];
        $sqlGetName = "SELECT nombre FROM $tNivEsc WHERE id='$idNivel' ";
        $resGetName = $con->query($sqlGetName);
        $rowGetName = $resGetName->fetch_assoc();
        $nameNivel = $rowGetName['nombre'];
        //Obtenemos Nombre del grado
        $idGrado = $_GET['idGrado'];
        $sqlGetName = "SELECT nombre FROM $tGrado WHERE id='$idGrado' ";
        $resGetName = $con->query($sqlGetName);
        $rowGetName = $resGetName->fetch_assoc();
        $nameGrado = $rowGetName['nombre'];
        //Obtenemos Nombre Materia
        $idMateria = $_GET['idMateria'];
        $sqlGetNameMateria = "SELECT nombre FROM $tBMat WHERE id='$idMateria' ";
        $resGetNameMateria = $con->query($sqlGetNameMateria);
        $rowGetNameMateria = $resGetNameMateria->fetch_assoc();
        $nameMateria = $rowGetNameMateria['nombre'];
        //Obtenemos Nombre Bloque
        $idBloque = $_GET['idBloque'];
        $sqlGetNameBloque = "SELECT nombre FROM $tBBloq WHERE id='$idBloque' ";
        $resGetNameBloque = $con->query($sqlGetNameBloque);
        $rowGetNameBloque = $resGetNameBloque->fetch_assoc();
        $nameBloque = $rowGetNameBloque['nombre'];
        //Obtenemos Nombre Tema
        $idTema = $_GET['idTema'];
        $sqlGetNameTema = "SELECT nombre FROM $tBTema WHERE id='$idTema' ";
        $resGetNameTema = $con->query($sqlGetNameTema);
        $rowGetNameTema = $resGetNameTema->fetch_assoc();
        $nameTema = $rowGetNameTema['nombre'];
        //Obtenemos Nombre Subtema
        $idSubtema = $_GET['idSubtema'];
        $sqlGetNameSubtema = "SELECT nombre FROM $tBSubTema WHERE id='$idSubtema' ";
        $resGetNameSubtema = $con->query($sqlGetNameSubtema);
        $rowGetNameSubtema = $resGetNameSubtema->fetch_assoc();
        $nameSubtema = $rowGetNameSubtema['nombre'];
    
?>

    <div class="container">
        <div class="row">
            <div id="loading">
                <img src="../assets/obj/loading.gif" height="300" width="400">
            </div>
        </div>
        <div class="row text-center"><h1>Preguntas</h1></div>
        <div class="row placeholder text-center">
            <div class="col-sm-12 placeholder">
                <a href="admin_create_banco_pregunta.php?idNivel=<?=$idNivel;?>&idGrado=<?=$idGrado?>&idMateria=<?=$idMateria;?>&idBloque=<?=$idBloque;?>&idTema=<?=$idTema;?>&idSubtema=<?=$idSubtema;?>" class="btn btn-primary btn-lg">
                    Añadir nueva pregunta <span class="glyphicon glyphicon-plus"></span>
                </a>
            </div>
        </div>
        <br>
        <div class="row">
            <ol class="breadcrumb">
                <li><a href="admin_read_banco_niveles.php"><?=$nameNivel;?></a></li>
                <li><a href="admin_read_banco_grados.php?idNivel='<?=$idNivel;?>'"><?=$nameGrado;?></a></li>
                <!-- <li><a href="admin_read_banco_materias.php?idNivel='.$idNivel.'&idGrado='.$idGrado.'">'.$nameMateria.'</a></li>
                <li><a href="admin_read_banco_bloques.php?idNivel='.$idNivel.'&idGrado='.$idGrado.'&idMateria='.$idMateria.'">'.$nameBloque.'</a></li>
                <li><a href="admin_read_banco_temas.php?idNivel='.$idNivel.'&idGrado='.$idGrado.'&idMateria='.$idMateria.'&idBloque='.$idBloque.'">'.$nameTema.'</a></li>
                <li><a href="admin_read_banco_subtemas.php?idNivel='.$idNivel.'&idGrado='.$idGrado.'&idMateria='.$idMateria.'&idBloque='.$idBloque.'&idTema='.$idTema.'">'.$nameSubtema.'</a></li>
                -->
                <li class="active">Preguntas</li>
            </ol>
        </div>
        <div class="table-responsive">
            <table class="table table-striped" id="data">
                <caption>
                    <?php 
                        $cadCap = '<a href="admin_read_banco_niveles.php">'.$nameNivel.'</a> => ';
                        $cadCap .= '<a href="admin_read_banco_grados.php?idNivel='.$idNivel.'">'.$nameGrado.'</a> => ';
                        $cadCap .= '<a href="admin_read_banco_materias.php?idNivel='.$idNivel.'&idGrado='.$idGrado.'">'.$nameMateria.'</a> => ';
                        $cadCap .= '<a href="admin_read_banco_bloques.php?idNivel='.$idNivel.'&idGrado='.$idGrado.'&idMateria='.$idMateria.'">'.$nameBloque.'</a> => ';
                        $cadCap .= '<a href="admin_read_banco_temas.php?idNivel='.$idNivel.'&idGrado='.$idGrado.'&idMateria='.$idMateria.'&idBloque='.$idBloque.'">'.$nameTema.'</a> => ';
                        $cadCap .= '<a href="admin_read_banco_subtemas.php?idNivel='.$idNivel.'&idGrado='.$idGrado.'&idMateria='.$idMateria.'&idBloque='.$idBloque.'&idTema='.$idTema.'">'.$nameSubtema.'</a>';
                    ?>
                    <?= $cadCap; ?> => Preguntas
                </caption>
                <thead>
                    <tr>
                        <th><span title="id">Id</span></th>
                        <th><span title="nombre">Nombre Pregunta</span></th>
                        <th><span title="created">Creado</span></th>
                        <th>Ver Pregunta</th> 
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
                   data: ordenar, 
                   url: "../controllers/get_preguntas.php?id="+<?=$idSubtema;?>,
                   success: function(msg){
                       //alert(msg);
                       var msg = jQuery.parseJSON(msg);
                       if(msg.error == 0){
                           //alert(msg.dataRes[0].id);
                           $("#data tbody").html("");
                           $.each(msg.dataRes, function(i, item){
                               var newRow = '<tr>'
                                    +'<td>'+msg.dataRes[i].id+'</td>'   
                                    +'<td>'+msg.dataRes[i].nombre+'</td>'   
                                    +'<td>'+msg.dataRes[i].creado+'</td>' 
                                    //+'<td><a href="admin_read_banco_pregunta.php?idPreg='+msg.dataRes[i].id+'"></a></td>'
                                    //+'<td><a href="admin_read_banco_pregunta.php?idNivel='+<?=$idNivel;?>+'&idGrado='+<?=$idGrado;?>+'&idMateria='+<?=$idMateria;?>+'&idBloque='+<?=$idBloque;?>+'&idTema='+<?=$idTema;?>+'&idSubtema='+<?=$idSubtema?>+'&idPreg='+msg.dataRes[i].id+'" class="btn btn-default"><span class="glyphicon glyphicon-eye-open"></span></a></td>'
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
                                    newPreg += '<div class="row">'
                                        +'<img src="../<?=$filesExams;?>/'+msg.dataPregs[i].archivo+'" class="img-responsive center-block" width="60%">'
                                        +'</div>';
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
