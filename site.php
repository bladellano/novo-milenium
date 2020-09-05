<?php

use \Source\Page;
use \Source\Support\Mailer;
use \Source\Model\Post;

$app->get('/', function () {

    $posts = (new Post())->lisAll();

    foreach ($posts as &$post) {
        $post['m'] = (new DateTime($post['created_at']))->format("m");
        $post['d'] = strftime('%b', strtotime($post['created_at']));
    }

    $page = new Page();
    $page->setTpl("main", ['posts' => $posts]);
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
