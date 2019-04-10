<?php

use Phalcon\Mvc\User\Component;

/**
 * Elements
 *
 * Helps to build UI elements for the application
 */
class Elements extends Component
{

    private $_sidebarMenu = array();



    /**
     * Builds sidebar menu <i class="fas fa-tachometer-alt"></i>
     *
     * @return string
     */
    public function getSidebarMenu()
    {

        $auth = $this->session->get('auth');
        if ($auth) {
            $this->_sidebarMenu['index']['index'] = [
                'caption' => 'Dashboard',
                'fa-icon' => 'fa-tachometer-alt'
            ];
            $this->_sidebarMenu['reservations']['index'] = [
                'caption' => 'Gestione Prenotazioni',
                'fa-icon' => 'fa-briefcase'
            ];
            $this->_sidebarMenu['exhibitors']['new'] = [
                'caption' => 'Inserim. espositori',
                'fa-icon' => 'fa-folder'
            ];
            $this->_sidebarMenu['session']['end'] = [
                'caption' => 'Log Out',
                'fa-icon' => 'fa-sign-out-alt'
            ];
        } else {
            $this->_sidebarMenu = [
                'exhibitors' => [
                    'new' => [
                        'caption' => 'Prenotaz. espositori',
                        'fa-icon' => 'fa-folder'
                    ],
                ],
                'session' => [
                    'index' => [
                        'caption' => 'Login',
                        'fa-icon' => 'fa-user'
                    ],
                ],
            ];
        }
/*

    <li class="nav-item active">
      <a class="nav-link" href="index.html">
        <i class="fas fa-fw fa-tachometer-alt"></i>
        <span>Dashboard</span>
      </a>
    </li>
    <!------      DA IMPLEMENTARE IN CASO DI MENU DI SECONDO LIVELLO --------------------->
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-fw fa-folder"></i>
        <span>Pages</span>
      </a>
      <div class="dropdown-menu" aria-labelledby="pagesDropdown">
        <h6 class="dropdown-header">Login Screens:</h6>
        <a class="dropdown-item" href="login.html">Login</a>
        <a class="dropdown-item" href="register.html">Register</a>
        <a class="dropdown-item" href="forgot-password.html">Forgot Password</a>
        <div class="dropdown-divider"></div>
        <h6 class="dropdown-header">Other Pages:</h6>
        <a class="dropdown-item" href="404.html">404 Page</a>
        <a class="dropdown-item" href="blank.html">Blank Page</a>
      </div>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="charts.html">
        <i class="fas fa-fw fa-chart-area"></i>
        <span>Charts</span></a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="tables.html">
        <i class="fas fa-fw fa-table"></i>
        <span>Tables</span></a>
    </li>
*/
        $controllerName = $this->view->getControllerName();

        foreach ($this->_sidebarMenu as $thecontroller => $action) {
            
            foreach ($action as $azione => $option) {
                echo '<li class="nav-item">';
                $classe = $controllerName == $thecontroller ? 'nav-link active' : 'nav-link'; /* snippet per differenziare la classe del link attivo */
                $caption = '<i class="fas fa-fw '.$option['fa-icon'].'"></i> <span>'.$option['caption'].'</span>';
                echo $this->tag->linkTo([$thecontroller . '/' . $azione, $caption, 'class' => $classe]);
                echo '</li>';
            }
            
        }

    }

public function getBodyClass(){
    $controllerName = $this->view->getControllerName();

    switch ($controllerName) {
        case "session":
            echo "class=\"bg-dark\"";
        break;
        default:
            echo "";
        break;
    }
}

public function getUserName(){
    $auth = $this->session->get('auth');

    if ($auth) {
        $ses =  $this->session->get('auth');
        return $ses['username'];
    }
    else{
        return '';
    }
}

public function getUserId(){
    $auth = $this->session->get('auth');

    if ($auth) {
        $ses =  $this->session->get('auth');
        return $ses['id'];
    }
    else{
        return '';
    }
}

public function getUserEmail(){
    $auth = $this->session->get('auth');

    if ($auth) {
        $ses =  $this->session->get('auth');
        return $ses['email'];
    }
    else{
        return '';
    }
}

public function getAvatar(){
    $auth = $this->session->get('auth');
    if ($auth) {
        $ses =  $this->session->get('auth');
        if(!empty($ses['avatar'])){
            return "<img src='/{$ses['avatar']}' class='rounded-circle' height='21px'>";
        }
        else{
             //return '<i class="fas fa-user-circle fa-fw"></i>';
            return "<img src=\"http://lorempixel.com/21/21/cats/".$ses['id']."\" class='rounded-circle' height='21px'>";
        }
    }
    else{
        return '';
    }
}

public function getNomeUtente(){
    $auth = $this->session->get('auth');
    if ($auth) {
        $ses =  $this->session->get('auth');
        return $ses['nome'];
    }
    else{
        return '';
    }
}

}
