$(document).ready(function () {




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
  
          // Create a new FormData object
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