<?php
require_once('include/init.php');


// Si l'utilisateur est connecté, il n'a rien à faire sur la page inscription, on le redirige vers la page index.php
if(userConnected()){
  header('location: index.php');
}

// 1. Contrôler que l'on réceptionne bien toutes les données saisies dans le formulaire en PHP.
echo '<pre>';
print_r($_POST);
echo '</pre>';

if(isset($_POST['submit']) && $_SERVER['REQUEST_METHOD'] === 'POST'){
  //  2. Contrôler la validité de l'email (select + rowCount).

  // On sélectionne tout dans la BDD à condition que la colonne email dans la BDD soit égale à l'email saisi dans le formulaire
  //                                                              karolynn..gmail.com
  $data = $connect_db->prepare("SELECT * FROM user WHERE email = :email");
  $data->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
  $data->execute();

  // echo $data->rowCount();
  // Si la condition IF retourne TRUE, l'email est existant en BDD, on entre dans le IF.
  if($data->rowCount()){
    $errorEmail = '<small class="text-color-danger">Un compte est déjà existant à cette adresse email.</small>';

    $error = true;
  }elseif(empty($_POST['email'])){
    $errorEmail = '<small class="text-color-danger">Merci de saisir une adresse email.</small>';
    
    $error = true;
  }elseif(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
    $errorEmail = '<small class="text-color-danger">Merci de saisir une adresse email valide. (ex: exemple@gmail.com)</small>';

    $error = true;
  }

  $password_regex = "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/"; 
  // echo preg_match($password_regex, 'secret') . '<br>'; // returns 0
  // echo preg_match($password_regex, '-Secr3t.'); // returns 1


// 5. Afficher un message si le champs mot de passe est vide.
    if(empty($_POST['password'])){
      $errorPassword = '<small class="text-color-danger">Merci de saisir un mot de passe.</small>';

      $error = true;
    }elseif (!preg_match($password_regex, $_POST['password'])) {
      $errorPassword = '<small class="text-color-danger">8 caractères minimum, une majuscule, une minuscule, un chiffre, un caractère spécial (#?!@$%^¨&*-).</small>';  
      $error = true;
    }elseif ($_POST['password'] !== $_POST['repeat_password']){
      $errorPassword = '<small class="text-color-danger">Les mots de passe ne correspondent pas</small>';

      $error = true;
    }

  // Exo : si l'utilisateur a correctement rempli le formulaire, exécuter la requête d'insertion en BDD (prepare + bindValue + execute), on redirige l'internaute vers la page connexion.php.
  if(!isset($error)){
    // Le mot de passe n'est jamais conservé en clair dans la base de données.
    // password_bash permet de créer une clé de hachage du mot de passe dans la BDD.

    $data = $connect_db->prepare("INSERT INTO user(password, firstName, lastName, email, city, zipcode, address) VALUES (:password, :firstName, :lastName, :email, :city, :zipcode, :address)");
    $data->bindValue(':password', password_hash($_POST['password'], PASSWORD_DEFAULT), PDO::PARAM_STR);
    $data->bindValue(':firstName', $_POST['firstName'], PDO::PARAM_STR);
    $data->bindValue(':lastName', $_POST['lastName'], PDO::PARAM_STR);
    $data->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
    $data->bindValue(':city', $_POST['city'], PDO::PARAM_STR);
    $data->bindValue(':zipcode', $_POST['zipcode'], PDO::PARAM_STR);
    $data->bindValue(':address', $_POST['address'], PDO::PARAM_STR);
    $data->execute();


    // On stocke dans le fichier de session de l'utilisateur, le fichier de session est stocké côté serveur et accessible via la superglobale $_SESSION et accessible sur n'importe quelle page du site, on stocke ici un message (message flash) dans le fichier de session de l'utilisateur.
    $_SESSION['msgRegisterValidate'] = '<div class="bg-success p-3 text-white text-center">Votre inscription est validée. Vous pouvez dès à présent vous connecter</div>';

    header('location: connexion.php');
  }   
}




// 3. Afficher un message d'erreur si le champs email est vide.
//   if(empty($_POST['email'])){
//    // echo "Le champs email est vide";

//    $errorEmail = 'Erreur';
//    $error = true;
//   } 
 

// //  4. Contrôler la validité de l'email(filter_var).
// if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
//   // echo "non valide";
//    $errorEmail = 'erreur';
//    $error = true;
// }



//   6. Contrôler que les mots de passe correspondent.
// if(!=($_POST[]))
//   echo 


require_once('include/header.php');
?>

  <!-- inner page section -->
  <section class="inner_page_head">
    <div class="container_fuild">
      <div class="row">
        <div class="col-md-12">
          <div class="full">
            <h3>Créer votre compte</h3>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- end inner page section -->
  <!-- why section -->
  <section class="why_section layout_padding">
    <div class="container">
      <div class="row">
        <div class="col-lg-8 offset-lg-2">
          <div class="full">
            <form method="post" action="inscription.php">
              <fieldset>
                <input
                  type="text"
                  placeholder="Enter votre prénom"
                  name="firstName"
                   />
                <input
                  type="text"
                  placeholder="Enter votre nom"
                  name="lastName"
                   />
                  <?php if(isset($errorEmail)) echo $errorEmail; ?>
                <input
                  type="text"
                  placeholder="Entrez votre adresse e-mail"
                  name="email"
                 
                 
                   class="<?php if(isset($errorEmail)) echo 'border-danger'; ?>"
                   value="<?php if(isset($_POST['email'])) echo $_POST['email']; ?>"
                   />
                <input 
                  type="text"
                  placeholder="Entrer votre adresse"
                  name="address"
                   />
                <input
                  type="text"
                  placeholder="Entrer votre ville"
                  name="city"
                   />
                <input
                  type="text"
                  placeholder="Entrer votre code postal"
                  name="zipcode"
                   />
                   <?php if(isset($errorPassword)) echo $errorPassword; ?>
                <input
                  type="text"
                  placeholder="Enter votre mot de passe"
                  name="password"
                  class="<?php if(isset($errorPassword)) echo 'border-danger'; ?>"
                   />
                  
                <input                
                  type="text"
                  placeholder="Répétez votre mot de passe"
                  name="repeat_password"
                   />
                <input type="submit" value="Submit" name="submit" />
              </fieldset>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- end why section -->
  <!-- arrival section -->
  <!-- end arrival section -->
 
<?php
require_once('include/footer.php');
// On supprime le message de validation d'inscription dans la session, afin qu'il ne soit plus affiché à chaque visite sur la page d'authentification.
unset($_SESSION['msgRegisterValidate']);
?>