<?php
require_once './config/bdd.php';
$req = $bdd->query('SELECT article.*, categorie.nom AS nom_categorie FROM article INNER JOIN categorie ON article.category_id = categorie.id 
ORDER BY article.id DESC
LIMIT 3');
$articles = $req->fetchALL();
?>
<h1>Accueil</h1>
<div class="row">
    <?php foreach ($articles as $article): ?>
        <div class="col-4 p-2">
            <div class="card">
                <img src="./assets/img/<?= $article['image'] ?>" class="card-img-top" alt="<?= $article['alt'] ?>">
                <div class="card-body">
                    <h5 class="card-title"><?= $article['titre'] ?></h5>
                    <p class="card-text"><?= substr($article['contenu'], 0, 100) ?></p>
                    <a href="index.php?page=article&id=<?= $article['id']?>" class="btn btn-primary">Lire l'article</a>
                </div>
            </div>
        </div>
    <?php endforeach ?>
</div>