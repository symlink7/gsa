<? 
include(TEMPLATES."general_wrap.php"); 
include(TEMPLATES."widgets/spinner.html");
include(TEMPLATES."widgets/data_tables.html");
?>

        <div id="page-content-wrapper">
          <div id="page-content">
            <div class="container">
              <div id="page-title">
                <h2 class="pad10B"><strong>USERS</strong></h2>
                <h5 class="pad10B">&nbsp;</h5>
              </div><!--Page Title-->

              <div class="panel">
                <div class="panel-body">

                  <h3 class="title-hero">USERS</h3>
                  <div class="example-box-wrapper">
                    <div class="content-box-wrapper">
                      <div class="example-box-wrapper">
                        <table id="datatable-responsive" class="table table-striped table-bordered responsive no-wrap" cellspacing="0" width="100%">
                          <thead>
                            <tr>
                              <th>Name</th>
                              <th>Email</th>
                              <th>Phone</th>
                              <th>Role</th>
                              <th>Project Type</th>
                            </tr>
                          </thead>
                          <tbody>
                            <? 
                            include(CONFIG."users.php");
                            foreach ($tudo as $user) {
                              extract($user);

                              $edit_url = (is_arr_valid($permissions) && 
                                is_arr_valid($permissions["edit"]) &&
                                !in_array($_SESSION["user_info"]["user_role"], 
                                          $permissions["edit"]) 
                                  ? "#" : 
                                  "index.php?q=users/edit_form/$user_id"
                              );  
                            ?>
                            <tr>
                              <td>
                                <a href="<?=$edit_url?>"><?=$user_last_name?>, <?=$user_first_name?></a>
                              </td>
                              <td><a href="mailto:<?=$user_email?>"><?=$user_email?></a></td>
                              <td><?=$user_phone?></td>
                              <td><?=$user_role_options[$user_role]?></td>
                              <td><?=$user_project_type?></td>
                            </tr>
                            <? } /* end of looping through users */ ?>
                          </tbody>
                        </table>
                      </div><!-- .example-box-wrapper -->
                    </div><!-- .content-box-wrapper -->
                  </div><!-- .example-box-wrapper -->

                </div><!-- panel-body-->
              </div><!--panel-->
      
            </div><!-- container -->
          </div><!-- page-content -->
        </div><!-- page-content-wrapper -->
      </div><!-- page_wrapper -->
    </div><!-- sb-site -->
