document.addEventListener('DOMContentLoaded', function() {
    // Get all product price containers
    const priceContainers = document.querySelectorAll('.product-summary');

    priceContainers.forEach(container => 
    {
        const priceSaleElement = container.querySelector('.woocommerce-Price-amount');
        const priceRegElement = container.querySelector('ins .woocommerce-Price-amount');

        // Get the suffix element
        const suffixElement = container.querySelector('.woocommerce-price-suffix');
        const suffix = suffixElement.textContent.trim();

        if(priceSaleElement)
        {
            const priceSaleText = priceSaleElement.textContent.trim();
            const priceSaleValue = parseFloat(priceSaleText.replace(/[^0-9.]/g, ''));
            const roundedSalePrice = Math.round(priceSaleValue);
            const currencySaleSymbol = priceSaleElement.querySelector('.woocommerce-Price-currencySymbol').textContent;
            priceSaleElement.innerHTML = `<bdi>${currencySaleSymbol}${roundedSalePrice}</bdi>`;
        }

        if(priceRegElement)
        {
            const priceRegText = priceRegElement.textContent.trim();
            const priceRegValue = parseFloat(priceRegText.replace(/[^0-9.]/g, ''));
            const roundedRegPrice = Math.round(priceRegValue);
            const currencyRegSymbol = priceRegElement.querySelector('.woocommerce-Price-currencySymbol').textContent;
            priceRegElement.innerHTML = `<bdi>${currencyRegSymbol}${roundedRegPrice}</bdi>`;
        }
        
    });
});