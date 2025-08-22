<?

function widget_project_health($colors) {
  $str = '
    <div class="panel">
      <div class="panel-body bg-black">
        <h2 class="title-hero" style="font-size:20px;">
          OVERALL PROJECT HEALTH
        </h2>
        <div class="example-box-wrapper">
          <div class="row">
  ';
  $arr = array("red", "yellow", "green");
  foreach ($arr as $color) {
    $str .= '
            <div class="col-md-4">
              <a href="index.php?q=projects/view_approved/'.
              $color.'" title="'.$color.
              '" class="tile-box btn bg-'.$color.'">
                <div class="tile-header">'.strtoupper($color).'</div>
                <div class="tile-content-wrapper">
                  <i class="glyph-icon icon-status-'.$color.'-circle"></i>
                  <div class="tile-content">'.$colors[$color].'</div>
                  <small>Projects</small>
                </div><!-- tile content wrapper -->
              </a>
            </div><!-- col-med-4 -->
  ';
  }
  $str .= '               
          </div><!-- .row -->
        </div><!-- .example-box-wrapper -->
      </div><!-- .panel-body -->
    </div><!-- .panel -->
  ';
  return $str;
}

function widget_project_health_staging($colors, $archived) {
  $str = '
    <div class="box-line" style="height:4px;">
    </div>
    <h4 class="pad10B">Overall Project Health</h4>
    <div class="row">
  ';
  $arr = array("green", "yellow", "red");
  foreach ($arr as $color) {
    $str .= '
      <div class="col-md-4">
        <div class="layout-box '.$color.'-border-white-bg">
          <a href="index.php?q=projects/view_approved/'.
          $color.$archived.'" title="'.$color.
          '" class="tile-box btn bg-'.$color.'">
            <div class="tile-header">'.strtoupper($color).'</div>
            <div class="tile-content-wrapper">
              <i class="glyph-icon icon-status-'.$color.'-circle"></i>
              <div class="tile-content">'.$colors[$color].'</div>
              <small>Projects</small>
            </div><!-- tile content wrapper -->
          </a>
        </div><!-- layout-box '.$color.'-border-white-bg -->  
      </div><!-- col-med-4 -->
  ';
  }
  $str .= '               
    </div><!-- .row -->
    <div class="box-line" style="height:4px;">
    </div>
  ';
  return $str;
}

?>
