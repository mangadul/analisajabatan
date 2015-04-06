<style>
    .overlay {
        position:fixed;
        top:0; bottom:0; left:0; right:0;
        margin:auto; 
        width:100%;
        background: #000;
        color: #ffffff;	
        border: 1px solid #000;
        z-index: 500;
        opacity:0.6;
        display: none;
        text-align: center;
        vertical-align: middle;
        font-size: 11px;
    }
    
    .overlay table tr td{
        font-size: 11px;
    }
    
    .headertab{
        background: rgb(228,245,252); /* Old browsers */
        background: -moz-linear-gradient(top,  rgba(228,245,252,1) 0%, rgba(191,232,249,1) 50%, rgba(159,216,239,1) 51%, rgba(42,176,237,1) 100%); /* FF3.6+ */
        background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(228,245,252,1)), color-stop(50%,rgba(191,232,249,1)), color-stop(51%,rgba(159,216,239,1)), color-stop(100%,rgba(42,176,237,1))); /* Chrome,Safari4+ */
        background: -webkit-linear-gradient(top,  rgba(228,245,252,1) 0%,rgba(191,232,249,1) 50%,rgba(159,216,239,1) 51%,rgba(42,176,237,1) 100%); /* Chrome10+,Safari5.1+ */
        background: -o-linear-gradient(top,  rgba(228,245,252,1) 0%,rgba(191,232,249,1) 50%,rgba(159,216,239,1) 51%,rgba(42,176,237,1) 100%); /* Opera 11.10+ */
        background: -ms-linear-gradient(top,  rgba(228,245,252,1) 0%,rgba(191,232,249,1) 50%,rgba(159,216,239,1) 51%,rgba(42,176,237,1) 100%); /* IE10+ */
        background: linear-gradient(to bottom,  rgba(228,245,252,1) 0%,rgba(191,232,249,1) 50%,rgba(159,216,239,1) 51%,rgba(42,176,237,1) 100%); /* W3C */
        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#e4f5fc', endColorstr='#2ab0ed',GradientType=0 ); /* IE6-9 */
    }
    
</style>


<div id="login-form" class="overlay">
    <table width="100%" height="100%" border="0">
        <tr>
            <td align="center">
               
                    <table width="285" border="0" cellspacing="1" cellpadding="1" bgcolor="#8DB2E3" style="opacity:1">
                        <tr>
                            <td bgcolor="#DFE8F6" align="center">

                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                  <tr>
                                    <td height="27" colspan="3" class="headertab">
                                        <img src="<?=base_url()?>resources/images/caplogin.png">
                                    </td>
                                  </tr>
                                  <tr>
                                      <td height="10" colspan="3" align="center" id="message-login" style="color: red">
                                          
                                      </td>
                                  </tr>
                                  <tr>
                                    <td height="25" width="100">&nbsp; &nbsp; User Name </td>
                                    <td height="25" width="30">:</td>
                                    <td height="25"><input type="text" id="username" name="username" size="18"></td>
                                  </tr>
                                  <tr>
                                    <td height="25">&nbsp; &nbsp; Password </td>
                                    <td height="25">:</td>
                                    <td height="25"><input type="password" id="password" name="password" size="18"></td>
                                  </tr>
                                  <tr>
                                    <td height="25">&nbsp;</td>
                                    <td height="25">&nbsp;</td>
                                    <td height="25"><input type="button" value="Login" onclick="ProcessLogin()"></td>
                                  </tr>
                                  <tr>
                                      <td height="10" colspan="3"></td>
                                  </tr>
                                </table>

                            </td>
                        </tr>
                    </table>
            </td>
        </tr>
    </table>
</div>

<script>
    function ShowLoginPage(){
        document.getElementById('login-form').style.display = 'block';
    }
    
    function HideLoginPage(){
        document.getElementById('login-form').style.display = 'none';
    } 
    
    function ProcessLogin(){
        var username = document.getElementById('username').value;
        var password = document.getElementById('password').value;
        
        var conn = new Ext.data.Connection();
            conn.request({
                method: 'POST',
                url: '<?=base_url()?>index.php/mainindex/process_login/1',
                params: {
                    username : username,
                    password : password
                },
                success: function(r) {
                    if(r.responseText=='1'){
                        HideLoginPage();
                        onCloseLogin();
                    }
                    else {
                        document.getElementById('message-login').innerHTML = "USER / PASSWORD SALAH";
                        document.getElementById('username').value = '';
                        document.getElementById('password').value = '';
                    } 
                },
                failure: function(){
                    alert('JARINGAN INTERNET ANDA TERPUTUS');
                }
            }); 
    }
    
    function CekLogin(){
        var conn = new Ext.data.Connection();
            conn.request({
                method: 'POST',
                url: '<?=base_url()?>index.php/mainindex/CekLogin',
                success: function(r) {
                    if(r.responseText=='0')ShowLoginPage(); 
                },
                failure: function(){
                    alert('JARINGAN INTERNET ANDA TERPUTUS');
                }
            }); 
    }
    
    CekLogin();
</script>