<?php
  session_start();
?>
 
<!DOCTYPE html>
<html lang="en">

<head>
	<title>Cart</title>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-T8Gy5hrqNKT+hzMclPo118YTQO6cYprQmhrYwIiQ/3axmI1hQomh7Ud2hPOy8SP1" crossorigin="anonymous">

<style>
	.navbar a
	{
		color: #000;
	}
</style>
</head>

<body>
<nav class="navbar navbar-expand-sm bg-light">
	<div class="navbar-header">
      <a class="navbar-brand">Bowl[product page]</a>
    </div>
  <ul class="navbar-nav ml-auto">
  <!--  <li class="nav-item">
      <a class="nav-link" href="index.php">Product</a>
    </li>
  -->
    <li class="nav-item">      
      <a class="nav-link active" href="cart.php">Checkout <i class="fa fa-shopping-cart"></i> <span id="cart-item" class="badge badge-danger"></span></a>
    </li>
  </ul>
</nav>

<div class="container">
  <div class="row justify-content-center">
    <div class="col-lg-10">    
      <div style="display:<?php if(isset($_SESSION['showAlert'])){echo $_SESSION['showAlert'];}else { echo 'none'; } unset($_SESSION['showAlert']); ?>" class="alert alert-success alert-dismissible mt-3">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong><?php if (isset($_SESSION['message'])){echo $_SESSION['message'];} unset($_SESSION['showAlert']); ?></strong> 
      </div>
      <div class="table-responsive mt-2">
        <table class="table table-bordered table-striped text-center">
          <thead>
          <tr>
            <td colspan="7">
              <h4 class="text-center text-info m-0"> Products in your cart!</h4>
            </td>
          </tr>          
          <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Product</th>
            <th>Price</th>
            <th>Quantity</th> 
            <th>Total Price</th>
            <th>
              <?php  
               $url = $_SERVER['REQUEST_URI'];
                       $urlArray = explode('0',$url);
                       $last = $urlArray[sizeof($urlArray)-1];
                       //echo $url;
                       //echo "+++++" .$last; 
                      //$id_1 = $row['id'];
                      //echo "----".$id_1;
                     $_SESSION['page'] = $last;                       
                       ?>
              <a href="action.php?clear=all" class="badge-danger badge p-1" onclick="return confirm('Are you sure want to clear your cart?');"><i class="fa fa-trash"></i>&nbsp;&nbsp;Clear Cart</a>
            </th>
          </tr>
        </thead>
        <tbody>
          <?php 
            require 'config.php';
            $stmt = $conn->prepare("SELECT * FROM cart");
            $stmt->execute();
            $result = $stmt->get_result();
            $grand_total = 0;
            while($row = $result->fetch_assoc()):
          ?> 
          <tr>
            <td><?= $row['id'] ?></td>
            <input type="hidden" class="pid" value="<?= $row['id'] ?>">
            <td><img src="<?= $row['product_image'] ?>" width="50"></td>
            <td><?= $row['product_name'] ?></td>
            <td><i class="fa fa-rupee"></i>&nbsp;&nbsp;<?=number_format($row['product_price'],2); ?></td>
            <input type="hidden" class="pprice" value="<?= $row['product_price'] ?>">
            <td><input type="number" class="form-control itemQty" value="<?= $row['qty'] ?>" style="width: 75px;"></td>
            <td><i class="fa fa-rupee"></i>&nbsp;&nbsp;<?=number_format($row['total_price'],2); ?></td>
            <td>
              <?php  
               $url = $_SERVER['REQUEST_URI'];
                       $urlArray = explode('0',$url);
                       $last = $urlArray[sizeof($urlArray)-1];
                       //echo $url;
                       //echo "+++++" .$last; 
                      //$id_1 = $row['id'];
                      //echo "----".$id_1;
                     $_SESSION['page'] = $last;                       
                       ?>

              <a href="action.php?remove=<?= $row['id']?>" class="text-danger lead" onclick="return confirm('Are you sure want to remove this item');"><i class="fa fa-trash"></i></a>
            </td>
          </tr> 
            <?php $grand_total +=$row['total_price']; ?>
          <?php endwhile; ?>  
          <tr>
            <td colspan="3">
              <?php  
               $url = $_SERVER['REQUEST_URI'];
                       $urlArray = explode('0',$url);
                       $last = $urlArray[sizeof($urlArray)-1];
                       //echo $url;
                       //echo "+++++" .$last; 

               
            echo '<a href="' .$last. '" class="btn btn-success"><i class="fa fa-cart-plus"></i>&nbsp;&nbsp;Continue Shopping</a>';   
               ?>    
               <input type="hidden" value="<?php if(isset($last)){ echo($last);} ?>">        
            </td>
            <td colspan="2"><b>Grand Total</b></td>
            <td><b><i class="fa fa-rupee"></i>&nbsp;&nbsp;<?= number_format($grand_total,2); ?></b></td>
          </tr>      
        </tbody>
      </table>
      </div>
    </div>
  </div>  
</div>

  

<script>
  $(document).ready(function()
  {
    $(".itemQty").on('change', function(){
      var $el = $(this).closest('tr');

      var pid = $el.find(".pid").val();
      var pprice = $el.find(".pprice").val();
      var qty = $el.find(".itemQty").val();      
      location.reload(true);
      $.ajax({
        url: 'action.php',
        method: 'post',
        cache: false,
        data: {qty:qty,pid:pid,pprice:pprice},
        success: function(response){
          console.log(response);
        }
      });
    });

    load_cart_item_number();

    function load_cart_item_number(){
      $.ajax({
        url: 'action.php',
        method: 'get',
        data: {cartItem:"cart_item"},
        success:function(response){
          $("#cart-item").html(response);
        }
      });
    }
  });
</script>
</body>

</html>