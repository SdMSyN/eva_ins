<?php
    session_start();
    include ('../config/conexion.php');
    $user = $_POST['inputUser'];
    $pass = $_POST['inputPass'];
    
    $cadErr = '';
    $ban =false;
    $perfil = 0;
    
    $sqlGetUser = "SELECT $tEsc.id as id, $tEsc.informacion_id as idInfo, "
            . "$tEsc.nombre as name, $tEsc.nivel_escolar_id as nivelEsc FROM $tEsc "
            . "WHERE BINARY $tEsc.user='$user' AND BINARY $tEsc.pass='$pass' ";
    $resGetUser=$con->query($sqlGetUser);
    if($resGetUser->num_rows > 0){
        $rowGetUser=$resGetUser->fetch_assoc();
        $_SESSION['sessU'] = true;
        $_SESSION['userId'] = $rowGetUser['id'];
        $_SESSION['userName'] = $rowGetUser['name'];
        $_SESSION['perfil'] = 1;
        $_SESSION['nivelEsc'] = $rowGetUser['nivelEsc'];
        $perfil = 1;
        $ban = true;
    }
    else{ // Si no esta en escuela lo buscamos en los profesores
        $sqlGetUser = "SELECT $tProf.id as id, $tProf.informacion_id as idInfo, "
                . "$tProf.nombre as name, $tProf.escuela_id as idEsc FROM $tProf "
                . "WHERE BINARY $tProf.user='$user' AND BINARY $tProf.pass='$pass' ";
        $resGetUser=$con->query($sqlGetUser);
        if($resGetUser->num_rows > 0){
            $rowGetUser=$resGetUser->fetch_assoc();
            $_SESSION['sessU'] = true;
            $_SESSION['userId'] = $rowGetUser['id'];
            $_SESSION['userName'] = $rowGetUser['name'];
            $_SESSION['idEsc'] = $rowGetUser['idEsc'];
            $_SESSION['perfil'] = 2;
            $perfil = 2;
            $ban = true;
        }
        else{ // Si no esta en profesores lo buscamos en alumnos
            $sqlGetUser = "SELECT $tAlum.id as id, $tAlum.informacion_id as idInfo, "
                    . "$tAlum.nombre as name FROM $tAlum "
                    . "WHERE BINARY $tAlum.user='$user' AND BINARY $tAlum.pass='$pass' ";
            $resGetUser=$con->query($sqlGetUser);
            if($resGetUser->num_rows > 0){
                $rowGetUser=$resGetUser->fetch_assoc();
                $_SESSION['sessU'] = true;
                $_SESSION['userId'] = $rowGetUser['id'];
                $_SESSION['userName'] = $rowGetUser['name'];
                $_SESSION['perfil'] = 3;
                $perfil = 3;
                $ban = true;
            }
            else{ // Si no esta en alumnos lo buscamos en tutores
                $sqlGetUser = "SELECT $tTut.id as id, $tTut.informacion_id as idInfo, "
                        . "$tTut.nombre as name, $tTut.alumno_id as idAlum FROM $tTut "
                        . "WHERE BINARY $tTut.user='$user' AND BINARY $tTut.pass='$pass' ";
                $resGetUser=$con->query($sqlGetUser);
                if($resGetUser->num_rows > 0){
                    $rowGetUser=$resGetUser->fetch_assoc();
                    $_SESSION['sessU'] = true;
                    $_SESSION['userId'] = $rowGetUser['id'];
                    $_SESSION['userName'] = $rowGetUser['name'];
                    $_SESSION['perfil'] = 4;
                    $_SESSION['idAlum'] = $rowGetUser['idAlum'];
                    $perfil = 4;
                    $ban = true;
                }
                else{ // Si no esta en tutores lo buscamos en administradores
                    $sqlGetUser = "SELECT $tAdm.id as id, $tAdm.nombre as name FROM $tAdm "
                            . "WHERE BINARY $tAdm.user='$user' AND BINARY $tAdm.pass='$pass' ";
                    $resGetUser=$con->query($sqlGetUser);
                    if($resGetUser->num_rows > 0){
                        $rowGetUser=$resGetUser->fetch_assoc();
                        $_SESSION['sessU'] = true;
                        $_SESSION['userId'] = $rowGetUser['id'];
                        $_SESSION['userName'] = $rowGetUser['name'];
                        $_SESSION['perfil'] = 10;
                        $perfil = 10;
                        $ban = true;
                    }
                    else{ // Si no existe lo buscamos en personal de la escuela
                        $sqlGetUser = "SELECT $tSec.id as id, $tSec.informacion_id as idInfo, "
                                . "$tSec.nombre as name, $tSec.escuela_id as idEsc, $tEsc.nivel_escolar_id "
                                . "FROM $tSec INNER JOIN $tEsc ON $tEsc.id=$tSec.escuela_id "
                                . "WHERE BINARY $tSec.user='$user' AND BINARY $tSec.pass='$pass' ";
                        $resGetUser=$con->query($sqlGetUser);
                        if($resGetUser->num_rows > 0){
                            $rowGetUser=$resGetUser->fetch_assoc();
                            $_SESSION['sessU'] = true;
                            $_SESSION['userId'] = $rowGetUser['id'];
                            $_SESSION['userName'] = $rowGetUser['name'];
                            $_SESSION['idEsc'] = $rowGetUser['idEsc'];
                            $_SESSION['nivelEsc'] = $rowGetUser['nivel_escolar_id'];
                            $_SESSION['perfil'] = 1.2;
                            $perfil = 1.2;
                            $ban = true;
                        }
                        else{ // Definitivamente no existe
                            $_SESSION['sessU']=false;
                            //echo "Error en la consulta<br>".$con->error;
                            $cadErr = "Usuario y/o contraseña incorrecta";
                            $ban = false;
                        }
                    }
                }
            }
        }
    }
    
    if($ban){
        echo json_encode(array("error"=>0, "perfil"=>$perfil));
    }else{
        echo json_encode(array("error"=>1, "msgErr"=>$cadErr));
    }
?>