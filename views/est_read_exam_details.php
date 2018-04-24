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
    }else if($_SESSION['perfil'] != 3){
        echo '<div class="row"><div class="col-sm-12 text-center"><h2>¿Estás tratando de acceder? No es tu perfil o(´^｀)o </h2></div></div>';
    }
    else {
        
        $idPerfil = $_SESSION['perfil'];
        $idUser = $_SESSION['userId'];
        $idExam = $_GET['idExam'];
        $idExamAsig = $_GET['idExamAsig'];
        $idExamAsigAlum = $_GET['idExamAsigAlum'];
        unset ( $_SESSION['exaRand'] );
?>

    <div class="container">
        <div id="loader"><img src="../assets/obj/loading.gif"></div>
        <div class="outer_div"></div>
        <div id="dataExa"></div>
        
    </div>

    <script type="text/javascript">
        //$('#loading').hide();
        var ordenar = '';
        $(document).ready(function(){
            load(1);
        });
        
        //paginación
        // http://obedalvarado.pw/blog/paginacion-con-php-mysql-jquery-ajax-y-bootstrap/
        
        function load(page){
            var parametros = {"action": "ajax", "page": page};
            $("#loader").fadeIn('slow');
            $.ajax({
                url: "../controllers/est_read_exam_details.php?idExam=<?=$idExam;?>&idExamAsig=<?=$idExamAsig;?>&idUser=<?=$idUser;?>&idExamAsigAlum=<?=$idExamAsigAlum;?>",
                data: parametros,
                beforeSend: function(objeto){
                    $("#loader").html('<img src="../assets/obj/loading.gif" height="300" width="400">');
                },
                success: function(data){
                    //alert(data);
                    console.log(data);
                    var msg = jQuery.parseJSON(data);
                        if(msg.error == 0){
                            $("#loader").html("");
                            $("#data tbody").html("");
                            $("#dataExa").html("");
                            //alert(data);
                            $.each(msg.dataPregs, function(i, item){
                                var newPreg = '<div class="row"><div class="col-sm-12 text-center">';
                                        newPreg += '<p class="text-center">'+msg.dataPregs[i].nombre;
                                        if(msg.dataPregs[i].calif == 0) newPreg += ' <span class="glyphicon glyphicon-remove"></span>';
                                        else if(msg.dataPregs[i].calif == 1) newPreg += ' <span class="glyphicon glyphicon-ok"></span>';
                                        else newPreg += ' <span class="glyphicon glyphicon-ban-circle"></span>';
                                        //newPreg += (msg.dataPregs[i].calif == 0) ? '<span class="glyphicon glyphicon-remove"></span>' : '<span class="glyphicon glyphicon-ok"></span>';
                                    newPreg += '<p>('+msg.dataPregs[i].valorPreg;
                                    newPreg += (msg.dataPregs[i].valorPreg == 1) ? ' Punto)</p>' : ' Puntos)</p>';
                                    newPreg += '</p></div></div>';
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
                                $(newPreg).appendTo("#dataExa");
                                $.each(msg.dataPregs[i].resps, function(j, item2){
                                    var newResp = '';
                                    if(msg.dataPregs[i].resps[j].tipoR == 1){
                                        newResp += '<div class="col-sm-6 text-center">';
                                        console.log(msg.dataPregs[i].resps[j].archivo);
                                        /*if(msg.dataPregs[i].resps[j].archivo != null){
                                            newResp += '<img src="../<?=$filesExams;?>/'+msg.dataPregs[i].resps[j].archivo+'" class="img-responsive center-block" >';
                                        }*/
                                        newResp += '<label>'+msg.dataPregs[i].resps[j].nombreResp+'</label>';
                                        newResp += '<input type="radio" class="form-control" name="radio[]" id="radio" value="'+msg.dataPregs[i].resps[j].id+'" checked disabled>';
                                        newResp += '</div>';
                                    }else if(msg.dataPregs[i].resps[j].tipoR == 2){
                                        newResp += '<div class="col-sm-6 text-center">';
                                        /*if(msg.dataPregs[i].resps[j].archivo != null){
                                            newResp += '<img src="../<?=$filesExams;?>/'+msg.dataPregs[i].resps[j].archivo+'" class="img-responsive center-block" >';
                                        }*/
                                        newResp += '<label>'+msg.dataPregs[i].resps[j].nombreResp+'</label>';
                                        newResp += '<input type="checkbox" class="form-control" name="check[]" id="check" value="'+msg.dataPregs[i].resps[j].id+'" checked disabled>';
                                        newResp += '</div>';
                                    }else if(msg.dataPregs[i].resps[j].tipoR == 3){
                                        newResp += '<div class="col-sm-12">';
                                            //newResp += (msg.dataPregs[i].resps[j].respCorr == 1) ? '<input type="text" class="form-control" name="text[]" id="text" value="'+msg.dataPregs[i].resp[j].palabra+'">' : '<input type="text" class="form-control" name="text[]" id="text" >';
                                            newResp += '<input type="text" class="form-control" name="text[]" id="text" value="'+msg.dataPregs[i].resps[j].resp+'" disabled>';
                                        newResp += '</div>';
                                    }else if(msg.dataPregs[i].resps[j].tipoR == 4){
                                        newResp += '<div class="col-sm-12">';
                                            //newResp += (msg.dataPregs[i].resps[j].respCorr == 1) ? '<input type="text" class="form-control" name="text[]" id="text" value="'+msg.dataPregs[i].resp[j].palabra+'">' : '<input type="text" class="form-control" name="text[]" id="text" >';
                                            newResp += '<input type="text" class="form-control" name="text[]" id="text" value="'+msg.dataPregs[i].resps[j].resp+'" disabled>';
                                        newResp += '</div>';
                                    }else{
                                        newResp += '<div class="row">Tipo de respuesta inexistente.</div>';
                                    }
                                    //newResp += '</div><!-- end row -->';
                                    $(newResp).appendTo("#dataExa");
                                })
                           });
                           //var sqlTmp = '<p class="text-center">**'+msg.sql+'**</p>';
                           //$(sqlTmp).appendTo("#dataExa");
                           $(".outer_div").html(msg.pags);
                       }else{
                           var newRow = '<tr><td></td><td>'+msg.msgErr+'</td></tr>';
                           $("#data tbody").html(newRow);
                       }
                    //$(".outer_div").html(data).fadeIn('slow');
                }
            })
        }
    </script>
    
<?php
    }//end if-else
    include ('footer.php');
?>