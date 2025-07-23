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
        <p id="todaySales" class="text-2xl font-bold text-green-600">₱0.00</p>
    </div>

    <!-- Weekly Sales -->
    <div class="bg-white p-4 rounded-lg shadow hover:shadow-md transition">
        <h4 class="text-sm text-gray-500">Weekly Sales</h4>
        <p id="weeklySales" class="text-2xl font-bold text-purple-600">₱0.00</p>
    </div>

    <!-- Monthly Sales -->
    <div class="bg-white p-4 rounded-lg shadow hover:shadow-md transition">
        <h4 class="text-sm text-gray-500">Monthly Sales</h4>
        <p id="monthlySales" class="text-2xl font-bold text-blue-600">₱0.00</p>
    </div>

    <!-- Total Customers -->
    <div class="bg-white p-4 rounded-lg shadow hover:shadow-md transition">
        <h4 class="text-sm text-gray-500">Total Customers</h4>
        <p id="totalCustomers" class="text-2xl font-bold text-pink-600">0</p>
    </div>

    <!-- Total Products -->
    <div class="bg-white p-4 rounded-lg shadow hover:shadow-md transition">
        <h4 class="text-sm text-gray-500">Total Products</h4>
        <p id="totalProducts" class="text-2xl font-bold text-yellow-600">0</p>
    </div>
</div>

<!-- Sales Chart Section -->
<div class="bg-white p-6 rounded-lg shadow-md min-h-[600px]">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Sales Overview</h3>
    <div id="salesChart" class="h-full"></div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    let chart;

    function renderChart(categories, data) {
        const options = {
            chart: {
                type: 'line',
                height: 500,
                toolbar: { show: false }
            },
            series: [{
                name: 'Sales',
                data: data
            }],
            xaxis: {
                categories: categories
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

        if (chart) {
            chart.updateOptions({
                xaxis: { categories },
                series: [{ name: 'Sales', data }]
            });
        } else {
            chart = new ApexCharts(document.querySelector("#salesChart"), options);
            chart.render();
        }
    }

    function fetchAnalytics() {
        $.ajax({
            url: 'backend/end-points/getDataAnalytics.php',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                const formatCurrency = num => new Intl.NumberFormat('en-PH', {
                    style: 'currency',
                    currency: 'PHP'
                }).format(num ?? 0);

                $('#todaySales').text(formatCurrency(data.todaySales));
                $('#weeklySales').text(formatCurrency(data.weeklySales));
                $('#monthlySales').text(formatCurrency(data.monthlySales));
                $('#totalCustomers').text(data.userCount ?? 0);
                $('#totalProducts').text(data.productCount ?? 0);

                if (data.chartMonths && data.chartTotals) {
                    renderChart(data.chartMonths, data.chartTotals);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching analytics:', error);
            }
        });
    }

    // Initial fetch
    fetchAnalytics();

    // Realtime fetch every 10 seconds
    setInterval(fetchAnalytics, 10000);
</script>

<?php include "components/footer.php"; ?>
