<?php

/**
 * Class View
 * The part that handles all the output
 */
class View
{
    /**
     * simply includes (=shows) the view. this is done from the controller. In the controller, you usually say
     * $this->view->render('help/index'); to show (in this example) the view index.php in the folder help.
     * Usually the Class and the method are the same like the view, but sometimes you need to show different views.
     * @param string $filename Path of the to-be-rendered view, usually folder/file(.php)
     * @param array $data Data to be used in the view
     */
    public function render($filename, $data = null)
    {
        if ($data) {
            foreach ($data as $key => $value) {
                $this->{$key} = $value;
            }
        }

        require Config::get('PATH_VIEW') . '_templates/header.php';
        require Config::get('PATH_VIEW') . $filename . '.php';
        require Config::get('PATH_VIEW') . '_templates/footer.php';
    }

    /**
     * Similar to render, but accepts an array of separate views to render between the header and footer. Use like
     * the following: $this->view->renderMulti(array('help/index', 'help/banner'));
     * @param array $filenames Array of the paths of the to-be-rendered view, usually folder/file(.php) for each
     * @param array $data Data to be used in the view
     * @return bool
     */
    public function renderMulti($filenames, $data = null)
    {
        if (!is_array($filenames)) {
            self::render($filenames, $data);
            return false;
        }

        if ($data) {
            foreach ($data as $key => $value) {
                $this->{$key} = $value;
            }
        }

        require Config::get('PATH_VIEW') . '_templates/header.php';

        foreach($filenames as $filename) {
            require Config::get('PATH_VIEW') . $filename . '.php';
        }

        require Config::get('PATH_VIEW') . '_templates/footer.php';
    }

    /**
     * Same like render(), but does not include header and footer
     * @param string $filename Path of the to-be-rendered view, usually folder/file(.php)
     * @param mixed $data Data to be used in the view
     */
    public function renderWithoutHeaderAndFooter($filename, $data = null)
    {
        if ($data) {
            foreach ($data as $key => $value) {
                $this->{$key} = $value;
            }
        }

        require Config::get('PATH_VIEW') . $filename . '.php';
    }

    /**
     * Renders pure JSON to the browser, useful for API construction
     * @param $data
     */
    public function renderJSON($data)
    {
        header("Content-Type: application/json");
        echo json_encode($data);
    }

    /**
     * renders the feedback messages into the view
     */
    public function renderFeedbackMessages()
    {
        // echo out the feedback messages (errors and success messages etc.),
        // they are in $_SESSION["feedback_positive"] and $_SESSION["feedback_negative"]
        require Config::get('PATH_VIEW') . '_templates/feedback.php';

        // delete these messages (as they are not needed anymore and we want to avoid to show them twice
        Session::set('feedback_positive', null);
        Session::set('feedback_negative', null);
    }

    /**
     * Checks if the passed string is the currently active controller.
     * Useful for handling the navigation's active/non-active link.
     *
     * @param string $filename
     * @param string $navigation_controller
     *
     * @return bool Shows if the controller is used or not
     */
    public static function checkForActiveController($filename, $navigation_controller)
    {
        $split_filename = explode("/", $filename);
        $active_controller = $split_filename[0];
        if ($active_controller == $navigation_controller) {
            return true;
        }

        return false;
    }

    /**
     * Checks if the passed string is the currently active controller-action (=method).
     * Useful for handling the navigation's active/non-active link.
     *
     * @param string $filename
     * @param string $navigation_action
     *
     * @return bool Shows if the action/method is used or not
     */
    public static function checkForActiveAction($filename, $navigation_action)
    {
        $split_filename = explode("/", $filename);
        $active_action = $split_filename[1];

        if ($active_action == $navigation_action) {
            return true;
        }

        return false;
    }

    /**
     * Checks if the passed string is the currently active controller and controller-action.
     * Useful for handling the navigation's active/non-active link.
     *
     * @param string $filename
     * @param string $navigation_controller_and_action
     *
     * @return bool
     */
    public static function checkForActiveControllerAndAction($filename, $navigation_controller_and_action)
    {
        $split_filename = explode("/", $filename);
        $active_controller = $split_filename[0];
        $active_action = $split_filename[1];

        $split_filename = explode("/", $navigation_controller_and_action);
        $navigation_controller = $split_filename[0];
        $navigation_action = $split_filename[1];

        if ($active_controller == $navigation_controller AND $active_action == $navigation_action) {
            return true;
        }

        return false;
    }

    /**
     * Converts characters to HTML entities
     * This is important to avoid XSS attacks, and attempts to inject malicious code in your page.
     *
     * @param  string $str The string.
     * @return string
     */
    public function encodeHTML($str)
    {
        return htmlentities($str, ENT_QUOTES, 'UTF-8');
    }

    public function generateCard($playersArray) {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT sum(`big_chances_created`) as big_chances_created,
        sum(`big_chances_missed`) as big_chances_missed,
        sum(`yellow_cards`) as yellow_cards,
        sum(`red_cards`) as red_cards,
        sum(`penalties_missed`) as penalties_missed,
        sum(`key_passes`) as key_passes,
        sum(ph.`fouls`) as fouls,
        sum(`offside`) as offside,
        sum(`target_missed`) as target_missed
        FROM `players_history` ph
        WHERE ph.`player_id`=" . $playersArray->id;

        $query = $database->prepare($sql);
        $query->execute();
        $playersHistory = $query->fetchAll();

        $selectedByClass = '';
        if($playersArray->selected_by > 20) {
                $selectedByClass = 'green';
        }

        $pointsPerGameClass = '';
        if($playersArray->points_per_game > 6) {
                $pointsPerGameClass = 'green';
        }

        $formClass = '';
        if($playersArray->form > 8) {
                $formClass = 'green';
        }

        $redCardClass = '';
        if($playersHistory[0]->red_cards > 0)
            $redCardClass = "class='red'";

        $yellowCardClass = '';
        if($playersHistory[0]->yellow_cards > 4)
            $yellowCardClass = "class='red'";

        $foulClass = '';
        if($playersHistory[0]->fouls > 30)
            $foulClass = "class='red'";

        $bigChancesCreatedClass = '';
        if($playersHistory[0]->big_chances_created > 8)
            $bigChancesCreatedClass = "class='green'";

        return '<div class="col-md-4 col-sm-6 '. $playersArray->teamName .' allFlipCards">
             <div class="card-container manual-flip">
                <div class="card">
                    <div class="front">
                        <div class="cover">
                            <h3 class="name">' . $playersArray->playerName . '</h3>
                            <p class="profession">' . $playersArray->position . '</p>
                        </div>
                        <div class="content">
                            <div class="main">
                                <div class="stats-container">
                                    <div class="stats">
                                        <h4>' . $playersArray->total_points . '</h4>
                                        <p>
                                            Total Points
                                        </p>
                                    </div>
                                    <div class="stats">
                                        <h4>' . $playersArray->goals_scored . '</h4>
                                        <p>
                                            Goals Scored
                                        </p>
                                    </div>
                                    <div class="stats '.$selectedByClass.'">
                                        <h4>' . $playersArray->selected_by . '%</h4>
                                        <p>
                                            Selected By
                                        </p>
                                    </div>
                                    <div class="stats '.$formClass.'">
                                        <h4>' . $playersArray->form . '</h4>
                                        <p>
                                            Form
                                        </p>
                                    </div>
                                    <div class="stats">
                                        <h4>' . $playersArray->assists . '</h4>
                                        <p>
                                            Assists
                                        </p>
                                    </div>
                                    <div class="stats">
                                        <h4>' . $playersArray->minutes . '</h4>
                                        <p>
                                            Minutes
                                        </p>
                                    </div>
                                    <div class="stats '.$pointsPerGameClass.'">
                                        <h4>' . $playersArray->points_per_game . '</h4>
                                        <p>
                                            Points Per Game
                                        </p>
                                    </div>
                                    <div class="stats">
                                        <h4>' . $playersArray->dreamteam_count . '</h4>
                                        <p>
                                            Dreamteam Count
                                        </p>
                                    </div>
                                    <div class="stats">
                                        <h4>' . $playersArray->clean_sheets . '</h4>
                                        <p>
                                            Clean Sheets
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="footer">
                                <div class="club ' . $playersArray->teamName . '"></div>
                                <button class="btn btn-simple" onclick="rotateCard(this)">
                                    <i class="fa fa-mail-forward"></i> View details
                                </button>
                            </div>
                        </div>
                    </div> <!-- end front panel -->
                    <div class="back">
                        <div class="player-details">
                            <div '.$bigChancesCreatedClass.'>
                                <i class="fa fa-futbol-o" aria-hidden="true"></i>
                                <label>Big Chances Created</label>
                                <span>' . $playersHistory[0]->big_chances_created . '</span>
                            </div>
                            <div>
                                <i class="fa fa-futbol-o" aria-hidden="true"></i>
                                <label>Big Chances Missed</label>
                                <span>' . $playersHistory[0]->big_chances_missed . '</span>
                            </div>
                            <div '.$yellowCardClass.'>
                                <i class="fa fa-futbol-o" aria-hidden="true"></i>
                                <label>Yellow Cards</label>
                                <span>' . $playersHistory[0]->yellow_cards . '</span>
                            </div>
                            <div '.$redCardClass.'>
                                <i class="fa fa-futbol-o" aria-hidden="true"></i>
                                <label>Red Cards</label>
                                <span>' . $playersHistory[0]->red_cards . '</span>
                            </div>
                            <div '.$foulClass.'>
                                <i class="fa fa-futbol-o" aria-hidden="true"></i>
                                <label>Total Fouls</label>
                                <span>' . $playersHistory[0]->fouls . '</span>
                            </div>
                            <div>
                                <i class="fa fa-futbol-o" aria-hidden="true"></i>
                                <label>No. of times offside</label>
                                <span>' . $playersHistory[0]->offside . '</span>
                            </div>
                            <div>
                                <i class="fa fa-futbol-o" aria-hidden="true"></i>
                                <label>Penalties Missed</label>
                                <span>' . $playersHistory[0]->penalties_missed . '</span>
                            </div>
                            <div>
                                <i class="fa fa-futbol-o" aria-hidden="true"></i>
                                <label>Key Passes</label>
                                <span>' . $playersHistory[0]->key_passes . '</span>
                            </div>
                            <div>
                                <i class="fa fa-futbol-o" aria-hidden="true"></i>
                                <label>Targets Missed</label>
                                <span>' . $playersHistory[0]->target_missed . '</span>
                            </div>
                        </div>
                        <div class="footer">
                            <button class="btn btn-simple" rel="tooltip" title="Flip Card" onclick="rotateCard(this)">
                                <i class="fa fa-reply"></i> Back
                            </button>
                        </div>
                    </div> <!-- end back panel -->
                </div> <!-- end card -->
            </div> <!-- end card-container -->
        </div> <!-- end col sm 3 -->';
    }

    public function gameweekTopPointPlayers($gwNumber) {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT concat(p.`first_name`, ' ', p.`second_name`) as playerName, ph.`total_points` from `players_history` ph
        JOIN players p on p.`id` = ph.`player_id`
        where ph.`round`= " . $gwNumber . "
        ORDER BY ph.`total_points` DESC
        LIMIT 8";

        $query = $database->prepare($sql);
        $query->execute();
        return $query->fetchAll();
    }

    public function getClubs() {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT short_name FROM teams order by short_name ASC";

        $query = $database->prepare($sql);
        $query->execute();
        return $query->fetchAll();
    }
}
