<?php 

// header("Content-type: application/json; charset=utf-8");
 
$body = '<!DOCTYPE html>
<html>
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <title>Template Email</title>
      <link rel="stylesheet" href="">
   </head>
   <body bgcolor="#E0E0E0">
      <center>
         <table border=0 width="650px" bgcolor="#FFF" 
            style="font-family: arial; box-shadow: 2px 2px 10px #CCC;border:1px solid #999">
            <tr>
               <th align="center" bgcolor="#444" style="color:#FFF">
                  <h3>FORMULÁRIO DE TENTATIVA CONTATO</h3>
               </th>
            </tr>
            <tr>
               <td>
                  <table width="100%" style="padding:20px">
                     <tr>
                        <td  align="right" width="40%"><b>Nome:</b></td>
                        <td width="60%">'.$_POST['name'].'</td>
                     </tr>
                     <tr>
                        <td  align="right" width="40%"><b>E-mail:</b></td>
                        <td width="60%">'.$_POST['email'].'</td>
                     </tr>
                     <tr>
                        <td  align="right" valign="top" width="40%"><b>Telefone:</b></td>
                        <td width="60%">'.$_POST['tel'].'</td>
                     </tr>
                     <tr>
                        <td  align="right" width="40%"><b>Empresa:</b></td>
                        <td width="60%">'.$_POST['company'].'</td>
                     </tr>
                     <tr>
                        <td  align="right" valign="top" width="40%"><b>O que o cliente deseja:</b></td>
                        <td width="60%">'.$_POST['need'].'</td>
                     </tr>
                     <tr>
                        <td  align="right" valign="top" width="40%"><b>Período para entrar em contato:</b></td>
                        <td width="60%">'.$_POST['period'].'</td>
                     </tr>
                    
                  </table>
               </td>
            </tr>
            <tr>
               <td align="center">
                  <p style="color:#999">
                     © 2020 Dellano Sites. Todos os direitos reservados.<br/>
                     Enviado a partir: '.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'<br/>
                     Ip de envio: '.$_SERVER['SERVER_ADDR'].'
                  </p>
               </td>
            </tr>
         </table>
      </center>
   </body>
</html>';

#Utilizar quando a porta smtp for 587.
/*require_once("class/php-mailer/Mailer.php");
$email = new Mail();
$result = $email->send("bladellano@gmail.com",$_POST['email'],$_POST['name'],"FORMULÁRIO DE TENTATIVA CONTATO",$body);
die(json_encode($result));
exit;*/

#Utilizar quando a porta smtp for 465.
require_once("class/mail-native/MailNative.php");
$oMail = new MailNative("caio@dellanosites.com.br",$_POST['email'],$_POST['need']);
$oMail->setBody($body);
$result = $oMail->send();
die(json_encode($result));


