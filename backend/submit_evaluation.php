<?php

include_once('website_page_handle.php');
include_once('session.php');
include_once("student_evaluation_model.php");

// We get the created session instance which is created earlier then proceed or error out
$sessionObj = Session::getInstance();

if (isset($_POST['form_data'])) {
    $form_data = $_POST['form_data'];
    $count = count($form_data);
    $insertSuccess = true;
    $insertSuccess = $insertSuccess & insertIntoTable($form_data);
}

function insertIntoTable($form_data)
{
    for ($i = 0; $i < count($form_data); $i++) {
        $var0 = $form_data[$i][0];
        $var1 = $form_data[$i][1];
        $var2 = $form_data[$i][2];
        $var3 = $form_data[$i][3];
        $var4 = $form_data[$i][4];
        $var5 = $form_data[$i][5];
        $var6 = $form_data[$i][6];

        $studentEvaluationModel = StudentEvaluationModel::getInstance();
        if ($studentEvaluationModel->insert($var0, $var1, $var2, $var3, $var4, $var5, $var6) === false AND
            $studentEvaluationModel->update($var0, $var1, $var2, $var3, $var4, $var5, $var6) === false) {
            return false;
        }
        return true;
    }
}