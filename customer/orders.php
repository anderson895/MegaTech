<?php
include "components/header.php";

$result = $db->fetch_list_order($user_id);
?>

<div class="max-w-6xl mx-auto mt-10 p-4 bg-white shadow-lg rounded-xl">
    <h2 class="text-2xl font-bold mb-6 text-gray-700">Your Orders</h2>

    <?php if (!empty($result)) : ?>
        <div class="overflow-x-auto">
            <table class="min-w-full text-left border border-gray-200">
                <thead class="bg-gray-100 text-gray-800 font-semibold">
                    <tr>
                        <th class="py-3 px-4 border-b">Order Code</th>
                        <th class="py-3 px-4 border-b">Pickup Schedule</th>
                        <th class="py-3 px-4 border-b">Total</th>
                        <th class="py-3 px-4 border-b">Status</th>
                        <th class="py-3 px-4 border-b text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    <?php foreach ($result as $order): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="py-2 px-4 border-b"><?= htmlspecialchars($order['order_code']) ?></td>

                            <td class="py-2 px-4 border-b">
                                <?php if (!empty($order['order_pickup_date']) && $order['order_pickup_date'] !== '0000-00-00' && 
                                          !empty($order['order_pickup_time']) && $order['order_pickup_time'] !== '00:00:00'): ?>
                                    <?php
                                        $pickupDateTime = new DateTime("{$order['order_pickup_date']} {$order['order_pickup_time']}");
                                        echo $pickupDateTime->format('l, F j, Y - g:i A');
                                    ?>
                                <?php else: ?>
                                    No date schedule
                                <?php endif; ?>
                            </td>

                            <td class="py-2 px-4 border-b">₱<?= number_format($order['order_total'], 2) ?></td>

                            <td class="py-2 px-4 border-b">
                                <i><?= $order['order_status'] === 'pending' ? 'Waiting Approval' : htmlspecialchars($order['order_status']) ?></i>
                            </td>

                            <td class="py-2 px-4 border-b text-center">
                                <a href="reservation_receipt?order_id=<?= $order['order_id'] ?>" 
                                   class="inline-block bg-blue-600 hover:bg-blue-700 text-white text-sm px-3 py-1 rounded">
                                    View Receipt
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="text-gray-500">No orders found.</p>
    <?php endif; ?>
</div>

<?php include "components/footer.php"; ?>
<script src="../function/js/filter_price_category.js"></script>
