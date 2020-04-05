<!DOCTYPE html>
<html lang="en">

<head>
  <title>Handbag</title>
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
      <a class="navbar-brand">Handbag</a>
    </div>
  <ul class="navbar-nav ml-auto">
  <!--  <li class="nav-item">
      <a class="nav-link active" href="index.php">Product</a>
    </li>
  -->
    <li class="nav-item">    

      <?php  
       $url = $_SERVER['REQUEST_URI'];
               $urlArray = explode('/',$url);
               $last = $urlArray[sizeof($urlArray)-1];
               //echo $url;
               //echo $last;  
     echo '<a class="nav-link" href="cart.php?last = ' .$last. '">Checkout <i class="fa fa-shopping-cart"></i> <span id="cart-item" class="badge badge-danger"></span></a>';
     ?>

    </li>
  </ul>
</nav>

<div class="container">
  <div id="message"></div>
  <div class="row mt-2 pb-3">

    <?php
    include 'config.php';
    $stmt = $conn->prepare("SELECT * FROM handbag");
    $stmt->execute();
    $result = $stmt->get_result();
    while($row = $result->fetch_assoc()):
    ?>
    <div class="col-lg-4 col-md-4 col-sm-6">
      <div class="card-deck">
        <div class="card p-2 border-secondary mb-2">
        
        <img src="<?= $row['product_image'] ?>" class="card-img-top" height="250">         
          <div class="card-body p-1" style="display: inline-block; overflow: hidden !important;">             
          <!--  <h4 class="card-title text-center text-info"><?= $row['product_name'] ?></h4> -->
          
          </div>
          <div class="card-footer p-1">
            <form action="" class="form-submit">
              <input type="hidden" class="pid" value="<?= $row['id']?>">
              <input type="hidden" class="pname" value="<?= $row['product_name']?>">
              <input type="hidden" class="pprice" value="<?= $row['product_price']?>">
              <input type="hidden" class="pimage" value="<?= $row['product_image']?>">
              <input type="hidden" class="pcode" value="<?= $row['product_code']?>">
              <button class="btn btn-info btn-block addItemBtn"><i class="fa fa-cart-plus"></i> &nbsp; &nbsp; Add to cart</button>
            </form>
            
          </div>
        </div>
    </div>
  </div>
<?php endwhile; ?>
  </div>
</div>

  

<script>
  $(document).ready(function()
  {
    $(".addItemBtn").click(function(e){
      e.preventDefault();
      var $form = $(this).closest(".form-submit");
      var pid = $form.find(".pid").val();
      var pname = $form.find(".pname").val();
      var pprice = $form.find(".pprice").val();
      var pimage = $form.find(".pimage").val();
      var pcode = $form.find(".pcode").val();

      $.ajax({
        url: 'action.php',
        method: 'post',
        data: {pid:pid,pname:pname,pprice:pprice,pimage:pimage,pcode:pcode},
        success:function(response){
          $("#message").html(response);
          window.scrollTo(0,0);
          load_cart_item_number();
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