


<div class="container">
   
      <div class="card card-login mx-auto mt-5">
         
        <div class="card-header">Prego, autenticarsi.</div>
        <div class="card-body">

          {{ content() }}

          {{ form('session/start', 'role': 'form', 'method': 'POST') }}
            <div class="form-group">
              <div class="form-label-group">
                {{ text_field('email', 'id':"inputEmail", 'class': "form-control", 'placeholder':"Il tuo indirizzo email", 'required':"required",'autofocus':"autofocus") }}
                <label for="inputEmail">Email o Username</label>
              </div>
            </div>
            <div class="form-group">
              <div class="form-label-group">
                <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password" required="required">
                <label for="inputPassword">Password</label>
              </div>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Login</button>
          </form>
          <div class="text-center">
            <p>{{ link_to('/session/forgot', 'Password dimenticata?', 'class': 'd-block small') }}</p>
          </div>
        </div>
      </div>
    </div>
