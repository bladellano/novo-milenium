<?php

session_start();

#include('test-user-asaas.php');
#include('test-cadastro-usuario.php');
#include('test-payment.php');

use \Source\Page;
use \Source\Support\Mailer;
use \Source\Model\Blog;
use \Source\Model\PageSite;
use \Source\Model\PhotosAlbums;
use \Source\Model\Convenio;
use \Source\Model\User;
use \Source\Model\Address;
use Source\Model\Plans;
use \Source\Support\Payment;


$app->get('/convenios', function () {
    //=================//
    //====CONVÊNIOS====//
    //=================//
    $search = (isset($_GET['search'])) ? $_GET['search'] : "";
    $page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;
    if ($search != '') {
        $pagination = Convenio::getPageSearch(trim($search), $page);
    } else {
        $pagination = Convenio::getPage($page, 6);
    }
    $pages = [];

    for ($x = 0; $x <  $pagination['pages']; $x++) {
        array_push($pages, [
            'href' => '/convenios?' . http_build_query([
                'page' => $x + 1,
                // 'search' => $search
            ]),
            'text' => $x + 1
        ]);
    }

    $page = new Page();

    $page->setTpl("convenios", [
        "convenios" => $pagination['data'],
        "search" => $search,
        "pages" => $pages,
        "title" => 'Convênios'
    ]);
});

// PAYMENTS

$app->post('/payment-plans', function () {
    $pay = new Payment;
    $customer = [
        "customer" => $_POST['idcustomer'],
        "billingType" => $_POST['billingType'],
        "dueDate" => date("Y-m-d"),
        "value" => $_POST['desvalueplan'],
        "description" => "",
        "externalReference" => md5(uniqid()),
        "postalService" => false //Quando Boleto.
    ];
    // echo '<pre>'; print_r($customer);exit;

    $creditCard = [
        "holderName" => $_POST['holderName'],
        "number" => $_POST['number'],
        "expiryMonth" => $_POST['expiryMonth'],
        "expiryYear" => $_POST['expiryYear'],
        "ccv" => $_POST['ccv']
    ];
    // echo '<pre>'; print_r($creditCard);exit;

    $creditCardHolderInfo = [
        "name" => $_POST['desperson'],
        "email" => $_POST['desemail'],
        "cpfCnpj" => $_POST['descpf'],
        "postalCode" => $_POST['deszipcode'],
        "addressNumber" => $_POST['desnumber'],
        "addressComplement" => $_POST['descomplement'],
        "phone" => NULL,
        "mobilePhone" => $_POST['nrphone'],
    ];
    // echo '<pre>'; print_r($creditCardHolderInfo);exit;

    /* BillingType */
    if ($_POST['billingType'] == 'BOLETO') {
        $result = $pay->withBoleto($customer);
        if ($result->callback()->id) {
            $aJson = [
                "success" => true,
                "msg" => "Transação realizada com sucesso. Verifique seu e-mail",
                "urlBoleto" => $result->callback()->invoiceUrl,
                "pdfBoleto" => $result->callback()->bankSlipUrl,
                "billingType" => $_POST['billingType'],
            ];
            die(json_encode($aJson));
        } else {
            $aJson = ["success" => false, "msg" => "Transação recusada, verifique os dados preenchidos!"];
            die(json_encode($aJson));
        }
    } elseif ($_POST['billingType'] == 'CREDIT_CARD') {

        $result = $pay->withCard($customer, $creditCard, $creditCardHolderInfo);
        if ($result->callback()->id) {
            $aJson = [
                "success" => true,
                "msg" => "Transação realizada com sucesso. Verifique seu e-mail",
                "billingType" => $_POST['billingType']
            ];
            die(json_encode($aJson));
        } else {
            $aJson = ["success" => false, "msg" => "Transação recusada, verifique os dados preenchidos!"];
            die(json_encode($aJson));
        }
    }
});

$app->post('/logout-user', function () {
    unset($_SESSION['User']);
    unset($dataUserFull);
});

$app->post('/logging-in', function () {

    $data = filter_var_array($_POST, FILTER_SANITIZE_STRING);
    $login = new User();
    $address = new Address();

    try {
        $login->login($data['deslogin'], $data['despassword']);
        $idperson = $_SESSION['User']['idperson'];
        $address->getAddress($idperson);
        $dataUserFull = array_merge($_SESSION['User'], $address->getValues());
        $aJson = ["success" => true, "data" => $dataUserFull];
        die(json_encode($aJson));
    } catch (\Exception $e) {
        $aJson = ["success" => false, "msg" => $e->getMessage()];
        die(json_encode($aJson));
    }
});

$app->post('/create-user', function () {
    //==================//
    //====INSERT USER====//
    //==================// 
    $user = new User();
    $address = new Address();
    $customer = new Payment();

    $data = filter_var_array($_POST, FILTER_SANITIZE_STRING);

    $_SESSION['recoversPost'] = $_POST;

    if (empty($data['descomplement'])) unset($data['descomplement']);

    if (in_array("", $data)) {
        User::setError('Preencha todos os campos.');
        header("Location: /cadastro-cliente");
        exit;
    }

    /*Sanitize cpf*/
    $data['descpf'] = str_replace([".", "-"], "", $data['descpf']);

    /*Registra primeiramente na api ASAAS o cliente.*/
    $idCustomer = $customer->createClient($data)->callback()->id;
    $dataUser = [
        "desperson" => $_POST['desperson'],
        "desemail" => $_POST['desemail'],
        "deslogin" => $_POST['deslogin'],
        "despassword" => $_POST['despassword'],
        "nrphone" => $_POST['nrphone'],
        "inadmin" => 0,
        "idcustomer" => $idCustomer,
        "descpf" => str_replace([".", "-"], "", $_POST['descpf'])
    ];
    $user->setData($dataUser);
    $user->save();
    $idPerson = $user->getidperson();

    $addressUser = [
        "desaddress" => $_POST['desaddress'],
        "desnumber" => $_POST['desnumber'],
        "descomplement" => utf8_encode($_POST['descomplement']),
        "descity" => ($_POST['descity']),
        "desstate" => $_POST['desstate'],
        "descountry" => $_POST['descountry'],
        "deszipcode" => str_replace([".", "-"], "", $_POST['deszipcode']),
        "desdistrict" => $_POST['desdistrict'],
        "idperson" => $idPerson,
    ];

    $address->setData($addressUser);
    $address->save();

    //ENVIAR EMAIL PARA O CLIENTE;
    User::setSuccess('Cadastro realizado com sucesso.');
    unset($_SESSION['recoversPost']);
    header("Location: /cadastro-cliente");
    exit;
});

$app->get('/cadastro-cliente', function () {
    //========================//
    //====FORM INSERT USER====//
    //========================//  
    $page = new Page();
    $page->setTpl("create-user", [
        'msgError' => User::getError(),
        'msgSuccess' => User::getSuccess()
    ]);
});

$app->get('/', function () {
    //==============//
    //=====MAIN=====//
    //==============//
    $posts = Blog::lisAll();
    $plans = Plans::lisAll();
    $convenios = Convenio::lisAll();

    foreach ($posts as &$post) {
        $post['m'] = (new DateTime($post['created_at']))->format("m");
        $post['d'] = strftime('%b', strtotime($post['created_at']));
        $post['category'] = Blog::getCategory($post['id_articles_categories']);
    }
    $page = new Page();

    $page->setTpl("main", [
        'posts' => $posts,
        'plans' => $plans,
        'convenios' => $convenios,
        'scripts' => ['neurologic.js']
    ]);
});

$app->get('/galeria', function () {
    //==========================//
    //====SHOW PHOTOS/ALBUMS====//
    //==========================//  
    $albums = new PhotosAlbums();
    $result = $albums->lisAll();

    $page = new Page();
    $page->setTpl("albums", ['albums' => $result]);
});

$app->get('/galeria/:id_album', function ($id_album) {
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
    $page->setTpl("post", ['post' => $result, 'posts' => $allPosts]);
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
