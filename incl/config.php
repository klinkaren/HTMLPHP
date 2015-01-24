<?php
// turn on all error reporting
error_reporting(-1);

// start a named session
session_name(preg_replace('/[:\.\/-_]/', '', __FILE__));
session_start(); 
	
// time page generation, display in footer. comment out to disable timing.
$pageTimeGeneration = microtime(true);

// path to database
$dbPath = dirname(__FILE__) . "/../incl/data/bmo.sqlite";

// include common functions 
include_once(dirname(__FILE__) . "/../src/common.php");

// include functions for login & logout
include_once(dirname(__FILE__) . "/../src/login.php");

// include general functions
include_once(dirname(__FILE__) . "/../src/functions.php");

// Account and password that can login
// Should really be a separate table in database for users containing md5-converted password and other user details.
$userAccount = "change";
$userPassword = userPassword("me");

?>