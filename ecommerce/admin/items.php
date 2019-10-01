<?php
	/*
	================================================
	== Template Page
	================================================
	*/
	ob_start(); // Output Buffering Start

	session_start();

	$pageTitle = '';

	if (isset($_SESSION['Username'])) {

		include 'init.php';

		$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    		if ($do == 'Manage') {  // Mange Page

      	$stmt = $con->prepare("SELECT
                            items.*,
                            categories.Name AS category_name,
                             admin.Username
                             FROM
                                items
                              INNER JOIN
                                categories
                              ON
                                    categories.ID = items.Cat_ID

                              INNER JOIN
                                    admin
                                ON
                                    admin.UserID = items.Member_ID

																ORDER BY

																Item_ID DESC");

          // Exee The Statement

      	$stmt->execute();

      	// Assign To Variable

      	$items = $stmt->fetchAll();

				if(!empty($items)){

      	?>
      	<h1 class="text-center">Mange Items</h1>
      	<div class="container">
	      	<div class="table-responsive">
		      	<table class="main-table table  text-center table-bordered">
			      	<tr>
				      	<td>#ID</td>
				      	<td>Name</td>
				      	<td>Description</td>
				      	<td>Price</td>
				      	<td>Adding Date</td>
				        <td>Categorey</td>
				        <td>Username</td>
				      	<td>Control</td>
			      	</tr>
			      	<?php

			      	foreach($items as $item){
						      	echo "<tr>" ;
						      	echo  "<td>". $item['Item_ID'] . "</td>" ;
						      	echo "<td>". $item['Name'] . "</td>" ;
						      	echo "<td>". $item['Description'] . "</td>"  ;
						      	echo "<td>". $item['Price'] . "</td>"  ;
						      	echo "<td>" . $item['Add_Date'] ."</td>";
						        echo "<td>" . $item['category_name'] ."</td>";
						        echo "<td>" . $item['Username'] ."</td>";
						      	echo "<td>
						        	<a href='items.php?do=Edit&itemid=" . $item['Item_ID'] . "' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
						        	<a href='items.php?do=Delete&itemid=" . $item['Item_ID'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete </a>";
											if ($item['Approve'] == 0) {
												echo "<a
														href='items.php?do=Approve&itemid=" . $item['Item_ID'] . "'
														class='btn btn-info activate'>
														<i class='fa fa-check'></i> Approve</a>";
												}
						      	echo "</td>";
						      	echo "</tr>";
						      	}
			      	?>

		      	</table>
	      	</div>
      	<a href="items.php?do=Add" class="btn btn-primary">
      		<i class="fa fa-plus"></i> New Item
      	</a>
      	</div>
      	<?php }else{
					echo '<div class="container">';
						echo '<div class="nice-message">There\'s No Items To Show</div>';
						echo '<a href="items.php?do=Add" class="btn btn-sm btn-primary">
								<i class="fa fa-plus"></i> New Item
							</a>';
					echo '</div>';
				} ?>

	<?php
		} elseif ($do == 'Add') {?>

      <h1 class="text-center">Add New Item</h1>
      <div class="container">
        <form class="form-horizontal" action="?do=Insert" method="POST">
          <!-- Start Name Field -->
          <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Name</label>
            <div class="col-sm-10 col-md-6">
              <input
                    type="text"
                    name="name"
                    class="form-control"
                    required="required"
                    placeholder="Name Of The Item" />
            </div>
          </div>
          <!-- End Name Field -->
          <!-- Start Description Field -->
          <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Description</label>
            <div class="col-sm-10 col-md-6">
              <input
                    type="text"
                    name="description"
                    class="form-control"
                    required="required"
                    placeholder="Description Of The Item" />
            </div>
          </div>
          <!-- End Description Field -->

          <!-- Start Price Field -->
          <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Price</label>
            <div class="col-sm-10 col-md-6">
              <input
                    type="text"
                    name="price"
                    class="form-control"
                    required="required"
                    placeholder="Price Of The Item" />
            </div>
          </div>
          <!-- End Price Field -->
          <!-- Start Countery Field -->
          <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Countery</label>
            <div class="col-sm-10 col-md-6">
              <input
                    type="text"
                    name="countery"
                    class="form-control"
                    required="required"
                    placeholder="Countery Of The Item" />
            </div>
          </div>
          <!-- End Price Field -->
          <!-- Start Status Field -->
          <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Status</label>
            <div class="col-sm-10 col-md-6">
                <select name="status">
                  <option value="0">...</option>
                  <option value="1">New</option>
                  <option value="2">Like New</option>
                  <option value="3">Used</option>
                  <option value="4">Very Old</option>
                </select>
            </div>
          </div>
          <!-- End Status Field -->
          <!-- Start Members Field -->
          <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Member</label>
            <div class="col-sm-10 col-md-6">
                <select name="member">
                  <option value="0">...</option>
                  <?php
                  $stmt =$con->prepare('SELECT * FROM admin');
                  $stmt->execute();
                  $users = $stmt->fetchAll();
                  foreach ($users as $user) {
                  echo "<option value='" . $user['UserID'] . "'>" . $user['Username'] . "</option>";
                  }
                  ?>
                </select>
            </div>
          </div>
          <!-- End Members Field -->
          <!-- Start Categories Field -->
          <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Category</label>
            <div class="col-sm-10 col-md-6">
                <select name="categorey">
                  <option value="0">...</option>
                  <?php
                  $stmt2 =$con->prepare('SELECT * FROM categories');
                  $stmt2->execute();
                  $cats = $stmt2->fetchAll();
                  foreach ($cats as $cat) {
                  echo "<option value='" . $cat['ID'] . "'>" . $cat['Name'] . "</option>";
                  }
                  ?>
                </select>
            </div>
          </div>
          <!-- End Categories Field -->

          <!-- Start Submit Field -->
          <div class="form-group form-group-lg">
            <div class="col-sm-offset-2 col-sm-10">
              <input type="submit" value="Add Item" class="btn btn-primary btn-sm" />
            </div>
          </div>
          <!-- End Submit Field -->
        </form>
      </div>

    <?php



		} elseif ($do == 'Insert') {
      if ($_SERVER['REQUEST_METHOD'] == 'POST'){

      echo "<h1 class = 'text-center'>Insert Item</h1>";
      echo"<div class='container'>";
      //  Get Variabels From The Form
      $name = $_POST['name'];
      $desc = $_POST['description'];
      $price = $_POST['price'];
      $countery = $_POST['countery'];
      $status = $_POST['status'];
      $member = $_POST['member'];
      $cat = $_POST['categorey'];

      // Validate The Form

      $formErrors =array();

      if (empty($name)) {
      $formErrors[] = 'Name Can not be <strong>Empty</strong>';
      }
      if (empty($desc)) {
      $formErrors[] = 'Description Can not be <strong>Empty</strong>';
      }
      if (empty($price)) {
      $formErrors[] = 'Price Can not be <strong>Empty</strong>';
      }
      if (empty($countery)) {
      $formErrors[] = 'Countery Can not be  <strong>Empty</strong>';
      }
      if ($status == 0) {
      $formErrors[] = 'You Must Choose<strong>Status</strong>';
      }
      if ($member == 0) {
      $formErrors[] = 'You Must Choose<strong>Member</strong>';
      }
      if ($cat == 0) {
      $formErrors[] = 'You Must Choose<strong>Categorey</strong>';
      }



      // Loop Into Errors Array And Echo It
      foreach($formErrors as $error) {
      echo '<div class="alert alert-danger">' . $error . '</div>';
      }

      // Check if there is no Error proceed the update operation

      if(empty($formErrors)) {

          $stmt = $con->prepare("INSERT INTO
              items(Name, Description, Price, Country_Made,
                 Status, Image, Add_Date, Rating, Cat_ID, Member_ID  )

              VALUES(:zname, :zdesc, :zprice, :zcountery,
                 :zstatus,'', now(),'1',:zcat, :zmember) ");

            $stmt->execute(array(

            'zname' => $name,
            'zdesc' =>	$desc,
            'zprice' => $price,
            'zcountery' => $countery,
            'zstatus' =>$status,
            'zcat' =>$cat,
            'zmember' =>$member,


            ));


            //Echo Success Message

            $theMsg = "<div class='alert alert-success'>" .$stmt->rowCount(). 'Record Inserted</div> ';

            redirectHome($theMsg);

        }

      } else {

      echo "<div class='container'>";

        $theMsg = '<div class="alert alert-danger">Sorry You Cant Browse This Page Directly</div>';

        redirectHome($theMsg,'back');

        echo "</div>";
      }


		} elseif ($do == 'Edit') {

      // Check if get request  userID is numeric & Get its integer value

    	$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval ($_GET['itemid']) : 0;

    	// Select all data Depend on this id

    	$stmt = $con->prepare("SELECT * FROM items WHERE Item_ID = ?");

    	// Execute Query

    	$stmt ->execute(array($itemid));

    	// Fetch The Data

    	$item = $stmt->fetch();

    	// The Row Count

    	$count = $stmt->rowCount();




    	if($count> 0) { ?>

        <h1 class="text-center">Edit Item</h1>
				<div class="container">
					<form class="form-horizontal" action="?do=Update" method="POST">
              <input type="hidden" name="itemid" value="<?php echo $itemid ?>" />
            <!-- Start Name Field -->
            <div class="form-group form-group-lg">
              <label class="col-sm-2 control-label">Name</label>
              <div class="col-sm-10 col-md-6">
                <input
                      type="text"
                      name="name"
                      class="form-control"
                      required="required"
                      placeholder="Name Of The Item"
                      value="<?php echo $item['Name'] ?>"/>
              </div>
            </div>
            <!-- End Name Field -->
            <!-- Start Description Field -->
            <div class="form-group form-group-lg">
              <label class="col-sm-2 control-label">Description</label>
              <div class="col-sm-10 col-md-6">
                <input
                      type="text"
                      name="description"
                      class="form-control"
                      required="required"
                      placeholder="Description Of The Item"
                      value="<?php echo $item['Description'] ?>"/>
              </div>
            </div>
            <!-- End Description Field -->

            <!-- Start Price Field -->
            <div class="form-group form-group-lg">
              <label class="col-sm-2 control-label">Price</label>
              <div class="col-sm-10 col-md-6">
                <input
                      type="text"
                      name="price"
                      class="form-control"
                      required="required"
                      placeholder="Price Of The Item"
                      value="<?php echo $item['Price'] ?>"/>
              </div>
            </div>
            <!-- End Price Field -->
            <!-- Start Countery Field -->
            <div class="form-group form-group-lg">
              <label class="col-sm-2 control-label">Countery</label>
              <div class="col-sm-10 col-md-6">
                <input
                      type="text"
                      name="countery"
                      class="form-control"
                      required="required"
                      placeholder="Countery Of The Item"
                      value="<?php echo $item['Country_Made'] ?>"/>
              </div>
            </div>
            <!-- End Price Field -->
            <!-- Start Status Field -->
            <div class="form-group form-group-lg">
              <label class="col-sm-2 control-label">Status</label>
              <div class="col-sm-10 col-md-6">
                  <select name="status">
                    <option value="1" <?php if ($item['Status'] == 1){echo 'selected';} ?>>New</option>
                    <option value="2" <?php if ($item['Status'] == 2){echo 'selected';} ?>>Like New</option>
                    <option value="3" <?php if ($item['Status'] == 3){echo 'selected';} ?>>Used</option>
                    <option value="4" <?php if ($item['Status'] == 4){echo 'selected';} ?>>Very Old</option>
                  </select>
              </div>
            </div>
            <!-- End Status Field -->
            <!-- Start Members Field -->
            <div class="form-group form-group-lg">
              <label class="col-sm-2 control-label">Member</label>
              <div class="col-sm-10 col-md-6">
                  <select name="member">
                    <?php
                    $stmt =$con->prepare('SELECT * FROM admin');
                    $stmt->execute();
                    $users = $stmt->fetchAll();
                    foreach ($users as $user) {
                      echo "<option value='" . $user['UserID'] . "'";
  											if ($item['Member_ID'] == $user['UserID']) { echo 'selected'; }
  											echo ">" . $user['Username'] . "</option>";
                    }
                    ?>
                  </select>
              </div>
            </div>
            <!-- End Members Field -->
            <!-- Start Categories Field -->
            <div class="form-group form-group-lg">
              <label class="col-sm-2 control-label">Category</label>
              <div class="col-sm-10 col-md-6">
                  <select name="categorey">
                    <?php
                    $stmt2 =$con->prepare('SELECT * FROM categories');
                    $stmt2->execute();
                    $cats = $stmt2->fetchAll();
                    foreach ($cats as $cat) {
                      echo "<option value='" . $cat['ID'] . "'";
    											if ($item['Cat_ID'] == $cat['ID']) { echo 'selected'; }
    											echo ">" . $cat['Name'] . "</option>";
                    }
                    ?>
                  </select>
              </div>
            </div>
            <!-- End Categories Field -->

            <!-- Start Submit Field -->
            <div class="form-group form-group-lg">
              <div class="col-sm-offset-2 col-sm-10">
                <input type="submit" value="Save Item" class="btn btn-primary btn-sm" />
              </div>
            </div>
            <!-- End Submit Field -->
          </form>
				</div>

				<?php
				// Select All Users Except Admin
				$stmt = $con->prepare("SELECT
											comments.*, admin.Username AS Member
										FROM
											comments
										INNER JOIN
											admin
										ON
											admin.UserID = comments.user_id
										WHERE item_id = ?");
				// Execute The Statement
				$stmt->execute(array($itemid));
				// Assign To Variable
				$rows = $stmt->fetchAll();
				if (! empty($rows)) {

				?>
				<h1 class="text-center">Manage [ <?php echo $item['Name'] ?> ] Comments</h1>
				<div class="container">
					<div class="table-responsive">
						<table class="main-table text-center table table-bordered">
							<tr>
								<td>Comment</td>
								<td>User Name</td>
								<td>Added Date</td>
								<td>Control</td>
							</tr>
							<?php
								foreach($rows as $row) {
									echo "<tr>";
										echo "<td>" . $row['comment'] . "</td>";
										echo "<td>" . $row['Member'] . "</td>";
										echo "<td>" . $row['comment_date'] ."</td>";
										echo "<td>
											<a href='comments.php?do=Edit&comid=" . $row['c_id'] . "' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
											<a href='comments.php?do=Delete&comid=" . $row['c_id'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete </a>";
											if ($row['status'] == 0) {
												echo "<a href='comments.php?do=Approve&comid="
														 . $row['c_id'] . "'
														class='btn btn-info activate'>
														<i class='fa fa-check'></i> Approve</a>";
											}
										echo "</td>";
									echo "</tr>";
								}
							?>
							<tr>
						</table>
					</div>
					<?php } ?>
				</div>

				</div>

      <?php

    	} else {

    				echo "<div class='container'>";

    				$theMsg = '<div class="alert alert-danger">Theres No Such ID</div>';

    				redirectHome($theMsg);

    				echo "</div>";

    	}

		} elseif ($do == 'Update') {

      echo "<h1 class = 'text-center'>Update Item </h1>";
    	echo"<div class='container'>";

    	if ($_SERVER['REQUEST_METHOD'] == 'POST'){

    	//  Get Variabels From The Form
    	$id = $_POST['itemid'];
    	$name = $_POST['name'];
    	$desc = $_POST['description'];
    	$price = $_POST['price'];
      $countery = $_POST['countery'];
      $status = $_POST['status'];
      $member = $_POST['member'];
      $cat = $_POST['categorey'];

    	// Validate The Form

      $formErrors =array();

      if (empty($name)) {
      $formErrors[] = 'Name Can not be <strong>Empty</strong>';
      }
      if (empty($desc)) {
      $formErrors[] = 'Description Can not be <strong>Empty</strong>';
      }
      if (empty($price)) {
      $formErrors[] = 'Price Can not be <strong>Empty</strong>';
      }
      if (empty($countery)) {
      $formErrors[] = 'Countery Can not be  <strong>Empty</strong>';
      }
      if ($status == 0) {
      $formErrors[] = 'You Must Choose<strong>Status</strong>';
      }
      if ($member == 0) {
      $formErrors[] = 'You Must Choose<strong>Member</strong>';
      }
      if ($cat == 0) {
      $formErrors[] = 'You Must Choose<strong>Categorey</strong>';
      }
    	// Loop Into Errors Array And Echo It
    	foreach($formErrors as $error) {
    	echo '<div class="alert alert-danger">' . $error . '</div>';
    	}

    	// Check uf ther is no Error proceed the updqte operation

    	if(empty($formErrors)){

    	//Update The Database with This Info

    	$stmt = $con->prepare("UPDATE
                                    items
                                SET
                                    Name = ?,
                                     Description = ?,
                                     Price = ?,
                                     Country_Made = ?,
                                     Status = ?,
                                     Cat_ID = ?,
                                     Member_ID = ?
                                 WHERE
                                      Item_ID = ?");

    	$stmt->execute(array($name, $desc, $price, $countery, $status, $cat, $member, $id));

    	//Echo Success Message
    				$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated</div>';

    				redirectHome($theMsg, 'back');
    	}


    	} else {

    				$theMsg = '<div class="alert alert-danger">Sorry You Cant Browse This Page Directly</div>';

    				redirectHome($theMsg);
    	}

    	echo "</div>";



		} elseif ($do == 'Delete') {

      echo"<h1 class='text-center'>Delete Item</h1>";
      echo"<div class'container'>";
    // Check if get request  ItemID is numeric & Get its intger value

    $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval ($_GET['itemid']) : 0;

    // Select all data Depend on this id

    $check =checkItem('Item_ID','items',$itemid);

    //  If there is such Id Show the form

    if($check > 0){

      $stmt = $con->prepare("DELETE FROM items WHERE Item_ID = :zid ");
      $stmt ->bindParam(":zid", $itemid);
      $stmt->execute();

        $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Deleted</div>';

        redirectHome($theMsg, 'back');

    }else{

        $theMsg = '<div class="alert alert-danger">This ID is Not Exist</div>';

        redirectHome($theMsg);
      }
		} elseif ($do == 'Approve') {

			echo"<h1 class='text-center'>Approve Item</h1>";
			echo"<div class'container'>";
		// Check if get request  userID is numeric & Get its intger value

		$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval ($_GET['itemid']) : 0;

		// Select all data Depend on this id

		$check =checkItem('Item_ID','items',$itemid);

		//  If there is such Id Show the form

		if($check > 0){

			$stmt = $con->prepare("UPDATE items SET Approve = 1 WHERE Item_ID =?");

			$stmt->execute(array($itemid));

				$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated</div>';

				redirectHome($theMsg, 'back');

		}else{

				$theMsg = '<div class="alert alert-danger">This ID is Not Exist</div>';

				redirectHome($theMsg);
			}

		}
		include $tpl . 'footer.php';
	} else {
		header('Location: index.php');
		exit();
	}
	ob_end_flush(); // Release The Output
?>
