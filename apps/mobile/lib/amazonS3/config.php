<?php

// Incluimos la clase de Amazon S3
if (!class_exists('S3'))require_once('S3.php');

// Informacion de acceso al AWT
if (!defined('awsAccessKey')) define('awsAccessKey', 'AKIAIDPA5IXQKMNWFCYQ');
if (!defined('awsSecretKey')) define('awsSecretKey', '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');
    
// Instanciamos la clase de S3
$s3 = new S3(awsAccessKey, awsSecretKey);
    
?>