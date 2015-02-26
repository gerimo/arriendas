<?php
// Bucket Name
$bucket="arriendas.testing";
//$bucket="arriendas.cl";
if (!class_exists('S3'))require_once('S3.php');
			
//AWS access info
if (!defined('awsAccessKey')) define('awsAccessKey', 'AKIAIDPA5IXQKMNWFCYQ');
if (!defined('awsSecretKey')) define('awsSecretKey', '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');
			
//instantiate the class
$s3 = new S3(awsAccessKey, awsSecretKey);

$s3->putBucket($bucket, S3::ACL_PUBLIC_READ);

?>