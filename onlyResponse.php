<?php
$obj = array();
$obj['firstname'] = "John";
$obj['lastname'] = "Smith";
$obj['email'] = "john.smith@johnsmith.com";

$response = "callback(" . json_encode($obj) . ");";
print $response;

/**
  Browser prints this out as:
  callback({"firstname":"John", "lastname":"Smith", "email":"john.smith@johnsmith.com"});
**/
?>
