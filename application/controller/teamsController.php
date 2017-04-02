<?php
// Turn off all error reporting
error_reporting(0);

class teamsController extends Controller
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
        // $this->View->render('players/index');
        $this->View->render('teams/index', array(
            'teams' => teamsModel::getAllTeamsSummary()
        ));

    }
}
