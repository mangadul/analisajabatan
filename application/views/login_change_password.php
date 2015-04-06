<!DOCTYPE html>
<head>
  <title>Sistem Informasi Analisa Formasi Jabatan - Sekretariat Daerah Pemerintah Kota Cilegon</title>
  <link rel="stylesheet" type="text/css" href="<?=base_url()?>resources/style_login.css" />
  <link rel="icon" href="<?=base_url()?>/favicon.ico" type="image/gif">
</head>

<body>
<div class="login">
<img src="<?=base_url()?>resources/images/banner-login.png" alt="PT Kereta Api Indonesia (Persero)" title="Pranopka" style="margin-left: 0px; margin-right: 0px; margin-top: 0px; margin-bottom: 15px; float: center;">
<h1>Ganti Password Anda</h1>
<form action="<?=base_url()?>index.php/mainindex/ChangePassword" method="post" id='myform'>
  <center>
  <table>
    <tr>
      <td>User Name</td><td><input type="text" name="username" value="<?=$username?>" readonly></td>
    </tr>

    <tr>
      <td>Password Baru</td><td><input type="password" name="password_new" id="password_new"></td>
    </tr>
    
    <tr>
      <td>Ulangi Password</td><td><input type="password" name="password_confirm" id="password_confirm"></td>
    </tr>

    <tr>
      <td>Nip</td><td><input type="text" name="nipp" id="nipp" value="<?=$nipp?>" readonly></td>
    </tr>

    <tr>
      <td>Nama</td><td><input type="text" name="nama" id="nama" value="<?=$nama?>" readonly></td>
    </tr>

    <tr>
      <td colspan="2">
          <p class="submit">
            <input type="button" onclick="cekSubmit()" value="Simpan">
	    <input type="button" onclick="back()" value="Batal">
          </p>
           <p class="remember_me"><span id="msgError" style="color: red"></span></p>
      </td>
    </tr>
    
  </table>
  </center>
</form>
</div>

<div class="login-help">
<p>&copy;2014 Sekretariat Daerah, Pemerintah Kota Cilegon <br/>Informasi : Email <a href="setda@cilegon.go.id">setda@cilegon.go.id</a> - Notelp/Fax <strong>...................</strong></p>
</div>
</div>

<script>
    function cekSubmit(){
        var pwd_n = document.getElementById('password_new').value;
        var pwd_c = document.getElementById('password_confirm').value;

        if(pwd_n.length < 8)
            document.getElementById('msgError').innerHTML = "Jumlah Kombinasi Password Minimal 8 Combinasi <br>";
        
        else if(pwd_n == '12345678')
            document.getElementById('msgError').innerHTML = "Kombinasi Password 12345678 Tidak Diizinjan <br>";
        
        else if(pwd_n != pwd_c)
            document.getElementById('msgError').innerHTML = "Pengulangan Password Harus Sama <br>";
        
        else if(document.getElementById('nipp').value=='')
            document.getElementById('msgError').innerHTML = "Nipp Harus Diisi<br>";
        
        else if(document.getElementById('nama').value=='')
            document.getElementById('msgError').innerHTML = "Nama Harus Diisi <br>";
        
        else document.forms["myform"].submit();
        
    }
</script>

</body>
</html>

