<? 
require_once(VIEWS."projects/widgets.php");

include(CONFIG."projects.php");

include(TEMPLATES."general_wrap.php"); 
include(TEMPLATES."widgets/spinner.html");
include(TEMPLATES."widgets/data_tables.html");
include(TEMPLATES."widgets/flot.html");
if (!$color) {
  include(TEMPLATES."widgets/morris.html");
}

setlocale(LC_MONETARY, 'en_US');
?>

        <div id="page-content-wrapper">
          <div id="page-content">
            <div class="container">
              
              <div id="page-title">
                <h2><?=(!$color ? 
                  "GSA Project Status Dashboard" :
                  'Projects with <span class="font-'.$color.'">'.
                  strtoupper($color).' Status</span>')?></h2>
              </div><!--Page Title-->
                
              <?
              if (!$color) {
                // if we're looking at archived projects
                // we want to link to archived projects as well
                echo widget_project_health_staging(
                  $colors, 
                  ($archived ? "/archived" : "")
                );
              } /* end of if !$color */ 
              ?>

              <div class="row">
                <div class="col-md-6">
                  <? if (!$color) { ?>
                  <h4>All Projects</h4>
                  <? } ?>
                </div><!-- col-md-6 -->

                <div class="col-md-6">
                  <div class="float-right-box pad20B">
                    <a href="index.php?q=projects/view_approved<?=($color ? "/$color" : "")?><?=($archived ? "" : "/archived")?>" class="btn btn-light-green font-bold pad5A pad10B pad20L pad20R"><?=($archived ? "Current Projects" : "Completed Projects")?></a>
                  </div><!-- float right box -->
                </div><!-- col-med-6 -->
              </div><!-- row -->
              
              <div class="row">
                <div class="col-md-6">
                  <div class="panel">
                    <div class="panel-body">
                      <h3 class="title-hero">
                        Budget by District 
                      </h3>
                      <div class="example-box-wrapper clearfix">
                        <div id="districts-donut" 
                             style="width: 100%; height: 200px;">
                        </div>
                      </div>
                    </div><!-- panel-body -->
                  </div><!-- panel -->
                </div><!-- col-md-4-->

                <div class="col-md-6">
                  <div class="panel">
                    <div class="panel-body">
                      <h3 class="title-hero">
                        Budget by Client 
                      </h3>
                      <div class="example-box-wrapper clearfix">
                        <div id="clients-donut" 
                             style="width: 100%; height: 200px;">
                        </div>
                      </div>
                    </div><!-- panel-body -->
                  </div><!-- panel -->
                </div><!-- col-md-4-->

              </div><!-- row -->
              
              <? if (!$color) { ?>
              <div class="row">
                <div class="col-md-12">
                  <div class="panel">
                    <div class="panel-body">
                      <h3 class="title-hero">
                        Budget by Building
                      </h3>
                      <div class="example-box-wrapper">
                        <div id="buildings-bar" class="graph"></div>
                        <!--
                        <canvas id="canvas-1" height="450" width="600"></canvas>
                        -->
                      </div>
                    </div>
                  </div>
                </div>
              </div><!-- .row -->
              <? } ?>

              <div class="example-box-wrapper">
                <div class="content-box-wrapper">
                  <div class="example-box-wrapper">
                    <table id="datatable-responsive" class="table table-striped table-bordered responsive no-wrap" cellspacing="0" width="100%">
                      <thead>
                        <tr>
                          <th>Project Name</th>
                          <th>Project Number</th>
                          <th>District</th>
                          <th>Project Type</th>
                          <th>Project Department</th>
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
                          <td><?=$project_type?></td>
                          <td><?=$dep_name?></td>
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

    <script>
      var dataSet = [
      <? 
      $set = array();
      foreach ($budgets_by_district as $district => $budget) {
        $set[] = '
        { 
          label: "'.$project_district_options[$district].
            '<span class=\"invis\">, '.
            format_budget($budget).'</span>", 
          data: '.$budget.' 
        }';
      }
      echo implode(",\n", $set);
      ?>
      ]; // dataSet
      jQuery.plot('#districts-donut', dataSet, {
        series: {
          pie: {
            show: true
          },
        },
        tooltip: true,
        tooltipOpts: {
          content: "%p.0%, %s"
        },
        grid: {
          hoverable: true,
          clickable: false
        }
      });

      var dataSetc = [
      <?
      $set = array();
      foreach ($budgets_by_client as $client => $budget) {
        $set[] = '
        { 
          label: "'.$client_names[$client].
            '<span class=\"invis\">, '.
            format_budget($budget).'</span>", 
          data: '.$budget.' 
        }';
      }
      echo implode(",\n", $set);
      ?>
      ]; // dataSetc
      jQuery.plot('#clients-donut', dataSetc, {
        series: {
          pie: {
            show: true,
            combine: {
              threshold: 0.005,
              label: "Other",
            }
          }
        },
        tooltip: true,
        tooltipOpts: {
          content: "%p.0%, %s"
        },
        grid: {
          hoverable: true,
          clickable: false
        }
      });

    <?
    if (!$color) {
      $set = array();
      $max = 0;
      $min = 0;
      foreach ($budgets_by_building as $building => $budget) {
        if ($budget < 1) {
          break;
        }
        if (!$max) {
          $max = $budget;
        }
        $min = $budget;
         
        $set[] = '
        { 
          building: "<a href=\"index.php?q=projects/view_approved_by_building/'.
          $building.'\">#'.$building.'</a>",
          building_num: "#'.$building.'",
          budget: '.$budget.' 
        }';
      }

      $min = round_down($min);
      $max = round_up($max);
      ?>

      // part of Morris
      var dataSet2 = [ 
      <?=implode(",\n", $set); ?>
      ]; // dataSet2
      
      Morris.Bar({
        element: 'buildings-bar',
        data: dataSet2,
        xkey: 'building',
        xkey_nohtml: 'building_num',
        ykeys: ['budget'],
        labels: ['Budget'],
        barRatio: 0.75,
        xLabelAngle: 35,
        hideHover: 1,
        // stacked: 1,
        preUnits: '$',
        ymin: <?=$min?>,
        ymax: <?=$max?>,
        numLines: 10,
        barSizeRatio: 0.75
        // barGap: 0 
      });
    <? } ?>      
    </script>
