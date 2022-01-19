<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="task4style.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <title>Task 4</title>
</head>

<body>
  <div id="Headings">
    <h1>2012 Olympics Comparison Viewer</h1>
    <h2>COA123 Web Programming - Joseph Powell</h2>
    <img src="medals.png" alt="Bronze, Silver, Gold Medals" id="image">
  </div>
  <div id="inputDiv">
    <h3>Please input the required information below:</h3>
    <form action="" method="post" name="postForm">
      <table class="inputTable">
        <th>Key</th>
        <th>Input</th>
        <tr>
          <td>Country 1:</td>
          <td><input type="text" class="textInputs" id="country_1" name="country1"></td>
        </tr>
        <tr>
          <td>Country 2:</td>
          <td><input type="text" class="textInputs" id="country_2"></td>
        </tr>
        <tr>
          <td>Order by:</td>
          <td><select class="selectInputs" id="order">
              <option value="Default">Default</option>
              <option value="gold">Gold Medals</option>
              <option value="total">Total Medals</option>
              <option value="numberOfCyclists">Number of cyclists</option>
              <option value="averageAge">Cyclist Average Age</option>
            </select></td>
        </tr>
        <tr>
          <td>Submit:</td>
          <td><input type="submit" value="Submit"></td>
        </tr>
      </table>
    </form>
    <script>
$(document).ready(function() {
    $('form').submit(function(event) { //Trigger on form submit
        $('#country1 + .throw_error').empty(); //Clear the messages first
        $('#success').empty();

        //Validate fields if required using jQuery

        var postForm = { //Fetch form data
            'name' : $('#country_1').val() //Store name fields value
        };

        $.ajax({ //Process the form using $.ajax()
            type      : 'POST', //Method type
            url       : 'sendData.php', //Your form processing file URL
            data      : postForm, //Forms name
            dataType  : 'json',
            success   : function(data) {
              if (!data.success) { //If fails
                if (data.errors.name) { //Returned if any error from process.php
                  $('.throw_error').fadeIn(1000).html(data.errors.name); //Throw relevant error
                }
              }
              else {
                $('#success').fadeIn(1000).append('<p>' + data.posted + '</p>'); //If successful, than throw a success message
                }
              }
        });
        event.preventDefault(); //Prevent the default submit
    });
});
    </script>
  </div>
</body>

</html>