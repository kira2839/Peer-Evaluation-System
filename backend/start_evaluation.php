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

$group_members_count = count($group_members_id);

//print_r($evaluation_meaning);
//print_r($group_members_id);
//print_r($group_members_names);

$evaluation_meaning = EvaluationMeaningModel::getInstance()->getAllData();
//print_r($evaluation_meaning);

$roles = $evaluation_meaning[4];
$lead = $evaluation_meaning[0];
$part = $evaluation_meaning[1];
$prof = $evaluation_meaning[2];
$quality = $evaluation_meaning[3];

include("main_site_header.html");

foreach ($group_members_id as $index => $student_id) {
    echo <<<EOC
    <form name="start_eval">
EOC;
    echo <<< EOC1
    <h2 class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-top">$group_members_names[$index] </h2>
    <table class="ui-datepicker-calendar ui-widget ui-widget-content ui-corner-bottom" style="width:100%">
      <tr>
        <th>Category</th>
        <th>Ratings</th> 
      </tr>
      <tr>
        <td>Role</td>
        <td><select id="role_$index" class="ui-selectmenu-button ui-selectmenu-button-closed ui-corner-all ui-button ui-widget">
      <option value="0">$roles[1]</option>
      <option value="1">$roles[2]</option>
      <option value="2">$roles[3]</option>
      <option value="3">$roles[4]</option>
      </select></td>
        
      </tr>
      
      <tr>
        <td>Leadership</td>
        <td><select id="leadership_$index" class="ui-selectmenu-button ui-selectmenu-button-closed ui-corner-all ui-button ui-widget">
      <option value="0">$lead[1]</option>
      <option value="1">$lead[2]</option>
      <option value="2">$lead[3]</option>
      <option value="3">$lead[4]</option>
      </select></td>
        
      </tr>
      
      <tr>
        <td>Participation</td>
        <td><select id="participation_$index" class="ui-selectmenu-button ui-selectmenu-button-closed ui-corner-all ui-button ui-widget">
      <option value="0">$part[1]</option>
      <option value="1">$part[2]</option>
      <option value="2">$part[3]</option>
      <option value="3">$part[4]</option>
      </select></td>
      </tr>
      
      <tr>
        <td>Professionalism</td>
        <td><select id="professionalism_$index" class="ui-selectmenu-button ui-selectmenu-button-closed ui-corner-all ui-button ui-widget">
      <option value="0">$prof[1]</option>
      <option value="1">$prof[2]</option>
      <option value="2">$prof[3]</option>
      <option value="3">$prof[4]</option>
      </select></td>
      </tr>
      
      <tr>
        <td>Quality</td>
        <td><select id="quality_$index" class="ui-selectmenu-button ui-selectmenu-button-closed ui-corner-all ui-button ui-widget">
      <option value="0">$quality[1]</option>
      <option value="1">$quality[2]</option>
      <option value="2">$quality[3]</option>
      <option value="3">$quality[4]</option>
      </select></td>
      </tr>
    </table>
<br>
EOC1;
}
echo <<<EOC2


    <button onclick="return getData()" id="button"
                        class="ui-button ui-corner-all ui-widget">Submit Ratings
    </button>
</form>
<script src="../jquery-ui-1.12.1.custom/external/jquery/jquery.js"></script>
<script src="../jquery-ui-1.12.1.custom/jquery-ui.js"></script>
<script type="text/javascript">
function getData(){
    var i,j = 0;
    var data=[];
    var first_id = 0;
    var student_count = $group_members_count;
    var group_no = $sessionObj->group_id;    
    
    for(i=first_id; i<(first_id+student_count) ;i++){
    var role,leader,part,proff,quality=0;
    var row = [];
    role = parseInt(getSelectData("role_",i));
    leader = parseInt(getSelectData("leadership_",i));
    part = parseInt(getSelectData("participation_",i));
    proff = parseInt(getSelectData("professionalism_",i));
    quality = parseInt(getSelectData("quality_",i));    
    row.push(group_no);
    row.push(i+1);
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
        url: "submit_evaluation.php",
        data: {
            form_data: data
        }
    }).done(function (msg) {
        alert(msg);
    });
}
</script>
EOC2;
