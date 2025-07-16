 // Function to filter products by category and price
    function applyFilters() {
        const selectedCategory = document.querySelector('.category-filter.active')?.getAttribute('data-category-id') || 'all';
        
        const minInput = document.querySelector('#min-price');
        const maxInput = document.querySelector('#max-price');

        const minPrice = parseFloat(minInput.value) || 0;
        const maxPrice = parseFloat(maxInput.value) || Infinity;

        const productCards = document.querySelectorAll('.product-card');

        productCards.forEach(card => {
            const categoryId = card.getAttribute('data-category-id');
            const productPrice = parseFloat(card.getAttribute('data-price'));

            // Check category
            const categoryMatch = (selectedCategory === 'all' || categoryId === selectedCategory);

            // Check price
            const priceMatch = productPrice >= minPrice && productPrice <= maxPrice;

            // Show or hide card
            card.style.display = (categoryMatch && priceMatch) ? 'block' : 'none';
        });
    }

    // Handle category filter clicks
    document.querySelectorAll('.category-filter').forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            document.querySelectorAll('.category-filter').forEach(el => el.classList.remove('active'));
            link.classList.add('active');
            applyFilters();
        });
    });

    // Apply price filter when "Apply" button is clicked
    document.querySelector('#price-filter-btn').addEventListener('click', () => {
        applyFilters();
    });

    // Optional: Filter on price input typing
    document.querySelectorAll('#min-price, #max-price').forEach(input => {
        input.addEventListener('input', () => {
            applyFilters();
        });
    });

    // Apply filters on page load
    window.addEventListener('DOMContentLoaded', applyFilters);