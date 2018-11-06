<?php



class IndexController extends ControllerBase
{


    public function indexAction()
    {
        $this->miologger->log('ciao sono la index di indexcontroller');
        $auth = $this->session->get('auth');
		if (!$auth){
            $this->view->logged = false;
		} else {
            $this->view->logged = true;
            $this->view->user = $auth;
		}
    }

}

