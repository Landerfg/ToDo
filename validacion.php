<?php
$errores = [];
$exito = [];
$tarea = "";
$prioridad = "";
$correo = "";
$descripcion = ""; 
$nuevaDescripcion = "";
$idEditada = "";

// Form añadir Tareas
if(isset($_POST["enviar"])){
    //tarea
    if(isset($_POST["tarea"]) && $_POST["tarea"] != ""){
        $tarea = $_POST["tarea"];
    }else{
        $errores['tarea'] = '*Este campo es obligatorio';
    }
    //Prioridad
    if(isset($_POST['prioridad']) && $_POST["prioridad"] != "") {
        $prioridad = $_POST["prioridad"];
    }else{
        $errores['prioridad'] = '*Marca un nivel de prioridad';
    }
    if(isset($_POST['correo']) && $_POST["correo"] != "" && (filter_var($_POST["correo"], FILTER_VALIDATE_EMAIL))){
        $correo = $_POST["correo"];
    }else{
        $errores['correo'] = '*El correo introducido no es correcto';
    }
    if(isset($_POST['descripcion']) && $_POST["descripcion"] != ""){
        $descripcion = $_POST["descripcion"];
    }
    if(empty($errores)){
        $exito['tarea'] = '*La tarea ha sido añadida';
    }
}

//Form Editartarea
if(isset($_POST["editar"])){
    if(isset($_POST['nuevaDescripcion']) && $_POST["nuevaDescripcion"] != ""){
        $nuevaDescripcion = $_POST["nuevaDescripcion"];
    }else{
        $errores['nuevaDescripcion'] = '*El campo es obligatorio';
    }
    if(isset($_POST['id'])){
        $idEditada = $_POST["id"];
    }
    if(empty($errores)){
        $exito['descripcion'] = '*La descripción ha sido modificada';
    }
}


//Form enviarRecordatorios
if(isset($_POST["enviarCorreos"])){
    if(isset($_POST['captcha'])){
        if($_POST["captcha"] == "No"){
            $exito['RecordatoriosEnviados'] = '*Los recordatorios han sido enviados';
        }else{
            $errores['robot'] = '*Los robots no pueden enviar correos';
        }
    }else{
        $errores['captcha'] = '*Tienes que elegir una opción';
    }
}



?>