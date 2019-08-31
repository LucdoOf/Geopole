<?php
if(!isset($type)) return;
switch ($type){
    case "last_articles":
        $posts = Post::getLastCreated(6);
        for($i = $page*2; $i < $page*2+2; $i++){
            $post = $posts[$i];
            displayPost($post);
        }
        break;
    case "most_read_articles":
        $post = Post::getMostRead(6);
        displayPost($post);
        break;
    case "category-articles":
        $category = $id == null ? null : new Category($id);
        $articles = Post::getLastCreated(3, $category);
        foreach ($articles as $article){
            displayCategoryPost($article);
        }
        break;
}

function displayPost($post){
    ?>
    <article>
        <h3 class="title"><?= $post->getTitle(); ?></h3>
        <p class="content"><?= $post->getDescription(); ?></p>
    </article>
    <?php
}

function displayCategoryPost($post){
    ArticleParser::parseMin($post);
}