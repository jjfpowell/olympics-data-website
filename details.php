<?php
include "coa123-mysql-connect.php"; // DB details

$conn = mysqli_connect($servername,$username,$password,$dbname); // Creates connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error()); // Error if conection fails
}

$var1 = $_GET['date_1']; // Gets date values from form
$var2 = $_GET['date_2'];

$date1Array=explode ("/",$var1); // Seperates the string into day month year for validation
$date2Array=explode ("/",$var2);
// IF dates are valid
if(checkdate($date1Array[1], $date1Array[0], $date1Array[2])&&checkdate($date2Array[1], $date2Array[0], $date2Array[2])){ 
    $date_1 = date_create_from_format('d/m/Y', $var1); // Creates date objects
    $date_2 = date_create_from_format('d/m/Y', $var2);
    $data_sql1 = date_format($date_1, 'Y-m-d'); // Formats for DB
    $data_sql2 = date_format($date_2, 'Y-m-d');

    // SQL query to be sent
    $query = "SELECT Cyclist.name, Country.country_name, Country.gdp, Country.population FROM Cyclist LEFT OUTER 
        JOIN Country ON Cyclist.ISO_id = Country.ISO_id WHERE DATE(dob) BETWEEN '$data_sql1' AND '$data_sql2' UNION
        SELECT Cyclist.name, Country.country_name, Country.gdp, Country.population FROM Cyclist RIGHT OUTER JOIN 
        Country ON Cyclist.ISO_id = Country.ISO_id WHERE DATE(dob) BETWEEN '$data_sql1' AND '$data_sql2'";

    $res = mysqli_query($conn, $query);
    $rows = array();
    while($r = mysqli_fetch_assoc($res)) { // appends results to array
        $rows[] = $r;
    }
    echo json_encode($rows); // Echos in JSON Format
}
else{
    echo("Please enter valid dates.");
    echo("</br>");
}
