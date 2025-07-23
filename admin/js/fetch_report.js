function loadSalesReport() {
    $.ajax({
        url: 'backend/end-points/get_sales_report.php', 
        method: 'GET',
        dataType: 'json',
        success: function (data) {
            let tbody = $('#sales #userTable tbody');
            tbody.empty(); 

            if (data.length > 0) {
                data.forEach(item => {
                    tbody.append(`
                        <tr class="border-t">
                            <td class="p-2">${item.id}</td>
                            <td class="p-2">${item.order_code}</td>
                            <td class="p-2">₱ ${item.order_total}</td>
                            <td class="p-2">${item.order_date}</td>
                        </tr>
                    `);
                });
            } else {
                tbody.append(`
                    <tr><td colspan="4" class="p-4 text-center text-gray-500">No sales data found.</td></tr>
                `);
            }
        },
        error: function () {
            alert('Failed to fetch sales report.');
        }
    });
}

// Auto-load when Sales tab is clicked
$('button[data-tab="sales"]').on('click', function () {
    loadSalesReport();
});

// Optionally load immediately if "Sales" is default visible
$(document).ready(function () {
    loadSalesReport();
});











function loadInventoryReport() {
    $.ajax({
        url: 'backend/end-points/get_inventory_report.php',
        method: 'GET',
        dataType: 'json',
        success: function (data) {
            let tbody = $('#inventory tbody');
            tbody.empty();

            if (data.length > 0) {
                data.forEach(item => {
                    tbody.append(`
                        <tr class="border-t">
                            <td class="p-2">${item.date}</td>
                            <td class="p-2">${item.supplier_name}</td>
                            <td class="p-2">₱ ${item.supplier_price}</td>
                            <td class="p-2">${item.product_name}</td>
                            <td class="p-2">${item.stock_qty}</td>
                            <td class="p-2">${item.changes}</td>
                        </tr>
                    `);
                });
            } else {
                tbody.append(`
                    <tr><td colspan="6" class="p-4 text-center text-gray-500">No inventory data found.</td></tr>
                `);
            }
        },
        error: function () {
            alert('Failed to fetch inventory report.');
        }
    });
}

// Load when "Inventory" tab is clicked
$('button[data-tab="inventory"]').on('click', function () {
    loadInventoryReport();
});

// Optional: Load immediately if inventory tab is default
$(document).ready(function () {
    loadInventoryReport();
});





















function loadDefective_report() {
    $.ajax({
        url: 'backend/end-points/get_defective_report.php',
        method: 'GET',
        dataType: 'json',
        success: function (data) {
            let tbody = $('#defective tbody');
            tbody.empty();

            if (data.length > 0) {
                data.forEach(item => {
                    tbody.append(`
                        <tr class="border-t">
                            <td class="p-2">${item.date}</td>
                            <td class="p-2">${item.order_code}</td>
                            <td class="p-2">₱ ${item.prod_name}</td>
                            <td class="p-2">${item.return_qty}</td>
                            <td class="p-2">${item.return_reason}</td>
                        </tr>
                    `);
                });
            } else {
                tbody.append(`
                    <tr><td colspan="6" class="p-4 text-center text-gray-500">No data found.</td></tr>
                `);
            }
        },
        error: function () {
            alert('Failed to fetch report.');
        }
    });
}

$('button[data-tab="defective"]').on('click', function () {
    loadDefective_report();
});

$(document).ready(function () {
    loadDefective_report();
});


















function loadAllpickedupReport() {
    $.ajax({
        url: 'backend/end-points/get_allpickuped_report.php',
        method: 'GET',
        dataType: 'json',
        success: function (data) {
            let tbody = $('#pickuped tbody');
            tbody.empty();

            if (data.length > 0) {
                data.forEach(item => {
                    tbody.append(`
                        <tr class="border-t">
                            <td class="p-2">${item.date}</td>
                            <td class="p-2">${item.order_code}</td>
                            <td class="p-2">${item.Fullname}</td>
                        </tr>
                    `);
                });
            } else {
                tbody.append(`
                    <tr><td colspan="6" class="p-4 text-center text-gray-500">No inventory data found.</td></tr>
                `);
            }
        },
        error: function () {
            alert('Failed to fetch inventory report.');
        }
    });
}

$('button[data-tab="inventory"]').on('click', function () {
    loadAllpickedupReport();
});

$(document).ready(function () {
    loadAllpickedupReport();
});
