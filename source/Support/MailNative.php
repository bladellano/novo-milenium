<?php

namespace Source;

error_reporting(0);

class MailNative
{

    private $to;
    private $from;
    private $subject;
    private $body;
    private $headers;

    public function __construct($to, $from, $subject)
    {
        $this->to = $to;
        $this->from = $from;
        $this->subject = $subject;
        $this->setHeader();
    }

    public function setHeader()
    {
        $this->headers =  "Content-Type:text/html; charset=UTF-8\n";
        $this->headers .= "From: " . $this->from . "\n";
        $this->headers .= "X-Sender:  " . $this->from . "\n";
        $this->headers .= "X-Mailer: PHP  v" . phpversion() . "\n";
        $this->headers .= "X-IP:  " . $_SERVER['REMOTE_ADDR'] . "\n";
        $this->headers .= "Return-Path:  <" . $this->from . ">\n";
        $this->headers .= "MIME-Version: 1.0\n";
    }

    public function setBody($body)
    {
        $this->body = $body;
    }

    public function send()
    {

        if (!mail($this->to, $this->subject, $this->body, $this->headers)) {
            return ['status' => false, 'message' => 'Não foi possível enviar a mensagem.'];
        } else {
            return ['status' => true, 'message' => 'Mensagem enviada com sucesso!'];
        }
    }
}
