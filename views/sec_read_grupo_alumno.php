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
    }else if($_SESSION['perfil'] != 1.2){
        echo '<div class="row"><div class="col-sm-12 text-center"><h2>¿Estás tratando de acceder? No es tu perfil o(´^｀)o </h2></div></div>';
    }else {
        $idUser = $_SESSION['idEsc'];
        $idPerfil = $_SESSION['perfil'];
        $idGroup = $_GET['idGrupo'];
        
        //Obtenemos informacion del grupo
        $sqlGetGrupoInfo = "SELECT $tGrupo.nombre as grupo, $tGrado.nombre as grado "
                . "FROM $tGrupo INNER JOIN $tGrado ON $tGrado.id=$tGrupo.nivel_grado_id "
                . "WHERE $tGrupo.id='$idGroup' ";
        $resGetGrupoInfo = $con->query($sqlGetGrupoInfo);
        $rowGetGrupoInfo = $resGetGrupoInfo->fetch_assoc();
        $nombreGrupo = $rowGetGrupoInfo['grado'].' - '.$rowGetGrupoInfo['grupo'];
        
?>

    <div class="container">
         <div class="row">
            <div id="loading">
                <img src="../assets/obj/loading.gif" height="300" width="400">
            </div>
        </div>
        <br>
        <div class="table-responsive">
            <table class="table table-striped" id="data">
                <caption>Grupo "<?=$nombreGrupo;?>"</caption>
                <thead>
                    <tr>
                        <th><span title="id">Id</span></th>
                        <th><span title="idAlum">IdAlum</span></th>
                        <th><span title="nombre">Nombre</span></th>
                        <th><span title="user">Usuario</span></th>
                        <th><span title="pass">Contraseña</span></th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
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
                   url: "../controllers/esc_read_grupo_alumno_details.php?idGrupo=<?=$idGroup;?>",
                   success: function(msg){
                       $("#data tbody").html(msg);
                       var msg = jQuery.parseJSON(msg);
                       //console.log(msg);
                       if(msg.error == 0){
                           $("#data tbody").html("");
                           $.each(msg.dataRes, function(i, item){
                                var newRow = '<tr>'
                                         +'<td>'+msg.dataRes[i].id+'</td>'
                                         +'<td>'+msg.dataRes[i].idAlumno+'</td>'
                                         +'<td>'+msg.dataRes[i].nombre+'</td>'
                                         +'<td>'+msg.dataRes[i].user+'</td>'
                                         +'<td>'+msg.dataRes[i].pass+'</td>'
                                    +'</tr>';
                                $(newRow).appendTo("#data tbody");
                            });
                       }else{
                           var newRow = '<tr><td colspan="4">'+msg.msgErr+'</td></tr>';
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
            
            
            
        });
    </script>
    
<?php
    }//end if-else
    include ('footer.php');
?>
