/* ===================================== */
/* TAB MENU */
/* ===================================== */

function showTab(tabId, button) {

    document
        .querySelectorAll(".tab-content")
        .forEach(tab => {

            tab.style.display = "none";

        });

    document
        .querySelectorAll(".tab-btn")
        .forEach(btn => {

            btn.classList.remove(
                "active"
            );

        });

    document
        .getElementById(tabId)
        .style.display = "block";

    button.classList.add(
        "active"
    );
}

/* ===================================== */
/* OPEN MODAL */
/* ===================================== */

function openModal(
    id,
    nama,
    hafalan = '',
    kelugesan = ''
){

    document
        .getElementById("modalNilai")
        .classList.add("show");

    document
        .getElementById("id_murid")
        .value = id;

    document
        .getElementById("nama_murid")
        .value = nama;

    document
        .getElementById("hafalan_jurus")
        .value = hafalan;

    document
        .getElementById("kelugesan_gerak")
        .value = kelugesan;
}

/* ===================================== */
/* CLOSE MODAL */
/* ===================================== */

function closeModal(){

    document
        .getElementById("modalNilai")
        .classList.remove("show");
}

/* ===================================== */
/* KLIK LUAR MODAL */
/* ===================================== */

window.onclick = function(event){

    let modal =
    document.getElementById(
        "modalNilai"
    );

    if(event.target == modal){

        closeModal();

    }
};

/* ===================================== */
/* ESC */
/* ===================================== */

document.addEventListener(
    "keydown",
    function(event){

        if(event.key === "Escape"){

            closeModal();

        }

    }
);

/* ===================================== */
/* DEFAULT TAB */
/* ===================================== */

document.addEventListener(
    "DOMContentLoaded",
    function(){

        let penilaian =
        document.getElementById(
            "tab_penilaian"
        );

        let topsis =
        document.getElementById(
            "tab_topsis"
        );

        if(penilaian){

            penilaian.style.display =
            "block";

        }

        if(topsis){

            topsis.style.display =
            "none";

        }

    }
);
/* CLOSE SAAT KLIK LUAR */
window.onclick = function(event) {

    let modal = document.getElementById(
        "modalNilai"
    );

    if (event.target === modal) {

        modal.classList.remove("show");
    }
}