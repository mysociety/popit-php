<?php

require_once("sdk.php");

$popit = new PopIt(array(
    'instanceName' => 'chetan',
    'user' => 'chetan1@gmail.com',
    'password' => 'M31K9uUz',
));

$result = $popit->call("position", "POST", array('name' => 'SDK1.2', 'slug'=>'SDK1.2', 'summary'=>'Test user created through the PHP SDK.'));
$popit->call("organisation/4fe8bde4d4bd081b6b0002bd", "DELETE");
$result = $popit->call("person/4fe8b799d4bd081b6b000236", "PUT", array('name' => 'SDK1.1 RR'));
$result = $popit->call("person");

print_r($result);

?>