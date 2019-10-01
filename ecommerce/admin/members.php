	<?php

	/*
	================================================
	== Manage Members Page
	== You Can Add | Edit | Delete Members From Here
	================================================
	*/
	session_start();
	$pageTitle = 'Members';

		if (isset($_SESSION['Username'])){

		include 'init.php';

		$do = isset($_GET['do']) ? $_GET['do'] : 'Mange';

		// Start Mange Page

	if($do == 'Mange'){ // Mange Page

		$query = '';

		if(isset($_GET['page']) && $_GET['page'] == 'Pending'){
			$query ='AND RegStatus = 0';
		}

	// Select All user except Admin

	$stmt = $con->prepare("SELECT * FROM admin WHERE GroupID !=1 $query ORDER BY UserID DESC");
	$stmt->execute();

	// Assign To Variable

	$rows = $stmt->fetchAll();

	if(!empty($rows)){
		?>
			<h1 class="text-center">Mange Members</h1>
			<div class="container">
				<div class="table-responsive">
				<table class="main-table table  text-center table-bordered">
					<tr>
						<td>ID</td>
						<td>Username</td>
						<td>Email</td>
						<td>Full Name </td>
						<td>Register Date</td>
						<td>Control</td>
						</tr>
					<?php

						foreach($rows as $row){
							echo "<tr>" ;
							echo  "<td>". $row['UserID'] . "</td>" ;
							echo "<td>". $row['Username'] . "</td>" ;
							echo "<td>". $row['Email'] . "</td>"  ;
							echo "<td>". $row['FullName'] . "</td>"  ;
							echo "<td>" . $row['Date'] ."</td>";
							echo "<td>
							<a href='members.php?do=Edit&userid=" . $row['UserID'] . "' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
							<a href='members.php?do=Delete&userid=" . $row['UserID'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete </a>";
							if ($row['RegStatus'] == 0) {
								echo "<a
										href='members.php?do=Activate&userid=" . $row['UserID'] . "'
										class='btn btn-info activate'>
										<i class='fa fa-check'></i> Activate</a>";
								}
							echo "</td>";
							echo "</tr>";
						}
					?>

				</table>
				</div>
				<a href="members.php?do=Add" class="btn btn-primary">
				<i class="fa fa-plus"></i> New Member
				</a>
			</div>
		<?php }else{
			echo'<div class="container">';
				echo'<div class="nive-message">There\'s No Members To Show</div>';
				echo '<a href="members.php?do=Add" class="btn btn-primary">
								<i class="fa fa-plus"></i> New Member
							</a>';
			echo '</div>';
		} ?>

<?php
} elseif($do =='Add' ){// Add Member ?>

							<h1 class="text-center">Add New Member</h1>
	<div class="container">
		<form class="form-horizontal" action="?do=Insert" method="POST">

			<!-- Start Username Field -->
			<div class="form-group form-group-lg">
				<label class="col-sm-2 control-label">Username</label>
				<div class="col-sm-10 col-md-6">
					<input type="text" name="username" class="form-control"  autocomplete="off" required="required" placeholder="Username To Login to Shop" />
				</div>
			</div>
			<!-- End Username Field -->
			<!-- Start Password Field -->
			<div class="form-group form-group-lg">
				<label class="col-sm-2 control-label">Password</label>
				<div class="col-sm-10 col-md-6">
					<input type="password" name="password" class="password form-control" autocomplete="new-password" required="required" placeholder="Password be complex" />
					<i class="show-pass fa fa-eye fa-2x"></i>
				</div>
			</div>
			<!-- End Password Field -->
			<!-- Start Email Field -->
			<div class="form-group form-group-lg">
				<label class="col-sm-2 control-label">Email</label>
				<div class="col-sm-10 col-md-6">
					<input type="email" name="email"  class="form-control" required="required" placeholder="Must Be Valid"  />
				</div>
			</div>
			<!-- End Email Field -->
			<!-- Start Full Name Field -->
			<div class="form-group form-group-lg">
				<label class="col-sm-2 control-label">Full Name</label>
				<div class="col-sm-10 col-md-6">
					<input type="text" name="full"  class="form-control" required="required" placeholder="Will apper in Your Profile"  />
				</div>
			</div>
			<!-- End Full Name Field -->
			<!-- Start Submit Field -->
			<div class="form-group form-group-lg">
				<div class="col-sm-offset-2 col-sm-10">
					<input type="submit" value="Add Member" class="btn btn-primary btn-lg" />
				</div>
			</div>
			<!-- End Submit Field -->
		</form>
	</div>
	<?php
	}elseif($do =='Insert'){ // Insert member Page
			if ($_SERVER['REQUEST_METHOD'] == 'POST'){

			echo "<h1 class = 'text-center'>Insert Member</h1>";
			echo"<div class='container'>";
			//  Get Variabels From The Form
			$user = $_POST['username'];
			$pass = sha1($_POST['password']);
			$email = $_POST['email'];
			$name = $_POST['full'];

			// password trick

			$hashPass = sha1($_POST['password']);

			// Validate The Form

			$formErrors =array();

			if (strlen($user) < 4) {
			$formErrors[] = 'Username Cant Be Less Than <strong>4 Characters</strong>';
			}
			if (strlen($user) > 20) {
			$formErrors[] = 'Username Cant Be More Than <strong>20 Characters</strong>';
			}
			if (empty($user)) {
			$formErrors[] = 'Username Cant Be <strong>Empty</strong>';
			}
			if (empty($pass)) {
			$formErrors[] = 'Password Cant Be <strong>Empty</strong>';
			}
			if (empty($name)) {
			$formErrors[] = 'Full Name Cant Be <strong>Empty</strong>';
			}
			if (empty($email)) {
			$formErrors[] = 'Email Cant Be <strong>Empty</strong>';
			}
			// Loop Into Errors Array And Echo It
			foreach($formErrors as $error) {
			echo '<div class="alert alert-danger">' . $error . '</div>';
			}

			// Check if there is no Error proceed the update operation

			if(empty($formErrors)) {

			// Check if user exist in database

			$check = checkItem("Username","admin",$user);

			if($check==1){

				$theMsg = '<div class="alert alert-danger">Sorry This User Is Exist</div>';

				redirectHome($theMsg, 'back');
			}

					else{

					// Insert User Info  In Database

					$stmt = $con->prepare("INSERT INTO
							admin(Username, Password, Email, FullName,RegStatus, Date  )

							VALUES(:zuser, :zpass, :zmail, :zname, 10, now()) ");

					  $stmt->execute(array(

					  'zuser' => $user,
					  'zpass' =>	$hashPass,
					  'zmail' => $email,
					  'zname' => $name

					  ));


					  //Echo Success Message

					  $theMsg = "<div class='alert alert-success'>" .$stmt->rowCount(). 'Record Inserted</div> ';

					  redirectHome($theMsg, 'back');

					  }

				}

			} else {

			echo "<div class='container'>";

				$theMsg = '<div class="alert alert-danger">Sorry You Cant Browse This Page Directly</div>';

				redirectHome($theMsg);

				echo "</div>";
			}

		}

	elseif($do =='Edit'){

	// Check if get request  userID is numeric & Get its integer value

	$userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval ($_GET['userid']) : 0;

	// Select all data Depend on this id

	$stmt = $con->prepare("SELECT * FROM admin WHERE UserID = ? LIMIT 1");

	// Execute Query

	$stmt ->execute(array($userid));

	// Fetch The Data

	$row = $stmt->fetch();

	// The Row Count

	$count = $stmt->rowCount();




	if($count> 0) { ?>

	<h1 class="text-center">Edit Member</h1>
	<div class="container">
		<form class="form-horizontal" action="?do=Update" method="POST">
			<input type="hidden" name="userid" value="<?php echo $userid ?>" />
			<!-- Start Username Field -->
			<div class="form-group form-group-lg">
				<label class="col-sm-2 control-label">Username</label>
					<div class="col-sm-10 col-md-6">
						<input type="text" name="username" class="form-control" value="<?php echo $row['Username'] ?>" autocomplete="off" required="required" />
					</div>
			</div>
			<!-- End Username Field -->
			<!-- Start Password Field -->
			<div class="form-group form-group-lg">
				<label class="col-sm-2 control-label">Password</label>
				<div class="col-sm-10 col-md-6">
					<input type="hidden" name="oldpassword" value="<?php echo $row['Password'] ?>" />
					<input type="password" name="newpassword" class="form-control" autocomplete="new-password" placeholder="Leave Blank If You Dont Want To Change" />
				</div>
			</div>
			<!-- End Password Field -->
			<!-- Start Email Field -->
			<div class="form-group form-group-lg">
				<label class="col-sm-2 control-label">Email</label>
				<div class="col-sm-10 col-md-6">
					<input type="email" name="email" value="<?php echo $row['Email'] ?>" class="form-control" required="required" />
				</div>
			</div>
			<!-- End Email Field -->
			<!-- Start Full Name Field -->
			<div class="form-group form-group-lg">
				<label class="col-sm-2 control-label">Full Name</label>
				<div class="col-sm-10 col-md-6">
						<input type="text" name="full" value="<?php echo $row['FullName'] ?>" class="form-control" required="required" />
				</div>
			</div>
			<!-- End Full Name Field -->
			<!-- Start Submit Field -->
			<div class="form-group form-group-lg">
				<div class="col-sm-offset-2 col-sm-10">
					<input type="submit" value="Save" class="btn btn-primary btn-lg" />
				</div>
			</div>
			<!-- End Submit Field -->
		</form>
	</div>

	<?php

	} else {

				echo "<div class='container'>";

				$theMsg = '<div class="alert alert-danger">Theres No Such ID</div>';

				redirectHome($theMsg);

				echo "</div>";

	}

	}elseif ($do == 'Update') {

	echo "<h1 class = 'text-center'>Update Member</h1>";
	echo"<div class='container'>";

	if ($_SERVER['REQUEST_METHOD'] == 'POST'){

	//  Get Variabels From The Form
	$id = $_POST['userid'];
	$user = $_POST['username'];
	$email = $_POST['email'];
	$name = $_POST['full'];

	// password trick

	$pass =empty($_POST['newpassword']) ?  $_POST['oldpassword'] : sha1($_POST['newpassword']);

	// Validate The Form

	$formErrors =array();

	if (strlen($user) < 4) {
	$formErrors[] = 'Username Cant Be Less Than <strong>4 Characters</strong>';
	}
	if (strlen($user) > 20) {
	$formErrors[] = 'Username Cant Be More Than <strong>20 Characters</strong>';
	}
	if (empty($user)) {
	$formErrors[] = 'Username Cant Be <strong>Empty</strong>';
	}
	if (empty($name)) {
	$formErrors[] = 'Full Name Cant Be <strong>Empty</strong>';
	}
	if (empty($email)) {
	$formErrors[] = 'Email Cant Be <strong>Empty</strong>';
	}
	// Loop Into Errors Array And Echo It
	foreach($formErrors as $error) {
	echo '<div class="alert alert-danger">' . $error . '</div>';
	}

	// Check uf ther is no Error proceed the updqte operation

	if(empty($formErrors)){
					$stmt2 = $con->prepare("SELECT
						 												*
																		FROM
																			admin
																		WHERE
																		Username = ?
																	 	AND
																		UserID != ?");

					$stmt2->execute(array($user, $id));

					$count = $stmt2->rowCount();

					if($count == 1){

						//Echo Success Message
						$theMsg = '<div class="alert alert-danger">Sorry This User Is Exist</div>';

							redirectHome($theMsg, 'back');

					}
				} else{
					//Update The Database with This Info

					$stmt = $con->prepare("UPDATE admin SET Username = ?, Email = ?, FullName = ?, Password = ? WHERE UserID = ?");

					$stmt->execute(array($user, $email, $name, $pass, $id));

					//Echo Success Message
								$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated</div>';

								redirectHome($theMsg, 'back');
				}


	} else {

				$theMsg = '<div class="alert alert-danger">Sorry You Cant Browse This Page Directly</div>';

				redirectHome($theMsg);
	}

	echo "</div>";



	}elseif($do =='Delete' ){ // Delete The Member Page

		echo"<h1 class='text-center'>Delete Member</h1>";
		echo"<div class'container'>";
	// Check if get request  userID is numeric & Get its intger value

	$userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval ($_GET['userid']) : 0;

	// Select all data Depend on this id

	$check =checkItem('userid','admin',$userid);

	//  If there is such Id Show the form

	if($check > 0){

		$stmt = $con->prepare("DELETE FROM admin WHERE UserID = :zuser ");
		$stmt ->bindParam(":zuser", $userid);
		$stmt->execute();

			$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Deleted</div>';

			redirectHome($theMsg, 'back');

	}else{

			$theMsg = '<div class="alert alert-danger">This ID is Not Exist</div>';

			redirectHome($theMsg,'back');
		}
	}elseif ($do =='Activate') { // Activate Member Page

				echo"<h1 class='text-center'>Activate Member</h1>";
				echo"<div class'container'>";
			// Check if get request  userID is numeric & Get its intger value

			$userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval ($_GET['userid']) : 0;

			// Select all data Depend on this id

			$check =checkItem('userid','admin',$userid);

			//  If there is such Id Show the form

			if($check > 0){

				$stmt = $con->prepare("UPDATE admin SET RegStatus = 1 WHERE UserId =?");

				$stmt->execute(array($userid));

					$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated</div>';

					redirectHome($theMsg, 'back');

			}else{

					$theMsg = '<div class="alert alert-danger">This ID is Not Exist</div>';

					redirectHome($theMsg);
				}
	}

		include $tpl .'footer.php';

	} else {

		header('Location:index.php');

	exit();

	}
?>
