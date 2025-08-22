<? extract($_SESSION["user_info"]); ?>  
    <div id="sb-site">
      <div id="loading">
        <div class="spinner">
          <div class="bounce1"></div>
          <div class="bounce2"></div>
          <div class="bounce3"></div>
        </div>
      </div>
      
      <div id="page-wrapper">
        <div id="page-header" class="bg-new-navy">
          <div id="mobile-navigation">
            <button id="nav-toggle" class="collapsed" data-toggle="collapse" data-target="#page-sidebar"><span></span></button>
            <a href="index.php" class="logo-content-small" title="GSA"></a>
          </div>
          <div id="header-logo" class="logo-bg">
            <a href="index.php" title="GSA"><strong>ALAMEDA COUNTY GSA</strong><span> PROJECT STATUS DASHBOARD</span></a>
            <a href="index.php" class="logo-content-small" title="GSA">ALAMEDA COUNTY <i>GSA</i><span> PROJECT STATUS DASHBOARD</span></a>
            <? if (TEST_SITE) { ?>
            <br /><h3 class="staging-header">STAGING SITE, using database '<?=DB_NAME?>'</h3>
            <? } ?>
          </div>
     
          <div id="header-nav-right">
            <a href="#" class="hdr-btn" id="fullscreen-btn" title="Fullscreen"> <i class="glyph-icon icon-arrows-alt"></i></a>
          </div><!-- #header-nav-right -->

        </div><!-- #page-header -->
        
        <div id="page-sidebar">
          <div id="header-nav-left">
            <div class="user-account-btn dropdown">
              <div id="user-box">
                <a href="#" title="My Account" class="user-profile clearfix" data-toggle="dropdown"> <span><?=$user_first_name?> <?=$user_last_name?></span> <i class="glyph-icon icon-angle-down"></i></a>
              </div><!-- user-box -->  
              <div class="dropdown-menu float-left">
                <div class="box-sm">
                  <div class="login-box clearfix">
                    <div class="user-info">
                      <span><?=$user_first_name?> <?=$user_last_name?><i><?=$user_role_name?></i></span>
                      <a href="index.php?q=users/edit_form" title="Edit profile">Edit profile</a>
                      <a href="#" title="View notifications">View notifications</a>
                    </div>
                  </div>
                  <div class="divider"></div>
                  <ul class="reset-ul mrg5B">
                    <li>
                      <a href="#"> <i class="glyph-icon float-right icon-caret-right"></i> View account details</a>
                    </li>
                  </ul>
                  <div class="pad5A button-pane button-pane-alt text-center">
                    <a href="index.php?logout=1" class="btn display-block font-normal btn-danger"> <i class="glyph-icon icon-power-off"></i> Logout</a>
                  </div>
                </div><!-- box-sm -->
              </div><!-- dropdown-menu -->
            </div><!-- user-account-btn -->
          </div><!-- #header-nav-left -->
<? 
include(TEMPLATES.
  $_SESSION["user_info"]["user_role"]."_sidebar.php");
?>
