<?php include "components/header.php"; ?>
<div class="bg-white p-4 mb-6 rounded-md shadow-md flex justify-between items-center">
    <h2 class="text-lg font-semibold text-gray-700">Reports</h2>
    <div class="w-10 h-10 bg-indigo-500 rounded-full flex items-center justify-center text-white font-bold">
        <?php echo substr(ucfirst($_SESSION['admin_username']), 0, 1); ?>
    </div>
</div>

<!-- Tabs -->
<div class="mb-4 border-b border-gray-200">
    <ul class="flex flex-wrap text-sm font-medium text-center" id="reportTabs">
        <li class="mr-2">
            <button class="tab-button px-4 py-2 rounded-t-md bg-indigo-100 text-indigo-700 font-semibold" data-tab="sales">Sales Report</button>
        </li>
        <li class="mr-2">
            <button class="tab-button px-4 py-2 rounded-t-md hover:bg-gray-100" data-tab="inventory">Inventory Report</button>
        </li>
        <li class="mr-2">
            <button class="tab-button px-4 py-2 rounded-t-md hover:bg-gray-100" data-tab="defective">Defective Items</button>
        </li>
        <li class="mr-2">
            <button class="tab-button px-4 py-2 rounded-t-md hover:bg-gray-100" data-tab="pickuped">Pickup Receipts</button>
        </li>
    </ul>
</div>

<!-- Search Input -->
<div class="mb-4">
    <input type="text" id="searchInput" placeholder="Search..." class="w-full p-2 border rounded-md shadow-sm focus:outline-none focus:ring focus:ring-indigo-300">
</div>

<!-- Report Tables -->
<div id="sales" class="report-tab block">
    <h3 class="text-md font-semibold mb-2 text-indigo-600">Sales Report</h3>
    <div class="overflow-auto rounded shadow">
        <table id="userTable" class="min-w-full bg-white border">
            <thead class="bg-indigo-100 text-indigo-800">
                <tr>
                    <th class="text-left p-2">#</th>
                    <th class="text-left p-2">Order Code</th>
                    <th class="text-left p-2">Total</th>
                    <th class="text-left p-2">Date</th>
                </tr>
            </thead>
            <tbody>
                <!-- Sample Data -->
                <tr class="border-t">
                    <td class="p-2">1</td>
                    <td class="p-2">ORD-00123</td>
                    <td class="p-2">₱ 1,200.00</td>
                    <td class="p-2">2025-07-23</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div id="inventory" class="report-tab hidden">
    <h3 class="text-md font-semibold mb-2 text-indigo-600">Inventory Report</h3>
    <div class="overflow-auto rounded shadow">
        <table class="min-w-full bg-white border">
            <thead class="bg-indigo-100 text-indigo-800">
                <tr>
                    <th class="text-left p-2">Date</th>
                    <th class="text-left p-2">Supplier Name</th>
                    <th class="text-left p-2">Supplier Price</th>
                    <th class="text-left p-2">Product</th>
                    <th class="text-left p-2">Stock Qty</th>
                    <th class="text-left p-2">Changes</th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-t">
                     <td class="p-2">Date</td>
                    <td class="p-2">J SUpply</td>
                    <td class="p-2">₱ 99.00</td>
                    <td class="p-2">Sample Item</td>
                    <td class="p-2">2</td>
                    <td class="p-2">143 -> 145</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div id="defective" class="report-tab hidden">
  <h3 class="text-md font-semibold mb-2 text-red-600">Defective Items Report</h3>
  
  <div class="overflow-x-auto rounded-lg shadow ring-1 ring-gray-200">
    <table class="min-w-full divide-y divide-gray-200 bg-white">
      <thead class="bg-red-100 text-red-800">
        <tr>
          <th class="px-4 py-3 text-left text-sm font-semibold">Reported Date</th>
          <th class="px-4 py-3 text-left text-sm font-semibold">Item</th>
          <th class="px-4 py-3 text-left text-sm font-semibold">Quantity</th>
          <th class="px-4 py-3 text-left text-sm font-semibold">Reason</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100">
        <tr class="hover:bg-red-50 transition-colors duration-200">
          <td class="px-4 py-2 text-sm text-gray-700">2025-07-20</td>
          <td class="px-4 py-2 text-sm text-gray-700">Glass</td>
          <td class="px-4 py-2 text-sm text-gray-700">2</td>
          <td class="px-4 py-2 text-sm text-gray-700">Cracked during shipment</td>
        </tr>
        <!-- Add more rows here -->
      </tbody>
    </table>
  </div>
</div>

<div id="pickuped" class="report-tab hidden">
    <h3 class="text-md font-semibold mb-2 text-green-600">Pick up Receipts</h3>
    <div class="overflow-auto rounded shadow">
        <table class="min-w-full bg-white border">
            <thead class="bg-green-100 text-green-800">
                <tr>
                    <th class="text-left p-2">Receipt No.</th>
                    <th class="text-left p-2">Delivered To</th>
                    <th class="text-left p-2">Date</th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-t">
                    <td class="p-2">DR-2025-001</td>
                    <td class="p-2">Juan Dela Cruz</td>
                    <td class="p-2">2025-07-21</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<?php include "components/footer.php"; ?>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Search filter
    $('#searchInput').on('input', function () {
        var value = $(this).val().toLowerCase();
        $('table:visible tbody tr').filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });

    // Tab switching
    $('.tab-button').on('click', function () {
        const tab = $(this).data('tab');
        $('.report-tab').addClass('hidden');
        $('#' + tab).removeClass('hidden');
        $('.tab-button').removeClass('bg-indigo-100 text-indigo-700 font-semibold');
        $(this).addClass('bg-indigo-100 text-indigo-700 font-semibold');
    });
</script>
