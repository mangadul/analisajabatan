<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Bakat Kerja</title>

<!--
<link rel="stylesheet" href="<?=$this->config->item('base_url');?>assets/css/bootstrap.min.css"/>
<link href="<?=$this->config->item('base_url');?>assets/css/tree.css" rel="stylesheet" type="text/css" />

-->
<link href="<?=$this->config->item('base_url');?>assets/css/bootstraps.min.css" rel="stylesheet" type="text/css" />

<style>
.icon-add { background-image:url(<?=base_url(); ?>assets/images/add.gif) !important; }
.icon-del { background-image:url(<?=base_url(); ?>assets/images/delete.png) !important; }
.icon-reload { background-image:url(<?=base_url(); ?>assets/images/reload.png) !important; }
.tabs { background-image:url(<?=base_url(); ?>assets/images/tabs.gif ) !important;}
ul { list-style-type: none; } 
ol.p {list-style-type: lower-latin;}
ol.sq {list-style-type: square;}
ol.lower {list-style-type: lower-alpha;}
ol.upper {list-style-type: upper-alpha;}
ul.upper {list-style-type: upper-alpha;}
table {padding: 5px;}
th { background: #cccccc;}
.display-element        {}
.display-label          {display: inline-block;
                         width: 100px;
                         padding-left: 5px;}
.display-field          {display: inline-block;
                         padding-left: 50px;
                         text-indent: -50px;
                         vertical-align: top;
                         width: 400px; }
span.data {display:inline-block;padding-left: 50px;text-indent: -50px;vertical-align: top;width: 400px;}
ol.dec {list-style-type: decimal;}
table.tbcontainer {padding-left: 20px;}
</style>

<script type="text/javascript">
</script>
</head>
<?php
if($iscetak)
{
	echo '<body onload="javascript:window.print();">';	
} else echo "<body>";
?>
<div align="center">
<img src="<?=base_url();?>assets/images/logo-cilegon.png">
<h3 align="center">PEMERINTAH KOTA CILEGON</h3>
<h4  align="center">INFORMASI JABATAN</h4>
</div>
<hr align="left" style="border: 1px solid #000; width:100%;" />

<table width="80%" class="tbcontainer">
	<tr>
		<td width="4%">1.</td>
		<td width="40%">Nama Jabatan</td>
		<td width="5%">:</td>
		<td width="50%"><?=$jabatan['nama'];?></td>
	</tr>
	<tr>
		<td>2.</td>
		<td>Kode Jabatan</td>
		<td>:</td>
		<td><?=$jabatan['kode'];?><td>
	</tr>
	<tr>
		<td>3.</td>
		<td>Unit Organisasi</td>
		<td>:</td>
		<td>
		<?php  echo $instansi;
		?></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>Eselon I</td>
		<td>:</td>
		<td><?=$dt1['unit_org_eselon_1'];?></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>Eselon II</td>
		<td>:</td>
		<td><?=$dt1['unit_org_eselon_2'];?></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>Eselon III</td>
		<td>:</td>
		<td><?=$dt1['unit_org_eselon_3'];?></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>Eselon IV</td>
		<td>:</td>
		<td><?=$dt1['unit_org_eselon_4'];?></td>
	</tr>
	<tr>
		<td colspan="4">&nbsp;</td>
	</tr>		
	<tr>
		<td>4.</td>
		<td colspan="3">Kedudukan dalam struktur Organisasi</td>
	</tr>
	<tr>
		<td colspan="4">
		<div align="center">
			<div class="tree">
			<iframe height="220" frameborder="0" width="600" src="<?=base_url();?>index.php/entry_sdm/isian_jabatan/diagram_sotk"></iframe>
			<?php
			//echo $data_diagram;
			?>
			</div>
		</div>
		</td>
	</tr>
	<tr>
		<td colspan="4">&nbsp;</td>
	</tr>		
	<tr>
		<td>5.</td>
		<td colspan="3">Ikhtisar Jabatan</td>
	</tr>
	<tr>
		<td colspan="4">
			<ul>
				<li style="text-align: justify;"><?=$dt1['ikhtisar_jabatan'];?></li>
			</ul>
		</td>
	</tr>
	<tr>
		<td colspan="4">&nbsp;</td>
	</tr>		
	<tr>
		<td>6.</td>
		<td>Uraian Tugas</td>
		<td></td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td colspan="4" style="text-align: justify;">		
<?php 
echo "<ol class='upper'>";
if(is_array($urtag))
{
foreach($urtag as $uraian)
{
	$patterns = array('/(^[a-zA-Z]+)\./');
	$txt = preg_replace($patterns, '', $uraian['uraian_tugas']);
	//preg_replace("/[^a-zA-Z0-9\/_|+ .-]/", '', $clean);
	//echo sprintf("<ol class='upper'>%s", $txt);
	echo sprintf("<li>%s</li>", trim($txt));
	$q_get_6 = sprintf("select * from frm_isian_6_uraian_tugas where kode_jabatan='%s' and kode_instansi='%s' and parent='%d' order by uraian_tugas asc", $jabatan['kode'], $jabatan['kode_instansi'], $uraian['id']);
	$data_q6 = $this->db->query($q_get_6)->result_array();	
	$a = 1;
	echo "Tahapan:<br/>";
	echo "<ol class='lower'>";
	foreach ($data_q6 as $c) {
		$ptrn = array('/(^[a-zA-Z]+)\./');
		$child = preg_replace($patterns, '', $c['uraian_tugas']);
		echo sprintf("<li>%s</li>", trim($child));
		$a++;
	}
	//echo "</li>";
	echo "</ol>";
}
} else echo "-- Data belum diisi --";
echo "</ol>";
?>				
		</td>
	</tr>
	<tr>
		<td colspan="4">&nbsp;</td>
	</tr>	
	<tr>
		<td>7.</td>
		<td>Bahan Kerja</td>
		<td></td>
		<td></td>
	</tr>	
	<tr>
		<td colspan="4">
		<ul>
			<li>
			<table border="1" cellpadding="2" cellspacing="2">
				<tr>
					<th>No</th>
					<th>Bahan Kerja</th>
					<th>Penggunaan dalam Tugas</th>
				</tr>
				<?php
				if(is_array($bahan_kerja))
				{
				$i=1;
				foreach ($bahan_kerja as $bk) {
					echo sprintf("
						<tr>
							<td>%s</td>
							<td>%s</td>
							<td>%s</td>
						</tr>					
					",$i,$bk['bahan_kerja'], $bk['penggunaan']);
					$i++;
				}
			} else echo "<tr><td colspan=3>-- data belum diisi --</td></tr>";
				?>
			</table>
			</li>
		</ul>
		</td>
	</tr>
	<tr>
		<td colspan="4">&nbsp;</td>
	</tr>
	<tr>
		<td>8.</td>
		<td>Perangkat Alat / Kerja</td>
		<td></td>
		<td></td>
	</tr>	
	<tr>
		<td colspan="4">
		<ul>
			<li>
			<table border="1">
				<tr>
					<th>No</th>
					<th>Perangkat Kerja</th>
					<th>Digunakan untuk Tugas</th>
				</tr>
				<?php
				if(is_array($alat_kerja))
				{
				$i=1;
				foreach ($alat_kerja as $ak) {
					echo sprintf("
						<tr>
							<td>%s</td>
							<td>%s</td>
							<td>%s</td>
						</tr>					
					",$i,$ak['perangkat_kerja'], $ak['digunakan_untuk']);
					$i++;
				}					
				} else echo "<tr><td colspan=3>-- data belum diisi --</td></tr>";
				?>
			</table>				
			</li>
		</ul>
		</td>
	</tr>
	<tr>
		<td colspan="4">&nbsp;</td>
	</tr>	
	<tr>
		<td>9.</td>
		<td>Hasil Kerja</td>
		<td></td>
		<td></td>
	</tr>	
	<tr>
		<td colspan="4">
			<ul>
				<li>
				<table width="100%" border="1" cellspacing="2" cellpadding="2">
					<tr>
						<th>No</th>
						<th>Hasil Kerja</th>
						<th>Satuan Hasil</th>
					</tr>
				<?php
				if(is_array($hasil_kerja))
				{
				$i=1;
				foreach ($hasil_kerja as $hk) {
					echo sprintf("
						<tr>
							<td>%s</td>
							<td>%s</td>
							<td>%s</td>
						</tr>					
					",$i,$hk['hasil_kerja'], $hk['satuan_hasil']);
					$i++;
				}
				} else echo "<tr><td colspan=3>-- data belum diisi --</td></tr>";
				?>
				</table>					
				</li>
			</ul>
		</td>
	</tr>	
	<tr>
		<td colspan="4">&nbsp;</td>
	</tr>
	<tr>
		<td align="left" valign="top">10.</td>
		<td align="left" valign="top" colspan="3">Tanggung Jawab</td>
	</tr>
	<tr>
		<td colspan="4">
				<?php
				if(is_array($tanggungjawab))
				{
				$i=1;
				echo "<ul>";
				foreach ($tanggungjawab as $tg) {
					echo sprintf("
						<li>%s</li>
					",$tg['tanggungjawab']);
					$i++;
				}
				} else echo "-- data belum diisi --";
				echo "</ul>";
				?>
		</td>
	</tr>
	<tr>
		<td colspan="4">&nbsp;</td>
	</tr>		
	<tr>
		<td>11.</td>
		<td colspan="3">Wewenang</td>
	</tr>
	<tr>
		<td colspan="4">
				<?php
				if(is_array($wewenang))
				{					
				$i=1;
				echo "<ul>";
				foreach ($wewenang as $ww) {
					echo sprintf("
						<li>%s</li>
					",$ww['wewenang']);
					$i++;
				}
				echo "</ul>";
				} else echo "-- Data belum diisi --";
				?>
		</td>
	</tr>
	<tr>
		<td colspan="4">&nbsp;</td>
	</tr>
	<tr>
		<td>12.</td>
		<td>Korelasi Jabatan</td>
		<td></td>
		<td></td>
	</tr>	
	<tr>
		<td colspan="4">
		<ul>
			<li>
			<table border="1" width="100%">
				<tr>
					<th>No</th>
					<th>Jabatan</th>
					<th>Unit Kerja / Instansi</th>
					<th>Dalam Hal</th>
				</tr>
				<?php
				if(is_array($korelasi_jabatan))
				{
				$i=1;
				foreach ($korelasi_jabatan as $kj) {
					echo sprintf("
						<tr>
							<td>%s</td>
							<td>%s</td>
							<td>%s</td>
							<td>%s</td>
						</tr>					
					",$i,$kj['jabatan'], $kj['unit_kerja_instansi'],$kj['dalam_hal']);
					$i++;
				}
			} else echo "<tr><td align='center' colspan=4>-- data belum diisi --</td></tr>";
				?>
			</table>				
			</li>
		</ul>
		</td>
	</tr>		
	<tr>
		<td colspan="4">&nbsp;</td>
	</tr>	
	<tr>
		<td>13.</td>
		<td colspan="3">Kondisi Lingkungan Kerja</td>
	</tr>	
	<tr>
		<td colspan="4">
		<ul>
			<li>
			<table border="1" width="100%">
				<tr>
					<th>No</th>
					<th>Aspek</th>
					<th>Faktor</th>
				</tr>
				<?php
				if(is_array($kondisi_kerja))
				{
				$i=1;
				foreach ($kondisi_kerja as $kkj) {
					echo sprintf("
						<tr>
							<td>%s</td>
							<td>%s</td>
							<td>%s</td>
						</tr>					
					",$i,$kkj['aspek'], $kkj['faktor']);
					$i++;
				}
				} else echo "<tr><td align='center' colspan=3>-- data belum diisi --</td></tr>"; 
				?>
			</table>				
			</li>
		</ul>
		</td>
	</tr>	
	<tr>
		<td colspan="4">&nbsp;</td>
	</tr>		
	<tr>
		<td>14.</td>
		<td colspan="3">Resiko Bahaya</td>
	</tr>	
	<tr>
		<td colspan="4">
		<ul>
			<li>				
			<table border="1" width="100%">
				<tr>
					<th>No</th>
					<th>Fisik / Mental</th>
					<th>Penyebab</th>
				</tr>
				<?php
				if(is_array($resiko))
				{
				$i=1;
				foreach ($resiko as $res) {
					echo sprintf("
						<tr>
							<td>%s</td>
							<td>%s</td>
							<td>%s</td>
						</tr>					
					",$i,$res['fisik_mental'],$res['penyebab']);
					$i++;
				}
				} else echo "<tr><td align='center' colspan=3>-- data belum diisi --</td></tr>";
				?>
			</table>
			</li>
		</ul>
		</td>
	</tr>		
	<tr>
		<td colspan="4">&nbsp;</td>
	</tr>	
	<tr>
		<td>15.</td>
		<td>Syarat Jabatan</td>
		<td></td>
		<td></td>
	</tr>	
	<tr>
		<td colspan="4">
		<ul>
			<li>
				
		<table width="100%" border="0">
			<tr>
				<td width="40%">a. Pangkat / Gol. Ruang</td>
				<td width="5%">:</td>
				<td width="55%"><?php echo $sj['pangkat'] . ' / '. $sj['golongan'];?></td>
			</tr>
			<tr>
				<td align="left" valign="top">b. Pendidikan</td>
				<td align="left" valign="top">:</td>
				<td align="left" valign="top">
					<?php
					$pend = explode(',', $sj['pendidikan']);
					if(is_array($pend))
					{
					echo "<ol class='sq'>";
					foreach ($pend as $k=>$pk) {
						echo sprintf("<li>%s</li>", $pk);
					}
					} else echo "-- data belum diisi --";
					echo "</ol>";
					?>
				</td>
			</tr>			
			<tr>
				<td>c. Kursus / Diklat</td>
				<td></td>
				<td></td>
			</tr>			
			<tr>
				<td colspan="3">
				<ul>
					<li>						
					<table width="100%" cellpadding="5" cellspacing="5">
						<tr>
							<td align="left" valign="top" width="25%">1) Penjenjangan</td>
							<td align="left" valign="top">:</td>
							<td align="left" valign="top">
							<ul style="list-style-type: square;">
								<li><?=$penjenjangan;?></li>
							</ul>
							</td>
						</tr>
						<tr>
							<td align="left" valign="top" width="15%">2) Teknis</td>
							<td align="left" valign="top">:</td>
							<td align="left" valign="top">
					<?php					
					echo "<ol class='sq'>";
					if(is_array($sj_teknis))
					{
					foreach ($sj_teknis as $k=>$v) {
						echo sprintf("<li>%s</li>", $v['teknis']);
					}
				} else echo "-- data belum diisi --";
					echo "</ol>";
					?>
							</td>
						</tr>
					</table>
					</li>
				</ul>
				</td>
			</tr>			
			<tr>
				<td>d. Pengalaman Kerja</td>
				<td>:</td>
				<td><?=$sj['pengalaman'];?></td>
			</tr>						
			<tr>
				<td colspan="3">e. Pengetahuan Kerja</td>
			</tr>						
			<tr>
				<td colspan="3">
					<ol class="dec">
				<?php
				if(is_array($sj_pengetahuan))
				{
				foreach ($sj_pengetahuan as $sjtahu) {
					echo sprintf("
						<li>%s</li>
					",trim($sjtahu['pengetahuan_kerja']));
				}
			} else echo "-- data belum diisi --";
				?>																
				</td>
			</tr>						
			<tr>
				<td align="left" valign="top" colspan="3">f. Keterampilan Kerja</td>
			</tr>						
			<tr>
				<td colspan="3" align="left" valign="top" >
					<ol class="dec">
				<?php
				if(is_array($sj_keterampilan))
				{
				foreach ($sj_keterampilan as $sjket) {
					echo sprintf("
						<li>%s</li>
					",trim($sjket['keterampilan']));
				}
			} else echo "-- data belum diisi --";
				?>											
					</ol>					
				</td>
			</tr>						
			<tr>
				<td>g. Bakat Kerja</td>
				<td></td>
				<td></td>
			</tr>						
			<tr>
				<td colspan="3">
					<ul>
						<li>
							<table width="100%">

				<?php
				if(is_array($sj_bakat_kerja))
				{
				$i=1;
				foreach ($sj_bakat_kerja as $sjbk) {
					echo sprintf("
					<tr>
						<td align='left' valign='top'>%s).</td>
						<td align='left' valign='top'>%s</td>
						<td align='left' valign='top'>%s</td>
					</tr>
					",$i, $sjbk['kode_bakat_kerja'], $sjbk['arti']);
					$i++;
				}
				} else echo "<tr><td align='center' colspan=3>-- data belum diisi --</td></tr>";				?>							
							</table>
						</li>
					</ul>
				</td>
			</tr>						
			<tr>
				<td>h. Temperamen Kerja</td>
				<td></td>
				<td></td>
			</tr>	
			<tr>
				<td colspan="3">
					<ul>
						<li>
							<table width="100%">
				<?php
				if(is_array($sj_temperamen))
				{
				$i=1;
				foreach ($sj_temperamen as $sjtmpr) {
					echo sprintf("
					<tr>
						<td align='left' valign='top'>%s).</td>
						<td align='left' valign='top'>%s</td>
						<td align='left' valign='top'>%s</td>
					</tr>
					",$i, $sjtmpr['kode_tempramen'], $sjtmpr['arti']);
					$i++;
				}
				} else echo "<tr><td align='center' colspan=3>-- data belum diisi --</td></tr>";
				?>
							</table>
						</li>
					</ul>
				</td>
			</tr>
			<tr>
				<td>i. Minat Kerja</td>
				<td></td>
				<td></td>
			</tr>						
			<tr>
				<td colspan="3">
				<ul>
					<li>						
				<table width="100%">
				<?php
				if(is_array($sj_minat_kerja))
				{
				$i=1;
				foreach ($sj_minat_kerja as $sjmk) {
					echo sprintf("
					<tr>
						<td align='left' valign='top'>%s.</td>
						<td align='left' valign='top'>%s</td>
						<td align='left' valign='top'>%s</td>
						<td align='left' valign='top'>%s</td>
					</tr>
					",$i, $sjmk['kode_minat'], $sjmk['arti'], $sjmk['uraian']);
					$i++;
				}
				} else echo "<tr><td align='center' colspan=4>-- data belum diisi --</td></tr>";			
				?>
				</table>
					</li>
				</ul>
				</td>
			</tr>						
			<tr>
				<td>j. Upaya Fisik</td>
				<td></td>
				<td></td>
			</tr>						
			<tr>
				<td colspan="4">
				<?php
				if(is_array($sj_upaya_fisik))
				{
				$i=1;
				echo "<ol>";
				foreach ($sj_upaya_fisik as $sjuf) {
					echo sprintf("
					<li class='dec'>%s</li>
					",$sjuf['kode']);
					$i++;
				}
				echo "</ol>";
				} else echo "-- data belum diisi --";
				?>
				</td>
			</tr>						
			<tr>
				<td colspan="3">k. Kondisi Fisik</td>
			</tr>						
			<tr>
				<td colspan="3">
				<ul>
					<li>			
					<table width="50%">
						<tr>
							<td width="5%">1)</td>
							<td width="50%">Jenis Kelamin</td>
							<td width="5%">:</td>
							<td><?=$sj['jk'];?></td>
						</tr>
						<tr>
							<td>2)</td>
							<td>Umur</td>
							<td>:</td>
							<td><?=$sj['umur'];?></td>
						</tr>
						<tr>
							<td>3)</td>
							<td>Tinggi Badan</td>
							<td>:</td>
							<td><?=$sj['tinggi_badan'];?> cm</td>
						</tr>
						<tr>
							<td>4)</td>
							<td>Berat badan</td>
							<td>:</td>
							<td><?=$sj['berat_badan'];?> kg</td>
						</tr>
						<tr>
							<td>5)</td>
							<td>Postur badan</td>
							<td>:</td>
							<td><?=$sj['postur_badan'];?></td>
						</tr>
						<tr>
							<td>6)</td>
							<td>Penampilan</td>
							<td>:</td>
							<td><?=$sj['penampilan'];?></td>
						</tr>
					</table>						
					</li>
				</ul>
				</td>
			</tr>						
			<tr>
				<td colspan="3">l. Fungsi Kerja</td>
			</tr>						
			<tr>
				<td colspan="3">				
				<?php
				if(is_array($sj_fungsi_kerja))
				{
				$i=1;
				echo "<ol>";				
				foreach ($sj_fungsi_kerja as $sjfk) {
					echo sprintf("
					<li class='dec'>%s  -  %s</li>
					",$sjfk['kode'], $sjfk['arti']);
					$i++;
				}
				echo "</ol>";
				} else echo "-- data belum diisi --";
				?>
				</td>
			</tr>						
		</table>
			</li>
		</ul>		
		</td>
	</tr>		
	<tr>
		<td>16.</td>
		<td colspan="3">Prestasi Kerja Yang Diharapkan</td>
	</tr>
	<tr>
		<td colspan="4">
			<ul>
				<li>
					<table width="100%" border="1">
						<tr>
							<th>No</th>
							<th>Satuan Hasil</th>
							<th>Jumlah Hasil (dalam 1 tahun)</th>
							<th>Waktu Penyelesaian (menit)</th>
						</tr>
				<?php
				if(is_array($prestasi))
				{
				$i=1;
				foreach ($prestasi as $pr) {
					echo sprintf("
						<tr>
							<td>%s</td>
							<td>%s</td>
							<td align='center'>%s</td>
							<td align='center'>%s menit</td>
						</tr>					
					",$i,$pr['hasil_kerja'],$pr['jml_hasil'], $pr['waktu_selesai']);
					$i++;
				}
				} else echo "<tr><td align='center' colspan=4>-- data belum diisi --</td></tr>";
				?>
					</table>
				</li>
			</ul>
		</td>
	</tr>
	<tr>
		<td>17.</td>
		<td>Butir Informasi Lain</td>
		<td></td>
		<td></td>
	</tr>
	<tr>
		<td colspan="4">
			<ul>
				<li><?=trim($sj_infolain['informasi_lain']);?></li>
			</ul>
		</td>
	</tr>
	<tr>
		<td colspan="4"><p>&nbsp;</p></td>
	</tr>
	<tr>
		<td colspan="4">
			<div align="right">Cilegon, <?=$tgl_dibuat;?></div>
			<div align="center"><p>&nbsp;</p></div>
			<table width="100%" align="center">
				<tr>
					<td align="center">Mengetahui Atasan Langsung</td>
					<td align="center">Yang membuat</td>
				</tr>
				<tr>
					<td align="center" colspan="2"><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p></td>
				</tr>
				<tr>
					<td align="center"><strong><?=$dt1['atasan_langsung'];?></strong></td>
					<td align="center"><strong><?=$dt1['yg_membuat'];?></strong></td>
				</tr>
				<tr>
					<td align="center"><strong><?=$dt1['nip_atasan'];?></strong></td>
					<td align="center"><strong><?=$dt1['nip_pembuat'];?></strong></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</body>
</html>