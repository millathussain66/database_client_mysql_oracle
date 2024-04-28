<?php
// Oracle Database credentials
$username = 'loscbl';
$password = 'loscbl123';
$connection_string = 'MICROMAC-PC37.Micromac.com:1521/ORCLPDB'; 

/*
In the Oracle connection string:

   > MICROMAC-PC37.Micromac.com is the hostname or IP address of the Oracle database server.
   > 1521 is the port number on which the Oracle listener is listening for connections.
   > ORCLPDB is the service name of the Oracle Pluggable Database (PDB).

Ensure that you have the correct hostname, port number, and service name for your Oracle database. 
Additionally, make sure that the username and password provided in the oci_connect() function are valid for connecting to the Oracle database.
*/


// Establishing connection
$conn = oci_connect($username, $password, $connection_string);

// Check if connection was successful
if (!$conn) {
    $error_message = oci_error(); // Get error information
    die('Connection failed: ' . $error_message['message']);
} else {
    echo 'Connection successful!';
    // You can execute SQL queries here using $conn
}

// Closing connection
oci_close($conn);