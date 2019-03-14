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

check_profile_ownage( $pdo );

if(isset($_POST['save']) ){
    // $_SESSION['error'] = false;
    
/*     if( strlen($_POST['email']) < 1
    || strlen($_POST['first_name']) < 1
    || strlen($_POST['last_name']) < 1
    || strlen($_POST['headline']) < 1
    || strlen($_POST['summary']) < 1 ){
        $_SESSION['error'] = "All fields are required";
        header('Location: edit.php?profile_id='.$_REQUEST['profile_id']);
        return;
    } */

    $_SESSION['error'] = validateProfile();
    if( $_SESSION['error'] == false )
        $_SESSION['error'] = validatePosition();

    if( $_SESSION['error'] == true ){
        fromPostToSession();
        header('Location: edit.php?profile_id='.$_REQUEST['profile_id']);
        return;
    }

    try {
        $stmt = $pdo->prepare( 'UPDATE Profile SET
                                    first_name = :first_name,
                                    last_name = :last_name,
                                    email = :email,
                                    headline = :headline,
                                    summary = :summary
                                WHERE profile_id = :profile_id' );
        $stmt->execute(array(
            ':profile_id' => $_POST['profile_id'],
            ':first_name' => $_POST['first_name'],
            ':last_name' => $_POST['last_name'],
            ':email' => $_POST['email'],
            ':headline' => $_POST['headline'],
            ':summary' => $_POST['summary'])
        );

        // Clears out the old position entries
        $stmt = $pdo->prepare( 'DELETE FROM Position WHERE profile_id=:pid' );
        $stmt->execute(array( ':pid' => $_REQUEST['profile_id'] ));

        // Insert the new or altered position entries. This snippet is almost identical
        // to the add.php snippet that inserts Position data.
        $rank = 1;
        for( $i=1; $i<=9; $i++ ){
            if( ! isset($_POST['year'.$i]) ) continue;
            if( ! isset($_POST['desc'.$i]) ) continue;

            $stmt = $pdo->prepare('INSERT INTO
                Position (profile_id, rank, year, description)
                VALUES (:profile_id, :rank, :year, :descr)');
            $stmt->execute(array(
                ':profile_id' => $_REQUEST['profile_id'],
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
    $_SESSION['success'] = 'Profile saved';
    header('Location: index.php');
    return;
}

$stmt = $pdo->query( 'SELECT * FROM Profile WHERE profile_id = '.$_GET['profile_id'] );
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if( $row === false ) {
    $_SESSION['error'] = 'Bad value for profile_id';
    header( 'Location: index.php' );
    return;
}

$profile_id = $row['profile_id'];
$first_name = htmlentities( $row['first_name']);
$last_name = htmlentities( $row['last_name']);
$email = htmlentities( $row['email']);
$headline = htmlentities( $row['headline']);
$summary = htmlentities( $row['summary']);

$stmt = $pdo->query( 'SELECT * FROM Position WHERE profile_id = '.$profile_id );
$i = 1;
while( $row = $stmt->fetch(PDO::FETCH_ASSOC) ){
    $year[$i] = htmlentities( $row['year']);
    $desc[$i] = htmlentities( $row['description']);
    $i++;
}
$maxRank = $i - 1;
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
			<h1> Editing <?= $first_name.' '.$last_name ?>'s profile </h1>
		</header>

		<form class='box' method='post'>
            <input type='hidden' name='profile_id' value='<?= $profile_id ?>'>
			<p>
				<label for='first_name'>First Name: </label>
                <input type='text' name='first_name' id='first_name' size='20' value='<?= $first_name ?>'>
			</p>
			<p>
				<label for='last_name'>Last Name: </label>
                <input type='text' name='last_name' id='last_name' size='20' value='<?= $last_name ?>'>
			</p>
			<p>
				<label for='email'>Email: </label>
				<input type='text' name='email' id='email' size='30' value='<?= $email ?>'>
			</p>
			<p>
				<label for='headline'>Headline: </label><br>
				<input type='text' name='headline' id='headline' size='70' value='<?= $headline ?>'>
            </p>
			<p>
				<label for='summary'>Summary: </label><br>
				<textarea rows='8' cols='80' name='summary' id='summary' size='80'><?= $summary ?></textarea>
			</p>
            <p>
                <label for="addPos">Position: </label><input type="submit" id="addPos" value="+">
            </p>
            <div id="position_fields">
                <?php
                for( $i=1; $i<=$maxRank; $i++ ){
                    echo '
                        <div id="position'.$i.'">
                            <p>Year:
                                <input type="text" name="year'.$i.'" value="'.$year[$i].'" />
                                <input type="button" value="-"
                                    onclick="$(\'#position'.$i.'\').remove();return false;">
                            </p>
                            <textarea name="desc'.$i.'" rows="8" cols="80">'.$desc[$i].'</textarea>
                        </div>';
                }
                ?>
            </div>
			<p>
                <input type="submit" class="button" name="save" value="Save" >
                <input type="submit" class="button" name="cancel" value="Cancel">
			</p>
            <?php
            flashMessage();
            unsetSessionVars();
            ?>
		</form>
	</div>

    <?php importJQ(); ?>
    <script> let countPos = <?php echo json_encode($maxRank); ?>; </script>
    <script src="js/script.js"></script>
</body>
</html>