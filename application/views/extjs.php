<?=header('P3P: CP="CAO PSA OUR"');?>

<meta HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE"></meta>
<meta HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE"></meta>
<script type="text/javascript" src="<?=base_url()?>resources/js/dateformat.js"></script>

<script type="text/javascript" src="<?=base_url()?>resources/ext4/examples/shared/include-ext.js"></script>
<script type="text/javascript" src="<?=base_url()?>resources/ext4/examples/shared/examples.js"></script>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>resources/ext4/examples/shared/example.css" />

<script>
    // WAKTU SERVER
    var serverTime   = new Date(<?= date('Y,').(date('m')-1).date(', d, H, i, s, 0')?>);
    var clientTime   = new Date();
    var Diff         = serverTime.getTime() - clientTime.getTime();
    function getJamServer(){
        var clientTime   = new Date();
        var datetimeNow  = new Date(clientTime.getTime() + Diff);
        return datetimeNow;
    }
    
    // FORMAT RUPIAH   
    function formatCurrency(nStr) {
        nStr += '';
        var x = nStr.split('.');
        var x1 = x[0];
        var x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + '.' + '$2');
        }
        return 'Rp '+x1 + x2 +',00';
    }
</script>