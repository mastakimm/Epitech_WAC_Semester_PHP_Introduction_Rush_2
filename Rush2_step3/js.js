// Sélection de l'élément HTML avec la classe "output"
const output = document.querySelector(".output");

// Sélection de l'élément HTML avec l'ID "myfiles" (input de type fichier)
const fileInput = document.querySelector("#myfiles");

// Ajout d'un écouteur d'événement pour le changement de fichiers sélectionnés
fileInput.addEventListener("change", () => {
  // Parcours de chaque fichier sélectionné
  for (const file of fileInput.files) {
    // Ajout du nom du fichier au contenu textuel de l'élément avec la classe "output"
    output.innerText += `\n${file.name}`;
  }
});
