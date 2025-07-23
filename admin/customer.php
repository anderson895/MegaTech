<?php 
include "components/header.php";
?>

<!-- Top Bar -->
<div class="flex justify-between items-center bg-white p-4 mb-6 rounded-md shadow shadow-gray-200">
    <h2 class="text-xl font-semibold text-gray-800">Customer</h2>
    <div class="w-10 h-10 bg-indigo-500 rounded-full flex items-center justify-center text-white font-bold">
        <?php echo strtoupper(substr($_SESSION['admin_username'], 0, 1)); ?>
    </div>
</div>

<!-- Card for Table -->
<div class="bg-white rounded-lg shadow-lg p-6">

    <!-- Search Input -->
    <div class="mb-4">
        <input type="text" id="searchInput" class="p-2 border rounded-md" placeholder="Search customers...">
    </div>

    <!-- Table Wrapper for Responsiveness -->
    <div class="overflow-x-auto">
        <table id="userTable" class="display table-auto w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="p-2">ID</th>
                    <th class="p-2">Fullname</th>
                    <th class="p-2">Email</th>
                    <th class="p-2">Phone</th>
                    <th class="p-2">Type</th>
                    <th class="p-2">Status</th>
                    <th class="p-2 text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php include "backend/end-points/customers_list.php"; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include "components/footer.php";?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Search functionality
        $('#searchInput').on('input', function() {
            var value = $(this).val().toLowerCase();
            $('#userTable tbody tr').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });
    });
</script>