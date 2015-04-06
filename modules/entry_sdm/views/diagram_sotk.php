<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Diagram Struktur Organisasi</title>
<link href="<?=$this->config->item('base_url');?>assets/css/tree.css" rel="stylesheet" type="text/css" />

<style>
</style>

<script type="text/javascript">
</script>
</head>
<body>
<div class="tree">
<?php
echo isset($data) ? $data :"Data Diagram belum diisi";
?>
</div>
</body>
</html>