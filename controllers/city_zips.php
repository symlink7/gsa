<?
class City_zipsController extends Controller {

  function main_form($args = array()) {
    // check permissions
    $error_msg = "";
    if (!$this->has_permissions("main_form")) {
      $error_msg = "You have no permissions to use this functionality.";
    }
    
    $this->set_var("all_projects", $this->{$this->_model_name}->all_projects());
    $this->set_var("public_projects", $this->{$this->_model_name}->public_projects());
    $this->set_var("city_zips", $this->{$this->_model_name}->get_all(array(), true));
    $this->render();
  } // main_form()

} // City_zipsController 
