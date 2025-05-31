<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();

    // Functions ពីមុន (getCart, updateCart, removeFromCart, updateQuantities, addToCart) នៅទីនេះ

    // រក្សាទុកព័ត៌មានអ្នកទូទាត់ពីទម្រង់
    if (isset($_POST['username'])) {
        $_SESSION['payer_info'] = [
            'username' => $_POST['username'],
            'email' => $_POST['email'],
            'phone' => $_POST['phone'],
            'pin_code' => $_POST['pin-code'],
            'address' => $_POST['address'],
        ];
    }

    // យកព័ត៌មានអ្នកទូទាត់
    function getPayerInfo()
    {
        return isset($_SESSION['payer_info']) ? $_SESSION['payer_info'] : [];
    }

    //Handle add to cart, remove item, update quantities (ដូចមុន)

}

// Function to get the cart from the session
function getCart()
{
    return isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
}

// Function to update the cart in the session
function updateCart($newCart)
{
    $_SESSION['cart'] = $newCart;
}

// Function to remove an item from the cart
function removeFromCart($itemId)
{
    $cart = getCart();
    $itemRemoved = false;

    foreach ($cart as $key => $item) {
        if ($item['id'] == $itemId) {
            unset($cart[$key]);
            $itemRemoved = true;
            break;
        }
    }

    updateCart(array_values($cart)); // Re-index after removal
    return $itemRemoved;
}

// Function to update item quantities in the cart
function updateQuantities($cartData)
{
    $cart = getCart();
    $updated = false;

    foreach ($cartData as $itemData) {
        $itemId = $itemData['id'];
        $newQuantity = intval($itemData['quantity']);

        foreach ($cart as &$item) { // Use reference to modify original array
            if ($item['id'] == $itemId) {
                $item['quantity'] = $newQuantity;
                $updated = true;
                break;
            }
        }
    }
    updateCart($cart);
    return $updated;
}

// Function to add item to cart
function addToCart($itemToAdd)
{
    $cart = getCart();
    $itemFound = false;
    foreach ($cart as &$item) {
        if ($item['id'] == $itemToAdd['id']) {
            $item['quantity'] += $itemToAdd['quantity'];
            $itemFound = true;
            break;
        }
    }
    if (!$itemFound) {
        $cart[] = $itemToAdd;
    }
    updateCart($cart);
}

//Handle add to cart.
if (isset($_POST['action']) && $_POST['action'] == 'addToCart') {
    $itemToAdd = array(
        'id' => $_POST['id'],
        'name' => $_POST['name'],
        'price' => floatval($_POST['price']),
        'image' => $_POST['image'],
        'quantity' => intval($_POST['quantity'])
    );

    addToCart($itemToAdd);
    echo json_encode(['success' => true, 'message' => 'Item added to cart']);
    exit();
}

// Handle remove item
if (isset($_POST['action']) && $_POST['action'] == 'removeItem') {
    $itemId = $_POST['id'];
    $removed = removeFromCart($itemId);

    if ($removed) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Item not found in cart']);
    }
    exit();
}

// Handle update quantities
if (isset($_POST['action']) && $_POST['action'] == 'updateQuantities') {
    $cartData = $_POST['cart'];
    $updated = updateQuantities($cartData);

    $newCart = getCart();
    $total = 0;
    $itemTotals = [];
    foreach ($newCart as $item) {
        $total += $item['price'] * $item['quantity'];
        $itemTotals[] =  number_format($item['price'] * $item['quantity'], 2); // Format each item total
    }

    if ($updated) {
        echo json_encode(['success' => true, 'total' => number_format($total, 2), 'item_totals' => $itemTotals]); //send total and item totals
    } else {
        echo json_encode(['success' => false, 'message' => 'Cart not updated']);
    }
    exit();
}
?>
<?php
include 'db/DBconnnect.php';
// session_start();

if (isset($_POST['add_to_cart'])) {
	if (isset($_SESSION['shoping-cart'])) {
		$product_array_ids = array_column($_SESSION['shoping-cart'], 'id');
		if (!in_array($_POST['id'], $product_array_ids)) {
			$product_array = array(
				'id' => $_POST['id'],
				'name' => $_POST['name'],
				'image' => $_POST['image'],
				'price' =>  $_POST['price'],
				'stock_quantity' => $_POST['stock_quantity']
			);

			$_SESSION['shopping-cart'][$product_id] = $product_array; // session ths store all item is array
		} else {
			echo '<script>alert("Product was already to cart.")</script>';
			echo '<script>window.location="index.php";</script>';
		}
	}
	// if this the first products
	else {
		$product_id = $_POST['id'];
		$product_name = $_POST['name'];
		$product_image = $_POST['image'];
		$product_price = $_POST['price'];
		$product_quantity = $_POST['stock_quantity'];

		$product_array = array(
			'id' => $product_id,
			'name' => $product_name,
			'image' => $product_image,
			'price' => $product_price,
			'stock_quantity' => $product_quantity
		);

		$_SESSION['shopping-cart'][$product_id] = $product_array; // session ths store all item is array
	}
} 
// else {
// 	header('location: index.php');
// }


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
                                <th class="column-2">Name</th>
                                <th class="column-3">Price</th>
                                <th class="column-4">Quantity</th>
                                <th class="column-5">Total</th>
                                <th class="column-6">Action</th>
                            </tr>

                            <?php
                            $cart = getCart();
                            $total = 0;

                            foreach ($cart as $item) {
                                $itemTotal = $item['price'] * $item['quantity'];
                                $total += $itemTotal;
                                echo '
                                <tr class="table_row">
                                    <td class="column-1">
                                        <div class="how-itemcart1">
                                            <img src="' . htmlspecialchars($item['image']) . '" alt="IMG">
                                        </div>
                                    </td>
                                    <td class="column-2">' . htmlspecialchars($item['name']) . '</td>
                                    <td class="column-3 price-per-item" data-price="' . $item['price'] . '">$' . number_format($item['price'], 2) . '</td>
                                    <td class="column-4">
                                        <div class="wrap-num-product flex-w m-l-auto m-r-0">
                                            <div class="btn-num-product-down cl8 hov-btn3 trans-04 flex-c-m">
                                                <i class="fs-16 zmdi zmdi-minus"></i>
                                            </div>
                                            <input class="mtext-104 cl3 txt-center num-product" type="number" name="num-product' . $item['id'] . '" value="' . $item['quantity'] . '" data-id="' . $item['id'] . '">
                                            <div class="btn-num-product-up cl8 hov-btn3 trans-04 flex-c-m">
                                                <i class="fs-16 zmdi zmdi-plus"></i>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="column-5 product-total">$' . number_format($itemTotal, 2) . '</td>
                                    <td class="column-6">
                                        <button class="flex-c-m stext-101 cl0 size-107 bg3 bor2 hov-btn3 p-lr-15 trans-04 m-b-10 remove-item" data-id="' . $item['id'] . '">
                                            Remove
                                        </button>
                                    </td>
                                </tr>';
                            }
                            ?>
                        </table>

                    </div>

                    <div class="size-209 p-r-18 p-r-0-sm w-full-ssm">
                        <div class="p-t-15">

                            <div class="flex-w">
                                <button class="flex-c-m stext-101 cl2 size-115 bg8 bor13 hov-btn3 p-lr-15 trans-04 pointer update-cart-btn" type="button">
                                    Update Amount
                                </button>
                            </div>

                        </div>
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
                            <span class="mtext-110 cl2 cart-subtotal">
                                $<?php echo number_format($total, 2); ?>
                            </span>
                        </div>

                    </div>

                    <div class="flex-w flex-t bor12 p-t-15 p-b-30">

                        <h4 class="mtext-109 cl2 p-b-30">
                            Bassic Details
                        </h4>
                        <form method="post">
                            <div class="bor8 bg0 m-b-22">
                                <input class="stext-111 cl8 plh3 size-111 p-lr-15" type="text" name="username" placeholder="Username">
                            </div>

                            <div class="bor8 bg0 m-b-22">
                                <input class="stext-111 cl8 plh3 size-111 p-lr-15" type="email" name="email" placeholder="Email Address">
                            </div>

                            <div class="bor8 bg0 m-b-22">
                                <input class="stext-111 cl8 plh3 size-111 p-lr-15" type="phone" name="phone" placeholder="Phone">
                            </div>

                            <div class="bor8 bg0 m-b-22">
                                <input class="stext-111 cl8 plh3 size-111 p-lr-15" type="text" name="pin-code" placeholder="Pin Code">
                            </div>

                            <div class="bor8 bg0 m-b-22">
                                <input class="stext-111 cl8 plh3 size-111 p-lr-15" type="address" name="address" placeholder="Address">
                            </div>
                        </form>

                    </div>

                    <div class="flex-w flex-t p-t-27 p-b-33">
                        <div class="size-208">
                            <span class="mtext-101 cl2">
                                Total:
                            </span>
                        </div>

                        <div class="size-209 p-t-1">
                            <span class="mtext-110 cl2 cart-total">
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
<script src="https://www.paypal.com/sdk/js?client-id=AfkfEA59DV9179OsB5kuRYFTzdu4Ap7KZTKe9peAbqHH7Q0L5JFJeQcDFKP9qEpvObffMNaTVILlUfqC&currency=USD"></script>
<script>
    $(document).ready(function() {
        // Event listener for quantity changes using event delegation
        $(".table-shopping-cart").on("change", ".num-product", function() {
            updateCartTotals(); // Update totals on quantity change
        });

        // Function to update the cart total and individual product totals
        function updateCartTotals() {
            let grandTotal = 0;
            let itemTotals = [];
            $(".table_row").each(function() {
                let quantity = parseInt($(this).find(".num-product").val());
                let price = parseFloat($(this).find(".price-per-item").data("price"));
                let itemTotal = quantity * price;
                $(this).find(".product-total").text("$" + itemTotal.toFixed(2));
                grandTotal += itemTotal;
                itemTotals.push(itemTotal.toFixed(2)); // Store formatted item total
            });
            $(".cart-total").text("$" + grandTotal.toFixed(2));
            $(".cart-subtotal").text("$" + grandTotal.toFixed(2));
            return {
                grandTotal: grandTotal.toFixed(2),
                itemTotals: itemTotals
            }; // Return the totals
        }

        // Event listener for the "Update Amount" button
        $(".update-cart-btn").on("click", function() {
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
                url: "", // Send to the same file
                type: "POST",
                data: {
                    action: "updateQuantities",
                    cart: cartData
                },
                dataType: "json",
                success: function(response) {
                    location.reload();
                },
                error: function(xhr, status, error) {
                    console.error("AJAX error:", error);
                    location.reload();

                }
            });
        });

        $(".remove-item").on("click", function() {
            let itemId = $(this).data("id");
            $.ajax({
                url: "", // Send to the same file
                type: "POST",
                data: {
                    action: "removeItem",
                    id: itemId
                },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        // Reload the cart to reflect the removal
                        location.reload();
                    } else {
                        alert("Error removing item from cart");
                    }
                }
            });
        });

        // PayPal
        paypal.Buttons({
            createOrder: function(data, actions) {
                let totalAmount = $(".cart-total").text().replace("$", "");
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
    });
</script>