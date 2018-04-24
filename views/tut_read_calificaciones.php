<?php
    include ('header.php');
    include('../config/variables.php');
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
    }else if($_SESSION['perfil'] != 4){
        echo '<div class="row"><div class="col-sm-12 text-center"><h2>¿Estás tratando de acceder? No es tu perfil o(´^｀)o </h2></div></div>';
    }
    else {
        $idPerfil = $_SESSION['perfil'];
        $idUser = $_SESSION['userId'];
        $idAlum = $_SESSION['idAlum'];
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
                <caption>Las calificaciones</caption>
                <thead>
                    <tr>
                        <th><span title="id">Id</span></th>
                        <th><span title="nameExa">Nombre</span></th>
                        <th><span title="nameMat">Materia</span></th>
                        <th><span title="nameProf">Profesor</span></th>
                        <th><span title="inicio">Periodo</span></th>
                        <th><span title="numPregs">Preguntas</span></th>
                        <th>Resultado</th>
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
           $('[data-toggle="tooltip"]').tooltip();
            
            filtrar();
            function filtrar(){
               $.ajax({
                   type: "POST",
                   data: ordenar, 
                   url: "../controllers/est_read_exams.php?idUser="+<?=$idAlum;?>,
                   success: function(msg){
                       //alert(msg);
                       console.log(msg);
                       $("#data tbody").html(msg);
                       var msg = jQuery.parseJSON(msg);
                       var countExa = 0;
                       if(msg.error == 0){
                           //alert(msg.dataRes[0].id);
                           $("#data tbody").html("");
                           $.each(msg.dataRes, function(i, item){
                               var newRow = '<tr>'
                                    +'<td>'+msg.dataRes[i].idExa+'</td>'   
                                    +'<td>'+msg.dataRes[i].nombre+'</td>'   
                                    +'<td>'+msg.dataRes[i].materia+'</td>'   
                                    +'<td>'+msg.dataRes[i].prof+'</td>';   
                                newRow += '<td>('+msg.dataRes[i].inicio+' ['+msg.dataRes[i].inicioT+'] - '
                                        +msg.dataRes[i].fin+' ['+msg.dataRes[i].finT+']) ';
                                newRow += (msg.dataRes[i].disp == true) ? '[Disponible] </td>' : '[No disponible] </td>';
                                newRow += '<td>'+msg.dataRes[i].numPregs+'</td>';
                                //newRow += (msg.dataRes[i].calif == null) ? '<td></td>' : '<td>'+msg.dataRes[i].calif+'</td>';
                                if(msg.dataRes[i].calif == null ){
                                    if(msg.dataRes[i].disp == true){
                                        newRow += '<td>Disponible</td>';
                                        countExa++;
                                    }else
                                        newRow += '<td>Se le paso la fecha</td>';
                                }else{
                                    newRow += '<td>'+msg.dataRes[i].calif+'</td>';
                                }  
                                newRow += '</tr>';
                                $(newRow).appendTo("#data tbody");
                           });
                       }else{
                           var newRow = '<tr><td></td><td>'+msg.msgErr+'</td></tr>';
                           $("#data tbody").html(newRow);
                       }
                       $("#numExas").html(countExa);
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
