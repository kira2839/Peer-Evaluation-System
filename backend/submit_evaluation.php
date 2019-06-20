<?php
include ("./backend/db_connector.php");
$db_connection = new DBConnector();
$connection = $db_connection -> getDBConnection();

if (!isset($_POST['form_data'])) {
    echo "Data Received";
}

$form_data = $_POST['form_data'];
$count = count($form_data);
//print(count($form_data));
//print($form_data[0][0]);
insertIntoTable($connection,$form_data);

function insertIntoTable($connection,$form_data){
    // INSERT INTO `student_evaluation`(`fk_student_id`, `fk_group_member_id`, `role`, `leadership`, `participation`, `professionalism`, `quality`) VALUES ([value-1],[value-2],[value-3],[value-4],[value-5],[value-6],[value-7])
    for($i=0;$i<count($form_data);$i++) {
        $var0 = $form_data[$i][0];
        $var1 = $form_data[$i][1];
        $var2 = $form_data[$i][2];
        $var3 = $form_data[$i][3];
        $var4 = $form_data[$i][4];
        $var5 = $form_data[$i][5];
        $var6 = $form_data[$i][6];
//        $var7 = $form_data[$i][7];
        $query = "INSERT INTO `student_evaluation`(`fk_student_id`, `fk_group_member_id`, `role`, `leadership`, `participation`, `professionalism`, `quality`) VALUES ($var0,$var1,$var2,$var3,$var4,$var5,$var6)";
        mysqli_query($connection, $query);
        print($query);
    }
}