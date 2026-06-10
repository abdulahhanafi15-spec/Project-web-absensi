/* ========================================= */
/* MODAL SETTING AKUN */
/* ========================================= */

document.addEventListener("DOMContentLoaded", function () {

    const btnEditData = document.getElementById("btnEditData");
    const btnUbahPassword = document.getElementById("btnUbahPassword");

    const modalEditData = document.getElementById("modalEditData");
    const modalPassword = document.getElementById("modalPassword");

    /* BUKA MODAL EDIT DATA */
    if (btnEditData && modalEditData) {

        btnEditData.addEventListener("click", function () {

            modalEditData.classList.add("show");

        });

    }

    /* BUKA MODAL PASSWORD */
    if (btnUbahPassword && modalPassword) {

        btnUbahPassword.addEventListener("click", function () {

            modalPassword.classList.add("show");

        });

    }

    /* TOMBOL CLOSE */
    document.querySelectorAll(".close-modal").forEach(function(btn) {

        btn.addEventListener("click", function() {

            this.closest(".modal").classList.remove("show");

        });

    });

    /* KLIK DI LUAR MODAL */
    window.addEventListener("click", function(event) {

        if (event.target.classList.contains("modal")) {

            event.target.classList.remove("show");

        }

    });

});

/* ========================================= */
/* MODAL PENGATURAN AKUN */
/* ========================================= */

document.addEventListener("DOMContentLoaded", function () {

    const btnEditData = document.getElementById("btnEditData");
    const btnUbahPassword = document.getElementById("btnUbahPassword");

    const modalEditData = document.getElementById("modalEditData");
    const modalPassword = document.getElementById("modalPassword");

    if (btnEditData && modalEditData) {

        btnEditData.addEventListener("click", function () {

            modalEditData.classList.add("show");

        });

    }

    if (btnUbahPassword && modalPassword) {

        btnUbahPassword.addEventListener("click", function () {

            modalPassword.classList.add("show");

        });

    }

    document.querySelectorAll(".close-modal").forEach(function(btn) {

        btn.addEventListener("click", function() {

            this.closest(".modal").classList.remove("show");

        });

    });

    window.addEventListener("click", function(event) {

        if (event.target.classList.contains("modal")) {

            event.target.classList.remove("show");

        }

    });

});