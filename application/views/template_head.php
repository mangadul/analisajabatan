<style>
    .headertable td {
        font-size: 11px;
        color: #fff;
    }
    .headertable td a{
        font-size: 11px;
        color: #000;
        font-weight: bold;
        text-decoration: none;
    }
    body{
        margin: 0px 0px 0px 0px;
        padding: 0px 0px 0px 0px; 
        font-family: Calibri, Arial, Tahoma;
        font-size: 11px;
	background:#fff;
    }
</style>
<?php
$lokasi = $this->session->userdata('lokasi');
$nama_lokasi = $this->session->userdata('nama_lokasi');
$nipp = $this->session->userdata('nipp');
$nama = $this->session->userdata('nama');
$daop = $this->session->userdata('daop');

 if ($daop > 9) {
    switch ($daop) {
    case 10:  // Sumatera Utara
        $login_daop = 'I';
        break;
    case 11:
        $login_daop = 'II';
        break;
    case 12:
        $login_daop = 'III';
        break;
    case 13:
        $login_daop = 'III';
        break;
    case 14:
        $login_daop = 'III';
        break;
    }
    $login_daop = 'DIVRE '.$login_daop; 
 } else $login_daop = 'DAOP '.$daop;

$jab  = $this->session->userdata('jab');

if ($lokasi == 'KPT' || $lokasi == 'TNK') { 
    $login_lokasi = '';
    $nama_lokasi = 'Palembang';
} else 
    $login_lokasi = $lokasi;

?>
<!-- <div style="background-image: -moz-linear-gradient(center top , #F0BC20, #FF9900);
    border-bottom: 1px solid #567422;"> -->
<div style="background-image: -moz-linear-gradient(center top , #F0BC20, #FF9900);
    border-bottom: 1px solid #567422;">
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td width="100">
                <img src="<?=base_url()?>resources/images/logo2014.png"
                     style="padding:0px; margin:0px">
            </td>
            <td align="center">
                <div id="contentArea" 
                     style="padding-left: 20px; 
                            font-size: 23px;
                            color: white;
                            font-weight: bold;
                            text-shadow: 2px 2px #666;"></div>	
	    </td>
            <td align="right" width="500">
                <table width="100%" border="0" class="headertable">
                  <tr>
                    <td align="right">
                        <b>INFORMASI LOGIN : <?=$nama?> (<?=$nipp?>)</b><br>
                        <span style="font-size: 13px"><?=$jab?> <?=$login_lokasi?> (<?=$nama_lokasi?>) - <?=$login_daop?></span> <br>
                        <a href="<?=base_url()?>index.php/mainindex/FormChangePassword">RUBAH PASSWORD</a> | <a href="<?=base_url()?>index.php/mainindex/Logout">LOGOUT USER</a>
                    </td>
                    <td width="50" align="right">
                        <img src="<?=base_url()?>resources/images/user.png">
                    </td>
                    <td width="20"></td>
                  </tr>
                </table> 
            </td>
        </tr>        
    </table>
</div>

