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

    public function download()
    {
        $filename = Config::get('PATH_LIBRARIES') . 'uploads/IPL.xlsx';
        header("Pragma: public"); // required
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private",false); // required for certain browsers
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"".basename($filename)."\";" );
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: ".filesize($filename));
        readfile("$filename");
        exit();
    }

    public function vote()
    {
        $name = Request::post('name');
        $type = Request::post('type');

        if($name != '' && $type != '') {
            $client = new Predis\Client();
            if($client->exists($name)) {
                // $lastAccessTime = $client->object("idletime", $name);
                $getValues = $client->hgetall($name);
                $diffTime = time() - $getValues['time'];
                if($diffTime > 600) {
                    $client->hincrby($name, $type, 1);
                    $client->hset($name, 'time', time());
                    $value = $client->hgetall($name);
                    $output = array('message' => 'Vote registered successfully.', 'values' => $value);
                } else {
                    // don't do anything.
                    $minute = ceil($diffTime/60);

                    $remainingTime = (10-$minute) . ' minute(s)';
                    if((10-$minute) == 0) {
                        $remainingTime = 600-$diffTime . ' seconds';
                    }
                    $output = array('message' => 'Wait for ' . $remainingTime . ', as somebody has already voted for ' . str_replace('_', ' ', $name));
                }
            } else {
                $up_vote = ($type == 'up_vote') ? 1 : 0;
                $down_vote = ($type == 'down_vote') ? 1 : 0;
                $client->hmset($name, [
                    'up_vote' => $up_vote,
                    'down_vote' => $down_vote,
                    'time' => time()
                ]);
                $value = $client->hgetall($name);
                $output = array('message' => 'Vote registered successfully.', 'values' => $value);
            }
        }
        echo json_encode($output);
    }
}
?>
