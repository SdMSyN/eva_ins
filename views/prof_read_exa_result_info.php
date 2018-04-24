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
        $idAsig = $_GET['idAsig'];
        
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
                <caption>Tus examenes asignados</caption>
                <thead>
                    <tr>
                        <th><span title="idExaAsig">Id</span></th>
                        <th><span title="nameExa">Nombre Alumno</span></th>
                        <th><span title="nameExa">Preguntas respondidas</span></th>
                        <th><span title="createdExa">Preguntas no respondidas</span></th>
                        <th><span title="grado">Respuestas correctas</span></th>
                        <th><span title="numPregs">Respuestas incorrectas</span></th>
                        <th><span title="numAlums">Valor obtenido</span></th>
                        <th><span title="numEvals">Calificación</span></th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <div class="row">
            <div class="col-sm-6"><div id="chart_pie_div" ></div></div>
            <div class="col-sm-6"><div id="chart_colum_div" ></div></div>
        </div>
    </div>

        <!-- gráficas -->
    <!--Load the AJAX API-->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript" async>
      function drawChartsGogole(){
        // Load the Visualization API and the corechart package.
        google.charts.load('current', {'packages':['corechart']});
        // Set a callback to run when the Google Visualization API is loaded.
        google.charts.setOnLoadCallback(drawChart);
        google.charts.setOnLoadCallback(drawAnthonyChart);
      }
      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart() {
        // Create the data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Topping');
        data.addColumn('number', 'Slices');
        data.addRows([
          ['Aprobados', apr],
          ['Reprobados', rep]
        ]);
        // Set chart options
        var options = {'title':'Porcentaje de aprobación',
                       'width':400,
                       'height':300,
                       'is3D':true
                   };
        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById('chart_pie_div'));
        chart.draw(data, options);
      }
      
      // Callback that draws the pie chart for Anthony's pizza.
      function drawAnthonyChart() {

        var data = google.visualization.arrayToDataTable([
            ["Element", "Alumnos", { role: "style" } ],
            ["0 - 3.9", r1, "#FF0000"],
            ["4 - 5.9", r2, "#FF8000"],
            ["6 - 7.9", r3, "#FFFF00"],
            ["8 - 10", r4, "#40FF00"]
          ]);

          /*var view = new google.visualization.DataView(data);
          view.setColumns([0, 1,
                           { calc: "stringify",
                             sourceColumn: 1,
                             type: "string",
                             role: "annotation" },
                           2]);*/

          var options = {
            title: "Rango de calificaciones",
            width: 400,
            height: 300,
            bar: {groupWidth: "65%"},
            legend: { position: "none" },
          };

        // Instantiate and draw the chart for Anthony's pizza.
        var chart = new google.visualization.ColumnChart(document.getElementById('chart_colum_div'));
        chart.draw(data, options);
      }
      
    </script>
    
    <script type="text/javascript">
        $('#loading').hide();
        var ordenar = '';
        var r1 = 0, r2=0, r3=0, r4=0, apr=0, rep=0;
        $(document).ready(function(){
            
            filtrar();
            function filtrar(){
               $.ajax({
                   type: "POST",
                   data: ordenar, 
                   url: "../controllers/prof_read_exa_result_info.php?idAsig=<?=$idAsig;?>",
                   success: function(msg){
                       console.log(msg);
                       var msg = jQuery.parseJSON(msg);
                       if(msg.error == 0){
                           $("#data tbody").html("");
                           $.each(msg.dataRes, function(i, item){
                                var newRow = '<tr>'
                                         +'<td>'+msg.dataRes[i].idAsigAlum+'</td>'
                                         +'<td>'+msg.dataRes[i].nombreAlum+'</td>';
                                         newRow += (msg.dataRes[i].pregResp != null) ? '<td>'+msg.dataRes[i].pregResp+'</td>' : '<td>0</td>';
                                         newRow += (msg.dataRes[i].pregNoResp != null) ? '<td>'+msg.dataRes[i].pregNoResp+'</td>' : '<td>0</td>';
                                         newRow += (msg.dataRes[i].pregCorr != null) ? '<td>'+msg.dataRes[i].pregCorr+'</td>' : '<td>0</td>';
                                         newRow += (msg.dataRes[i].pregIncorr != null) ? '<td>'+msg.dataRes[i].pregIncorr+'</td>' : '<td>0</td>';
                                         newRow += (msg.dataRes[i].valorAlum != null) ? '<td>'+msg.dataRes[i].valorAlum+'</td>' : '<td>0</td>';
                                         newRow += (msg.dataRes[i].califAlum != null) ? '<td>'+msg.dataRes[i].califAlum+'</td>' : '<td>0</td>';
                                    newRow += '</tr>';
                                $(newRow).appendTo("#data tbody");
                                var califTmp = parseInt(msg.dataRes[i].califAlum);
                                switch (true){
                                   /*case(califTmp == null):
                                        r1++;
                                        rep++;
                                        break;*/
                                   case (msg.dataRes[i].califAlum == null || califTmp <= 3.9):
                                       r1++;
                                       rep++;
                                       break;
                                   case (califTmp >= 4 && califTmp <= 5.9):
                                       r2++;
                                       rep++;
                                       break;
                                   case (califTmp >= 6 && califTmp <= 7.9):
                                       r3++;
                                       apr++;
                                       break;
                                   case (califTmp >= 8 && califTmp <= 10):
                                       r4++;
                                       apr++;
                                       break;
                                   default:
                                       break;
                                }
                            });
                       }else{
                           var newRow = '<tr><td colspan="9">'+msg.msgErr+'</td></tr>';
                           $("#data tbody").html(newRow);
                       }
                       setTimeout(drawChartsGogole(), 2000);
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
