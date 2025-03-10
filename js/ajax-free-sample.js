jQuery(document).ready(function($) 
{
    $('.sample-button').on('click', function() {
        const productName = $(this).data('product-name');
        const productThumbnail = $(this).data('product-thumbnail');
        const productPermalink = $(this).data('product-permalink');

        let sampleList = JSON.parse(localStorage.getItem('sampleList')) || [];
        
        if (!sampleList.some(item => item.name === productName)) {
            sampleList.push({
                name: productName,
                thumbnail: productThumbnail,
                permalink: productPermalink
            });
            localStorage.setItem('sampleList', JSON.stringify(sampleList));
        } 
    });
});


