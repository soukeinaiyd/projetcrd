<?php
session_start();
require_once '../config/bdd.php';



/***************************************categorie************************************ */
/*********************create************************* */ 
if (isset($_GET['action']) && $_GET['action'] ==
 'category-create'){

    $name=strip_tags(trim($_POST['name']));

    if (!empty($name) && strlen($name)<=45){
     $req = $bdd->prepare('INSERT INTO categorie (nom) VALUES (:name)');// prépare une requete sql
    $req->bindParam(':name', $_POST['name'], PDO::PARAM_STR);
    $req->execute();
    $_SESSION['notification']=[
        'type' => 'succes',
        'message' =>'la catégorie est bien créée'
    ];
    //message succes 
    }else{
        //message d'erreur
        $_SESSION['notification']=[
            'type' => 'danger',
            'message' =>'Une erreur est survenue lors de la création de la catégorie'
        ]; 
    }
    header('location:../index.php?page=admin');
}
/*********************update************************* */ 
if (isset($_GET['action']) && $_GET['action'] ==
 'category-update'){
    
}
/*********************delate************************* */ 
if (isset($_GET['action']) && $_GET['action'] ==
 'category-delete') {
    $req = $bdd->prepare('DELETE FROM category WHERE id = :id');
    $req->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
    $req->execute();
    
    // message de succes
    header('location:../index.php?page=admin');
}

/***************************************article************************************ */
if (isset($_GET['action']) && $_GET['action'] ==
 'article-create'){
//nettoyage des données
$title=strip_tags(trim($_POST['title']));
$content=strip_tags(trim($_POST['content']));
$alt=strip_tags(trim($_POST['alt']));
$published=strip_tags(trim($_POST['published']));
$author=strip_tags(trim($_POST['author']));
$category = (INT)$_POST['category'];

$errorMessage ='<p>Merci de vérifier les points suivants:</p>';
$validation=true;
//vérification du titre
if (empty($title) ||  strlen($title) > 100){
    $errorMessage .='<p>- le champ titre est obligatoire est doit comporter moins de 100 caratères.</p>';
    $validation=false;
}
if (empty($content) ||  strlen($content) > 65535){
    $errorMessage .='<p>- le champ contenu est obligatoire est doit comporter moins de 65535 caratères.</p>';
    $validation=false;
}

if (empty($alt) ||  strlen($alt) > 100){
    $errorMessage .='<p>- le champ "text alternatif" est obligatoire est doit comporter moins de 100 caratères.</p>';
    $validation=false;
}


if (empty($published) ||  ($published != 'true'&& $published != 'false')){
    $errorMessage .='<p>- le champ "publie" est obligatoir.</p>';
    $validation=false;
}

if (empty($author) ||  strlen($author) > 100){
    $errorMessage .='<p>- le champ contenu est obligatoire est doit comporter moins de 65535 caratères.</p>';
    $validation=false;
}
if (empty($category) || !is_int($category) ){
    $errorMessage .='<p>- problème de catégorie.</p>';
    $validation=false;
}

$authorizedFormats = [
'image/png',
'image/jpg',
'image/jpeg',
'image/jp2',
'image/webp',
'image/gif',
];

if(empty($_FILES['image']['name']) || $_FILES['image']['size']> 2000000 || !in_array($_FILES['image']['type'],$authorizedFormats))  {
    $errorMessage .='<p>- l\'image ne doit pas  dépasser 2 Mo et doit etre en format PNG,JPG,JPEG,JP2,WEBP,GIF.</p>';
    $validation = false;
    echo 'pb img';
}


if($validation == true){
 
$timestamp = time();
$format = strstr($_FILES['image']['name'],'.');
$imgName = $timestamp . $format;
move_uploaded_file($_FILES['image']['tmp_name'],'../assets/img/' . $imgName);


$req = $bdd->prepare('INSERT INTO article (titre, contenu, image, alt, date_publication, publie, auteur, category_id) VALUES (:titre, :contenu, :image, :alt, NOW(), :publie, :auteur, :category_id)');
$req->bindParam(':titre', $title, PDO::PARAM_STR);
$req->bindParam(':contenu', $content, PDO::PARAM_STR);
$req->bindParam(':image', $imgName, PDO::PARAM_STR);
$req->bindParam(':alt', $alt, PDO::PARAM_STR);
$req->bindParam(':publie', $published, PDO::PARAM_BOOL);
$req->bindParam(':auteur', $author, PDO::PARAM_BOOL);
$req->bindParam(':category_id', $category, PDO::PARAM_INT);
$req->execute();

$_SESSION['notification'] = [
 'type' => 'success',
 'message' => "L\'article a bien été créé"

];



}else{
    $_SESSION['notification'] = [
        'type' => 'danger',
        'message' => $errorMessage
       
    ];
   header('Location:../index.php?page=article-form');
}
}

if (isset($_GET['action']) && $_GET['action'] ==
 'article-update'){
    
}

// suppression d'un article
if (isset($_GET['action']) && $_GET['action'] == 
'article-delete'){

    if (isset($_GET['id']) && !empty($_GET['id'])){
        // traitement

        $reqImage = $bdd->prepare('SELECT image FROM article WHERE id=:id');
        $reqImage->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
        $reqImage->execute();
        $nomImage = $reqImage->fetch();

       $localisationImage = '../assets/img/' .$nomImage['image'];
       if(file_exists($localisationImage)){
        unlink($localisationImage);
       }
       // suppression de l'article en base de données
         $reqSuppression = $bdd->prepare('DELETE FROM article WHERE id=:id');
         $reqSuppression->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
         $reqSuppression->execute();

// message de succès
        $_SESSION['notification'] = [
    'type' => 'success',
    'message' => 'L\'article a bien été supprimé'
        ];
    } else{
        $_SESSION['notification'] = [
        'type' => 'danger',
        'message' => 'L\'article a bien été supprimé '
        ];
    }
    
    header('Location:../index.php?page=admin');
}





/*----------------------------------contact---------------------------------------*/
if (isset($_GET['action']) && $_GET['action'] == 'contact') {

    $firstName = strip_tags(trim($_POST['first_name']));
    $lastName = strip_tags(trim($_POST['last_name']));
    $email= strip_tags(trim($_POST['email']));
    $object= strip_tags(trim($_POST['object']));
    $message = strip_tags(trim($_POST['message']));
    
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
 
     if (empty($email) ||  strlen($email) > 100){
         $errorMessage .='<p>- L\'email est obligatoire est doit comporter moins de 45 caratères.</p>';
         $validation=false;
     }
 
     if (empty($object) ||  strlen($object) > 6){
         $errorMessage .='<p>- l\'objet est obligatoire .</p>';
         $validation=false;
     }
 
     if (empty($message) ||  strlen($message) > 1000){
         $errorMessage .='<p>- le message est obligatoire est doit comporter moins de 1000 caratères.</p>';
         $validation=false;
     }
 
     if ($validation == true){
         
         $destinataire = '';
         $emetteur = '';
         $titre = '';
         $contenu= '';
         $entetes= '';
 
         if( $object == 'emploi'){
             $destinataire = 'techno.souka@gmail.com';
         }else if  ($object == 'bug'){
             $destinataire = 'techno.souka@gmail.com';
         }else if  ($object == 'info'){
             $destinataire = 'techno.souka@gmail.com';
         }else   {
             $destinataire = 'techno.souka@gmail.com';
         }
 
         $emetteur = $email;
         $titre = 'Nouvelle demande de contact - ' . $object;
         $contenu = $message;
 
         $entetes = 'MIME-Version: 1.0' . "\r\n" .
         'Content-type: text/html; charset=utf-8' . "\r\n" .
         'From: ' . $emetteur ."\r\n";
  
         if (mail($destinataire, $titre, $contenu , $entetes)) {
             $_SESSION['notification'] = [
                 'type' => 'success',
                 'message' => 'Votre demande a bien prise en compte.Nous vous répondrons dans les plus bref délais'
             ];
         } else {
             $_SESSION['notification'] = [
                 'type' => 'danger',
                 'message' => 'une erreur est survenue lors de l\'envoi  de  votre message'
             ];
         }
     } else {
         $_SESSION['notification'] = [
             'type' => 'danger',
             'message' => $errorMessage
         ];
     }
            
     header('Location:../index.php?page=contact');
 }
 
 ?>