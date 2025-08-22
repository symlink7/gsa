<script type="text/javascript" src="platform/js/forms_submit.js"></script>

    <div id="lmading">
      <div class="spinner">
        <div class="bounce1"></div>
        <div class="bounce2"></div>
        <div class="bounce3"></div>
      </div>
    </div>

    <style type="text/css">
    html,body {
        height: 100%;
        background: #fff;
    }
    </style>
    
    <!-- Parsley -->
    <script type="text/javascript" src="assets/widgets/parsley/parsley.js"></script>

    <div class="center-vertical">
      <div class="center-content row">
        <form action="#" id="reset-pass-form" class="col-md-6 col-sm-9 col-xs-12 col-lg-6 center-margin" method="POST">
        <h3 class="text-center pad25B font-gray text-transform-upr font-size-23">PROJECT STATUS DASHBOARD<span class="opacity-80"></span></h3>
        <div id="reset-pass-form-div" class="content-box bg-default" data-parsley-validate="">
          <div class="content-box-wrapper pad20A">
            <img class="mrg25B center-margin radius-all-100 display-block" src="assets/image-resources/gsa-logo.png" alt="">

		        <div id="reset-pass-error-msg" class="alert alert-danger" style="display:none"></div>
					  <input type="hidden" name="q" value="users/reset_pass2" />
             
            <div class="form-group">
              <div class="input-group">
                <label class="col-sm-3 control-label" id="user_email_req">
                  Email: 
                  <span style="color:red">*</span>
                </label>  
                <div class="col-sm-9">
                  <input type="email" name="user_email" value="<?=$user_email?>" class="form-control" id="user_email" required data-parsley-type="email" placeholder="Email">
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="input-group">
                <label class="col-sm-3 control-label" id="reset_code_req">
                  Reset Code:
                  <span style="color:red">*</span>
                </label>
                <div class="col-sm-9">
                  <input type="text" name="reset_code" value="<?=$reset_code?>" class="form-control" required id="reset_code" placeholder="Reset Code">
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="input-group">
                <label class="col-sm-3 control-label" id="new_password_req">
                  Choose New Password:
                  <span style="color:red">*</span>
                </label>
                <div class="col-sm-9">
                  <input type="password" name="new_password" value="" class="form-control" required id="new_password" placeholder="Choose New Password">
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="input-group">
                <label class="col-sm-3 control-label" id="new_password2_req">
                  Re-type Password:
                  <span style="color:red">*</span>
                </label>
                <div class="col-sm-9">
                  <input type="password" name="new_password2" value="" class="form-control" required id="new_password2" placeholder="Retype Password">
                </div>
              </div>
            </div>
            <div class="form-group">
              <div id="reset-pass-please-wait" style="display:none">Submitting, please wait...</div>
              <button type="button" id="reset-pass-form-submit" name="reset-pass" class="btn btn-block btn-primary button">Reset Password</button>
            </div>
          </div><!-- content box wrapper -->
        </div><!-- reset-pass-form-div -->
        <div id="reset-pass-form-done" style="display:none" 
          class="content-box bg-default">
          <div class="content-box-wrapper pad20A">
            <img class="mrg25B center-margin radius-all-100 display-block" src="assets/image-resources/gsa-logo.png" alt="">

            <h2>Success!</h2>
            <p>You have succesfully reset your password.</p>
            <p>You can now <a href="index.php">LOGIN HERE</a>.</p>
          </div><!-- content-box-wrapper -->
        </div><!-- reset-pass-form-done --> 
        </form>
      </div><!-- .center-content .row -->
    </div><!-- .center-vertical -->
  </div>
