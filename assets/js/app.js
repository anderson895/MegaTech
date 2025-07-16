$(document).ready(function () {
$("#frmLogin").submit(function (e) {
    e.preventDefault();

    $('#spinner').show();
    $('#btnLogin').prop('disabled', true);

    var formData = $(this).serializeArray();
    formData.push({ name: 'requestType', value: 'Login' });

    $.ajax({
        type: "POST",
        url: "backend/end-points/controller.php",
        data: $.param(formData),
        dataType: 'json',
        success: function (response) {
            console.log("Response:", response);

            if (response.status === "success") {
                alertify.success('Login Successful');

                setTimeout(function () {
                    window.location.href = "customer/home";
                }, 1000);

            } else {
                $('#spinner').hide();
                $('#btnLogin').prop('disabled', false);
                alertify.error(response.message || 'Invalid login.');
            }
        },
        error: function (xhr, status, error) {
            $('#spinner').hide();
            $('#btnLogin').prop('disabled', false);
            console.error("AJAX Error:", xhr.responseText);
            alertify.error('An error occurred. Please try again.');
        }
    });
});



  $("#FrmRegister").submit(function (e) {
    e.preventDefault();

    $('#spinner').show();
    $('#btnRegister').prop('disabled', true);
    
    var formData = $(this).serializeArray(); 
    formData.push({ name: 'requestType', value: 'Signup' });
    var serializedData = $.param(formData);

    // Perform the AJAX request
    $.ajax({
      type: "POST",
      url: "backend/end-points/controller.php",
      data: serializedData,  
      success: function (response) {

    console.log(response);
        var data = JSON.parse(response);

        if (data.status === "success") {

           alertify.success('Registration Successful');

            setTimeout(function () {
                window.location.href = "pending";    
            }, 1000);


        } else if(data.status==="EmailAlreadyExists"){
          alertify.error(data.message);

          $('#spinner').hide();
          $('#btnRegister').prop('disabled', false);

        }else {
          $('#spinner').hide();
          $('#btnRegister').prop('disabled', false);
          console.error(response); 
          alertify.error('Registration failed, please try again.');
        }
      },
      error: function () {
        $('#spinner').hide();
        $('#btnRegister').prop('disabled', false);
        alertify.error('An error occurred. Please try again.');
      }
    });
  });

 





























  $('#resendLink').click(function() {
    var userId = $(this).attr('data-userId');
    
    // Show loading spinner
    $('#loadingSpinner').removeClass('hidden');

    // Disable the resend button during loading
    $('#resendLink').prop('disabled', true);

    $.ajax({
        type: "POST",
        url: "mailer.php",
        data: { user_id: userId },  
        success: function(response) {
            // Hide loading spinner

            console.log(response);

            $('#loadingSpinner').addClass('hidden');
            
            // Enable the resend button after request completes
            $('#resendLink').prop('disabled', false);

            if (response == 'OTPSentSuccessfully') {
                alertify.success('Verification link sent successfully!');
            } else {
                alertify.error('Error resending verification link.');
            }
        },
        error: function() {
            // Hide loading spinner
            $('#loadingSpinner').addClass('hidden');
            
            // Enable the resend button after error
            $('#resendLink').prop('disabled', false);

            alertify.error('Something went wrong. Please try again.');
        }
    });
});
































  
$("#frmForgotPassword").submit(function (e) {
  e.preventDefault();

  $('#spinner').show();
  $('#btnForgotPassword').prop('disabled', true);
  
  var formData = $(this).serializeArray(); 
  formData.push({ name: 'requestType', value: 'ForgotPassword' });
  var serializedData = $.param(formData);

  // Perform the AJAX request
  $.ajax({
    type: "POST",
    url: "backend/end-points/forgot.php",
    data: serializedData,  
    success: function (response) {

  console.log(response);
      var data = JSON.parse(response);

      if (data.status === "EmailNotExists") {

        alertify.error(data.message);

        $('#spinner').hide();
        $('#btnForgotPassword').prop('disabled', false);  
        
      } else if(data.status==="EmailExist"){

         sendforgotEmail(data.data.id,data.data.fullname,data.data.email);  



      }else {
        $('#spinner').hide();
        $('#btnForgotPassword').prop('disabled', false);
        console.error(response); 
        alertify.error('Registration failed, please try again.');
      }
    },
    error: function () {
      $('#spinner').hide();
      $('#btnForgotPassword').prop('disabled', false);
      alertify.error('An error occurred. Please try again.');
    }
  });
});


function sendforgotEmail(userID, fullName, Email) {
  $('#btnRegister').prop('disabled', true);
  $('#spinner').show();

  $.ajax({
    type: "POST",
    url: "ForgotPasswordMailer.php",
    data: { 
      userID: userID,
      fullName: fullName,
      Email: Email
    },
    dataType: "json",  // Set the expected response data type to JSON
    success: function (emailResponse) {
      console.log("Response from server:", emailResponse);
  
      // Check if the response indicates success
      if (emailResponse.status == "success") {  // Update to match the 'success' status from your PHP response
        alertify.success('Your new password has been sent to your email successfully!');
        setTimeout(function () {
          window.location.href = "login.php";
        }, 2000);
      } else {
        alertify.error('There was an issue sending the email. Please try again.');
      }
    },
    error: function (xhr, status, error) {
      console.error("Error in AJAX request:", error);
      alertify.error('There was an error communicating with the server. Please try again.');
    },
    complete: function () {
      $('#spinner').hide();
      $('#btnRegister').prop('disabled', false);
    }
  });
  

}








});