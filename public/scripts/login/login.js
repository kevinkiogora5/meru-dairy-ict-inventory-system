$(document).ready(function () {
    $('#user').on('submit', function (e) {
        e.preventDefault();

        var submit = $('#send');
        submit.html("Sending...").prop('disabled', true);

        const formData = {
            email: $('#email').val(),
            password: $('#password').val()
        };

        console.log("Sending data:", formData); // 🔍 Debug

        $.ajax({
            type: "POST",
            url: '/postlogin',
            contentType: "application/json",
            data: JSON.stringify(formData),
            success: function (response) {
                Toastify({
                    text: response.message || "Login successful",
                    duration: 3000,
                    close: true,
                    gravity: "top",
                    position: "right",
                    stopOnFocus: true,
                    style: { background: "#22c55e" }
                }).showToast();

                setTimeout(function () {
                    submit.html("Login").prop('disabled', false);
                    $('#user')[0].reset();
                    window.location.href = '/dashboard';
                }, 1500);
            },
            error: function (xhr) {
                submit.html("Login").prop('disabled', false);
                let err = xhr.responseJSON?.error;
                let errormessage = typeof err === 'string'
                    ? err
                    : Object.values(err || {}).join("\n") || "An error occurred";

                Toastify({
                    text: errormessage,
                    duration: 4000,
                    close: true,
                    gravity: "top",
                    position: "right",
                    stopOnFocus: true,
                    style: { background: "#ef4444" }
                }).showToast();
            }
        });
    });
});
