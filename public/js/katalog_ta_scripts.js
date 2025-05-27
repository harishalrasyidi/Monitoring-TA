// File ini bisa disimpan di public/js/katalog_ta_scripts.js
// Kemudian dimuat di layout template atau halaman spesifik

$(document).ready(function () {
    // Handler untuk form submit request
    $("#requestForm").on("submit", function (e) {
        e.preventDefault();

        // Validasi form secara manual
        const tujuanRequest = $("#tujuan_request").val().trim();
        if (!tujuanRequest) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Tujuan request harus diisi!",
            });
            return false;
        }

        // Tampilkan konfirmasi sebelum mengirim
        Swal.fire({
            title: "Konfirmasi Request",
            text: "Email request akan dikirim ke penulis TA. Lanjutkan?",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, kirim request",
            cancelButtonText: "Batal",
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit form jika dikonfirmasi
                this.submit();

                // Tampilkan loading
                Swal.fire({
                    title: "Mengirim request...",
                    html: "Mohon tunggu sebentar",
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    },
                });
            }
        });
    });

    // Animasi hover untuk card katalog
    $(".card").hover(
        function () {
            $(this).addClass("shadow");
            $(this).css("transform", "translateY(-5px)");
            $(this).css("transition", "all 0.3s ease");
        },
        function () {
            $(this).removeClass("shadow");
            $(this).css("transform", "translateY(0)");
        }
    );

    // Untuk menampilkan notifikasi sukses dari session flash
    if ($(".alert-success").length) {
        setTimeout(function () {
            $(".alert-success").fadeOut("slow");
        }, 5000);
    }
});
