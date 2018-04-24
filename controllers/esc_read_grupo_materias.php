<?php
    include ('../config/conexion.php');
    include('../config/variables.php');
    $idGroup = $_GET['idGrupo'];

    $msgErr = '';
    $ban = true;
    $arrMaterias = array();
    

    $sqlGetMats = "SELECT $tGMatProfs.id as idMatProf, $tGMatProfs.usuario_profesor_id as idProf, "
            . "$tProf.nombre as nameProf, $tBMat.nombre as nameMat, $tGMatProfs.banco_materia_id as idMat "
            . "FROM $tGMatProfs "
            . "INNER JOIN $tProf ON $tProf.id=$tGMatProfs.usuario_profesor_id "
            . "INNER JOIN $tBMat ON $tBMat.id=$tGMatProfs.banco_materia_id "
            . "WHERE $tGMatProfs.grupo_info_id='$idGroup'  ";
    $resGetMats = $con->query($sqlGetMats);
    if($resGetMats->num_rows > 0){
        while($rowGetMat = $resGetMats->fetch_assoc()){
            $idMatProf = $rowGetMat['idMatProf'];
            $idProf = $rowGetMat['idProf'];
            $nameProf = $rowGetMat['nameProf'];
            $idMat = $rowGetMat['idMat'];
            $nameMat = $rowGetMat['nameMat'];
            $arrMaterias[] = array('idMat'=>$idMat, 'nameMat'=>$nameMat, 
                    'idProf'=>$idProf, 'nameProf'=>$nameProf, 'idMatProf'=>$idMatProf);
        }
    }else{
        $ban = false;
        $msgErr .= 'No existen alumnos en éste grupo.'.$con->error;
    }

    
    
    if($ban){
        echo json_encode(array("error"=>0, "dataRes"=>$arrMaterias));
    }else{
        echo json_encode(array("error"=>1, "msgErr"=>$msgErr));
    }
?>

<?php
/*
    include ('../config/conexion.php');
    include('../config/variables.php');
    $idGroup = $_GET['idGrupo'];

    $msgErr = '';
    $ban = true;
    $arrMat = array();
    
    //Buscamos materias del grupo
    $sqlGetMats = "SELECT $tGMatProfs.id as idMatProf, $tProf.nombre as nameProf "
            . "FROM $tGMatProfs INNER JOIN $tProf ON $tProf.id=$tGMatProfs.usuario_profesor_id "
            . "WHERE $tGMatProfs.grupo_info_id='$idGroup'  ";
    $resGetMats = $con->query($sqlGetMats);
    if($resGetMats->num_rows > 0){
        while($rowGetMat = $resGetMats->fetch_assoc()){
            $idMatProf = $rowGetMat['idMatProf'];
            $nameProf = $rowGetMat['nameProf'];
            $sqlGetAlums = "SELECT $tGrupoAlums.alumno_id as idAlum, $tAlum.nombre as nameAlum "
                    . "FROM $tGrupoAlums INNER JOIN $tAlum ON $tAlum.id=$tGrupoAlums.alumno_id "
                    . "WHERE $tGrupoAlums.grupo_id='$idGroup' ";
            $resGetAlums = $con->query($sqlGetAlums);
            if($resGetAlums->num_rows > 0){
                while($rowGetAlum = $resGetAlums->fetch_assoc()){
                    $idAlum = $rowGetAlum['idAlum'];
                    $nameAlum = $rowGetAlum['nameAlum'];
                    $sqlGetAlumMat = "SELECT * FROM $tGMatAlums "
                            . "WHERE grupo_materia_profesor_id='$idMatProf' AND usuario_alumno_id='$idAlum' ";
                    $resGetAlumMat = $con->query($sqlGetAlumMat);
                    if($resGetAlumMat->num_rows > 0){//existe la materia en el alumno
                        
                    }else{//no existe la materia en el alumno
                        
                    }
                }
            }else{
                $ban = false;
                $msgErr .= 'No existen alumnos en éste grupo.'.$con->error;
            }
        }
    }else{
        $ban = false;
        $msgErr .= 'No existen materias en éste grupo.'.$con->error;
    }
    //Buscamos Alumos del grupo
    
    //Buscamos si existe relación alumno_materias & materias_profesor
    
    
    if($ban){
        echo json_encode(array("error"=>0, "dataRes"=>$arrAlumno));
    }else{
        echo json_encode(array("error"=>1, "msgErr"=>$msgErr));
    }
 
 */
?>