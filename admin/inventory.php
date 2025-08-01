<?php 
include "components/header.php";

?>
<!-- Top Bar -->
<div class="flex justify-between items-center bg-white p-4 mb-6 rounded-md shadow shadow-gray-200">
    <h2 class="text-xl font-semibold text-gray-800">Inventory</h2>
    <div class="w-10 h-10 bg-indigo-500 rounded-full flex items-center justify-center text-white font-bold">
        <?php echo strtoupper(substr($_SESSION['admin_username'], 0, 1)); ?>
    </div>
</div>
<!-- Card for Table -->
<div class="bg-white rounded-lg shadow-lg p-6">
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
                    <th class="p-2">Stock</th>
                    <th class="p-2">Critical level</th>
                    <th class="p-2">Current Price</th>
                    <th class="p-2">Status</th>
                    <th class="p-2 text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php include "backend/end-points/inventory_list.php" ?>
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
                    <label class="text-sm text-gray-600 mb-1 block">Supplier Price</label>
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

    updateRemoveButtonsVisibility(); 
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
    updateRemoveButtonsVisibility(); 
});
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
<div id="UpdateProductModal" class="fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50" style="display: none;">
    <div class="bg-white w-full max-w-2xl rounded-xl shadow-lg p-6 max-h-[90vh] overflow-y-auto">

        <!-- Title -->
        <h2 class="text-xl font-semibold text-gray-800 mb-6">Update Product</h2>

        <!-- Form -->
        <form id="frmUpdateProduct" class="space-y-5" enctype="multipart/form-data">
            <!-- Hidden Product ID -->
            <input type="hidden" name="product_ID" id="product_id_update">

            <!-- Product Code -->
            <div>
                <label class="text-sm text-gray-600 mb-1 block">Product Code</label>
                <input type="text" name="product_Code" id="product_Code_update" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm" required>
            </div>

            <!-- Product Name -->
            <div>
                <label class="text-sm text-gray-600 mb-1 block">Product Name</label>
                <input type="text" name="product_Name" id="product_Name_update" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm" required>
            </div>

            <!-- Price & Critical Level -->
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <label class="text-sm text-gray-600 mb-1 block">Price</label>
                    <input type="text" name="product_Price" id="product_Price_update" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm" required>
                </div>
                <div class="flex-1">
                    <label class="text-sm text-gray-600 mb-1 block">Critical Level</label>
                    <input type="number" name="critical_Level" id="critical_Level_update" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm" required>
                </div>
            </div>

            <!-- Category -->
            <div>
                <label class="text-sm text-gray-600 mb-1 block">Category</label>
                <select name="product_Category" id="product_Category_update" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm bg-white" required>
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
                <textarea name="product_Description" id="product_Description_update" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm"></textarea>
            </div>

            <!-- Stocks & Image -->
            <div class="flex flex-col sm:flex-row gap-4">


            
                <div class="flex-1">
                    <label class="text-sm text-gray-600 mb-1 block">Stocks</label>
                    <input type="number" name="product_Stocks" id="product_Stocks_update" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm" required>
                </div>



                <div class="flex-1">
                    <label class="text-sm text-gray-600 mb-1 block">Image</label>
                    <input type="file" name="product_Image" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm" accept="image/*">
                    <small class="text-xs text-gray-500">Leave blank if you don't want to change the image.</small>
                </div>
            </div>

            <!-- Specifications -->
            <div>
                <label class="text-sm text-gray-600 block mb-2">Specifications</label>
                <div id="specsListUpdate" class="space-y-2">
                    <!-- Specs will be appended dynamically here -->
                </div>
                <button type="button" id="addSpecsButtonUpdate" class="mt-2 text-sm text-blue-600 hover:underline">+ Add Specs</button>
            </div>

            <!-- Actions -->
            <div class="flex justify-end gap-2 pt-4 border-t border-gray-200">
                <button type="button" class="closeUpdateModalButton text-sm px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">Cancel</button>
                <button type="submit" class="text-sm px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Update Product</button>
            </div>
        </form>
    </div>
</div>





                
<!-- Modal -->
<div id="StockInModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" style="display: none;">
  <div class="bg-white rounded-xl shadow-2xl w-full sm:w-[550px] max-h-[90vh] overflow-y-auto p-6 animate__animated animate__fadeIn">

    <!-- Spinner Overlay -->
    <div class="spinner hidden absolute inset-0 bg-white bg-opacity-80 flex items-center justify-center z-10">
      <div class="w-12 h-12 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
    </div>

    <!-- Header -->
    <h2 class="text-2xl font-bold text-gray-800 text-center mb-6">
      Add Stocks: <span id="stockinTarget" class="text-blue-600"></span>
    </h2>

    <!-- Form Start -->
    <form id="frmUpdateStock" class="space-y-4">

      <!-- Hidden Product Info -->
      <div class="hidden">
        <label for="product_id_stockin" class="block text-sm text-gray-600">Product ID</label>
        <input type="text" id="product_id_stockin" name="product_id_stockin" class="w-full border p-2 rounded-md">
        <input type="text" id="product_name_stockin" name="product_name_stockin" class="w-full border p-2 rounded-md">
      </div>

      <!-- Current Stock Display -->
      <div class="text-center text-base text-gray-700">
        Current Stock: <span id="product_stocks" class="font-semibold text-blue-700"></span>
      </div>

      <!-- Qty -->
      <div>
        <label for="stockin_qty" class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
        <input type="number" id="stockin_qty" name="stockin_qty" placeholder="Enter quantity"
          class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
      </div>

      <!-- Supplier -->
      <div>
        <label for="stockin_supplier" class="block text-sm font-medium text-gray-700 mb-1">Supplier</label>
        <input type="text" id="stockin_supplier" name="stockin_supplier" placeholder="Enter supplier name"
          class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
      </div>

      <!-- Price -->
      <div>
        <label for="stockin_price" class="block text-sm font-medium text-gray-700 mb-1">Price</label>
        <input type="number" id="stockin_price" name="stockin_price" step="0.01" placeholder="Enter price"
          class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
      </div>


      <!-- Action Buttons -->
      <div class="flex justify-end gap-3 pt-4">
        <button type="button" id="StockInModalClose"
          class="px-6 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition duration-200">Cancel</button>
        <button type="submit"
          class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200">Add Stocks</button>
      </div>
    </form>
  </div>
</div>







<?php include "components/footer.php";?>