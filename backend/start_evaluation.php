<?php

include_once('session.php');
include_once('student_model.php');
include_once('student_group_model.php');
include_once('evaluation_meaning_model.php');
// We get the created session instance if already
$sessionObj = Session::getInstance();

if (!isset($_SESSION['email_id']) OR ($_SESSION['agent'] != sha1($_SERVER['HTTP_USER_AGENT']))) {
    echo "Session is invalid";
    //$sessionObj->destroy();
    //Utility::redirectUser("index.html");
    return;
}

//echo $sessionObj->student_id;
//echo $sessionObj->name;
//echo $sessionObj->group_id;

$group_members_id = StudentGroupModel::getInstance()->getGroupMembersID($sessionObj->group_id);
$group_members_names = array();
foreach ($group_members_id as $val) {
    array_push($group_members_names, StudentModel::getInstance()->getStudentName($val));
}

//$evaluation_meaning = EvaluationMeaningModel::getInstance()->get();
$group_members_count = count($group_members_id);

//print_r($evaluation_meaning);
//print_r($group_members_id);
print_r($group_members_names);

foreach ($group_members_id as $index => $student_id) {
    echo <<<EOC
    <title>Start Evaluation</title>
    <form name="start_eval">
EOC;

    echo <<< EOC1
    <h2>$group_members_names[$index] </h2>
    <table style="width:100%">
      <tr>
        <th>Category</th>
        <th>Ratings</th> 
      </tr>
      <tr>
        <td>evaluation_meaning</td>
        <td><select id="role_$index">
      <option value="0">evaluation_meaning</option>
      <option value="1">evaluation_meaning</option>
      <option value="2">evaluation_meaning</option>
      <option value="3">evaluation_meaning</option>
      </select></td>
        
      </tr>
      
      <tr>
        <td>evaluation_meaning[0][0]</td>
        <td><select id="leadership_$index">
      <option value="0">evaluation_meaning</option>
      <option value="1">evaluation_meaning</option>
      <option value="2">evaluation_meaning</option>
      <option value="3">evaluation_meaning</option>
      </select></td>
        
      </tr>
      
      <tr>
        <td>evaluation_meaning[1][0]</td>
        <td><select id="participation_$index">
      <option value="0">evaluation_meaning</option>
      <option value="1">evaluation_meaning</option>
      <option value="2">evaluation_meaning</option>
      <option value="3">evaluation_meaning</option>
      </select></td>
      </tr>
      
      <tr>
        <td>evaluation_meaning</td>
        <td><select id="professionalism_$index">
      <option value="0">evaluation_meaning</option>
      <option value="1">evaluation_meaning</option>
      <option value="2">evaluation_meaning</option>
      <option value="3">evaluation_meaning</option>
      </select></td>
      </tr>
      
      <tr>
        <td>evaluation_meaning</td>
        <td><select id="quality_$index">
      <option value="0">evaluation_meaning</option>
      <option value="1">evaluation_meaning</option>
      <option value="2">evaluation_meaning</option>
      <option value="3">evaluation_meaning</option>
      </select></td>
      </tr>
    </table>
EOC1;

}