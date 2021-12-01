<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/_.session.php";

echo "<strong>current session values</strong> <br>";
foreach ((array) $_SESSION as $key => $value) {
    echo "[" . $key . "]" . " => " . "[" . $value . "]" . "<br>";
}
