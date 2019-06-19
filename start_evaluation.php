<?php
include ("./backend/db_connector.php");

// Common variables
$group_no = 1;
$db_connection = new DBConnector();
$connection = $db_connection -> getDBConnection();
//$db_connection -> dropTables();
//$db_connection -> createTable();
$student_count=getCountInGroup($connection,$group_no);
$names = getStudentNameInGroup($connection,$student_count,$group_no);


for($i=0;$i<$student_count;$i++) {
    echo <<< EOC
    <h2>$names[$i] </h2>
    <table style="width:100%">
      <tr>
        <th>Category</th>
        <th>Comment</th> 
      </tr>
      <tr>
        <td>Role</td>
        <td><select>
      <option value="volvo">Volvo</option>
      <option value="saab">Saab</option>
      <option value="opel">Opel</option>
      <option value="audi">Audi</option>
      </select></td>
        
      </tr>
      
      <tr>
        <td>Leadership</td>
        <td><select>
      <option value="volvo">Volvo</option>
      <option value="saab">Saab</option>
      <option value="opel">Opel</option>
      <option value="audi">Audi</option>
      </select></td>
        
      </tr>
      
      <tr>
        <td>Participation</td>
        <td><select>
      <option value="volvo">Volvo</option>
      <option value="saab">Saab</option>
      <option value="opel">Opel</option>
      <option value="audi">Audi</option>
      </select></td>
      </tr>
      
      <tr>
        <td>Professionalism</td>
        <td><select>
      <option value="volvo">Volvo</option>
      <option value="saab">Saab</option>
      <option value="opel">Opel</option>
      <option value="audi">Audi</option>
      </select></td>
      </tr>
      
      <tr>
        <td>Quality</td>
        <td><select>
      <option value="volvo">Volvo</option>
      <option value="saab">Saab</option>
      <option value="opel">Opel</option>
      <option value="audi">Audi</option>
      </select></td>
      </tr>
    </table>
EOC;
}

function getCountInGroup($connection,$group_no){
    $query = "SELECT count(fk_student_id) as student_count FROM `student_group` WHERE group_id = $group_no";
    $result= mysqli_query($connection, $query);
    $student_count = $result -> fetch_assoc()['student_count'];
    return $student_count;
}

function getStudentNameInGroup($connection,$student_count,$group_no){
    //Getting the ids of all the students within the group
    $query = "SELECT `fk_student_id` as student_ids FROM `student_group` WHERE group_id = $group_no";
    $results= mysqli_query($connection, $query);
    $student_ids = [];
    while($row = mysqli_fetch_assoc($results)) {
        $student_ids[] = $row;
    }
//print_r($student_ids);

//SELECT `student_name` FROM `student` WHERE `id`=1
    $student_names = [];
    print("Names are");
    for($i=0;$i<$student_count;$i++){
        $query = "SELECT `student_name` as name FROM `student` WHERE `id`=$i+1";
        $result= mysqli_query($connection, $query);
        $name = $result -> fetch_assoc()['name'];
        array_push($student_names,$name);
    }
return $student_names;
}
?>