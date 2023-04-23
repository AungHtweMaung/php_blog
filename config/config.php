<?php


define('MYSQL_HOST', 'localhost');
define('MYSQL_DB', 'blog');
define('MYSQL_USER', 'root');
define('MYSQL_PASSWORD', '');

$options = array(
    PDO::ATTR_ERRMODE=> PDO::ERRMODE_EXCEPTION
);



try {
    $pdo = new PDO(
        'mysql:host=' . MYSQL_HOST . ";dbname=" . MYSQL_DB,
        MYSQL_USER,
        MYSQL_PASSWORD,
        $options
    );

} catch(PDOException $e) {
    echo "Error: ". $e->getMessage();
}


?>