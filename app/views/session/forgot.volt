


<div class="container">
   
    <div class="card card-login mx-auto mt-5">
       
      <div class="card-header">Prego, inserisci il tuo indirizzo email per il recupero della password</div>
      <div class="card-body">

        {{ content() }}

        {{ form('session/reset', 'role': 'form', 'method': 'POST') }}
          <div class="form-group">
            <div class="form-label-group">
              {{ text_field('email', 'id':"inputEmail", 'class': "form-control", 'placeholder':"Il tuo indirizzo email", 'required':"required",'autofocus':"autofocus") }}
              <label for="inputEmail">Email o Username</label>
            </div>
          </div>
          <button type="submit" class="btn btn-primary btn-block">Reset Password</button>
        </form>
        <div class="text-center">
          <p>{{ link_to('/index/index', 'Torna indietro', 'class': 'd-block small') }}</p>
        </div>
      </div>
    </div>
  </div>
