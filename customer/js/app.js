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
            dataType: 'json', // Corrected to dataType
            processData: false, // Prevent jQuery from processing FormData
            contentType: false, // Allow FormData to set its own content type
            success: function(response) {
                console.log(response);
                if (response.success) {
                    alertify.success(response.message); // Show success message
                } else {
                    alertify.error(response.message); // Show error message
                }
            },
            error: function(xhr, status, error) {
                console.log('AJAX error:', error); // Log the error for debugging
                console.log('XHR:', xhr); // Log the xhr object for more details
                alert('An error occurred: ' + error); // Show alert with error message
            }
        });
    });