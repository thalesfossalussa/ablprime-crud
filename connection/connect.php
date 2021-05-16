<?php

// mysql('local', 'usuario', 'senha', 'banco')
$conn = new mysqli('127.0.0.1', 'root', 'devmysql@2020', 'bd_ablcrud');

mysqli_set_charset($conn,'utf8');

if (mysqli_connect_errno()) {
  echo "Erro de conexão com o banco de dados: " . mysqli_connect_error();
}

if (mysqli_connect_errno()) {
    die('Não foi possível conectar-se ao banco de dados: ' . mysqli_connect_error());
    exit();
}

?>