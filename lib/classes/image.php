<?
ini_set('memory_limit', '64M');
class image {
  var $filename;
  var $newfilename;
  var $fileext;
  var $curw;
  var $curh;
  var $curo;
  var $maxw;
  var $maxh;
  var $main_limit;
  var $neww;
  var $newh;
  var $oldimgrc;
  var $newimgrc;
  var $x;
  var $y;

  function image ($filename, $newfilename, $maxw, $maxh, $main_limit=""){
    $this->filename = $filename;
    $this->newfilename = $newfilename;
    $this->maxw = $maxw;
    $this->maxh = $maxh;
    $this->main_limit = $main_limit;
  }

  function resize(){
    $this->get_image_info();
    $this->calculate_new_size();
    $this->get_extention();
    $this->open_image();
    $this->resize_image();
    $this->save_image();
  }
  
  function crop(){
    $this->get_image_info();
    $this->new_thumb_size();
    $this->get_extention();
    $this->open_image();
    $this->crop_image();    
    $this->save_image();
  }

  function merge($filename, $fb_img){
    $this->filename = $filename;
    $this->newfilename = $filename;   
    $this->get_image_info();
    $this->get_extention();
    $this->open_image();
    
    $fb_size = getimagesize($fb_img);
    $fb_w = $fb_size[0];
    $fb_h = $fb_size[1];
    $fb_imgrc = imagecreatefromgif($fb_img);
    imagecopymerge($this->oldimgrc, $fb_imgrc, 
                   $this->curw - $fb_w, $this->curh - $fb_h, 0, 0, $fb_w, $fb_h, 100);
    imagedestroy($fb_imgrc);
    $this->newimgrc = $this->oldimgrc;
    $this->save_image();
  }

  function rotate($angle){
    $this->get_image_info();
    $this->get_extention();
    $this->open_image();
    $this->newimgrc = imagerotate($this->oldimgrc, $angle, 1);  
    $this->save_image();
  }

  function get_image_info(){
    $size = getimagesize($this->filename);
    $this->curw = $size[0];
    $this->curh = $size[1];
    $this->orientation();
  }
                    
  function orientation(){
    $this->curo = ($this->curw < $this->curh ? "v" : "h");
  }    
  
  function calculate_new_size(){
    $oldw = $this->curw;
    $oldh = $this->curh;
//    $w = ($this->curo=="h" ? $this->maxw : $this->maxh);
//    $h = ($this->curo=="h" ? $this->maxh : $this->maxw);
    if ($this->main_limit=="width"){
      $w = $this->maxw;
      $h = $this->curh;
    }
    else if ($this->main_limit=="height"){
      $w = $this->curw;
      $h = $this->maxh;
    }
    else if ($this->main_limit=="both"){
      $w = $this->maxw;
      $h = $this->maxh;
    }
    else if ($this->main_limit=="crop") {
      $w = ($this->curo=="h" ? $this->curw : $this->maxw);
      $h = ($this->curo=="h" ? $this->maxh : $this->curh);
    }
    else {
      $w = ($this->curo=="h" ? $this->maxw : $this->curw);
      $h = ($this->curo=="h" ? $this->curh : $this->maxh);
    }
    $this->maxw = $w;
    $this->maxh = $h;
    if ($oldw <= $w && $oldh <= $h){
      $this->neww = $oldw;
      $this->newh = $oldh;
    }
    else {
      $kw = round($oldw / $w, 2);
      $kh = round($oldh / $h, 2);
      if ($oldw > $w && $oldh <= $h)
        $resize_koeff = $kw;
      else if ($oldw <= $w && $oldh > $h)
        $resize_koeff = $kh;
      else {
        if ($kw > $kh)
          $resize_koeff = $kw;
        else
          $resize_koeff = $kh;
      }
      $this->neww = ceil($oldw / $resize_koeff);
      if ($this->neww==0)
        $this->neww=1;
      $this->newh = ceil($oldh / $resize_koeff);
      if ($this->newh==0)
        $this->newh=1;
    }
  }

  function get_extention(){
    $temp = explode(".", $this->filename);
    $last = sizeof($temp) - 1;
    $this->fileext = strtolower($temp[$last]);
  }        

  function open_image(){
    if ($this->fileext=="jpg" || $this->fileext=="jpeg")
      $this->oldimgrc = imagecreatefromjpeg($this->filename);
    else if ($this->fileext=="gif")
      $this->oldimgrc = imagecreatefromgif($this->filename);
    else if ($this->fileext=="png") 
      $this->oldimgrc = imagecreatefrompng($this->filename);
  }

  function resize_image(){
    $this->newimgrc = imagecreatetruecolor($this->neww, $this->newh);
    imagecopyresampled($this->newimgrc, $this->oldimgrc, 0, 0, 0, 0, $this->neww, $this->newh, $this->curw, $this->curh);
  }

  function new_thumb_size(){
    if ($this->curh > $this->maxh)
      $this->y = round(($this->curh / 2) - ($this->maxh / 2));
    else
      $this->y = 0;
    
    if ($this->curw > $this->maxw)
      $this->x = round(($this->curw / 2) - ($this->maxw / 2));
    else
      $this->x = 0;
  }

  function crop_image(){
    $this->newimgrc = imagecreatetruecolor($this->maxw, $this->maxh);
    imagecopy($this->newimgrc, $this->oldimgrc, 0, 0, $this->x, $this->y, $this->curw, $this->curh);
    $this->oldimgrc = $this->newimgrc;
    
  }

  function save_image(){
    if ($this->fileext=="jpg" || $this->fileext=="jpeg")
      imagejpeg($this->newimgrc, $this->newfilename, 100);
    else if ($this->fileext=="png")
      imagepng($this->newimgrc, $this->newfilename);
    else if ($this->fileext=="gif")
      imagegif($this->newimgrc, $this->newfilename);
    imagedestroy($this->newimgrc);
    imagedestroy($this->oldimgrc);
  }
}
?>
