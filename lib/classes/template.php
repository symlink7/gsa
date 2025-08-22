<?
class Template {
  private $_vars;
  protected $_controller;
  protected $_action;
     
  public function __construct($controller, $action) {
    $this->_vars = array();
    $this->_controller = $controller;
    $this->_action = $action;
  }
 
  public function set_var($varname, $value) {
    $this->_vars[$varname] = $value;
  }

  public function get_var($varname) {
    return (is_var_valid($this->_vars[$varname]) ? $this->_vars[$varname] : "");
  }  

  public function render() {
    extract($this->_vars);
    
    if (is_var_valid($generic_template)
        && file_exists(TEMPLATES.$generic_template)) {
      include(TEMPLATES.$generic_template);
    }
    else {
      $controller = ucfirst($this->_controller)."Controller";

      /*
      if (is_var_valid($_SESSION["staging"])) {
        if ((int)method_exists($controller, $this->_action)
            && file_exists(TEMPLATES."staging_header.php")) {
          include(TEMPLATES."staging_header.php");
        }
        else if (file_exists(TEMPLATES."header.php")) {
          include(TEMPLATES."header.php"); 
        }
    
        if (file_exists(VIEWS.$this->_controller.
            "/staging_".$this->_action.".php")) {
          include(VIEWS.$this->_controller.
            "/staging_".$this->_action.".php");
        }
        else if (file_exists(VIEWS.$this->_controller.
          "/".$this->_action.".php")) {
          include(VIEWS.$this->_controller.
            "/".$this->_action.".php");
        }
    
        if ((int)method_exists($controller, $this->_action) &&
            file_exists(TEMPLATES."footer.php")) { 
          include(TEMPLATES."footer.php");
        }
      } // if it's staging
      else { */
        if ((int)method_exists($controller, $this->_action)
            && file_exists(TEMPLATES."header.php")) {
          include(TEMPLATES."header.php");
        }
    
        if (file_exists(VIEWS.$this->_controller."/".$this->_action.".php")) {
          include(VIEWS.$this->_controller."/".$this->_action.".php");
        } 
    
        if ((int)method_exists($controller, $this->_action) &&
            file_exists(TEMPLATES."footer.php")) { 
          include(TEMPLATES."footer.php");
        }
      // } // if it's live
    }  
  } // end of function render
}
