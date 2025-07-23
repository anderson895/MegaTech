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
            <button class="tab-button px-4 py-2 rounded-t-md hover:bg-gray-100" data-tab="pickuped">Pickup Order</button>
        </li>
    </ul>
</div>

<!-- Search Input -->
<div class="mb-4">
    <input type="text" id="searchInput" placeholder="Search..." class="w-full p-2 border rounded-md shadow-sm focus:outline-none focus:ring focus:ring-indigo-300">
</div>

<!-- Sales Report -->
<div id="sales" class="report-tab block">
    <div class="flex justify-between items-center mb-2">
        <h3 class="text-md font-semibold text-indigo-600">Sales Report</h3>
        <button onclick="printReport('sales')" class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-1 rounded text-sm">Print</button>
    </div>
    <div class="overflow-auto rounded shadow bg-white p-2">
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

<!-- Inventory Report -->
<div id="inventory" class="report-tab hidden">
    <div class="flex justify-between items-center mb-2">
        <h3 class="text-md font-semibold text-indigo-600">Inventory Report</h3>
        <button onclick="printReport('inventory')" class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-1 rounded text-sm">Print</button>
    </div>
    <div class="overflow-auto rounded shadow bg-white p-2">
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
                    <td class="p-2">J Supply</td>
                    <td class="p-2">₱ 99.00</td>
                    <td class="p-2">Sample Item</td>
                    <td class="p-2">2</td>
                    <td class="p-2">143 -> 145</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Defective Report -->
<div id="defective" class="report-tab hidden">
    <div class="flex justify-between items-center mb-2">
        <h3 class="text-md font-semibold text-red-600">Defective Items Report</h3>
        <button onclick="printReport('defective')" class="bg-red-500 hover:bg-red-600 text-white px-4 py-1 rounded text-sm">Print</button>
    </div>
    <div class="overflow-x-auto rounded-lg shadow bg-white p-2 ring-1 ring-gray-200">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-red-100 text-red-800">
                <tr>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Reported Date</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Order Code</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Item</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Quantity</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Reason</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <tr class="hover:bg-red-50 transition-colors duration-200">
                    <td class="px-4 py-2 text-sm text-gray-700">2025-07-20</td>
                    <td class="px-4 py-2 text-sm text-gray-700">ORD-00123</td>
                    <td class="px-4 py-2 text-sm text-gray-700">Glass</td>
                    <td class="px-4 py-2 text-sm text-gray-700">2</td>
                    <td class="px-4 py-2 text-sm text-gray-700">Cracked during shipment</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Pickup Report -->
<div id="pickuped" class="report-tab hidden">
    <div class="flex justify-between items-center mb-2">
        <h3 class="text-md font-semibold text-green-600">Pick up Receipts</h3>
        <button onclick="printReport('pickuped')" class="bg-green-500 hover:bg-green-600 text-white px-4 py-1 rounded text-sm">Print</button>
    </div>
    <div class="overflow-auto rounded shadow bg-white p-2">
        <table class="min-w-full bg-white border">
            <thead class="bg-green-100 text-green-800">
                <tr>
                    <th class="text-left p-2">Date</th>
                    <th class="text-left p-2">Order Code</th>
                    <th class="text-left p-2">Delivered To</th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-t">
                    <td class="p-2">2025-07-21</td>
                    <td class="p-2">DR-2025-001</td>
                    <td class="p-2">Juan Dela Cruz</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<?php include "components/footer.php"; ?>
<script src="js/fetch_report.js"></script>

<script>
    $('#searchInput').on('input', function () {
        var value = $(this).val().toLowerCase();
        $('table:visible tbody tr').filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });

    $('.tab-button').on('click', function () {
        const tab = $(this).data('tab');
        $('.report-tab').addClass('hidden');
        $('#' + tab).removeClass('hidden');
        $('.tab-button').removeClass('bg-indigo-100 text-indigo-700 font-semibold');
        $(this).addClass('bg-indigo-100 text-indigo-700 font-semibold');
    });

    function printReport(tabId) {
        const reportElement = document.getElementById(tabId);
        const reportTitle = reportElement.querySelector('h3')?.textContent || "Report";
        const today = new Date().toLocaleDateString();

        const printableContent = `
            <html>
            <head>
                <title>${reportTitle}</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        margin: 40px;
                        color: #000;
                    }
                    h1 {
                        text-align: center;
                        font-size: 22px;
                        margin-bottom: 5px;
                    }
                    h2 {
                        text-align: center;
                        font-size: 18px;
                        margin-bottom: 20px;
                        color: #555;
                    }
                    table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-top: 20px;
                    }
                    th, td {
                        border: 1px solid #333;
                        padding: 8px 12px;
                        text-align: left;
                        font-size: 13px;
                    }
                    th {
                        background-color: #f0f0f0;
                    }
                    .footer {
                        text-align: right;
                        font-size: 12px;
                        margin-top: 40px;
                        color: #555;
                    }
                    @media print {
                        button {
                            display: none;
                        }
                    }
                </style>
            </head>
            <body>
                <h1>MEGATECH</h1>
                <h2>${reportTitle} <br><small>Date: ${today}</small></h2>
                ${reportElement.querySelector('table').outerHTML}
                <div class="footer">Generated by Admin Panel</div>
            </body>
            </html>
        `;

        const printWindow = window.open('', '_blank');
        printWindow.document.open();
        printWindow.document.write(printableContent);
        printWindow.document.close();
        printWindow.print();
    }
</script>

