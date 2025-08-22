<? 
include(TEMPLATES."general_wrap.php"); 
include(TEMPLATES."widgets/spinner.html");
include(TEMPLATES."widgets/data_tables.html");
?>

        <div id="page-content-wrapper">
          <div id="page-content">
            <div class="container">
              <div id="page-title">
                <h2 class="pad10B"><strong>DEPARTMENTS</strong></h2>
                <h5 class="pad10B">&nbsp;</h5>
              </div><!--Page Title-->

              <div class="panel">
                <div class="panel-body">

                  <h3 class="title-hero">DEPARTMENTS</h3>
                  <div class="example-box-wrapper">
                    <div class="content-box-wrapper">
                      <div class="example-box-wrapper">
                        <table id="datatable-responsive" class="table table-striped table-bordered responsive no-wrap" cellspacing="0" width="100%">
                          <thead>
                            <tr>
                              <th>Department Name</th>
                              <!--<th>Projects</th>-->
                            </tr>
                          </thead>
                          <tbody>
                            <? 
                            include(CONFIG."departments.php");
                            foreach ($tudo as $dep) {
                              extract($dep);
                              $edit_url = (is_arr_valid($permissions) && 
                                is_arr_valid($permissions["edit"]) &&
                                !in_array($_SESSION["user_info"]["user_role"], 
                                  $permissions["edit"])
                                  ? "#" : 
                                  "index.php?q=departments/edit_form/$dep_id" 
                              );
                            ?>
                            <tr>
                              <td>
                                <a href="<?=$edit_url?>"><?=$dep_name?></a>
                              </td>
                              <!--<td>here come projects</td>-->
                            </tr>
                            <? } /* end of looping through departments */ ?>
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
