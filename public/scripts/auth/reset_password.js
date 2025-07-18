$(document).ready(function () {
  const $form = $("#resetFrm");
  const $submitBtn = $("#loginBtn");
  const $loginBtn = $("#login_now_div");

  $form.on("submit", function (e) {
    e.preventDefault();

    const token = $("#token").val().trim();
    const password = $("#password").val();
    const confirmPassword = $("#confirm_password").val();

    // Optional: disable button and show loading spinner
    $submitBtn
      .html(`
        <svg class="animate-spin h-5 w-5 text-white mr-2 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
        </svg> Updating...
      `)
      .prop("disabled", true);

    $.ajax({
      url: `/reset_password`,
      method: "POST",
      contentType: "application/json",
      data: JSON.stringify({
        token: token,
        password: password,
        confirm_password: confirmPassword, // must match backend
      }),
      dataType: "json",
      success: function (response) {
        if (response.success) {
          alert(response.message);
          $form.fadeOut();
          $loginBtn.fadeIn();
        } else {
          alert("Unexpected response.");
        }
      },
      error: function (xhr) {
        const errors = xhr.responseJSON?.error;
        if (typeof errors === "object") {
          let msg = Object.values(errors).join("\n");
          alert(msg);
        } else {
          alert(errors || "An unexpected error occurred.");
        }
      },
      complete: function () {
        $submitBtn.html("Update Password").prop("disabled", false);
      },
    });
  });
});
