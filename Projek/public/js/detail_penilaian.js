function showTab(tabId) {

    let tabs = document.querySelectorAll(
        ".tab-content"
    );

    tabs.forEach(tab => {

        tab.classList.remove(
            "active"
        );

    });

    document
        .getElementById(tabId)
        .classList.add(
            "active"
        );
}

function openModal(id,nama){

    document
        .getElementById(
            "modalNilai"
        )
        .classList.add(
            "show"
        );

    document
        .getElementById(
            "id_murid"
        )
        .value = id;

    document
        .getElementById(
            "nama_murid"
        )
        .value = nama;
}

function closeModal(){

    document
        .getElementById(
            "modalNilai"
        )
        .classList.remove(
            "show"
        );
}