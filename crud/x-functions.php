<?php
	session_start();

	$f_name    	    = '';
	$l_name    	    = '';
	$email 	   	    = '';
	$birthdate	    = '';
	$myAge	        = '';
	$gender    	    = '';
	$username  	    = '';
	$comp_name      = '';
	$comp_details   = '';
	$comp_address   = '';
	$emp_fname      = '';
	$emp_lname      = '';
	$emp_email      = '';
	$emp_contact    = '';
	$emp_username   = '';
	$contact        = '';
	$address        = '';
	$nationality    = '';
	$status         = '';
	$job_title	    = '';
	$job_skill      = '';
	$tech_skill     = '';
	$job_descr      = '';
	$educ           = '';
	$job_status	    = '';
	$job_level	    = '';
	$feed	        = '';
	$avatar_path    = '';
	$errors    	    = array();

	// connect to database
	$db = mysqli_connect('localhost', 'root', '', 'quix');

	if (mysqli_connect_errno()) {
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}

	if (isset($_SESSION['SESS_USER_ID'])) {
  			header('location: userhome.php');
	}
	if (isset($_SESSION['SESS_EMP_ID'])) {
  			header('location: emp_home.php');
	}
	if (isset($_SESSION['SESS_ADM_ID'])) {
  			header('location: admin_panel.php');
	}

	$sqli = "DELETE FROM job WHERE date_posted < (NOW() - INTERVAL 1 HOURS)";
	mysqli_query($db, $sqli);

	// REGISTER - EMPLOYER
    if (isset($_POST['emp_register_btn'])) {
		$comp_name       = mysqli_real_escape_string($db, $_POST['comp_name']);
		$comp_details    = mysqli_real_escape_string($db, $_POST['comp_details']);
		$comp_address    = mysqli_real_escape_string($db, $_POST['comp_address']);
		$emp_fname	     = mysqli_real_escape_string($db, $_POST['emp_fname']);
		$emp_lname       = mysqli_real_escape_string($db, $_POST['emp_lname']);
		$emp_email       = mysqli_real_escape_string($db, $_POST['emp_email']);
		$emp_contact     = mysqli_real_escape_string($db, $_POST['emp_contact']);
		$username        = mysqli_real_escape_string($db, $_POST['username']);
		$password        = mysqli_real_escape_string($db, $_POST['password']);
		$emp_cpassword   = mysqli_real_escape_string($db, $_POST['emp_cpassword']);
		$avatar_path     = mysqli_real_escape_string($db, 'uploads/'.$_FILES['avatar']['name']);

		// FORM VALIDATION
		if (empty($comp_name)) {
			array_push($errors, "Company Name is required");
		}
		if (empty($comp_details)) {
			array_push($errors, "Company Details is required");
		}
		if (empty($comp_address)) {
			array_push($errors, "Company Address is required");
		}
		if (empty($emp_fname)) {
			array_push($errors, "First Name is required");
		}
		if (empty($emp_lname)) {
			array_push($errors, "Last Name is required");
		}
		if (empty($emp_email)) {
			array_push($errors, "Email is required");
		}
		if (empty($emp_contact)) {
			array_push($errors, "Contact is required");
		}
		if (empty($emp_contact)) {
			array_push($errors, "Contact is required");
		}
		if (empty($username)) {
			array_push($errors, "Username is required");
		}
		if (empty($password)) {
			array_push($errors, "Password is required");
		}
		if ($password != $emp_cpassword) {
			array_push($errors, "Password does not match!");
		}
		if ($avatar_path == null) {
			array_push($errors, "Profile Picture is required");
		}

		$checkx = "SELECT * FROM employer WHERE username = '$username'";
		$checkxrow = mysqli_query($db, $checkx);

		if (mysqli_num_rows($checkxrow) > 0) {
			array_push($errors, "Username already exists!");
		}

		$checksx = "SELECT * FROM employer WHERE emp_email = '$emp_email'";
		$checkrowsx = mysqli_query($db, $checksx);

		if (mysqli_num_rows($checkrowsx) > 0) {
			array_push($errors, "Email already exists!");
		}

		// REGISTER - NO ERRORS
		if (count($errors) == 0) {
			$password = md5($emp_cpassword);

			if (preg_match("!image!", $_FILES['avatar']['type'])) {

				if (copy($_FILES['avatar']['tmp_name'], $avatar_path)) {

					$queryx = "INSERT INTO employer (comp_name, comp_details, comp_address, emp_fname, emp_lname, emp_email, emp_contact, 							  username, password, date_registered, avatar)
							  VALUES('$comp_name', '$comp_details', '$comp_address', '$emp_fname', '$emp_lname', '$emp_email', '$emp_contact', '$username', '$password', NOW(), '$avatar_path')";
					$resultx = mysqli_query($db, $queryx) or die(mysqli_error($db));
					session_regenerate_id();
					$employer = mysqli_fetch_assoc($resultx);
					$_SESSION['SESS_USER_ID']      = $employer['emp_id'];
					$_SESSION['SESS_EMP_USERNAME'] = $employer['username'];
					$_SESSION['SESS_EMP_FNAME']    = $employer['emp_fname'];
					$_SESSION['SESS_EMP_IMG']      = $employer['avatar'];
					session_write_close();
					header('location: login.php');
					$query = "INSERT INTO admin_feed (activity, date_time, quixer_id)
					      VALUES('New Employer: ".$comp_name." of ".$emp_fname." ".$emp_lname." has been registered', NOW(), last_insert_id())";
					mysqli_query($db, $query) or die(mysqli_error($db));
					exit();
				}
			}
		}
	}

	// REGISTER - USER
	if (isset($_POST['register_btn'])) {
		$f_name      = mysqli_real_escape_string($db, $_POST['f_name']);
		$l_name      = mysqli_real_escape_string($db, $_POST['l_name']);
		$email       = mysqli_real_escape_string($db, $_POST['email']);
		$bday        = mysqli_real_escape_string($db, $_POST['bday']);
		$myAge       = mysqli_real_escape_string($db, $_POST['myAge']);
		$gender      = mysqli_real_escape_string($db, $_POST['gender']);
		$username    = mysqli_real_escape_string($db, $_POST['username']);
		$password    = mysqli_real_escape_string($db, $_POST['password']);
		$cpassword   = mysqli_real_escape_string($db, $_POST['cpassword']);
		$avatar_path = mysqli_real_escape_string($db, 'uploads/'.$_FILES['avatar']['name']);	

		// FORM VALIDATION
		if (empty($f_name)) {
			array_push($errors, "FIRST NAME IS REQURED");
		}
		if (empty($l_name)) {
			array_push($errors, "LAST NAME IS REQURED");
		}
		if (empty($email)) {
			array_push($errors, "EMAIL IS REQUIRED");
		}
		if (empty($username)) {
			array_push($errors, "USERNAME IS REQURED");
		}
		if (empty($password)) {
			array_push($errors, "PASSWORD IS REQUIRED");
		}
		if ($password != $cpassword) {
			array_push($errors, "PASSWORD DID NOT MATCH");
		}
		if ($avatar_path === "No file chosen") {
			array_push($errors, "PROFILE PICTURE IS REQUIRED");
		}

		$check = "SELECT * FROM job_seeker WHERE username = '$username'";
		$checkrow = mysqli_query($db, $check);

		if (mysqli_num_rows($checkrow) > 0) {
			array_push($errors, "Username already exists!");
		}

		$checks = "SELECT * FROM job_seeker WHERE email = '$email'";
		$checkrows = mysqli_query($db, $checks);

		if (mysqli_num_rows($checkrows) > 0) {
			array_push($errors, "EMAIL IS ALREADY IN USE");
		}

		$birthday = new DateTime($bday); 
		//adds time interval of 18 years to bday    
		$birthday->add(new DateInterval("P18Y"));
		if (strtotime($_POST['bday']) === false) {
		    // birthday was entered, but was invalid
		    array_push($errors, "ENTER A VALID BIRTHDATE");
		} 
		else if ($birthday > new DateTime()){
			// validate if age is over or equal to 18   
			array_push($errors, "AGE MUST BE 18 AND ABOVE");
		}

		// REGISTER - NO ERRORS
		if (count($errors) == 0) {
			$password = md5($cpassword);
			// validate if uploaded file is image
			if (preg_match("!image!", $_FILES['avatar']['type'])) {
				// validates and copy the uploaded image to server
				if (copy($_FILES['avatar']['tmp_name'], $avatar_path)) {
					array_push($errors, "PROFILE PICTURE IS REQUIRED");	
				// This query will insert the new job seeker to database 	
				$query = "INSERT INTO job_seeker (f_name, l_name, email, birthdate, myAge, gender, 
						  username, password, date_registered, avatar)
					      VALUES('$f_name', '$l_name', '$email', '$bday', '$myAge', '$gender', 
					      '$username', '$password', NOW(), '$avatar_path')";
				mysqli_query($db, $query) or die(mysqli_error($db));
				session_regenerate_id();
				// fetch data of new job seeker
				$user = mysqli_fetch_assoc($results);
				$_SESSION['SESS_USER_ID']    = $user['user_id'];
				$_SESSION['SESS_USER_NAME']  = $user['username'];
				$_SESSION['SESS_FIRST_NAME'] = $user['f_name'];
				$_SESSION['SESS_USER_IMG']   = $user['avatar'];
				session_write_close();
				header('location: login.php');
				$query = "INSERT INTO admin_feed (activity, date_time, quixer_id)
					      VALUES('New Job Seeker: ".$f_name." ".$l_name." has been registered', 
					      NOW(), last_insert_id())";
				mysqli_query($db, $query) or die(mysqli_error($db));
				exit();

				}

			}	
			
		}
	}

	// UPDATE PROFILE PICTURE - USER
	if (isset($_POST['update_pic'])) {
		$avatar_path = mysqli_real_escape_string($db, 'uploads/'.$_FILES['avatar']['name']);
		$f_namex      = mysqli_real_escape_string($db, $_POST['f_namex']);
		$l_namex      = mysqli_real_escape_string($db, $_POST['l_namex']);

		// UPDATE - NO ERRORS
		if (count($errors) == 0) {

			if (preg_match("!image!", $_FILES['avatar']['type'])) {

				if (copy($_FILES['avatar']['tmp_name'], $avatar_path)) {

				$queryz    = "SELECT * FROM job_seeker";
        		$resultsz  = mysqli_query($db, $queryz);

        		$queryzx    = "SELECT * FROM job_seeker
        					  WHERE user_id = '" . $_SESSION['SESS_USER_ID'] . "'";
        		$resultszx  = mysqli_query($db, $queryzx);

				$queryx = "UPDATE job_seeker SET avatar = '$avatar_path' WHERE user_id = '" . $_SESSION['SESS_USER_ID'] . "'";
					$resultsx = mysqli_query($db, $queryx) or die(mysqli_error($db));
					$row = mysqli_fetch_array($resultszx);
					session_regenerate_id();
					$user = mysqli_fetch_assoc($resultsz);
		            $_SESSION['SESS_USER_IDs']          = $job ['user_id']; 
		            $_SESSION['SESS_USER_NAME']         = $user['username'];	
		            $_SESSION['SESS_FIRST_NAME']        = $user['f_name'];
		            $_SESSION['SESS_LAST_NAME']         = $user['l_name'];
		            $_SESSION['SESS_USER_EMAIL']        = $user['email'];
		            $_SESSION['SESS_BIRTH_DATE']        = $user['birthdate'];
		            $_SESSION['SESS_USER_GENDER']       = $user['gender'];
		            $_SESSION['SESS_USER_CONTACT']      = $user['contact'];
		            $_SESSION['SESS_USER_ADDRESS']      = $user['address'];
		            $_SESSION['SESS_USER_NATIONALITY']  = $user['nationality'];
		            $_SESSION['SESS_DATE_REGISTERED']   = $user['date_registered'];
		            $_SESSION['SESS_USER_IMG']          = $user['avatar'];
		            $_SESSION['SESS_USER_STAT']         = $user['status'];
					header('location: user_change_avatar.php');
					$query = "INSERT INTO admin_feed (activity, date_time, quixer_id)
					      	  VALUES('Update: ".$row['f_name']." ".$row['l_name']."`s profile picture has been updated', 
					          NOW(), '" . $_SESSION['SESS_USER_ID'] . "')";
					mysqli_query($db, $query) or die(mysqli_error($db));
				}
			}
		}
		else {
					echo "Error! <br>";
					echo "User ID: {$_SESSION['SESS_USER_ID']}";
		}
	}

	// UPLOAD RESUME - USER
	if (isset($_POST['upload_resume'])) {
		$resume_path = mysqli_real_escape_string($db, 'uploads/'.$_FILES['resume']['name']);
		$f_namex      = mysqli_real_escape_string($db, $_POST['f_namex']);
		$l_namex      = mysqli_real_escape_string($db, $_POST['l_namex']);

		// UPLOAD - NO ERRORS
		if (count($errors) == 0) {

			if (preg_match("!(pdf|doc|docx)!", $_FILES['resume']['type'])) {

				if (copy($_FILES['resume']['tmp_name'], $resume_path)) {

				$queryz    = "SELECT * FROM job_seeker";
        		$resultsz  = mysqli_query($db, $queryz);

        		$queryzx    = "SELECT * FROM job_seeker
        					  WHERE user_id = '" . $_SESSION['SESS_USER_ID'] . "'";
        		$resultszx  = mysqli_query($db, $queryzx);

				$queryx = "UPDATE job_seeker SET resume = '$resume_path' WHERE user_id = '" . $_SESSION['SESS_USER_ID'] . "'";
					$resultsx = mysqli_query($db, $queryx) or die(mysqli_error($db));
					$row = mysqli_fetch_array($resultszx);
					session_regenerate_id();
					$user = mysqli_fetch_assoc($resultsz);
		            $_SESSION['SESS_USER_IDs']          = $job ['user_id']; 
		            $_SESSION['SESS_USER_NAME']         = $user['username'];	
		            $_SESSION['SESS_FIRST_NAME']        = $user['f_name'];
		            $_SESSION['SESS_LAST_NAME']         = $user['l_name'];
		            $_SESSION['SESS_USER_EMAIL']        = $user['email'];
		            $_SESSION['SESS_BIRTH_DATE']        = $user['birthdate'];
		            $_SESSION['SESS_USER_GENDER']       = $user['gender'];
		            $_SESSION['SESS_USER_CONTACT']      = $user['contact'];
		            $_SESSION['SESS_USER_ADDRESS']      = $user['address'];
		            $_SESSION['SESS_USER_NATIONALITY']  = $user['nationality'];
		            $_SESSION['SESS_DATE_REGISTERED']   = $user['date_registered'];
		            $_SESSION['SESS_USER_IMG']          = $user['avatar'];
		            $_SESSION['SESS_USER_RES']          = $user['resume'];
		            $_SESSION['SESS_USER_STAT']         = $user['status'];
					header('location: user_upload_resume.php');
					$query = "INSERT INTO admin_feed (activity, date_time, quixer_id)
					      	  VALUES('Update: ".$row['f_name']." ".$row['l_name']." uploaded a resume.', 
					          NOW(), '" . $_SESSION['SESS_USER_ID'] . "')";
					mysqli_query($db, $query);
				}
			}
		}
		else {
					echo "Error! <br>";
					echo "User ID: {$_SESSION['SESS_USER_ID']}";
		}
	}

		// LOGIN USER
		if (isset($_POST['login_btn'])) {
		$username = mysqli_real_escape_string($db, $_POST['username']);
		$password = mysqli_real_escape_string($db, $_POST['password']);

		// LOGIN VALIDATION
		if (empty($username)) {
			array_push($errors, "Username is required!");
		}
		if (empty($password)) {
			array_push($errors, "Password is required!");
		}

		// LOGIN - NO ERRORS
		if (count($errors) == 0) {
        $password = md5($password);
        $query    = "SELECT * FROM job_seeker WHERE username = '$username' AND password = '$password'";
        $results  = mysqli_query($db, $query);

        if (mysqli_num_rows($results) == 1) {
            session_regenerate_id();
            $user = mysqli_fetch_assoc($results);
            $job  = mysqli_fetch_assoc($result);
            $_SESSION['SESS_USER_ID']           = $user['user_id'];
            $_SESSION['SESS_USER_IDs']          = $job ['user_id']; 
            $_SESSION['SESS_USER_NAME']         = $user['username'];
            $_SESSION['SESS_FIRST_NAME']        = $user['f_name'];
            $_SESSION['SESS_LAST_NAME']         = $user['l_name'];
            $_SESSION['SESS_USER_EMAIL']        = $user['email'];
            $_SESSION['SESS_BIRTH_DATE']        = $user['birthdate'];
            $_SESSION['SESS_USER_AGE']          = $user['myAge'];
            $_SESSION['SESS_USER_GENDER']       = $user['gender'];
            $_SESSION['SESS_USER_CONTACT']      = $user['contact'];
            $_SESSION['SESS_USER_ADDRESS']      = $user['address'];
            $_SESSION['SESS_USER_NATIONALITY']  = $user['nationality'];
            $_SESSION['SESS_DATE_REGISTERED']   = $user['date_registered'];
            $_SESSION['SESS_USER_IMG']          = $user['avatar'];
            $_SESSION['SESS_USER_RES']          = $user['resume'];
            $_SESSION['SESS_USER_STAT']         = $user['status'];

    		$userId = $user['user_id'];
    		$jobId  = $job['user_id'];

            $query    = "SELECT * FROM job_seeker";
    		$resultx  = mysqli_query($db, $query);

    		$user = mysqli_fetch_assoc($resultx);
   			$userId = $user['user_id'];

			$skills  = "SELECT *
			    		FROM job_seeker
			    		INNER JOIN user_skill ON user_skill.user_id = job_seeker.user_id
			    		WHERE user_skill.user_id = '" . $_SESSION['SESS_USER_ID'] . "'";

			$results = mysqli_query($db, $skills);        	 

            if($results->num_rows) {

            	header('location: userhome.php');

            }
            else {

                header('location: userskill.php');

            }
        	session_write_close();
        }

        $queryc    = "SELECT * FROM employer WHERE username = '$username' AND password = '$password'";
        $resultc   = mysqli_query($db, $queryc);    

        if (mysqli_num_rows($resultc) == 1) {
            session_regenerate_id();
            $employer = mysqli_fetch_assoc($resultc);
            $_SESSION['SESS_EMP_ID']         = $employer['emp_id'];
            $_SESSION['SESS_COMP_NAME']      = $employer['comp_name'];
            $_SESSION['SESS_COMP_DETAILS']   = $employer['comp_details'];
            $_SESSION['SESS_COMP_ADDRESS']   = $employer['comp_address'];
            $_SESSION['SESS_EMP_FNAME']      = $employer['emp_fname'];
            $_SESSION['SESS_EMP_LNAME']      = $employer['emp_lname'];
            $_SESSION['SESS_EMP_EMAIL']      = $employer['emp_email'];
            $_SESSION['SESS_EMP_CONTACT']    = $employer['emp_contact'];
            $_SESSION['SESS_EMP_IMG']        = $employer['avatar'];
            $_SESSION['SESS_DATE_REG']		 = $employer['date_registered'];
            

            header('location: emp_home.php');

        	session_write_close();
        }
        $queryd    = "SELECT * FROM admin WHERE username = '$username' AND password = '$password'";
        $resultd   = mysqli_query($db, $queryd);    

        if (mysqli_num_rows($resultd) == 1) {
            session_regenerate_id();
            $admin = mysqli_fetch_assoc($resultd);
            $_SESSION['SESS_ADM_ID']         = $admin['admin_id'];
            $_SESSION['SESS_ADM_NAME']       = $admin['name'];
            $_SESSION['SESS_ADM_UNAME']      = $admin['username'];
            $_SESSION['SESS_ADM_IMG']        = $admin['avatar'];

            header('location: admin_panel.php');

        	session_write_close();
        }
        else {
            array_push($errors, "Wrong username or password!");
        }
    }
    }

	// UPDATE EMPLOYER
	if (isset($_POST['emp_update_info'])) {
		$comp_name       = mysqli_real_escape_string($db, $_POST['comp_name']);
		$comp_details    = mysqli_real_escape_string($db, $_POST['comp_details']);
		$comp_address    = mysqli_real_escape_string($db, $_POST['comp_address']);
		$emp_fname	     = mysqli_real_escape_string($db, $_POST['emp_fname']);
		$emp_lname       = mysqli_real_escape_string($db, $_POST['emp_lname']);
		$emp_email       = mysqli_real_escape_string($db, $_POST['emp_email']);
		$emp_contact     = mysqli_real_escape_string($db, $_POST['emp_contact']);

		// UPDATE - NO ERRORS
		if (count($errors) == 0) {

				$queryzc    = "SELECT * FROM employer";
        		$resultsz  = mysqli_query($db, $queryzc);

				$queryxc = "UPDATE employer SET comp_name = '$comp_name', comp_details = '$comp_details', 
							comp_address = '$comp_address', emp_fname = '$emp_fname', emp_lname = '$emp_lname', 
							emp_email = '$emp_email', emp_contact = '$emp_contact'
					WHERE emp_id = '" . $_SESSION['SESS_EMP_ID'] . "'";
					$resultxx = mysqli_query($db, $queryxc) or die(mysqli_error($db));
					session_regenerate_id();
					header('location: emp_profile.php');
					$query = "INSERT INTO admin_feed (activity, date_time, quixer_id)
					      VALUES('Employer Update: ".$comp_name."`s profile has been updated', NOW(), '" . $_SESSION['SESS_EMP_ID'] . "')";
					mysqli_query($db, $query) or die(mysqli_error($db));
					exit();
		}
		else {
					echo "Error! <br>";
					echo "User ID: {$_SESSION['SESS_USER_ID']}";
		}
	}

	// UPDATE PROFILE PICTURE - EMPLOYER
	if (isset($_POST['emp_update_pic'])) {
		$avatar_pathx = mysqli_real_escape_string($db, 'uploads/'.$_FILES['avatar']['name']);

		// UPDATE - NO ERRORS
		if (count($errors) == 0) {

			if (preg_match("!image!", $_FILES['avatar']['type'])) {

				if (copy($_FILES['avatar']['tmp_name'], $avatar_pathx)) {

				$queryz    = "SELECT * FROM employer";
        		$resultsz  = mysqli_query($db, $queryz);

        		$queryzx    = "SELECT * FROM employer
        					  WHERE emp_id = '" . $_SESSION['SESS_EMP_ID'] . "'";
        		$resultszx  = mysqli_query($db, $queryzx);

				$queryx = "UPDATE employer SET avatar = '$avatar_pathx' 
				WHERE emp_id = '" . $_SESSION['SESS_EMP_ID'] . "'";
					$resultsx = mysqli_query($db, $queryx) or die(mysqli_error($db));
					$row = mysqli_fetch_array($resultszx);
					session_regenerate_id();
					$employer = mysqli_fetch_assoc($resultsx);
					$_SESSION['SESS_USER_ID']      = $employer['emp_id'];
					$_SESSION['SESS_EMP_USERNAME'] = $employer['username'];
					$_SESSION['SESS_EMP_FNAME']    = $employer['emp_fname'];
					$_SESSION['SESS_EMP_IMG']      = $employer['avatar'];
					header('location: emp_change_avatar.php');
					$query = "INSERT INTO admin_feed (activity, date_time, quixer_id)
					      	  VALUES('Update: ".$row['comp_name']."`s profile picture has been updated', 
					          NOW(), '" . $_SESSION['SESS_EMP_ID'] . "')";
					mysqli_query($db, $query) or die(mysqli_error($db));
				}
			}
		}
		else {
					echo "Error! <br>";
					echo "User ID: {$_SESSION['SESS_USER_ID']}";
		}
	}

	if (isset($_POST['apply_btn'])) {
				$message  = mysqli_real_escape_string($db, $_POST['message']);
				$userid   = mysqli_real_escape_string($db, $_POST['userid']);
				$empid    = mysqli_real_escape_string($db, $_POST['empid']);
				$jobid    = mysqli_real_escape_string($db, $_POST['jobid']);

				if (count($errors) == 0) {

				$query = "INSERT INTO message (message, notif, sender, receiver, ref_id, date_sent, status)
					      VALUES('$message', 'An applicant applied to the job you posted.', '$userid', '$empid', '$jobid', NOW(), '0')";
				mysqli_query($db, $query) or die(mysqli_error($db));
				session_regenerate_id();
				session_write_close();
				header('location: userhome.php');
			
				}

	}

	if (isset($_POST['appoint_btn'])) {
				$message  = mysqli_real_escape_string($db, $_POST['message']);
				$userid   = mysqli_real_escape_string($db, $_POST['userid']);
				$empid    = mysqli_real_escape_string($db, $_POST['empid']);
				$jobid    = mysqli_real_escape_string($db, $_POST['jobid']);

				if (count($errors) == 0) {

				$query = "INSERT INTO message (message, notif, sender, receiver, ref_id, date_sent, status)
					      VALUES('$message', 'You have been appointed for interview.', '$empid', '$userid', '$jobid', NOW(), '0')";
				mysqli_query($db, $query) or die(mysqli_error($db));
				session_regenerate_id();
				session_write_close();
				header('location: userhome.php');
			
				}

	}

	if (isset($_POST['send_feed'])) {
				$feedback  = mysqli_real_escape_string($db, $_POST['feedback']);

				if (empty($feedback)) {
					array_push($errors, "Company Name is required");
				}

				if (count($errors) == 0) {

				$querxx = "INSERT INTO feedback (feedback, date_time, quixer_id)
					      VALUES('$feedback', NOW(), '" . $_SESSION['SESS_USER_ID'] . "')";
				mysqli_query($db, $querxx) or die(mysqli_error($db));
				session_regenerate_id();
				session_write_close();
				header('location: userhome.php');
			
				}

	}

	if (isset($_POST['send_feedx'])) {
				$feedback  = mysqli_real_escape_string($db, $_POST['feedback']);

				if (empty($feedback)) {
					array_push($errors, "Company Name is required");
				}

				if (count($errors) == 0) {

				$querxx = "INSERT INTO feedback (feedback, date_time, quixer_id)
					      VALUES('$feedback', NOW(), '" . $_SESSION['SESS_EMP_ID'] . "')";
				mysqli_query($db, $querxx) or die(mysqli_error($db));
				session_regenerate_id();
				session_write_close();
				header('location: emp_home.php');
			
				}

	}

	// UPDATE TRAINING CENTER
	if (isset($_POST['update_tc'])) {
		$tc_name         = mysqli_real_escape_string($db, $_POST['tc_name']);
		$tc_address      = mysqli_real_escape_string($db, $_POST['tc_address']);
		$tc_description  = mysqli_real_escape_string($db, $_POST['tc_description']);
		$tc_offer	     = mysqli_real_escape_string($db, $_POST['tc_offer']);
		$tc_email        = mysqli_real_escape_string($db, $_POST['tc_email']);
		$tc_contact      = mysqli_real_escape_string($db, $_POST['tc_contact']);

		// UPDATE - NO ERRORS
		if (count($errors) == 0) {

				$queryzcv    = "SELECT * FROM training_center";
        		$resultszv   = mysqli_query($db, $queryzcv);

				$queryxcv = "UPDATE training_center SET tc_name = '$tc_name', tc_address = '$tc_address', 
							tc_description = '$tc_description', tc_offer = '$tc_offer', tc_offer = '$tc_offer', 
							tc_email = '$tc_email', tc_contact = '$tc_contact'
					        WHERE tc_id = '" . $_SESSION['SESS_TC_ID'] . "'";
					$resultxxv = mysqli_query($db, $queryxcv) or die(mysqli_error($db));
					session_regenerate_id();
					header('location: admin_training_center.php');
					exit();
		}
		else {
					echo "Error! <br>";
					echo "User ID: {$_SESSION['SESS_USER_ID']}";
		}
	}

?>