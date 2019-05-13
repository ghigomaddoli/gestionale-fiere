


<div class="container">
    
   
    <div class="card card-login mx-auto mt-5">
       
      <div class="card-header">Ora puoi resettare la tua password</div>
      <div class="card-body">

        {{ content() }}

        <p><?php $this->flashSession->output() ?></p>

        {{ form('session/savenewpassword', 'role': 'form', 'method': 'POST') }}
          <div class="form-group">
            <div class="form-label-group">
                {{ password_field('newpass', 'class': "form-control", 'placeholder':"nuova password", 'required':"required",'autofocus':"autofocus") }}
                <label for="inputEmail">Nuova Password</label>
              </div>
            </div>
            <div class="form-group">              
              <div class="form-label-group">
                {{ password_field('newpass2', 'class': "form-control", 'placeholder':"ripeti la nuova password", 'required':"required") }}
                <label for="inputEmail">Ripeti la nuova password</label>
              </div>
          </div>
          {{ hidden_field('token', "value" : token) }}
          <button type="submit" class="btn btn-primary btn-block">Salva</button>
        </form>
        <div class="text-center">
          <p>&nbsp;</p>
          <p>{{ link_to('/index/index', 'Torna indietro', 'class': 'd-block small') }}</p>
        </div>
      </div>
    </div>
  </div>
