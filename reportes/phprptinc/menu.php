<!-- Begin Main Menu -->
<div class="ewMenu">
<?php $RootMenu = new crMenu(EWR_MENUBAR_ID); ?>
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(16, "mi_Report2", $ReportLanguage->Phrase("DetailSummaryReportMenuItemPrefix") . $ReportLanguage->MenuPhrase("16", "MenuText") . $ReportLanguage->Phrase("DetailSummaryReportMenuItemSuffix"), "Report2smry.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(17, "mi_Report3", $ReportLanguage->Phrase("DetailSummaryReportMenuItemPrefix") . $ReportLanguage->MenuPhrase("17", "MenuText") . $ReportLanguage->Phrase("DetailSummaryReportMenuItemSuffix"), "Report3smry.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(22, "mi_Report4", $ReportLanguage->Phrase("DetailSummaryReportMenuItemPrefix") . $ReportLanguage->MenuPhrase("22", "MenuText") . $ReportLanguage->Phrase("DetailSummaryReportMenuItemSuffix"), "Report4smry.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(19, "mi_Report5", $ReportLanguage->Phrase("DetailSummaryReportMenuItemPrefix") . $ReportLanguage->MenuPhrase("19", "MenuText") . $ReportLanguage->Phrase("DetailSummaryReportMenuItemSuffix"), "Report5smry.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(28, "mi_Report6", $ReportLanguage->Phrase("DetailSummaryReportMenuItemPrefix") . $ReportLanguage->MenuPhrase("28", "MenuText") . $ReportLanguage->Phrase("DetailSummaryReportMenuItemSuffix"), "Report6smry.php", -1, "", IsLoggedIn(), FALSE);
if (IsLoggedIn()) {
	$RootMenu->AddMenuItem(-1, "mi_logout", $ReportLanguage->Phrase("Logout"), "rlogout.php", -1, "", TRUE);
} elseif (substr(ewr_ScriptName(), 0 - strlen("rlogin.php")) <> "rlogin.php") {
	$RootMenu->AddMenuItem(-1, "mi_login", $ReportLanguage->Phrase("Login"), "rlogin.php", -1, "", TRUE);
}
$RootMenu->Render();
?>
</div>
<!-- End Main Menu -->
