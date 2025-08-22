<? 
include(CONFIG."project_dates.php");

require_once(VIEWS."projects/date_fields.php"); 
include(TEMPLATES."widgets/datepicker.html");
?>
<input type="hidden" name="project_id" value="<?=$project_id?>" />

<?
echo date_fields("intake", "", false, true);
echo date_fields("cao_memo");
echo date_fields("handover");
echo date_fields("design_start");
echo date_fields("construct_start");
echo date_fields("completion_orig");
echo date_fields("completion_revised");
echo status_fields("schedule", "Schedule");

?>
