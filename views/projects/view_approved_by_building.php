<? 
require_once(VIEWS."projects/widgets.php");

include(CONFIG."projects.php");

include(TEMPLATES."general_wrap.php"); 
include(TEMPLATES."widgets/spinner.html");
include(TEMPLATES."widgets/data_tables.html");
setlocale(LC_MONETARY, 'en_US');
?>

        <div id="page-content-wrapper">
          <div id="page-content">
            <div class="container">
              
              <div id="page-title">
                <h2>GSA Project Status Dashboard</h2>
              </div><!--Page Title-->
                
              <div class="row">
                <div class="col-md-6">
                  <h4 class="pad10B">All Projects for Building #<?=$building_num?></h4>
                </div><!-- col-md-6 -->
              </div><!-- row -->
              
              <div class="example-box-wrapper">
                <div class="content-box-wrapper">
                  <div class="example-box-wrapper">
                    <table id="datatable-responsive" class="table table-striped table-bordered responsive no-wrap" cellspacing="0" width="100%">
                      <thead>
                        <tr>
                          <th>Project Name</th>
                          <th>Project Number</th>
                          <th>District</th>
                          <th>Joint Clients</th>
                          <th>Project Phase</th>
                          <th aria-controls="datatable-tabletools">CBA Budget</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?
                        foreach ($tudo as $project) {
                          extract($project);
                        ?>
                        <tr>
                          <td>
                            <a href="index.php?q=projects/details/<?=$project_id?>"><?=$project_name?></a>
                          </td>
                          <td><?=$project_number?></td>
                          <td><?=($project_district ? 
                              $project_district_options[$project_district] : "")?></td>
                          <td><?=$client_list?></td>
                          <td><?=$project_phase?></td>
                          <td class="sorting_1">
                            <?=format_budget($budget_approved_revised ? 
                              $budget_approved_revised : 
                              $budget_approved_orig, "")?>
                          </td>
                        </tr>
                        <? } /* end of looping through projects */ ?>
                      </tbody>
                    </table>
                  </div><!-- .example-box-wrapper -->
                </div><!-- .content-box-wrapper -->
              </div><!-- .example-box-wrapper -->

            </div><!-- container -->
          </div><!-- page-content -->
        </div><!-- page-content-wrapper -->
      </div><!-- page_wrapper -->
    </div><!-- sb-site -->

