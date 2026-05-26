/* ===================================== */
/* OPEN MODAL */
/* ===================================== */

function openModal(id, nama) {

    document
        .getElementById("modalNilai")
        .classList.add("show");

    document.getElementById("id_siswa").value = id;

    document.getElementById("nama_siswa").value = nama;
}

/* ===================================== */
/* CLOSE MODAL */
/* ===================================== */

function closeModal() {

    document
        .getElementById("modalNilai")
        .classList.remove("show");
}

/* ===================================== */
/* CLOSE SAAT KLIK LUAR MODAL */
/* ===================================== */

window.onclick = function(event) {

    let modal = document.getElementById(
        "modalNilai"
    );

    if (event.target === modal) {

        modal.classList.remove("show");
    }
}

/* ===================================== */
/* REALTIME SEARCH */
/* ===================================== */

const searchInput = document.getElementById(
    "searchInput"
);

const filterSearch = document.getElementById(
    "filterSearch"
);

const tableRows = document.querySelectorAll(
    "#tableData tr"
);

/* SEARCH OTOMATIS */
searchInput.addEventListener("input", realtimeSearch);

filterSearch.addEventListener("change", realtimeSearch);

function realtimeSearch() {

    let keyword = searchInput.value.toLowerCase();

    let filter = filterSearch.value;

    tableRows.forEach(row => {

        let targetText = "";

        if (filter === "nama") {

            targetText = row
                .querySelector(".nama-siswa")
                .innerText
                .toLowerCase();

        } else {

            targetText = row
                .querySelector(".nama-sekolah")
                .innerText
                .toLowerCase();
        }

        if (targetText.includes(keyword)) {

            row.style.display = "";

        } else {

            row.style.display = "none";
        }
    });
}