
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
        <form action="index.php" id="login-validation" class="col-md-4 col-sm-5 col-xs-11 col-lg-3 center-margin" method="POST">
        <h3 class="text-center pad25B font-gray text-transform-upr font-size-23">PROJECT STATUS DASHBOARD<span class="opacity-80"></span></h3>
        <div id="login-form" class="content-box bg-default" data-parsley-validate="">
          <div class="content-box-wrapper pad20A">
            <img class="mrg25B center-margin radius-all-100 display-block" src="assets/image-resources/gsa-logo.png" alt="">
            
            <? if (is_var_valid($error_msg)) {?>
            <div class="alert alert-danger">
              <p><?=$error_msg?></p>
            </div>
            <? } ?>

            <div class="form-group">
              <div class="input-group">
                <? if (is_var_valid($_SERVER["QUERY_STRING"])) { ?>
                <input type="hidden" name="q" value="<?=htmlspecialchars($_SERVER["QUERY_STRING"])?>" />
                <? } ?>
                <span class="input-group-addon addon-inside bg-gray">
                  <i class="glyph-icon icon-envelope-o"></i>
                </span>
                <input type="email" name="login_email" value="<?=$login_email?>" class="form-control" id="login_email" required data-parsley-type="email" placeholder="Enter email">
              </div>
            </div>
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon addon-inside bg-gray">
                  <i class="glyph-icon icon-unlock-alt"></i>
                </span>
                <input type="password" name="login_password" value="<?=$login_password?>" class="form-control" required id="login_password" placeholder="Password">
              </div>
            </div>
            <div class="form-group">
              <button type="submit" class="btn btn-block btn-primary">Login</button>
            </div>
            
            <div class="row">
              <div class="checkbox-primary col-md-6" style="height: 20px;">
              <!--<label>
                <input type="checkbox" id="loginCheckbox1" class="custom-checkbox">
                Remember me
              </label>-->
            </div>

            <div class="text-right col-md-6">
              <a href="#" class="switch-button" data-toggle="modal" data-target="#modalResetPass" title="Forgot your password?">Forgot your password?</a>
            </div>
          </div>
        </div>
      </div>

      </form>
    </div>
  </div>
<script type="text/javascript" src="platform/js/forms_submit.js"></script>
<? include(VIEWS."users/reset_pass_modal.php"); ?>
