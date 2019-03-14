<!DOCTYPE HTML>

<?php
require_once('pdo.php');
require_once('functions.php');
require_once('imports.php');

session_start();

if( ! isset($_SESSION['name']) ){
    die("Not logged in");
}

if( isset($_POST['cancel']) ){
	header('Location: index.php');
	return;
}

if (isset($_POST['add'])) {
    // $_SESSION['error'] = false;
      
/*     if( strlen($_POST['email']) < 1
    || strlen($_POST['first_name']) < 1
    || strlen($_POST['last_name']) < 1
    || strlen($_POST['headline']) < 1
    || strlen($_POST['summary']) < 1 ){
        $_SESSION['error'] = "All fields are required";
        header( 'Location: add.php' );
        return;
    } */

    $_SESSION['error'] = validateProfile();
    if( $_SESSION['error'] == false )
        $_SESSION['error'] = validatePosition();
        
    if( $_SESSION['error'] == true ){
        fromPostToSession();
        header('Location: add.php');
        return;
    }

    try {
        $stmt = $pdo->prepare('INSERT INTO
            Profile (user_id, first_name, last_name, email, headline, summary)
            VALUES (:user_id, :first_name, :last_name, :email, :headline, :summary)');
        $stmt->execute(array(
            ':user_id' => $_SESSION['user_id'],
            ':first_name' => $_POST['first_name'],
            ':last_name' => $_POST['last_name'],
            ':email' => $_POST['email'],
            ':headline' => $_POST['headline'],
            ':summary' => $_POST['summary'])
        );

        $profile_id = $pdo->lastInsertId();
        
        print $profile_id;
        print $_POST['year1'];

        $rank = 1;
        for( $i=1; $i<=9; $i++ ){
            if( ! isset($_POST['year'.$i]) ) continue;
            if( ! isset($_POST['desc'.$i]) ) continue;

            $stmt = $pdo->prepare('INSERT INTO
                Position (profile_id, rank, year, description)
                VALUES (:profile_id, :rank, :year, :descr)');
            $stmt->execute(array(
                ':profile_id' => $profile_id,
                ':rank' => $rank,
                ':year' => $_POST['year'.$i],
                ':descr' => $_POST['desc'.$i])
            );
            
            $rank++;
        }

    } catch( Exception $ex ){
        echo("Internal error, please contact support");
        // Why error4?
        error_log("error4.php, SQL error=".$ex->getMessage());
        return;
    }
    $_SESSION['success'] = 'Profile added';
    header('Location: index.php');
    return;
}
?>

<html lang='en'>

<head>
	<meta charset='UTF-8'>
	<link rel='stylesheet' href='css/style.css'>
	<title> Resume Registry - 52c8b8d5 </title>
</head>

<body>
	<div id='fb'>
		<header>
			<h1> Profile information </h1>
		</header>

		<form class='box' method='post'>
			<p>
				<label for='first_name'>First Name: </label>
                <input type='text' name='first_name' id='first_name' size='20'
                    value='<?= isset($_SESSION['first_name']) ? htmlentities($_SESSION['first_name']) : '' ?>'>
			</p>
			<p>
				<label for='last_name'>Last Name: </label>
                <input type='text' name='last_name' id='last_name' size='20'
                    value='<?= isset($_SESSION['last_name']) ? htmlentities($_SESSION['last_name']) : '' ?>'>
			</p>
			<p>
				<label for='email'>Email: </label>
				<input type='text' name='email' id='email' size='30'
                    value='<?= isset($_SESSION['email']) ? htmlentities($_SESSION['email']) : '' ?>'>
			</p>
			<p>
				<label for='headline'>Headline: </label><br>
				<input type='text' name='headline' id='headline' size='70'
                    value='<?= isset($_SESSION['headline']) ? htmlentities($_SESSION['headline']) : '' ?>'>
            </p>
			<p>
				<label for='summary'>Summary: </label><br>
				<textarea rows='8' cols='80' name='summary' id='summary' size='80'><?= isset($_SESSION['summary']) ? htmlentities($_SESSION['summary']) : '' ?></textarea>
			</p>
            <p>
                <label for="addPos">Position: </label><input type="submit" id="addPos" value="+">
            </p>
            <div id="position_fields"></div>
			<p>
                <input type="submit" class="button" name="add" value="Add" >
                <input type="submit" class="button" name="cancel" value="Cancel">
			</p>
            <?php
            flashMessage();
            unsetSessionVars();
            ?>
		</form>
	</div>

    <?php importJQ(); ?>
    <script> let countPos = 0; </script>
	<script src="js/script.js"></script>
</body>
</html>