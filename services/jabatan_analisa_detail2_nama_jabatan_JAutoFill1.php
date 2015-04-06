<?php
//Include Common Files @1-2A770DF1
define("RelativePath", "..");
define("PathToCurrentPage", "/services/");
define("FileName", "jabatan_analisa_detail2_nama_jabatan_JAutoFill1.php");
include_once(RelativePath . "/Common.php");
include_once(RelativePath . "/Template.php");
include_once(RelativePath . "/Sorter.php");
include_once(RelativePath . "/Navigator.php");
//End Include Common Files

class clsGridm_jabatan { //m_jabatan class @2-A237DACB

//Variables @2-6E51DF5A

    // Public variables
    public $ComponentType = "Grid";
    public $ComponentName;
    public $Visible;
    public $Errors;
    public $ErrorBlock;
    public $ds;
    public $DataSource;
    public $PageSize;
    public $IsEmpty;
    public $ForceIteration = false;
    public $HasRecord = false;
    public $SorterName = "";
    public $SorterDirection = "";
    public $PageNumber;
    public $RowNumber;
    public $ControlsVisible = array();

    public $CCSEvents = "";
    public $CCSEventResult;

    public $RelativePath = "";
    public $Attributes;

    // Grid Controls
    public $StaticControls;
    public $RowControls;
//End Variables

//Class_Initialize Event @2-CA068AB3
    function clsGridm_jabatan($RelativePath, & $Parent)
    {
        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->ComponentName = "m_jabatan";
        $this->Visible = True;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid m_jabatan";
        $this->Attributes = new clsAttributes($this->ComponentName . ":");
        $this->DataSource = new clsm_jabatanDataSource($this);
        $this->ds = & $this->DataSource;
        $this->PageSize = CCGetParam($this->ComponentName . "PageSize", "");
        if(!is_numeric($this->PageSize) || !strlen($this->PageSize))
            $this->PageSize = 10;
        else
            $this->PageSize = intval($this->PageSize);
        if ($this->PageSize > 100)
            $this->PageSize = 100;
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: Grid " . $this->ComponentName . "<BR>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));
        if ($this->PageNumber <= 0) $this->PageNumber = 1;

        $this->id = new clsControl(ccsLabel, "id", "id", ccsInteger, "", CCGetRequestParam("id", ccsGet, NULL), $this);
        $this->id->HTML = true;
        $this->kode_rumpun = new clsControl(ccsLabel, "kode_rumpun", "kode_rumpun", ccsText, "", CCGetRequestParam("kode_rumpun", ccsGet, NULL), $this);
        $this->kode_rumpun->HTML = true;
        $this->kode = new clsControl(ccsLabel, "kode", "kode", ccsText, "", CCGetRequestParam("kode", ccsGet, NULL), $this);
        $this->kode->HTML = true;
        $this->nama = new clsControl(ccsLabel, "nama", "nama", ccsText, "", CCGetRequestParam("nama", ccsGet, NULL), $this);
        $this->nama->HTML = true;
        $this->kualifikasi_pendidikan = new clsControl(ccsLabel, "kualifikasi_pendidikan", "kualifikasi_pendidikan", ccsText, "", CCGetRequestParam("kualifikasi_pendidikan", ccsGet, NULL), $this);
        $this->kualifikasi_pendidikan->HTML = true;
        $this->keterangan = new clsControl(ccsLabel, "keterangan", "keterangan", ccsText, "", CCGetRequestParam("keterangan", ccsGet, NULL), $this);
        $this->keterangan->HTML = true;
        $this->id_jenis_jabatan = new clsControl(ccsLabel, "id_jenis_jabatan", "id_jenis_jabatan", ccsInteger, "", CCGetRequestParam("id_jenis_jabatan", ccsGet, NULL), $this);
        $this->id_jenis_jabatan->HTML = true;
        $this->ikhtisar = new clsControl(ccsLabel, "ikhtisar", "ikhtisar", ccsText, "", CCGetRequestParam("ikhtisar", ccsGet, NULL), $this);
        $this->ikhtisar->HTML = true;
        $this->eselon1 = new clsControl(ccsLabel, "eselon1", "eselon1", ccsText, "", CCGetRequestParam("eselon1", ccsGet, NULL), $this);
        $this->eselon1->HTML = true;
        $this->eselon2 = new clsControl(ccsLabel, "eselon2", "eselon2", ccsText, "", CCGetRequestParam("eselon2", ccsGet, NULL), $this);
        $this->eselon2->HTML = true;
        $this->eselon3 = new clsControl(ccsLabel, "eselon3", "eselon3", ccsText, "", CCGetRequestParam("eselon3", ccsGet, NULL), $this);
        $this->eselon3->HTML = true;
        $this->eselon4 = new clsControl(ccsLabel, "eselon4", "eselon4", ccsText, "", CCGetRequestParam("eselon4", ccsGet, NULL), $this);
        $this->eselon4->HTML = true;
    }
//End Class_Initialize Event

//Initialize Method @2-90E704C5
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->DataSource->PageSize = & $this->PageSize;
        $this->DataSource->AbsolutePage = & $this->PageNumber;
        $this->DataSource->SetOrder($this->SorterName, $this->SorterDirection);
    }
//End Initialize Method

//Show Method @2-54D08914
    function Show()
    {
        $Tpl = CCGetTemplate($this);
        global $CCSLocales;
        if(!$this->Visible) return;

        $this->RowNumber = 0;

        $this->DataSource->Parameters["urlkeyword"] = CCGetFromGet("keyword", NULL);

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect", $this);


        $this->DataSource->Prepare();
        $this->DataSource->Open();
        $this->HasRecord = $this->DataSource->has_next_record();
        $this->IsEmpty = ! $this->HasRecord;
        $this->Attributes->Show();

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow", $this);
        if(!$this->Visible) return;

        $GridBlock = "Grid " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $GridBlock;


        if (!$this->IsEmpty) {
            $this->ControlsVisible["id"] = $this->id->Visible;
            $this->ControlsVisible["kode_rumpun"] = $this->kode_rumpun->Visible;
            $this->ControlsVisible["kode"] = $this->kode->Visible;
            $this->ControlsVisible["nama"] = $this->nama->Visible;
            $this->ControlsVisible["kualifikasi_pendidikan"] = $this->kualifikasi_pendidikan->Visible;
            $this->ControlsVisible["keterangan"] = $this->keterangan->Visible;
            $this->ControlsVisible["id_jenis_jabatan"] = $this->id_jenis_jabatan->Visible;
            $this->ControlsVisible["ikhtisar"] = $this->ikhtisar->Visible;
            $this->ControlsVisible["eselon1"] = $this->eselon1->Visible;
            $this->ControlsVisible["eselon2"] = $this->eselon2->Visible;
            $this->ControlsVisible["eselon3"] = $this->eselon3->Visible;
            $this->ControlsVisible["eselon4"] = $this->eselon4->Visible;
            while ($this->ForceIteration || (($this->RowNumber < $this->PageSize) &&  ($this->HasRecord = $this->DataSource->has_next_record()))) {
                // Parse Separator
                if($this->RowNumber) {
                    $this->Attributes->Show();
                    $Tpl->parseto("Separator", true, "Row");
                }
                $this->RowNumber++;
                if ($this->HasRecord) {
                    $this->DataSource->next_record();
                    $this->DataSource->SetValues();
                }
                $Tpl->block_path = $ParentPath . "/" . $GridBlock . "/Row";
                $this->id->SetValue($this->DataSource->id->GetValue());
                $this->kode_rumpun->SetValue($this->DataSource->kode_rumpun->GetValue());
                $this->kode->SetValue($this->DataSource->kode->GetValue());
                $this->nama->SetValue($this->DataSource->nama->GetValue());
                $this->kualifikasi_pendidikan->SetValue($this->DataSource->kualifikasi_pendidikan->GetValue());
                $this->keterangan->SetValue($this->DataSource->keterangan->GetValue());
                $this->id_jenis_jabatan->SetValue($this->DataSource->id_jenis_jabatan->GetValue());
                $this->ikhtisar->SetValue($this->DataSource->ikhtisar->GetValue());
                $this->eselon1->SetValue($this->DataSource->eselon1->GetValue());
                $this->eselon2->SetValue($this->DataSource->eselon2->GetValue());
                $this->eselon3->SetValue($this->DataSource->eselon3->GetValue());
                $this->eselon4->SetValue($this->DataSource->eselon4->GetValue());
                $this->Attributes->SetValue("rowNumber", $this->RowNumber);
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow", $this);
                $this->Attributes->Show();
                $this->id->Show();
                $this->kode_rumpun->Show();
                $this->kode->Show();
                $this->nama->Show();
                $this->kualifikasi_pendidikan->Show();
                $this->keterangan->Show();
                $this->id_jenis_jabatan->Show();
                $this->ikhtisar->Show();
                $this->eselon1->Show();
                $this->eselon2->Show();
                $this->eselon3->Show();
                $this->eselon4->Show();
                $Tpl->block_path = $ParentPath . "/" . $GridBlock;
                $Tpl->parse("Row", true);
            }
        }

        $errors = $this->GetErrors();
        if(strlen($errors))
        {
            $Tpl->replaceblock("", $errors);
            $Tpl->block_path = $ParentPath;
            return;
        }
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

//GetErrors Method @2-901D024B
    function GetErrors()
    {
        $errors = "";
        $errors = ComposeStrings($errors, $this->id->Errors->ToString());
        $errors = ComposeStrings($errors, $this->kode_rumpun->Errors->ToString());
        $errors = ComposeStrings($errors, $this->kode->Errors->ToString());
        $errors = ComposeStrings($errors, $this->nama->Errors->ToString());
        $errors = ComposeStrings($errors, $this->kualifikasi_pendidikan->Errors->ToString());
        $errors = ComposeStrings($errors, $this->keterangan->Errors->ToString());
        $errors = ComposeStrings($errors, $this->id_jenis_jabatan->Errors->ToString());
        $errors = ComposeStrings($errors, $this->ikhtisar->Errors->ToString());
        $errors = ComposeStrings($errors, $this->eselon1->Errors->ToString());
        $errors = ComposeStrings($errors, $this->eselon2->Errors->ToString());
        $errors = ComposeStrings($errors, $this->eselon3->Errors->ToString());
        $errors = ComposeStrings($errors, $this->eselon4->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Errors->ToString());
        $errors = ComposeStrings($errors, $this->DataSource->Errors->ToString());
        return $errors;
    }
//End GetErrors Method

} //End m_jabatan Class @2-FCB6E20C

class clsm_jabatanDataSource extends clsDBanjab {  //m_jabatanDataSource Class @2-5EA48050

//DataSource Variables @2-A670764A
    public $Parent = "";
    public $CCSEvents = "";
    public $CCSEventResult;
    public $ErrorBlock;
    public $CmdExecution;

    public $CountSQL;
    public $wp;


    // Datasource fields
    public $id;
    public $kode_rumpun;
    public $kode;
    public $nama;
    public $kualifikasi_pendidikan;
    public $keterangan;
    public $id_jenis_jabatan;
    public $ikhtisar;
    public $eselon1;
    public $eselon2;
    public $eselon3;
    public $eselon4;
//End DataSource Variables

//DataSourceClass_Initialize Event @2-6376D5C0
    function clsm_jabatanDataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Grid m_jabatan";
        $this->Initialize();
        $this->id = new clsField("id", ccsInteger, "");
        
        $this->kode_rumpun = new clsField("kode_rumpun", ccsText, "");
        
        $this->kode = new clsField("kode", ccsText, "");
        
        $this->nama = new clsField("nama", ccsText, "");
        
        $this->kualifikasi_pendidikan = new clsField("kualifikasi_pendidikan", ccsText, "");
        
        $this->keterangan = new clsField("keterangan", ccsText, "");
        
        $this->id_jenis_jabatan = new clsField("id_jenis_jabatan", ccsInteger, "");
        
        $this->ikhtisar = new clsField("ikhtisar", ccsText, "");
        
        $this->eselon1 = new clsField("eselon1", ccsText, "");
        
        $this->eselon2 = new clsField("eselon2", ccsText, "");
        
        $this->eselon3 = new clsField("eselon3", ccsText, "");
        
        $this->eselon4 = new clsField("eselon4", ccsText, "");
        

    }
//End DataSourceClass_Initialize Event

//SetOrder Method @2-9E1383D1
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            "");
    }
//End SetOrder Method

//Prepare Method @2-5BF85BDB
    function Prepare()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlkeyword", ccsText, "", "", $this->Parameters["urlkeyword"], "", true);
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "nama", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsText),true);
        $this->Where = 
             $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @2-DB7177A7
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->CountSQL = "SELECT COUNT(*)\n\n" .
        "FROM m_jabatan";
        $this->SQL = "SELECT * \n\n" .
        "FROM m_jabatan {SQL_Where} {SQL_OrderBy}";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect", $this->Parent);
        if ($this->CountSQL) 
            $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        else
            $this->RecordsCount = "CCS not counted";
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect", $this->Parent);
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @2-E8D280C6
    function SetValues()
    {
        $this->id->SetDBValue(trim($this->f("id")));
        $this->kode_rumpun->SetDBValue($this->f("kode_rumpun"));
        $this->kode->SetDBValue($this->f("kode"));
        $this->nama->SetDBValue($this->f("nama"));
        $this->kualifikasi_pendidikan->SetDBValue($this->f("kualifikasi_pendidikan"));
        $this->keterangan->SetDBValue($this->f("keterangan"));
        $this->id_jenis_jabatan->SetDBValue(trim($this->f("id_jenis_jabatan")));
        $this->ikhtisar->SetDBValue($this->f("ikhtisar"));
        $this->eselon1->SetDBValue($this->f("eselon1"));
        $this->eselon2->SetDBValue($this->f("eselon2"));
        $this->eselon3->SetDBValue($this->f("eselon3"));
        $this->eselon4->SetDBValue($this->f("eselon4"));
    }
//End SetValues Method

} //End m_jabatanDataSource Class @2-FCB6E20C

//Initialize Page @1-3DB860D4
// Variables
$FileName = "";
$Redirect = "";
$Tpl = "";
$TemplateFileName = "";
$BlockToParse = "";
$ComponentName = "";
$Attributes = "";

// Events;
$CCSEvents = "";
$CCSEventResult = "";
$TemplateSource = "";

$FileName = FileName;
$Redirect = "";
$TemplateFileName = "jabatan_analisa_detail2_nama_jabatan_JAutoFill1.html";
$BlockToParse = "main";
$TemplateEncoding = "UTF-8";
$ContentType = "text/html";
$PathToRoot = "../";
$PathToRootOpt = "../";
$Scripts = "|";
//End Initialize Page

//Include events file @1-20F4406E
include_once("./jabatan_analisa_detail2_nama_jabatan_JAutoFill1_events.php");
//End Include events file

//Before Initialize @1-E870CEBC
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeInitialize", $MainPage);
//End Before Initialize

//Initialize Objects @1-93311F27
$DBanjab = new clsDBanjab();
$MainPage->Connections["anjab"] = & $DBanjab;
$Attributes = new clsAttributes("page:");
$Attributes->SetValue("pathToRoot", $PathToRoot);
$MainPage->Attributes = & $Attributes;

// Controls
$m_jabatan = new clsGridm_jabatan("", $MainPage);
$MainPage->m_jabatan = & $m_jabatan;
$m_jabatan->Initialize();
$ScriptIncludes = "";
$SList = explode("|", $Scripts);
foreach ($SList as $Script) {
    if ($Script != "") $ScriptIncludes = $ScriptIncludes . "<script src=\"" . $PathToRoot . $Script . "\" type=\"text/javascript\"></script>\n";
}
$Attributes->SetValue("scriptIncludes", $ScriptIncludes);

BindEvents();

$CCSEventResult = CCGetEvent($CCSEvents, "AfterInitialize", $MainPage);

if ($Charset) {
    header("Content-Type: " . $ContentType . "; charset=" . $Charset);
} else {
    header("Content-Type: " . $ContentType);
}
//End Initialize Objects

//Initialize HTML Template @1-6AE7B07D
$CCSEventResult = CCGetEvent($CCSEvents, "OnInitializeView", $MainPage);
$Tpl = new clsTemplate($FileEncoding, $TemplateEncoding);
if (strlen($TemplateSource)) {
    $Tpl->LoadTemplateFromStr($TemplateSource, $BlockToParse, "UTF-8");
} else {
    $Tpl->LoadTemplate(PathToCurrentPage . $TemplateFileName, $BlockToParse, "UTF-8");
}
$Tpl->SetVar("CCS_PathToRoot", $PathToRoot);
$Tpl->block_path = "/$BlockToParse";
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeShow", $MainPage);
$Attributes->SetValue("pathToRoot", "../");
$Attributes->Show();
//End Initialize HTML Template

//Go to destination page @1-C8D68879
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
    $DBanjab->close();
    header("Location: " . $Redirect);
    unset($m_jabatan);
    unset($Tpl);
    exit;
}
//End Go to destination page

//Show Page @1-4F17279A
$m_jabatan->Show();
$Tpl->block_path = "";
$Tpl->Parse($BlockToParse, false);
if (!isset($main_block)) $main_block = $Tpl->GetVar($BlockToParse);
$main_block = CCConvertEncoding($main_block, $FileEncoding, $TemplateEncoding);
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeOutput", $MainPage);
if ($CCSEventResult) echo $main_block;
//End Show Page

//Unload Page @1-2B7A237E
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
$DBanjab->close();
unset($m_jabatan);
unset($Tpl);
//End Unload Page


?>
