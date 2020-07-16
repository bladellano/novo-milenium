<?php

use \Sts\Page;

$app->get('/', function () {

    // $listScripts = [
    //     'typewriter-effect.js'
    // ];
    $listScripts = [
    ];
    $page = new Page();
    $page->setTpl("main", [
        'scripts' => $listScripts
    ]);
});

$app->get('/reformular-site-antigo', function () {
    $page = new Page();
    $page->setTpl("reformular-site-antigo");
});

$app->get('/criacao-artes-site-redes-sociais', function () {
    $page = new Page();
    $page->setTpl("criacao-artes-site-redes-sociais");
});

$app->get('/registro-dominio', function () {
    $page = new Page();
    $page->setTpl("registro-dominio");
});
$app->get('/formas-pagamento', function () {
    $page = new Page();
    $page->setTpl("formas-pagamento");
});
$app->get('/hospedagem', function () {
    $page = new Page();
    $page->setTpl("hospedagem");
});
$app->get('/hotsites', function () {
    $page = new Page();
    $page->setTpl("hotsites");
});
$app->get('/landing-pages', function () {
    $page = new Page();
    $page->setTpl("landing-pages");
});
$app->get('/criacao-logomarca-cartoes-visita', function () {
    $page = new Page();
    $page->setTpl("criacao-logomarca-cartoes-visita");
});
$app->get('/portfolio-sites-ecommerces', function () {
    $page = new Page();
    $page->setTpl("portfolio-sites-ecommerces");
});
$app->get('/portfolio-artes-digitais', function () {
    $page = new Page();
    $page->setTpl("portfolio-artes-digitais");
});
$app->get('/consultoria-seo', function () {
    $page = new Page();
    $page->setTpl("consultoria-seo");
});
$app->get('/campanhas-para-instagram', function () {
    $page = new Page();
    $page->setTpl("campanhas-para-instagram");
});

$app->get('/site-para-advogados', function () {
    $page = new Page();
    $page->setTpl("site-para-advogados");
});
$app->get('/site-para-escritorios', function () {
    $page = new Page();
    $page->setTpl("site-para-escritorios");
});
$app->get('/site-para-contabilidade', function () {
    $page = new Page();
    $page->setTpl("site-para-contabilidade");
});
$app->get('/site-para-youtubers', function () {
    $page = new Page();
    $page->setTpl("site-para-youtubers");
});
$app->get('/site-para-representantes', function () {
    $page = new Page();
    $page->setTpl("site-para-representantes");
});
$app->get('/site-para-clinicas', function () {
    $page = new Page();
    $page->setTpl("site-para-clinicas");
});
$app->get('/site-para-microempreendedores', function () {
    $page = new Page();
    $page->setTpl("site-para-microempreendedores");
});
$app->get('/site-para-petshop', function () {
    $page = new Page();
    $page->setTpl("site-para-petshop");
});
$app->get('/site-para-transportadoras', function () {
    $page = new Page();
    $page->setTpl("site-para-transportadoras");
});
