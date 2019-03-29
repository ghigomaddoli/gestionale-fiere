
{{ partial('partials/navbar') }}

<div id="wrapper">

<!-- Sidebar -->
{{ partial('partials/sidebar') }}

<div id="content-wrapper">

  <div class="container-fluid">

    {{ content() }}

    <!-- Page Content -->
    <h2>{{ evento|e }}</h2>

    {% if logged === false %}
    <hr>
    <p>Prenotazione e gestione degli spazi espositivi.</p>
    <p>Prego, effettuare il login.</p>
    <p><a class="btn btn-primary" href="/session/index">Login</a></p>
    {% else %}
    {{ partial('partials/dashboard') }}
    {% endif %}

  </div>
  <!-- /.container-fluid -->

  <!-- Sticky Footer -->
  <footer class="sticky-footer">
    <div class="container my-auto">
      <div class="copyright text-center my-auto">
        <span>Copyright © Fairlab 2018</span>
      </div>
    </div>
  </footer>

</div>
<!-- /.content-wrapper -->


</div>
<!-- /#wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
<i class="fas fa-angle-up"></i>
</a>

<!-- Logout Modal-->
{{ partial('partials/logoutmodal') }}
