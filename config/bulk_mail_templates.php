<?
$bulk_mail_templates = array(
  "statuses_need_update" => array(
    "subject" => "Update Project Statuses",
    "body" => "Dear $pm_name,

Please update your project statuses by this Monday, $mondays_date by 5pm.

$link_to_projects_needing_update

Thank you,
GSA Administration
"), // $bulk_mail_templates["statuses_need_update"]
  "statuses_need_update2" => array(
    "subject" => "Update Project Statuses",
    "body" => "Dear $pm_name,

If you haven’t done so already, please update your project statues by 5pm today.

$link_to_projects_needing_update

Thank you,
GSA Administration
"), // $bulk_mail_templates["statuses_need_update2"]
  "statuses_need_approval" => array(
    "subject" => "Approve/Update Projects",
    "body" => "Dear $approver_name,

Your projects are awaiting approval and need to be complete by this Thursday, $next_thursday at 5pm.

$link_to_pending_statuses

Thank you,
GSA Administration
"), // $bulk_mail_templates["statuses_need_approval"]
  "statuses_need_approval2" => array(
    "subject" => "Approve/Update Projects",
    "body" => "Dear $approver_name,

Project statuses will be reviewed tomorrow, make sure you've approved all your statuses by 5pm today.

$link_to_pending_statuses

Thank you,
GSA Administration
"), // $bulk_mail_templates["statuses_need_approval2"]
  "admin_update_approve_statuses" => array(
    "subject" => "Approve/Update Projects",
    "body" => "Dear $admin_name,
$need_update_text
$need_approval_text

Thank you,
GSA Administration
"), // $bulk_mail_templates["admin_update_approve_statuses"]

);
?>
