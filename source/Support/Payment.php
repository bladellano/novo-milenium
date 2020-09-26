<?php

namespace Source\Support;

class Payment
{
    private $apiUrl;
    private $apiKey;
    private $endPoint;
    private $build;
    private $callback;

    public function __construct()
    {
        $this->apiUrl = "https://sandbox.asaas.com/api/v3";
        $this->apiKey = ASAAS_API_KEY;
    }

    /**
     * Undocumented function createCard - constrói o cartão
     * @param string $holder_name nome completo
     * @param string $card_number nº do cartão
     * @param string $expiration_date data de expiração do cartão
     * @param integer $cvv código de segurança
     * @return Payment
     */
    public function createCard(string $holder_name, string $card_number, string $expiration_date, int $cvv): Payment
    {
        $this->endPoint = '/cards';
        $this->build = [
            'holder_name' => $holder_name,
            'number' => $card_number,
            'expiration_date' => $expiration_date,
            'cvv' => $cvv
        ];
        $this->post();
        return $this;
    }
    public function listPaymentsWithBoleto()
    {
        $this->endPoint = "/payments?billingType=BOLETO";
        $this->get();
        return $this;
    }
    public function listPaymentsWithCard()
    {
        $this->endPoint = "/payments?billingType=CREDIT_CARD";
        $this->get();
        return $this;
    }
    public function withCard($customer, $creditCard, $creditCardHolderInfo): Payment
    {
        $this->endPoint = "/payments";
        $this->build = [
            "customer" => $customer['customer'],
            "billingType" => $customer['billingType'],
            "dueDate" => $customer['dueDate'],
            "value" => $customer['value'],
            "description" => $customer['description'],
            "externalReference" => $customer['externalReference'],
            "creditCard" => $creditCard,
            "creditCardHolderInfo" => $creditCardHolderInfo
        ];

        $this->post();
        return $this;
    }
    public function withCard_(int $order_id, CreditCard $card, string $amount, int $installments): Payment
    {
        $this->endPoint = "/transactions";
        $this->build = [
            'payment_type' => 'credit_card',
            'amount' => $amount,
            'installments' => $installments,
            'card_id' => $card->hash,
            'metadata' => [
                'orderId' => $order_id
            ]
        ];
        $this->post();
        return $this;
    }

    public function callback()
    {
        return $this->callback;
    }
    public function post()
    {
        $url = $this->apiUrl . $this->endPoint;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($this->build));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "access_token: $this->apiKey"
        ));

        $this->callback = json_decode(curl_exec($ch));
        curl_close($ch);
    }

    public function get()
    {
        $url = $this->apiUrl . $this->endPoint;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "access_token: $this->apiKey"
        ));

        $this->callback = json_decode(curl_exec($ch));
        curl_close($ch);
    }
    public function delete()
    {
    }
}
