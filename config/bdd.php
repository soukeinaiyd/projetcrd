<?php
define('DB_HOST','localhost:3306');
define('DB_NAME','project');
define('DB_USER','root');
define('DB_PASS','');
//try {
$bdd = new PDO('mysql:host=' . DB_HOST . ' ;dbname=' . DB_NAME . ';charset=utf8mb4' , DB_USER, DB_PASS);
//} catch (PDOException $e){
   // echo $e->getMessage();
   //include_once './templates/erreur500.php';
    //die();
////}
?>
