<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start();
?>
<?php include_once "phprptinc/ewrcfg8.php" ?>
<?php include_once "phprptinc/ewmysql.php" ?>
<?php include_once "phprptinc/ewrfn8.php" ?>
<?php include_once "phprptinc/ewrusrfn8.php" ?>
<?php include_once "Report5smryinfo.php" ?>
<?php

//
// Page class
//

$Report5_summary = NULL; // Initialize page object first

class crReport5_summary extends crReport5 {

	// Page ID
	var $PageID = 'summary';

	// Project ID
	var $ProjectID = "{DBB651B2-15BD-4402-ACB5-53B44A927C69}";

	// Page object name
	var $PageObjName = 'Report5_summary';

	// Page name
	function PageName() {
		return ewr_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ewr_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Export URLs
	var $ExportPrintUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportPdfUrl;
	var $ReportTableClass;
	var $ReportTableStyle = "";

	// Custom export
	var $ExportPrintCustom = FALSE;
	var $ExportExcelCustom = FALSE;
	var $ExportWordCustom = FALSE;
	var $ExportPdfCustom = FALSE;
	var $ExportEmailCustom = FALSE;

	// Message
	function getMessage() {
		return @$_SESSION[EWR_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ewr_AddMessage($_SESSION[EWR_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EWR_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ewr_AddMessage($_SESSION[EWR_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EWR_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ewr_AddMessage($_SESSION[EWR_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EWR_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ewr_AddMessage($_SESSION[EWR_SESSION_WARNING_MESSAGE], $v);
	}

		// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EWR_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EWR_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EWR_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EWR_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog ewDisplayTable\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") // Header exists, display
			echo $sHeader;
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") // Fotoer exists, display
			echo $sFooter;
	}

	// Validate page request
	function IsPageRequest() {
		if ($this->UseTokenInUrl) {
			if (ewr_IsHttpPost())
				return ($this->TableVar == @$_POST("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == @$_GET["t"]);
		} else {
			return TRUE;
		}
	}
	var $Token = "";
	var $CheckToken = EWR_CHECK_TOKEN;
	var $CheckTokenFn = "ewr_CheckToken";
	var $CreateTokenFn = "ewr_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ewr_IsHttpPost())
			return TRUE;
		if (!isset($_POST[EWR_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EWR_TOKEN_NAME]);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $gsToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$gsToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $ReportLanguage;

		// Language object
		$ReportLanguage = new crLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (Report5)
		if (!isset($GLOBALS["Report5"])) {
			$GLOBALS["Report5"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["Report5"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";

		// Page ID
		if (!defined("EWR_PAGE_ID"))
			define("EWR_PAGE_ID", 'summary', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EWR_TABLE_NAME"))
			define("EWR_TABLE_NAME", 'Report5', TRUE);

		// Start timer
		$GLOBALS["gsTimer"] = new crTimer();

		// Open connection
		$conn = ewr_Connect();

		// Export options
		$this->ExportOptions = new crListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Search options
		$this->SearchOptions = new crListOptions();
		$this->SearchOptions->Tag = "div";
		$this->SearchOptions->TagClassName = "ewSearchOption";

		// Filter options
		$this->FilterOptions = new crListOptions();
		$this->FilterOptions->Tag = "div";
		$this->FilterOptions->TagClassName = "ewFilterOption fReport5summary";
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsExportFile, $gsEmailContentType, $ReportLanguage, $Security;
		global $gsCustomExport;

		// Security
		$Security = new crAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin(); // Auto login
		if (!$Security->IsLoggedIn()) {
			$Security->SaveLastUrl();
			$this->Page_Terminate(ewr_GetUrl("rlogin.php"));
		}
		if (!$Security->IsLoggedIn()) {
			$Security->SaveLastUrl();
			$this->Page_Terminate(ewr_GetUrl("rlogin.php"));
		}

		// Get export parameters
		if (@$_GET["export"] <> "")
			$this->Export = strtolower($_GET["export"]);
		elseif (@$_POST["export"] <> "")
			$this->Export = strtolower($_POST["export"]);
		$gsExport = $this->Export; // Get export parameter, used in header
		$gsExportFile = $this->TableVar; // Get export file, used in header
		$gsEmailContentType = @$_POST["contenttype"]; // Get email content type

		// Setup placeholder
		$this->usuario->PlaceHolder = $this->usuario->FldCaption();
		$this->nombre->PlaceHolder = $this->nombre->FldCaption();
		$this->apellido->PlaceHolder = $this->apellido->FldCaption();

		// Setup export options
		$this->SetupExportOptions();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $ReportLanguage->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Create Token
		$this->CreateToken();
	}

	// Set up export options
	function SetupExportOptions() {
		global $ReportLanguage;
		$exportid = session_id();

		// Printer friendly
		$item = &$this->ExportOptions->Add("print");
		$item->Body = "<a title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("PrinterFriendly", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("PrinterFriendly", TRUE)) . "\" href=\"" . $this->ExportPrintUrl . "\">" . $ReportLanguage->Phrase("PrinterFriendly") . "</a>";
		$item->Visible = FALSE;

		// Export to Excel
		$item = &$this->ExportOptions->Add("excel");
		$item->Body = "<a title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToExcel", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToExcel", TRUE)) . "\" href=\"" . $this->ExportExcelUrl . "\">" . $ReportLanguage->Phrase("ExportToExcel") . "</a>";
		$item->Visible = TRUE;

		// Export to Word
		$item = &$this->ExportOptions->Add("word");
		$item->Body = "<a title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToWord", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToWord", TRUE)) . "\" href=\"" . $this->ExportWordUrl . "\">" . $ReportLanguage->Phrase("ExportToWord") . "</a>";

		//$item->Visible = FALSE;
		$item->Visible = FALSE;

		// Export to Pdf
		$item = &$this->ExportOptions->Add("pdf");
		$item->Body = "<a title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToPDF", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToPDF", TRUE)) . "\" href=\"" . $this->ExportPdfUrl . "\">" . $ReportLanguage->Phrase("ExportToPDF") . "</a>";
		$item->Visible = FALSE;

		// Uncomment codes below to show export to Pdf link
//		$item->Visible = TRUE;
		// Export to Email

		$item = &$this->ExportOptions->Add("email");
		$url = $this->PageUrl() . "export=email";
		$item->Body = "<a title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToEmail", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToEmail", TRUE)) . "\" id=\"emf_Report5\" href=\"javascript:void(0);\" onclick=\"ewr_EmailDialogShow({lnk:'emf_Report5',hdr:ewLanguage.Phrase('ExportToEmail'),url:'$url',exportid:'$exportid',el:this});\">" . $ReportLanguage->Phrase("ExportToEmail") . "</a>";
		$item->Visible = FALSE;

		// Drop down button for export
		$this->ExportOptions->UseDropDownButton = FALSE;
		$this->ExportOptions->UseButtonGroup = TRUE;
		$this->ExportOptions->UseImageAndText = $this->ExportOptions->UseDropDownButton;
		$this->ExportOptions->DropDownButtonPhrase = $ReportLanguage->Phrase("ButtonExport");

		// Add group option item
		$item = &$this->ExportOptions->Add($this->ExportOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Filter panel button
		$item = &$this->SearchOptions->Add("searchtoggle");
		$SearchToggleClass = " active";
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $ReportLanguage->Phrase("SearchBtn", TRUE) . "\" data-caption=\"" . $ReportLanguage->Phrase("SearchBtn", TRUE) . "\" data-toggle=\"button\" data-form=\"fReport5summary\">" . $ReportLanguage->Phrase("SearchBtn") . "</button>";
		$item->Visible = TRUE;

		// Reset filter
		$item = &$this->SearchOptions->Add("resetfilter");
		$item->Body = "<button type=\"button\" class=\"btn btn-default\" title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ResetAllFilter", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ResetAllFilter", TRUE)) . "\" onclick=\"location='" . ewr_CurrentPage() . "?cmd=reset'\">" . $ReportLanguage->Phrase("ResetAllFilter") . "</button>";
		$item->Visible = TRUE;

		// Button group for reset filter
		$this->SearchOptions->UseButtonGroup = TRUE;

		// Add group option item
		$item = &$this->SearchOptions->Add($this->SearchOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Filter button
		$item = &$this->FilterOptions->Add("savecurrentfilter");
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fReport5summary\" href=\"#\">" . $ReportLanguage->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fReport5summary\" href=\"#\">" . $ReportLanguage->Phrase("DeleteFilter") . "</a>";
		$item->Visible = TRUE;
		$this->FilterOptions->UseDropDownButton = TRUE;
		$this->FilterOptions->UseButtonGroup = !$this->FilterOptions->UseDropDownButton; // v8
		$this->FilterOptions->DropDownButtonPhrase = $ReportLanguage->Phrase("Filters");

		// Add group option item
		$item = &$this->FilterOptions->Add($this->FilterOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Set up options (extended)
		$this->SetupExportOptionsExt();

		// Hide options for export
		if ($this->Export <> "") {
			$this->ExportOptions->HideAllOptions();
			$this->SearchOptions->HideAllOptions();
			$this->FilterOptions->HideAllOptions();
		}

		// Set up table class
		if ($this->Export == "word" || $this->Export == "excel" || $this->Export == "pdf")
			$this->ReportTableClass = "ewTable";
		else
			$this->ReportTableClass = "table ewTable";
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $conn, $ReportLanguage, $EWR_EXPORT, $gsExportFile;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		if ($this->Export <> "" && array_key_exists($this->Export, $EWR_EXPORT)) {
			$sContent = ob_get_contents();

			// Remove all <div data-tagid="..." id="orig..." class="hide">...</div> (for customviewtag export, except "googlemaps")
			if (preg_match_all('/<div\s+data-tagid=[\'"]([\s\S]*?)[\'"]\s+id=[\'"]orig([\s\S]*?)[\'"]\s+class\s*=\s*[\'"]hide[\'"]>([\s\S]*?)<\/div\s*>/i', $sContent, $divmatches, PREG_SET_ORDER)) {
				foreach ($divmatches as $divmatch) {
					if ($divmatch[1] <> "googlemaps")
						$sContent = str_replace($divmatch[0], '', $sContent);
				}
			}
			$fn = $EWR_EXPORT[$this->Export];
			if ($this->Export == "email") { // Email
				ob_end_clean();
				echo $this->$fn($sContent);
				$conn->Close(); // Close connection
				exit();
			} else {
				$this->$fn($sContent);
			}
		}

		 // Close connection
		$conn->Close();

		// Go to URL if specified
		if ($url <> "") {
			if (!EWR_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}

	// Initialize common variables
	var $ExportOptions; // Export options
	var $SearchOptions; // Search options
	var $FilterOptions; // Filter options

	// Paging variables
	var $RecIndex = 0; // Record index
	var $RecCount = 0; // Record count
	var $StartGrp = 0; // Start group
	var $StopGrp = 0; // Stop group
	var $TotalGrps = 0; // Total groups
	var $GrpCount = 0; // Group count
	var $GrpCounter = array(); // Group counter
	var $DisplayGrps = 10; // Groups per page
	var $GrpRange = 10;
	var $Sort = "";
	var $Filter = "";
	var $PageFirstGroupFilter = "";
	var $UserIDFilter = "";
	var $DrillDown = FALSE;
	var $DrillDownInPanel = FALSE;
	var $DrillDownList = "";

	// Clear field for ext filter
	var $ClearExtFilter = "";
	var $PopupName = "";
	var $PopupValue = "";
	var $FilterApplied;
	var $SearchCommand = FALSE;
	var $ShowHeader;
	var $GrpFldCount = 0;
	var $SubGrpFldCount = 0;
	var $DtlFldCount = 0;
	var $Cnt, $Col, $Val, $Smry, $Mn, $Mx, $GrandCnt, $GrandSmry, $GrandMn, $GrandMx;
	var $TotCount;
	var $GrandSummarySetup = FALSE;
	var $GrpIdx;

	//
	// Page main
	//
	function Page_Main() {
		global $rs;
		global $rsgrp;
		global $Security;
		global $gsFormError;
		global $gbDrillDownInPanel;
		global $ReportBreadcrumb;
		global $ReportLanguage;

		// Aggregate variables
		// 1st dimension = no of groups (level 0 used for grand total)
		// 2nd dimension = no of fields

		$nDtls = 2;
		$nGrps = 5;
		$this->Val = &ewr_InitArray($nDtls, 0);
		$this->Cnt = &ewr_Init2DArray($nGrps, $nDtls, 0);
		$this->Smry = &ewr_Init2DArray($nGrps, $nDtls, 0);
		$this->Mn = &ewr_Init2DArray($nGrps, $nDtls, NULL);
		$this->Mx = &ewr_Init2DArray($nGrps, $nDtls, NULL);
		$this->GrandCnt = &ewr_InitArray($nDtls, 0);
		$this->GrandSmry = &ewr_InitArray($nDtls, 0);
		$this->GrandMn = &ewr_InitArray($nDtls, NULL);
		$this->GrandMx = &ewr_InitArray($nDtls, NULL);

		// Set up array if accumulation required: array(Accum, SkipNullOrZero)
		$this->Col = array(array(FALSE, FALSE), array(FALSE,FALSE));

		// Set up groups per page dynamically
		$this->SetUpDisplayGrps();

		// Set up Breadcrumb
		if ($this->Export == "")
			$this->SetupBreadcrumb();
		$this->rol->SelectionList = "";
		$this->rol->DefaultSelectionList = "";
		$this->rol->ValueList = "";
		$this->Establecimiento->SelectionList = "";
		$this->Establecimiento->DefaultSelectionList = "";
		$this->Establecimiento->ValueList = "";

		// Check if search command
		$this->SearchCommand = (@$_GET["cmd"] == "search");

		// Load default filter values
		$this->LoadDefaultFilters();

		// Load custom filters
		$this->Page_FilterLoad();

		// Set up popup filter
		$this->SetupPopup();

		// Load group db values if necessary
		$this->LoadGroupDbValues();

		// Handle Ajax popup
		$this->ProcessAjaxPopup();

		// Extended filter
		$sExtendedFilter = "";

		// Restore filter list
		$this->RestoreFilterList();

		// Build extended filter
		$sExtendedFilter = $this->GetExtendedFilter();
		ewr_AddFilter($this->Filter, $sExtendedFilter);

		// Build popup filter
		$sPopupFilter = $this->GetPopupFilter();

		//ewr_SetDebugMsg("popup filter: " . $sPopupFilter);
		ewr_AddFilter($this->Filter, $sPopupFilter);

		// Check if filter applied
		$this->FilterApplied = $this->CheckFilter();

		// Call Page Selecting event
		$this->Page_Selecting($this->Filter);
		$this->SearchOptions->GetItem("resetfilter")->Visible = $this->FilterApplied;

		// Get sort
		$this->Sort = $this->GetSort();

		// Get total group count
		$sGrpSort = ewr_UpdateSortFields($this->getSqlOrderByGroup(), $this->Sort, 2); // Get grouping field only
		$sSql = ewr_BuildReportSql($this->getSqlSelectGroup(), $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderByGroup(), $this->Filter, $sGrpSort);
		$this->TotalGrps = $this->GetGrpCnt($sSql);
		if ($this->DisplayGrps <= 0 || $this->DrillDown) // Display all groups
			$this->DisplayGrps = $this->TotalGrps;
		$this->StartGrp = 1;

		// Show header
		$this->ShowHeader = TRUE;

		// Set up start position if not export all
		if ($this->ExportAll && $this->Export <> "")
		    $this->DisplayGrps = $this->TotalGrps;
		else
			$this->SetUpStartGroup(); 

		// Set no record found message
		if ($this->TotalGrps == 0) {
				if ($this->Filter == "0=101") {
					$this->setWarningMessage($ReportLanguage->Phrase("EnterSearchCriteria"));
				} else {
					$this->setWarningMessage($ReportLanguage->Phrase("NoRecord"));
				}
		}

		// Hide export options if export
		if ($this->Export <> "")
			$this->ExportOptions->HideAllOptions();

		// Hide search/filter options if export/drilldown
		if ($this->Export <> "" || $this->DrillDown) {
			$this->SearchOptions->HideAllOptions();
			$this->FilterOptions->HideAllOptions();
		}

		// Get current page groups
		$rsgrp = $this->GetGrpRs($sSql, $this->StartGrp, $this->DisplayGrps);

		// Init detail recordset
		$rs = NULL;
		$this->SetupFieldCount();
	}

	// Check level break
	function ChkLvlBreak($lvl) {
		switch ($lvl) {
			case 1:
				return (is_null($this->usuario->CurrentValue) && !is_null($this->usuario->OldValue)) ||
					(!is_null($this->usuario->CurrentValue) && is_null($this->usuario->OldValue)) ||
					($this->usuario->GroupValue() <> $this->usuario->GroupOldValue());
			case 2:
				return (is_null($this->nombre->CurrentValue) && !is_null($this->nombre->OldValue)) ||
					(!is_null($this->nombre->CurrentValue) && is_null($this->nombre->OldValue)) ||
					($this->nombre->GroupValue() <> $this->nombre->GroupOldValue()) || $this->ChkLvlBreak(1); // Recurse upper level
			case 3:
				return (is_null($this->apellido->CurrentValue) && !is_null($this->apellido->OldValue)) ||
					(!is_null($this->apellido->CurrentValue) && is_null($this->apellido->OldValue)) ||
					($this->apellido->GroupValue() <> $this->apellido->GroupOldValue()) || $this->ChkLvlBreak(2); // Recurse upper level
			case 4:
				return (is_null($this->rol->CurrentValue) && !is_null($this->rol->OldValue)) ||
					(!is_null($this->rol->CurrentValue) && is_null($this->rol->OldValue)) ||
					($this->rol->GroupValue() <> $this->rol->GroupOldValue()) || $this->ChkLvlBreak(3); // Recurse upper level
		}
	}

	// Accummulate summary
	function AccumulateSummary() {
		$cntx = count($this->Smry);
		for ($ix = 0; $ix < $cntx; $ix++) {
			$cnty = count($this->Smry[$ix]);
			for ($iy = 1; $iy < $cnty; $iy++) {
				if ($this->Col[$iy][0]) { // Accumulate required
					$valwrk = $this->Val[$iy];
					if (is_null($valwrk)) {
						if (!$this->Col[$iy][1])
							$this->Cnt[$ix][$iy]++;
					} else {
						$accum = (!$this->Col[$iy][1] || !is_numeric($valwrk) || $valwrk <> 0);
						if ($accum) {
							$this->Cnt[$ix][$iy]++;
							if (is_numeric($valwrk)) {
								$this->Smry[$ix][$iy] += $valwrk;
								if (is_null($this->Mn[$ix][$iy])) {
									$this->Mn[$ix][$iy] = $valwrk;
									$this->Mx[$ix][$iy] = $valwrk;
								} else {
									if ($this->Mn[$ix][$iy] > $valwrk) $this->Mn[$ix][$iy] = $valwrk;
									if ($this->Mx[$ix][$iy] < $valwrk) $this->Mx[$ix][$iy] = $valwrk;
								}
							}
						}
					}
				}
			}
		}
		$cntx = count($this->Smry);
		for ($ix = 0; $ix < $cntx; $ix++) {
			$this->Cnt[$ix][0]++;
		}
	}

	// Reset level summary
	function ResetLevelSummary($lvl) {

		// Clear summary values
		$cntx = count($this->Smry);
		for ($ix = $lvl; $ix < $cntx; $ix++) {
			$cnty = count($this->Smry[$ix]);
			for ($iy = 1; $iy < $cnty; $iy++) {
				$this->Cnt[$ix][$iy] = 0;
				if ($this->Col[$iy][0]) {
					$this->Smry[$ix][$iy] = 0;
					$this->Mn[$ix][$iy] = NULL;
					$this->Mx[$ix][$iy] = NULL;
				}
			}
		}
		$cntx = count($this->Smry);
		for ($ix = $lvl; $ix < $cntx; $ix++) {
			$this->Cnt[$ix][0] = 0;
		}

		// Reset record count
		$this->RecCount = 0;
	}

	// Accummulate grand summary
	function AccumulateGrandSummary() {
		$this->TotCount++;
		$cntgs = count($this->GrandSmry);
		for ($iy = 1; $iy < $cntgs; $iy++) {
			if ($this->Col[$iy][0]) {
				$valwrk = $this->Val[$iy];
				if (is_null($valwrk) || !is_numeric($valwrk)) {
					if (!$this->Col[$iy][1])
						$this->GrandCnt[$iy]++;
				} else {
					if (!$this->Col[$iy][1] || $valwrk <> 0) {
						$this->GrandCnt[$iy]++;
						$this->GrandSmry[$iy] += $valwrk;
						if (is_null($this->GrandMn[$iy])) {
							$this->GrandMn[$iy] = $valwrk;
							$this->GrandMx[$iy] = $valwrk;
						} else {
							if ($this->GrandMn[$iy] > $valwrk) $this->GrandMn[$iy] = $valwrk;
							if ($this->GrandMx[$iy] < $valwrk) $this->GrandMx[$iy] = $valwrk;
						}
					}
				}
			}
		}
	}

	// Get group count
	function GetGrpCnt($sql) {
		global $conn;
		$rsgrpcnt = $conn->Execute($sql);
		$grpcnt = ($rsgrpcnt) ? $rsgrpcnt->RecordCount() : 0;
		if ($rsgrpcnt) $rsgrpcnt->Close();
		return $grpcnt;
	}

	// Get group recordset
	function GetGrpRs($wrksql, $start = -1, $grps = -1) {
		global $conn;
		$conn->raiseErrorFn = $GLOBALS["EWR_ERROR_FN"];
		$rswrk = $conn->SelectLimit($wrksql, $grps, $start - 1);
		$conn->raiseErrorFn = '';
		return $rswrk;
	}

	// Get group row values
	function GetGrpRow($opt) {
		global $rsgrp;
		if (!$rsgrp)
			return;
		if ($opt == 1) { // Get first group

			//$rsgrp->MoveFirst(); // NOTE: no need to move position
			$this->usuario->setDbValue(""); // Init first value
		} else { // Get next group
			$rsgrp->MoveNext();
		}
		if (!$rsgrp->EOF)
			$this->usuario->setDbValue($rsgrp->fields[0]);
		if ($rsgrp->EOF) {
			$this->usuario->setDbValue("");
		}
	}

	// Get row values
	function GetRow($opt) {
		global $rs;
		if (!$rs)
			return;
		if ($opt == 1) { // Get first row

	//		$rs->MoveFirst(); // NOTE: no need to move position
			if ($this->GrpCount == 1) {
				$this->FirstRowData = array();
				$this->FirstRowData['usuario'] = ewr_Conv($rs->fields('usuario'),200);
				$this->FirstRowData['nombre'] = ewr_Conv($rs->fields('nombre'),200);
				$this->FirstRowData['apellido'] = ewr_Conv($rs->fields('apellido'),200);
				$this->FirstRowData['rol'] = ewr_Conv($rs->fields('rol'),200);
				$this->FirstRowData['Establecimiento'] = ewr_Conv($rs->fields('Establecimiento'),200);
			}
		} else { // Get next row
			$rs->MoveNext();
		}
		if (!$rs->EOF) {
			if ($opt <> 1) {
				if (is_array($this->usuario->GroupDbValues))
					$this->usuario->setDbValue(@$this->usuario->GroupDbValues[$rs->fields('usuario')]);
				else
					$this->usuario->setDbValue(ewr_GroupValue($this->usuario, $rs->fields('usuario')));
			}
			$this->nombre->setDbValue($rs->fields('nombre'));
			$this->apellido->setDbValue($rs->fields('apellido'));
			$this->rol->setDbValue($rs->fields('rol'));
			$this->Establecimiento->setDbValue($rs->fields('Establecimiento'));
			$this->Val[1] = $this->Establecimiento->CurrentValue;
		} else {
			$this->usuario->setDbValue("");
			$this->nombre->setDbValue("");
			$this->apellido->setDbValue("");
			$this->rol->setDbValue("");
			$this->Establecimiento->setDbValue("");
		}
	}

	//  Set up starting group
	function SetUpStartGroup() {

		// Exit if no groups
		if ($this->DisplayGrps == 0)
			return;

		// Check for a 'start' parameter
		if (@$_GET[EWR_TABLE_START_GROUP] != "") {
			$this->StartGrp = $_GET[EWR_TABLE_START_GROUP];
			$this->setStartGroup($this->StartGrp);
		} elseif (@$_GET["pageno"] != "") {
			$nPageNo = $_GET["pageno"];
			if (is_numeric($nPageNo)) {
				$this->StartGrp = ($nPageNo-1)*$this->DisplayGrps+1;
				if ($this->StartGrp <= 0) {
					$this->StartGrp = 1;
				} elseif ($this->StartGrp >= intval(($this->TotalGrps-1)/$this->DisplayGrps)*$this->DisplayGrps+1) {
					$this->StartGrp = intval(($this->TotalGrps-1)/$this->DisplayGrps)*$this->DisplayGrps+1;
				}
				$this->setStartGroup($this->StartGrp);
			} else {
				$this->StartGrp = $this->getStartGroup();
			}
		} else {
			$this->StartGrp = $this->getStartGroup();
		}

		// Check if correct start group counter
		if (!is_numeric($this->StartGrp) || $this->StartGrp == "") { // Avoid invalid start group counter
			$this->StartGrp = 1; // Reset start group counter
			$this->setStartGroup($this->StartGrp);
		} elseif (intval($this->StartGrp) > intval($this->TotalGrps)) { // Avoid starting group > total groups
			$this->StartGrp = intval(($this->TotalGrps-1)/$this->DisplayGrps) * $this->DisplayGrps + 1; // Point to last page first group
			$this->setStartGroup($this->StartGrp);
		} elseif (($this->StartGrp-1) % $this->DisplayGrps <> 0) {
			$this->StartGrp = intval(($this->StartGrp-1)/$this->DisplayGrps) * $this->DisplayGrps + 1; // Point to page boundary
			$this->setStartGroup($this->StartGrp);
		}
	}

	// Load group db values if necessary
	function LoadGroupDbValues() {
		global $conn;
	}

	// Process Ajax popup
	function ProcessAjaxPopup() {
		global $conn, $ReportLanguage;
		$fld = NULL;
		if (@$_GET["popup"] <> "") {
			$popupname = $_GET["popup"];

			// Check popup name
			// Build distinct values for rol

			if ($popupname == 'Report5_rol') {
				$bNullValue = FALSE;
				$bEmptyValue = FALSE;
				$sFilter = $this->Filter;
				$sSql = ewr_BuildReportSql($this->rol->SqlSelect, $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->rol->SqlOrderBy, $sFilter, "");
				$rswrk = $conn->Execute($sSql);
				while ($rswrk && !$rswrk->EOF) {
					$this->rol->setDbValue($rswrk->fields[0]);
					if (is_null($this->rol->CurrentValue)) {
						$bNullValue = TRUE;
					} elseif ($this->rol->CurrentValue == "") {
						$bEmptyValue = TRUE;
					} else {
						$this->rol->GroupViewValue = ewr_DisplayGroupValue($this->rol,$this->rol->GroupValue());
						ewr_SetupDistinctValues($this->rol->ValueList, $this->rol->GroupValue(), $this->rol->GroupViewValue, FALSE);
					}
					$rswrk->MoveNext();
				}
				if ($rswrk)
					$rswrk->Close();
				if ($bEmptyValue)
					ewr_SetupDistinctValues($this->rol->ValueList, EWR_EMPTY_VALUE, $ReportLanguage->Phrase("EmptyLabel"), FALSE);
				if ($bNullValue)
					ewr_SetupDistinctValues($this->rol->ValueList, EWR_NULL_VALUE, $ReportLanguage->Phrase("NullLabel"), FALSE);
				$fld = &$this->rol;
			}

			// Build distinct values for Establecimiento
			if ($popupname == 'Report5_Establecimiento') {
				$bNullValue = FALSE;
				$bEmptyValue = FALSE;
				$sFilter = $this->Filter;
				$sSql = ewr_BuildReportSql($this->Establecimiento->SqlSelect, $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->Establecimiento->SqlOrderBy, $sFilter, "");
				$rswrk = $conn->Execute($sSql);
				while ($rswrk && !$rswrk->EOF) {
					$this->Establecimiento->setDbValue($rswrk->fields[0]);
					if (is_null($this->Establecimiento->CurrentValue)) {
						$bNullValue = TRUE;
					} elseif ($this->Establecimiento->CurrentValue == "") {
						$bEmptyValue = TRUE;
					} else {
						$this->Establecimiento->ViewValue = $this->Establecimiento->CurrentValue;
						ewr_SetupDistinctValues($this->Establecimiento->ValueList, $this->Establecimiento->CurrentValue, $this->Establecimiento->ViewValue, FALSE, $this->Establecimiento->FldDelimiter);
					}
					$rswrk->MoveNext();
				}
				if ($rswrk)
					$rswrk->Close();
				if ($bEmptyValue)
					ewr_SetupDistinctValues($this->Establecimiento->ValueList, EWR_EMPTY_VALUE, $ReportLanguage->Phrase("EmptyLabel"), FALSE);
				if ($bNullValue)
					ewr_SetupDistinctValues($this->Establecimiento->ValueList, EWR_NULL_VALUE, $ReportLanguage->Phrase("NullLabel"), FALSE);
				$fld = &$this->Establecimiento;
			}

			// Output data as Json
			if (!is_null($fld)) {
				$jsdb = ewr_GetJsDb($fld, $fld->FldType);
				ob_end_clean();
				echo $jsdb;
				exit();
			}
		}
	}

	// Set up popup
	function SetupPopup() {
		global $conn, $ReportLanguage;
		if ($this->DrillDown)
			return;

		// Process post back form
		if (ewr_IsHttpPost()) {
			$sName = @$_POST["popup"]; // Get popup form name
			if ($sName <> "") {
				$cntValues = (is_array(@$_POST["sel_$sName"])) ? count($_POST["sel_$sName"]) : 0;
				if ($cntValues > 0) {
					$arValues = ewr_StripSlashes($_POST["sel_$sName"]);
					if (trim($arValues[0]) == "") // Select all
						$arValues = EWR_INIT_VALUE;
					$this->PopupName = $sName;
					if (ewr_IsAdvancedFilterValue($arValues) || $arValues == EWR_INIT_VALUE)
						$this->PopupValue = $arValues;
					if (!ewr_MatchedArray($arValues, $_SESSION["sel_$sName"])) {
						if ($this->HasSessionFilterValues($sName))
							$this->ClearExtFilter = $sName; // Clear extended filter for this field
					}
					$_SESSION["sel_$sName"] = $arValues;
					$_SESSION["rf_$sName"] = ewr_StripSlashes(@$_POST["rf_$sName"]);
					$_SESSION["rt_$sName"] = ewr_StripSlashes(@$_POST["rt_$sName"]);
					$this->ResetPager();
				}
			}

		// Get 'reset' command
		} elseif (@$_GET["cmd"] <> "") {
			$sCmd = $_GET["cmd"];
			if (strtolower($sCmd) == "reset") {
				$this->ClearSessionSelection('rol');
				$this->ClearSessionSelection('Establecimiento');
				$this->ResetPager();
			}
		}

		// Load selection criteria to array
		// Get rol selected values

		if (is_array(@$_SESSION["sel_Report5_rol"])) {
			$this->LoadSelectionFromSession('rol');
		} elseif (@$_SESSION["sel_Report5_rol"] == EWR_INIT_VALUE) { // Select all
			$this->rol->SelectionList = "";
		}

		// Get Establecimiento selected values
		if (is_array(@$_SESSION["sel_Report5_Establecimiento"])) {
			$this->LoadSelectionFromSession('Establecimiento');
		} elseif (@$_SESSION["sel_Report5_Establecimiento"] == EWR_INIT_VALUE) { // Select all
			$this->Establecimiento->SelectionList = "";
		}
	}

	// Reset pager
	function ResetPager() {

		// Reset start position (reset command)
		$this->StartGrp = 1;
		$this->setStartGroup($this->StartGrp);
	}

	// Set up number of groups displayed per page
	function SetUpDisplayGrps() {
		$sWrk = @$_GET[EWR_TABLE_GROUP_PER_PAGE];
		if ($sWrk <> "") {
			if (is_numeric($sWrk)) {
				$this->DisplayGrps = intval($sWrk);
			} else {
				if (strtoupper($sWrk) == "ALL") { // Display all groups
					$this->DisplayGrps = -1;
				} else {
					$this->DisplayGrps = 10; // Non-numeric, load default
				}
			}
			$this->setGroupPerPage($this->DisplayGrps); // Save to session

			// Reset start position (reset command)
			$this->StartGrp = 1;
			$this->setStartGroup($this->StartGrp);
		} else {
			if ($this->getGroupPerPage() <> "") {
				$this->DisplayGrps = $this->getGroupPerPage(); // Restore from session
			} else {
				$this->DisplayGrps = 10; // Load default
			}
		}
	}

	// Render row
	function RenderRow() {
		global $conn, $rs, $Security, $ReportLanguage;
		if ($this->RowTotalType == EWR_ROWTOTAL_GRAND && !$this->GrandSummarySetup) { // Grand total
			$bGotCount = FALSE;
			$bGotSummary = FALSE;

			// Get total count from sql directly
			$sSql = ewr_BuildReportSql($this->getSqlSelectCount(), $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), "", $this->Filter, "");
			$rstot = $conn->Execute($sSql);
			if ($rstot) {
				$this->TotCount = ($rstot->RecordCount()>1) ? $rstot->RecordCount() : $rstot->fields[0];
				$rstot->Close();
				$bGotCount = TRUE;
			} else {
				$this->TotCount = 0;
			}
		$bGotSummary = TRUE;

			// Accumulate grand summary from detail records
			if (!$bGotCount || !$bGotSummary) {
				$sSql = ewr_BuildReportSql($this->getSqlSelect(), $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), "", $this->Filter, "");
				$rs = $conn->Execute($sSql);
				if ($rs) {
					$this->GetRow(1);
					while (!$rs->EOF) {
						$this->AccumulateGrandSummary();
						$this->GetRow(2);
					}
					$rs->Close();
				}
			}
			$this->GrandSummarySetup = TRUE; // No need to set up again
		}

		// Call Row_Rendering event
		$this->Row_Rendering();

		//
		// Render view codes
		//

		if ($this->RowType == EWR_ROWTYPE_TOTAL) { // Summary row

			// usuario
			$this->usuario->GroupViewValue = $this->usuario->GroupOldValue();
			$this->usuario->CellAttrs["class"] = ($this->RowGroupLevel == 1) ? "ewRptGrpSummary1" : "ewRptGrpField1";
			$this->usuario->GroupViewValue = ewr_DisplayGroupValue($this->usuario, $this->usuario->GroupViewValue);
			$this->usuario->GroupSummaryOldValue = $this->usuario->GroupSummaryValue;
			$this->usuario->GroupSummaryValue = $this->usuario->GroupViewValue;
			$this->usuario->GroupSummaryViewValue = ($this->usuario->GroupSummaryOldValue <> $this->usuario->GroupSummaryValue) ? $this->usuario->GroupSummaryValue : "&nbsp;";

			// nombre
			$this->nombre->GroupViewValue = $this->nombre->GroupOldValue();
			$this->nombre->CellAttrs["class"] = ($this->RowGroupLevel == 2) ? "ewRptGrpSummary2" : "ewRptGrpField2";
			$this->nombre->GroupViewValue = ewr_DisplayGroupValue($this->nombre, $this->nombre->GroupViewValue);
			$this->nombre->GroupSummaryOldValue = $this->nombre->GroupSummaryValue;
			$this->nombre->GroupSummaryValue = $this->nombre->GroupViewValue;
			$this->nombre->GroupSummaryViewValue = ($this->nombre->GroupSummaryOldValue <> $this->nombre->GroupSummaryValue) ? $this->nombre->GroupSummaryValue : "&nbsp;";

			// apellido
			$this->apellido->GroupViewValue = $this->apellido->GroupOldValue();
			$this->apellido->CellAttrs["class"] = ($this->RowGroupLevel == 3) ? "ewRptGrpSummary3" : "ewRptGrpField3";
			$this->apellido->GroupViewValue = ewr_DisplayGroupValue($this->apellido, $this->apellido->GroupViewValue);
			$this->apellido->GroupSummaryOldValue = $this->apellido->GroupSummaryValue;
			$this->apellido->GroupSummaryValue = $this->apellido->GroupViewValue;
			$this->apellido->GroupSummaryViewValue = ($this->apellido->GroupSummaryOldValue <> $this->apellido->GroupSummaryValue) ? $this->apellido->GroupSummaryValue : "&nbsp;";

			// rol
			$this->rol->GroupViewValue = $this->rol->GroupOldValue();
			$this->rol->CellAttrs["class"] = ($this->RowGroupLevel == 4) ? "ewRptGrpSummary4" : "ewRptGrpField4";
			$this->rol->GroupViewValue = ewr_DisplayGroupValue($this->rol, $this->rol->GroupViewValue);
			$this->rol->GroupSummaryOldValue = $this->rol->GroupSummaryValue;
			$this->rol->GroupSummaryValue = $this->rol->GroupViewValue;
			$this->rol->GroupSummaryViewValue = ($this->rol->GroupSummaryOldValue <> $this->rol->GroupSummaryValue) ? $this->rol->GroupSummaryValue : "&nbsp;";

			// usuario
			$this->usuario->HrefValue = "";

			// nombre
			$this->nombre->HrefValue = "";

			// apellido
			$this->apellido->HrefValue = "";

			// rol
			$this->rol->HrefValue = "";

			// Establecimiento
			$this->Establecimiento->HrefValue = "";
		} else {

			// usuario
			$this->usuario->GroupViewValue = $this->usuario->GroupValue();
			$this->usuario->CellAttrs["class"] = "ewRptGrpField1";
			$this->usuario->GroupViewValue = ewr_DisplayGroupValue($this->usuario, $this->usuario->GroupViewValue);
			if ($this->usuario->GroupValue() == $this->usuario->GroupOldValue() && !$this->ChkLvlBreak(1))
				$this->usuario->GroupViewValue = "&nbsp;";

			// nombre
			$this->nombre->GroupViewValue = $this->nombre->GroupValue();
			$this->nombre->CellAttrs["class"] = "ewRptGrpField2";
			$this->nombre->GroupViewValue = ewr_DisplayGroupValue($this->nombre, $this->nombre->GroupViewValue);
			if ($this->nombre->GroupValue() == $this->nombre->GroupOldValue() && !$this->ChkLvlBreak(2))
				$this->nombre->GroupViewValue = "&nbsp;";

			// apellido
			$this->apellido->GroupViewValue = $this->apellido->GroupValue();
			$this->apellido->CellAttrs["class"] = "ewRptGrpField3";
			$this->apellido->GroupViewValue = ewr_DisplayGroupValue($this->apellido, $this->apellido->GroupViewValue);
			if ($this->apellido->GroupValue() == $this->apellido->GroupOldValue() && !$this->ChkLvlBreak(3))
				$this->apellido->GroupViewValue = "&nbsp;";

			// rol
			$this->rol->GroupViewValue = $this->rol->GroupValue();
			$this->rol->CellAttrs["class"] = "ewRptGrpField4";
			$this->rol->GroupViewValue = ewr_DisplayGroupValue($this->rol, $this->rol->GroupViewValue);
			if ($this->rol->GroupValue() == $this->rol->GroupOldValue() && !$this->ChkLvlBreak(4))
				$this->rol->GroupViewValue = "&nbsp;";

			// Establecimiento
			$this->Establecimiento->ViewValue = $this->Establecimiento->CurrentValue;
			$this->Establecimiento->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// usuario
			$this->usuario->HrefValue = "";

			// nombre
			$this->nombre->HrefValue = "";

			// apellido
			$this->apellido->HrefValue = "";

			// rol
			$this->rol->HrefValue = "";

			// Establecimiento
			$this->Establecimiento->HrefValue = "";
		}

		// Call Cell_Rendered event
		if ($this->RowType == EWR_ROWTYPE_TOTAL) { // Summary row

			// usuario
			$CurrentValue = $this->usuario->GroupViewValue;
			$ViewValue = &$this->usuario->GroupViewValue;
			$ViewAttrs = &$this->usuario->ViewAttrs;
			$CellAttrs = &$this->usuario->CellAttrs;
			$HrefValue = &$this->usuario->HrefValue;
			$LinkAttrs = &$this->usuario->LinkAttrs;
			$this->Cell_Rendered($this->usuario, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// nombre
			$CurrentValue = $this->nombre->GroupViewValue;
			$ViewValue = &$this->nombre->GroupViewValue;
			$ViewAttrs = &$this->nombre->ViewAttrs;
			$CellAttrs = &$this->nombre->CellAttrs;
			$HrefValue = &$this->nombre->HrefValue;
			$LinkAttrs = &$this->nombre->LinkAttrs;
			$this->Cell_Rendered($this->nombre, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// apellido
			$CurrentValue = $this->apellido->GroupViewValue;
			$ViewValue = &$this->apellido->GroupViewValue;
			$ViewAttrs = &$this->apellido->ViewAttrs;
			$CellAttrs = &$this->apellido->CellAttrs;
			$HrefValue = &$this->apellido->HrefValue;
			$LinkAttrs = &$this->apellido->LinkAttrs;
			$this->Cell_Rendered($this->apellido, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// rol
			$CurrentValue = $this->rol->GroupViewValue;
			$ViewValue = &$this->rol->GroupViewValue;
			$ViewAttrs = &$this->rol->ViewAttrs;
			$CellAttrs = &$this->rol->CellAttrs;
			$HrefValue = &$this->rol->HrefValue;
			$LinkAttrs = &$this->rol->LinkAttrs;
			$this->Cell_Rendered($this->rol, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);
		} else {

			// usuario
			$CurrentValue = $this->usuario->GroupValue();
			$ViewValue = &$this->usuario->GroupViewValue;
			$ViewAttrs = &$this->usuario->ViewAttrs;
			$CellAttrs = &$this->usuario->CellAttrs;
			$HrefValue = &$this->usuario->HrefValue;
			$LinkAttrs = &$this->usuario->LinkAttrs;
			$this->Cell_Rendered($this->usuario, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// nombre
			$CurrentValue = $this->nombre->GroupValue();
			$ViewValue = &$this->nombre->GroupViewValue;
			$ViewAttrs = &$this->nombre->ViewAttrs;
			$CellAttrs = &$this->nombre->CellAttrs;
			$HrefValue = &$this->nombre->HrefValue;
			$LinkAttrs = &$this->nombre->LinkAttrs;
			$this->Cell_Rendered($this->nombre, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// apellido
			$CurrentValue = $this->apellido->GroupValue();
			$ViewValue = &$this->apellido->GroupViewValue;
			$ViewAttrs = &$this->apellido->ViewAttrs;
			$CellAttrs = &$this->apellido->CellAttrs;
			$HrefValue = &$this->apellido->HrefValue;
			$LinkAttrs = &$this->apellido->LinkAttrs;
			$this->Cell_Rendered($this->apellido, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// rol
			$CurrentValue = $this->rol->GroupValue();
			$ViewValue = &$this->rol->GroupViewValue;
			$ViewAttrs = &$this->rol->ViewAttrs;
			$CellAttrs = &$this->rol->CellAttrs;
			$HrefValue = &$this->rol->HrefValue;
			$LinkAttrs = &$this->rol->LinkAttrs;
			$this->Cell_Rendered($this->rol, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// Establecimiento
			$CurrentValue = $this->Establecimiento->CurrentValue;
			$ViewValue = &$this->Establecimiento->ViewValue;
			$ViewAttrs = &$this->Establecimiento->ViewAttrs;
			$CellAttrs = &$this->Establecimiento->CellAttrs;
			$HrefValue = &$this->Establecimiento->HrefValue;
			$LinkAttrs = &$this->Establecimiento->LinkAttrs;
			$this->Cell_Rendered($this->Establecimiento, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);
		}

		// Call Row_Rendered event
		$this->Row_Rendered();
		$this->SetupFieldCount();
	}

	// Setup field count
	function SetupFieldCount() {
		$this->GrpFldCount = 0;
		$this->SubGrpFldCount = 0;
		$this->DtlFldCount = 0;
		if ($this->usuario->Visible) $this->GrpFldCount += 1;
		if ($this->nombre->Visible) { $this->GrpFldCount += 1; $this->SubGrpFldCount += 1; }
		if ($this->apellido->Visible) { $this->GrpFldCount += 1; $this->SubGrpFldCount += 1; }
		if ($this->rol->Visible) { $this->GrpFldCount += 1; $this->SubGrpFldCount += 1; }
		if ($this->Establecimiento->Visible) $this->DtlFldCount += 1;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $ReportBreadcrumb;
		$ReportBreadcrumb = new crBreadcrumb();
		$url = substr(ewr_CurrentUrl(), strrpos(ewr_CurrentUrl(), "/")+1);
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$ReportBreadcrumb->Add("summary", $this->TableVar, $url, "", $this->TableVar, TRUE);
	}

	function SetupExportOptionsExt() {
		global $ReportLanguage;
		$item =& $this->ExportOptions->GetItem("pdf");
		$item->Visible = TRUE;
		$exportid = session_id();
		$url = $this->ExportPdfUrl;
		$item->Body = "<a title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToPDF", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToPDF", TRUE)) . "\" href=\"javascript:void(0);\" onclick=\"ewr_ExportCharts(this, '" . $url . "', '" . $exportid . "');\">" . $ReportLanguage->Phrase("ExportToPDF") . "</a>";
	}

	// Return extended filter
	function GetExtendedFilter() {
		global $gsFormError;
		$sFilter = "";
		if ($this->DrillDown)
			return "";
		$bPostBack = ewr_IsHttpPost();
		$bRestoreSession = TRUE;
		$bSetupFilter = FALSE;

		// Reset extended filter if filter changed
		if ($bPostBack) {

		// Reset search command
		} elseif (@$_GET["cmd"] == "reset") {

			// Load default values
			$this->SetSessionFilterValues($this->usuario->SearchValue, $this->usuario->SearchOperator, $this->usuario->SearchCondition, $this->usuario->SearchValue2, $this->usuario->SearchOperator2, 'usuario'); // Field usuario
			$this->SetSessionFilterValues($this->nombre->SearchValue, $this->nombre->SearchOperator, $this->nombre->SearchCondition, $this->nombre->SearchValue2, $this->nombre->SearchOperator2, 'nombre'); // Field nombre
			$this->SetSessionFilterValues($this->apellido->SearchValue, $this->apellido->SearchOperator, $this->apellido->SearchCondition, $this->apellido->SearchValue2, $this->apellido->SearchOperator2, 'apellido'); // Field apellido

			//$bSetupFilter = TRUE; // No need to set up, just use default
		} else {
			$bRestoreSession = !$this->SearchCommand;

			// Field usuario
			if ($this->GetFilterValues($this->usuario)) {
				$bSetupFilter = TRUE;
			}

			// Field nombre
			if ($this->GetFilterValues($this->nombre)) {
				$bSetupFilter = TRUE;
			}

			// Field apellido
			if ($this->GetFilterValues($this->apellido)) {
				$bSetupFilter = TRUE;
			}
			if (!$this->ValidateForm()) {
				$this->setFailureMessage($gsFormError);
				return $sFilter;
			}
		}

		// Restore session
		if ($bRestoreSession) {
			$this->GetSessionFilterValues($this->usuario); // Field usuario
			$this->GetSessionFilterValues($this->nombre); // Field nombre
			$this->GetSessionFilterValues($this->apellido); // Field apellido
		}

		// Call page filter validated event
		$this->Page_FilterValidated();

		// Build SQL
		$this->BuildExtendedFilter($this->usuario, $sFilter, FALSE, TRUE); // Field usuario
		$this->BuildExtendedFilter($this->nombre, $sFilter, FALSE, TRUE); // Field nombre
		$this->BuildExtendedFilter($this->apellido, $sFilter, FALSE, TRUE); // Field apellido

		// Save parms to session
		$this->SetSessionFilterValues($this->usuario->SearchValue, $this->usuario->SearchOperator, $this->usuario->SearchCondition, $this->usuario->SearchValue2, $this->usuario->SearchOperator2, 'usuario'); // Field usuario
		$this->SetSessionFilterValues($this->nombre->SearchValue, $this->nombre->SearchOperator, $this->nombre->SearchCondition, $this->nombre->SearchValue2, $this->nombre->SearchOperator2, 'nombre'); // Field nombre
		$this->SetSessionFilterValues($this->apellido->SearchValue, $this->apellido->SearchOperator, $this->apellido->SearchCondition, $this->apellido->SearchValue2, $this->apellido->SearchOperator2, 'apellido'); // Field apellido

		// Setup filter
		if ($bSetupFilter) {
		}
		return $sFilter;
	}

	// Build dropdown filter
	function BuildDropDownFilter(&$fld, &$FilterClause, $FldOpr, $Default = FALSE, $SaveFilter = FALSE) {
		$FldVal = ($Default) ? $fld->DefaultDropDownValue : $fld->DropDownValue;
		$sSql = "";
		if (is_array($FldVal)) {
			foreach ($FldVal as $val) {
				$sWrk = $this->GetDropDownFilter($fld, $val, $FldOpr);

				// Call Page Filtering event
				if (substr($val, 0, 2) <> "@@") $this->Page_Filtering($fld, $sWrk, "dropdown", $FldOpr, $val);
				if ($sWrk <> "") {
					if ($sSql <> "")
						$sSql .= " OR " . $sWrk;
					else
						$sSql = $sWrk;
				}
			}
		} else {
			$sSql = $this->GetDropDownFilter($fld, $FldVal, $FldOpr);

			// Call Page Filtering event
			if (substr($FldVal, 0, 2) <> "@@") $this->Page_Filtering($fld, $sSql, "dropdown", $FldOpr, $FldVal);
		}
		if ($sSql <> "") {
			ewr_AddFilter($FilterClause, $sSql);
			if ($SaveFilter) $fld->CurrentFilter = $sSql;
		}
	}

	function GetDropDownFilter(&$fld, $FldVal, $FldOpr) {
		$FldName = $fld->FldName;
		$FldExpression = $fld->FldExpression;
		$FldDataType = $fld->FldDataType;
		$FldDelimiter = $fld->FldDelimiter;
		$FldVal = strval($FldVal);
		$sWrk = "";
		if ($FldVal == EWR_NULL_VALUE) {
			$sWrk = $FldExpression . " IS NULL";
		} elseif ($FldVal == EWR_NOT_NULL_VALUE) {
			$sWrk = $FldExpression . " IS NOT NULL";
		} elseif ($FldVal == EWR_EMPTY_VALUE) {
			$sWrk = $FldExpression . " = ''";
		} elseif ($FldVal == EWR_ALL_VALUE) {
			$sWrk = "1 = 1";
		} else {
			if (substr($FldVal, 0, 2) == "@@") {
				$sWrk = $this->GetCustomFilter($fld, $FldVal);
			} elseif ($FldDelimiter <> "" && trim($FldVal) <> "") {
				$sWrk = ewr_GetMultiSearchSql($FldExpression, trim($FldVal));
			} else {
				if ($FldVal <> "" && $FldVal <> EWR_INIT_VALUE) {
					if ($FldDataType == EWR_DATATYPE_DATE && $FldOpr <> "") {
						$sWrk = ewr_DateFilterString($FldExpression, $FldOpr, $FldVal, $FldDataType);
					} else {
						$sWrk = ewr_FilterString("=", $FldVal, $FldDataType);
						if ($sWrk <> "") $sWrk = $FldExpression . $sWrk;
					}
				}
			}
		}
		return $sWrk;
	}

	// Get custom filter
	function GetCustomFilter(&$fld, $FldVal) {
		$sWrk = "";
		if (is_array($fld->AdvancedFilters)) {
			foreach ($fld->AdvancedFilters as $filter) {
				if ($filter->ID == $FldVal && $filter->Enabled) {
					$sFld = $fld->FldExpression;
					$sFn = $filter->FunctionName;
					$wrkid = (substr($filter->ID,0,2) == "@@") ? substr($filter->ID,2) : $filter->ID;
					if ($sFn <> "")
						$sWrk = $sFn($sFld);
					else
						$sWrk = "";
					$this->Page_Filtering($fld, $sWrk, "custom", $wrkid);
					break;
				}
			}
		}
		return $sWrk;
	}

	// Build extended filter
	function BuildExtendedFilter(&$fld, &$FilterClause, $Default = FALSE, $SaveFilter = FALSE) {
		$sWrk = ewr_GetExtendedFilter($fld, $Default);
		if (!$Default)
			$this->Page_Filtering($fld, $sWrk, "extended", $fld->SearchOperator, $fld->SearchValue, $fld->SearchCondition, $fld->SearchOperator2, $fld->SearchValue2);
		if ($sWrk <> "") {
			ewr_AddFilter($FilterClause, $sWrk);
			if ($SaveFilter) $fld->CurrentFilter = $sWrk;
		}
	}

	// Get drop down value from querystring
	function GetDropDownValue(&$sv, $parm) {
		if (ewr_IsHttpPost())
			return FALSE; // Skip post back
		if (isset($_GET["sv_$parm"])) {
			$sv = ewr_StripSlashes(@$_GET["sv_$parm"]);
			return TRUE;
		}
		return FALSE;
	}

	// Get filter values from querystring
	function GetFilterValues(&$fld) {
		$parm = substr($fld->FldVar, 2);
		if (ewr_IsHttpPost())
			return; // Skip post back
		$got = FALSE;
		if (isset($_GET["sv_$parm"])) {
			$fld->SearchValue = ewr_StripSlashes(@$_GET["sv_$parm"]);
			$got = TRUE;
		}
		if (isset($_GET["so_$parm"])) {
			$fld->SearchOperator = ewr_StripSlashes(@$_GET["so_$parm"]);
			$got = TRUE;
		}
		if (isset($_GET["sc_$parm"])) {
			$fld->SearchCondition = ewr_StripSlashes(@$_GET["sc_$parm"]);
			$got = TRUE;
		}
		if (isset($_GET["sv2_$parm"])) {
			$fld->SearchValue2 = ewr_StripSlashes(@$_GET["sv2_$parm"]);
			$got = TRUE;
		}
		if (isset($_GET["so2_$parm"])) {
			$fld->SearchOperator2 = ewr_StripSlashes($_GET["so2_$parm"]);
			$got = TRUE;
		}
		return $got;
	}

	// Set default ext filter
	function SetDefaultExtFilter(&$fld, $so1, $sv1, $sc, $so2, $sv2) {
		$fld->DefaultSearchValue = $sv1; // Default ext filter value 1
		$fld->DefaultSearchValue2 = $sv2; // Default ext filter value 2 (if operator 2 is enabled)
		$fld->DefaultSearchOperator = $so1; // Default search operator 1
		$fld->DefaultSearchOperator2 = $so2; // Default search operator 2 (if operator 2 is enabled)
		$fld->DefaultSearchCondition = $sc; // Default search condition (if operator 2 is enabled)
	}

	// Apply default ext filter
	function ApplyDefaultExtFilter(&$fld) {
		$fld->SearchValue = $fld->DefaultSearchValue;
		$fld->SearchValue2 = $fld->DefaultSearchValue2;
		$fld->SearchOperator = $fld->DefaultSearchOperator;
		$fld->SearchOperator2 = $fld->DefaultSearchOperator2;
		$fld->SearchCondition = $fld->DefaultSearchCondition;
	}

	// Check if Text Filter applied
	function TextFilterApplied(&$fld) {
		return (strval($fld->SearchValue) <> strval($fld->DefaultSearchValue) ||
			strval($fld->SearchValue2) <> strval($fld->DefaultSearchValue2) ||
			(strval($fld->SearchValue) <> "" &&
				strval($fld->SearchOperator) <> strval($fld->DefaultSearchOperator)) ||
			(strval($fld->SearchValue2) <> "" &&
				strval($fld->SearchOperator2) <> strval($fld->DefaultSearchOperator2)) ||
			strval($fld->SearchCondition) <> strval($fld->DefaultSearchCondition));
	}

	// Check if Non-Text Filter applied
	function NonTextFilterApplied(&$fld) {
		if (is_array($fld->DropDownValue)) {
			if (is_array($fld->DefaultDropDownValue)) {
				if (count($fld->DefaultDropDownValue) <> count($fld->DropDownValue))
					return TRUE;
				else
					return (count(array_diff($fld->DefaultDropDownValue, $fld->DropDownValue)) <> 0);
			} else {
				return TRUE;
			}
		} else {
			if (is_array($fld->DefaultDropDownValue))
				return TRUE;
			else
				$v1 = strval($fld->DefaultDropDownValue);
			if ($v1 == EWR_INIT_VALUE)
				$v1 = "";
			$v2 = strval($fld->DropDownValue);
			if ($v2 == EWR_INIT_VALUE || $v2 == EWR_ALL_VALUE)
				$v2 = "";
			return ($v1 <> $v2);
		}
	}

	// Get dropdown value from session
	function GetSessionDropDownValue(&$fld) {
		$parm = substr($fld->FldVar, 2);
		$this->GetSessionValue($fld->DropDownValue, 'sv_Report5_' . $parm);
	}

	// Get filter values from session
	function GetSessionFilterValues(&$fld) {
		$parm = substr($fld->FldVar, 2);
		$this->GetSessionValue($fld->SearchValue, 'sv_Report5_' . $parm);
		$this->GetSessionValue($fld->SearchOperator, 'so_Report5_' . $parm);
		$this->GetSessionValue($fld->SearchCondition, 'sc_Report5_' . $parm);
		$this->GetSessionValue($fld->SearchValue2, 'sv2_Report5_' . $parm);
		$this->GetSessionValue($fld->SearchOperator2, 'so2_Report5_' . $parm);
	}

	// Get value from session
	function GetSessionValue(&$sv, $sn) {
		if (array_key_exists($sn, $_SESSION))
			$sv = $_SESSION[$sn];
	}

	// Set dropdown value to session
	function SetSessionDropDownValue($sv, $parm) {
		$_SESSION['sv_Report5_' . $parm] = $sv;
	}

	// Set filter values to session
	function SetSessionFilterValues($sv1, $so1, $sc, $sv2, $so2, $parm) {
		$_SESSION['sv_Report5_' . $parm] = $sv1;
		$_SESSION['so_Report5_' . $parm] = $so1;
		$_SESSION['sc_Report5_' . $parm] = $sc;
		$_SESSION['sv2_Report5_' . $parm] = $sv2;
		$_SESSION['so2_Report5_' . $parm] = $so2;
	}

	// Check if has Session filter values
	function HasSessionFilterValues($parm) {
		return ((@$_SESSION['sv_' . $parm] <> "" && @$_SESSION['sv_' . $parm] <> EWR_INIT_VALUE) ||
			(@$_SESSION['sv_' . $parm] <> "" && @$_SESSION['sv_' . $parm] <> EWR_INIT_VALUE) ||
			(@$_SESSION['sv2_' . $parm] <> "" && @$_SESSION['sv2_' . $parm] <> EWR_INIT_VALUE));
	}

	// Dropdown filter exist
	function DropDownFilterExist(&$fld, $FldOpr) {
		$sWrk = "";
		$this->BuildDropDownFilter($fld, $sWrk, $FldOpr);
		return ($sWrk <> "");
	}

	// Extended filter exist
	function ExtendedFilterExist(&$fld) {
		$sExtWrk = "";
		$this->BuildExtendedFilter($fld, $sExtWrk);
		return ($sExtWrk <> "");
	}

	// Validate form
	function ValidateForm() {
		global $ReportLanguage, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EWR_SERVER_VALIDATE)
			return ($gsFormError == "");

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			$gsFormError .= ($gsFormError <> "") ? "<p>&nbsp;</p>" : "";
			$gsFormError .= $sFormCustomError;
		}
		return $ValidateForm;
	}

	// Clear selection stored in session
	function ClearSessionSelection($parm) {
		$_SESSION["sel_Report5_$parm"] = "";
		$_SESSION["rf_Report5_$parm"] = "";
		$_SESSION["rt_Report5_$parm"] = "";
	}

	// Load selection from session
	function LoadSelectionFromSession($parm) {
		$fld = &$this->fields($parm);
		$fld->SelectionList = @$_SESSION["sel_Report5_$parm"];
		$fld->RangeFrom = @$_SESSION["rf_Report5_$parm"];
		$fld->RangeTo = @$_SESSION["rt_Report5_$parm"];
	}

	// Load default value for filters
	function LoadDefaultFilters() {

		/**
		* Set up default values for non Text filters
		*/

		/**
		* Set up default values for extended filters
		* function SetDefaultExtFilter(&$fld, $so1, $sv1, $sc, $so2, $sv2)
		* Parameters:
		* $fld - Field object
		* $so1 - Default search operator 1
		* $sv1 - Default ext filter value 1
		* $sc - Default search condition (if operator 2 is enabled)
		* $so2 - Default search operator 2 (if operator 2 is enabled)
		* $sv2 - Default ext filter value 2 (if operator 2 is enabled)
		*/

		// Field usuario
		$this->SetDefaultExtFilter($this->usuario, "LIKE", NULL, 'AND', "=", NULL);
		if (!$this->SearchCommand) $this->ApplyDefaultExtFilter($this->usuario);

		// Field nombre
		$this->SetDefaultExtFilter($this->nombre, "LIKE", NULL, 'AND', "=", NULL);
		if (!$this->SearchCommand) $this->ApplyDefaultExtFilter($this->nombre);

		// Field apellido
		$this->SetDefaultExtFilter($this->apellido, "LIKE", NULL, 'AND', "=", NULL);
		if (!$this->SearchCommand) $this->ApplyDefaultExtFilter($this->apellido);

		/**
		* Set up default values for popup filters
		*/

		// Field rol
		// $this->rol->DefaultSelectionList = array("val1", "val2");
		// Field Establecimiento
		// $this->Establecimiento->DefaultSelectionList = array("val1", "val2");

	}

	// Check if filter applied
	function CheckFilter() {

		// Check usuario text filter
		if ($this->TextFilterApplied($this->usuario))
			return TRUE;

		// Check nombre text filter
		if ($this->TextFilterApplied($this->nombre))
			return TRUE;

		// Check apellido text filter
		if ($this->TextFilterApplied($this->apellido))
			return TRUE;

		// Check rol popup filter
		if (!ewr_MatchedArray($this->rol->DefaultSelectionList, $this->rol->SelectionList))
			return TRUE;

		// Check Establecimiento popup filter
		if (!ewr_MatchedArray($this->Establecimiento->DefaultSelectionList, $this->Establecimiento->SelectionList))
			return TRUE;
		return FALSE;
	}

	// Show list of filters
	function ShowFilterList() {
		global $ReportLanguage;

		// Initialize
		$sFilterList = "";

		// Field usuario
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildExtendedFilter($this->usuario, $sExtWrk);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->usuario->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field nombre
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildExtendedFilter($this->nombre, $sExtWrk);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->nombre->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field apellido
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildExtendedFilter($this->apellido, $sExtWrk);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->apellido->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field rol
		$sExtWrk = "";
		$sWrk = "";
		if (is_array($this->rol->SelectionList))
			$sWrk = ewr_JoinArray($this->rol->SelectionList, ", ", EWR_DATATYPE_STRING);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->rol->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field Establecimiento
		$sExtWrk = "";
		$sWrk = "";
		if (is_array($this->Establecimiento->SelectionList))
			$sWrk = ewr_JoinArray($this->Establecimiento->SelectionList, ", ", EWR_DATATYPE_STRING);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->Establecimiento->FldCaption() . "</span>" . $sFilter . "</div>";
		$divstyle = "";
		$divdataclass = "";

		// Show Filters
		if ($sFilterList <> "") {
			$sMessage = "<div class=\"ewDisplayTable\"" . $divstyle . "><div id=\"ewrFilterList\" class=\"alert alert-info\"" . $divdataclass . "><div id=\"ewrCurrentFilters\">" . $ReportLanguage->Phrase("CurrentFilters") . "</div>" . $sFilterList . "</div></div>";
			$this->Message_Showing($sMessage, "");
			echo $sMessage;
		}
	}

	// Get list of filters
	function GetFilterList() {

		// Initialize
		$sFilterList = "";

		// Field usuario
		$sWrk = "";
		if ($this->usuario->SearchValue <> "" || $this->usuario->SearchValue2 <> "") {
			$sWrk = "\"sv_usuario\":\"" . ewr_JsEncode2($this->usuario->SearchValue) . "\"," .
				"\"so_usuario\":\"" . ewr_JsEncode2($this->usuario->SearchOperator) . "\"," .
				"\"sc_usuario\":\"" . ewr_JsEncode2($this->usuario->SearchCondition) . "\"," .
				"\"sv2_usuario\":\"" . ewr_JsEncode2($this->usuario->SearchValue2) . "\"," .
				"\"so2_usuario\":\"" . ewr_JsEncode2($this->usuario->SearchOperator2) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Field nombre
		$sWrk = "";
		if ($this->nombre->SearchValue <> "" || $this->nombre->SearchValue2 <> "") {
			$sWrk = "\"sv_nombre\":\"" . ewr_JsEncode2($this->nombre->SearchValue) . "\"," .
				"\"so_nombre\":\"" . ewr_JsEncode2($this->nombre->SearchOperator) . "\"," .
				"\"sc_nombre\":\"" . ewr_JsEncode2($this->nombre->SearchCondition) . "\"," .
				"\"sv2_nombre\":\"" . ewr_JsEncode2($this->nombre->SearchValue2) . "\"," .
				"\"so2_nombre\":\"" . ewr_JsEncode2($this->nombre->SearchOperator2) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Field apellido
		$sWrk = "";
		if ($this->apellido->SearchValue <> "" || $this->apellido->SearchValue2 <> "") {
			$sWrk = "\"sv_apellido\":\"" . ewr_JsEncode2($this->apellido->SearchValue) . "\"," .
				"\"so_apellido\":\"" . ewr_JsEncode2($this->apellido->SearchOperator) . "\"," .
				"\"sc_apellido\":\"" . ewr_JsEncode2($this->apellido->SearchCondition) . "\"," .
				"\"sv2_apellido\":\"" . ewr_JsEncode2($this->apellido->SearchValue2) . "\"," .
				"\"so2_apellido\":\"" . ewr_JsEncode2($this->apellido->SearchOperator2) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Field rol
		$sWrk = "";
		if ($sWrk == "") {
			$sWrk = ($this->rol->SelectionList <> EWR_INIT_VALUE) ? $this->rol->SelectionList : "";
			if (is_array($sWrk))
				$sWrk = implode("||", $sWrk);
			if ($sWrk <> "")
				$sWrk = "\"sel_rol\":\"" . ewr_JsEncode2($sWrk) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Field Establecimiento
		$sWrk = "";
		if ($sWrk == "") {
			$sWrk = ($this->Establecimiento->SelectionList <> EWR_INIT_VALUE) ? $this->Establecimiento->SelectionList : "";
			if (is_array($sWrk))
				$sWrk = implode("||", $sWrk);
			if ($sWrk <> "")
				$sWrk = "\"sel_Establecimiento\":\"" . ewr_JsEncode2($sWrk) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Return filter list in json
		if ($sFilterList <> "")
			return "{" . $sFilterList . "}";
		else
			return "null";
	}

	// Restore list of filters
	function RestoreFilterList() {

		// Return if not reset filter
		if (@$_POST["cmd"] <> "resetfilter")
			return FALSE;
		$filter = json_decode(ewr_StripSlashes(@$_POST["filter"]), TRUE);

		// Field usuario
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_usuario", $filter) || array_key_exists("so_usuario", $filter) ||
			array_key_exists("sc_usuario", $filter) ||
			array_key_exists("sv2_usuario", $filter) || array_key_exists("so2_usuario", $filter)) {
			$this->SetSessionFilterValues(@$filter["sv_usuario"], @$filter["so_usuario"], @$filter["sc_usuario"], @$filter["sv2_usuario"], @$filter["so2_usuario"], "usuario");
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "usuario");
		}

		// Field nombre
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_nombre", $filter) || array_key_exists("so_nombre", $filter) ||
			array_key_exists("sc_nombre", $filter) ||
			array_key_exists("sv2_nombre", $filter) || array_key_exists("so2_nombre", $filter)) {
			$this->SetSessionFilterValues(@$filter["sv_nombre"], @$filter["so_nombre"], @$filter["sc_nombre"], @$filter["sv2_nombre"], @$filter["so2_nombre"], "nombre");
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "nombre");
		}

		// Field apellido
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_apellido", $filter) || array_key_exists("so_apellido", $filter) ||
			array_key_exists("sc_apellido", $filter) ||
			array_key_exists("sv2_apellido", $filter) || array_key_exists("so2_apellido", $filter)) {
			$this->SetSessionFilterValues(@$filter["sv_apellido"], @$filter["so_apellido"], @$filter["sc_apellido"], @$filter["sv2_apellido"], @$filter["so2_apellido"], "apellido");
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "apellido");
		}

		// Field rol
		$bRestoreFilter = FALSE;
		if (array_key_exists("sel_rol", $filter)) {
			$sWrk = $filter["sel_rol"];
			$sWrk = explode("||", $sWrk);
			$this->rol->SelectionList = $sWrk;
			$_SESSION["sel_Report5_rol"] = $sWrk;
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
		}

		// Field Establecimiento
		$bRestoreFilter = FALSE;
		if (array_key_exists("sel_Establecimiento", $filter)) {
			$sWrk = $filter["sel_Establecimiento"];
			$sWrk = explode("||", $sWrk);
			$this->Establecimiento->SelectionList = $sWrk;
			$_SESSION["sel_Report5_Establecimiento"] = $sWrk;
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
		}
	}

	// Return popup filter
	function GetPopupFilter() {
		$sWrk = "";
		if ($this->DrillDown)
			return "";
			if (is_array($this->rol->SelectionList)) {
				$sFilter = ewr_FilterSQL($this->rol, "`rol`", EWR_DATATYPE_STRING);

				// Call Page Filtering event
				$this->Page_Filtering($this->rol, $sFilter, "popup");
				$this->rol->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
			if (is_array($this->Establecimiento->SelectionList)) {
				$sFilter = ewr_FilterSQL($this->Establecimiento, "`Establecimiento`", EWR_DATATYPE_STRING);

				// Call Page Filtering event
				$this->Page_Filtering($this->Establecimiento, $sFilter, "popup");
				$this->Establecimiento->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
		return $sWrk;
	}

	//-------------------------------------------------------------------------------
	// Function GetSort
	// - Return Sort parameters based on Sort Links clicked
	// - Variables setup: Session[EWR_TABLE_SESSION_ORDER_BY], Session["sort_Table_Field"]
	function GetSort() {
		if ($this->DrillDown)
			return "";

		// Check for a resetsort command
		if (strlen(@$_GET["cmd"]) > 0) {
			$sCmd = @$_GET["cmd"];
			if ($sCmd == "resetsort") {
				$this->setOrderBy("");
				$this->setStartGroup(1);
				$this->usuario->setSort("");
				$this->nombre->setSort("");
				$this->apellido->setSort("");
				$this->rol->setSort("");
				$this->Establecimiento->setSort("");
			}

		// Check for an Order parameter
		} elseif (@$_GET["order"] <> "") {
			$this->CurrentOrder = ewr_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$sSortSql = $this->SortSql();
			$this->setOrderBy($sSortSql);
			$this->setStartGroup(1);
		}
		return $this->getOrderBy();
	}

	// Export to EXCEL
	function ExportExcel($html) {
		global $gsExportFile;
		header('Content-Type: application/vnd.ms-excel' . (EWR_CHARSET <> '' ? ';charset=' . EWR_CHARSET : ''));
		header('Content-Disposition: attachment; filename=' . $gsExportFile . '.xls');
		echo $html;
	}

	// Export PDF
	function ExportPDF($html) {
		global $gsExportFile;
		include_once "dompdf061/dompdf_config.inc.php";
		@ini_set("memory_limit", EWR_PDF_MEMORY_LIMIT);
		set_time_limit(EWR_PDF_TIME_LIMIT);
		$dompdf = new DOMPDF();
		$dompdf->load_html($html);
		ob_end_clean();
		$dompdf->set_paper("a4", "portrait");
		$dompdf->render();
		ewr_DeleteTmpImages($html);
		$dompdf->stream($gsExportFile . ".pdf", array("Attachment" => 1)); // 0 to open in browser, 1 to download

//		exit();
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php ewr_Header(FALSE) ?>
<?php

// Create page object
if (!isset($Report5_summary)) $Report5_summary = new crReport5_summary();
if (isset($Page)) $OldPage = $Page;
$Page = &$Report5_summary;

// Page init
$Page->Page_Init();

// Page main
$Page->Page_Main();

// Global Page Rendering event (in ewrusrfn*.php)
Page_Rendering();

// Page Rendering event
$Page->Page_Render();
?>
<?php include_once "phprptinc/header.php" ?>
<?php if ($Page->Export == "") { ?>
<script type="text/javascript">

// Create page object
var Report5_summary = new ewr_Page("Report5_summary");

// Page properties
Report5_summary.PageID = "summary"; // Page ID
var EWR_PAGE_ID = Report5_summary.PageID;

// Extend page with Chart_Rendering function
Report5_summary.Chart_Rendering = 
 function(chart, chartid) { // DO NOT CHANGE THIS LINE!

 	//alert(chartid);
 }

// Extend page with Chart_Rendered function
Report5_summary.Chart_Rendered = 
 function(chart, chartid) { // DO NOT CHANGE THIS LINE!

 	//alert(chartid);
 }
</script>
<?php } ?>
<?php if ($Page->Export == "" && !$Page->DrillDown) { ?>
<script type="text/javascript">

// Form object
var fReport5summary = new ewr_Form("fReport5summary");

// Validate method
fReport5summary.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);

	// Call Form Custom Validate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate method
fReport5summary.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }
<?php if (EWR_CLIENT_VALIDATE) { ?>
fReport5summary.ValidateRequired = true; // Uses JavaScript validation
<?php } else { ?>
fReport5summary.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Use Ajax
</script>
<?php } ?>
<?php if ($Page->Export == "" && !$Page->DrillDown) { ?>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($Page->Export == "") { ?>
<!-- container (begin) -->
<div id="ewContainer" class="ewContainer">
<!-- top container (begin) -->
<div id="ewTop" class="ewTop">
<a id="top"></a>
<?php } ?>
<!-- top slot -->
<div class="ewToolbar">
<?php if ($Page->Export == "" && (!$Page->DrillDown || !$Page->DrillDownInPanel)) { ?>
<?php if ($ReportBreadcrumb) $ReportBreadcrumb->Render(); ?>
<?php } ?>
<?php
if (!$Page->DrillDownInPanel) {
	$Page->ExportOptions->Render("body");
	$Page->SearchOptions->Render("body");
	$Page->FilterOptions->Render("body");
}
?>
<?php if ($Page->Export == "" && !$Page->DrillDown) { ?>
<?php echo $ReportLanguage->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php $Page->ShowPageHeader(); ?>
<?php $Page->ShowMessage(); ?>
<?php if ($Page->Export == "") { ?>
</div>
<!-- top container (end) -->
	<!-- left container (begin) -->
	<div id="ewLeft" class="ewLeft">
<?php } ?>
	<!-- Left slot -->
<?php if ($Page->Export == "") { ?>
	</div>
	<!-- left container (end) -->
	<!-- center container - report (begin) -->
	<div id="ewCenter" class="ewCenter">
<?php } ?>
	<!-- center slot -->
<!-- summary report starts -->
<?php if ($Page->Export <> "pdf") { ?>
<div id="report_summary">
<?php if ($Page->Export == "" && !$Page->DrillDown) { ?>
<!-- Search form (begin) -->
<form name="fReport5summary" id="fReport5summary" class="form-inline ewForm ewExtFilterForm" action="<?php echo ewr_CurrentPage() ?>">
<?php $SearchPanelClass = ($Page->Filter <> "") ? " in" : " in"; ?>
<div id="fReport5summary_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<div id="r_1" class="ewRow">
<div id="c_usuario" class="ewCell form-group">
	<label for="sv_usuario" class="ewSearchCaption ewLabel"><?php echo $Page->usuario->FldCaption() ?></label>
	<span class="ewSearchOperator"><?php echo $ReportLanguage->Phrase("LIKE"); ?><input type="hidden" name="so_usuario" id="so_usuario" value="LIKE"></span>
	<span class="control-group ewSearchField">
<?php ewr_PrependClass($Page->usuario->EditAttrs["class"], "form-control"); // PR8 ?>
<input type="text" id="sv_usuario" name="sv_usuario" size="30" maxlength="20" placeholder="<?php echo $Page->usuario->PlaceHolder ?>" value="<?php echo ewr_HtmlEncode($Page->usuario->SearchValue) ?>"<?php echo $Page->usuario->EditAttributes() ?>>
</span>
</div>
</div>
<div id="r_2" class="ewRow">
<div id="c_nombre" class="ewCell form-group">
	<label for="sv_nombre" class="ewSearchCaption ewLabel"><?php echo $Page->nombre->FldCaption() ?></label>
	<span class="ewSearchOperator"><?php echo $ReportLanguage->Phrase("LIKE"); ?><input type="hidden" name="so_nombre" id="so_nombre" value="LIKE"></span>
	<span class="control-group ewSearchField">
<?php ewr_PrependClass($Page->nombre->EditAttrs["class"], "form-control"); // PR8 ?>
<input type="text" id="sv_nombre" name="sv_nombre" size="30" maxlength="20" placeholder="<?php echo $Page->nombre->PlaceHolder ?>" value="<?php echo ewr_HtmlEncode($Page->nombre->SearchValue) ?>"<?php echo $Page->nombre->EditAttributes() ?>>
</span>
</div>
</div>
<div id="r_3" class="ewRow">
<div id="c_apellido" class="ewCell form-group">
	<label for="sv_apellido" class="ewSearchCaption ewLabel"><?php echo $Page->apellido->FldCaption() ?></label>
	<span class="ewSearchOperator"><?php echo $ReportLanguage->Phrase("LIKE"); ?><input type="hidden" name="so_apellido" id="so_apellido" value="LIKE"></span>
	<span class="control-group ewSearchField">
<?php ewr_PrependClass($Page->apellido->EditAttrs["class"], "form-control"); // PR8 ?>
<input type="text" id="sv_apellido" name="sv_apellido" size="30" maxlength="20" placeholder="<?php echo $Page->apellido->PlaceHolder ?>" value="<?php echo ewr_HtmlEncode($Page->apellido->SearchValue) ?>"<?php echo $Page->apellido->EditAttributes() ?>>
</span>
</div>
</div>
<div class="ewRow"><input type="submit" name="btnsubmit" id="btnsubmit" class="btn btn-primary" value="<?php echo $ReportLanguage->Phrase("Search") ?>">
<input type="reset" name="btnreset" id="btnreset" class="btn hide" value="<?php echo $ReportLanguage->Phrase("Reset") ?>"></div>
</div>
</form>
<script type="text/javascript">
fReport5summary.Init();
fReport5summary.FilterList = <?php echo $Page->GetFilterList() ?>;
</script>
<!-- Search form (end) -->
<?php } ?>
<?php if ($Page->ShowCurrentFilter) { ?>
<?php $Page->ShowFilterList() ?>
<?php } ?>
<?php } ?>
<?php

// Set the last group to display if not export all
if ($Page->ExportAll && $Page->Export <> "") {
	$Page->StopGrp = $Page->TotalGrps;
} else {
	$Page->StopGrp = $Page->StartGrp + $Page->DisplayGrps - 1;
}

// Stop group <= total number of groups
if (intval($Page->StopGrp) > intval($Page->TotalGrps))
	$Page->StopGrp = $Page->TotalGrps;
$Page->RecCount = 0;
$Page->RecIndex = 0;

// Get first row
if ($Page->TotalGrps > 0) {
	$Page->GetGrpRow(1);
	$Page->GrpCounter[0] = 1;
	$Page->GrpCounter[1] = 1;
	$Page->GrpCounter[2] = 1;
	$Page->GrpCount = 1;
}
$Page->GrpIdx = ewr_InitArray($Page->StopGrp - $Page->StartGrp + 1, -1);
while ($rsgrp && !$rsgrp->EOF && $Page->GrpCount <= $Page->DisplayGrps || $Page->ShowHeader) {

	// Show dummy header for custom template
	// Show header

	if ($Page->ShowHeader) {
?>
<?php if ($Page->GrpCount > 1) { ?>
</tbody>
</table>
<?php if ($Page->Export <> "pdf") { ?>
</div>
<?php } ?>
<?php if ($Page->Export <> "pdf") { ?>
</div>
<?php } ?>
<span data-class="tpb<?php echo $Page->GrpCount-1 ?>_Report5"><?php echo $Page->PageBreakContent ?></span>
<?php } ?>
<?php if ($Page->Export <> "pdf") { ?>
<div class="ewGrid"<?php echo $Page->ReportTableStyle ?>>
<?php } ?>
<?php if ($Page->Export == "" && !($Page->DrillDown && $Page->TotalGrps > 0)) { ?>
<div class="ewGridUpperPanel">
<?php include "Report5smrypager.php" ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<!-- Report grid (begin) -->
<?php if ($Page->Export <> "pdf") { ?>
<div class="<?php if (ewr_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php } ?>
<table class="<?php echo $Page->ReportTableClass ?>">
<thead>
	<!-- Table header -->
	<tr class="ewTableHeader">
<?php if ($Page->usuario->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="usuario"><div class="Report5_usuario"><span class="ewTableHeaderCaption"><?php echo $Page->usuario->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="usuario">
<?php if ($Page->SortUrl($Page->usuario) == "") { ?>
		<div class="ewTableHeaderBtn Report5_usuario">
			<span class="ewTableHeaderCaption"><?php echo $Page->usuario->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer Report5_usuario" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->usuario) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->usuario->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->usuario->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->usuario->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->nombre->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="nombre"><div class="Report5_nombre"><span class="ewTableHeaderCaption"><?php echo $Page->nombre->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="nombre">
<?php if ($Page->SortUrl($Page->nombre) == "") { ?>
		<div class="ewTableHeaderBtn Report5_nombre">
			<span class="ewTableHeaderCaption"><?php echo $Page->nombre->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer Report5_nombre" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->nombre) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->nombre->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->nombre->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->nombre->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->apellido->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="apellido"><div class="Report5_apellido"><span class="ewTableHeaderCaption"><?php echo $Page->apellido->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="apellido">
<?php if ($Page->SortUrl($Page->apellido) == "") { ?>
		<div class="ewTableHeaderBtn Report5_apellido">
			<span class="ewTableHeaderCaption"><?php echo $Page->apellido->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer Report5_apellido" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->apellido) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->apellido->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->apellido->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->apellido->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->rol->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="rol"><div class="Report5_rol"><span class="ewTableHeaderCaption"><?php echo $Page->rol->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="rol">
<?php if ($Page->SortUrl($Page->rol) == "") { ?>
		<div class="ewTableHeaderBtn Report5_rol">
			<span class="ewTableHeaderCaption"><?php echo $Page->rol->FldCaption() ?></span>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, 'Report5_rol', false, '<?php echo $Page->rol->RangeFrom; ?>', '<?php echo $Page->rol->RangeTo; ?>');" id="x_rol<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer Report5_rol" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->rol) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->rol->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->rol->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->rol->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, 'Report5_rol', false, '<?php echo $Page->rol->RangeFrom; ?>', '<?php echo $Page->rol->RangeTo; ?>');" id="x_rol<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->Establecimiento->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="Establecimiento"><div class="Report5_Establecimiento"><span class="ewTableHeaderCaption"><?php echo $Page->Establecimiento->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="Establecimiento">
<?php if ($Page->SortUrl($Page->Establecimiento) == "") { ?>
		<div class="ewTableHeaderBtn Report5_Establecimiento">
			<span class="ewTableHeaderCaption"><?php echo $Page->Establecimiento->FldCaption() ?></span>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, 'Report5_Establecimiento', false, '<?php echo $Page->Establecimiento->RangeFrom; ?>', '<?php echo $Page->Establecimiento->RangeTo; ?>');" id="x_Establecimiento<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer Report5_Establecimiento" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->Establecimiento) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->Establecimiento->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->Establecimiento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->Establecimiento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, 'Report5_Establecimiento', false, '<?php echo $Page->Establecimiento->RangeFrom; ?>', '<?php echo $Page->Establecimiento->RangeTo; ?>');" id="x_Establecimiento<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
	</tr>
</thead>
<tbody>
<?php
		if ($Page->TotalGrps == 0) break; // Show header only
		$Page->ShowHeader = FALSE;
	}

	// Build detail SQL
	$sWhere = ewr_DetailFilterSQL($Page->usuario, $Page->getSqlFirstGroupField(), $Page->usuario->GroupValue());
	if ($Page->PageFirstGroupFilter <> "") $Page->PageFirstGroupFilter .= " OR ";
	$Page->PageFirstGroupFilter .= $sWhere;
	if ($Page->Filter != "")
		$sWhere = "($Page->Filter) AND ($sWhere)";
	$sSql = ewr_BuildReportSql($Page->getSqlSelect(), $Page->getSqlWhere(), $Page->getSqlGroupBy(), $Page->getSqlHaving(), $Page->getSqlOrderBy(), $sWhere, $Page->Sort);
	$rs = $conn->Execute($sSql);
	$rsdtlcnt = ($rs) ? $rs->RecordCount() : 0;
	if ($rsdtlcnt > 0)
		$Page->GetRow(1);
	$Page->GrpIdx[$Page->GrpCount] = array(-1);
	$Page->GrpIdx[$Page->GrpCount][] = array(-1);
	$Page->GrpIdx[$Page->GrpCount][][] = array(-1);
	while ($rs && !$rs->EOF) { // Loop detail records
		$Page->RecCount++;
		$Page->RecIndex++;

		// Render detail row
		$Page->ResetAttrs();
		$Page->RowType = EWR_ROWTYPE_DETAIL;
		$Page->RenderRow();
?>
	<tr<?php echo $Page->RowAttributes(); ?>>
<?php if ($Page->usuario->Visible) { ?>
		<td data-field="usuario"<?php echo $Page->usuario->CellAttributes(); ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_Report5_usuario"<?php echo $Page->usuario->ViewAttributes() ?>><?php echo $Page->usuario->GroupViewValue ?></span></td>
<?php } ?>
<?php if ($Page->nombre->Visible) { ?>
		<td data-field="nombre"<?php echo $Page->nombre->CellAttributes(); ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_Report5_nombre"<?php echo $Page->nombre->ViewAttributes() ?>><?php echo $Page->nombre->GroupViewValue ?></span></td>
<?php } ?>
<?php if ($Page->apellido->Visible) { ?>
		<td data-field="apellido"<?php echo $Page->apellido->CellAttributes(); ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_<?php echo $Page->GrpCounter[1] ?>_Report5_apellido"<?php echo $Page->apellido->ViewAttributes() ?>><?php echo $Page->apellido->GroupViewValue ?></span></td>
<?php } ?>
<?php if ($Page->rol->Visible) { ?>
		<td data-field="rol"<?php echo $Page->rol->CellAttributes(); ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_<?php echo $Page->GrpCounter[1] ?>_<?php echo $Page->GrpCounter[2] ?>_Report5_rol"<?php echo $Page->rol->ViewAttributes() ?>><?php echo $Page->rol->GroupViewValue ?></span></td>
<?php } ?>
<?php if ($Page->Establecimiento->Visible) { ?>
		<td data-field="Establecimiento"<?php echo $Page->Establecimiento->CellAttributes() ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_<?php echo $Page->GrpCounter[1] ?>_<?php echo $Page->GrpCounter[2] ?>_<?php echo $Page->RecCount ?>_Report5_Establecimiento"<?php echo $Page->Establecimiento->ViewAttributes() ?>><?php echo $Page->Establecimiento->ListViewValue() ?></span></td>
<?php } ?>
	</tr>
<?php

		// Accumulate page summary
		$Page->AccumulateSummary();

		// Get next record
		$Page->GetRow(2);

		// Show Footers
?>
<?php
	} // End detail records loop
?>
<?php

	// Next group
	$Page->GetGrpRow(2);

	// Show header if page break
	if ($Page->Export <> "")
		$Page->ShowHeader = ($Page->ExportPageBreakCount == 0) ? FALSE : ($Page->GrpCount % $Page->ExportPageBreakCount == 0);

	// Page_Breaking server event
	if ($Page->ShowHeader)
		$Page->Page_Breaking($Page->ShowHeader, $Page->PageBreakContent);
	$Page->GrpCount++;
	$Page->GrpCounter[2] = 1;
	$Page->GrpCounter[1] = 1;
	$Page->GrpCounter[0] = 1;

	// Handle EOF
	if (!$rsgrp || $rsgrp->EOF)
		$Page->ShowHeader = FALSE;
} // End while
?>
<?php if ($Page->TotalGrps > 0) { ?>
</tbody>
<tfoot>
<?php
	$Page->ResetAttrs();
	$Page->RowType = EWR_ROWTYPE_TOTAL;
	$Page->RowTotalType = EWR_ROWTOTAL_GRAND;
	$Page->RowTotalSubType = EWR_ROWTOTAL_FOOTER;
	$Page->RowAttrs["class"] = "ewRptGrandSummary";
	$Page->RenderRow();
?>
	<tr<?php echo $Page->RowAttributes(); ?>><td colspan="<?php echo ($Page->GrpFldCount + $Page->DtlFldCount) ?>"><?php echo $ReportLanguage->Phrase("RptGrandTotal") ?> <span class="ewDirLtr">(<?php echo ewr_FormatNumber($Page->TotCount,0,-2,-2,-2); ?><?php echo $ReportLanguage->Phrase("RptDtlRec") ?>)</span></td></tr>
	</tfoot>
<?php } elseif (!$Page->ShowHeader && TRUE) { // No header displayed ?>
<?php if ($Page->Export <> "pdf") { ?>
<div class="ewGrid"<?php echo $Page->ReportTableStyle ?>>
<?php } ?>
<?php if ($Page->Export == "" && !($Page->DrillDown && $Page->TotalGrps > 0)) { ?>
<div class="ewGridUpperPanel">
<?php include "Report5smrypager.php" ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<!-- Report grid (begin) -->
<?php if ($Page->Export <> "pdf") { ?>
<div class="<?php if (ewr_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php } ?>
<table class="<?php echo $Page->ReportTableClass ?>">
<?php } ?>
<?php if ($Page->TotalGrps > 0 || TRUE) { // Show footer ?>
</table>
<?php if ($Page->Export <> "pdf") { ?>
</div>
<?php } ?>
<?php if ($Page->Export <> "pdf") { ?>
</div>
<?php } ?>
<?php } ?>
<?php if ($Page->Export <> "pdf") { ?>
</div>
<?php } ?>
<!-- Summary Report Ends -->
<?php if ($Page->Export == "") { ?>
	</div>
	<!-- center container - report (end) -->
	<!-- right container (begin) -->
	<div id="ewRight" class="ewRight">
<?php } ?>
	<!-- Right slot -->
<?php if ($Page->Export == "") { ?>
	</div>
	<!-- right container (end) -->
<div class="clearfix"></div>
<!-- bottom container (begin) -->
<div id="ewBottom" class="ewBottom">
<?php } ?>
	<!-- Bottom slot -->
<?php if ($Page->Export == "") { ?>
	</div>
<!-- Bottom Container (End) -->
</div>
<!-- Table Container (End) -->
<?php } ?>
<?php $Page->ShowPageFooter(); ?>
<?php if (EWR_DEBUG_ENABLED) echo ewr_DebugMsg(); ?>
<?php

// Close recordsets
if ($rsgrp) $rsgrp->Close();
if ($rs) $rs->Close();
?>
<?php if ($Page->Export == "" && !$Page->DrillDown) { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "phprptinc/footer.php" ?>
<?php
$Page->Page_Terminate();
if (isset($OldPage)) $Page = $OldPage;
?>
