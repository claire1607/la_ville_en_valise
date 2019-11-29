// Exécute à la fin du chargement de la page
$(document).ready(afficheImage());

// Rafraichir la page
function reload() {
  window.location.reload();
}

// Ajouter une Pizza
function ajoutImage() {
  var nom = $(".ajout_nom_pizza").val();
  var image = $(".ajout_image_pizza").val();
  var description = $(".ajout_description_pizza").val();

  $.ajax({
    url: "http://localhost/pizzeria/pizzeria_php/admin_server.php",
    type: "POST",
    data: {
      nom: nom,
      image: image,
      description: description
    },
    success: function success(result) {
      alert("Image ajouté");
      reload();
    },
    error: function error(erreur) {
      console.log("erreur");
    }
  });
}

// Afficher la liste des pizzas

function affichePizza() {
  $.ajax({
    url: "http://localhost/La_Ville_en_Valise/include_php/liste_image.php",
    type: "GET",
    success: function success(result) {
      result = JSON.parse(result);
      for (var i = 0; i < result.length; i++) {
        $(".liste").append(
          `
                <div class="liste_pizza" id="pizza_` +
            result[i].id +
            `" >
                    <input type="text" id="pizza_` +
            result[i].id +
            `_nom" class="liste_nom_pizza" value="` +
            result[i].nom +
            `">
                    <div class="row_description">
                        <div>
                            <input type="text" id="pizza_` +
            result[i].id +
            `_img" class="liste_url_pizza" value="` +
            result[i].image +
            `">
                            <input type="text" id="pizza_` +
            result[i].id +
            `_desc" class="liste_description_pizza" value="` +
            result[i].description +
            `">
                        </div>
                        <img src="` +
            result[i].image +
            `" class="liste_image_pizza">
                    </div>
                    <div>
                        <button class="modifier" onclick="modifier(` +
            result[i].id +
            `)">Modifier</button>
                        <button class="supprimer" value="` +
            result[i].id +
            `"onclick="supprimer(` +
            result[i].id +
            `)">Supprimer</button>
                    </div>
                </div>
                `
        );
      }
    },

    error: function error(erreur) {
      console.log(erreur);
    }
  });
}

// Supprimer la pizza

function supprimer(id) {
  $.ajax({
    url: "http://localhost/pizzeria/pizzeria_php/delete_pizza.php",
    type: "POST",
    data: {
      id: id
    },
    success: function success(result) {
      alert(result);
      reload();
    },

    error: function error(erreur) {
      console.log("erreur");
    }
  });
}

// Modifier le nom, l'image et la description de la pizza
function modifier(idPizza) {
  let nom = $("#pizza_" + idPizza + "_nom").val();
  let img = $("#pizza_" + idPizza + "_img").val();
  let desc = $("#pizza_" + idPizza + "_desc").val();

  $.ajax({
    url: "http://localhost/pizzeria/pizzeria_php/modify_pizza.php",
    type: "POST",
    data: {
      id: idPizza,
      nom: nom,
      image: img,
      description: desc
    },
    success: function success(result) {
      alert("Pizza modifié");
      reload();
    },

    error: function error(erreur) {
      console.log("erreur");
    }
  });
}
