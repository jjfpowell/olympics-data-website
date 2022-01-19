
<?php
include 'coa123-mysql-connect.php';
$conn = mysqli_connect($servername, $username, $password,$dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$_POST['name']
$errors = array(); //To store errors
$form_data = array(); //Pass back the data to `form.php`

/* Validate the form on the server side */
if (empty($_POST['name'])) { //Name cannot be empty
    $errors['name'] = 'Name cannot be blank';
}

if (!empty($errors)) { //If errors in validation
    $form_data['success'] = false;
    $form_data['errors']  = $errors;
}
else { //If not, process the form, and return true on success
    $form_data['success'] = true;
    $form_data['posted'] = 'Data Was Posted Successfully';
}

//Return the data back to form.php
echo json_encode($form_data);
?>

// error is list of issues with the resquest
// if errors is empty then all is good