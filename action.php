<?php
require_once 'isi.php';
$f = new isi();
$table = $_GET['table'];
$action = $_GET['action']
$f->{$action}($table);
}
?>