<?php
use Phalcon\Mvc\View;

class MyEmailSender {

    /**
     * Riceve i paramentri e invia il messaggio utilizzando il template email corretto
     *
     * @return boolean
     *  array parametri:
     *       'exhibitors' => $exhibitors,
     *       'evento' => $this->evento->descrizione, 
     *       'destinatari' => array('federico@desegno.it' => 'Federico Maddoli',...)
     *       'allegato' => $allegato
     *   
     */
    static function inviaEmail($application, $template, $parametri,$oggetto)
    {
        //\PhalconDebug::info("parametri:",$parametri);
        //$destinatari = get_object_vars($parametri['destinatari']);
        $destinatari = $parametri['destinatari'];
        // verifico che i destinatari dell'email siano impostati:
        if(!is_array($destinatari) || count($destinatari)==0){
            \PhalconDebug::info('Destinatari email non valorizzati');
            return false;
        }
        // invio email di conferma...
        $transport = (new Swift_SmtpTransport($application->config->swift->smtp, $application->config->swift->port, $application->config->swift->ssl))
        ->setUsername($application->config->swift->username)
        ->setPassword($application->config->swift->password);        

        // Create the Mailer using your created Transport
        $mailer = new Swift_Mailer($transport);

        if($application->view->exists('emailtemplates/'.$template)){

            \PhalconDebug::info('SI IL TEMPLATE ESISTE');
            $miaview = $application->view;

            $bodyemail = $miaview->getRender(
                'emailtemplates',
                $template,
                $parametri,
                function ($miaview) {
                    $miaview->setViewsDir('../app/views/');
                    $miaview->setRenderLevel(
                        View::LEVEL_LAYOUT
                    );

                }
            );
            sleep(1);

            // Create a message
            $message = (new Swift_Message($oggetto))
            ->setFrom(['iscrizioni@falacosagiustaumbria.it' => 'Iscrizioni Fa\' la cosa giusta Umbria' ])
            ->setTo($destinatari)
            ->setBody($bodyemail, 'text/html')
            ->addPart(strip_tags($bodyemail), 'text/plain');

            if(isset($parametri['replyto'])){
                $message->setReplyTo($parametri['replyto']);
            }

            if(isset($parametri['allegato']) && is_array($parametri['allegato'])){
                $allegato = $parametri['allegato'];
                $attachment = Swift_Attachment::fromPath($allegato['filepath'], $allegato['mimetype']);
                $message->attach($attachment);
            }

            // Send the message
            $result = $mailer->send($message);
            \PhalconDebug::info("result invio:",$result);
            if($result){
                return true;
            }
            else{
                return false;
            }
        }
        else{
            \PhalconDebug::info('NO IL TEMPLATE NON ESISTE');
            return false;
        }
    }   

}

?>