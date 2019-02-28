<!DOCTYPE HTML>

<?php
require_once('pdo.php');
require_once('functions.php');

session_start();

if( ! isset($_SESSION['name']) ){
    die("Not logged in");
}

if( isset($_POST['cancel']) ){
	header('Location: index.php');
	return;
}

check_profile_ownage( $pdo );

if( isset($_POST['delete']) ){
    $_SESSION['msg'] = false;
    $_error = false;

    try {
        $sql = "DELETE FROM Profile WHERE profile_id = :profile_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array( ':profile_id' => $_POST['profile_id'] ));
    } catch( Exception $ex ){
        echo("Internal error, please contact support");
        // Why error4?
        error_log("error4.php, SQL error=".$ex->getMessage());
        return;
    }
    $_SESSION['msg'] = 'Profile deleted';
    header('Location: index.php');
    return;
}

if( !isset($_GET['profile_id']) ) {
    $_SESSION['msg'] = "Missing profile_id";
    $_SESSION['error'] = true;
    header( 'Location: index.php' );
    return;
}

$stmt = $pdo->prepare('SELECT profile_id, first_name, last_name FROM Profile WHERE profile_id = :profile_id');
$stmt->execute(array( ':profile_id' => $_GET['profile_id'] ));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if( $row === false ) {
    $_SESSION['msg'] = 'Bad value for profile_id';
    $_SESSION['error'] = true;
    header( 'Location: index.php' );
    return;
}

?>

<html lang='en'>

<head>
	<meta charset='UTF-8'>
	<link rel='stylesheet' href='css/style.css'>
	<title> Resume Registry </title>
</head>

<body>
	<div id='fb'>
		<header>
			<h1> Confirm: Deleting <?= htmlentities($row["first_name"]).' '.htmlentities($row["last_name"]) ?>'s profile </h1>
		</header>

		<form class='box' method='post'>
            <input type='hidden' name='profile_id' value='<?= $row["profile_id"] ?>'>
            <input type="submit" class="button" name="delete" value="Delete" >
            <input type="submit" class="button" name="cancel" value="Cancel">
		</form>
	</div>
</body>
</html>