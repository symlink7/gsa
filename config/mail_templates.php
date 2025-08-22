<?
$mail_templates = array(
  "user_account_created" => array(
    "subject" => "Your account for Alameda County GSA's Project Status Dashboard",
    "body" => "Hi ##user_first_name\## ##user_last_name\##, 

Your ##user_role_name\## account has just been created with the GSA project status dashboard.

You can log in at:

##admin_url\##

email: ##user_email\##
password: ##user_password\##

Thank you,
Administrator
GSA Project status dashboard
"),
  "status_updated" => array(
    "subject" => "Project Status Updated",
    "body" => '<p>Dear ##supervisor_name\##,</p>

<p>##sender_name\## has requested your review of the ##status_name\## of \'##project_name\##, Building No. ##project_building\##\'.</p>

##status_color\##

<p>The status explanation:</p>
##status_descr\##

<p><a href="##link_to_project\##">Approve Status</a></p>

<p>Thank you,<br />
Administrator<br />
GSA Project status dashboard</p>
'),

	"reset_password" => array(
    "subject" => "Reset your password for Alameda County GSA's Project Status Dashboard",
    "body" => "Hi ##user_first_name\## ##user_last_name\##, 

We received a request to reset the password for your ##user_role_name\## account with the GSA project status dashboard.

If you believe we received this request by mistake and you still remember your password, please ignore this email.

Otherwise, you can reset your password via the following URL:

##admin_url\##?q=users/reset_pass1/##user_email\##/##reset_code\##

Thank you,
Administrator
GSA Project status dashboard
"),

); // $mail_templates

?>
