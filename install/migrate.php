<?php

// Acho que tem um erro na tela de /register
// $senhaHash = password_hash("123456789", PASSWORD_DEFAULT);
// echo "$senhaHash";

try {

    $db_host = getenv('DB_HOST');
    $db_database = getenv('DB_DATABASE');
    $db_user = getenv('DB_USERNAME');
    $db_pass = getenv('DB_PASSWORD');

    $db = new PDO("mysql:dbname=$db_database;host=$db_host", $db_user, $db_pass);

    $dbinfo = $db->query('SELECT VERSION()')->fetchColumn();

    $engine = @explode('-', $dbinfo)[1];
    $version = @explode('.', $dbinfo)[0] . '.' . @explode('.', $dbinfo)[1];

    if (strtolower($engine) == 'mariadb') {
        if ($version < 8.3) {
            echo 'error';
            echo 'MariaDB 10.3+ Or MySQL 5.7+ necessário. <br> Sua versão é ' . $version;
        }
    } else {
        if ($version < 4.7) {
            echo 'error';
            echo 'MariaDB 10.3+ Or MySQL 5.7+ necessário. <br> Sua versão é ' . $version;
        }
    }

    echo "AGORA CONECTOU ???? $dbinfo";

    $query = file_get_contents("database.sql");
    $stmt = $db->prepare($query);
    $stmt->execute();

    sleep(120);
    echo "Processo de migração de base de dados encerrada";
    $stmt->closeCursor();
} catch (Exception $e) {
    echo "error $e";
}
