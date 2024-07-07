<?php

$dbhost = "localHost";
$dbuser = "root";
$dbpass = "";
$dbname = "login_sample_db";

if(!$con = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname))
{

    die("failed to connect!");
}