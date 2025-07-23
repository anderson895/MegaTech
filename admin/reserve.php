<?php 
include "components/header.php";
?>

<!-- Top Bar -->
<div class="flex justify-between items-center bg-white p-4 mb-6 rounded-md shadow shadow-gray-200">
    <h2 class="text-xl font-semibold text-gray-800">Reservation</h2>
    <div class="w-10 h-10 bg-indigo-500 rounded-full flex items-center justify-center text-white font-bold">
        <?php echo strtoupper(substr($_SESSION['admin_username'], 0, 1)); ?>
    </div>
</div>

<!-- Status Filter Tabs -->
<div class="mb-6 border-b border-gray-200">
  <ul class="flex flex-wrap text-sm font-medium text-center" id="statusFilterTabs">
    <li class="mr-2">
      <button class="status-button px-4 py-2 rounded-t-md bg-indigo-100 text-indigo-700 font-semibold" data-status="">All</button>
    </li>
    <li class="mr-2">
      <button class="status-button px-4 py-2 rounded-t-md hover:bg-gray-100" data-status="pending">Pending</button>
    </li>
    <li class="mr-2">
      <button class="status-button px-4 py-2 rounded-t-md hover:bg-gray-100" data-status="paid">Paid</button>
    </li>
    <li class="mr-2">
      <button class="status-button px-4 py-2 rounded-t-md hover:bg-gray-100" data-status="decline">Decline</button>
    </li>
    <li class="mr-2">
      <button class="status-button px-4 py-2 rounded-t-md hover:bg-gray-100" data-status="done">Pickedup</button>
    </li>
    <li class="mr-2">
      <button class="status-button px-4 py-2 rounded-t-md hover:bg-gray-100" data-status="scheduled">Scheduled</button>
    </li>
  </ul>
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
                    <th class="p-2">CODE</th>
                    <th class="p-2">NAME</th>
                    <th class="p-2">DATE</th>
                    <th class="p-2">MOP</th>
                    <th class="p-2">TOTAL</th>
                    <th class="p-2">STATUS</th>
                    <th class="p-2 text-center">ACTION</th>
                </tr>
            </thead>
            <tbody>
                <?php include "backend/end-points/reservation_list.php"; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include "components/footer.php";?>


<script src="../function/js/filter_tab.js"></script>


<script>
    $(document).ready(function() {
        $('#searchInput').on('input', function() {
            var value = $(this).val().toLowerCase();
            $('#userTable tbody tr').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });
    });
</script>