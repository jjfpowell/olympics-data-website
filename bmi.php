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
// Gets the current values from the form when submit is pressed
$min_weight = $_GET['min_weight'];
$max_weight = $_GET['max_weight'];
$min_height = $_GET['min_height'];
$max_height = $_GET['max_height'];

function calculateBMI($weight, $height){ // Calculates the BMI for a given height and weight
    $bmi = ($weight)/(($height*$height)/100)*100; // Coverts to float to prevent rounding errors
    return number_format((float)$bmi, 3, '.', ''); // Formats to correct decimal places
}
function checkNumeric($weight){ // Checks input is numeric
    if(is_numeric($weight)==1){
        return true;
    }
    else{
        return false;
    }
}
$validationComplete = true; // Only prints table if validation is complete and true
$checkMMW = checkNumeric($min_weight);
$checkMW = checkNumeric($max_weight);
$checkMMH = checkNumeric($min_height);
$checkMH = checkNumeric($min_height);
if(checkNumeric($min_weight)&&checkNumeric($max_weight)&&checkNumeric($min_height)&&checkNumeric($max_height)){ // Check numeric
    $validationComplete = true;
}
else{
    echo("Please only enter numeric values.");
    echo("</br>");
    $validationComplete = false;
    
}

if(($min_weight<=0)||($max_weight<=0)||($max_weight<$min_weight)){ // Check weight is valid
    echo("Invalid weight entered.");
    echo("</br>");
    $validationComplete = false;
}
if(($max_height<=0)||($min_height<=0)||($max_height<$min_height)){ // Check height is valid
    echo("Invalid height entered.");
    echo("</br>"); 
    $validationComplete = false;
}

if($validationComplete == false){ // Checks to see if validation process is complete
    echo("Please try again.");
    echo("</br>"); 
}
else{
    echo "<table>"; // Create table
    echo "<th>Height →<br>Weight ↓</th> "; // Add headings
    
    for ($i=$min_height;$i<=$max_height;$i+=5) {  // Loops range for height creating collumns
        echo "<th>$i</th>";
    };
    for ($i=$min_weight;$i<=$max_weight;$i+=5) { // Loops range for weight creating rows
        echo "<tr>";
        echo "<td>$i</td>";
        for ($j=$min_height;$j<=$max_height;$j+=5) { // Fills table with the relevant BMI data
            $result = calculateBMI($i,$j);
            echo "<td>$result</td>"; // Returns each cell
        }
        echo "</tr>"; // Returns completed row.
    };
    echo "</table>";
}
?>