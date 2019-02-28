<!DOCTYPE HTML>

<?php
require_once('functions.php');
require_once('pdo.php');

?>

<html lang='en'>

<head>
	<meta charset='UTF-8'>
	<link rel='stylesheet' href='css/style.css'>
	<title> Resume Registry - 553c3741 </title>
</head>

<body>
	<div id='fb'>
		<header>
			<h1> Profile information </h1>
		</header>

		<div class='box'>
			<?php
            $stmt = $pdo->query( 'SELECT first_name, last_name, email, headline, summary FROM Profile WHERE profile_id = '.$_GET['profile_id'] );
            if( $stmt->rowCount() == 0 ){
                echo '<p> Wrong profile id </p>';
            } else {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                echo '<p> First Name: '.$row['first_name'].'<p>';
                echo '<p> Last Name: '.$row['last_name'].'<p>';
                echo '<p> Email: '.$row['email'].'<p>';
                echo '<p> Headline: <br>'.$row['headline'].'<p>';
                echo '<p> Summary: <br>'.$row['summary'].'<p>';
            }
			?>
        </div>
        <p>
            <a href='index.php'>Done</a>
        </p>
	</div>
</body>
</html>