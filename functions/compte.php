<?php
session_start();
require_once '../config/bdd.php';
/*************************connexion************ */

if ($_GET['action'] && $_GET['action']  == 'connexion'){
}
/*************************inscription************ */

if ($_GET['action'] && $_GET['action']  == 'inscription'){

    $firstName = strip_tags(trim($_POST['first_name']));
    $lastName = strip_tags(trim($_POST['last_name']));
    $email= strip_tags(trim($_POST['email']));
    $password= strip_tags(trim($_POST['password']));
    $passwordConfirm = strip_tags(trim($_POST['password_confirm']));

    $emailRegex = '/^\\S+@\\S+\\.\\S+$/';
    $passwordRegex = '/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-_]).{8,255}$/';

    $errorMessage .='<p>-Merci de vérifier les points suivants :</p>';
    $validation = true;

    if (empty($firstName) ||  strlen($firstName) > 45){
        $errorMessage .='<p>- le prenom est obligatoire est doit comporter moins de 45 caratères.</p>';
        $validation=false;
    }

    if (empty($lastName) ||  strlen($lastName) > 45){
        $errorMessage .='<p>- le nom est obligatoire est doit comporter moins de 45 caratères.</p>';
        $validation=false;
    }

    if (empty($email) ||  strlen($email) > 100 || !preg_match($emailRegex, $email)){
        $errorMessage .='<p>- L\'email est obligatoire est doit comporter moins de 100 caratères.</p>';
        $validation=false;
    }

    if (empty($password) ||  empty($passwordConfirm)  || $password != $passwordConfirm || !preg_match($passwordRegex, $password)){
        $errorMessage .='<p>- Le mot de passe est obligatoire est doit comporter moins de 20 caratères.</p>';
        $validation=false;
    }

    if ($validation === true) {
        $hashedPassword = password_hash($password, PASSWORD_ARGON2I);
        $req = $bdd->prepare('INSERT INTO utilisateur (email, password, prenom, nom) VALUES (:email, :password, :prenom, :nom)');
        $req->bindParam(':email', $email, PDO::PARAM_STR);
        $req->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
        $req->bindParam(':prenom', $firstName, PDO::PARAM_STR);
        $req->bindParam(':nom', $lastName, PDO::PARAM_STR);
        $req->execute();
        $_SESSION['notification'] = [
            'type' => 'success',
            'message' => 'Votre compte a bien été créé'
        ];
        header('Location: ../index.php?page=connexion');
    } else {
        $_SESSION['notification'] = [
            'type' => 'danger',
            'message' => $errorMessage
        ];
    }
        
        header('Location: ../index.php?page=inscription');
   
}

/*************************deconnexion************ */

if ($_GET['action'] && $_GET['action']  == 'deconnexion'){

}







?>