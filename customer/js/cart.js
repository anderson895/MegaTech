$(document).ready(function() {
    function updateOrderSummary() {
        let subTotal = 0;
        let total = 0;

        $('.product-checkbox:checked').each(function() {
            const price = $(this).data('price'); 
            const productTotal = price;
            subTotal += productTotal;
            total = subTotal;
        });
        
        $('#total').text(total.toFixed(2));
    }

    $('#check-all').click(function() {
        var isChecked = $(this).prop('checked');
        $('.product-checkbox').prop('checked', isChecked);
        updateOrderSummary();
    });

    $('.product-checkbox').click(function() {
        updateOrderSummary();

        if($('.product-checkbox:checked').length === $('.product-checkbox').length) {
            $('#check-all').prop('checked', true);
        } else {
            $('#check-all').prop('checked', false);
        }
    });





  $(".btnCheckOut").click(function () {
    // Calculate total from selected checkboxes
    let subTotal = 0;
    $('.product-checkbox:checked').each(function() {
        const price = $(this).data('price');
        subTotal += price;
    });

    // Calculate 50% downpayment
    const downpayment = subTotal * 0.5;

    // Update modal display
    $("#downpaymentAmount").text(downpayment.toFixed(2));
    $("#downpaymentInfo").removeClass("hidden");

    // Show the modal
    $("#checkoutModal").fadeIn();
});

    $(".closeModal").click(function () {
        $("#checkoutModal").fadeOut();
    });
    
    $("#closeModal").click(function () {
            $("#checkoutModal").addClass("hidden");
    });




        $("#paymentMethod").change(function () {
            const selectedPaymentMethod = $(this).val();
            const selectedOption = $(this).find("option:selected");
    
            $("#paymentDetails").addClass("hidden");
            $("#qrCode").addClass("hidden");
            $("#proofOfPaymentSection").removeClass("hidden").find("input").removeAttr("required");
            $("#qrCode img").attr("src", ""); 

            if (selectedPaymentMethod !== "cod") {
                $("#paymentDetails").removeClass("hidden");
                
                const qrImagePath = selectedOption.data('img');
                
                if (qrImagePath) {

                    $("#qrCode").removeClass("hidden").find("img").attr("src", "../ewallet/" + qrImagePath);
                }
    
                $("#proofOfPaymentSection").find("input").attr("required", true);
            }

            else {
                $("#paymentDetails").addClass("hidden");
                $("#qrCode").addClass("hidden");
                $("#proofOfPaymentSection").find("input").removeAttr("required").val('');
            }
        });




        $('#btnConfirmCheckout').click(function (e) {
            e.preventDefault();

    
            var total = $('#total').text();
        
            // Retrieve selected payment method and file input
            var selectedPaymentMethod = $("#paymentMethod option:selected").data('ename');
            var pickupDate=$("#pickupDate").val();
            var pickupTime=$("#pickupTime").val();

            var fileInput = $('#proofOfPayment')[0];
            var selectedFile = fileInput.files[0];
        
            console.log(selectedPaymentMethod)
            // If payment method is not 'COD' and no file is selected, show error
            if (selectedPaymentMethod !== "cod" && selectedFile == undefined) {
                alertify.error('You are required to upload a proof of payment.');
                return;
            }
        
            // Validate file type and size if a file is selected
            if (selectedFile) {
                const fileSizeLimit = 10 * 1024 * 1024; // 10MB limit
                const fileSize = selectedFile.size;
                const fileType = selectedFile.type;
        
                if (!fileType.startsWith('image/')) {
                    alertify.error('Please upload a valid image file.');
                    return;
                }
                if (fileSize > fileSizeLimit) {
                    alertify.error('The image file size should not exceed 10MB.');
                    return;
                }
            }
        
            // Check if a valid address is selected
            var selectedAddress = $("#addressSelect").val();
            if (selectedAddress === null) {
                alertify.error('Please set an address first.');
                return;
            }

         
            // Collect selected products' data from checkboxes
            var selectedProducts = [];
            $('.product-checkbox:checked').each(function() {
                selectedProducts.push({
                    productId: $(this).data('product-id'),
                    originalPrice: $(this).data('originalprice'),  
                    price: $(this).data('price'),
                    qty: $(this).data('qty')
                });

                console.log($(this).data('originalprice')); 
            });
            
            // Ensure at least one product is selected
            if (selectedProducts.length === 0) {
                alertify.error('Please select at least one product.');
                return;
            }
        
            // Prepare form data for AJAX
            var formData = new FormData();
            formData.append("selectedPaymentMethod", selectedPaymentMethod);
            formData.append("selectedFile", selectedFile);
            formData.append("pickupDate", pickupDate);
            formData.append("pickupTime", pickupTime);
       

            formData.append("total", total);
            formData.append("selectedProducts", JSON.stringify(selectedProducts));
            formData.append("requestType", "OrderRequest");
        
            $.ajax({
                type: "POST",
                url: "backend/end-points/controller.php",
                data: formData,
                processData: false, 
                contentType: false, 
                dataType: "json", 
                beforeSend: function() {
                    $(".loadingSpinner").fadeIn();
                },
                
                success: function(response) {
                    console.log(response);
                    if (response.status == 'success') {
                        $(".loadingSpinner").fadeOut();
                        alertify.success('Order Request sent successfully.');

                        const orderId = response.order_id;
                        const order_code = response.order_code;

                        $.ajax({
                            type: "POST",
                            url: "backend/end-points/QRgenerator.php",
                            data: {
                                order_id: orderId,
                                order_code: order_code
                            },
                            success: function(qrResponse) {
                                if (qrResponse.status === 'success') {
                                    // Redirect to receipt with order ID
                                    window.location.href = `reservation_receipt?order_id=${orderId}`;
                                } else {
                                    alertify.error('QR code generation failed.');
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error("QR Generation failed:", error);
                                alertify.error('QR code generation failed.');
                            }
                        });

                        // Optional: close modal
                        setTimeout(() => {
                            $("#checkoutModal").fadeOut();
                        }, 1000);
                    }
                },
                
                error: function(xhr, status, error) {
                    $(".loadingSpinner").fadeOut();
                    console.log('Status:', status);
                    console.log('Error:', error);
                    alertify.error('Error occurred during the request!');
                }
                
            });
            
        });
        

        updateOrderSummary();

  });