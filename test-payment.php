<?php

use \Source\Support\Payment;

echo "<h1>Testando cartão de crédito</h1>";

$customer = [
    'customer' => 'cus_000003039858',
    'billingType' => 'CREDIT_CARD',
    'dueDate' => '2020-09-25',
    'value' => 666.66,
    'description' => 'Pedido 666',
    'externalReference' => '666'
];

$creditCard = [
    "holderName" => "Felipe Cardoso Apoena",
    "number" => "5509970157075879",
    "expiryMonth" => "01",
    "expiryYear" => "2022",
    "ccv" => "941"
];

$creditCardHolderInfo = [
    "name" => "Caio Dellano Nunes da Silva",
    "email" => "bladellano@gmail.com",
    "cpfCnpj" => "24971563792",
    "postalCode" => "67033-160",
    "addressNumber" => "277",
    "addressComplement" => null,
    "phone" => "9138010919",
    "mobilePhone" => "91998781877"
];

// 1-tb_persons;
// 2-tb_users;
// 3-tb_address;

// 1-cart
// 2 - tb_cartsproducts
// 2-order


//id do cliente/customer;
//tipo de pgt;
//data do pedido;
//valor do pedido;
//descr do pedido;
//id do pedido para referencia externa;
//---------------
//nome completo do cartao;
//numero do cartao
//mes // ano // cvv
//----------------
//nome,email,cpfCnpj,cep,n,complemento,telefone,celular,

$pay = new Payment();
#$pay->withCard($customer,$creditCard,$creditCardHolderInfo);
#if($pay->callback()->id)
    #echo '<h3>Pagamento Efetuado!</h3>';
#exit;


echo '<h1>Listando pedidos</h1>';
$pay->listPaymentsWithBoleto();
$listPayments = $pay->callback()->data;
foreach($listPayments as $payments){
    echo $payments->id." | ".$payments->value,'<br>';
}

exit;







