<?php
	
    date_default_timezone_set('America/Mexico_City');
    $host="localhost";
    $user="root";
    $pass="";
    $db="eva_ins";
    $con=mysqli_connect($host, $user, $pass, $db);
    if($con->connect_error){
            die("Connection failed: ".$con->connect_error);
    }
    //echo 'Hola';

    //Tablas Usuarios
        $tAdm = "usuarios_administradores";
        $tEsc = "usuarios_escuelas";
            $tSec = "usuarios_escuelas_secretarias";
        $tProf = "usuarios_profesores";
        $tAlum = "usuarios_alumnos";
        $tTut = "usuarios_tutores";
        $tInfo = "usuarios_informacion";

    //Tablas Niveles
        $tTurn = "niveles_turnos";
        $tNivEsc = "niveles_escolares";
        $tGrado = "niveles_grados";
    
    //Tablas de Banco
        $tBMat = "banco_materias";
        $tBBloq = "banco_bloques";
        $tBTema = "banco_temas";
        $tBSubTema = "banco_subtemas";
        $tBPregs = "banco_preguntas";
        $tBResp = "banco_respuestas";
        
    //Tablas Grupos, materias y alumnos
        $tGrupo = "grupos_info";
        $tGrupoAlums = "grupo_alumnos";
        $tGMatProfs = "grupos_materias_profesores";
        $tGMatAlums = "grupos_materia_alumnos";
        
    //Tablas de exámenes
        $tExaInf = "exa_info";
        $tExaInfAsig = "exa_info_asig";
        $tExaInfAsigAlum = "exa_info_asig_alum";
        $tExaPregs = "exa_preguntas";
        
    //Tablas de resultados de alumno
        $tExaResultInfo = "est_exa_result_info";
        $tExaResultPregs = "est_exa_result_preguntas";
        $tExaTmp = "est_exa_respuestas_tmp";
        $tExaTime = "est_exa_tiempos";
        
    //Tablas de avisos
        $tAvInfo = "aviso_info";
        $tAvAsigA = "aviso_asig_alum";
        $tAvAsigT = "aviso_asig_tutor";
        $tAvTipo = "aviso_tipo";
        
?>