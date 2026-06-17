document.querySelectorAll("tbody tr").forEach(function(row){

    const selects = row.querySelectorAll(".kehadiran");

    function hitungAbsensi(){

        let total = 0;

        selects.forEach(function(select){
            total += parseInt(select.value);
        });

        row.querySelector(".nilai-absensi").innerText = total;
    }

    selects.forEach(function(select){

        select.addEventListener(
            "change",
            hitungAbsensi
        );

    });

    hitungAbsensi();

});