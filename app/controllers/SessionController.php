<?php
use Phalcon\Mvc\Url;

class SessionController extends ControllerBase
{
    
    public function initialize()
    {
        $this->tag->setTitle('Login');
        parent::initialize();
    }

    public function indexAction()
    {
        /* form di login nella view */
        $this->miologger->log('ciao sono la index di sessioncontroller (form login)');
    }

    /**
     * Register an authenticated user into session data
     *
     * @param Users $user
     */

    private function _registerSession(Users $user)
    {
        $this->session->set('auth', [
            'id' => $user->id,
            'nome' => $user->nome,
            'username' => $user->username,
            'avatar' => $user->avatar,
            'email' => $user->email
        ]);
    }

    /**
     * This action authenticate and logs an user into the application
     *
     */
    public function startAction()
    {
        $this->miologger->log('inizio session/start...');
        if ($this->request->isPost()) {

            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');

            $user = Users::findFirst([
                "(email = :email: OR username = :email:) AND password = :password: AND attivo = 1",
                'bind' => ['email' => $email, 'password' => sha1($password)]
            ]);

            if ($user != false) {
                $this->miologger->log('registro utente in sessione');
                $this->_registerSession($user);
                $this->view->user = $user;
                $this->miologger->log('sto per fare il forward verso reservations/index');

                return $this->dispatcher->forward(
                    [
                        "controller" => "index",
                        "action"     => "index",
                    ]
                );
            }

            $this->flash->error('Credenziali errate');
        }
        $this->miologger->log('sto per fare il forward verso session/index');
        return $this->dispatcher->forward(
            [
                "controller" => "session",
                "action"     => "index",
            ]
        );
    }

    /**
     * Finishes the active session redirecting to the index
     *
     * @return unknown
     */
    public function endAction()
    {
        $this->session->remove('auth');
        $this->flash->success('Arrivederci!');

        return $this->dispatcher->forward(
            [
                "controller" => "index",
                "action"     => "index",
            ]
        );
    }

    public function forgotAction()
    {

    }

    public function resetAction()
    {
        if ($this->request->isPost()) {

            $email = $this->request->getPost('email');

            $user = Users::findFirst([
            "(email = :email:) AND attivo = 1",
            'bind' => ['email' => $email]
            ]);

            if ($user != false) {

                // 1) genero un token
                $token = $this->security->getToken();

                // 2) lo inserisco nel database
                $user->token = $token;
                if ($user->save() == false) {
                    foreach ($user->getMessages() as $message) {
                        $this->flash->error($message);
                    }
                }

                // 3) invio il link di reset password
                $parametri = array(
                    'user' => $user,
                    'baseuri' => 'http://iscrizioni.falacosagiustaumbria.it/',
                    'destinatari' => array($email => $user->nome),
                    'replyto' => array($email => $user->nome)
                );        
                $result = MyEmailSender::inviaEmail($this, 'resetpassword', $parametri,"Istruzioni di reset della password per ".$user->nome);

                if($result > 0){

                    $this->flash->success("le istruzioni per il reset della password sono state inviate all'indirizzo email {$email}");

                    return $this->dispatcher->forward(
                        [
                            "controller" => "index",
                            "action"     => "index",
                        ]
                    );
                }
                else{
                    $this->flash->error('errore nell\'invio dell\'email');
                }
            }

            $this->flash->error('indirizzo email inesistente');
        }

        return $this->dispatcher->forward(
            [
                "controller" => "session",
                "action"     => "index",
            ]
        );
    }

    /* visualizza il form per inserire la nuova password */
    public function newpassAction($token)
    {
        $this->view->token = $token;
    }

    /* salva la nuova password nel db */
    public function savenewpasswordAction()
    {
            // 1) mi arriva token e vecchia password, le controllo.
            if ($this->request->isPost()) {
                $token = $this->request->getPost('token');
                \PhalconDebug::info('ricevo token: ',$token);
                $newpass = $this->request->getPost('newpass');
                $newpass2 = $this->request->getPost('newpass2');
                // verifico che le due password siano uguali
                if($newpass != $newpass2){
                    $this->flashSession->error('Errore. I due campi password non coincidono');
                    $this->response->redirect('session/newpass/{$token}');
                }
                // verifico con espressione regolare che la password abbia un minimo di complessità: una maiuscola, una minuscola un numero e un carattere speciale
                $pattern = '/(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/';
                if (!(preg_match($pattern, $newpass))) {
                    $this->flashSession->error('La password che scegli dovrebbe contenere almeno: una maiuscola, una minuscola, un numero e un carattere speciale');
                    $this->response->redirect('session/newpass/{$token}');                   
                }
                $user = Users::findFirst([
                "(token = :token:) AND attivo = 1",
                'bind' => ['token' => $token]
                ]);
    
                if ($user != false) {
                    \PhalconDebug::info('user trovato!: ',$user);
                    // 3) se sono corrette resetto la password e salvo
                   $user->password = sha1($newpass);

                   if($user->save()){

                        $this->flash->success('Password aggiornata con successo!');
                        return $this->dispatcher->forward(
                            [
                                "controller" => "index",
                                "action"     => "index",
                            ]
                        );

                   }
                   else{
                    $this->flash->error('errore nel salvataggio della nuova password');
                    $this->dispatcher->forward(
                        [
                            'controller' => 'errors',
                            'action'     => 'show404'
                        ]
                    );
                   }
                }
                else{
                    $this->flash->error('errore. Token scaduto e/o password non valida');
                    $this->dispatcher->forward(
                        [
                            'controller' => 'errors',
                            'action'     => 'show404'
                        ]
                    );
                }
            }
            else{
                $this->dispatcher->forward(
                    [
                        'controller' => 'errors',
                        'action'     => 'show404'
                    ]
                );
            }
    }

}
