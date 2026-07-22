document.addEventListener("DOMContentLoaded", function () {

    const btnCetak = document.getElementById("btnCetak");

    if (!btnCetak) {
        return;
    }

    btnCetak.addEventListener("click", function () {

        const jenis = document.getElementById("jenisLaporan").value;
        const sekolah = document.getElementById("idSekolah").value;
        const periode = document.getElementById("periode").value;

        if (sekolah === "") {
            alert("Silahkan pilih sekolah.");
            return;
        }

        if (jenis === "") {
            alert("Silahkan pilih jenis laporan.");
            return;
        }

        if (periode === "") {
            alert("Silahkan pilih periode.");
            return;
        }

        const data = periode.split("-");

        const tahun = parseInt(data[0]);
        const bulan = parseInt(data[1]);

        let url = "";

        if (jenis === "absensi") {

            url =
                "index.php?page=generate_pdf_absensi" +
                "&id=" + sekolah +
                "&bulan=" + bulan +
                "&tahun=" + tahun;

        } else {

            url =
                "index.php?page=generate_pdf_nilai" +
                "&id=" + sekolah +
                "&bulan=" + bulan +
                "&tahun=" + tahun;

        }

        window.open(url, "_blank");

    });

});