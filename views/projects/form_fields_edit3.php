<? 
include(CONFIG."project_dates.php");

require_once(VIEWS."projects/date_fields.php"); 
include(TEMPLATES."widgets/datepicker.html");

echo date_fields("intake", $date_intake, false, true);
echo date_fields("cao_memo", $date_cao_memo);
echo date_fields("handover", $date_handover);
echo date_fields("design_start", $date_design_start);
echo date_fields("construct_start", $date_construct_start);
echo date_fields("completion_orig", $date_completion_orig);
echo date_fields("completion_revised", $date_completion_revised);

?>
