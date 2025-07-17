$(document).ready(function () {
    $('#logoutBtn').on('click', function (e) {
        e.preventDefault();

        const token = $('meta[name="session-token"]').attr('content');

        if (!token) {
            Toastify({
                text: "No valid session token found.",
                duration: 3000,
                close: true,
                gravity: "top",
                position: "right",
                style: { background: "#ef4444" }
            }).showToast();
            return;
        }

        $.ajax({
            type: "POST",
            url: '/logout',
            data: { session_token: token },
            success: function (response) {
                Toastify({
                    text: response.message || "Logout successful",
                    duration: 3000,
                    close: true,
                    gravity: "top",
                    position: "right",
                    style: { background: "#22c55e" }
                }).showToast();

                setTimeout(() => {
                    window.location.href = '/login';
                }, 1000);
            },
            error: function (xhr) {
                const err = xhr.responseJSON?.error || "Logout failed";
                Toastify({
                    text: typeof err === 'string' ? err : Object.values(err).join("\n"),
                    duration: 4000,
                    close: true,
                    gravity: "top",
                    position: "right",
                    style: { background: "#ef4444" }
                }).showToast();
            }
        });
    });
});
