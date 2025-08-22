<?

class DepartmentsController extends Controller {

  function api_list() {
    $this->_template = new Template($this->_controller_name, "list_json");
    $this->set_var("tudo", DB::get_id_name(
      array("model" => "departments", "order" => "dep_name")
    ));

    $this->render();
  } // api_list()

}
