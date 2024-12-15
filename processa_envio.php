<?php
    //recupera os dados pelo post, tudo que der submit no front sera enviado pra esse documento
    //print_r($_POST);
    //importa a biblioteca miller
    require "./Bibliotecas/PHPMiler/Exception.php";
    require "./Bibliotecas/PHPMiler/PHPMailer.php";
    //recebimento de email
    require "./Bibliotecas/PHPMiler/POP3.php";
    //envio de email
    require "./Bibliotecas/PHPMiler/SMTP.php";
    
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    class Mensagem{
        private $para = null;
        private $assunto = null;
        private $mensagem = null;

        public function __get($atributo){
            return $this->$atributo;
        }

        public function __set($atributo, $valor){
            $this->$atributo = $valor;
        }
        public function mensagemValida(){
            //valida se algum campo esta preenchido corretamente
            if(empty($this->para) || empty($this->assunto) || empty($this->mensagem)){
                return false;
            }else{
                return true;
            }
        }
    }

    $mensagem = new Mensagem();

    //armazena as informacoes do formulario dentro do objeto mensagem
    $mensagem-> __set('para', $_POST['para']);
    $mensagem-> __set('assunto', $_POST['assunto']);
    $mensagem-> __set('mensagem', $_POST['mensagem']);

    //print_r($mensagem);

    //se estiver preenchido
    if (!$mensagem->mensagemValida()) {
        echo "Por favor, preencha todos os campos obrigatórios!";
        exit();
    }    

    $mail = new PHPMailer(true);
    try {
        //Server settings
        $mail->isSMTP();                                            //madando via smtp
        $mail->Host       = 'smtp.gmail.com';                       //Link do smtp do gmail (app que vamos usar para mandar os email)
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'samuelostenta356@gmail.com';           //nome do utilizador do servidor
        $mail->Password   = 'uaiebouvfiakmeer';                            //senha do utilizador do servidor
        $mail->SMTPSecure = 'tls';                                  //tipo de criptografia que sera usada
        $mail->Port       = 587;                                    //porta uq sera usada para a criptografia

        //Recipients
        $mail->setFrom('samuelostenta356@gmail.com', 'Samuel Costa');
        //caso precise apenas adicionar addAddress para um novo destinatario
        $mail->addAddress('samuelostenta356@gmail.com', 'samuel');
        //$mail->addAddress('ellen@example.com');
        //e-mail que sera enviado a resposta do email
        //$mail->addReplyTo('info@example.com', 'Information');

        //$mail->addCC('cc@example.com'); copia do email
        //$mail->addBCC('bcc@example.com'); copia do emeil

        //anexos
        //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
       // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

        //conteudo
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Trablaho php'; // assunto 
        $mail->Body    = $mensagem->__get('mensagem'); // corpo da mensagem
        //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients'; caso nao ha p[ossibilidade de poder usar html
        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo "verifique se o e-mail esta correto, ou se preencheu todos os dados!";
        echo '</br>';
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
    




    
    