<?php

include('validacion.php');



//Conectamos con base de datos
try{
    $db = new PDO('mysql:host=localhost;dbname=ToDo;charset=utf8mb4','lander','78563');
}catch(PDOException $e) {
    echo "ERROR" . $e->getMessage();
    die();
}

// Cogemos el id de la tarea a mostrar
if(isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
}else{
    header('Location: 404.html');
}

// cogemos el id de la tarea a editar
if(isset( $_GET["editar"])) {
    $editar = $_GET["id"];
}

//Editartarea
if(isset($_POST["editar"]) && empty($errores)){
    $edit = $db->prepare("UPDATE ToDo SET descripcion = :nuevaDescripcion WHERE id = :id");
    $edit->bindParam(":id", $idEditada, PDO::PARAM_INT);
    $edit->bindParam(":nuevaDescripcion", $nuevaDescripcion, PDO::PARAM_STR);
    $resultado = $edit->execute();
    header("location: detalle.php?id=$idEditada");
    exit;
}

//Realizamos la consulta para obtener todo de la tarea a detallar
$consulta = $db->prepare("SELECT * FROM ToDo WHERE id = :idTarea");
$consulta->bindParam(':idTarea', $id, PDO::PARAM_INT);
$results = $consulta->execute();
$datosTarea = $consulta->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Detalle</title>
</head>
<body>
        <h1><?=$datosTarea['tarea']?></h1>
        <h2>Prioridad: <?=$datosTarea['prioridad']?></h2>
        <?php if(isset($datosTarea['descripcion']) && $datosTarea['descripcion'] != "" ){?>
            <div><p><?=$datosTarea['descripcion']?></p></div><?php
        }else{?>
            <div><p>Esta tarea no tiene descripción</p></div><?php
        }?>        
    <?php ?>  

    <div id="formularioDetalle">
        <form action="#" method="post">
            <input type="hidden" name="id" value="<?=$id?>">
            <label for="nuevaDescripcion">Nueva descripción:</label><br>
            <textarea name="nuevaDescripcion" placeholder="Nueva Descripción"></textarea><br>
            <?php if(isset($errores['nuevaDescripcion'])) { ?>
                    <span class="error"><?=$errores['nuevaDescripcion']?></span><br>
                    <?php } ?>
            <input type="submit" value="Editar tarea" name="editar">
        </form>
        <?php if(isset($exito['descripcion'])) { ?>
                <span class="exito"><?=$exito['descripcion']?></span><br>
                <?php } ?>
    </div>
    <a href="ToDo.php"><img src="img/volver.png" class="back-button"></a>
</body>
</html>