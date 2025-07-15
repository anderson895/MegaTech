<?php 
include "components/header.php";

?>
<div class="flex justify-between items-center bg-white p-4 mb-6 rounded-md shadow-md">
    <h2 class="text-lg font-semibold text-gray-700">Customer</h2>
    <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center text-lg font-bold text-white">
        <?php echo substr(ucfirst($_SESSION['admin_username']), 0, 1); ?>
    </div>
</div>

<!-- Card for Table -->
<div class="bg-white rounded-lg shadow-lg p-6">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-xl font-semibold text-gray-700">Product List</h3>
        <!-- Add Product Button -->
        <button id="addProductButton" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
            Add Product
        </button>
    </div>

    <!-- Search Bar -->
    <div class="mb-4">
        <input
            type="text"
            id="searchInput"
            class="w-50 px-4 py-2 border rounded-md text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
            placeholder="Search products..."
        />
    </div>

    <!-- Table Wrapper for Responsiveness -->
    <div class="overflow-x-auto">
        <table id="userTable" class="display table-auto w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="p-2">CODE</th>
                    <th class="p-2">Image</th>
                    <th class="p-2">Product</th>
                    <th class="p-2">Description</th>
                    <th class="p-2">Specs</th>
                    <th class="p-2">Stock</th>
                    <th class="p-2">Category</th>
                    <th class="p-2">Price</th>
                    <th class="p-2">Status</th>
                    <th class="p-2">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php include "backend/end-points/product_list.php" ?>
            </tbody>
        </table>
    </div>
</div>




<!-- Modal -->
<div id="AddproductModal" class="fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50" style="display: none;">
    <div class="bg-white w-full max-w-2xl rounded-xl shadow-lg p-6 max-h-[90vh] overflow-y-auto">

        <!-- Title -->
        <h2 class="text-xl font-semibold text-gray-800 mb-6">Add New Product</h2>

        <!-- Form -->
        <form id="frmAddProduct" class="space-y-5">
            <!-- Product Code -->
            <div>
                <label class="text-sm text-gray-600 mb-1 block">Product Code</label>
                <input type="text" name="product_Code" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500" required>
            </div>

            <!-- Product Name -->
            <div>
                <label class="text-sm text-gray-600 mb-1 block">Product Name</label>
                <input type="text" name="product_Name" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500" required>
            </div>

            <!-- Price & Critical Level -->
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <label class="text-sm text-gray-600 mb-1 block">Price</label>
                    <input type="text" name="product_Price" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm" required>
                </div>
                <div class="flex-1">
                    <label class="text-sm text-gray-600 mb-1 block">Critical Level</label>
                    <input type="number" name="critical_Level" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm" required>
                </div>
            </div>

            <!-- Category -->
            <div>
                <label class="text-sm text-gray-600 mb-1 block">Category</label>
                <select name="product_Category" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm bg-white" required>
                    <option disabled selected>Select a Category</option>
                    <?php $fetch_all_category = $db->fetch_all_category();
                        if ($fetch_all_category): 
                            foreach ($fetch_all_category as $category): ?>
                                <option value="<?=$category['category_id']?>"><?=$category['category_name']?></option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option disabled>No record found.</option>
                        <?php endif; ?>
                </select>
            </div>

            <!-- Description -->
            <div>
                <label class="text-sm text-gray-600 mb-1 block">Description</label>
                <textarea name="product_Description" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm"></textarea>
            </div>

            <!-- Stocks & Image -->
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <label class="text-sm text-gray-600 mb-1 block">Stocks</label>
                    <input type="number" name="product_Stocks" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm" required>
                </div>
                <div class="flex-1">
                    <label class="text-sm text-gray-600 mb-1 block">Image</label>
                    <input type="file" name="product_Image" accept="image/*" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm" required>
                </div>
            </div>

            <!-- Specifications -->
            <div>
                <label class="text-sm text-gray-600 block mb-2">Specifications</label>
                <div id="sizesList" class="space-y-2">
                    <div class="flex gap-2 items-center">
                        <input type="text" name="specs_name[]" placeholder="Specs Name (e.g. Size)" class="w-1/2 px-3 py-2 border border-gray-300 rounded-md text-sm">
                        <input type="text" name="specs_value[]" placeholder="Specs Value (e.g. 10 feet)" class="w-1/2 px-3 py-2 border border-gray-300 rounded-md text-sm">
                        <button type="button" class="remove-spec-btn text-red-500 hover:text-red-700">
                            <span class="material-icons">close</span>
                        </button>
                    </div>
                </div>
                <button type="button" id="addSpecsButton" class="mt-2 text-sm text-blue-600 hover:underline">+ Add Specs</button>
            </div>

            <!-- Actions -->
            <div class="flex justify-end gap-2 pt-4 border-t border-gray-200">
                <button type="button" id="closeModalButton" class="text-sm px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">Cancel</button>
                <button type="submit" class="text-sm px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Add Product</button>
            </div>
        </form>
    </div>
</div>



<script>
function updateRemoveButtonsVisibility() {
    const specRows = document.querySelectorAll('#sizesList > div');
    specRows.forEach((row, index) => {
        const removeBtn = row.querySelector('button');
        if (removeBtn) {
            removeBtn.style.display = specRows.length > 1 ? 'inline-flex' : 'none';
        }
    });
}

document.getElementById('addSpecsButton').addEventListener('click', function () {
    const sizeContainer = document.getElementById('sizesList');

    const specsGroup = document.createElement('div');
    specsGroup.className = 'flex gap-2 mb-2 items-center';

    const nameInput = document.createElement('input');
    nameInput.type = 'text';
    nameInput.name = 'specs_name[]';
    nameInput.placeholder = 'Specs Name (e.g. Size)';
    nameInput.className = 'w-1/2 px-3 py-2 border border-gray-300 rounded-md text-sm';

    const valueInput = document.createElement('input');
    valueInput.type = 'text';
    valueInput.name = 'specs_value[]';
    valueInput.placeholder = 'Specs Value (e.g. 10 feet)';
    valueInput.className = 'w-1/2 px-3 py-2 border border-gray-300 rounded-md text-sm';

    const removeButton = document.createElement('button');
    removeButton.type = 'button';
    removeButton.innerHTML = '<span class="material-icons text-lg">close</span>';
    removeButton.className = 'text-red-500 hover:text-red-700 ml-2';

    removeButton.addEventListener('click', function () {
        specsGroup.remove();
        updateRemoveButtonsVisibility();
    });

    specsGroup.appendChild(nameInput);
    specsGroup.appendChild(valueInput);
    specsGroup.appendChild(removeButton);
    sizeContainer.appendChild(specsGroup);

    updateRemoveButtonsVisibility(); // Refresh visibility
});

// For default spec row after DOM is loaded
document.addEventListener('DOMContentLoaded', function () {
    const defaultRemoveBtn = document.querySelector('#sizesList button');
    if (defaultRemoveBtn) {
        defaultRemoveBtn.addEventListener('click', function () {
            const specsRow = this.parentElement;
            specsRow.remove();
            updateRemoveButtonsVisibility();
        });
    }
    updateRemoveButtonsVisibility(); // Initial check
});
// jQuery for filtering the table based on search input
    $(document).ready(function () {
        $("#searchInput").on("keyup", function () {
            var value = $(this).val().toLowerCase();
            $("#userTable tbody tr").filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });
    });


   
</script>










<!-- Modal -->
<div id="UpdateProductModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center" style="display:none;">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full sm:max-w-3xl max-h-[90vh] overflow-y-auto flex flex-col justify-between">

     <!-- Spinner -->
    <div class="spinner" style="display:none;">
        <div class=" absolute inset-0 bg-white bg-opacity-75 flex items-center justify-center">
          <div class="w-10 h-10 border-4 border-indigo-500 border-t-transparent rounded-full animate-spin"></div>
        </div>
     </div>

        <h2 class="text-2xl font-semibold text-gray-700 mb-4">Update Product</h2>
        <!-- Modal Form for Adding Product -->
        <form id="frmUpdateProduct">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Left Column -->

                <div class="mb-4" hidden>
                    <label for="product_id_update" class="block text-gray-700">Product ID</label>
                    <input type="text" id="product_id_update" name="product_id_update" class="w-full p-2 border border-gray-300 rounded-md" required>
                </div>

                <div class="mb-4">
                    <label for="product_Code_update" class="block text-gray-700">Product Code</label>
                    <input type="text" id="product_Code_update" name="product_Code_update" class="w-full p-2 border border-gray-300 rounded-md" required>
                </div>

                <div class="mb-4">
                    <label for="product_Name_update" class="block text-gray-700">Product Name</label>
                    <input type="text" id="product_Name_update" name="product_Name_update" class="w-full p-2 border border-gray-300 rounded-md" required>
                </div>

                <div class="mb-4">
                    <label for="product_Price_update" class="block text-gray-700">Price</label>
                    <input type="text" id="product_Price_update" name="product_Price_update" class="w-full p-2 border border-gray-300 rounded-md" required>
                </div>

                <div class="mb-4">
                    <label for="critical_Level_update" class="block text-gray-700">Critical Level</label>
                    <input type="number" id="critical_Level_update" name="critical_Level_update" class="w-full p-2 border border-gray-300 rounded-md" required>
                </div>

                <div class="mb-4">
                    <label for="product_Category_update" class="block text-sm font-medium text-gray-700">Choose a Category</label>
                    <select id="product_Category_update" name="product_Category_update" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                        <option value="" disabled selected>Select a Category</option>
                        <?php $fetch_all_category = $db->fetch_all_category();
                            if ($fetch_all_category): 
                                foreach ($fetch_all_category as $category): ?>
                                    <option value="<?=$category['category_id']?>"><?=$category['category_name']?></option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="" disabled>No record found.</option>
                            <?php endif; ?>
                    </select>
                </div>

                <!-- Right Column -->
                <div class="mb-4">
                    <label for="product_Description_update" class="block text-gray-700">Description</label>
                    <textarea id="product_Description_update" name="product_Description_update" class="w-full p-2 border border-gray-300 rounded-md"></textarea>
                </div>

               

                <div class="mb-4">
                    <label for="product_Image_update" class="block text-gray-700">Image</label>
                    <input type="file" id="product_Image_update" name="product_Image_update" class="w-full p-2 border border-gray-300 rounded-md" accept="image/*" >
                </div>


               
            </div>

            <div class="flex justify-end space-x-4 mt-4">
                <button type="button" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 closeModal">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Update Product</button>
            </div>
        </form>
    </div>
</div>




                

<!-- Modal -->
<div id="StockInModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50" style="display:none;">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full sm:w-[600px] max-h-[90vh] overflow-y-auto flex flex-col animate__animated animate__fadeIn">



        <!-- Spinner -->
        <div class="spinner" style="display:none;">
            <div class="absolute inset-0 bg-white bg-opacity-75 flex items-center justify-center">
                <div class="w-12 h-12 border-4 border-indigo-500 border-t-transparent rounded-full animate-spin"></div>
            </div>
        </div>

        <h2 class="text-3xl font-semibold text-gray-800 mb-6 text-center">Update Stocks For <span id="stockinTarget"></span></h2>

        <!-- Modal Form for Adding Product -->
        <form id="frmUpdateStock" class="flex flex-col items-center w-full">

                <div class="mb-6" hidden>
                    <label for="product_id_stockin" class="block text-gray-700">Product ID</label>
                    <input type="text" id="product_id_stockin" name="product_id_stockin" class="w-full p-3 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" required>
                    <input type="text" id="product_name_stockin" name="product_name_stockin" class="w-full p-3 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" required>
                </div>

            <div class="mb-6 w-full flex justify-center">
                <label for="stockin_qty" class="block text-lg font-medium text-gray-700 mb-2">Current Stock: <span id="product_stocks"></span></label>
            </div>
            <div class="mb-6 w-full flex justify-center">
                <input type="number" id="stockin_qty" name="stockin_qty" class="w-full sm:w-3/4 p-4 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-lg" required>
            </div>

            <div class="flex justify-center gap-4 mt-6 w-full">
                <button type="button" id="StockInModalClose" class="px-6 py-3 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition duration-300 ease-in-out w-full sm:w-auto text-lg">Cancel</button>
                <button type="submit" class="px-6 py-3 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition duration-300 ease-in-out w-full sm:w-auto text-lg">Add Stocks</button>
            </div>
        </form>
    </div>
</div>









<?php include "components/footer.php";?>