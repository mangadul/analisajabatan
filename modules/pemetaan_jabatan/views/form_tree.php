<script>
$("#form_tree").submit(function(event) {
  event.preventDefault();
  var posting = $.post("<?=$this->config->item('base_url');?>index.php/pemetaan_jabatan/main/tambah_tree", $("#form_tree").serialize());
  posting.done(function(data) {
	//alert(data);
    $("#msg").empty().append(data);	
	$("#dialog-message" ).dialog({
		modal: true,
		buttons: {
			Ok: function() {
				$(this).dialog("close");
			}
		}
	}).delay(800).fadeOut(350);	
	window.location = '<?=$this->config->item('base_url');?>index.php/pemetaan_jabatan/main/index';
  });
});  
</script>
<div id="dialog-message" title="Status"><div id="msg"></div></div>
<?=form_open("pemetaan_jabatan/main/tambah_tree", 'id="form_tree" class="form-horizontal" role="form"');?>
  <div class="form-group">
    <label for="markup" class="col-sm-2 control-label">Instansi</label>
    <div class="col-sm-9">	
	<?=form_hidden('parent', $parentid);?>    
	<?=form_dropdown('id_jenis_diagram', $m_jj, '');?>    
	</div>
  </div>
  <div class="form-group">
    <label for="markup" class="col-sm-2 control-label">Item</label>
    <div class="col-sm-9">
	<?=form_input('markup', '');?>	
    </div>
  </div>
  <div class="form-group">
    <label for="markup" class="col-sm-2 control-label">Jabatan</label>
    <div class="col-sm-9">
	<?=form_dropdown('id_jabatan', $m_jabatan, '');?>	
    </div>
  </div>
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-9">
		  <button type="submit" class="btn btn-default">Simpan</button>
		</div>
	</div>  
<?=form_close();?>