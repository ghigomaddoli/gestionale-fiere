
{{ partial('partials/navbar') }}

<div id="wrapper">

<!-- Sidebar -->
{{ partial('partials/sidebar') }}

<div id="content-wrapper">

  <div class="container-fluid">

    {{ content() }}

    <!-- Page Content -->
    <h2>{{ evento|e }} - Modulo di richiesta degli spazi espositivi</h2>
    <hr>

    <?php
    echo $this->tag->form(
        [
            "reservations/create",
            "autocomplete" => "off",
            "class" => "form-horizontal"
        ]
    );
?>
<div class="form-group">
  <label for="fieldRagionesociale" class="col-sm-2 control-label">Ragionesociale</label>
  <div class="col-sm-10">
      <?php echo $this->tag->textField(["ragionesociale", "size" => 30, "class" => "form-control", "id" => "fieldRagionesociale"]) ?>
  </div>
</div>

<div class="form-group">
  <label for="fieldCap" class="col-sm-2 control-label">Cap</label>
  <div class="col-sm-10">
      <?php echo $this->tag->textField(["cap", "size" => 30, "class" => "form-control", "id" => "fieldCap"]) ?>
  </div>
</div>

<div class="form-group">
  <label for="fieldCitta" class="col-sm-2 control-label">Citta</label>
  <div class="col-sm-10">
      <?php echo $this->tag->textField(["citta", "size" => 30, "class" => "form-control", "id" => "fieldCitta"]) ?>
  </div>
</div>

<div class="form-group">
  <label for="fieldProvincia" class="col-sm-2 control-label">Provincia</label>
  <div class="col-sm-10">
      <?php echo $this->tag->textField(["provincia", "size" => 30, "class" => "form-control", "id" => "fieldProvincia"]) ?>
  </div>
</div>

<div class="form-group">
  <label for="fieldTelefono" class="col-sm-2 control-label">Telefono</label>
  <div class="col-sm-10">
      <?php echo $this->tag->textField(["telefono", "size" => 30, "class" => "form-control", "id" => "fieldTelefono"]) ?>
  </div>
</div>

<div class="form-group">
  <label for="fieldEmailaziendale" class="col-sm-2 control-label">Emailaziendale</label>
  <div class="col-sm-10">
      <?php echo $this->tag->textField(["emailaziendale", "size" => 30, "class" => "form-control", "id" => "fieldEmailaziendale"]) ?>
  </div>
</div>

<div class="form-group">
  <label for="fieldPartitaiva" class="col-sm-2 control-label">Partitaiva</label>
  <div class="col-sm-10">
      <?php echo $this->tag->textField(["partitaiva", "type" => "number", "class" => "form-control", "id" => "fieldPartitaiva"]) ?>
  </div>
</div>

<div class="form-group">
  <label for="fieldCodicefiscale" class="col-sm-2 control-label">Codicefiscale</label>
  <div class="col-sm-10">
      <?php echo $this->tag->textField(["codicefiscale", "size" => 30, "class" => "form-control", "id" => "fieldCodicefiscale"]) ?>
  </div>
</div>

<div class="form-group">
  <label for="fieldReferentenome" class="col-sm-2 control-label">Referentenome</label>
  <div class="col-sm-10">
      <?php echo $this->tag->textField(["referentenome", "size" => 30, "class" => "form-control", "id" => "fieldReferentenome"]) ?>
  </div>
</div>

<div class="form-group">
  <label for="fieldReferentetelefono" class="col-sm-2 control-label">Referentetelefono</label>
  <div class="col-sm-10">
      <?php echo $this->tag->textField(["referentetelefono", "size" => 30, "class" => "form-control", "id" => "fieldReferentetelefono"]) ?>
  </div>
</div>

<div class="form-group">
  <label for="fieldReferenteemail" class="col-sm-2 control-label">Referenteemail</label>
  <div class="col-sm-10">
      <?php echo $this->tag->textField(["referenteemail", "size" => 30, "class" => "form-control", "id" => "fieldReferenteemail"]) ?>
  </div>
</div>

<div class="form-group">
  <label for="fieldProdottiesposti" class="col-sm-2 control-label">Prodottiesposti</label>
  <div class="col-sm-10">
      <?php echo $this->tag->textArea(["prodottiesposti", "cols" => 30, "rows" => 4, "class" => "form-control", "id" => "fieldProdottiesposti"]) ?>
  </div>
</div>

<div class="form-group">
  <label for="fieldFasciadiprezzo" class="col-sm-2 control-label">Fasciadiprezzo</label>
  <div class="col-sm-10">
      <?php echo $this->tag->selectStatic(["fasciadiprezzo", [], "class" => "form-control", "id" => "fieldFasciadiprezzo"]) ?>
  </div>
</div>

<div class="form-group">
  <label for="fieldNumerocoespositore" class="col-sm-2 control-label">Numerocoespositore</label>
  <div class="col-sm-10">
      <?php echo $this->tag->textField(["numerocoespositore", "size" => 30, "class" => "form-control", "id" => "fieldNumerocoespositore"]) ?>
  </div>
</div>

<div class="form-group">
  <label for="fieldNomecoespositore" class="col-sm-2 control-label">Nomecoespositore</label>
  <div class="col-sm-10">
      <?php echo $this->tag->textField(["nomecoespositore", "size" => 30, "class" => "form-control", "id" => "fieldNomecoespositore"]) ?>
  </div>
</div>

<div class="form-group">
  <label for="fieldCatalogonome" class="col-sm-2 control-label">Catalogonome</label>
  <div class="col-sm-10">
      <?php echo $this->tag->textField(["catalogonome", "size" => 30, "class" => "form-control", "id" => "fieldCatalogonome"]) ?>
  </div>
</div>

<div class="form-group">
  <label for="fieldCatalogoindirizzo" class="col-sm-2 control-label">Catalogoindirizzo</label>
  <div class="col-sm-10">
      <?php echo $this->tag->textField(["catalogoindirizzo", "size" => 30, "class" => "form-control", "id" => "fieldCatalogoindirizzo"]) ?>
  </div>
</div>

<div class="form-group">
  <label for="fieldCatalogocap" class="col-sm-2 control-label">Catalogocap</label>
  <div class="col-sm-10">
      <?php echo $this->tag->textField(["catalogocap", "size" => 30, "class" => "form-control", "id" => "fieldCatalogocap"]) ?>
  </div>
</div>

<div class="form-group">
  <label for="fieldCatalogocitta" class="col-sm-2 control-label">Catalogocitta</label>
  <div class="col-sm-10">
      <?php echo $this->tag->textField(["catalogocitta", "size" => 30, "class" => "form-control", "id" => "fieldCatalogocitta"]) ?>
  </div>
</div>

<div class="form-group">
  <label for="fieldCatalogoprovincia" class="col-sm-2 control-label">Catalogoprovincia</label>
  <div class="col-sm-10">
      <?php echo $this->tag->textField(["catalogoprovincia", "size" => 30, "class" => "form-control", "id" => "fieldCatalogoprovincia"]) ?>
  </div>
</div>

<div class="form-group">
  <label for="fieldCatalogotelefono" class="col-sm-2 control-label">Catalogotelefono</label>
  <div class="col-sm-10">
      <?php echo $this->tag->textField(["catalogotelefono", "size" => 30, "class" => "form-control", "id" => "fieldCatalogotelefono"]) ?>
  </div>
</div>

<div class="form-group">
  <label for="fieldCatalogoemail" class="col-sm-2 control-label">Catalogoemail</label>
  <div class="col-sm-10">
      <?php echo $this->tag->textField(["catalogoemail", "size" => 30, "class" => "form-control", "id" => "fieldCatalogoemail"]) ?>
  </div>
</div>

<div class="form-group">
  <label for="fieldCatalogositoweb" class="col-sm-2 control-label">Catalogositoweb</label>
  <div class="col-sm-10">
      <?php echo $this->tag->textField(["catalogositoweb", "size" => 30, "class" => "form-control", "id" => "fieldCatalogositoweb"]) ?>
  </div>
</div>

<div class="form-group">
  <label for="fieldCatalogofacebook" class="col-sm-2 control-label">Catalogofacebook</label>
  <div class="col-sm-10">
      <?php echo $this->tag->textField(["catalogofacebook", "size" => 30, "class" => "form-control", "id" => "fieldCatalogofacebook"]) ?>
  </div>
</div>

<div class="form-group">
  <label for="fieldCatalogoinstagram" class="col-sm-2 control-label">Catalogoinstagram</label>
  <div class="col-sm-10">
      <?php echo $this->tag->textField(["catalogoinstagram", "size" => 30, "class" => "form-control", "id" => "fieldCatalogoinstagram"]) ?>
  </div>
</div>

<div class="form-group">
  <label for="fieldCatalogotwitter" class="col-sm-2 control-label">Catalogotwitter</label>
  <div class="col-sm-10">
      <?php echo $this->tag->textField(["catalogotwitter", "size" => 30, "class" => "form-control", "id" => "fieldCatalogotwitter"]) ?>
  </div>
</div>

<div class="form-group">
  <label for="fieldCatalogodescrizione" class="col-sm-2 control-label">Catalogodescrizione</label>
  <div class="col-sm-10">
      <?php echo $this->tag->textArea(["catalogodescrizione", "cols" => 30, "rows" => 4, "class" => "form-control", "id" => "fieldCatalogodescrizione"]) ?>
  </div>
</div>

<div class="form-group">
  <label for="fieldInterventoprogrammaculturale" class="col-sm-2 control-label">Interventoprogrammaculturale</label>
  <div class="col-sm-10">
      <?php echo $this->tag->textField(["interventoprogrammaculturale", "type" => "number", "class" => "form-control", "id" => "fieldInterventoprogrammaculturale"]) ?>
  </div>
</div>

<hr>


<div class="form-group">
    <label for="fieldExhibitorsId" class="col-sm-2 control-label">Exhibitors</label>
    <div class="col-sm-10">
        <?php echo $this->tag->textField(["exhibitors_id", "type" => "number", "class" => "form-control", "id" => "fieldExhibitorsId"]) ?>
    </div>
</div>

<div class="form-group">
    <label for="fieldEventsId" class="col-sm-2 control-label">Events</label>
    <div class="col-sm-10">
        <?php echo $this->tag->textField(["events_id", "type" => "number", "class" => "form-control", "id" => "fieldEventsId"]) ?>
    </div>
</div>

<div class="form-group">
    <label for="fieldAreasId" class="col-sm-2 control-label">Areas</label>
    <div class="col-sm-10">
        <?php echo $this->tag->textField(["areas_id", "type" => "number", "class" => "form-control", "id" => "fieldAreasId"]) ?>
    </div>
</div>

<div class="form-group">
    <label for="fieldCodicestand" class="col-sm-2 control-label">Codicestand</label>
    <div class="col-sm-10">
        <?php echo $this->tag->textField(["codicestand", "size" => 30, "class" => "form-control", "id" => "fieldCodicestand"]) ?>
    </div>
</div>

<div class="form-group">
    <label for="fieldPadreId" class="col-sm-2 control-label">Padre</label>
    <div class="col-sm-10">
        <?php echo $this->tag->textField(["padre_id", "type" => "number", "class" => "form-control", "id" => "fieldPadreId"]) ?>
    </div>
</div>

<div class="form-group">
    <label for="fieldPrezzofinale" class="col-sm-2 control-label">Prezzofinale</label>
    <div class="col-sm-10">
        <?php echo $this->tag->textField(["prezzofinale", "type" => "number", "class" => "form-control", "id" => "fieldPrezzofinale"]) ?>
    </div>
</div>

<div class="form-group">
    <label for="fieldNotepagamento" class="col-sm-2 control-label">Notepagamento</label>
    <div class="col-sm-10">
        <?php echo $this->tag->textArea(["notepagamento", "cols" => 30, "rows" => 4, "class" => "form-control", "id" => "fieldNotepagamento"]) ?>
    </div>
</div>


<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        <?php echo $this->tag->submitButton(["Save", "class" => "btn btn-default"]) ?>
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
