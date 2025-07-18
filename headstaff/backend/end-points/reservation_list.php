<?php 
$fetch_all_students = $db->fetch_all_reservation();

if ($fetch_all_students): ?>
    <?php foreach ($fetch_all_students as $student):

        $status_color = match ($student['order_status']) {
            "decline" => "text-red-500",
            "pending" => "text-yellow-500",
            default => "text-green-500"
        };

        $is_pending = strtolower($student['order_status']) === 'pending';
    ?>
    <tr>
        <td class="p-2"><?php echo htmlspecialchars($student['order_code']); ?></td>
        <td class="p-2"><?php echo htmlspecialchars(ucfirst($student['Fullname'])); ?></td>
        <td class="p-2"><?php echo htmlspecialchars($student['order_date']); ?></td>
        <td class="p-2"><?php echo htmlspecialchars($student['order_payment_method']); ?></td>
        <td class="p-2">₱ <?php echo htmlspecialchars(number_format($student['order_total'],2)); ?></td>
        <td class="p-2 <?= $status_color; ?>"><strong><?php echo ucfirst($student['order_status']); ?></strong></td>
        <td class="p-2 text-center">
            <div class="flex flex-col sm:flex-row justify-center items-center space-y-1 sm:space-y-0 sm:space-x-2">

                <a href="view_order?order_id=<?= $student['order_id']?>" 
                   class="bg-blue-600 hover:bg-blue-700 text-white py-1 px-3 rounded-md text-sm font-medium transition inline-flex items-center">
                    <span class="material-icons align-middle text-sm mr-1">visibility</span> View
                </a>

                <?php if ($is_pending): ?>
                    <button 
                        class="bg-green-600 hover:bg-green-700 text-white py-1 px-3 rounded-md text-sm font-medium transition userActionToggler"
                        data-user_id="<?= $student['user_id'] ?>"
                        data-order_id="<?= $student['order_id'] ?>"
                        data-order_code="<?= $student['order_code'] ?>"
                        data-fullname="<?= htmlspecialchars($student['Fullname'], ENT_QUOTES) ?>"
                        data-action="paid"
                    >
                        <span class="material-icons align-middle text-sm">check_circle</span> Mark as Paid
                    </button>

                    <button 
                        class="bg-red-600 hover:bg-red-700 text-white py-1 px-3 rounded-md text-sm font-medium transition userActionToggler"
                        data-user_id="<?= $student['user_id'] ?>"
                        data-order_id="<?= $student['order_id'] ?>"
                        data-order_code="<?= $student['order_code'] ?>"
                        data-fullname="<?= htmlspecialchars($student['Fullname'], ENT_QUOTES) ?>"
                        data-action="decline"
                    >
                        <span class="material-icons align-middle text-sm">block</span> Decline
                    </button>
                <?php endif; ?>

            </div>
        </td>
    </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="7" class="p-2 text-center">No record found.</td>
    </tr>
<?php endif; ?>
