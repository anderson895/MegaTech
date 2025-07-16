  $('#userPasswordFrm').on('submit', function(e) {
        e.preventDefault(); 

        var newpassword =$('#newpassword').val()
        var confirmpassword =$('#confirmpassword').val()

        if(confirmpassword!=newpassword){
            alertify.error('Confirm Password Not Match');
            return;
        }

        var formData = new FormData(this);
        $.ajax({
            url: 'backend/end-points/controller.php', 
            type: 'POST',
            data: formData,
            dataType: 'json', 
            processData: false, 
            contentType: false, 
            success: function(response) {
                console.log(response);
                if (response.success) {
                    alertify.success(response.message); 
                } else {
                    alertify.error(response.message);
                }
            }
        });
    });




    
    $('.btnAddToCart').click(function() {
        let cart_user_id = $(this).data('user_id');
        let cart_prod_id = $(this).attr('data-product_id'); 

    
        $.ajax({
            type: "POST",
            url: "backend/end-points/controller.php",
            data: { 
                cart_user_id: cart_user_id,
                cart_prod_id: cart_prod_id,
                requestType: "AddToCart" 
            },
            dataType: 'json', 
            success: function(response) {
                console.log(response);
                
                if(response.status == "Added To Cart!") {
                    alertify.success('Item successfully added to the cart!');
                } else if(response.status == "Cart Updated!") {
                    alertify.success('Cart updated successfully!');
                } else {
                    alertify.error(response.status);
                }
            },
            error: function() {
                alertify.error('Error occurred during the request!');
            }
        });
    });











    // cartCount


const getOrdersCount = () => {
    $.ajax({
      url: 'backend/end-points/get_count_status.php', 
      type: 'GET',
      dataType: 'json',
      success: function(response) {
    //    console.log(response); 
        let cartCount = response.cartCount;
        let wishlistCount = response.wishlistCount;
        
        if (cartCount && cartCount > 0) {
            $('.cartCount').text(cartCount).show(); 
            // wishlistCount
        } else {
            $('.cartCount').hide();
        }

        if (wishlistCount && wishlistCount > 0) {
            $('.wishlistCount').text(wishlistCount).show(); 
            // wishlistCount
        } else {
            $('.wishlistCount').hide();
        }
      },
    });
};


getOrdersCount();

  setInterval(() => {
    getOrdersCount();
  }, 1000)