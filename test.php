<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <title></title>
</head>
<body>
  <script type="text/javascript">
  $(document).ready(function(){
    $(document).on('submit', '#login_form', function(){
      // get form data
      var login_form=$(this);
      var form_data=JSON.stringify(login_form.serializeObject());
      alert(form_data);
      // submit form data to api
      $.ajax({
        url: "api/member/login.php",
        type : "POST",
        contentType : 'application/json',
        data : form_data,
        success : function(result){
          // store jwt to cookie
          setCookie("jwt", result.jwt, 1);
          // show home page & tell the user it was a successful login
          showHomePage();
          $('#response').html("<div class='alert alert-success'>Successful login.</div>");
        },
        error: function(xhr, resp, text){
          // on error, tell the user login has failed & empty the input boxes
          $('#response').html("<div class='alert alert-danger'>Login failed. Email or password is incorrect.</div>");
          login_form.find('input').val('');
        }
      });
      return false;
    });

    $.fn.serializeObject = function(){

      var o = {};
      var a = this.serializeArray();
      $.each(a, function() {
        if (o[this.name] !== undefined) {
          if (!o[this.name].push) {
            o[this.name] = [o[this.name]];
          }
          o[this.name].push(this.value || '');
        } else {
          o[this.name] = this.value || '';
        }
      });
      return o;
    };
  });
</script>
  <h2>Login</h2>
  <div id="response"></div>
  <form id='login_form' method="post">
    <div class='form-group'>
      <label for='email'>Email address</label>
      <input type='email' class='form-control' id='email' name='email' placeholder='Enter email'>
    </div>

    <div class='form-group'>
      <label for='password'>Password</label>
      <input type='password' class='form-control' id='password' name='password' placeholder='Password'>
    </div>

    <button type='submit' class='btn btn-primary'>Login</button>
  </form>

</body>
</html>
