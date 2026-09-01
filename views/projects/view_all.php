<? 
include(TEMPLATES."general_wrap.php"); 
include(TEMPLATES."widgets/spinner.html");
include(TEMPLATES."widgets/data_tables.html");
?>

        <div id="page-content-wrapper">
          <div id="page-content">
            <div class="container">
              <div id="page-title">
                <h2 class="pad10B"><strong>PROJECTS</strong></h2>
                <h5 class="pad10B">&nbsp;</h5>
              </div><!--Page Title-->

              <div class="panel">
                <div class="panel-body">

                  <h3 class="title-hero"><?=$title?></h3>
                  <div class="example-box-wrapper">
                    <div class="content-box-wrapper">
                      <div class="example-box-wrapper">
                        <table id="datatable-responsive" class="table table-striped table-bordered responsive no-wrap" cellspacing="0" width="100%">
                          <thead>
                            <tr>
                              <th>Project Name</th>
                              <th>Proj. No.</th>
                              <th>Bldg. No.</th>
                              <th>Project Type</th>
                              <th>Project Department</td>
                              <th>Joint Clients</th>
                              <th>Project Manager</th>
                              <th>Status</th>
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
                              <td><?=($project_number ? $project_number : "")?></td>
                              <td><?=($project_building ? format_building_num($project_building) : "")?></td>
                              <td><?=$project_type?></td>
                              <td><?=$dep_name?></td>
                              <td><?=$client_list?></td>
                              <td><?=$project_manager?></td>
                              <td class="wrap-all">
                                <span class="badge bg-gray font-black font-none">
                                  <?=($project["status_needs_update"] == "Y" ?
                                  "Needs Update" : 
                                  ($project["status_approved"] == "N" ? 
                                    "Pending" : "Approved")
                                  )?>
                                </span>  
                                <span class="badge bg-<?=$project["status_color"]?>">
                                  <i class="glyph-icon icon-status-<?=$project["status_color"]?>"></i></span>
                              </td>
                            </tr>
                            <? } /* end of looping through projects */ ?>
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
