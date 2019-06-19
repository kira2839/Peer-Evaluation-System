

<?php
include 'db_connector.php';


$db_connection = new DBConnector();
$connection = $db_connection -> getDBConnection();
//$db_connection -> dropTables();
//$db_connection -> createTable();
$query = "SELECT count(fk_student_id) as student_count FROM `student_group` WHERE group_id = 1";
$result= mysqli_query($connection, $query);
$student_count= $result -> fetch_assoc();
//$student_count = print_r($student_count, true);
//echo "student count is". $student_count['student_count'];

//echo <<<EOL
?>
<html>

<body background="ub.jpg">
<h2>#Student name goes here# </h2>

<table style="width:100%">
  <tr>
    <th>Category</th>
    <th>Comment</th> 
  </tr>
  <tr>
    <td>Role</td>
    <td><select>
  <option value= 0><?php $query = "SELECT value_0 FROM evaluation_meaning WHERE key_name='Role'";  

  $result= mysqli_query($connection, $query); $result = mysqli_fetch_object($result); echo $result->value_0; ?>  </option>

  <option value= 1><?php $query = "SELECT value_1 FROM evaluation_meaning WHERE key_name='Role'";  
  $result= mysqli_query($connection, $query); $result = mysqli_fetch_object($result); echo $result->value_1; ?></option>

  <option value= 2><?php $query = "SELECT value_2 FROM evaluation_meaning WHERE key_name='Role'";  
  $result= mysqli_query($connection, $query); $result = mysqli_fetch_object($result); echo $result->value_2; ?></option>
  
  <option value= 3><?php $query = "SELECT value_3 FROM evaluation_meaning WHERE key_name='Role'";  
  $result= mysqli_query($connection, $query); $result = mysqli_fetch_object($result); echo $result->value_3; ?></option>

  </select></td>
    
  </tr>
  
  <tr>
    <td>Leadership</td>
    <td><select>
  <option value= 0><?php $query = "SELECT value_0 FROM evaluation_meaning WHERE key_name='Leadership'";  
  $result= mysqli_query($connection, $query); $result = mysqli_fetch_object($result); echo $result->value_0; ?></option>
  <option value= 1><?php $query = "SELECT value_1 FROM evaluation_meaning WHERE key_name='Leadership'";  
  $result= mysqli_query($connection, $query); $result = mysqli_fetch_object($result); echo $result->value_1; ?></option>
  <option value= 2><?php $query = "SELECT value_2 FROM evaluation_meaning WHERE key_name='Leadership'";  
  $result= mysqli_query($connection, $query); $result = mysqli_fetch_object($result); echo $result->value_2; ?></option>
  <option value= 3><?php $query = "SELECT value_3 FROM evaluation_meaning WHERE key_name='Leadership'";  
  $result= mysqli_query($connection, $query); $result = mysqli_fetch_object($result); echo $result->value_3; ?></option>
  </select></td>
    
  </tr>
  
  <tr>
    <td>Participation</td>
    <td><select>
  <option value= 0><?php $query = "SELECT value_0 FROM evaluation_meaning WHERE key_name='Participation'";  
  $result= mysqli_query($connection, $query); $result = mysqli_fetch_object($result); echo $result->value_0; ?></option>
  <option value= 1><?php $query = "SELECT value_1 FROM evaluation_meaning WHERE key_name='Participation'";  
  $result= mysqli_query($connection, $query); $result = mysqli_fetch_object($result); echo $result->value_1; ?></option>
  <option value= 2><?php $query = "SELECT value_2 FROM evaluation_meaning WHERE key_name='Leadership'";  
  $result= mysqli_query($connection, $query); $result = mysqli_fetch_object($result); echo $result->value_2; ?></option>
  <option value= 3><?php $query = "SELECT value_3 FROM evaluation_meaning WHERE key_name='Leadership'";  
  $result= mysqli_query($connection, $query); $result = mysqli_fetch_object($result); echo $result->value_3; ?></option>
  </select></td>
  </tr>
  
  <tr>
    <td>Professionalism</td>
    <td><select>
  <option value= 0><?php $query = "SELECT value_0 FROM evaluation_meaning WHERE key_name='Professionalism'";  
  $result= mysqli_query($connection, $query); $result = mysqli_fetch_object($result); echo $result->value_0; ?></option>
  <option value= 1><?php $query = "SELECT value_1 FROM evaluation_meaning WHERE key_name='Professionalism'";  
  $result= mysqli_query($connection, $query); $result = mysqli_fetch_object($result); echo $result->value_1; ?></option>
  <option value= 2><?php $query = "SELECT value_2 FROM evaluation_meaning WHERE key_name='Professionalism'";  
  $result= mysqli_query($connection, $query); $result = mysqli_fetch_object($result); echo $result->value_2; ?></option>
  <option value= 3><?php $query = "SELECT value_3 FROM evaluation_meaning WHERE key_name='Leadership'";  
  $result= mysqli_query($connection, $query); $result = mysqli_fetch_object($result); echo $result->value_3; ?></option>
  </select></td>
  </tr>
  
  <tr>
    <td>Quality</td>
    <td><select>
  <option value= 0><?php $query = "SELECT value_0 FROM evaluation_meaning WHERE key_name='Quality'";  
  $result= mysqli_query($connection, $query); $result = mysqli_fetch_object($result); echo $result->value_0; ?></option>
  <option value= 1><?php $query = "SELECT value_1 FROM evaluation_meaning WHERE key_name='Quality'";  
  $result= mysqli_query($connection, $query); $result = mysqli_fetch_object($result); echo $result->value_1; ?></option>
  <option value= 2><?php $query = "SELECT value_2 FROM evaluation_meaning WHERE key_name='Quality'";  
  $result= mysqli_query($connection, $query); $result = mysqli_fetch_object($result); echo $result->value_2; ?></option>
  <option value= 3><?php $query = "SELECT value_3 FROM evaluation_meaning WHERE key_name='Quality'";  
  $result= mysqli_query($connection, $query); $result = mysqli_fetch_object($result); echo $result->value_3; ?></option>
  </select></td>
  </tr>
</table>

</body>
</html>

//EOL;


