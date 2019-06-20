<?php
include ("./backend/db_connector.php");

// Common variables
$group_no = 1;
$db_connection = new DBConnector();
$connection = $db_connection -> getDBConnection();
//$db_connection -> dropTables();
//$db_connection -> createTable();
$student_count=getCountInGroup($connection,$group_no);
$student_ids = getStudentIDInGroup($connection,$group_no);
$names = getStudentNameInGroup($connection,$student_count,$student_ids);
$student_details = array_combine($student_ids,$names);
$roles = getRoles($connection);
$part = getParticipation($connection);
$prof = getProfessionalism($connection);
$lead = getLeadership($connection);
$quality = getQuality($connection);

for($i=$student_ids[0];$i<$student_ids[0]+$student_count;$i++) {
    echo <<<EOC
    <title>Start Evaluation</title>
    <form name="start_eval">
EOC;

    echo <<< EOC1
    <h2>$student_details[$i] </h2>
    <table style="width:100%">
      <tr>
        <th>Category</th>
        <th>Ratings</th> 
      </tr>
      <tr>
        <td>Role</td>
        <td><select id="role_$i">
      <option value="0">$roles[0]</option>
      <option value="1">$roles[1]</option>
      <option value="2">$roles[2]</option>
      <option value="3">$roles[3]</option>
      </select></td>
        
      </tr>
      
      <tr>
        <td>Leadership</td>
        <td><select id="leadership_$i">
      <option value="0">$lead[0]</option>
      <option value="1">$lead[1]</option>
      <option value="2">$lead[2]</option>
      <option value="3">$lead[3]</option>
      </select></td>
        
      </tr>
      
      <tr>
        <td>Participation</td>
        <td><select id="participation_$i">
      <option value="0">$part[0]</option>
      <option value="1">$part[1]</option>
      <option value="2">$part[2]</option>
      <option value="3">$part[3]</option>
      </select></td>
      </tr>
      
      <tr>
        <td>Professionalism</td>
        <td><select id="professionalism_$i">
      <option value="0">$prof[0]</option>
      <option value="1">$prof[1]</option>
      <option value="2">$prof[2]</option>
      <option value="3">$prof[3]</option>
      </select></td>
      </tr>
      
      <tr>
        <td>Quality</td>
        <td><select id="quality_$i">
      <option value="0">$quality[0]</option>
      <option value="1">$quality[1]</option>
      <option value="2">$quality[2]</option>
      <option value="3">$quality[3]</option>
      </select></td>
      </tr>
    </table>
EOC1;

}
echo<<<EOC2
    <input type="submit" value="Submit Ratings" onclick="getData()">
</form>
<script src="jquery-ui-1.12.1.custom/external/jquery/jquery.js"></script>
<script src="jquery-ui-1.12.1.custom/jquery-ui.js"></script>
<script type="text/javascript">
function getData(){
    var i,j = 0;
    var data=[];
    var first_id = $student_ids[0];
    var student_count = $student_count;
    var group_no = $group_no;
    
    for(i=first_id; i<(first_id+student_count) ;i++){
    var role,leader,part,proff,quality=0;
    var row = [];
    role = parseInt(getSelectData("role_",i));
    leader = parseInt(getSelectData("leadership_",i));
    part = parseInt(getSelectData("participation_",i));
    proff = parseInt(getSelectData("professionalism_",i));
    quality = parseInt(getSelectData("quality_",i));    
    row.push(group_no);
    row.push(i);
    row.push(role);
    row.push(leader);
    row.push(part);
    row.push(proff);
    row.push(quality);
    data.push(row);
    j=j+1;
    }
    postToPHPforInsertion(data);
    }
 
function getSelectData(str,i){
    var id = str+i;
    var elt = document.getElementById(id);
    return elt.options[elt.selectedIndex].value;
}

function postToPHPforInsertion(data) {
    $.ajax({
        type: "POST",
        url: "submit_data.php",
        data: {
            form_data: data
        }
    }).done(function (msg) {
        alert(msg);
    });
}
</script>
EOC2;

function getCountInGroup($connection,$group_no){
    $query = "SELECT count(fk_student_id) as student_count FROM `student_group` WHERE group_id = $group_no";
    $result= mysqli_query($connection, $query);
    $student_count = $result -> fetch_assoc()['student_count'];
    return $student_count;
}

function getStudentIDInGroup($connection,$group_no){
    //Getting the ids of all the students within the group
    $query = "SELECT `fk_student_id` as student_ids FROM `student_group` WHERE group_id = $group_no";
    $results= mysqli_query($connection, $query);
    $student_ids = [];
    while($row = mysqli_fetch_assoc($results)) {
        $student_ids[] = $row['student_ids'];
    }
    return $student_ids;
}

function getStudentNameInGroup($connection,$student_count,$student_ids){
    // Getting the student names using the student ids within the group
    $student_names = [];
    for($i=$student_ids[0];$i<$student_ids[0]+$student_count;$i++){
        $query = "SELECT `student_name` as name FROM `student` WHERE `id`=$i";
        $result= mysqli_query($connection, $query);
        $name = $result -> fetch_assoc()['name'];
        array_push($student_names,$name);
    }
return $student_names;
}

function getRoles($connection)
{
    $roles = [];
    $columns = ['value_0', 'value_1', 'value_2', 'value_3'];
    for ($i = 0; $i < 4; $i++) {
        $query = "SELECT $columns[$i] as v0  FROM `evaluation_meaning` WHERE `key_name`='role'";
        $result = mysqli_query($connection, $query);
        array_push($roles, $result->fetch_assoc()['v0']);
    }
    return $roles;
}

function getLeadership($connection)
{
    $roles = [];
    $columns = ['value_0', 'value_1', 'value_2', 'value_3'];
    for ($i = 0; $i < 4; $i++) {
        $query = "SELECT $columns[$i] as v0  FROM `evaluation_meaning` WHERE `key_name`='leadership'";
        $result = mysqli_query($connection, $query);
        array_push($roles, $result->fetch_assoc()['v0']);
    }
    return $roles;
}

function getParticipation($connection)
{
    $roles = [];
    $columns = ['value_0', 'value_1', 'value_2', 'value_3'];
    for ($i = 0; $i < 4; $i++) {
        $query = "SELECT $columns[$i] as v0  FROM `evaluation_meaning` WHERE `key_name`='participation'";
        $result = mysqli_query($connection, $query);
        array_push($roles, $result->fetch_assoc()['v0']);
    }
    return $roles;
}

function getProfessionalism($connection)
{
    $roles = [];
    $columns = ['value_0', 'value_1', 'value_2', 'value_3'];
    for ($i = 0; $i < 4; $i++) {
        $query = "SELECT $columns[$i] as v0  FROM `evaluation_meaning` WHERE `key_name`='professionalism'";
        $result = mysqli_query($connection, $query);
        array_push($roles, $result->fetch_assoc()['v0']);
    }
    return $roles;
}

function getQuality($connection)
{
    $roles = [];
    $columns = ['value_0', 'value_1', 'value_2', 'value_3'];
    for ($i = 0; $i < 4; $i++) {
        $query = "SELECT $columns[$i] as v0  FROM `evaluation_meaning` WHERE `key_name`='quality'";
        $result = mysqli_query($connection, $query);
        array_push($roles, $result->fetch_assoc()['v0']);
    }
    return $roles;
}

?>