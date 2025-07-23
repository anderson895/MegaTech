$(document).ready(function () {



    
$(document).on('click', '.btnReturnToggler', function (e) {
    e.preventDefault();

    const return_id = $(this).data('return_id');
    const order_user_id = $(this).data('order_user_id');
  
    const action = $(this).data('action');

    let title = '';
    let confirmButtonText = '';
    let requestType = '';

    if (action === 'approve') {
       title = `<span style="color: green;">Approve Return</strong>`;

        confirmButtonText = 'Confirm';
        requestType = 'approveReturn';
    } else if (action === 'cancel') {
       title = `<span style="color: red;">Cancel Return</strong>`;

        confirmButtonText = 'Confirm';
        requestType = 'cancelReturn';
    }

    Swal.fire({
        title: title,
        html: 'You won\'t be able to revert this!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: confirmButtonText,
        cancelButtonText: 'No!',
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "backend/end-points/controller.php",
                type: 'POST',
                data: { return_id: return_id, requestType: requestType },
                dataType: 'json',
                success: function (response) {
                    if (response === 200) {
                        $('#spinnerOverlay').removeClass('hidden');


                         $.ajax({
                            url: 'backend/mail/return-mailer.php',
                            type: 'POST',
                            data: {
                                user_id: order_user_id,
                                action: action
                            },
                            success: function () {
                                $('#spinnerOverlay').addClass('hidden');
                                Swal.fire('Success!', response.message, 'success').then(() => {
                                    location.reload();
                                });
                            },
                            error: function () {
                                $('#spinnerOverlay').addClass('hidden');
                                Swal.fire('Email Error!', 'Action succeeded, but mail was not sent.', 'warning').then(() => {
                                    location.reload();
                                });
                            }
                        });
                       
                            

                    } else {
                        Swal.fire('Error!', response.message, 'error');
                    }
                },
                error: function () {
                    Swal.fire('Error!', 'There was a problem with the request.', 'error');
                }
            });
        }
    });
});








$(document).on('click', '.orderActionToggler', function (e) {
    e.preventDefault();

    const user_id = $(this).data('user_id');
    const order_code = $(this).data('order_code');
    const order_id = $(this).data('order_id');
  
    const action = $(this).data('action');

    let title = '';
    let confirmButtonText = '';
    let requestType = '';

    if (action === 'pickedup') {
       title = `<span style="color: green;">Set as picked up:</span> <strong style="color: gray;">${order_code}</strong>`;

        confirmButtonText = 'Confirm';
        requestType = 'pickedupOrder';
    } 

    Swal.fire({
    title: `
        <div class="text-green-600 text-lg font-semibold">
            Set as picked up:
            <span class="text-gray-700 font-bold">${order_code}</span>
        </div>
    `,
    html: `
        <div class="text-sm text-gray-700 mb-2">You won't be able to revert this!</div>
        
        <div class="flex flex-col items-start space-y-2 text-left">
            <label for="proof" class="text-sm font-medium text-gray-700">Upload proof of receipt:</label>
            <input 
                type="file" 
                id="proof"
                class="block w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4
                    file:rounded-md file:border-0
                    file:text-sm file:font-semibold
                    file:bg-blue-50 file:text-blue-700
                    hover:file:bg-blue-100
                    border border-gray-300 rounded-md p-1"
                accept="image/*,application/pdf"
            />
        </div>
    `,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: `<span class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded">Confirm</span>`,
    cancelButtonText: `<span class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded">Cancel</span>`,
    customClass: {
        popup: 'p-6 rounded-lg shadow-xl',
        confirmButton: 'swal2-confirm-button',
        cancelButton: 'swal2-cancel-button'
    },
    buttonsStyling: false,
    preConfirm: () => {
        const fileInput = document.getElementById('proof');
        if (!fileInput.files.length) {
            Swal.showValidationMessage('Please upload a proof of receipt');
            return false;
        }
        return fileInput.files[0];
    }
}).then((result) => {
    if (result.isConfirmed) {
        const file = result.value;

        const formData = new FormData();
        formData.append('order_id', order_id);
        formData.append('requestType', requestType);
        formData.append('proof_of_pickup', file);

        $.ajax({
            url: "backend/end-points/controller.php",
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function (response) {
                if (response.status === 200) {
                    $('#spinnerOverlay').removeClass('hidden');
                    Swal.fire('Success!', response.message, 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error!', response.message, 'error');
                }
            },
            error: function () {
                Swal.fire('Error!', 'There was a problem with the request.', 'error');
            }
        });
    }
});

});












//  $('.setScheduleToggler').on('click', function () {
$(document).on('click', '.setScheduleToggler', function (e) {
  const userId = $(this).data('user_id');
  const orderId = $(this).data('order_id');
  const orderCode = $(this).data('order_code');
  const fullname = $(this).data('fullname');

  // Update modal content
  $('#modalDetails').html(`
    <strong>Name:</strong> ${fullname} <br>
    <strong>Order Code:</strong> ${orderCode} <br>
  `);

  $("#orderId").val(orderId);
  $("#userId").val(userId);
  $("#orderCode").val(orderCode);

  // Show modal with fadeIn
  $('#setScheduleModal').removeClass('hidden').fadeIn(200);
});

// Close button
$('#closeModal').on('click', function () {
  $('#setScheduleModal').fadeOut(200, function () {
    $(this).addClass('hidden');
  });
});

// Close when clicking outside the modal content
$('#setScheduleModal').on('click', function (e) {
  if (e.target.id === 'setScheduleModal') {
    $(this).fadeOut(200, function () {
      $(this).addClass('hidden');
    });
  }
});




$('#setScheduleForm').on('submit', function(e) {
    e.preventDefault();

    const user_id = $('#userId').val();
    const order_code = $('#orderCode').val();
    const order_id = $('#orderId').val();

    console.log(user_id);

    var formData = new FormData(this);
    formData.append('requestType', 'setSchedule'); 

    $.ajax({
        url: "backend/end-points/controller.php",
        type: 'POST',
        data: formData,
        dataType: 'json',
        contentType: false,    // Required for FormData
        processData: false,    // Required for FormData
        success: function (response) {
            if (response.status === 200) {
                $('#spinnerOverlay').removeClass('hidden');

                $.ajax({
                    url: 'backend/mail/schedule-mailer.php',
                    type: 'POST',
                    data: {
                        user_id: user_id,
                        order_code: order_code,
                        order_id: order_id,
                    },
                    success: function () {
                        $('#spinnerOverlay').addClass('hidden');
                        Swal.fire('Success!', response.message, 'success').then(() => {
                            location.reload();
                        });
                    },
                    error: function () {
                        $('#spinnerOverlay').addClass('hidden');
                        Swal.fire('Email Error!', 'Action succeeded, but mail was not sent.', 'warning').then(() => {
                            location.reload();
                        });
                    }
                });

            } else {
                Swal.fire('Error!', response.message, 'error');
            }
        },
        error: function () {
            Swal.fire('Error!', 'There was a problem with the request.', 'error');
        }
    });
});








$(document).on('click', '.userActionToggler', function (e) {
    e.preventDefault();

    const user_id = $(this).data('user_id');
    const fullname = $(this).data('fullname');
    const action = $(this).data('action');

    let title = '';
    let confirmButtonText = '';
    let requestType = '';

    if (action === 'accept') {
        title = `Are you sure to <span style="color:green;">Accept</span> ${fullname}'s Request?`;
        confirmButtonText = 'Yes, accept it!';
        requestType = 'acceptUser';
    } else if (action === 'decline') {
        title = `Are you sure to <span style="color:red;">Decline</span> ${fullname}'s Request?`;
        confirmButtonText = 'Yes, decline it!';
        requestType = 'declineUser';
    } else if (action === 'restrict') {
        title = `Are you sure to <span style="color:red;">Restrict</span> ${fullname}?`;
        confirmButtonText = 'Yes, restrict!';
        requestType = 'restrictUser';
    }else if (action === 'activate') {
        title = `Are you sure to <span style="color:red;">Activate</span> ${fullname}?`;
        confirmButtonText = 'Yes, activate!';
        requestType = 'activateUser';
    }

    Swal.fire({
        title: title,
        html: 'You won\'t be able to revert this!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: confirmButtonText,
        cancelButtonText: 'No, cancel!',
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "backend/end-points/controller.php",
                type: 'POST',
                data: { user_id: user_id, requestType: requestType },
                dataType: 'json',
                success: function (response) {
                    if (response.status === 200) {
                        $('#spinnerOverlay').removeClass('hidden');
                        if (action === 'accept' || action === 'restrict') {
                            $.ajax({
                                url: 'backend/mail/user-mailer.php',
                                type: 'POST',
                                data: {
                                    user_id: user_id,
                                    action: action,
                                    password: response.generated_password || ''
                                },
                                success: function () {
                                    $('#spinnerOverlay').addClass('hidden');
                                    Swal.fire('Success!', response.message, 'success').then(() => {
                                        location.reload();
                                    });
                                },
                                error: function () {
                                    $('#spinnerOverlay').addClass('hidden');
                                    Swal.fire('Email Error!', 'Action succeeded, but mail was not sent.', 'warning').then(() => {
                                        location.reload();
                                    });
                                }
                            });
                        } else {
                            $('#spinnerOverlay').addClass('hidden');
                            Swal.fire('Success!', response.message, 'success').then(() => {
                                location.reload();
                            });
                        }

                    } else {
                        Swal.fire('Error!', response.message, 'error');
                    }
                },
                error: function () {
                    Swal.fire('Error!', 'There was a problem with the request.', 'error');
                }
            });
        }
    });
});









    $(document).on('click', '.togglerRemoveProduct', function(e) {
        e.preventDefault();
        var prod_id = $(this).data('prod_id');
        var prod_name = $(this).data('prod_name');
        console.log(prod_id);
    
        Swal.fire({
            title: `Are you sure to Remove <span style="color:red;">${prod_name}</span> ?`,
            text: 'You won\'t be able to revert this!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, remove it!',
            cancelButtonText: 'No, cancel!',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "backend/end-points/controller.php",
                    type: 'POST',
                    data: { prod_id: prod_id, requestType: 'removeProduct' },
                    dataType: 'json', 
                    success: function(response) {
                      console.log(response);
                        if (response.status === 200) {
                            Swal.fire(
                                'Removed!',
                                response.message, 
                                'success'
                            ).then(() => {
                                location.reload(); 
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                response.message, 
                                'error'
                            );
                        }
                    },
                    error: function() {
                        Swal.fire(
                            'Error!',
                            'There was a problem with the request.',
                            'error'
                        );
                    }
                });
            }
        });
    });






  $('.stockInToggler').click(function(){
    $('#product_id_stockin').val($(this).attr('data-prod_id'))
    $('#product_name_stockin').val($(this).data('prod_name'))
    
    $('#product_stocks').text($(this).attr('data-prod_stocks'))
    $('#stockinTarget').text($(this).attr('data-prod_name'))
    $('#StockInModal').fadeIn()
  });

  $('#StockInModalClose').click(function(){
    $('#StockInModal').fadeOut()
  });



  $('#frmUpdateStock').on('submit', function(e) {
        e.preventDefault();
        var stockin_qty = $('#stockin_qty').val();
        if (stockin_qty === null) {
            alert("Please Enter Quantity.");
            return; 
        }
         
         
        $('.spinner').show();
        $('#frmUpdateStock').prop('disabled', true);

        // Create a new FormData object
        var formData = new FormData(this);
        formData.append('requestType', 'StockIn'); 

        // Perform the AJAX request
        $.ajax({
            type: "POST",
            url: "backend/end-points/controller.php",
            data: formData,
            contentType: false,
            processData: false, 
            success: function(response) {
              console.log(response)
                if(response==200){
                  $('#StockInModal').hide();
                  $('.spinner').hide();
                  $('#frmUpdateStock').prop('disabled', false);

                  location.reload();
                }
            },
            error: function(xhr, status, error) {
                alert('Error: ' + error);
            }
        });
    });




 $('#frmAddProduct').on('submit', function(e) {
          e.preventDefault();
          var category = $('#productCategory').val();
          if (category === null) {
              alert("Please select a category.");
              return; 
          }
           var productImage = $('#productImage').val();
           if (productImage === "") {
               alert("Please upload an image.");
               return; 
           }
           
          $('.spinner').show();
          $('#frmAddProduct').prop('disabled', true);
  
          var formData = new FormData(this);
          formData.append('requestType', 'AddProduct'); 
  
          // Perform the AJAX request
          $.ajax({
              type: "POST",
              url: "backend/end-points/controller.php",
              data: formData,
              contentType: false,
              processData: false, 
              success: function(response) {
                console.log(response)
                  if(response==200){
                    $('#AddproductModal').hide();
                    $('.spinner').hide();
                    $('#frmAddProduct').prop('disabled', false);
                    location.reload();
                  }
              },
              error: function(xhr, status, error) {
                  alert('Error: ' + error);
              }
          });
      });








$('.updateProductToggler').click(function () {
    $('#product_id_update').val($(this).data('prod_id'));
    $('#product_Code_update').val($(this).data('prod_code'));
    $('#product_Name_update').val($(this).data('prod_name'));
    $('#product_Price_update').val($(this).data('prod_price'));
    $('#critical_Level_update').val($(this).data('prod_critical'));
    $('#product_Category_update').val($(this).data('prod_category_id'));
    $('#product_Description_update').val($(this).data('prod_description'));
    $('#product_Stocks_update').val($(this).data('prod_stocks'));

    const specsJSON = $(this).attr('data-prod_specs');
    let specs = [];

    try {
        specs = JSON.parse(specsJSON);
    } catch (e) {
        console.error('Invalid JSON in data-prod_specs', e);
    }

    const specsList = $('#specsListUpdate');
    specsList.empty();

    if (specs.length > 0) {
        specs.forEach((spec, index) => {
            specsList.append(`
                <div class="flex gap-2 items-center">
                    <input type="text" name="specs_name[]" value="${spec.Specs}" placeholder="Specs Name" class="w-1/2 px-3 py-2 border border-gray-300 rounded-md text-sm">
                    <input type="text" name="specs_value[]" value="${spec.value}" placeholder="Specs Value" class="w-1/2 px-3 py-2 border border-gray-300 rounded-md text-sm">
                    ${specs.length > 1 ? `
                        <button type="button" class="remove-spec-btn text-red-500 hover:text-red-700">
                            <span class="material-icons">close</span>
                        </button>` : ``}
                </div>
            `);
        });
    }

    $('#UpdateProductModal').fadeIn();
});

// Close modal
$('.closeUpdateModalButton').click(function () {
    $('#UpdateProductModal').fadeOut();
});

$('#UpdateProductModal').click(function (event) {
    if ($(event.target).is('#UpdateProductModal')) {
        $('#UpdateProductModal').fadeOut();
    }
});

// Add new specs
$('#addSpecsButtonUpdate').click(function () {
    const specsList = $('#specsListUpdate');

    const newSpec = $(`
        <div class="flex gap-2 items-center">
            <input type="text" name="specs_name[]" placeholder="Specs Name" class="w-1/2 px-3 py-2 border border-gray-300 rounded-md text-sm">
            <input type="text" name="specs_value[]" placeholder="Specs Value" class="w-1/2 px-3 py-2 border border-gray-300 rounded-md text-sm">
            <button type="button" class="remove-spec-btn text-red-500 hover:text-red-700">
                <span class="material-icons">close</span>
            </button>
        </div>
    `);

    specsList.append(newSpec);

    // Show all remove buttons since we now have more than one
    if (specsList.children().length > 1) {
        specsList.find('.remove-spec-btn').show();
    }
});

// Remove a spec
$(document).on('click', '.remove-spec-btn', function () {
    const specsList = $('#specsListUpdate');
    $(this).closest('div.flex').remove();

    // If only one spec left, hide its remove button
    if (specsList.children().length === 1) {
        specsList.find('.remove-spec-btn').hide();
    }
});








 $(document).ready(function() {
    $('#frmUpdateProduct').on('submit', function(e) {
        e.preventDefault();
        var category = $('#product_Category_update').val();
        if (category === null) {
            alert("Please select a category.");
            return; 
        }
         
         
        $('.spinner').show();
        $('#frmUpdateProduct').prop('disabled', true);

        // Create a new FormData object
        var formData = new FormData(this);
        formData.append('requestType', 'UpdateProduct'); 

        // Perform the AJAX request
        $.ajax({
            type: "POST",
            url: "backend/end-points/controller.php",
            data: formData,
            contentType: false,
            processData: false, 
            success: function(response) {
              console.log(response)
                if(response==200){
                  $('#UpdateProductModal').hide();
                  $('.spinner').hide();
                  $('#frmUpdateProduct').prop('disabled', false);


                  alertify.success("Update Successfully");

                   setTimeout(function () {
                    location.reload();
                }, 1000);
                }
            },
            error: function(xhr, status, error) {
                alert('Error: ' + error);
            }
        });
    });
  });
  










$("#frmLogin").submit(function (e) {
      e.preventDefault();
  
      $('#spinner').show();
      $('#btnLogin').prop('disabled', true);
      
      var formData = $(this).serializeArray(); 
      formData.push({ name: 'requestType', value: 'Login' });
      var serializedData = $.param(formData);
  
      // Perform the AJAX request
      $.ajax({
        type: "POST",
        url: "backend/end-points/controller.php",
        data: serializedData,
        dataType: 'json',
        success: function (response) {

          console.log(response.status)

          if (response.status === "success") {
            alertify.success('Login Successful');

            setTimeout(function () {
              window.location.href = "dashboard"; 
            }, 1000);

          } else {
            $('#spinner').hide();
            $('#btnLogin').prop('disabled', false);
            console.log(response); 
            alertify.error(response.message);
          }
        },
        error: function () {
          $('#spinner').hide();
          $('#btnLogin').prop('disabled', false);
          alertify.error('An error occurred. Please try again.');
        }
      });
    });





      // Show the modal when Add Product button is clicked
    $('#addProductButton').click(function() {
      $('#AddproductModal').fadeIn(200);  // Use fadeIn for smoother appearance
    });
      // Show the modal when Add Product button is clicked
    $('#addProductButton').click(function() {
      $('#AddproductModal').fadeIn(200);  // Use fadeIn for smoother appearance
    });

    // Hide the modal when Cancel button is clicked
    $('#closeModalButton').click(function() {
      $('#AddproductModal').fadeOut(200);  // Use fadeOut for smoother disappearance
    });

});