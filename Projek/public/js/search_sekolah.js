document
.getElementById("searchSekolah")
.addEventListener("keyup", function(){

    let keyword = this.value.toLowerCase();

    let rows = document.querySelectorAll(
        "#tableSekolah tr"
    );

    rows.forEach(function(row){

        let sekolah = row
        .querySelector(".nama-sekolah")
        .innerText
        .toLowerCase();

        if(sekolah.includes(keyword)){
            row.style.display = "";
        }else{
            row.style.display = "none";
        }

    });

});