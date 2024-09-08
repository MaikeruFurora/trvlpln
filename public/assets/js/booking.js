
/**
 * 
 * BOOKING
 * 
 */

// Array to store product data

// Get the DOM elements
const searchInput = document.getElementById('product');
const qtyInput = document.getElementById('qty');
const priceInput = document.getElementById('price');
const suggestionsList = document.getElementById('suggestions_list');
const addProductButton = document.getElementById('addProduct');
const productTableBody = document.getElementById('productTable').getElementsByTagName('tbody')[0];

// Variable to store the selected product
let selectedProduct = null;

// Adjust the width of the suggestion list to match the input field
function adjustSuggestionWidth() {
    suggestionsList.style.width = `${searchInput.offsetWidth}px`;
}

// Search input listener
searchInput.addEventListener('input', function() {
    const query = searchInput.value;
    adjustSuggestionWidth(); // Adjust width when input is typed
    if (query.length > 1) { // Start searching after 2 characters
        fetch(`/auth/product/search?query=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                suggestionsList.innerHTML = '';
                if (data.length > 0) {
                    suggestionsList.style.display = 'block';
                    data.forEach(product => {
                        const item = document.createElement('li');
                        item.className = 'list-group-item list-group-item-action';
                        item.textContent = product.name;
                        item.dataset.id = product.id;
                        suggestionsList.appendChild(item);
                    });
                } else {
                    suggestionsList.style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Error fetching products:', error);
            });
    } else {
        suggestionsList.style.display = 'none';
    }
});

// Handle suggestion click
suggestionsList.addEventListener('click', function(event) {
    if (event.target.tagName === 'LI') {
        searchInput.value = event.target.textContent; // Set the input field to the clicked item
        selectedProduct = {
            id: event.target.dataset.id,
            name: event.target.textContent
        }; // Store the selected product details
        suggestionsList.style.display = 'none'; // Hide the suggestions list
    }
});

// Add product to table and array
function addProduct() {
    const qty = qtyInput.value;
    const price = priceInput.value;

    if (selectedProduct && qty && price) {
        CoreModel.booking.push({
            id: selectedProduct.id, 
            item: selectedProduct.name, 
            qty: qty, 
            price: price
        });
        renderTable();
        clearInputs();
    } else {
        alert('Please select a product and enter quantity and price');
    }
}

// Render table from CoreModel.booking array
function renderTable() {
    productTableBody.innerHTML = '';
    CoreModel.booking.forEach((product, index) => {
        const row = productTableBody.insertRow();
        row.insertCell(0).textContent = product.item;
        row.insertCell(1).textContent = product.qty;
        row.insertCell(2).textContent = product.price;

        const actionsCell = row.insertCell(3);
        actionsCell.innerHTML = `
            <button class="btn btn-dark btn-sm deleteBtn" data-index="${index}">Remove</button>
        `;
    });
    addRowEventListeners();
}

// Add event listeners to rows for removing CoreModel.booking
function addRowEventListeners() {
    const deleteButtons = document.querySelectorAll('.deleteBtn');
    deleteButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            const index = e.target.dataset.index;
            CoreModel.booking.splice(index, 1);
            renderTable();
        });
    });
}

// Clear input fields after adding a product
function clearInputs() {
    searchInput.value = '';
    qtyInput.value = '';
    priceInput.value = '';
    selectedProduct = null; // Clear the selected product
}

// Add product button click event
addProductButton.addEventListener('click', addProduct);
