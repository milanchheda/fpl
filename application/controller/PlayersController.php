<?php
// Turn off all error reporting
error_reporting(0);

class PlayersController extends Controller
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
        if(isset($_REQUEST['size']) && $_REQUEST['size'] != '') {
            $players = PlayersModel::getAllPlayersSummary($_REQUEST['size']);
            $playersHtml = '';
            foreach($players as $key => $value) {
                $playersHtml .= View::generateCard($value);
            }
            echo json_encode(array('data' => $playersHtml, 'count' => count($players)));
        } else {
            // $this->View->render('players/index');
            $this->View->render('players/index', array(
                'players' => PlayersModel::getAllPlayersSummary()
            ));
        }
    }
}
