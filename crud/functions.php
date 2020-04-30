<?php
	session_start();

	$errors = '';

	// connect to database
	$db = mysqli_connect('localhost', 'root', '', 'no_name');

	if (mysqli_connect_errno()) {
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}

	// LOGIN USER
	if (isset($_POST['submit'])) {
		
		$username = mysqli_real_escape_string($db, $_POST['username']);
		$password = mysqli_real_escape_string($db, $_POST['password']);

		$password = md5($password);

		$query    = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
		$results  = mysqli_query($db, $query);
		
		if (mysqli_num_rows($results) == 1) {
            $user = mysqli_fetch_assoc($results);
			$_SESSION['SESS_USER_ID'] = $user['user_id'];

			// header('location: .../../web/dashboard.php');
			header('location: dashboard.php');
			exit();
		} else {
			$errors ='<span style="color:#FF0000;">Wrong Username and Password.</span>';
		}

	}

?>