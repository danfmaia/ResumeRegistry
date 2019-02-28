<?php
// Counts the number of at-signs in the email string.
function count_atSigns( $email ){
    $count = 0;
    for( $i=0; $i<strlen($email); $i++ ){
        if( $email[$i] == '@' ){
            $count++;
        }
    }
    return $count;
}
function check_profile_ownage( $pdo ) {
    if( isset($_GET['profile_id']) ){
        $stmt = $pdo->query('SELECT user_id FROM Profile WHERE profile_id = '.$_GET['profile_id']);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($_SESSION['user_id'] != $row['user_id']) {
            die("YOU DO NOT OWN THIS PROFILE");
        }
    }
}
?>