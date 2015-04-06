/**
 * Created by KAI2013 on 3/21/14.
 */


var hari_array      = new Array("MINGGU","SENIN","SELASA","RABU","KAMIS","JUMAT","SABTU")
var bulan_array     = new Array("JANUARI","FEBRUARI","MARET","APRIL","MEI","JUNI","JULI","AGUSTUS","SEPTEMBER","OKTOBER","NOPEMBER","DESEMBER")

function formatFullTglId(tgl) {
    var tahun   = tgl.getFullYear()
    var hari    = tgl.getDay()
    var bulan   = tgl.getMonth()
    var tanggal = tgl.getDate()

    return hari_array[hari] + ', ' + tanggal + ' ' + bulan_array[bulan] + ' ' + tahun;
}

function formatTglId(tgl) {
    var tahun   = tgl.getFullYear()
    var hari    = tgl.getDay()
    var bulan   = tgl.getMonth()
    var tanggal = tgl.getDate()

    return tanggal + ' ' + bulan_array[bulan] + ' ' + tahun;
}
