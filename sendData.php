<?php
// Retreive data from View.php
$country_1 = $_GET['country_1'];
$country_2 = $_GET['country_2'];
$order = $_GET['order'];

include "coa123-mysql-connect.php"; // Database info
$conn = mysqli_connect($servername, $username, $password, $dbname); // Create connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$orderStr = new stdClass(); // Create assoative array to update the ordered by element

// Create Query depending on how the data should be ordered
if($order=="Default") {
    // Use country_1 and country_2 to filter data
    $orderQuery = "SELECT ISO_id, total FROM `Country` WHERE ISO_id LIKE '$country_1' OR ISO_id LIKE '$country_2' ORDER BY Country.ISO_id ASC";
    $orderStr->orderKey = "Default"; // Order by string
}
elseif($order=="gold"){
    // If gold, use total>c.total to calculate overall ranking
    $orderQuery = "SELECT c.ISO_id, c.total, c.gold, ((SELECT COUNT(*) FROM Country WHERE total > c.gold)+1) AS 'Overall Rank' FROM Country as c WHERE c.ISO_id LIKE '$country_1' OR c.ISO_id LIKE '$country_2' ORDER BY c.gold DESC, c.total DESC";
    $orderStr->orderKey ="Gold Medals"; // Order by string
}
elseif($order=="total"){
    // If total, use total>c.total to calculate overall ranking
    $orderQuery = "SELECT c.ISO_id, c.total, c.gold, ((SELECT COUNT(*) FROM Country WHERE total > c.total)+1) AS 'Overall Rank' FROM Country as c WHERE c.ISO_id LIKE '$country_1' OR c.ISO_id LIKE '$country_2' ORDER BY c.total DESC, c.gold DESC";
    $orderStr->orderKey = "Total Medals"; // Order by string
}
elseif($order=="averageAge"){
    // Here I have used EXTRACT to get just the year element of the date object
    $orderQuery = "SELECT Cyclist.ISO_id, Country.total, ROUND(AVG(2012-EXTRACT(YEAR FROM Cyclist.dob))) AS 'Average Age'\n"
        ."FROM Country LEFT JOIN Cyclist ON Country.ISO_id = Cyclist.ISO_id\n"
        ."WHERE (Cyclist.ISO_id IS NOT NULL)\n"
        ."AND (Cyclist.ISO_id LIKE '$country_1' OR Cyclist.ISO_id LIKE '$country_2')\n"
        ."GROUP BY (Cyclist.ISO_id) ORDER BY AVG((2012-Cyclist.dob))";
    $orderStr->orderKey = "Average Age"; // Order by string
}
elseif($order=="numberOfCyclists"){
    // Counts the number cyclists with COUNT
    $orderQuery = "SELECT Country.ISO_id, Country.total, COUNT(Cyclist.ISO_id) AS 'Number of Cyclists'\n"
        . "FROM Country LEFT JOIN Cyclist ON Country.ISO_id = Cyclist.ISO_id\n"
        . "WHERE Cyclist.ISO_id LIKE '$country_1' \n"
        . "OR Cyclist.ISO_id LIKE '$country_2' GROUP BY Cyclist.ISO_id\n"
        . "ORDER BY 'Number of Cyclists' ASC, Country.total ASC";
    $orderStr->orderKey = "Number of Cyclists"; // Order by string
}
// Queries to get a list of cyclist from DB
$cyclistList = "SELECT ISO_id,name FROM Cyclist WHERE ISO_id LIKE '$country_1' OR ISO_id LIKE '$country_2' ORDER BY Cyclist.ISO_id ASC, Cyclist.name ASC";

$ordered = array();
$ordered[] = $orderStr; // Adds assocative array to orrdered

$orderResult = mysqli_query($conn, $orderQuery); // Creates queries
$cycleResult = mysqli_query($conn, $cyclistList);

$orderArray = array(); // Creates array to hold results
$cyclists = array();

while ($row = mysqli_fetch_assoc($orderResult)) { // Appends results to Array
    $orderArray[] = $row;
}
while ($row = mysqli_fetch_assoc($cycleResult)) {
    $cyclists[] = $row;
}
if($country_1==""||$country_1==""){ 
    echo '<p>Country values cannot be empty.</p>'; // Write errors to network log
}
else if(sizeof($orderArray)==0){
    echo '<p>Data for these countries does not exist.</p>';
}
else{
    // Uses assocative array for each array of data allowing them to be easy accessed
    $to_return = array("cyclist" => $cyclists, "orderBy"=>$orderArray,"ordered"=>$ordered);
    echo json_encode($to_return);
}
?>