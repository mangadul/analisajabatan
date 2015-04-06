<!DOCTYPE html>
<head>
    <title>Analisa Formasi Jabatan - Sekretariat Daerah Kota Cilegon </title>
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>resources/style_login.css" />
    <link rel="icon" href="<?=base_url()?>/favicon.ico" type="image/gif">
</head>

<body>
<br>
<div class="login">
<img src="<?=base_url()?>resources/images/banner-login.png" alt="PT Kereta Api Indonesia (Persero)" title="Pranopka" style="margin-left: 0px; margin-right: 0px; margin-top: 0px; margin-bottom: 15px; float: center;">
<h1>Login</h1>
<form action="<?=base_url()?>index.php/mainindex/process_login/0" method="post" id='myform'>
  <center>
  <table>
    <tr>
      <td colspan="2" ><p align="center"><span id="msgError" style="color: red"></span><br/></p></td>
    </tr>
    <tr>
      <td>Username</td>
      <td><input type="text" id="usern" name="username" value="" placeholder="NIPP" onkeypress="cekSubmitOnKeyPress(event);"></td>
    </tr>
    
    <tr>
      <td>Password</td>
      <td><input type="password" id="passwd" name="password" value="" placeholder="Password" onkeypress="cekSubmitOnKeyPress(event);"></td>
    </tr>

    <tr>
      <td colspan="2">
        <!--<p class="remember_me">
          <label>
            <input type="checkbox" name="remember_me" id="remember_me">
            Simpan info login di komputer ini
          </label>
        </p>
        -->  
      </td>
    </tr>
    <tr>
      <td colspan="2">
        <p class="submit"><input type="button" name="commit" value="Login" onclick="cekSubmitOnButton();"</p>
        
</form>
      </td>
    </tr>
  </table>
  </center>

  
  
  
</div>

<div class="login-help">
<p>&copy;2014 Sekretariat Daerah Kota Cilegon,  <br/>Informasi : Email <a href="mailto:setda@cilegon.go.id">setda@cilegon.go.id</a></p>
</div>

<?php
    if (isset($_GET['errlog']) == '1') {
      echo "<script>";
      echo "document.getElementById('msgError').innerHTML = 'Username/Password Anda salah';";
      echo "</script>";
    }
?>

<script>
    function cekSubmitOnButton(){
        //var user_n = document.getElementById('usern').value;
        //var pass_n = document.getElementById('passwd').value;
        
        if(document.getElementById('usern').value=='')
            document.getElementById('msgError').innerHTML = "Silahkan masukkan Username Anda.";
        else if(document.getElementById('passwd').value=='')
            document.getElementById('msgError').innerHTML = "Silahkan masukkan password Anda.";
        
        else document.forms["myform"].submit();
        
    }

    function cekSubmitOnKeyPress(e){
        var key = e.keyCode || e.which;
        if (key == 13) {
          cekSubmitOnButton();
        }
    }

</script>
</body>
</html>