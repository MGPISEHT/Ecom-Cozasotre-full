<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<form class="bg0 p-t-75 p-b-85">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 col-xl-7 m-lr-auto m-b-50">
                <div class="m-l-25 m-r--38 m-lr-0-xl">
                    <div class="wrap-table-shopping-cart">
                        <table class="table-shopping-cart">
                            <tr class="table_head">
                                <th class="column-1">Product</th>
                                <th class="column-2"></th>
                                <th class="column-3">Price</th>
                                <th class="column-4">Quantity</th>
                                <th class="column-5">Total</th>
                            </tr>

                            <?php
                            $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
                            $total = 0;

                            foreach ($cart as $item) {
                                $total += $item['price'] * $item['quantity'];
                                echo '
                                <tr class="table_row">
                                    <td class="column-1">
                                        <div class="how-itemcart1">
                                            <img src="' . htmlspecialchars($item['image']) . '" alt="IMG">
                                        </div>
                                    </td>
                                    <td class="column-2">' . htmlspecialchars($item['name']) . '</td>
                                    <td class="column-3">$' . number_format($item['price'], 2) . '</td>
                                    <td class="column-4">
                                        <div class="wrap-num-product flex-w m-l-auto m-r-0">
                                            <div class="btn-num-product-down cl8 hov-btn3 trans-04 flex-c-m">
                                                <i class="fs-16 zmdi zmdi-minus"></i>
                                            </div>
                                            <input class="mtext-104 cl3 txt-center num-product" type="number" name="num-product' . $item['id'] . '" value="' . $item['quantity'] . '">
                                            <div class="btn-num-product-up cl8 hov-btn3 trans-04 flex-c-m">
                                                <i class="fs-16 zmdi zmdi-plus"></i>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="column-5">$' . number_format($item['price'] * $item['quantity'], 2) . '</td>
                                </tr>';
                            }
                            ?>
                        </table>
                    </div>

                    
                </div>
            </div>

            <div class="col-sm-10 col-lg-7 col-xl-5 m-lr-auto m-b-50">
                <div class="bor10 p-lr-40 p-t-30 p-b-40 m-l-63 m-r-40 m-lr-0-xl p-lr-15-sm">
                    <h4 class="mtext-109 cl2 p-b-30">
                        Cart Totals
                    </h4>

                    <div class="flex-w flex-t bor12 p-b-13">
                        <div class="size-208">
                            <span class="stext-110 cl2">
                                Subtotal:
                            </span>
                        </div>

                        <div class="size-209 p-t-1">
                            <span class="mtext-110 cl2 cart-total">
                                $<?php echo number_format($total, 2); ?>
                            </span>
                        </div>

                    </div>

                    <div class="flex-w flex-t bor12 p-t-15 p-b-30">
                        <div class="size-208 w-full-ssm">
                            <span class="stext-110 cl2">
                                Shipping:
                            </span>
                        </div>

                        <div class="size-209 p-r-18 p-r-0-sm w-full-ssm">
                            <p class="stext-111 cl6 p-t-2">
                                There are no shipping methods available. Please double check your address, or contact us if you need any help.
                            </p>

                            <div class="p-t-15">
                                <span class="stext-112 cl8">
                                    Calculate Shipping
                                </span>

                                <div class="rs1-select2 rs2-select2 bor8 bg0 m-b-12 m-t-9">
                                    <select class="js-select2" name="time">
                                        <option>Select a country...</option>
                                        <option>USA</option>
                                        <option>UK</option>
                                    </select>
                                    <div class="dropDownSelect2"></div>
                                </div>

                                <div class="bor8 bg0 m-b-12">
                                    <input class="stext-111 cl8 plh3 size-111 p-lr-15" type="text" name="state" placeholder="State /  country">
                                </div>

                                <div class="bor8 bg0 m-b-22">
                                    <input class="stext-111 cl8 plh3 size-111 p-lr-15" type="text" name="postcode" placeholder="Postcode / Zip">
                                </div>

                                <div class="flex-w">
                                    <div class="flex-c-m stext-101 cl2 size-115 bg8 bor13 hov-btn3 p-lr-15 trans-04 pointer">
                                        Update Totals
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="flex-w flex-t p-t-27 p-b-33">
                        <div class="size-208">
                            <span class="mtext-101 cl2">
                                Total:
                            </span>
                        </div>

                        <div class="size-209 p-t-1">
                            <span class="mtext-110 cl2">
                                $<?php echo number_format($total, 2); ?>
                            </span>
                        </div>
                    </div>

                    <div id="paypal-button-container"></div>
                </div>
            </div>
        </div>
    </div>
</form>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://www.paypal.com/sdk/js?client-id=YOUR_PAYPAL_CLIENT_ID&currency=USD"></script>
<script>
    $(document).ready(function() {
        $(".update-cart-btn").on("click", function() {
            updateTotal();
        });

        function updateTotal() {
            let cartData = [];
            $(".table-shopping-cart tr.table_row").each(function() {
                let id = $(this).find("input.num-product").attr("name").replace("num-product", "");
                let quantity = $(this).find("input.num-product").val();
                cartData.push({
                    id: id,
                    quantity: quantity
                });
            });

            $.ajax({
                url: "update_total.php",
                type: "POST",
                data: {
                    cart: cartData
                },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        $(".cart-total").text("$" + response.total.toFixed(2));
                    } else {
                        alert("Error updating total");
                    }
                }
            });
        }
    });

    paypal.Buttons({
        createOrder: function(data, actions) {
            return actions.order.create({
                purchase_units: [{
                    amount: {
                        value: '<?php echo number_format($total, 2); ?>'
                    }
                }]
            });
        },
        onApprove: function(data, actions) {
            return actions.order.capture().then(function(details) {
                alert('Transaction completed by ' + details.payer.name.given_name);
                // Redirect to a success page
                window.location.href = 'success.php';
            });
        }
    }).render('#paypal-button-container');
</script>
<script>
    $(document).ready(function() {
        $("#update-cart").click(function() {
            updateTotal();
        });

        function updateTotal() {
            let cartData = [];
            $(".table_row").each(function() {
                let id = $(this).find(".num-product").data("id");
                let quantity = $(this).find(".num-product").val();
                cartData.push({
                    id: id,
                    quantity: quantity
                });
            });

            $.ajax({
                url: "update_total.php",
                type: "POST",
                data: {
                    cart: cartData
                },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        $("#cart-total").text("$" + response.total.toFixed(2));
                        $(".product-total").each(function(index) {
                            $(this).text("$" + response.item_totals[index].toFixed(2));
                        });
                    } else {
                        alert("Error updating total");
                    }
                }
            });
        }
    });

    paypal.Buttons({
        createOrder: function(data, actions) {
            let totalAmount = $("#cart-total").text().replace("$", "");
            return actions.order.create({
                purchase_units: [{
                    amount: {
                        value: totalAmount
                    }
                }]
            });
        },
        onApprove: function(data, actions) {
            return actions.order.capture().then(function(details) {
                alert("Transaction completed by " + details.payer.name.given_name);
                window.location.href = "success.php";
            });
        }
    }).render("#paypal-button-container");
</script>