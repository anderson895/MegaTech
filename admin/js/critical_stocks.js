
 let notifiedProducts = [];

    function checkLowStock() {
        $.ajax({
            url: "backend/end-points/check_low_stock.php",
            method: 'GET',
            dataType: 'json',
            success: function (products) {
                products.forEach(product => {
                    if (!notifiedProducts.includes(product.prod_id)) {
                        showPushNotification(product);
                        notifiedProducts.push(product.prod_id);
                    }
                });
            },
            error: function () {
                console.error("Failed to fetch low stock products.");
            }
        });
    }

    function showPushNotification(product) {
       const notif = $(`
            <div class="fixed bottom-4 right-4 bg-red-100 border-l-4 border-red-600 text-red-800 p-4 rounded shadow-lg w-80 z-50 animate-bounce">
                <strong class="block mb-1">⚠ Low Stock Alert!</strong>
                <span>Product <strong>${product.prod_name}</strong> is below critical level.</span><br>
                <span>Stock: ${product.prod_stocks} | Critical: ${product.prod_critical}</span>

                <div class="mt-3 flex justify-between items-center space-x-2">
                    <button class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 text-sm closeNotif">
                        Dismiss
                    </button>
                    <a href="inventory" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">
                        Go to Inventory
                    </a>
                </div>
            </div>
        `);


        $('body').append(notif);

        notif.find('.closeNotif').on('click', function () {
            notif.fadeOut(300, function () { $(this).remove(); });
        });

        setTimeout(() => {
            notif.fadeOut(500, function () { $(this).remove(); });
        }, 8000);
    }

    // Check every 10 seconds
    setInterval(checkLowStock, 10000);

    // Initial check
    checkLowStock();
