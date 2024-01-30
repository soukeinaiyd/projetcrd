<?php
    require_once './config/bdd.php';
// récupération des données
    $reqArticles = $bdd->query('SELECT article.id, titre, date_publication, categorie.nom AS categorie FROM article INNER JOIN categorie  ON article.category_id = categorie.id ORDER BY article.id DESC');
    $articles = $reqArticles->fetchALL();
    $reqCategories = $bdd->query('SELECT * FROM categorie');
    $categories    = $reqCategories->fetchALL();
?>

<h1>Espace administrateur</h1>

<h2>Articles</h2>

<table class="table table-hover">
    <thead>
        <tr>
            <th>N°</th>
            <th>TITRE</th>
            <th>DATE DE PUBLICATION</th>
            <th>CATÉGORIE</th>
            <th>ACTIONS</th>
        </tr>
    </thead>
    <tbody>
    <?php
            if (!empty($articles)) {
                 foreach($articles as $article):
     ?>
         <tr>
                 <td><?= $article['id'] ?></td>
                 <td><?= $article['titre'] ?></td>
                 <td><?= $article['date_publication'] ?></td>
                 <td><?= $article['categorie'] ?></td>
                 <td>
                   <a href="#"><i class="bi bi-pencil-square"></i></a>
                   <a href="./functions/traitement.php?action=article-delete&id=<?= $article['id'] ?>" class="text-danger"><i class="bi bi-trash"></i></a>
                 </td>
         </tr>
        <?php 


        
             endforeach;          
            } else {
                echo '
                    <tr>
                        <td colspan=5>Aucun article trouvé</td>
                    </tr>
                ';
            }
            
       ?>
        <!-- liste des articles -->
    </tbody>
</table>

<!-- formulaire article -->
<div  class="text-end">
    <a href="index.php?page=article-form" class="btn
    btn-primary">rédiger un article </a>
        </div>
<h2>Catégories</h2>

<table class="table table-hover text-center my-5">
    <thead>
        <tr>
            <th>N°</th>
            <th>NOM</th>
            <th>ACTIONS</th>
        </tr>
    </thead>
    <tbody>
     <?php foreach ($categories as $categorie):?>
        <tr>
           <td><?= $categorie['id']?></td>
           <td><?= $categorie['nom']?></td>
           <td>
              <a href="#"><i class="bi bi-pencil-square"></i></a>
              <a href="./functions/traitement.php?action=category-delete&id=<?= $categorie['id'] ?>"><i class="bi bi-trash"></a>
           </td>  
        </tr>
      <?php endforeach ?>

        <!-- liste des catégories -->
    </tbody>
</table>
<!-- formulaire catégorie -->
<form action="./functions/traitement.php?
action=category-create" method="POST">
    <label for="name">Name</label>
    <input type="text" name="name" maxlength="45" required>
    <input type="submit" value="créer une catégorie ">
</form>