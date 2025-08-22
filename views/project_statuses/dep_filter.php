<script type="text/javascript" src="platform/js/dep_filter.js"></script>
<div class="row dep-filter-row">
  <div class="col-sm-8"></div>
  <div class="col-sm-4">
    <label>Filter by Department: </label>
    <select id="dep_filter" name="dep_filter">
      <option value=""></option>
        <?
        for ($i = 0; $i < sizeof($departments); $i++) {
          $deps = $departments[$i];
          echo '
        <option value="'.$deps["dep_id"].'">'.
          htmlentities($deps["dep_name"]).
        '</option>
        ';
        } 
        ?>
    </select>
  </div><!-- col-sm-6 -->
</div><!-- row -->

