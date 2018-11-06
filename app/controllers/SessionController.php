<?php


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
            'avatar' => $user->avatar
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
}
