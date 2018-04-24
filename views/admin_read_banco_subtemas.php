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
    
?>

    <div class="container">
        <div class="row">
            <div id="loading">
                <img src="../assets/obj/loading.gif" height="300" width="400">
            </div>
        </div>
        <div class="row text-center"><h1>Subtemas</h1></div>
        <div class="row placeholder text-center">
            <div class="col-sm-12 placeholder">
                <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#modalAdd">
                    Añadir nuevo SubTema
                    <span class="glyphicon glyphicon-plus"></span>
                </button>
            </div>
        </div>
        <br>
        <div class="table-responsive">
            <table class="table table-striped" id="data">
                <caption>
                    <?php 
                        $cadCap = '<a href="admin_read_banco_niveles.php">'.$nameNivel.'</a> => ';
                        $cadCap .= '<a href="admin_read_banco_grados.php?idNivel='.$idNivel.'">'.$nameGrado.'</a> => ';
                        $cadCap .= '<a href="admin_read_banco_materias.php?idNivel='.$idNivel.'&idGrado='.$idGrado.'">'.$nameMateria.'</a> => ';
                        $cadCap .= '<a href="admin_read_banco_bloques.php?idNivel='.$idNivel.'&idGrado='.$idGrado.'&idMateria='.$idMateria.'">'.$nameBloque.'</a> => ';
                        $cadCap .= '<a href="admin_read_banco_temas.php?idNivel='.$idNivel.'&idGrado='.$idGrado.'&idMateria='.$idMateria.'&idBloque='.$idBloque.'">'.$nameTema.'</a>';
                    ?>
                    <?= $cadCap; ?> => Subtemas
                </caption>
                <thead>
                    <tr>
                        <th><span title="id">Id</span></th>
                        <th><span title="nombre">Nombre</span></th>
                        <th><span title="created">Creado</span></th>
                        <th>Ver Preguntas</th> 
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        
        <div class="modal fade" id="modalAdd" tabindex="-1" role="dialog" aria-labellebdy="myModal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
                        </button>
                        <h4 class="modal-title" id="exampleModalLabel">Añadir nuevo SubTema al Tema: <?= $nameTema; ?></h4>
                        <p class="msgModal"></p>
                    </div>
                    <form id="formAdd" name="formAdd">
                        <div class="modal-body">
                            <div class="form-group">
                                <input type="hidden" name="inputIdTema" value="<?= $idTema; ?>" >
                                <label for="inputName">Nombre: </label>
                                <input type="text" class="form-control" id="inputName" name="inputName" >
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
        $('#loading').hide();
        var ordenar = '';
        $(document).ready(function(){
           filtrar();
           function filtrar(){
               $.ajax({
                   type: "POST",
                   data: ordenar, 
                   url: "../controllers/get_subtemas.php?id="+<?=$idTema;?>,
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
                                    +'<td><a href="admin_read_banco_preguntas.php?idNivel='+<?=$idNivel;?>+'&idGrado='+<?=$idGrado;?>+'&idMateria='+<?=$idMateria;?>+'&idBloque='+<?=$idBloque;?>+'&idTema='+<?=$idTema;?>+'&idSubtema='+msg.dataRes[i].id+'" class="btn btn-default"><span class="glyphicon glyphicon-th-list"></span></a></td>'
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
           
           //añadir nuevo
           $('#formAdd').validate({
                rules: {
                    inputName: {required: true}
                },
                messages: {
                    inputName: "Nombre del subtema obligatorio"
                },
                tooltip_options: {
                    inputName: {trigger: "focus", placement: "bottom"}
                },
                submitHandler: function(form){
                    $('#loading').show();
                    $.ajax({
                        type: "POST",
                        url: "../controllers/admin_create_banco_subtema.php",
                        data: $('form#formAdd').serialize(),
                        success: function(msg){
                            var msg = jQuery.parseJSON(msg);
                            if(msg.error == 0){
                                $('#loading').empty();
                                $('#loading').append('<img src="../assets/obj/success_256.png" height="300" width="400" >');
                                setTimeout(function () {
                                  location.href = 'admin_read_banco_subtemas.php?idNivel='+<?=$idNivel;?>+'&idGrado='+<?=$idGrado;?>+'&idMateria='+<?=$idMateria;?>+'&idBloque='+<?=$idBloque;?>+'&idTema='+<?=$idTema;?>;
                                }, 1500);
                            }else{
                                 $('.msgModal').css({color: "#FF0000"});
                                $('.msgModal').html(msg.msgErr);
                                $('#loading').empty();
                                $('#loading').append('<img src="../assets/obj/error.png" height="300" width="400" ><p>'+msg.msgErr+'</p>');
                            }
                        }, error: function(){
                            alert("Error al crear nuevo subtema");
                        }
                    });
                }
            }); // end añadir nueva materia
           
        });
    </script>
    
<?php
    }//end if-else
    include ('footer.php');
?>
