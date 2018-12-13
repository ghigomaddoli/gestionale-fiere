<nav class="navbar navbar-expand navbar-dark bg-dark static-top">

    <a href="/" class="navbar-left"><img src="/img/logoflcgu.png" height="40px"></a>
    <li class="divider"></li>
    <a class="navbar-brand mr-1" href="/">Fa' la cosa giusta Umbria</a>
    
    <button class="btn btn-link btn-sm text-white order-1 order-sm-0" id="sidebarToggle" href="#">
      <i class="fas fa-bars"></i>
    </button>

    {% if logged === true %}
    <!-- Navbar Search -->
    {{ form('reservations/index', 'id' : 'fricerca', 'role': 'form', 'method': 'POST', 'autocomplete': 'off', 'class': 'd-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0') }} 
      <div class="input-group">
        {{ text_field('ragionesociale', 'class' : 'form-control', 'placeholder' : 'Cerca Espositore..') }}
        <div class="input-group-append">
          <button type="submit" class="btn btn-primary" type="button">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
      {{ end_form() }}
    
    <!-- Navbar -->
    <ul class="navbar-nav ml-auto ml-md-0">
      <!--li class="nav-item dropdown no-arrow mx-1">
        <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-bell fa-fw"></i>
          <span class="badge badge-danger">9+</span>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="alertsDropdown">
          <a class="dropdown-item" href="#">Action</a>
          <a class="dropdown-item" href="#">Another action</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="#">Something else here</a>
        </div>
      </li>
      <li class="nav-item dropdown no-arrow mx-1">
        <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-envelope fa-fw"></i>
          <span class="badge badge-danger">7</span>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="messagesDropdown">
          <a class="dropdown-item" href="#">Action</a>
          <a class="dropdown-item" href="#">Another action</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="#">Something else here</a>
        </div>
      </li-->
      
      <li class="nav-item dropdown no-arrow">
        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
         {{ elements.getUserName() }} {{ elements.getAvatar() }}
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
 
          <a class="dropdown-item">{{ elements.getNomeUtente() }}</a>

          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="session/end" data-toggle="modal" data-target="#logoutModal">Logout</a>
        </div>
      </li>
    </ul>
    {% endif %}
    </nav>
    