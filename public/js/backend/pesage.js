document.getElementById('addPesageLBtn').addEventListener('click', function () {
    // Affiche le formulaire et cache la liste
    document.getElementById('PesageLList').style.display = 'none';
    document.getElementById('addPesageLForm').style.display = 'block';
    document.getElementById('addPesageLBtn').style.display = 'none';
    // Met à jour le texte du titre
    var titreH4 = document.querySelector('#titre h4');
    var titreH6 = document.querySelector('#titre h6');
    titreH4.textContent = 'Pesages';
    titreH6.textContent = 'Ajouter une nouvelle Pesage';


});

document.getElementById('cancelPesageBtn').addEventListener('click', function () {
    // Cache le formulaire et affiche la liste
    document.getElementById('addPesageLForm').style.display = 'none';
    document.getElementById('PesageLList').style.display = 'block';

    // Réinitialise le texte du titre
    var titreH4 = document.querySelector('#titre h4');
    var titreH6 = document.querySelector('#titre h6');
    titreH4.textContent = 'Pesages';
    titreH6.textContent = 'Liste des Pesages';

});

function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    const file = input.files[0];
    const reader = new FileReader();

    reader.onload = function (e) {
        preview.src = e.target.result;
        preview.style.display = 'block';
    }

    if (file) {
        reader.readAsDataURL(file);
    } else {
        preview.src = '#';
        preview.style.display = 'none';
    }
}
