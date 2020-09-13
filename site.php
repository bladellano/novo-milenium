<?php

use \Source\Page;
use \Source\Support\Mailer;
use \Source\Model\Blog;
use \Source\Model\PageSite;
use \Source\Model\PhotosAlbums;

$app->get('/', function () {
    //==============//
    //=====BLOG=====//
    //==============//
    $posts = Blog::lisAll();
    foreach ($posts as &$post) {
        $post['m'] = (new DateTime($post['created_at']))->format("m");
        $post['d'] = strftime('%b', strtotime($post['created_at']));
    }

    $page = new Page();
    $page->setTpl("main", ['posts' => $posts]);
});

$app->get('/galeria',function(){
    //==========================//
    //====SHOW PHOTOS/ALBUMS====//
    //==========================//  
    $albums = new PhotosAlbums();
    $result = $albums->lisAll();

    $page = new Page();
    $page->setTpl("albums", ['albums' => $result]);

});

$app->get('/galeria/:id_album',function($id_album){
    //===================//
    //====SHOW PHOTOS====//
    //===================//
    $albums = new PhotosAlbums();
    $albums->getPhotos((int) $id_album);
    $page = new Page();
    $page->setTpl("photos", ['photos' => $albums->getValues()]);

});

$app->get('/:slug', function ($slug) {
    //==================//
    //====SHOW PAGES====//
    //==================//   
    $pageSite = new PageSite();
    $pageSite->getWithSlug($slug);
    $result = $pageSite->getValues();
    
    $page = new Page();
    $page->setTpl("page", ['data' => $result]);
});

$app->get('/post/:slug', function ($slug) {
    //=================//
    //====SHOW POST====//
    //=================//
    $post = new Blog();
    $post->getWithSlug($slug);
    $result = $post->getValues();

    $allPosts = Blog::lisAll();

    $page = new Page();
    $page->setTpl("post", ['post' => $result,'posts'=>$allPosts]);
});

$app->post('/email-sent', function () {

    $mailer = new Mailer(
        $_POST["email"],
        $_POST["name"],
        "Entrando em Contato.", //Assunto
        "email-sent", //Template
        $_POST
    );

    if ($mailer->send())
        die(json_encode(['success' => true, 'msg' => 'E-mail enviado com sucesso!']));
    die(json_encode(['success' => false, 'msg' => 'Problemas ao enviar o e-mail!']));
});
