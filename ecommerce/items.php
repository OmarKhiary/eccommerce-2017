    <?php
        ob_start();
        session_start();
        $pageTitle = 'Show Items';
        include 'init.php';

        // Check if get request  userID is numeric & Get its integer value

        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval ($_GET['itemid']) : 0;

        // Select all data Depend on this id

        $stmt = $con->prepare("SELECT
                                  items.*,
                                  Categories.Name As catgory_name,
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
                                        admin.UserId = items.Member_ID

                                    WHERE Item_ID = ?");

        // Execute Query

        $stmt ->execute(array($itemid));

        $count = $stmt->rowCount();

      	if ($count > 0) {

      	// Fetch The Data

      	$item = $stmt->fetch();
    ?>

    <h1 class="text-center"><?php echo $item['Name'] ?></h1>
      <div class="container">
        <div class="row">
          <div class="col-md-3">
            <img class="img-responsive img-thumbnail center-block" src="img.png" alt="" />
          </div>
            <div class="col-md-9 item-info">
              <h2><?php echo $item['Name'] ?></h2>
			         <p><?php echo $item['Description'] ?></p>
              <ul class="list-unstyled">
                        <li>
                          <i class="fa fa-calendar  fa-fw"></i>
                          <span>Added Date</span> : <?php echo $item['Add_Date'] ?>
                        </li>
                        <li>
                          <i class="fa fa-envelope-o fa-fw"></i>
                          <span>Price</span> : <?php echo $item['Price'] ?>
                        </li>
                        <li>
                          <i class="fa fa-user fa-fw"></i>
                          <span>Made In</span> : <?php echo $item['Country_Made'] ?>
                        </li>
                        <li>
                          <i class="fa fa-calendar fa-fw"></i>
                          <span>Category</span> : <a href="categories.php?pageid=<?php echo $item['Cat_ID'] ?>"><?php echo $item['catgory_name'] ?></a>
                        </li>
                        <li>
                          <i class="fa fa-tags fa-fw"></i>
                          <span>Added By</span> :  <a href="#"><?php echo $item['Username'] ?></a>
                        </li>
                      </ul>
            </div>
        </div>
        <hr class="custom-hr">
        <?php if(isset($_SESSION['user'])){ ?>
          <!-- Start Add Comment-->
            <div class="row">
              <div class="col-md-offset-3">
                <div class="add-comment">
                  <h3>Add Your Comment</h3>
                  <form action="<?php echo $_SERVER['PHP_SELF'] . '?itemid=' .$item['Item_ID']?>" method="post">
                    <textarea name="comment" required></textarea>
                    <input class="btn btn-primary" type="submit" value="Add Comment" />
                  </form>
                  <?php
                      if ($_SERVER['REQUEST_METHOD'] =='POST'){

                              $comment 	= filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
                              $itemid 	= $item['Item_ID'];
                  						$userid 	= $_SESSION['uid'];

                          if(! empty($comment)) {

                            $stmt = $con->prepare("INSERT INTO
                                    comments(comment, status, comment_date, item_id, user_id)
                                    VALUES(:zcomment, 0, NOW(), :zitemid, :zuserid )");

                                    $stmt->execute(array(

                                      'zcomment' => $comment,
                                      'zitemid' =>$itemid,
                                      'zuserid' => $userid

                                    ));

                                    if($stmt){
                                      echo '<div class="alert alert-success">Comment Added</div>';
                                    }

                          }else{
                                echo '<div class="alert alert-danger">You Must Add Comment</div>';
                              }
                        }
                  ?>
                </div>
              </div>
            </div>
          <!-- End Add Comment-->

    <?php } else {
            echo '<a href="login.php">Login</a> or <a href="login.php">Register</a> To Add Comment';
            }?>

        <hr class="custom-hr" />
            <?php

            // Select all users expcet admin

            $stmt =$con->prepare("SELECT
                                    comments.*,admin.Username AS Member
                                  FROM
                                    comments
                                  INNER JOIN
                                      admin
                                    ON
                                    admin.UserID = comments.user_id
                                    WHERE
                                    item_id = ?
                                    AND
                                    Status = 1
                                    ORDER BY
                                    c_id DESC");

              // execute The statment

              $stmt->execute(array($item['Item_ID']));

              // Assign To variable

              $comments = $stmt-> fetchAll();

            ?>

          <?php foreach ($comments as $comment ) { ?>
            <div class="comment-box">
              <div class="row">
                  <div class="col-sm-2 text-center">
                    <img class="img-responsive img-thumbnail img-circle center-block" src="img.png" alt="" />
                      <?php echo $comment['Member']?>
                  </div>
                  <div class="col-sm-10">
                          <p class="lead">
                            <?php echo $comment['comment'] ?>
                          </p>
                  </div>
              </div>
            </div>
        <hr class="custom-hr" />
          <?php } ?>
      </div>
    <?php
  } else {
    echo '<div class="container">';
      echo '<div class="alert alert-danger">There\'s no Such ID Or This Item Is Waiting Approval</div>';
    echo '</div>';
  }
          include $tpl .'footer.php';
          ob_end_flush();
    ?>
