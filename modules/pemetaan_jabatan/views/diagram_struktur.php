<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <title>Diagram Struktur Jabatan</title>
  <script type="text/javascript" src="<?=$this->config->item('base_url');?>assets/js/ext-base-3.4.js"></script>
  <script type="text/javascript" src="<?=$this->config->item('base_url');?>assets/js/ext-all-3.4.js"></script>   
  <!--
  <script type="text/javascript" src="<?=$this->config->item('base_url');?>resources/ext4/bootstrap.js"></script>  
  -->
  <script type="text/javascript" src="<?=$this->config->item('base_url');?>assets/js/ExtJSOrgChart.js"></script>
  <link href="<?=$this->config->item('base_url');?>resources/ext4/resources/css/ext-all.css" rel="stylesheet" type="text/css" />
  <!-- <link href="<?=$this->config->item('base_url');?>resources/ext4/resources/css/ext-all-gray.css" rel="stylesheet" type="text/css" /> -->
  <!-- <link rel="stylesheet" href="<?=$this->config->item('base_url');?>assets/css/bootstrap.min.css"/> -->
	<link rel="stylesheet" href="<?=$this->config->item('base_url');?>assets/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="<?=$this->config->item('base_url');?>assets/css/ExtJSOrgChart.css"/>
  <link rel="stylesheet" href="<?=$this->config->item('base_url');?>assets/css/custom.css"/>
  <link href="<?=$this->config->item('base_url');?>assets/css/jquery-ui.css" rel="stylesheet">
  <!-- <link rel="stylesheet" href="<?=$this->config->item('base_url');?>assets/css/dialog.css"/> -->
<script src="<?=$this->config->item('base_url');?>js/jquery.min.js"></script>
<script src="<?=$this->config->item('base_url');?>js/jquery-ui.min.js"></script>
<link rel="stylesheet" href="<?=$this->config->item('base_url');?>assets/css/bootstrap-theme.min.css">
<script src="<?=$this->config->item('base_url');?>assets/js/bootstrap.min.js"></script>
	
	
<script>
$(document).ready(function() {
$(function() {
	$("#show_pilih").dialog("open");	
	$("#dialog").dialog({
		autoOpen: false
	});
	$("#button").on("click", function() {
	//$("#dialog").dialog("open");	
	});
	
	$("#tabs" ).tabs({
      beforeLoad: function( event, ui ) {
        ui.jqXHR.error(function() {
          ui.panel.html(
            "Couldn't load this tab. We'll try to fix this as soon as possible. " +
            "If this wouldn't be a demo." );
        });
      }
    });
	
});

// Validating Form Fields.....
$("#submit").click(function(e) {
var email = $("#email").val();
var name = $("#name").val();
var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
if (email === '' || name === '') {
alert("Please fill all fields...!!!!!!");
e.preventDefault();
} else if (!(email).match(emailReg)) {
alert("Invalid Email...!!!!!!");
e.preventDefault();
} else {
alert("Form Submitted Successfully......");
}
});

});

function show_menu(id)
{
	$("#dialog-confirm").dialog({
		resizable: false,
		height:150,
		modal: true,
		width:'40%',
		//autoOpen: false,
		buttons: {
			"Edit Item": function() {
				$(this).dialog("close");
				var theDialog = $("#dialog").dialog({
						text: "Konfirmasi / Pilih Aksi",
						autoOpen: false,
						resizable: false,
						modal: true,
						width:'60%'
				});
				$.post( "<?=$this->config->item('base_url');?>index.php/pemetaan_jabatan/main/set_tree_parent", { id: id} );	
				$(theDialog).dialog("open");
			},
			"Hapus item": function() {
				$(this).dialog("close");
				if(confirm("Apakah anda yakin akan menghapus item ini?"))
				{
					var posting = $.post( "<?=$this->config->item('base_url');?>index.php/pemetaan_jabatan/main/hapus_tree", { id: id} );
					posting.done(function(data) {
						alert("Item berhasil dihapus!");
						window.location = '<?=$this->config->item('base_url');?>index.php/pemetaan_jabatan/main/index';					
					});
				}
			},
			Cancel: function() {
				$(this).dialog("close");
			}
		}
	});
}

</script>

</head>
<body>
<div id="dialog-confirm" title="Pilihan">
	<span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Apakah anda akan mengedit / menghapus item. Silahkan pilih!
</div>

<div id="dialog" title="Dialog Form">
<div id="tabs">
  <ul>
    <li><a href="ajax/content1.html">Nama Jabatan (t_tree_jabatan)</a></li>
    <li><a href="<?=$this->config->item('base_url');?>index.php/pemetaan_jabatan/main/form_tree">Tambah Sub (m_tree)</a></li>
  </ul>
</div>
</div>

<?php echo $id, ' :: ',$kode; ?>
<div id="chart" class="orgChart"></div>
<script type="text/javascript">
window.onload=function(){
	Ext.onReady(function () { 	
		Ext.Ajax.request({
				url : '<?=$this->config->item('base_url');?>index.php/pemetaan_jabatan/main/get_data_str/<?php echo $id;?>',
				method : "GET",
				success : function(response, opts) {
							var nodeList = new Array();
							var data= Ext.decode(response.responseText);
							data.sort(function(a,b){
								return parseInt(a.parentId) - parseInt(b.parentId);
							});
							var rootNode = new ExtJSOrgChart.createNode(data[0].id,data[0].markup,data[0].parentId);
							nodeList.push(rootNode);
							for(var i=1;i<data.length;i++){
									var nd = new ExtJSOrgChart.createNode(data[i].id,data[i].markup,data[i].parentId);
									var found=false;
									for(var k=0;k<nodeList.length;k++){
										if(nodeList[k].getId()==data[i].parentId){
											nodeList[k].addChild(nd);
											found=true;
											break;
										}
									}
									if(found){
										nodeList.push(nd);
									}
							}
							
							ExtJSOrgChart.prepareTree({
								chartElement: 'chart',
								rootObject: rootNode,
								depth: -1
							});	
				} , 
				failure : function(response, opts) {          
					alert("Error while loading data : "+response.responseText);                  
				}
			});								
	});
}  

</script>  
</body>
</html>