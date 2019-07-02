<?php

include_once('website_page_handle.php');
include_once('session.php');
include_once('student_model.php');
include_once('student_group_model.php');
include_once('evaluation_meaning_model.php');
include_once ('student_evaluation_model.php');

//Start creating the html tags
WebSitePageHandle::includeSiteHeader();

// We get the created session instance which is created earlier then proceed or error out
$sessionObj = Session::getInstance();

if (!$sessionObj->isSessionValid()) {
    $sessionObj->destroy();
    $sessionObj->displaySessionExpiredMessage();
    return;
}

if (!isset($sessionObj->course_name)) {
    echo <<<EOC
    <div class="ui-state-error ui-corner-all" style="padding: 0 .7em; display: inline-block;">
		<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
		Course not selected </p>
	</div>
EOC;
    return;
}

echo <<<EOC1
    <p id="evaluation_header" class="show_msg"> Evaluation for $sessionObj->course_name </p>
    <div id="course_selection_button" style="display: inline-block;">
    <a class="bar_li about orange" style="float:right; background: #CC852A;" href="course_selection.php">Change Course</a>
	</div><br><br>
    <form id="student_eval" name="start_eval">
EOC1;

$group_members_names = $sessionObj->group_members_names;
$group_members_count = count($sessionObj->group_members_id);
$category_count = count($sessionObj->evaluation_meaning);

foreach ($sessionObj->group_members_id as $index => $group_member) {
    $evaluation_data = array();
    $evaluation_data = StudentEvaluationModel::getInstance()->getEvaluationPerGroupMember($sessionObj->student_id, $group_member,$sessionObj->course_name);
    if (count($evaluation_data) < 1) {
        for ($i = 0; $i < $category_count; $i++) {
            array_push($evaluation_data, 2);
        }
    }

    echo <<< EOC2
    <li id="group_member_$index" value="$group_member" class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-top">$group_members_names[$index]</li>
    <table class="ui-datepicker-calendar ui-widget ui-widget-content ui-corner-bottom" style="width:100%">
      <tr>
        <th>Category</th>
        <th>Ratings</th> 
      </tr>
EOC2;
    foreach ($sessionObj->evaluation_meaning as $catIndex => $ratings) {
        $selected_option = $evaluation_data[$catIndex];
        echo <<< EOC31
        <tr>
        <td>$ratings[0]</td>
        <td><select id="category_$catIndex$index" class="ui-selectmenu-button ui-selectmenu-button-closed ui-corner-all ui-button ui-widget">
EOC31;
        for ($ratingIndex = 1; $ratingIndex < count($ratings); $ratingIndex++) {
            $ratingValue = $ratingIndex - 1;
            if ($ratingValue==$selected_option) {
                echo <<< EOC34
                <option selected="selected" value="$ratingValue">$ratings[$ratingIndex]</option>
EOC34;

            } else {
                echo <<< EOC32
                <option value="$ratingValue">$ratings[$ratingIndex]</option>
EOC32;
            }
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.1/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script type="text/javascript">


function removeSuccessMsg() {
    var node = document.getElementById("display_evaluation_submit_result");
    $("#display_evaluation_submit_result").fadeOut(1000);
    setTimeout(redirectToDisplay, 1000);
}

function redirectToDisplay() {
    window.location.href = "display_evaluation.php";
}

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
    
    postToPHPforUpdation(data);
}
 
function getSelectData(str, i, j){
    var id = str+i+j;
    var elt = document.getElementById(id);
    return elt.options[elt.selectedIndex].value;
}

function postToPHPforUpdation(data) {
    $.ajax({
        type: "POST",
        url: "submit_evaluation.php",
        data: {
            form_data: data
        }
    }).done(function (msg) {
        var tagToRemove = document.getElementById("course_selection_button");
        tagToRemove.parentNode.removeChild(tagToRemove);
        tagToRemove = document.getElementById("evaluation_header");
        tagToRemove.parentNode.removeChild(tagToRemove);
        document.getElementById("student_eval").innerHTML = "";
        document.getElementById("student_eval").innerHTML = msg;
        setTimeout(removeSuccessMsg, 1000);
    });
    return false;
}
</script>
EOC5;

return "success";
