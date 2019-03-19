<!DOCTYPE HTML>

<?php
require_once('functions.php');
require_once('pdo.php');

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
			<h1> Profile information </h1>
		</header>

		<div class='box'>
			<?php

            // Get and show profile basic info.

            $stmt = $pdo->query( 
                'SELECT first_name, last_name, email, headline, summary
                FROM Profile
                WHERE profile_id = '.$_GET['profile_id'] );
            if( $stmt->rowCount() === 0 ){
                echo '<p> Wrong profile id </p>';
            } else {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                echo '<p> First Name: '.$row['first_name'].'<p>';
                echo '<p> Last Name: '.$row['last_name'].'<p>';
                echo '<p> Email: '.$row['email'].'<p>';
                echo '<p> Headline: <br>'.$row['headline'].'<p>';
                echo '<p> Summary: <br>'.$row['summary'].'<p>';
            }
            
            // Get and show profile Position info.
            
            $stmt = $pdo->query( 
                'SELECT year, description
                FROM Position
                WHERE profile_id = '.$_GET['profile_id'] );
            if ($stmt->rowCount() === 1) {
                echo '<p style="margin-bottom:0"> Position: </p>';
                echo '<ul style="margin-top:0">';
            } elseif( $stmt->rowCount() > 1 ){
                echo '<p style="margin-bottom:0"> Positions: </p>';
                echo '<ul style="margin-top:0">';
            }
            while( $row = $stmt->fetch(PDO::FETCH_ASSOC) ){
                echo '<li>'.$row['year'].': '.$row['description'].'</li>';
            }
            if( $stmt->rowCount() > 0 )
                echo '</ul>';

            // Get and show profile Education info.

            $stmt = $pdo->query(
                'SELECT T1.year, T2.name
                FROM Education as T1 INNER JOIN Institution as T2
                ON T1.institution_id = T2.institution_id
                WHERE profile_id = '.$_GET['profile_id'] );
            if ($stmt->rowCount() === 1) {
                echo '<p style="margin-bottom:0"> Education: </p>';
                echo '<ul style="margin-top:0">';
            } elseif( $stmt->rowCount() > 1 ){
                echo '<p style="margin-bottom:0"> Educations: </p>';
                echo '<ul style="margin-top:0">';
            }
            while( $row = $stmt->fetch(PDO::FETCH_ASSOC) ){
                echo '<li>'.$row['year'].': '.$row['name'].'</li>';
            }
            if( $stmt->rowCount() > 0 )
                echo '</ul>';

			?>
        </div>
        <p>
            <a href='index.php'>Done</a>
        </p>
	</div>
</body>
</html>