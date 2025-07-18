$(document).ready(function () {





$(document).on('click', '.userActionToggler', function (e) {
    e.preventDefault();

    const user_id = $(this).data('user_id');
    
    const order_id = $(this).data('order_id');
    const order_code = $(this).data('order_code');
    const action = $(this).data('action');

    let title = '';
    let confirmButtonText = '';
    let requestType = '';

    if (action === 'paid') {
        title = `<span style="color:green;">Mark as Paid </span> ${order_code}`;
        confirmButtonText = 'Yes!';
        requestType = 'paidOrder';
    } else if (action === 'decline') {
        title = `<span style="color:red;">Decline</span> ${order_code}`;
        confirmButtonText = 'Yes, decline it!';
        requestType = 'declineOrder';
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
                data: { order_id: order_id, requestType: requestType },
                dataType: 'json',
                success: function (response) {
                    if (response.status === 200) {
                        $('#spinnerOverlay').removeClass('hidden');

                        if (action === 'paid' || action === 'decline') {
                            $.ajax({
                                url: 'backend/mail/order-mailer.php',
                                type: 'POST',
                                data: {
                                    user_id: user_id,
                                    order_code: order_code,
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


});