    <?php

    /*
    ================================================
    == Manage Comments Page
    == You Can Edit | Delete | Approve Members From Here
    ================================================
    */
    session_start();
    $pageTitle = 'Comments';

      if (isset($_SESSION['Username'])){

      include 'init.php';

      $do = isset($_GET['do']) ? $_GET['do'] : 'Mange';

      // Start Mange Page

    if($do == 'Mange'){ // Mange Page


    // Select All user except Admin

    $stmt = $con->prepare("SELECT
                                  comments.*, items.Name AS Item_Name,admin.Username AS Member
                                 FROM
                                  comments
                                 INNER JOIN
                                  items
                                  ON
                                    items.Item_ID = comments.item_id
                                  INNER JOIN
                                    admin
                                  ON
                                    admin.UserID = comments.user_id
                                  ORDER BY
                                  c_id DESC
                                    
                                 ");
    $stmt->execute();

    // Assign To Variable

    $comments = $stmt->fetchAll();

    if(!empty($comments)){

    ?>

    <h1 class="text-center">Mange Comments</h1>
    <div class="container">
      <div class="table-responsive">
        <table class="main-table table  text-center table-bordered">
        <tr>
          <td>ID</td>
          <td>Comment</td>
          <td>Item Name</td>
          <td>User Name </td>
          <td>Added Date</td>
          <td>Control</td>
        </tr>
        <?php

          foreach($comments as $comment){
          echo "<tr>" ;
          echo  "<td>". $comment['c_id'] . "</td>" ;
          echo "<td>". $comment['comment'] . "</td>" ;
          echo "<td>". $comment['Item_Name'] . "</td>"  ;
          echo "<td>". $comment['Member'] . "</td>"  ;
          echo "<td>" . $comment['comment_date'] ."</td>";
          echo "<td>
          <a href='comments.php?do=Edit&comid=" . $comment['c_id'] . "' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
          <a href='comments.php?do=Delete&comid=" . $comment['c_id'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete </a>";
          if ($comment['status'] == 0) {
            echo "<a
                href='comments.php?do=Approve&comid=" . $comment['c_id'] . "'
                class='btn btn-info activate'>
                <i class='fa fa-check'></i> Approve</a>";
            }
          echo "</td>";
          echo "</tr>";
          }
          ?>

        </table>
      </div>
    </div>
    <?php }else {
      echo '<div class="container">';
        echo '<div class="nice-message">There\'s No Comments To Show</div>';
      echo '</div>';
    } ?>

    <?php

      }elseif($do =='Edit'){

    // Check if get request  comid is numeric & Get its integer value

    $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval ($_GET['comid']) : 0;

    // Select all data Depend on this id

    $stmt = $con->prepare("SELECT * FROM comments WHERE c_id = ? ");

    // Execute Query

    $stmt ->execute(array($comid));

    // Fetch The Data

    $comment = $stmt->fetch();

    // The Row Count

    $count = $stmt->rowCount();




    if($count> 0) { ?>

    <h1 class="text-center">Edit Comments</h1>
    <div class="container">
    <form class="form-horizontal" action="?do=Update" method="POST">
    <input type="hidden" name="comid" value="<?php echo $comid ?>" />
    <!-- Start Comment Field -->
    <div class="form-group form-group-lg">
    <label class="col-sm-2 control-label">Comment</label>
    <div class="col-sm-10 col-md-6">
    <textarea class="form-control" name="comment"><?php echo $comment['comment'] ?></textarea>
    </div>
    <!-- End Comment Field -->

    <!-- Start Submit Field -->
    <div class="form-group form-group-lg">
    <div class="col-sm-offset-2 col-sm-10">
    <input type="submit" value="Save" class="btn btn-primary btn-sm" style="margin-left: 10px;margin-top: 5px;" />
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

    echo "<h1 class = 'text-center'>Update Comment</h1>";
    echo"<div class='container'>";

    if ($_SERVER['REQUEST_METHOD'] == 'POST'){

    //  Get Variabels From The Form

    $comid    = $_POST['comid'];
    $comment  = $_POST['comment'];




    //Update The Database with This Info

    $stmt = $con->prepare("UPDATE comments SET comment = ? WHERE c_id = ?");

    $stmt->execute(array($comment, $comid));

    //Echo Success Message
          $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated</div>';

          redirectHome($theMsg, 'back');



    } else {

          $theMsg = '<div class="alert alert-danger">Sorry You Cant Browse This Page Directly</div>';

          redirectHome($theMsg);
    }

    echo "</div>";



  }elseif($do =='Delete' ){ // Delete comments

      echo"<h1 class='text-center'>Delete Comment</h1>";
      echo"<div class'container'>";
    // Check if get request  userID is numeric & Get its intger value

    $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval ($_GET['comid']) : 0;

    // Select all data Depend on this id

    $check =checkItem('c_id','comments',$comid);

    //  If there is such Id Show the form

    if($check > 0){

      $stmt = $con->prepare("DELETE FROM comments WHERE c_id = :zcom ");
      $stmt ->bindParam(":zcom", $comid);
      $stmt->execute();

        $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Deleted</div>';

        redirectHome($theMsg, 'back');

    }else{

        $theMsg = '<div class="alert alert-danger">This ID is Not Exist</div>';

        redirectHome($theMsg);
      }
    }elseif ($do =='Approve') { // Approve Comment

          echo"<h1 class='text-center'>Approve Comment</h1>";
          echo"<div class'container'>";
        // Check if get request  userID is numeric & Get its intger value

        $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval ($_GET['comid']) : 0;

        // Select all data Depend on this id

        $check =checkItem('c_id','comments',$comid);

        //  If there is such Id Show the form

        if($check > 0){

          $stmt = $con->prepare("UPDATE comments SET Status = 1 WHERE c_id =?");

          $stmt->execute(array($comid));

            $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Approved</div>';

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
