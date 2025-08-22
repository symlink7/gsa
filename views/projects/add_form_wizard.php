<div id="form-wizard-3" class="form-wizard">
  <ul>
    <li<?=($step == 1 ? ' class="active"' : "")?>>
      <a href="#">
        <label class="wizard-step">1</label>
        <span class="wizard-description">Project details
          <small>Basic info</small>
        </span>
      </a>
    </li>
    <li<?=($step == 2 ? ' class="active"' : "")?>>
      <a href="#">
        <label class="wizard-step">2</label>
        <span class="wizard-description">
          Budget Information
          <small>OB, CB</small>
        </span>
      </a>
    </li>
    <li<?=($step == 3 ? ' class="active"' : "")?>>
      <a href="#">
        <label class="wizard-step">3</label>
        <span class="wizard-description">
          Schedule
          <small>Start date, Completion dates</small>
        </span>
      </a>
    </li>
    <li<?=($step == 4 ? ' class="active"' : "")?>>
      <a href="#">
        <label class="wizard-step">4</label>
        <span class="wizard-description">
          Phase & Status
          <small>Scope, Budget, Schedule Status</small>
        </span>
      </a>
    </li>
  </ul>
</div><!-- form-wizard -->
