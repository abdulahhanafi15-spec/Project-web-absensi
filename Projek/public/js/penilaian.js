/* ===================================== */
/* SEARCH PERGURUAN */
/* ===================================== */

const searchInput =
document.getElementById(
    "searchInput"
);

const tableRows =
document.querySelectorAll(
    "#tableData tr"
);

if(searchInput){

    searchInput.addEventListener(
        "input",
        function () {

            let keyword =
            this.value.toLowerCase();

            tableRows.forEach(row => {

                let sekolah = row
                    .querySelector(".nama-sekolah")
                    .innerText
                    .toLowerCase();

                row.style.display =
                sekolah.includes(keyword)
                ? ""
                : "none";

            });

        }
    );

}