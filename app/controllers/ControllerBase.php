<?php

use Phalcon\Mvc\Controller;


class ControllerBase extends Controller
{

    public function initialize()
    {
        $this->tag->prependTitle('Fairlab - Gestionale Eventi - ');
        $this->view->evento = $this->evento->descrizione;
        $auth = $this->session->get('auth');
		if (!$auth){
            $this->view->logged = false;
		} else {
            $this->view->logged = true;
            $this->view->user = $auth;
		}
    }

}
