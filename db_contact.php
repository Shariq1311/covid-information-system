<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "covid_contact";

$conn_contact = new mysqli($host, $user, $pass, $dbname);

if ($conn_contact->connect_error) {
    die("Contact DB Connection failed: " . $conn_contact->connect_error);
}
?>
