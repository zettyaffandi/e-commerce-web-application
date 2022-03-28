

<?php include 'includes/header.php'; ?>

<body data-spy="scroll" data-target=".site-navbar-target" data-offset="300">

  <div class="site-wrap bg-col pt-4">

     <div id="myCarousel" class="carousel slide bg-inverse resp" data-ride="carousel">
      <ol class="carousel-indicators">
        <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
        <li data-target="#myCarousel" data-slide-to="1"></li>
      </ol>
      <div class="carousel-inner" role="listbox">
        <div class="carousel-item active">
          <img src="images/img1.jpg" alt="First slide">
        </div>
        <div class="carousel-item">
          <img src="images/img2.jpg" alt="Second slide">
        </div>
      </div>
      <a class="carousel-control-prev" href="#myCarousel" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
      </a>
      <a class="carousel-control-next" href="#myCarousel" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
      </a>
  </div>

    <div class="site-section">
      <div class="container">

        <div class="row mb-2">
          <div class="col-12 section-title text-center">
            <h2 class="d-block">Thirst Menu</h2>
            <p>Thirst offers premium and healthy fruit-based milkshakes and smoothies. What is special about Thirst is that all our drinks are made only with the freshest ingredients. The secret behind the refreshing taste of Thirst’s milkshakes and smoothies is the use of fruit puree and syrup.</p>
            <p><a href="menu.php">View All Menu <span class="icon-long-arrow-right"></span></a></p>
          </div>
        </div>
        <div class="row">

          <div class="col-lg-4 col-md-6">

            <div class="prod-ad text-center pb-4">
              <img src="images/Milo Godzilla.jpg" alt="Image" class="img-fluid">
              <div class="bg-col-2">
                <h3 class="heading">Mermaid</h3>
                <span class="price">RM 9.50</span>
              </div>


              <div class="prod-ad-actions">

                <h3 class="heading">Mermaid</h3>
                <span class="price d-block">RM 9.50</span>

                <a href="product.php?product=11" class="btn"><span class="icon-shopping-bag mr-3"></span> Order Now</a>
              </div>
            </div>

          </div>

          <div class="col-lg-4 col-md-6">
            <div class="prod-ad text-center pb-4">
              <img src="images/Milo Godzilla.jpg" alt="Image" class="img-fluid">
              <div>
                <h3 class="heading">Milo Godzilla</h3>
                <span class="price">RM 10.50</span>
              </div>


              <div class="prod-ad-actions">

                <h3 class="heading">Milo Godzilla</h3>
                <span class="price d-block">RM 10.50</span>

                <a href="product.php?product=25" class="btn"><span class="icon-shopping-bag mr-3"></span> Order Now</a>
              </div>
            </div>
          </div>

          <div class="col-lg-4 col-md-6">
            <div class="prod-ad text-center pb-4">
              <img src="images/Milo Godzilla.jpg" alt="Image" class="img-fluid">
              <div>
                <h3 class="heading">Choco Loco</h3>
                <span class="price">RM 10.50</span>
              </div>


              <div class="prod-ad-actions">

                <h3 class="heading">Choco Loco</h3>
                <span class="price d-block">RM 10.50</span>

                <a href="product.php?product=19" class="btn"><span class="icon-shopping-bag mr-3"></span> Order Now</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

<?php
  include 'includes/footer.php';
?>

</body>

</html>
