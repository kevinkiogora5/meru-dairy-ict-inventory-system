$(document).ready(function () {
   
  var loginUrl = "/change_password_request";

  // Show password toggle
  $("#showpasswordInput").change(function () {
      $("input[name='password']").attr("type", this.checked ? "text" : "password");
  });

  // Handle form submission with AJAX
  $("#requestPassword").on('submit', function (e) {
      e.preventDefault();
      var formData = new FormData(this);
      var $button = $("#resetBtn");
      // Change button text to spinner
    
      $button.html(`
          <svg class="animate-spin h-5 w-5 text-white mr-2 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
          </svg> Sending mail...
        `).prop("disabled", true);
      console.log(formData);
      $.ajax({
          type: "POST",
          url: loginUrl, // Dynamic URL based on selected role
          data: formData,
          processData: false,
          contentType: false,
          success: function (response) {
    $("#resetBtn").css("display", "none");
    $("#loginBtn").css("display", "block");
    alert(response.message);

    // Redirect to reset password page with token (adjust URL and token param as needed)
    if (response.resetToken) {
        window.location.href = `/reset_password?token=${response.resetToken}`;
    } else {
        // fallback if no token sent
        window.location.href = `/reset_password`;
    }
},
          error: function (xhr, status, error) { 
              $button.html(` Request Reset Password`).prop("disabled", false);
              console.log(xhr)
              $("#resetBtn").css("display", "block");
               $("#loginBtn").css("display", "none");
               alert(xhr.responseJSON ? xhr.responseJSON.error : "An unexpected error occurred",);
          },
      });
      
  });
});