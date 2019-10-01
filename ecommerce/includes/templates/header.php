<!DOCTYPE html>

        <html lang="en">
        <head>
            <meta charset="utf-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title><?php getTitle()?></title>
               <link rel="stylesheet" href ="<?php echo $css?>bootstrap.css">
               <link rel="stylesheet" href = "<?php echo $css?>backend.css">
               <link rel="stylesheet" href = "<?php echo $css?>font-awesome.min.css">
               <link rel="stylesheet" href = "<?php echo $css?>jquery-ui.css">
               <link rel="stylesheet" href = "<?php echo $css?>jquery.selectBoxIt.css">

               <link rel = 'stylesheet'   href='https://fonts.googleapis.com/css?family=ABeeZee'>
            <!--[if lt IE 9]>
               <script src="layout/js/html5shiv.min.js"></script>
               <script src="layout/js/respond.min.js"></script>
             <![endif]-->

        </head>

        <body>
        <div class="upper-bar">
          <div class="container">
            <?php
              if (isset($_SESSION['user'])){?>
                <img class="my-image img-thumbnial img-circle" src="img.png" alt=" " />
                <div class="btn-group my-info">
                  <span class="btn dorpdwon-toggle" data-toggle="dropdown">
                      <?php echo $sessionUser ?>
                      <span class="caret"></span>
                    </span>
                    <ul class="dropdown-menu">
                      <li><a href="Profile.php">My Profile</a></li>
                      <li><a href="newad.php">New Item</a></li>
                      <li><a href="Profile.php#my-ads">My Items</a></li>
                      <li><a href="logout.php">Logout</a></li>
                    </ul>
                </div>

                <?php
                  }else{
                  ?>
                  <a href="login.php">
                  <span class="pull-right">Login / Signup</span>
                  </a>
                <?php } ?>

          </div>
        </div>
          <nav class="navbar navbar-inverse">
            <div class="container">
              <div class="navbar-header">
                <button type="button" class="navbar-toggle app-nav" data-toggle="collapse" data-target="#app-nav" aria-expanded="false">
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">Homepage</a>
              </div>
              <div class="collapse navbar-collapse" id="app-nav">
                <ul class="nav navbar-nav navbar-right">
                  <?php
                  foreach (getcat() as $cat) {
                    echo '<li>
                    <a href="categories.php?pageid=' .$cat['ID'] . '&pagename=' . '">' . $cat['Name']. '</a>
                    </li>';

                  }
                  ?>
                </ul>
              </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
          </nav>
