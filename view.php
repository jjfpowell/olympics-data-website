<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Imports stlye sheet -->
  <link rel="stylesheet" type="text/css" href="newstyle.css"> 
  <!-- Imports JQuert -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <title>Task 4</title> 
</head>

<!-- Main body of content -->
<body>
  <!-- Creates heading of page with images and Heading -->
  <div id="Headings">
    <h1>2012 Olympics Comparison Viewer</h1>
    <h2>COA123 Web Programming - Joseph Powell</h2>
    <img src="medals.png" alt="Bronze, Silver, Gold Medals" id="image">
  </div>
  <div id="inputDiv">
    <h3>Please input the required information below:</h3>
    <!-- Create a form for user to input their data into, these have a class for styling and unique ID-->
    <form action="sendData.php" method="GET">
      <!-- This form is stored in a table for presentation -->
      <table class="inputTable">
        <th>Key</th>
        <th>Input</th>
        <tr>
          <td>Country 1:</td>
          <td><input type="text" class="textInputs" id="country_1"></td>
        </tr>
        <tr>
          <td>Country 2:</td>
          <td><input type="text" class="textInputs" id="country_2"></td>
        </tr>
        <tr>
          <td>Order by:</td>
          <!-- Create a drop down menu with the available type of ordering -->
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
      $(document).ready(function() { // Waits until document is fully loaded before allowing manipulation
        $("form").submit(function(event) { // on sumbit event...
          $.ajax({ // Create AJAX Reuqest
            type: "GET", // Select method to send data by 
            // Create get URL and where to send the data
            url: `sendData.php?country_1=${$("#country_1").val()}&country_2=${$("#country_2").val()}&order=${$("#order").val()}`,
            dataType: "json" // Specify datatype
          }).done(ajaxResponse); // Get response from PHP which calls ajaxReponse with the data
          event.preventDefault(); // Ensures event is finsihed
        });
      });
    </script>
  </div>
  <div id="container">
    <div id="athleteDiv">
      <h2 id="athleteHead">Athlete List</h2>
      <br>
      <table id="athleteList" class="inputTable"></table>
    </div>
    <div id="orderDiv">
      <!-- This will be updated depending how data is ordered -->
        <h2 id="orderHead">Ordered by: </h2>
        <table id="orderList" class="inputTable"></table>
    </div>
  </div>
  <script>
    function ajaxResponse(data){ // Receieves data from AJAX function
        let athleteTable = document.getElementById("athleteList"); // Gets table from document
        let orderTable = document.getElementById("orderList"); // Gets table from document
        athleteTable.innerHTML = ""; // Clears any existing data
        orderTable.innerHTML = "";
        generateAthleteTableHead(athleteTable); // Creates header for table
        fillAthleteRows(athleteTable,data); // Fills data into athlete table 
        generateOrderTableHead(orderTable,data); // Creates header for table
        fillOrderRows(orderTable,data); // Fills data into ordered table
        headerUpdater(data); // Updates string
      };
      function headerUpdater(data){
        document.getElementById("orderHead").innerHTML = "<h2>Ordered by: "+data.ordered[0].orderKey+"</h2>"; // Updates text
        document.getElementById("orderHead").style.fontSize = "initial"; // Resets font size
      }
      function generateAthleteTableHead(table){ // Creates table header
        let tHead = table.createTHead(); // Creates head element
        let row = tHead.insertRow(0); // Adds new row for header
        let isoCell = row.insertCell(0); // Adds header text for collumns
        let nameCell = row.insertCell(1);
        isoCell.innerHTML = "<b>ISO_id</b>"; // Updates HTML
        nameCell.innerHTML = "<b>Name</b>"; 
      }
      function fillAthleteRows(table, data) { // Adds data to athlete table
        for(i=0;i<(data.cyclist).length;i++){ // For length of cyclist element of associative array 
          let row = table.insertRow(); // Create a row
          let cellIso = row.insertCell(); // Insert two cells
          let cellName = row.insertCell();
          cellIso.innerHTML = "<p>"+data.cyclist[i].ISO_id+"</p>"; // Fill cells with data
          cellName.innerHTML = "<p>"+data.cyclist[i].name+"</p>";
          }
      }
      function generateOrderTableHead(table,data){ // Creates header for table
        let tHead = table.createTHead(); // Creates head element
        let row = tHead.insertRow(0);  // Adds new row for header
        let isoCell = row.insertCell(); // Adds header text for collumns
        let totalCell = row.insertCell();
        isoCell.innerHTML = "<b>ISO_id</b>"; // Updates HTML
        totalCell.innerHTML = "<b>Total</b>";
        // Checks to see if e.g data.orderBy[0].gold is a valid map in the array, if its not undefined, do something
        if(data.orderBy[0].gold!==undefined){ // Adds extra cell if we need to display gold medal data
          let goldCell = row.insertCell();
          goldCell.innerHTML = "<b>Gold</b>";
        }
        else if(data.orderBy[0]['Number of Cyclists']!==undefined){ // Adds extra cell if we need to display the number of cyclists
          let numCell = row.insertCell();
          numCell.innerHTML = "<b>Number of Cyclists</b>";
        }
        if(data.orderBy[0]["Overall Rank"]!==undefined){ // Adds extra cell if we need to display Overall rank data
          let rankCell = row.insertCell();
          rankCell.innerHTML = "<b>Overall Rank</b>";
        }
        if(data.orderBy[0]["Average Age"]!==undefined){ // Adds extra cell if we need to display Average age data
          let ageCell = row.insertCell();
          ageCell.innerHTML = "<b>Average Age</b>";
        }
      }
      function fillOrderRows(table,data){ // Adds data to Ordered table
        for(i=0;i<(data.orderBy).length;i++){ // For length of order element of associative array 
          let row = table.insertRow(); // Create row
          let cellIso = row.insertCell(); // Insert two cells
          let cellTotal = row.insertCell();
          cellIso.innerHTML = "<p>"+data.orderBy[i].ISO_id+"</p>"; // Update data with HTML
          cellTotal.innerHTML = "<p>"+data.orderBy[i].total+"</p>";
          if(data.orderBy[0].gold!==undefined){ // Adds extra cell if we need to display gold medal data
            let goldCell = row.insertCell();
            goldCell.innerHTML = "<p>"+data.orderBy[i].gold+"</p>";
          }
          else if(data.orderBy[0]['Number of Cyclists']!==undefined){ // Adds extra cell if we need to display the number of cyclists
            let numCell = row.insertCell();
            numCell.innerHTML = "<p>"+data.orderBy[i]['Number of Cyclists']+"</p>";
          }
          if(data.orderBy[0]['Overall Rank']!==undefined){ // Adds extra cell if we need to display Overall rank data
            let rankCell = row.insertCell();
            rankCell.innerHTML = "<p>"+data.orderBy[i]['Overall Rank']+"</p>";
          }
          if(data.orderBy[0]['Average Age']!==undefined){  // Adds extra cell if we need to display Average age data
            let ageCell = row.insertCell();
            ageCell.innerHTML = "<p>"+data.orderBy[i]['Average Age']+"</p>";
          }
        }
      }
  </script>
</body>
</html>