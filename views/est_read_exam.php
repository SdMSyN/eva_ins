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
        
        //buscamos si existe tiempo de inicio del examen y si no lo creamos
        $sqlGetTime = "SELECT id, hora_inicio FROM $tExaTime "
                . "WHERE exa_info_id='$idExam' AND exa_info_asig_alum_id='$idExamAsigAlum' AND alumno_id='$idUser' ";
        //echo $sqlGetTime;
        $resGetTime = $con->query($sqlGetTime);
        if($resGetTime->num_rows > 0){
            $rowGetTime = $resGetTime->fetch_assoc();
            $tiempo = $rowGetTime['hora_inicio'];
            $idExaTime = $rowGetTime['id'];
        }else{
            $sqlInsertTime = "INSERT INTO $tExaTime "
                    . "(exa_info_id, exa_info_asig_alum_id, alumno_id, hora_inicio, creado) "
                    . "VALUES ('$idExam', '$idExamAsigAlum', '$idUser', '$timeNow', '$dateNow') ";
            if($con->query($sqlInsertTime) === TRUE){
                $tiempo = $timeNow;
                $idExaTime = $con->insert_id;
            }else{
                $tiempo = null;
            }
        }
        
        //obtenemos hora limite
        $sqlGetLimitTime = "SELECT ADDTIME($tExaTime.hora_inicio, $tExaInfAsig.tiempo) as hora_final "
                . "FROM $tExaTime "
                . "INNER JOIN $tExaInfAsigAlum ON $tExaInfAsigAlum.id=$tExaTime.exa_info_asig_alum_id "
                . "INNER JOIN $tExaInfAsig ON $tExaInfAsig.id=$tExaInfAsigAlum.exa_info_asig_id "
                . "WHERE $tExaTime.exa_info_id='$idExam' AND $tExaTime.exa_info_asig_alum_id='$idExamAsigAlum' "
                . "AND $tExaTime.alumno_id='$idUser' ";
        //echo $sqlGetLimitTime;
        $resGetLimitTime = $con->query($sqlGetLimitTime);
        $rowGetLimitTime = $resGetLimitTime->fetch_assoc();
        $limitTime = $rowGetLimitTime['hora_final'];
?>

    <div class="container">
        <div id="loader"><img src="../assets/obj/loading.gif"></div>
        <div class="outer_div"></div>
        <div class="col-sm-12 text-center">
            <span id="liveclock"></span>
        </div>
        <div class="col-sm-12 text-center">
            <button type="button" class="btn btn-warning" id="evaluate">Terminar examen 
                <span class="glyphicon glyphicon-screenshot"></span>
            </button>
        </div>
        <div id="dataExa"></div>
    </div>

    <script language="JavaScript" type="text/javascript">
        function show5(){
            if (!document.layers&&!document.all&&!document.getElementById)
            return
            
            var timeBegin = "<?=$limitTime;?>";
            //console.log(timeBegin);
            var timeBegin2 = timeBegin.toString();;
            var horaInicioExa = parseInt(timeBegin2.substr(0,2));
            var minInicioExa = parseInt(timeBegin2.substr(3,2));
            var segInicioExa = parseInt(timeBegin2.substr(4,2));
            //console.log(segInicioExa);
            //sustituir por horario servidor php
            //var Digital = "<?= $timeNowS; ?>";
            /*var Digital = "<?php echo date("G:i:s"); ?>";
            var timeNow = Digital.toString();
            console.log(timeNow);
            var hours = parseInt(timeNow.substr(0,2));
            var minutes = parseInt(timeNow.substr(3,2));
            var seconds = parseInt(timeNow.substr(6,2));*/
             var Digital=new Date()
             var hours=Digital.getHours()
             var minutes=Digital.getMinutes()
             var seconds=Digital.getSeconds()

             var hour = horaInicioExa - hours;
             var min = minInicioExa - minutes;
             var sec = segInicioExa - seconds;
             if (min < 0) {
                hour--;
                min = 60 + min;
              }
              if(sec < 0){
                  min--;
                  sec = 60 + sec;
              }
              var minS=min; var secS=sec;
             if (min<=9)
             minS="0"+min
             if (sec<=9)
             secS="0"+sec
             if(hour <= 0 && min <= 0 && sec <= 0){
                 autoSave();
                $.ajax({
                   method: "POST",
                   url: "../controllers/est_create_exa_result_preguntas.php?idUser=<?=$idUser;?>&idExam=<?=$idExam;?>&idExamAsig=<?=$idExamAsig;?>&idExamAsigAlum=<?=$idExamAsigAlum?>&idExaTime=<?=$idExaTime;?>",
                   success: function(data){
                      //alert(data);
                      console.log(data);
                      var msg = jQuery.parseJSON(data);
                      if(msg.error == 0){
                          $('#loader').empty();
                          $('#loader').append('<img src="../assets/obj/success_256.png" height="300" width="400" >');
                          setTimeout(function () {
                            location.href = 'est_read_exa_result.php?idExam=<?=$idExam;?>&idUser=<?=$idUser;?>&idExamAsig=<?=$idExamAsig;?>&idExamAsigAlum=<?=$idExamAsigAlum;?>';
                          }, 1500);
                      }else{
                          $('#loader').empty();
                          $('#loader').append('<img src="../assets/obj/error.png" height="300" width="400" ><p>'+msg.dataRes+'</p>');
                      }
                   }
               })
            }else{
                //change font size here to your desire
                myclock="<font size='5' face='Arial' ><b><font size='1'>Te quedan:</font></br>"+hour+":"+minS+":"
                 +secS+" </b></font>";
                 //myclock += "Tiempo de finalización: "+timeBegin;
                if (document.layers){
                    document.layers.liveclock.document.write(myclock)
                    document.layers.liveclock.document.close()
                }
                else if (document.all)
                    liveclock.innerHTML=myclock
                else if (document.getElementById)
                    document.getElementById("liveclock").innerHTML=myclock
                setTimeout("show5()",1000)
                }//end else
        }
        window.onload=show5
        //-->
    </script>
     
    <script type="text/javascript">
        //$('#loading').hide();
        var ordenar = '';
        $(document).ready(function(){
            load(1);
            //Para evitar pegar 
            $('body').bind('cut copy paste', function (e) {
                e.preventDefault();
            });
            
            $("#evaluate").click(function(){
               //alert("evaluando..."); 
               autoSave();
               if(confirm("¿Haz terminado ya el examen? No hay vuelta atrás")){
                    $.ajax({
                         method: "POST",
                         url: "../controllers/est_create_exa_result_preguntas.php?idUser=<?=$idUser;?>&idExam=<?=$idExam;?>&idExamAsig=<?=$idExamAsig;?>&idExamAsigAlum=<?=$idExamAsigAlum?>&idExaTime=<?=$idExaTime;?>",
                         success: function(data){
                            //alert(data);
                            console.log(data);
                            var msg = jQuery.parseJSON(data);
                            //console.log(msg.sqls);
                            //console.log(msg.dataRes);
                            if(msg.error == 0){
                                $('#loading').empty();
                                $('#loading').append('<img src="../assets/obj/success_256.png" height="300" width="400" >');
                                setTimeout(function () {
                                  location.href = 'est_read_exa_result.php?idExam=<?=$idExam;?>&idUser=<?=$idUser;?>&idExamAsig=<?=$idExamAsig;?>&idExamAsigAlum=<?=$idExamAsigAlum;?>';
                                }, 1500);
                            }else{
                                $('#loading').empty();
                                $('#loading').append('<img src="../assets/obj/error.png" height="300" width="400" ><p>'+msg.dataRes+'</p>');
                            }
                         }
                     })
                }else{
                    alert("Revisa minuciosamente");
                }
            });
        });
        
        function autoSave(){
            //alert("Welcome to the Mictlan");
            var idPreg = $("#idPreg").val();
            var tipoResp = $("#tipoR").val();
            var idUser = <?=$idUser;?>;
            var idExam = <?=$idExam;?>;
            var idExamAsig = <?=$idExamAsig;?>;
            var idExamAsigAlum = <?=$idExamAsigAlum;?>;
            //alert(idExamAsigAlum);
            parametros2 = {
                "idUser":idUser,
                "idExam":idExam,
                "idExamAsig":idExamAsig,
                "idExamAsigAlum":idExamAsigAlum,
                "idPreg":idPreg,
                "tipoResp":tipoResp
            };
            if(tipoResp == 1){
                var idRadio = $("input[name=radio]:checked").val();
                //alert(idRadio);
                parametros2.resp=idRadio;
            }else if(tipoResp == 2){
                var arCheck = [];
                $("#check:checked").each(function(){
                    arCheck.push($(this).val());
                    parametros2.resp=arCheck;
                })
                //alert(arCheck);
            }else if(tipoResp == 3){
                var words = $("#text").val();
                var idText = $("#idText").val();
                //alert(words);
                 parametros2.resp=words;
                 parametros2.idText=idText;
            }else if(tipoResp == 4){
                var word = $("#text").val();
                var idText = $("#idText").val();
                //alert(word);
                 parametros2.resp=word;
                 parametros2.idText=idText;
            }
            
                //alert(parametros.idPreg);
            //alert('idUser:'+idUser+', idExam:'+idExam+', idPreg:'+idPreg+', tipoResp:'+tipoResp);
            //alert(form);
            $.ajax({
                method: "POST",
                url: "../controllers/create_resp_exa_tmp.php",
                data: parametros2, //eres un pendejo, solo había que quitar las llaves ¡imbecil!
                //dataType: "json",
                success: function(data){
                    //alert(data);
                }
            })
        }
        
        //paginación
        // http://obedalvarado.pw/blog/paginacion-con-php-mysql-jquery-ajax-y-bootstrap/
        function load(page){
            var parametros = {"action": "ajax", "page": page};
            $("#loader").fadeIn('slow');
            $.ajax({
                url: "../controllers/est_read_exam.php?idExam="+<?=$idExam;?>+'&idUser='+<?=$idUser;?>+'&idExamAsig='+<?=$idExamAsig;?>+"&idExamAsigAlum="+<?=$idExamAsigAlum?>,
                data: parametros,
                beforeSend: function(objeto){
                    $("#loader").html('<img src="../assets/obj/loading.gif" height="300" width="400">');
                },
                success: function(data){
                    console.log(data);
                    var msg = jQuery.parseJSON(data);
                        if(msg.error == 0){
                            $("#loader").html("");
                            $("#data tbody").html("");
                            $("#dataExa").html("");
                            //alert(data);
                            $.each(msg.dataPregs, function(i, item){
                                var newPreg = '<div class="row">'
                                    +'<div class="col-sm-12 text-center">'
                                        +'<input type="hidden" id="idPreg" name="idPreg" value="'+msg.dataPregs[i].id+'">'
                                        +'<input type="hidden" id="tipoR" name="tipoR" value="'+msg.dataPregs[i].tipoR+'">'
                                        +'<p class="text-center">'
                                        //+msg.dataPregs[i].id+'.-'
                                        +msg.dataPregs[i].nombre+'</p>';
                                        newPreg += '<p class="text-center">(Valor de la pregunta: <b>'+msg.dataPregs[i].valorPreg;
                                        newPreg += (msg.dataPregs[i].valorPreg > 1) ? ' puntos</b>)</p>' : ' punto</b>)</p>'; 
                                        //+'<p class="text-center">**'+msg.dataPregs[i].tmp+'**</p>'
                                    newPreg += '</div></div>';
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
                                        newResp += '<div class="radio"><label>';
                                        if(msg.dataPregs[i].resps[j].archivo != null) 
                                            newResp += '<img src="../<?=$filesExams;?>/'+msg.dataPregs[i].resps[j].archivo+'" class="img-responsive center-block" >';
                                        //newResp += '<label>'+msg.dataPregs[i].resps[j].nombre;
                                        if(msg.dataPregs[i].resps[j].seleccionada == true || msg.dataPregs[i].resps[j].seleccionada == "true")
                                            newResp += '<input type="radio" name="radio" id="radio" value="'+msg.dataPregs[i].resps[j].id+'" checked>';
                                        else
                                            newResp += '<input type="radio" name="radio" id="radio" value="'+msg.dataPregs[i].resps[j].id+'">';
                                        newResp += msg.dataPregs[i].resps[j].nombre;
                                        newResp += '</label></div>';
                                    }else if(msg.dataPregs[i].resps[j].tipoR == 2){
                                        newResp += '<div class="col-sm-6">';
                                        if(msg.dataPregs[i].resps[j].archivo != null) 
                                            newResp += '<img src="../<?=$filesExams;?>/'+msg.dataPregs[i].resps[j].archivo+'" class="img-responsive center-block" >';
                                        newResp += '<label>'+msg.dataPregs[i].resps[j].nombre+'</label>';
                                        if(msg.dataPregs[i].resps[j].seleccionada == true || msg.dataPregs[i].resps[j].seleccionada == "true")
                                            newResp += '<input type="checkbox" class="form-control" name="check" id="check" value="'+msg.dataPregs[i].resps[j].id+'" checked>';
                                        else
                                            newResp += '<input type="checkbox" class="form-control" name="check" id="check" value="'+msg.dataPregs[i].resps[j].id+'">';
                                        newResp += '</div>';
                                    }else if(msg.dataPregs[i].resps[j].tipoR == 3){
                                        newResp += '<div class="col-sm-12">';
                                            newResp += '<input type="hidden" id="idText" value="'+msg.dataPregs[i].resps[j].id+'" >';
                                            //newResp += 'consulta: '+msg.dataPregs[i].resps[j].que;
                                            if(msg.dataPregs[i].resps[j].texto != "")
                                                newResp += '<input type="text" class="form-control" name="text" id="text" value="'+msg.dataPregs[i].resps[j].texto+'" >';
                                            else 
                                                newResp += '<input type="text" class="form-control" name="text" id="text" >';
                                        newResp += '</div>';
                                    }else if(msg.dataPregs[i].resps[j].tipoR == 4){
                                        newResp += '<div class="col-sm-12">';
                                            newResp += '<input type="hidden" id="idText" value="'+msg.dataPregs[i].resps[j].id+'" >';
                                            if(msg.dataPregs[i].resps[j].texto != "")
                                                newResp += '<input type="text" class="form-control" name="text" id="text" value="'+msg.dataPregs[i].resps[j].texto+'" >';
                                            else
                                                newResp += '<input type="text" class="form-control" name="text" id="text" >';
                                        newResp += '</div>';
                                    }else{
                                        newResp += '<div class="row">Tipo de respuesta inexistente.</div>';
                                    }
                                    //newResp += '</div><!-- end row -->';
                                    $(newResp).appendTo("#dataExa");
                                })
                           });
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