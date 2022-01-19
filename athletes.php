<style>
td {
    padding: 5px;
}
table, th, td {
  border: 1px solid black;
  border-collapse: collapse;
}
</style>
<?php
include "coa123-mysql-connect.php"; // MYSQL login details
function calculateBMI($weight, $height){ // BMI function from Task 1
    $bmi = ($weight)/(($height*$height)/100)*100;
    return number_format((float)$bmi, 3, '.', '');
}

$conn = mysqli_connect($servername, $username, $password,$dbname); // Creates DB connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$country_id = $_GET['country_id']; // Uses get method to grabs data from the from
$part_name = $_GET['part_name'];

// Query to send to DB
$inputQuery = "SELECT name, gender, height, weight FROM Cyclist WHERE  name LIKE '%$part_name%' AND ISO_id = '$country_id'";
$result = mysqli_query($conn, $inputQuery); // Sends request
$allDataArray = array(); // Array to hold response
while ($row = mysqli_fetch_assoc($result)) { // Adds each row of DB table to item in array
    $allDataArray[] = $row;
}
for ($i=0; $i<count($allDataArray);$i++) {
    $BMI=calculateBMI($allDataArray[$i]["weight"],$allDataArray[$i]["height"]); // Creates BMI value for selected atlete
            unset($allDataArray[$i]["weight"]); // Removes weight and height from array
            unset($allDataArray[$i]["height"]);
            $allDataArray[$i]["BMI"] = $BMI; // Adds BMI value
}

if(sizeof($allDataArray)==0){ // Validates data can be found
    echo("This data could not be found. Please try different values.");
    echo("</br>");
}
else{
    echo "<table>"; // Creates table
    echo "<th>Name</th> "; // Adds Headers
    echo "<th>Gender</th> ";
    echo "<th>BMI</th> ";

for ($i=0; $i<count($allDataArray);$i++) {  // For each line in the array
    $nameCell=$allDataArray[$i]['name']; // Adds data from array to cell variable
    $genderCell=$allDataArray[$i]['gender'];
    $BMICell=$allDataArray[$i]['BMI'];
    echo "<tr>"; // Echos row
    echo "<td>$nameCell</td>"; // Echos cell variable to table
    echo "<td>$genderCell</td>";
    echo "<td>$BMICell</td>";
    echo "</tr>";
};
echo "</table>";
}

mysqli_close($conn);
?>