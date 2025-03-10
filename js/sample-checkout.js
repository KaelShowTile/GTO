// Function to send the sample list to the server
function sendSampleListToServer() {
    let sampleList = JSON.parse(localStorage.getItem('sampleList')) || [];
        if (sampleList.length > 0) {
            $.post(ajax_object.ajax_url, {
            action: 'store_sample_list_in_session',
            sampleList: sampleList
        }, function(response) {
            console.log('Server Response:', response); // Log the server response
        });
    }
}

// Function to handle the checkout process
function setupCheckout() 
{
    // Trigger sending sample list when the checkout button is clicked
    $('#place_order').on('click', function() 
    {
        sendSampleListToServer();
    });
}