// Confirmation avant suppression
document.querySelectorAll(".delete").forEach(function(lien) {

    lien.addEventListener("click", function(e) {

        if (!confirm("Voulez-vous vraiment supprimer cet étudiant ?")) {
            e.preventDefault();
        }

    });

});


// Recherche instantanée
let recherche = document.querySelector("#recherche");

if (recherche) {

    recherche.addEventListener("input", function() {

        let texte = recherche.value.toLowerCase();

        document.querySelectorAll("table tr").forEach(function(ligne, index) {

            if (index == 0) return;

            ligne.style.display =
                ligne.textContent.toLowerCase().includes(texte)
                ? ""
                : "none";

        });

    });

}