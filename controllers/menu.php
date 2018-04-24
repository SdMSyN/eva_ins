<?php

    if(isset($_SESSION['sessU'])  AND $_SESSION['sessU'] == "true"){
        $cadMenuNavbar='';
        if($_SESSION['perfil'] == "1"){//Escuela
            $cadMenuNavbar .= '<li><a href="index_escuela.php">Menú Escuela</a></li>';
            $cadMenuNavbar .= '<li><a href="esc_read_secretarias.php">Personal de apoyo</a></li>';
            $cadMenuNavbar .= '<li><a href="esc_read_materias.php">Materias</a></li>';
            $cadMenuNavbar .= '<li><a href="esc_read_profesores.php">Profesores</a></li>';
            $cadMenuNavbar .= '<li><a href="esc_read_grupos_info.php">Grupos</a></li>';
            $cadMenuNavbar .= '<li><a href="esc_read_grupos_info_tutores.php">Tutores</a></li>';
            $cadMenuNavbar .= '<li><a href="esc_read_notificaciones.php">Notificaciones</a></li>';
        } else if($_SESSION['perfil'] == "1.2"){//Secretaria
            $cadMenuNavbar .= '<li><a href="index_escuela_secretaria.php">Inicio Secretaria</a></li>';
            $cadMenuNavbar .= '<li><a href="sec_read_grupos_info.php">Grupos</a></li>';
            $cadMenuNavbar .= '<li><a href="sec_read_grupos_info_tutores.php">Tutores</a></li>';
            $cadMenuNavbar .= '<li><a href="sec_read_notificaciones.php">Notificaciones</a></li>';
        } else if($_SESSION['perfil'] == "2"){//Profesor
            $cadMenuNavbar .= '<li><a href="index_profesor.php">Inicio Profesor</a></li>';
            $cadMenuNavbar .= '<li><a href="prof_read_grupos.php">Grupos</a></li>';
            $cadMenuNavbar .= '<li><a href="prof_read_my_pregs.php">Mis preguntas</a></li>';
            $cadMenuNavbar .= '<li><a href="prof_read_exams.php">Exámenes</a></li>';
            $cadMenuNavbar .= '<li><a href="prof_read_exa_info_asigs.php">Resultados</a></li>';
            $cadMenuNavbar .= '<li><a href="prof_read_notificaciones.php">Notificaciones</a></li>';
        } else if($_SESSION['perfil'] == "3"){//Alumno
            $cadMenuNavbar .= '<li><a href="index_estudiante.php">Menú Alumno</a></li>';
            $cadMenuNavbar .= '<li><a href="est_read_exams.php">Exámenes '
                    .'<span class="badge" id="numExas"></span></a></li>';
            $cadMenuNavbar .= '<li><a href="est_read_notificaciones.php">Notificaciones '
                    .'<span class="badge" id="numNots"></span></a></li>';
        } else if($_SESSION['perfil'] == "4"){//Tutor
            $cadMenuNavbar .= '<li><a href="index_tutor.php">Inicio Tutor</a></li>';
            $cadMenuNavbar .= '<li><a href="tut_read_calificaciones.php">Calificaciones'
                    . '<span class="badge" id="numExas"></span></a></li>';
            $cadMenuNavbar .= '<li><a href="tut_read_notificaciones.php">Notificaciones '
                    .'<span class="badge" id="numNots"></span></a></li>';
        } else if($_SESSION['perfil'] == "10"){
            $cadMenuNavbar .= '<li><a href="index_admin.php">Menú Administrador</a></li>';
            $cadMenuNavbar .= '<li><a href="admin_read_banco_niveles.php">Bancos</a></li>';
            $cadMenuNavbar .= '<li><a href="admin_read_escuelas.php">Escuelas</a></li>';
        }else{
            $cadMenuNavbar .= '<li>¿Cómo llegaste hasta acá?</li>';
        }
        echo $cadMenuNavbar;
    }
	
?>