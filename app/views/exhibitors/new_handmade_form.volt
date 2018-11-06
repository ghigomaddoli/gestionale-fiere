
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
    
    <div class="form-group">
        <div class="form-row">
            <div class="col-md-6">
                <label for="fieldRagionesociale" class="col-sm-2 control-label">Ragionesociale</label>
                    <?php echo $this->tag->textField(["ragionesociale", "size" => 30, "class" => "form-control", "id" => "fieldRagionesociale"]) ?>
            </div>
            <div class="col-md-6">
                    <label for="fieldIndirizzo" class="col-sm-2 control-label">Indirizzo</label>
                    <?php echo $this->tag->textField(["indirizzo", "size" => 100, "class" => "form-control", "id" => "fieldIndirizzo"]) ?>
            </div>
        </div>

        <div class="form-row">
            <div class="col-md-4">
                    <label for="fieldCap" class="col-sm-2 control-label">Cap</label>
                    <div>
                        <?php echo $this->tag->textField(["cap", "size" => 30, "class" => "form-control", "id" => "fieldCap"]) ?>
                    </div>
            </div>
            <div class="col-md-4">
                    <label for="fieldCitta" class="col-sm-2 control-label">Citta</label>
                    <div>
                        <?php echo $this->tag->textField(["citta", "size" => 30, "class" => "form-control", "id" => "fieldCitta"]) ?>
                    </div>
            </div>
            <div class="col-md-4">
                    <label for="fieldProvincia" class="col-sm-2 control-label">Provincia</label>
                    <div>
                        <?php echo $this->tag->textField(["provincia", "size" => 30, "class" => "form-control", "id" => "fieldProvincia"]) ?>
                    </div>
            </div>
        </div>

        <div class="form-row">
            <div class="col-md-4">
                    <label for="fieldTelefono" class="col-sm-2 control-label">Telefono</label>
                    <div>
                        <?php echo $this->tag->textField(["telefono", "size" => 30, "class" => "form-control", "id" => "fieldTelefono"]) ?>
                    </div>
            </div>
            <div class="col-md-4">
                    <label for="fieldEmailaziendale" class="col-sm-2 control-label">Emailaziendale</label>
                    <div>
                        <?php echo $this->tag->textField(["emailaziendale", "size" => 30, "class" => "form-control", "id" => "fieldEmailaziendale"]) ?>
                    </div>
            </div>
            <div class="col-md-4">
                    <label for="fieldPartitaivaCodFisc" class="col-sm-2 control-label">Partita&nbsp;iva&nbsp;o&nbsp;Cod.&nbsp;Fisc.</label>
                    <div>
                        <?php echo $this->tag->textField(["pivacodfisc", "type" => "number", "class" => "form-control", "id" => "fieldPartitaivaCodFisc"]) ?>
                    </div>
            </div>
        </div>
    </div>

    <div class="form-row">
            <div class="col-md-6">
                    <label for="fieldReferentenome" class="col-sm-2 control-label">Nome&nbsp;espositore</label>
                    <div>
                        <?php echo $this->tag->textField(["referentenome", "size" => 30, "class" => "form-control", "id" => "fieldReferentenome"]) ?>
                    </div>
            </div>
            <div class="col-md-3">
                    <label for="fieldReferentetelefono" class="col-sm-2 control-label">Tel.&nbsp;espositore</label>
                    <div>
                        <?php echo $this->tag->textField(["referentetelefono", "size" => 30, "class" => "form-control", "id" => "fieldReferentetelefono"]) ?>
                    </div>
            </div>
            <div class="col-md-3">
                    <label for="fieldReferenteemail" class="col-sm-2 control-label">Email&nbsp;espositore</label>
                    <div>
                        <?php echo $this->tag->textField(["referenteemail", "size" => 30, "class" => "form-control", "id" => "fieldReferenteemail"]) ?>
                    </div>
            </div>
    </div>

    

    
    <div class="form-group">
        <label for="fieldProdottiesposti" class="col-sm-2 control-label">Prodotti esposti</label>
        <div class="col-sm-10">
            <?php echo $this->tag->textArea(["prodottiesposti", "cols" => 30, "rows" => 4, "class" => "form-control", "id" => "fieldProdottiesposti"]) ?>
        </div>
    </div>
    
    <div class="form-group">
        <label for="fieldFasciadiprezzo" class="col-sm-2 control-label">Fascia di prezzo</label>
        <div class="col-sm-10">
            <?php echo $this->tag->selectStatic(["fasciadiprezzo", [], "class" => "form-control", "id" => "fieldFasciadiprezzo"]) ?>
        </div>
    </div>
    
    <div class="form-group">
        <label for="fieldNumerocoespositore" class="col-sm-2 control-label">Numero di eventuali altri co-espositori</label>
        <div class="col-sm-10">
            <?php echo $this->tag->textField(["numerocoespositore", "size" => 3, "class" => "form-control", "id" => "fieldNumerocoespositore"]) ?>
        </div>
    </div>
    
    <div class="form-group">
        <label for="fieldNomecoespositore" class="col-sm-2 control-label">Nomi coespositori</label>
        <div class="col-sm-10">
            <?php echo $this->tag->textField(["nomecoespositore", "size" => 30, "class" => "form-control", "id" => "fieldNomecoespositore"]) ?>
        </div>
    </div>
    
    <div class="form-group">
        <label for="fieldCatalogonome" class="col-sm-2 control-label">Nome per Catalogo</label>
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

