// Function to filter products by category and price
function applyFilters() {
    const selectedCategory = document.querySelector('.category-filter.active')?.getAttribute('data-category-id') || 'all';
    const selectedPriceRange = document.querySelector('.price-filter:checked')?.getAttribute('data-price-range') || 'all';
    const productCards = document.querySelectorAll('.product-card');

    productCards.forEach(card => {
        const categoryId = card.getAttribute('data-category-id');
        const productPrice = parseFloat(card.getAttribute('data-price'));

        // Check category
        const categoryMatch = (selectedCategory === 'all' || categoryId === selectedCategory);

        // Check price
        let priceMatch = true;
        if (selectedPriceRange !== 'all') {
            const [minPrice, maxPrice] = selectedPriceRange.split('-').map(Number);
            priceMatch = productPrice >= minPrice && productPrice <= maxPrice;
        }

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

// Handle price filter changes
document.querySelectorAll('.price-filter').forEach(radio => {
    radio.addEventListener('change', () => {
        applyFilters();
    });
});

// Apply filters on page load
window.addEventListener('DOMContentLoaded', applyFilters);
