<?php

include_once '_.config.php';

// try catch database connection
try {

    $dsn = 'mysql:host=' . $conf["host"] . ';dbname=' . $conf["db"] . ';charset=' . $conf["charset"];
    $pdo = new PDO($dsn, $conf["user"], $conf["pass"]);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    date_default_timezone_set('Europe/Berlin');
    $sroot = $_SERVER['DOCUMENT_ROOT'];

    return $pdo;
    exit();
} catch (PDOException $e) {

    echo "Error: " . $e->getMessage();
    exit();
}
