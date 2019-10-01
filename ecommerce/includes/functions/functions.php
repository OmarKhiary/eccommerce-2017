<?php


/*
** Get All Function v2.0
** Function To Get All Records From Any Database Table
*/

function getAllfrom($field, $table, $where = NULL, $and )






/*
** Get leatest Recoreds Function v1.0
** Function To Get Categories From Databasse[Users, Items,Comments]
*/

 function getCat(){
   global $con;

   $getCat = $con->prepare("SELECT * FROM categories ORDER BY ID DESC");

   $getCat->execute();

   $cats = $getCat->fetchAll();

   return $cats;
 }

 /*
 ** Get Items Function v2.0
 ** Function To Get items From Databasse
 */

  function getItems($where, $value, $approve = NULL){

    global $con;

    if ($approve == NULL){

      $sql = 'AND Approve = 1';

    } else{
      $sql = NULL ;
    }

    $getItems = $con->prepare("SELECT * FROM items WHERE $where = ? $sql ORDER BY Item_ID DESC");

    $getItems->execute(array($value));

    $items = $getItems->fetchAll();

    return $items;
  }


  /*
  ** Chechk if user Is Not Activated
  ** Function To Check The Regstatus Of The User
  */
  function checkUserStatus($user){

    global $con;

    $stmt = $con->prepare("SELECT
                               Username, Regstatus
                          FROM
                                admin
                          WHERE
                                 Username = ?
                          AND
                                Regstatus= 0");

    $stmt-> execute(array($user));

    $status = $stmt->rowCount();

    return $status;


    }


	/*
	** Get All Function v2.0
	** Function To Get All Records From Any Database Table
	*/

        function getTitle(){

            global $pageTitle;

            if(isset($pageTitle)){

                echo $pageTitle;
            } else {

                echo 'Default';
            }
        }

	/*
	** Home Redirect Function v2.0
	** This Function Accept Parameters
	** $theMsg = Echo The Message [ Error | Success | Warning ]
	** $url = The Link You Want To Redirect To
	** $seconds = Seconds Before Redirecting
	*/


	function redirectHome($theMsg,$url=null,  $seconds = 3){

		if($url === null){

			$url ='index.php';


			$link = 'Homepage';

		} else {

			if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '') {

				$url = $_SERVER['HTTP_REFERER'];

				$link = 'Previous Page';

			} else {

				$url = 'index.php';

				$link = 'Homepage';

			}

		}

		echo $theMsg;

		echo "<div class='alert alert-info'>You Will Be Redirected to $link After $seconds Seconds.</div>";

		header("refresh:$seconds;url=$url");
		exit();
	}


	/*
	** Check Items Function v1.0
	** Function to Check Item In Database [ Function Accept Parameters ]
	** $select = The Item To Select [ Example: user, item, category ]
	** $from = The Table To Select From [ Example: users, items, categories ]
	** $value = The Value Of Select [ Example: Osama, Box, Electronics ]
	*/

function checkItem($select, $from, $value) {

		global $con;

		$statement = $con->prepare("SELECT $select FROM $from WHERE $select = ?");

		$statement->execute(array($value));

		$count = $statement->rowCount();

		return $count;

	}

  /*
  ** Count number Of Itmes Function v1.0
  ** Function to count number of items rows
  **
  **
  */

  Function countItems($item,$table){
    global $con;

    $stmt2 =$con->prepare("SELECT COUNT($item) FROM $table");

    $stmt2->execute();

    return $stmt2->fetchColumn();

  }

  /*
  ** Get leatest Recoreds Function v1.0
  ** Function To Get latest Items From Databasse[Users, Items,Comments]
  ** $select =Field To Select
  ** $table = The table to choose From
  ** $limit = Number Of Records
  */

   function getLatest($select,$table, $order, $limit =5 ){
     global $con;

     $getStmt = $con->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit ");

     $getStmt->execute();

     $rows = $getStmt->fetchAll();

     return $rows;
   }
