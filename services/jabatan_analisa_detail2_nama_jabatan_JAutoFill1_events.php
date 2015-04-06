<?php
//BindEvents Method @1-26D67C0E
function BindEvents()
{
    global $m_jabatan;
    $m_jabatan->CCSEvents["BeforeShowRow"] = "m_jabatan_BeforeShowRow";
}
//End BindEvents Method

//m_jabatan_BeforeShowRow @2-BBD26F72
function m_jabatan_BeforeShowRow(& $sender)
{
    $m_jabatan_BeforeShowRow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $m_jabatan; //Compatibility
//End m_jabatan_BeforeShowRow

//Format JSON @178-C17EAB4F
    $Component->kode_rumpun->SetValue(str_replace(array("\\", '"', "/", "\n" , "\r", "\t", "\b"), array("\\\\", '\"', '\/', '\\n', '', '\t', '\b'), $Component->kode_rumpun->GetValue()));
    $Component->kode->SetValue(str_replace(array("\\", '"', "/", "\n" , "\r", "\t", "\b"), array("\\\\", '\"', '\/', '\\n', '', '\t', '\b'), $Component->kode->GetValue()));
    $Component->nama->SetValue(str_replace(array("\\", '"', "/", "\n" , "\r", "\t", "\b"), array("\\\\", '\"', '\/', '\\n', '', '\t', '\b'), $Component->nama->GetValue()));
    $Component->kualifikasi_pendidikan->SetValue(str_replace(array("\\", '"', "/", "\n" , "\r", "\t", "\b"), array("\\\\", '\"', '\/', '\\n', '', '\t', '\b'), $Component->kualifikasi_pendidikan->GetValue()));
    $Component->keterangan->SetValue(str_replace(array("\\", '"', "/", "\n" , "\r", "\t", "\b"), array("\\\\", '\"', '\/', '\\n', '', '\t', '\b'), $Component->keterangan->GetValue()));
    $Component->ikhtisar->SetValue(str_replace(array("\\", '"', "/", "\n" , "\r", "\t", "\b"), array("\\\\", '\"', '\/', '\\n', '', '\t', '\b'), $Component->ikhtisar->GetValue()));
    $Component->eselon1->SetValue(str_replace(array("\\", '"', "/", "\n" , "\r", "\t", "\b"), array("\\\\", '\"', '\/', '\\n', '', '\t', '\b'), $Component->eselon1->GetValue()));
    $Component->eselon2->SetValue(str_replace(array("\\", '"', "/", "\n" , "\r", "\t", "\b"), array("\\\\", '\"', '\/', '\\n', '', '\t', '\b'), $Component->eselon2->GetValue()));
    $Component->eselon3->SetValue(str_replace(array("\\", '"', "/", "\n" , "\r", "\t", "\b"), array("\\\\", '\"', '\/', '\\n', '', '\t', '\b'), $Component->eselon3->GetValue()));
    $Component->eselon4->SetValue(str_replace(array("\\", '"', "/", "\n" , "\r", "\t", "\b"), array("\\\\", '\"', '\/', '\\n', '', '\t', '\b'), $Component->eselon4->GetValue()));
    
//End Format JSON

//Close m_jabatan_BeforeShowRow @2-B0DC61A2
    return $m_jabatan_BeforeShowRow;
}
//End Close m_jabatan_BeforeShowRow


?>
