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
        //Obtenemos pregunta
        $idPreg = $_GET['idPreg'];
        $sqlGetNamePreg = "SELECT nombre FROM $tBPregs WHERE id='$idPreg' ";
        $resGetNamePreg = $con->query($sqlGetNamePreg);
        $rowGetNamePreg = $resGetNamePreg->fetch_assoc();
        $namePreg = $rowGetNamePreg['nombre'];
    
?>

    <div class="container">
        <div id="loader"><img src="../assets/obj/loading.gif"></div>
        <div class="row">
            <ol class="breadcrumb">
                <li><a href="admin_read_banco_niveles.php"><?=$nameNivel;?></a></li>
                <li><a href="admin_read_banco_grados.php?idNivel='<?=$idNivel;?>'"><?=$nameGrado;?></a></li>
                <li><a href="admin_read_banco_materias.php?idNivel=<?=$idNivel;?>&idGrado=<?=$idGrado;?>"><?=$nameMateria;?></a></li>
                <li><a href="admin_read_banco_bloques.php?idNivel=<?=$idNivel;?>&idGrado=<?=$idGrado;?>&idMateria=<?=$idMateria;?>"><?=$nameBloque;?></a></li>
                <li><a href="admin_read_banco_temas.php?idNivel=<?=$idNivel;?>&idGrado=<?=$idGrado;?>&idMateria=<?=$idMateria;?>&idBloque=<?=$idBloque;?>"><?=$nameTema;?></a></li>
                <li><a href="admin_read_banco_subtemas.php?idNivel=<?=$idNivel;?>&idGrado=<?=$idGrado;?>&idMateria=<?=$idMateria;?>&idBloque=<?=$idBloque;?>&idTema=<?=$idTema;?>"><?=$nameSubtema;?></a></li>
                <li><a href="admin_read_banco_preguntas.php?idNivel=<?=$idNivel;?>&idGrado=<?=$idGrado;?>&idMateria=<?=$idMateria;?>&idBloque=<?=$idBloque;?>&idTema=<?=$idTema;?>&idSubtema=<?=$idSubtema;?>"><?=$namePreg;?></a></li>
                <li class="active">Ver pregunta</li>
            </ol>
        </div>
        <div id="dataExa"></div>
        
    </div>

    <script type="text/javascript">
        $(document).ready(function(){
            $.ajax({
                url: "../controllers/admin_read_banco_pregunta.php?idPreg=<?=$idPreg;?>",
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
                                var newPreg = '<div class="row"><div class="col-sm-12 text-center">'
                                        +'<p class="text-center">'+msg.dataPregs[i].nombre+'</p>'
                                    +'</div></div>';
                                if(msg.dataPregs[i].archivo != null){ 
                                    newPreg += '<div class="row">'
                                        +'<img src="../<?=$filesExams;?>/'+msg.dataPregs[i].archivo+'" class="img-responsive center-block" width="60%">'
                                        +'</div>';
                                }
                                $(newPreg).appendTo("#dataExa");
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
                                    $(newResp).appendTo("#dataExa");
                                })
                           });
                           //$(".outer_div").html(msg.pags);
                       }else{
                           var newRow = '<tr><td></td><td>'+msg.msgErr+'</td></tr>';
                           $("#data tbody").html(newRow);
                       }
                    //$(".outer_div").html(data).fadeIn('slow');
                }
            })
        })
    </script>
    
<?php
    }//end if-else
    include ('footer.php');
?>