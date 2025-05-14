<!-- Cart Section -->
<div class="wrap-header-cart js-panel-cart">
    <div class="s-full js-hide-cart"></div>
    <div class="header-cart flex-col-l p-l-65 p-r-25">
        <div class="header-cart-title flex-w flex-sb-m p-b-8">
            <span class="mtext-103 cl2">Your Cart</span>
            <div class="fs-35 lh-10 cl2 p-lr-5 pointer hov-cl1 trans-04 js-hide-cart">
                <i class="zmdi zmdi-close"></i>
            </div>
        </div>

        <div class="header-cart-content flex-w js-pscroll">
            <ul class="header-cart-wrapitem w-full">
            </ul>

            <div class="w-full">
                

                <div class="header-cart-buttons flex-w w-full">
                    <a href="shoping-cart.php" class="flex-c-m stext-101 cl0 size-107 bg3 bor2 hov-btn3 p-lr-15 trans-04 m-r-8 m-b-10">
                        View Cart
                    </a>
                    <a href="shoping-cart.php" class="flex-c-m stext-101 cl0 size-107 bg3 bor2 hov-btn3 p-lr-15 trans-04 m-b-10">
                        Check Out
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    loadCart(); // Load cart data when the page loads

    document.querySelectorAll('.js-add-to-cart').forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault(); // Prevent default action

            var cartItem = {
                id: this.getAttribute('data-id'),
                name: this.getAttribute('data-name'),
                price: parseFloat(this.getAttribute('data-price')),
                image: this.getAttribute('data-image'),
                quantity: parseInt(this.getAttribute('data-quantity'))
            };

            // Send data to PHP via AJAX
            fetch('add_to_cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(cartItem)
            })
            .then(response => response.json())
            .then(cartData => {
                updateCart(cartData);
                document.querySelector('.wrap-header-cart').classList.add('show-cart'); // Show cart
            })
            .catch(error => console.error('Error adding to cart:', error));
        });
    });
});

// Function to update the cart UI
function updateCart(cartItems) {
    var cartList = document.querySelector('.header-cart-wrapitem');
    var cartTotal = document.querySelector('.header-cart-total');
    var cartIcon = document.querySelector('#cart');

    cartList.innerHTML = ''; // Clear cart before updating
    var totalAmount = 0;
    var itemCount = 0;

    cartItems.forEach(function (item) {
        totalAmount += item.price * item.quantity;
        itemCount += item.quantity;

        cartList.innerHTML += `
            <li class="header-cart-item flex-w flex-t m-b-12">
                <div class="header-cart-item-img">
                    <img src="${item.image}" alt="${item.name}">
                </div>
                <div class="header-cart-item-txt p-t-8">
                    <a href="#" class="header-cart-item-name m-b-18 hov-cl1 trans-04">${item.name}</a>
                    <span class="header-cart-item-info">${item.quantity} x $${item.price.toFixed(2)}</span>
                </div>
            </li>
        `;
    });

    cartTotal.textContent = `Total: $${totalAmount.toFixed(2)}`;

    if (cartIcon) {
        cartIcon.setAttribute('data-notify', itemCount); // Update cart count on icon
    }
}

// Load cart count and items on page load
function loadCart() {
    fetch('get_cart.php')
        .then(response => response.json())
        .then(cartData => {
            updateCart(cartData);
        })
        .catch(error => console.error('Error loading cart:', error));
}
</script>