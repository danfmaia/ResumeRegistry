<!DOCTYPE HTML>

<?php
require_once('pdo.php');
require_once('functions.php');

session_start();

// Enters here only in POST request.
if( isset($_POST['cancel']) ){
    session_destroy();
	header('Location: index.php');
	return;
}

// Enters here only in POST request.
if( isset($_POST['email']) ) {
    unset( $_SESSION['user_id'] );
    unset( $_SESSION['name'] );
	$email = ( !empty($_POST['email']) ? $_POST['email'] : '' );
	$pass = ( !empty($_POST['pass']) ? $_POST['pass'] : '' );
	$_SESSION['msg'] = false;

    // Query for user_id and password in the DB.
    $stmt = $pdo->prepare( 'SELECT user_id, name, password FROM User WHERE email = :email' );
    $stmt->execute( array(':email' => $_POST['email']) );
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $hash = $row['password'];

	if (!empty($email) && !empty($pass)) {
		// $error_message = 'Incorrect password';
		$salt = 'XyZzy12*_';
		$_SESSION['wrong_password'] = false;

        $count = count_atSigns( $email );
        if( $count !== 1 ){
            $_SESSION['msg'] = 'Please enter a valid email address';
            header( 'Location: login.php' );
            return;
        }

		if ($count === 1) {
			$check = hash('md5', $salt . $pass);
			if( $check == $hash ){
				error_log( "Login success ".$email );
				// Stores email in SESSION data and redirects to view.php using GET request (without GET parameters this time).
                $_SESSION['name'] = $row['name'];
                $_SESSION['email'] = $_POST['email'];
                $_SESSION['user_id'] = $row['user_id'];
				header( 'Location: index.php' );
				return;
			} else {
				error_log( "Login fail ".$email." $check" );
                $_SESSION['msg'] = "Incorrect login or password";
                $_SESSION['wrong_password'] = true;
                header( "Location: login.php" );
                return;
			}
		}
	} else {
        $_SESSION['msg'] = "Email and password are required";
        header( "Location: login.php" );
        return;
    }
    
	// Error. Execution's not supposed to reach here.
	die( "Should not pass by here" );
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
			<h1> Please Log In </h1>
		</header>

		<form class="box" method="post">
			<p>
				<label for='email'>Email: </label>
				<input type='text' name='email' id='email' size='20' value=''>
			</p>
			<p>
				<label for='pass'>Password: </label>
				<input type='password' name='pass' id='pass' size='20' value=''>
			</p>
			<p>
                <!-- Check how to set a submit button as default for Enter pressing, so button order can be changed -->
                <input type='submit' class='button' name='login' value='Log In' onclick="return doValidate();">
                <input type='submit' class='button' name='cancel' value='Cancel'>
            </p>

			<?php
			if( isset($_SESSION['msg']) ){
				echo "<p id='error'>";
				echo    ( $_SESSION['msg'] );
                echo "</p>";
                unset( $_SESSION['msg'] );
				
				/* if( isset($_SESSION['wrong_password']) && $_SESSION['wrong_password'] === true ){
					echo "<p id='login_tip'>";
					echo    "The password is the programming language used for this application concatenated with <span class='pre'>123</span>.";
                    echo "</p>";
                }
                unset( $_SESSION['wrong_password'] ); */
			}
            ?>
            
		</form>
    </div>

<script>
function doValidate() {
    console.log('Validating...');
    try {
        email = document.getElementById('email').value;
        pass = document.getElementById('pass').value;
        console.log("Validating email="+email);
        console.log("Validating password="+pass);
        if( email == null || email == "" || pass == null || pass == "" ){
            alert("Both fields must be filled out");
            return false;
        }
        return true;
    } catch(e) {
        return false;
    }
    return false;
}
</script>

</body>
</html>
