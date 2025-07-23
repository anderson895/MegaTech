<?php include "components/header.php"; ?>

<!-- Top Bar -->
<div class="flex justify-between items-center bg-white p-4 mb-6 rounded-md shadow shadow-gray-200">
    <h2 class="text-xl font-semibold text-gray-800">Dashboard</h2>
    <div class="w-10 h-10 bg-indigo-500 rounded-full flex items-center justify-center text-white font-bold">
        <?php echo strtoupper(substr($_SESSION['admin_username'], 0, 1)); ?>
    </div>
</div>

<!-- Dashboard Stats Cards -->
<div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-5 gap-4 mb-6">
    <!-- Today Sales -->
    <div class="bg-white p-4 rounded-lg shadow hover:shadow-md transition">
        <h4 class="text-sm text-gray-500">Today Sales</h4>
        <p class="text-2xl font-bold text-green-600">₱2,300</p>
    </div>

    <!-- Monthly Sales -->
    <div class="bg-white p-4 rounded-lg shadow hover:shadow-md transition">
        <h4 class="text-sm text-gray-500">Monthly Sales</h4>
        <p class="text-2xl font-bold text-blue-600">₱43,000</p>
    </div>

    <!-- Yearly Sales -->
    <div class="bg-white p-4 rounded-lg shadow hover:shadow-md transition">
        <h4 class="text-sm text-gray-500">Yearly Sales</h4>
        <p class="text-2xl font-bold text-purple-600">₱520,000</p>
    </div>

    <!-- Total Customers -->
    <div class="bg-white p-4 rounded-lg shadow hover:shadow-md transition">
        <h4 class="text-sm text-gray-500">Total Customers</h4>
        <p class="text-2xl font-bold text-pink-600">1,420</p>
    </div>

    <!-- Total Products -->
    <div class="bg-white p-4 rounded-lg shadow hover:shadow-md transition">
        <h4 class="text-sm text-gray-500">Total Products</h4>
        <p class="text-2xl font-bold text-yellow-600">320</p>
    </div>
</div>

<!-- Sales Chart with Max Height -->
<div class="bg-white p-6 rounded-lg shadow-md min-h-[600px]">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Sales Overview</h3>
    <div id="salesChart" class="h-full"></div>
</div>

<!-- ApexCharts -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    var options = {
        chart: {
            type: 'line',
            height: 500, // Increased height
            toolbar: { show: false }
        },
        series: [{
            name: 'Sales',
            data: [2300, 4200, 3900, 5800, 6100, 6900, 7300]
        }],
        xaxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul']
        },
        colors: ['#3b82f6'],
        stroke: {
            width: 3,
            curve: 'smooth'
        },
        grid: {
            row: {
                colors: ['#f1f5f9', 'transparent'],
                opacity: 0.5
            }
        },
        markers: {
            size: 5,
            colors: ['#3b82f6'],
            strokeWidth: 2,
            strokeColors: '#fff',
            hover: { size: 7 }
        }
    };

    var chart = new ApexCharts(document.querySelector("#salesChart"), options);
    chart.render();
</script>

<?php include "components/footer.php"; ?>
