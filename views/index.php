<?php
    include ('header.php');
    include('../config/variables.php');
?>

<title><?=$tit;?></title>
<meta name="author" content="Luigi Pérez Calzada (GianBros)" />
<meta name="description" content="Descripción de la página" />
<meta name="keywords" content="etiqueta1, etiqueta2, etiqueta3" />
<link href="../assets/css/login.css" rel="stylesheet">
</head>
    <body>
<?php
    include ('navbar.php');
?>

    <div class="container">
        <div class="row">
            <div id="loading">
                <img src="../assets/obj/loading.gif" height="300" width="400">
            </div>
        </div>
        <form class="form-signin" method="POST" id="formLogin">
            <h2 class="form-signin-heading">Iniciar Sesión</h2>
            <!--<div class="text-center"><img src="assets/obj/carousel_0.jpg" alt="" width="75%" class="img-rounded"/></div>-->
            <div class="row msg"></div>
            <label for="inputUser" class="sr-only">Usuario</label>
            <input type="text" id="inputUser" name="inputUser" class="form-control" placeholder="Usuario" >
            <label for="inputPass" class="sr-only">Contraseña</label>
            <input type="password" id="inputPass" name="inputPass" class="form-control" placeholder="Contraseña" >
            <button class="btn btn-lg btn-primary btn-block" type="submit">Iniciar sesión</button>
            <hr>
            <h3>Contacto</h3>
            <p><b>Celular/WhatsApp:</b> 246-126-85-29</p>
            <p><b>Facebook:</b> <a href="https://www.facebook.com/innovacionydesarrolloeducativo/?fref=ts" target="_blank">Innovación y desarrollo educativo</a></p>
        </form>
    </div>

    <script type="text/javascript">
        $('#loading').hide();
        $(document).ready(function(){
            $('#formLogin').validate({
                rules: {
                    inputUser: {required: true},
                    inputPass: {required: true}
                },
                messages: {
                    inputUser: "Usuario obligatorio",
                    inputPass: "Contraseña obligatoria"
                },
                tooltip_options: {
                    inputUser: {trigger: "focus", placement: 'right'},
                    inputPerfil: {trigger: "focus", placement: 'right'}
                },
                beforeSend: function(){
                    $('.msg').html('loading...');
                },
                submitHandler: function (form) {
                    $('#loading').show();
                    $.ajax({
                        type: "POST",
                        url: "../controllers/login_user.php",
                        data: $('form#formLogin').serialize(),
                        success: function (msg) {
                            //alert(msg);
                            var msg = jQuery.parseJSON(msg);
                            if(msg.error == 0){
                                $('#loading').empty();
                                $('#loading').append('<img src="../assets/obj/success_256.png" height="300" width="400" >');
                                if(msg.perfil == 1) location.href="index_escuela.php";
                                if(msg.perfil == 1.2) location.href="index_escuela_secretaria.php";
                                else if(msg.perfil == 2) location.href="index_profesor.php";
                                else if(msg.perfil == 3) location.href="index_estudiante.php";
                                else if(msg.perfil == 4) location.href="index_tutor.php";
                                else if(msg.perfil == 10) location.href="index_admin.php";
                                else location.href="#";
                            }else{
                                $('#loading').empty();
                                $('#loading').append('<img src="../assets/obj/error.png" height="300" width="400" >'
                                        +'<h2>'+msg.msgErr+'</h2><h2>Verifica que tus datos sean correctos con tu institución.</h2></p>');
                                setTimeout(function (){
                                    $('#loading').hide();
                                },3000);
                            }
                        },
                        error: function () {
                            alert("Error al iniciar sesión de usuario");
                        }		
                    });
                }
            });
        });
    </script>
    
<?php
    include ('footer.php');
?>