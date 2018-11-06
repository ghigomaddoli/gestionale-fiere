
{{ partial('partials/navbar') }}

<div id="wrapper">

<!-- Sidebar -->
{{ partial('partials/sidebar') }}

<div id="content-wrapper">

  <div class="container-fluid">

    <div class="page-header">
        <h1>
            Inserimento espositore
        </h1>
    </div>
    
    {{ content() }}
    
    {{ form('exhibitors/create', 'role': 'form', 'method': 'POST','autocomplete': 'off', 'class': 'form-horizontal') }}
    
    <fieldset>
        <p class="h4">Dati Espositore per la fatturazione</p>
        {% for element in form %}

            <div class="form-group">
                    {{ element.label() }}
                    {{ element.render(['class': 'form-control']) }}
            </div>
        {% endfor %}
    
    </fieldset>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <?php echo $this->tag->submitButton(["Salva", "class" => "btn btn-primary"]) ?>
        </div>
    </div>

    <?php echo $this->tag->endForm(); ?>
    

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