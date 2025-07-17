function togglePassword() {
    var passwordField = document.getElementById("example-password");
    var passwordLabel = document.getElementById("show-hide");
    if (passwordField.type === "password") {
        passwordField.type = "text";
        passwordLabel.innerHTML = "Uncheck to Hide Password";
    } else {
        passwordField.type = "password";
        passwordLabel.innerHTML = "Show Password";
    }
}

$(document).ready(function () {
      var options = {
        classname: "toast",
        transition: "fade",
        insertBefore: true,
        duration: 4000,
        enableSounds: false,
        autoClose: true,
        progressBar: true,
    };
        var toast = new Toasty(options);
        toast.configure(options);
       // Show password toggle
    $("#showpasswordInput").change(function () {
        $("input[name='password']").attr("type", this.checked ? "text" : "password");
    });
    // Handle form submission with AJAX
    $("#resetFrm").on('submit', function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        var token = $("#token").val();
        var $button = $("#loginBtn");
        // Change button text to spinner
        $button.html('<span class="spinner-grow spinner-grow-sm me-1"></span> Logging you in...').prop('disabled', true);
        console.log(formData);
        $.ajax({
            type: "POST",
            url: `/reset_password/${token}`, // Dynamic URL based on selected role
            data: formData,
            dataType: "json",
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.success) {
                  toast.success(response.message);
                   $("#reset_btn_div").fadeOut();
                   $("#login_now_div").fadeIn();
                } 
              
            },
            error: function (xhr) { 
                console.log(xhr);
                $("#reset_btn_div").fadeIn();
                $("#login_now_div").fadeOut(); 
                toast.error(xhr.responseJSON ? xhr.responseJSON.message : "An unexpected error occurred");
            },
            complete: function () {
                $("#loginBtn").html('Login Now').prop('disabled', false);
            }
        });
        
    });
    $("#bresetFrm").on("submit", function (e) {
      e.preventDefault();
      let password = $("#password").val();
      let confirmPassword = $("#confirm_password").val();
      let $button = $("#loginBtn");
      let token = $("#token").val();
  
      if (password !== confirmPassword) {
          toast.error("Passwords do not match!")
        return;
      }
  
      if (password.length < 6) {
          toast.error("Password must be at least 6 characters long!")
        return;
      }
  
      $button
        .html('<span class="spinner-grow spinner-grow-sm me-1"></span> Updating...')
        .prop("disabled", true);
  
      $.ajax({
        type: "POST",
        url: `/auth/buyer/reset_password/${token}`,
        contentType: "application/json",
        data: JSON.stringify({ password }),
        dataType: "json",
        success: function (response) {
          if (response.success) {
            toast.success(response.message || "Password updated!", true);
            $("#loginBtn").fadeOut();
            $("#login_now_div").fadeIn();
          }
        },
        error: function (xhr) {
          $("#loginBtn").fadeIn();
          $("#login_now_div").fadeOut();
          toast.error(
            xhr.responseJSON?.message || "An unexpected error occurred",
            false
          );
        },
        complete: function () {
          $button.html("Update Password").prop("disabled", false);
        },
      });
    });
    // 👁️ Toggle password visibility
    $(".toggle-password").on("click", function () {
      let input = $(this).siblings("input");
      input.attr("type", input.attr("type") === "password" ? "text" : "password");
      $(this).toggleClass("text-blue-600 text-gray-400");
    });
  });
    // 🔔 Simple Custom Toast Function
  function showCustomToast(message, success = true) {
    const toast = document.createElement("div");
    toast.className = `custom-toast ${success ? "toast-success" : "toast-error"}`;
    toast.innerText = message;
  
    document.body.appendChild(toast);
  
    setTimeout(() => {
      toast.classList.add("show");
    }, 100);
  
    setTimeout(() => {
      toast.classList.remove("show");
      toast.addEventListener("transitionend", () => toast.remove());
    }, 3000);
  }
  