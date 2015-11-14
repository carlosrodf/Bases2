<!-- Begin Main Menu -->
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(31, "mmi_Report1", $ReportLanguage->Phrase("DetailSummaryReportMenuItemPrefix") . $ReportLanguage->MenuPhrase("31", "MenuText") . $ReportLanguage->Phrase("DetailSummaryReportMenuItemSuffix"), "Report1smry.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(33, "mmi_Report0", $ReportLanguage->Phrase("DetailSummaryReportMenuItemPrefix") . $ReportLanguage->MenuPhrase("33", "MenuText") . $ReportLanguage->Phrase("DetailSummaryReportMenuItemSuffix"), "Report0smry.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(16, "mmi_Report2", $ReportLanguage->Phrase("DetailSummaryReportMenuItemPrefix") . $ReportLanguage->MenuPhrase("16", "MenuText") . $ReportLanguage->Phrase("DetailSummaryReportMenuItemSuffix"), "Report2smry.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(17, "mmi_Report3", $ReportLanguage->Phrase("DetailSummaryReportMenuItemPrefix") . $ReportLanguage->MenuPhrase("17", "MenuText") . $ReportLanguage->Phrase("DetailSummaryReportMenuItemSuffix"), "Report3smry.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(22, "mmi_Report4", $ReportLanguage->Phrase("DetailSummaryReportMenuItemPrefix") . $ReportLanguage->MenuPhrase("22", "MenuText") . $ReportLanguage->Phrase("DetailSummaryReportMenuItemSuffix"), "Report4smry.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(19, "mmi_Report5", $ReportLanguage->Phrase("DetailSummaryReportMenuItemPrefix") . $ReportLanguage->MenuPhrase("19", "MenuText") . $ReportLanguage->Phrase("DetailSummaryReportMenuItemSuffix"), "Report5smry.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(28, "mmi_Report6", $ReportLanguage->Phrase("DetailSummaryReportMenuItemPrefix") . $ReportLanguage->MenuPhrase("28", "MenuText") . $ReportLanguage->Phrase("DetailSummaryReportMenuItemSuffix"), "Report6smry.php", -1, "", IsLoggedIn(), FALSE);
if (IsLoggedIn()) {
	$RootMenu->AddMenuItem(-1, "mmi_logout", $ReportLanguage->Phrase("Logout"), "rlogout.php", -1, "", TRUE);
} elseif (substr(ewr_ScriptName(), 0 - strlen("rlogin.php")) <> "rlogin.php") {
	$RootMenu->AddMenuItem(-1, "mmi_login", $ReportLanguage->Phrase("Login"), "rlogin.php", -1, "", TRUE);
}
$RootMenu->Render();
?>
<!-- End Main Menu -->
