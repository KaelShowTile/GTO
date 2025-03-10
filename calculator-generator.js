const summaryDiv = document.querySelector('.tile-calculator-outcome-summary');

// Function to update summary
function updateSummary() {
    const area = document.querySelector('.tile-calculator-outcome-total-area .div .cff-summary-item .summary-field-value').textContent;
    const tiles = document.querySelector('.tile-calculator-outcome-total-tiles .div .cff-summary-item .summary-field-value').textContent;
    const box = document.querySelector('.tile-calculator-outcome-total-box .div .cff-summary-item .summary-field-value').textContent;
    const price = document.querySelector('.tile-calculator-outcome-total-price .div .cff-summary-item .summary-field-value').textContent;

    summaryDiv.innerHTML = `
        <p>Total Area: ${area}</p>
        <p>Total Tiles: ${tiles}</p>
        <p>Total Box: ${box}</p>
        <p>Total Price: ${price}</p>
    `;
}

// Call updateSummary initially to set the summary
updateSummary();

// Function to observe input changes and update spans
function observeInputs() {
    const inputs = document.querySelectorAll('input'); // Select all input fields

    inputs.forEach(input => {
        input.addEventListener('input', () => {
            // Log the input name and value for debugging
            console.log(`Input Changed: ${input.name}, Value: ${input.value}, Previous Value: ${input.getAttribute('data-previousvalue')}`);

            // Get the value from the data-previousvalue attribute
            const prevValue = input.getAttribute('data-previousvalue');

            // Update the corresponding span based on the input's name
            switch (input.name) {
                case 'total-area':
                    document.querySelector('.tile-calculator-outcome-total-area .div .cff-summary-item .summary-field-value').textContent = prevValue;
                    break;
                case 'total-tiles':
                    document.querySelector('.tile-calculator-outcome-total-tiles .div .cff-summary-item .summary-field-value').textContent = prevValue;
                    break;
                case 'total-box':
                    document.querySelector('.tile-calculator-outcome-total-box .div .cff-summary-item .summary-field-value').textContent = prevValue;
                    break;
                case 'total-price':
                    document.querySelector('.tile-calculator-outcome-total-price .div .cff-summary-item .summary-field-value').textContent = prevValue;
                    break;
                default:
                    break;
            }

            // Update the summary after changing the spans
            updateSummary();
        });
    });
}

// Start observing all inputs on the page
observeInputs();