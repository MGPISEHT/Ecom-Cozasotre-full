<!DOCTYPE html>
<html lang="en">
<?php include 'includes/head.php' ?>
<title>Home</title>

<body class="animsition">
  <!-- Header -->
  <?php include 'includes/header.php' ?>
  <!-- Cart -->
  <?php include 'includes/cart.php' ?>

  <!-- Slider -->
  <?php include 'includes/slider.php' ?>

  <!-- Banner -->
  <?php include 'includes/banner.php' ?>

  <!-- Product -->
  <?php include 'includes/product.php' ?>

  <!-- Footer -->
  <?php include 'includes/footer.php' ?>

  <!-- Back to top -->
  <div class="btn-back-to-top" id="myBtn">
    <span class="symbol-btn-back-to-top">
      <i class="zmdi zmdi-chevron-up"></i>
    </span>
  </div>

  <!-- Modal1 -->
  <!-- <?php include 'includes/modal.php' ?> -->

  <!--===============================================================================================-->
  <script src="vendor/jquery/jquery-3.2.1.min.js"></script>
  <!--===============================================================================================-->
  <script src="vendor/animsition/js/animsition.min.js"></script>
  <!--===============================================================================================-->
  <script src="vendor/bootstrap/js/popper.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
  <!--===============================================================================================-->
  <script src="vendor/select2/select2.min.js"></script>
  <script>
    $(".js-select2").each(function() {
      $(this).select2({
        minimumResultsForSearch: 20,
        dropdownParent: $(this).next(".dropDownSelect2"),
      });
    });
  </script>
  <!--===============================================================================================-->
  <script src="vendor/daterangepicker/moment.min.js"></script>
  <script src="vendor/daterangepicker/daterangepicker.js"></script>
  <!--===============================================================================================-->
  <script src="vendor/slick/slick.min.js"></script>
  <script src="js/slick-custom.js"></script>
  <!--===============================================================================================-->
  <script src="vendor/parallax100/parallax100.js"></script>
  <script>
    $(".parallax100").parallax100();
  </script>
  <!--===============================================================================================-->
  <script src="vendor/MagnificPopup/jquery.magnific-popup.min.js"></script>
  <script>
    $(".gallery-lb").each(function() {
      // the containers for all your galleries
      $(this).magnificPopup({
        delegate: "a", // the selector for gallery item
        type: "image",
        gallery: {
          enabled: true,
        },
        mainClass: "mfp-fade",
      });
    });
  </script>
  <!--===============================================================================================-->
  <script src="vendor/isotope/isotope.pkgd.min.js"></script>
  <!--===============================================================================================-->
  <script src="vendor/sweetalert/sweetalert.min.js"></script>
  <script>
    $(".js-addwish-b2").on("click", function(e) {
      e.preventDefault();
    });

    $(".js-addwish-b2").each(function() {
      var nameProduct = $(this).parent().parent().find(".js-name-b2").html();
      $(this).on("click", function() {
        swal(nameProduct, "is added to wishlist !", "success");

        $(this).addClass("js-addedwish-b2");
        $(this).off("click");
      });
    });

    $(".js-addwish-detail").each(function() {
      var nameProduct = $(this)
        .parent()
        .parent()
        .parent()
        .find(".js-name-detail")
        .html();

      $(this).on("click", function() {
        swal(nameProduct, "is added to wishlist !", "success");

        $(this).addClass("js-addedwish-detail");
        $(this).off("click");
      });
    });

    /*---------------------------------------------*/

    $(".js-addcart-detail").each(function() {
      var nameProduct = $(this)
        .parent()
        .parent()
        .parent()
        .parent()
        .find(".js-name-detail")
        .html();
      $(this).on("click", function() {
        swal(nameProduct, "is added to cart !", "success");
      });
    });
  </script>
  <!--===============================================================================================-->
  <script src="vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
  <script>
    $(".js-pscroll").each(function() {
      $(this).css("position", "relative");
      $(this).css("overflow", "hidden");
      var ps = new PerfectScrollbar(this, {
        wheelSpeed: 1,
        scrollingThreshold: 1000,
        wheelPropagation: false,
      });

      $(window).on("resize", function() {
        ps.update();
      });
    });
  </script>
  <!--===============================================================================================-->
  <script src="js/main.js"></script>
</body>

</html>