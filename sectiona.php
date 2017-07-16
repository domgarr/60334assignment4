<?php 
  require_once 'login.php';
  $conn = new mysqli($hn, $un, $pw, $db);
 
  if ($conn->connect_error) 
    die($conn->connect_error);



  if (isset($_POST['firstname'])   &&
      isset($_POST['lastname'])    &&
      isset($_POST['user_selected']) &&
      isset($_POST['email'])     &&
      isset($_POST['password']))
  {
   $statement = $conn->prepare("INSERT INTO user_profiles(fname, lname,usercode,email,password) VALUES (?, ?, ?, ?, ?)");
   $statement->bind_param("ssiss", $firstname, $lastname, $usercode, $email, $password);  

   $firstname = get_post($conn, 'firstname');
   $lastname    = get_post($conn, 'lastname');
   $usercode = get_post($conn, 'user_selected');
   $email     = get_post($conn, 'email');
   $password     =get_post($conn, 'password');
    
  $firstname = $conn->real_escape_string($firstname);
  $lastname = $conn->real_escape_string($lastname);
  $usercode = $conn->real_escape_string($usercode);
  $email = $conn->real_escape_string($email);
  $password = $conn->real_escape_string($password);
  
  $statement->execute();
  if(!$statement)
    echo $statement->error;
  else 
    echo "Record inserted succesfully!";

  $statement->close();
  }

 

  echo <<<_END
  <!DOCTYPE html>
  <form action="sectiona.php" method="post"><pre>
     First Name: <input type="text" name="firstname">
     Last Name:<input type="text" name="lastname">
     User Type: <select name="user_selected"> 
     <br> 
_END;


 
  $query = "SELECT user_description, user_code FROM user_codes";
  $result = $conn->query($query);
  if (!$result) die ("Database access failed: " . $conn->error);

  $rows = $result->num_rows;
  for ($j = 0 ; $j < $rows ; ++$j)
  {
    $result->data_seek($j);
    $row = $result->fetch_array(MYSQLI_NUM);
    echo "<option value=$row[1] > $row[0] </option><br>";

 }



 echo <<<_END
     </select>
     E-mail: <input type="text" name="email">
     Password: <input type="password" name="password">
     <input type="submit" value="Submit">
  </pre></form>
_END;


  
  $result->close();
  $conn->close();
  
  function get_post($conn, $var)
  {
    return $conn->real_escape_string($_POST[$var]);
  }
?>
