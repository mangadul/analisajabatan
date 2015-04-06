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

//Format JSON @277-F4F79297
    $Component->nama->SetValue(str_replace(array("\\", '"', "/", "\n" , "\r", "\t", "\b"), array("\\\\", '\"', '\/', '\\n', '', '\t', '\b'), $Component->nama->GetValue()));
    
//End Format JSON

//Close m_jabatan_BeforeShowRow @2-B0DC61A2
    return $m_jabatan_BeforeShowRow;
}
//End Close m_jabatan_BeforeShowRow


?>
