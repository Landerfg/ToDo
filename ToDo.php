<?php
// Para que salgan los errrores al cargar la página
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('validacion.php');
include('EmailConfig.php');

require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


//Trabajamos con la BD
try {
    //Conectamos con BD
    $db = new PDO('mysql:host=localhost;dbname=ToDo;charset=utf8mb4', 'lander', '78563');
} catch (PDOException $e) {
    echo "ERROR" . $e->getMessage();
    die();
}

//Definimos constantes
define('NUM_ELEM_POR_PAG', 5); // Numero de elementos por página
define('NUM_COLUMNS', 4); // Numero de elementos por columna en la tabla

//definimos :orderbay y $order
if (isset($_GET['orderby']) && is_numeric($_GET['orderby']) && 1 <= $_GET['orderby'] && $_GET['orderby'] <= NUM_COLUMNS) {
    $orderby = $_GET['orderby'];
} else {
    $orderby = 1;
}
if (isset($_GET['order'])) {
    if ($_GET['order'] == 'ASC') {
        $order = 'ASC';
    } else {
        $order = 'DESC';
    }
} else {
    $order = 'ASC';
}
;

// Para saber que página estamos o pulsamos
if (isset($_GET['page']) && is_numeric($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}
;

function generateQueryString($ordenapintar, $orderby, $order)
{
    if ($ordenapintar == $orderby) { // Invierte orden
        $newOrder = ($order == "ASC") ? "DESC" : "ASC";
        return "?orderby=$ordenapintar&order=$newOrder";
    } else {
        return "?orderby=$ordenapintar&order=DESC";
    }
}

function mantenerDatosPag($page, $orderby, $order)
{
    return "?page=$page&orderby=$orderby&order=$order";
}

// Para añadir tareas
if (isset($_POST["tarea"]) && ($_POST["prioridad"]) && ($_POST["correo"])) {
    $añadirTarea = $db->prepare("INSERT INTO ToDo (tarea,prioridad,correo,descripcion) VALUES (:tarea, :prioridad, :correo, :descripcion)");
    $añadirTarea->bindParam(':tarea', $tarea, PDO::PARAM_STR);
    $añadirTarea->bindParam(':prioridad', $prioridad, PDO::PARAM_INT);
    $añadirTarea->bindParam(':correo', $correo, PDO::PARAM_STR);
    if (isset($_POST["descripcion"])) {
        $añadirTarea->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
    } else {
        $descripcion = null;
        $añadirTarea->bindParam(':descripcion', $descripcion, PDO::PARAM_NULL);
    }
    $resultado = $añadirTarea->execute();
    mantenerDatosPag($page, $orderby, $order);
    $tareaIncluida = "La tarea se ha incluido con exito.";
} else {
    $tareaIncluida = "";
}



// Para borrar tareas
if (isset($_GET['borrar']) && is_numeric($_GET['borrar'])) {
    $tareaBorrar = $_GET['borrar'];
    $delete = $db->prepare("DELETE FROM ToDo WHERE id = :tareaBorrar");
    $delete->bindParam(':tareaBorrar', $tareaBorrar, PDO::PARAM_INT);
    $resultado = $delete->execute();
    mantenerDatosPag($page, $orderby, $order);
}

//Hacemos la consulta
$consulta = $db->prepare("SELECT * FROM ToDo ORDER BY :orderby $order LIMIT :limite OFFSET :offset");
//Hacemos los bindParam
$consulta->bindParam(':orderby', $orderby, PDO::PARAM_INT);
$consulta->bindValue(':limite', NUM_ELEM_POR_PAG, PDO::PARAM_INT);
$consulta->bindValue(':offset', NUM_ELEM_POR_PAG * ($page - 1), PDO::PARAM_INT);
//La ejecutamos
$results = $consulta->execute();
//Guardamos los datos obtenidos en un array asociativo
$datosDB = $consulta->fetchAll(PDO::FETCH_ASSOC);
//Para paginar
$consulta_count = $db->query('SELECT Count(id) AS N FROM ToDo'); // Contamos las filas
$count = $consulta_count->fetch();
$numPages = ceil($count[0] / NUM_ELEM_POR_PAG); // calculamos num de paginas


//Enviamos correos
if(isset($_POST["enviarCorreos"]) && $_POST["captcha"] == "No"){
    foreach($datosDB as $dato){
        $mailer = MailerSingleton::obtenerInstancia();
        $mailer->enviarCorreo($dato['correo'], $dato['tarea'], $dato['prioridad'], $dato['descripcion']);
    };
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Document</title>
</head>

<body>
    <h1>ToDo</h1>
    <h2>Tareas pendientes</h2>
    <table>
        <tr>
            <th><a href="<?= generateQueryString(1, $orderby, $order) ?>">Id</a></th>
            <th><a href="<?= generateQueryString(2, $orderby, $order) ?>">Tarea</a></th>
            <th><a href="<?= generateQueryString(3, $orderby, $order) ?>">Prioridad</a></th>
            <th><a href="<?= generateQueryString(4, $orderby, $order) ?>">E-mail</a></th>
        </tr>
        <?php
        foreach ($datosDB as $dato) { ?>
            <tr class="table-row">
                <td>
                    <?= $dato['id'] ?>
                </td>
                <td><a href="detalle.php?id=<?= $dato['id'] ?>">
                        <?= $dato['tarea'] ?>
                    </a></td>
                <td>
                    <?= $dato['prioridad'] ?>
                </td>
                <td>
                    <?= $dato['correo'] ?>
                </td>
                <td class="button-cell">
                    <a href="<?= mantenerDatosPag($page, $orderby, $order) ?>&borrar=<?= $dato['id'] ?>"><img
                            src="img/borrar.png" class="delete-button"></a>
                </td>
            </tr>
            <?php
        }
        ;
        ?>
    </table>
    <div id="selectorPaginas">
        <?php
        for ($i = 1; $i <= $numPages; $i++) {
            ?><span class="paginas"><a <?= ($i == $page) ? "class='actual'" : '' ?>
                    href="<?= mantenerDatosPag($i, $orderby, $order) ?>">
                    <?= $i ?>
                </a></span>
            <?php
        }
        ;
        ?>
    </div>
    <div id="nuevaTarea">
        <h2>Nueva tarea</h2>
        <form method="post" action="#">
            <label for="tarea">Nueva Tarea:</label><br>
            <input type="text" name="tarea" value="<?= $tarea ?>" placeholder="Nueva tarea"><br>
            <?php if (isset($errores['tarea'])) { ?>
                <span class="error">
                    <?= $errores['tarea'] ?>
                </span>
            <?php } ?><br>
            <label for="prioridad">Prioridad:</label><br>
            <label>1<input type="radio" name="prioridad" value="1" <?= ($prioridad == "1") ? 'checked' : '' ?>></label>
            <label>2<input type="radio" name="prioridad" value="2" <?= ($prioridad == "2") ? 'checked' : '' ?>></label>
            <label>3<input type="radio" name="prioridad" value="3" <?= ($prioridad == "3") ? 'checked' : '' ?>></label>
            <label>4<input type="radio" name="prioridad" value="4" <?= ($prioridad == "4") ? 'checked' : '' ?>></label>
            <label>5<input type="radio" name="prioridad" value="5" <?= ($prioridad == "5") ? 'checked' : '' ?>></label><br>
            <?php if (isset($errores['prioridad'])) { ?>
                <span class="error">
                    <?= $errores['prioridad'] ?>
                </span><br>
            <?php } ?>
            <label for="correo">Correo electrónico:</label><br>
            <input type="email" name="correo" placeholder="e-mail" value="<?= $correo ?>"><br>
            <?php if (isset($errores['correo'])) { ?>
                <span class="error">
                    <?= $errores['correo'] ?>
                </span>
            <?php } ?></br>
            <label for="descripcion">Descripción:</label><br>
            <textarea name="descripcion" placeholder="Descripción"><?= $descripcion ?></textarea><br>
            <input type="submit" value="Nueva tarea" name="enviar">
        </form>
        <?php if (isset($exito['tarea'])) { ?>
            <span class="exito">
                <?= $exito['tarea'] ?>
            </span><br>
        <?php } ?>
    </div>
    <div id="enviarCorreos">
        <form method="post" action="#">
            <label for="captcha">Eres un robot?</label><br>
                    <label>Si<input type="radio" name="captcha" value="Si"></label>
                    <label>No<input type="radio" name="captcha" value="No"></label>
                    <?php if (isset($errores['captcha'])) { ?>
                        <span class="error">
                            <?=$errores['captcha'] ?>
                        </span>
                    <?php } ?><br>
                    <?php if (isset($errores['robot'])) { ?>
                        <span class="error">
                            <?=$errores['robot'] ?>
                        </span>
                    <?php } ?><br>
                <input type="submit" value="Enviar Recordatorios" name="enviarCorreos"><br>
                <?php if (isset($exito['RecordatoriosEnviados'])) { ?>
                        <span class="exito">
                            <?=$exito['RecordatoriosEnviados'] ?>
                        </span>
                    <?php } ?><br>
        </form>
    </div>

</body>

</html>