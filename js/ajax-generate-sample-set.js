jQuery(document).ready(function($) 
{
    $('#generate-tile-set').on('click', function() 
    {
        // Retrieve the sampleList from local storage
        let sampleList = JSON.parse(localStorage.getItem('sampleList')) || [];

        // Create an array to hold product names
        let productNames = [];

        // Loop through the sampleList and extract product names
        $.each(sampleList, function(index, product) {
            if (product.name) { // Ensure the name attribute exists
                productNames.push(product.name);
            }
        });

        // Join the names into a single string, separated by commas 
        let passName = productNames.join(', ');

        // Save the new string in local storage  
        localStorage.setItem('passName', passName);
        localStorage.setItem('sampleList', JSON.stringify([]));

        /*var productId = 17906;//sample tile id  

        check & remove exist sample tile set
            $.ajax({
                url: '/wp-admin/admin-ajax.php',
                type: 'POST',
                data: {
                    action: 'get_cart_contents'
                },
                success: function(cart) {
                    // Check if the product is in the cart
                    var productInCart = cart.some(function(item) {
                        return item.product_id == productId;
                    });

                    // If the product is in the cart, remove it
                    if (productInCart) {
                        $.ajax({
                            url: '/wp-admin/admin-ajax.php',
                            type: 'POST',
                            data: {
                                action: 'remove_product_from_cart',
                                product_id: productId
                            },
 
                        });
                    }
                }
            });

        */
    });
});


