<?php
function isFlashMessage() {
    if( isset($_SESSION['success']) )
        return true;
    if( isset($_SESSION['error']) && $_SESSION['error'] != false )
        return true;
    return false;
}

function flashMessage() {
    if( isset($_SESSION['success']) ){
        echo "<p id='success'>";
        echo    $_SESSION['success'];
        echo "</p>";
        unset($_SESSION['success']);
    }

    if( isset($_SESSION['error']) && $_SESSION['error'] != false ){
        echo "<p id='error'>";
        echo    $_SESSION['error'];
        echo "</p>";
    }
    unset($_SESSION['error']);
}


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

function validateProfile() {
    $result = false;
    $count = count_atSigns( $_POST['email'] );

    if( $count !== 1 )
        return 'Email address must contain @';
    if( strlen($_POST['first_name']) < 1 )
        return 'First name is required';
    if( strlen($_POST['last_name']) < 1 )
        return 'Last name is required';
    if( strlen($_POST['headline']) < 1 )
        return 'Headline is required';
    if( strlen($_POST['summary']) < 1 )
        return 'Summary is required';

    if( $result == true ){
        $_SESSION['first_name'] = $_POST['first_name'];
        $_SESSION['last_name'] = $_POST['last_name'];
        $_SESSION['email'] = $_POST['email'];
        $_SESSION['headline'] = $_POST['headline'];
        $_SESSION['summary'] = $_POST['summary'];
    }

    return false;
}

function validatePosition() {
    for( $i=1; $i<=9; $i++ ){
        if( ! isset($_POST['year'.$i]) ) continue;
        if( ! isset($_POST['desc'.$i]) ) continue;

        $year = $_POST['year'.$i];
        $desc = $_POST['desc'.$i];

        if( strlen($year) == 0 || strlen($desc) == 0 ){
            return "All fields are required";
        }
        else if( ! is_numeric($year) ){
            return "Position year must be numeric";
        }
    }

    return false;
}

function unsetSessionVars() {
    unset( $_SESSION['first_name'] );
    unset( $_SESSION['last_name'] );
    unset( $_SESSION['headline'] );
    unset( $_SESSION['summary'] );

    for( $i=1; $i<=9; $i++ ){
        if( ! isset($_POST['year'.$i]) ) continue;
        if( ! isset($_POST['desc'.$i]) ) continue;

        unset( $_SESSION['year'.$i] );
        unset( $_SESSION['desc'.$i] );
    }
}

// Copies data from POST to SESSION.
function fromPostToSession() {
    $_SESSION['first_name'] = $_POST['first_name'];
    $_SESSION['last_name'] = $_POST['last_name'];
    $_SESSION['email'] = $_POST['email'];
    $_SESSION['headline'] = $_POST['headline'];
    $_SESSION['summary'] = $_POST['summary'];

    for( $i=1; $i<=9; $i++ ){
        if( ! isset($_POST['year'.$i]) ) continue;
        if( ! isset($_POST['desc'.$i]) ) continue;

        $_SESSION['year'.$i] = $_POST['year'.$i];
        $_SESSION['desc'.$i] = $_POST['desc'.$i];
    }
}
