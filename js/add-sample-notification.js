jQuery(document).ready(function($) {

    $('#sample-button-notification').on('click', function() {

        // Display the notification
        const productName = customData.productName;
        const notification = $('<div class="woocommerce-message" role="alert" tabindex="-1">' +
            'A sample of ' + productName + ' has been added to your cart.<a href="/cart" class="button wc-forward">View cart</a></div>');
        
        // Append the notification to the body or any specific element    
        $('.woocommerce-notices-wrapper').append(notification);

        //scroll to the notification
        notification.fadeIn('slow', function() {
            $('html, #header_main').animate({
                scrollTop: notification.offset().top - 300
            }, 500); 
        });
        
    });
});