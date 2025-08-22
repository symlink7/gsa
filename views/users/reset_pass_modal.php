<div class="modal fade gsa-modal" id="modalResetPass" tabindex="-1" role="dialog" aria-labelledby="modalResetPassLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Reset Your Password</h4>
      </div><!-- .modal-header-->

      <div class="modal-body">

        <form id="reset-pass-form">
        <input type="hidden" name="q" value="users/reset_pass" />
        <div class="form-group">
        	<div class="input-group">
            <label class="col-sm-3 control-label" id="user_email_req">
              Email: <span style="color:red">*</span>
      			</label>
            <div class="col-sm-6">
              <input type="email" name="user_email" value="" class="form-control" id="user_email" required data-parsley-type="email">
            </div>
          </div>
        </div>
        </form>
				<p>&nbsp;</p>
        <div id="reset-pass-error-msg" class="alert alert-danger" style="display:none">
        </div>
        <!-- these two div's are for compatibility with forms_submit.js -->
        <div id="reset-pass-form-div" style="display:none"></div>
        <div id="page-title" style="display:none"></div>
        <div id="reset-pass-form-done" class="alert alert-success" style="display:none">A password-reset code has been sent to the email above.</div>
      
      </div><!-- .modal-body-->

      <div class="modal-footer clear mrg20T">
        <div id="reset-pass-please-wait" style="display:none">Submitting, please wait...</div>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-info button" name="reset-pass" id="reset-pass-form-submit">RESET PASSWORD</button>
      </div><!-- modal-footer-->
      
    </div><!-- .modal-content-->
  </div><!-- .modal-dialogue-->
</div><!-- .modal-->
