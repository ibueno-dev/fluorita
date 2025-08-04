<?php
// logout.php

session_start();
$_SESSION = array();
session_destroy();
// Caminho atualizado para o login na raiz
header('Location: home.php');
exit;