<?php

use Phalcon\Mvc\Controller;


class ControllerBase extends Controller
{

    public function initialize()
    {
        $this->tag->prependTitle('Fairlab - Gestionale Eventi - ');
        $this->view->evento = $this->evento->descrizione;
        
    }

}
