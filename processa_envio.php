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
        public $status = array('codigo_status' => null, 'descricao_status'=> '');

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
        //caso o usuario tente acessar outro campo sem colocar as informacoes retornara para index
        header('Location: index.php');
    }    

    $mail = new PHPMailer(true);
    try {
        //Server settings
        $mail->isSMTP();                                            //madando via smtp
        $mail->Host       = 'smtp.gmail.com';                       //Link do smtp do gmail (app que vamos usar para mandar os email)
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'samuelostenta356@gmail.com';           //nome do utilizador do servidor
        $mail->Password   = 'uaiebouvfiakmeer';                     //senha do utilizador do servidor
        $mail->SMTPSecure = 'tls';                                  //tipo de criptografia que sera usada
        $mail->Port       = 587;                                    //porta uq sera usada para a criptografia

        //Recipients
        $mail->setFrom('samuelostenta356@gmail.com', 'Samuel Costa');
        //caso precise apenas adicionar addAddress para um novo destinatario
        $mail->addAddress($mensagem->__get('para'));
        //$mail->addAddress('ellen@example.com');
        //e-mail que sera enviado a resposta do email
        //$mail->addReplyTo('info@example.com', 'Information');

        //$mail->addCC('cc@example.com'); copia do email
        //$mail->addBCC('bcc@example.com'); copia do emeil

        //anexos
        //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
       // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

        //conteudo
        $mail->isHTML(true);                          //Set email format to HTML
        $mail->Subject = $mensagem->__get('assunto'); // assunto 
        $mail->Body    = $mensagem->__get('mensagem'); // corpo da mensagem
        //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients'; caso nao ha p[ossibilidade de poder usar html
        $mail->send();
        //atualiza o status da mensagem enviada
        $mensagem->status['codigo_status'] = 1;
        $mensagem->status['descricao_status'] = 'Email enviado com sucesso';
    } catch (Exception $e) {

        $mensagem->status['codigo_status'] = 2;
        $mensagem->status['descricao_status'] = 'Nao foi possivel mandar sua mensagem. Detalhe do erro:' . $mail->ErrorInfo;
    }
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <title>App_enviaemail</title>
    </head>
    <body>

        <div class="container">
            <div class="py-3 text-center">
				<img class="d-block mx-auto mb-2" src="logo.png" alt="" width="72" height="72">
				<h2>Send Mail</h2>
				<p class="lead">Seu app de envio de e-mails particular!</p>
			</div>

            <div class="row">
            <div class="col-md-12">
                <?php if ($mensagem->status['codigo_status'] == 1) { ?>
                    <div class="container">
                        <div class="display-4 text-success">Sucesso</div>
                        <p><?= $mensagem->status['descricao_status'] ?></p>
                        <a href="index.php" class="btn btn-success btn-lg mt-5 text-white">Voltar</a>
                    </div>
                <?php } else { ?>
                    <div class="container">
                        <div class="display-4 text-danger">Erro</div>
                        <p><?= $mensagem->status['descricao_status'] ?></p>
                        <a href="index.php" class="btn btn-success btn-lg mt-5 text-white">Voltar</a>
                    </div>
                <?php } ?>
            </div>
        </div>
        </div>
        
    </body>
    </html>
