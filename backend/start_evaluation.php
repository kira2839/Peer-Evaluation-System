<?php

include_once('website_page_handle.php');
include_once('session.php');
include_once('student_model.php');
include_once('student_group_model.php');
include_once('evaluation_meaning_model.php');

// We get the created session instance which is created earlier then proceed or error out
$sessionObj = Session::getInstance();

if (!$sessionObj->isSessionValid()) {
    $sessionObj->destroy();
    $sessionObj->displaySessionExpiredMessage();
    return;
}

//Start creating the html tags
WebSitePageHandle::includeSiteHeader();

echo <<<EOC1
    <form id="student_eval" name="start_eval">
EOC1;

$group_members_names = $sessionObj->group_members_names;
$group_members_count = count($sessionObj->group_members_id);
$category_count = count($sessionObj->evaluation_meaning);

foreach ($sessionObj->group_members_id as $index => $group_member) {
    echo <<< EOC2
    <li id="group_member_$index" value="$group_member" class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-top">$group_members_names[$index]</li>
    <table class="ui-datepicker-calendar ui-widget ui-widget-content ui-corner-bottom" style="width:100%">
      <tr>
        <th>Category</th>
        <th>Ratings</th> 
      </tr>
EOC2;
    foreach ($sessionObj->evaluation_meaning as $catIndex => $ratings) {
        echo <<< EOC31
        <tr>
        <td>$ratings[0]</td>
        <td><select id="category_$catIndex$index" class="ui-selectmenu-button ui-selectmenu-button-closed ui-corner-all ui-button ui-widget">
EOC31;
        for ($ratingIndex = 1; $ratingIndex < count($ratings); $ratingIndex++) {
            $ratingValue = $ratingIndex - 1;
            echo <<< EOC32
                <option value="$ratingValue">$ratings[$ratingIndex]</option>
EOC32;
        }
        echo <<< EOC33
        </select></td>
        </tr>
EOC33;
    }
    echo <<< EOC4
    </table>
<br>
EOC4;
}

echo <<<EOC5
    <button onclick="return getData(event)" id="button"
                        class="ui-button ui-corner-all ui-widget">Submit Ratings
    </button>
</form>
<script src="../jquery-ui-1.12.1.custom/external/jquery/jquery.js"></script>
<script src="../jquery-ui-1.12.1.custom/jquery-ui.js"></script>
<script type="text/javascript">

function getData(event){
    event.preventDefault();
    var data=[];
    var student_count = $group_members_count;
    var category_count = $category_count;
    var student_id = $sessionObj->student_id;

    for(var group_member_index=0; group_member_index < student_count; group_member_index++) {
        var row = [];
        row.push(student_id);
        row.push(document.getElementById("group_member_"+group_member_index).value);
        
        for(var cat_index=0; cat_index < category_count; cat_index++) {
            row.push(parseInt(getSelectData("category_",cat_index, group_member_index)));
        }
        data.push(row);
    }
    
    postToPHPforInsertion(data);
}
 
function getSelectData(str, i, j){
    var id = str+i+j;
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
        document.getElementById("student_eval").innerHTML = "";
        document.getElementById("student_eval").innerHTML = msg;
        return false;
    });
    return true;
}
</script>
EOC5;
