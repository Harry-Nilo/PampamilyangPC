<?php


include "api/slider.php";

if (isset($_GET['query'])) {
  $query = htmlspecialchars($_GET['query']);
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./css/homepage.css?=v=1.0">
    <link rel="stylesheet" href="./css/homeslider.css?v=1.0">
    <link rel="icon" type="image/png" href="assets/PPClogo.png">
    <title>Pampamilyang PC</title>
</head>

<body>
    <header class="header">
        <div class="container">
            <div class="logo">PAMPAMILYANG PC</div>
            <nav class="nav">
                <a href="#" class="nav-link">Home</a>
                <a href="pc-builder" class="nav-link">PC Builder</a>
                <a href="product" class="nav-link">Product</a>
                <a href="pricelist" class="nav-link">Pricelist</a>

            </nav>
            <div class="header-icons">
                <button class="icon-btn" aria-label="Search" onclick="location.href='search'">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <path d="M9 17A8 8 0 1 0 9 1a8 8 0 0 0 0 16zM18 18l-4.35-4.35" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </button>
                <button class="icon-btn" aria-label="Account" onclick="location.href='dashboard'">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <path d="M10 10a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM3 18a7 7 0 0 1 14 0" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </button>
                <button id="cart-btn" class="icon-btn badge" data-count="0" aria-label="Cart" onclick="location.href='dashboard.php?section=cart'">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <path d="M1 1h3l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L22 6H6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </button>
                <button id="theme-toggle" class="icon-btn theme-toggle" aria-label="Toggle theme">
                    <svg class="sun-icon" width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <circle cx="10" cy="10" r="4" fill="currentColor"/>
                        <path d="M10 1v2M10 17v2M19 10h-2M3 10H1M16.07 3.93l-1.41 1.41M5.34 14.66l-1.41 1.41M16.07 16.07l-1.41-1.41M5.34 5.34L3.93 3.93" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    <svg class="moon-icon" width="20" height="20" viewBox="0 0 20 20" fill="currentColor" style="display: none;">
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
                    </svg>
                </button>
            </div>
        </div>
    </header>

    <div class="container-scale">

       <div class="content-header-div hidden">
          <div class="content-seperator">

              <div class="header-left">


                 <div class="text-side-glow">

                    <div class="line-glow"></div>


                    <div class="text-header">
                       <h1>Powerful PC Performance Starts with Proven, Pre-Owned Components</h1>
                       <h3>Get more power for your budget with expertly inspected and performance-checked components.</h3> 
                       
                       <a href="">Shop Now</a>

                    </div>

                 </div>

              </div>

              <div class="header-right">
                <div class="bg-glow"></div>
                <img src="./assets/headerpic.png" alt="NVIDIA RTX picture">
              </div>

          </div>
       </div>


       <div class="content-1-container hidden">

          <div class="content-1">

            <div class="content1left">
                <h1>Why Buy From Our Shop?</h1>
                <div class="c1leftdesc">
                    <h3>
                        Our shop offers carefully tested 2nd-hand PC parts that deliver reliable performance without the high price tag. You get quality, convenience, and the best value for your budget.
                    </h3>
                    <h3>Each component is hand-checked to ensure you get performance you can trust.</h3>
                </div>
            </div>

            <div class="content1right">
                <div class="ct1rightcontainer">


                    <div class="ct1feature ct1feature1">

                       <div class="ctnfeature">
                           <div class="ctnftrtextcontainer">
                               <h2>Free Shipping</h2>
                               <h3>Free Shipping to Make Your Shopping Experience Seamless.</h3>
                           </div>

                       </div>
                       
                         <div class="ctnfeature">
                             <div class="ctnftrtextcontainer">
                                 <h2>Free Shipping</h2>
                                 <h3>Enjoy free shipping on all orders for a seamless shopping experience.</h3>
                             </div>
                         </div>


                                         </div>

                                         <div class="ct1feature ct1feature2">

                         <div class="ctnfeature">
                             <div class="ctnftrtextcontainer">
                                 <h2>Quality Assurance</h2>
                                 <h3>All products, new or used, are tested and guaranteed to work perfectly.</h3>
                             </div>
                         </div>

                         <div class="ctnfeature">
                             <div class="ctnftrtextcontainer">
                                 <h2>Easy Returns</h2>
                                 <h3>Hassle-free returns and exchanges for your peace of mind.</h3>
                             </div>
                         </div>

                    </div>

                </div>
            </div>  

          </div>

       </div>


        <div class="content-2-container">
          <div class="content-2">
               <div class="product-brands">

                    <div class="brands">

                      <img src="./assets/brands-logo/amd.jpg" class="logo_1" alt="AMD">
                      <img src="./assets/brands-logo/arctic.png" class="logo_2" alt="Arctic">
                      <img src="./assets/brands-logo/asrock.png" class="logo_3" alt="Asrock">
                      <img src="./assets/brands-logo/asus.png" class="logo_4" alt="Asus">
                      <img src="./assets/brands-logo/coolermaster.png" class="logo_5" alt="Cooler Master">
                      <img src="./assets/brands-logo/corsair.jpg" class="logo_6" alt="Corsair">
                      <img src="./assets/brands-logo/crucial.jpg" class="logo_7" alt="Crucial">
                      <img src="./assets/brands-logo/Gigabyte-Logo.png" class="logo_8" alt="Gigabyte">
                      <img src="./assets/brands-logo/intel.png" class="logo_9" alt="Intel">
                      <img src="./assets/brands-logo/lianli.png" class="logo_10" alt="Lian Li">
                      <img src="./assets/brands-logo/MSI-Logo.jpg" class="logo_11" alt="MSI">
                      <img src="./assets/brands-logo/nzxt.jpg" class="logo_12" alt="NZXT">
                      <img src="./assets/brands-logo/samsung.png" class="logo_13" alt="Samsung">
                      <img src="./assets/brands-logo/seasonic.png" class="logo_14" alt="Seasonic">
                      <img src="./assets/brands-logo/xfx.jpg" class="logo_15" alt="XFX">


                    </div>

                    <div class="brands">
                      <img src="./assets/brands-logo/amd.jpg" class="logo_1" alt="AMD">
                      <img src="./assets/brands-logo/arctic.png" class="logo_2" alt="Arctic">
                      <img src="./assets/brands-logo/asrock.png" class="logo_3" alt="Asrock">
                      <img src="./assets/brands-logo/asus.png" class="logo_4" alt="Asus">
                      <img src="./assets/brands-logo/coolermaster.png" class="logo_5" alt="Cooler Master">
                      <img src="./assets/brands-logo/corsair.jpg" class="logo_6" alt="Corsair">
                      <img src="./assets/brands-logo/crucial.jpg" class="logo_7" alt="Crucial">
                      <img src="./assets/brands-logo/Gigabyte-Logo.png" class="logo_8" alt="Gigabyte">
                      <img src="./assets/brands-logo/intel.png" class="logo_9" alt="Intel">
                      <img src="./assets/brands-logo/lianli.png" class="logo_10" alt="Lian Li">
                      <img src="./assets/brands-logo/MSI-Logo.jpg" class="logo_11" alt="MSI">
                      <img src="./assets/brands-logo/nzxt.jpg" class="logo_12" alt="NZXT">
                      <img src="./assets/brands-logo/samsung.png" class="logo_13" alt="Samsung">
                      <img src="./assets/brands-logo/seasonic.png" class="logo_14" alt="Seasonic">
                      <img src="./assets/brands-logo/xfx.jpg" class="logo_15" alt="XFX">

                    </div>

               </div>
          </div>
       </div>



       <div class="product-slider-container">
           <button class="slider-btn left" id="sliderLeft">&#10094;</button>
       
           <div class="product-slider" id="productSlider">
       
               <!-- ORIGINAL PRODUCTS -->
               <?php foreach ($products as $p): ?>
                   <div class="product-card">
                       <a href="product">
                           <img src="<?= $p['image'] ?>" alt="<?= $p['name'] ?>" class="product-image">                        
                       </a>

               
                       <div class="product-info">
                           <span class="product-category category-<?= strtolower($p['category']) ?>">
                               <?= $p['category'] ?>
                           </span>
               
                           <div class="product-name"><?= $p['name'] ?></div>
               
                           <div class="product-rating">
                               <?php
                                   $rating = round($p["rating"]);
                                   for ($i = 1; $i <= 5; $i++) {
                                       echo "<span class='star " . ($i <= $rating ? "filled" : "") . "'>★</span>";
                                   }
                               ?>
                           </div>
                               
                           <div class="product-price">
                               ₱<?= number_format($p["price"], 2) ?>
                           </div>
                       </div>
                               
                   </div>
               <?php endforeach; ?>
                               
               <!-- DUPLICATED PRODUCTS (for infinite scroll) -->
               <?php foreach ($products as $p): ?>
                   <div class="product-card">
               
                       <img src="<?= $p['image'] ?>" alt="<?= $p['name'] ?>" class="product-image">
               
                       <div class="product-info">
                           <span class="product-category category-<?= strtolower($p['category']) ?>">
                               <?= $p['category'] ?>
                           </span>
               
                           <div class="product-name"><?= $p['name'] ?></div>
               
                           <div class="product-rating">
                               <?php
                                   $rating = round($p["rating"]);
                                   for ($i = 1; $i <= 5; $i++) {
                                       echo "<span class='star " . ($i <= $rating ? "filled" : "") . "'>★</span>";
                                   }
                               ?>
                           </div>
                               
                           <div class="product-price">
                               ₱<?= number_format($p["price"], 2) ?>
                           </div>
                       </div>
                               
                   </div>
               <?php endforeach; ?>
                               
           </div>
                               
           <button class="slider-btn right" id="sliderRight">&#10095;</button>
       </div>



    </div>

    <script>


        const observer = new IntersectionObserver((entries) => {

            entries.forEach((entry) => {
                console.log(entry)
                if (entry.isIntersecting) {
                  entry.target.classList.add('show');
                  observer.unobserve(entry.target); 
                }
                // if (entry.isIntersecting){
                //     entry.target.classList.add('show');
                // } else {
                //     entry.target.classList.remove('show');                    
                // }
            })
        })
   
        const hiddenElements = document.querySelectorAll('.hidden');
        hiddenElements.forEach((el)=> observer.observe(el));



const slider = document.getElementById("productSlider");
const btnLeft = document.getElementById("sliderLeft");
const btnRight = document.getElementById("sliderRight");

const scrollAmount = 300;

// Loop endless
slider.addEventListener("scroll", () => {
    const half = slider.scrollWidth / 2;

    // Reached the duplicate end → teleport to start
    if (slider.scrollLeft >= half) {
        slider.scrollLeft = 1;
    }

    // Reached the start → teleport to duplicate end
    if (slider.scrollLeft <= 0) {
        slider.scrollLeft = half - 1;
    }
});

btnLeft.addEventListener("click", () => {
    slider.scrollBy({ left: -scrollAmount, behavior: "smooth" });
});

btnRight.addEventListener("click", () => {
    slider.scrollBy({ left: scrollAmount, behavior: "smooth" });
});





document.addEventListener('DOMContentLoaded', () => {
    const cartBtn = document.getElementById('cart-btn');
    if(!cartBtn) return;

    fetch('api/cart_count.php')
        .then(res => res.json())
        .then(data => {
            cartBtn.dataset.count = data.count || 0;
            cartBtn.classList.toggle('has-items', (data.count || 0) > 0);
        });
});

    </script>
</body>

</html>