<?php
include "components/header.php";
include('backend/class.php');

$db = new global_class();
?>
<div class="container mx-auto px-4 py-6">
    <button 
        id="hamburger-btn" 
        class="lg:hidden p-2 bg-gray-800 text-white rounded hover:bg-gray-700 focus:outline-none focus:ring"
    >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>

    <div class="flex">
       <!-- Sidebar Filters -->
<aside 
    id="sidebar" 
    class="fixed top-0 left-0 w-64 h-full bg-white shadow-lg transform -translate-x-full lg:relative lg:translate-x-0 lg:w-1/4 lg:h-auto p-6 transition-transform duration-300 ease-in-out z-50 space-y-6"
>
    <!-- Close Button (for mobile) -->
    <button 
        id="close-sidebar" 
        class="lg:hidden p-2 text-gray-500 hover:text-gray-700 focus:outline-none"
    >
        <span class="material-icons">close</span>
    </button>

    <!-- Category Filter -->
    <div>
        <h2 class="text-lg font-semibold text-gray-800 mb-3">Categories</h2>
        <ul id="category-list" class="space-y-1">
            <li>
                <a href="#" class="block px-3 py-2 rounded text-gray-700 hover:bg-gray-100 transition category-filter" data-category-id="all">
                    All Categories
                </a>
            </li>
            <?php 
            $categories = $db->fetch_all_categories(); 
            foreach ($categories as $category):
                echo ' 
                    <li>
                        <a href="#" class="block px-3 py-2 rounded text-gray-700 hover:bg-gray-100 transition category-filter" data-category-id="'.$category['category_id'].'">
                            '.ucfirst($category['category_name']).' 
                        </a>
                    </li>';
            endforeach;
            ?>
        </ul>
    </div>

    <!-- Price Filter -->
    <div>
        <h2 class="text-lg font-semibold text-gray-800 mb-3">Price</h2>
        <div class="space-y-3">
            <div class="flex flex-col space-y-2">
                <div class="flex items-center justify-between space-x-2">
                    <label class="flex-1">
                        <span class="block text-sm text-gray-600 mb-1">Min</span>
                        <input type="number" id="min-price" class="w-full border rounded px-2 py-1 text-sm focus:outline-none focus:ring focus:border-blue-300" placeholder="e.g. 1000">
                    </label>
                    <label class="flex-1">
                        <span class="block text-sm text-gray-600 mb-1">Max</span>
                        <input type="number" id="max-price" class="w-full border rounded px-2 py-1 text-sm focus:outline-none focus:ring focus:border-blue-300" placeholder="e.g. 5000">
                    </label>
                </div>
                <button id="price-filter-btn" class="w-full bg-blue-500 text-white text-sm font-medium py-2 rounded hover:bg-blue-600 transition">
                    Apply
                </button>
            </div>
        </div>
    </div>
</aside>


        <!-- Product Grid -->
        <main class="w-full lg:w-3/4 p-4 ml-auto">
            <?php include "backend/end-points/product_list.php";?>
        </main>
    </div>
</div>

<script>
    // JavaScript to toggle the sidebar
    document.getElementById('hamburger-btn').addEventListener('click', function () {
        document.getElementById('sidebar').classList.remove('-translate-x-full');
    });

    document.getElementById('close-sidebar').addEventListener('click', function () {
        document.getElementById('sidebar').classList.add('-translate-x-full');
    });

    // Close sidebar on overlay click (optional)
    document.addEventListener('click', function (e) {
        const sidebar = document.getElementById('sidebar');
        const hamburger = document.getElementById('hamburger-btn');
        if (!sidebar.contains(e.target) && !hamburger.contains(e.target)) {
            sidebar.classList.add('-translate-x-full');
        }
    });
</script>


<?php include "components/footer.php"; ?>

<script src="function/js/filter_price_category.js"></script>