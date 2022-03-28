<?php

$conn = $pdo->open();

function getCat() {

  global $conn;
  $returnval = "";
  try{
    $stmt = $conn->prepare("SELECT * FROM category");
    $stmt->execute();
    foreach($stmt as $row){
      $returnval .=
       "
        <li>
            <a href='menu.php?category=".$row['id']."'><span class='clickable'>".$row['name']."</span></a>
        </li>
      ";
    }
    return $returnval;
    }
    catch(PDOException $e){
      return "There is some problem in connection: " . $e->getMessage();
    }

    $pdo->close();

}


function getMenu(){
  global $conn;
  $returnval = "";

  try{

    if(isset($_GET['category'])){

      $catid =$_GET['category'];
  		$stmt = $conn->prepare("SELECT * FROM products WHERE cat_id = :catid");
  		$stmt->execute(['catid' => $catid]);
  		$cat = $stmt->fetch();

      $stmt2 = $conn->prepare("SELECT COUNT(*) AS numrows FROM products WHERE cat_id = :catid");
	    $stmt2->execute(['catid' => $catid]);
	    $stmt3 = $stmt2->fetch();
	    $rowcounter = $stmt3['numrows'];
    } else {

	    $stmt = $conn->prepare("SELECT * FROM products");
	    $stmt->execute();

      $stmt2 = $conn->prepare("SELECT COUNT(*) AS numrows FROM products");
	    $stmt2->execute();
	    $stmt3 = $stmt2->fetch();
	    $rowcounter = $stmt3['numrows'];
    }

    foreach ($stmt as $row) {
      if($row['avail']==1){
        $avail = 1;
      }
      else{
        $avail = 0;
      }
      $image = (!empty($row['image'])) ? 'images/'.$row['image'] : 'images/noimage.jpg';


        if($rowcounter < 3){
          if($avail==0){
          $returnval .= "
           <div class='card col-md-6 border-0'>
              <div class='prod-img card-body text-center pb-4'>
                <div class='thumbnail d-block mb-4'>
                  <a href='product.php?product=".$row['id']."'>
                  <img src='".$image."' class='menu-img'>
                  <p><span class='sold-out-overlay-2'>Sold Out</span></p>
                </div>
                  <h5 class='card-title'>".$row['name']."</a></h5>
                  <h6 class='card-subtitle mb-2 text-muted'>RM ".number_format($row['price'], 2)."</h6>
              </div>
            </div>
          ";
          } else{
            $returnval .= "
               <div class='card col-md-6 border-0'>
                  <div class='prod-img card-body text-center pb-4'>
                  <div class='thumbnail d-block mb-4'>
                    <a href='product.php?product=".$row['id']."'>
                    <img src='".$image."' class='menu-img'>
                  </div>
                  <h5 class='card-title'>".$row['name']."</a></h5>
                  <h6 class='card-subtitle mb-2 text-muted'>RM ".number_format($row['price'], 2)."</h6>
                </div>
              </div>
              ";
            }
          } else{
            if($avail==0){
              $returnval .= "
               <div class='card col-md-4 border-0'>
                  <div class='prod-img card-body text-center pb-4'>
                  <div class='thumbnail d-block mb-4'>
                  <a href='product.php?product=".$row['id']."'>
                  <img src='".$image."' class='menu-img'></div>
                  <p><span class='sold-out-overlay-2'>Sold Out</span></p>
                  <h5 class='card-title'>".$row['name']."</a></h5>
                  <h6 class='card-subtitle mb-2 text-muted'>RM ".number_format($row['price'], 2)."</h6>
                </div>
              </div>
              ";
            } else{
              $returnval .= "
               <div class='card col-md-4 border-0'>
                  <div class='prod-img card-body text-center pb-4'>
                  <div class='thumbnail d-block mb-4'>
                  <a href='product.php?product=".$row['id']."'>
                  <img src='".$image."' class='menu-img'></div>
                  <h5 class='card-title'>".$row['name']."</a></h5>
                  <h6 class='card-subtitle mb-2 text-muted'>RM ".number_format($row['price'], 2)."</h6>
                </div>
              </div>
              ";
            }
          }

      }
      return $returnval;
    }
    catch(PDOException $e){
    	return "There is some problem in connection: " . $e->getMessage();
    }
    $pdo->close();
}



function getProductDetails() {
  global $conn;
  $returnval = "";

	$prodid = $_GET['product'];

  try{

    $stmt = $conn->prepare("SELECT *, products.name AS prodname, category.name AS catname, products.id AS prodid FROM products LEFT JOIN category ON category.id=products.cat_id WHERE products.id = :prodid");
    $stmt->execute(['prodid' => $prodid]);
    $product = $stmt->fetch();
    $avail = $product['avail'];
    $image = (!empty($product['image'])) ? 'images/'.$product['image'] : 'images/noimage.jpg';

    $returnval .= "
    <div class='row'>
      <div class=' col-md-6 col-sm-12'><img src='".$image."' class='prod-pic'>
    ";

    if($avail==0){
        $returnval .= "
        <h6 class='unavailable'>Product is currently unavailable.</h6>
        <p><span class='sold-out-overlay'>Sold Out</span></p>
        </div>
        ";
      }
      else {
        $returnval .= " </div>";
      }

      $returnval .= "

      <div class='col-md-6 col-sm-12 mt-3'>
        <h3 class='page-header'>".$product['prodname']."</h3>
        <p>Price: <span class='det-col'>RM ".$product['price']."</span></p>
        <p>Category: <span class='det-col'><a href='menu.php?category='".$product['cat_id'].">".$product['catname']."</a></span></p>
        <p>Description: <span class='det-col'>".$product['description']."</span></p>
      ";

      $returnval .= "
      <form id='productForm'>
        <div class='form-group'>
          <div class='input-val'>
        ";

        if ($avail==0){
          $returnval .=  "
          <span class='input-group-btn'>
            <button type='button' disabled id='minus' class='btn btn-default btn-flat btn-lg'><i class='fa fa-minus'></i></button>
          </span>
          <input type='text' disabled name='quantity' id='quantity' class='qty-form' value='1'>
          <span class='input-group-btn'>
              <button type='button' disabled id='add' class='btn btn-default btn-flat btn-lg'><i class='fa fa-plus'></i>
              </button>
          </span>
          <input type='hidden' value='".$product['prodid']."'; name='id'>
          </div>
          </div>
          <div>
            <button type='submit' disabled class='btn btn-primary btn-lg btn-flat'><i class='fa fa-shopping-cart'></i> Add to Cart</button>
          </div>
          </div>
          ";
        }
        else {
          $returnval .=  "
          <span class='input-group-btn'>
            <button type='button' id='minus' class='btn btn-default btn-flat btn-lg'><i class='fa fa-minus'></i></button>
          </span>
          <input type='text' name='quantity' id='quantity' class='qty-form' value='1'>
          <span class='input-group-btn'>
              <button type='button' id='add' class='btn btn-default btn-flat btn-lg'><i class='fa fa-plus'></i>
              </button>
          </span>
            <input type='hidden' value=".$product['prodid']."; name='id'>
          </div>
          </div>
          <div>
            <button type='submit' class='btn btn-primary btn-lg btn-flat'><i class='fa fa-shopping-cart'></i> Add to Cart</button>
          </div>
          </div>
          ";
        }

        $returnval .=  "
      </form>
    </div>
  ";

  return $returnval;
}
catch(PDOException $e){
  return "There is some problem in connection: " . $e->getMessage();
}

}

function errorMsg() {
  global $conn;
  $returnval = "";

  if(isset($_SESSION['error'])){
    $returnval .= "
      <div class='callout alert-danger text-center'>
        <p>".$_SESSION['error']."</p>
      </div>
    ";
    unset($_SESSION['error']);
  }
  if(isset($_SESSION['success'])){
    $returnval .= "
      <div class='callout alert-success text-center'>
        <p>".$_SESSION['success']."</p>
      </div>
    ";
    unset($_SESSION['success']);
  }
  return $returnval;

}

?>
