<?php
// Turn off all error reporting
// error_reporting(0);

class CactusController extends Controller
{
    /**
     * Construct this object by extending the basic Controller class
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Handles what happens when user moves to URL/index/index - or - as this is the default controller, also
     * when user moves to /index or enter your application at base level
     */
    public function index()
    {
        $this->View->render('cactus/index');
    }

    public function upload()
    {
        $this->View->render('cactus/upload');
    }

    public function upload_xls() {
        $temp = explode(".", $_FILES["ufile"]["name"]);
        $newfilename = 'IPL.xlsx';
        unlink(Config::get('PATH_LIBRARIES') . "uploads/" . $newfilename);
        move_uploaded_file($_FILES["ufile"]["tmp_name"], Config::get('PATH_LIBRARIES') . "uploads/" . $newfilename);
        header('Location: ' . Config::get('URL') . 'cactus');
    }
}
?>
