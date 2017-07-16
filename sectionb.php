<?php 
  require_once 'login2.php';
  $conn = new mysqli($hn, $un, $pw, $db);
 
  if ($conn->connect_error) 
    die($conn->connect_error);
 
  $pbl_array = array();
   array_push($pbl_array,['Type','Count']);
 
 
  $query = "SELECT category, COUNT(*) FROM classics GROUP BY category";
  $result = $conn->query($query);
  if (!$result) die ("Database access failed: " . $conn->error);

  $rows = $result->num_rows;
  for ($j = 0 ; $j < $rows ; ++$j)
  {
    $result->data_seek($j);
    $row = $result->fetch_array(MYSQLI_NUM);
    $element = array($row[0],intval($row[1]));
    array_push($pbl_array,$element);
    
 }

  $result->close();
  $conn->close();
  
  function get_post($conn, $var)
  {
    return $conn->real_escape_string($_POST[$var]);
  }

?>

  <!DOCTYPE html>
  <html lang="en-US">
  <head>
  <script src="https://www.gstatic.com/charts/loader.js"></script>
  <script type = "text/javascript">
   google.charts.load('current',{'packages':['corechart']});
   google.charts.setOnLoadCallback(drawChart);
   

   function drawChart() {
    
    var dataTableArray = <?php echo json_encode($pbl_array); ?>;
    var data= google.visualization.arrayToDataTable(dataTableArray);
    
    var options = {'title':'Percentage of Publications', 'width':500, 'height':400};
    var chart = new google.visualization.PieChart(document.getElementById('piechart'));
    chart.draw(data,options);
    }
   </script>

   </head>  
  <body>
  <h1> Percentage of publication types </h1>
  <div id ="piechart"></div>
  </body>
  </html>
  

