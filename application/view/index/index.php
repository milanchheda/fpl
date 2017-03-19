<div class="container">
    <div class="row">
     <div class="col-sm-12 col-sm-offset-1" id="gwStats">
        <?php foreach($this->gw as $key => $value) {
            $additionalData = $this->gameweekTopPointPlayers($value->gameweek_number);
         ?>
            <div class="col-md-4 col-sm-6">
                 <div class="card-container manual-flip">
                    <div class="card">
                        <div class="front">
                            <div class="cover">
                                <h3 class="name">Gameweek <?= $value->gameweek_number ?></h3>
                            </div>
                            <div class="content">
                                <div class="main">
                                    <div class="stats-container">
                                        <div class="stats">
                                            <h4><?= $value->total_points ?></h4>
                                            <p>
                                                Total Points
                                            </p>
                                        </div>
                                        <div class="stats">
                                            <h4><?= $value->total_assists ?></h4>
                                            <p>
                                                Assists
                                            </p>
                                        </div>
                                        <div class="stats">
                                            <h4><?= $value->total_bonus ?></h4>
                                            <p>
                                                Bonus
                                            </p>
                                        </div>
                                        <div class="stats">
                                            <h4><?= $value->total_clean_sheets ?></h4>
                                            <p>
                                                Clean Sheets
                                            </p>
                                        </div>
                                        <div class="stats">
                                            <h4><?= $value->total_yellow_cards ?></h4>
                                            <p>
                                                Yellow Cards
                                            </p>
                                        </div>
                                        <div class="stats">
                                            <h4><?= $value->total_red_cards ?></h4>
                                            <p>
                                                Red Cards
                                            </p>
                                        </div>
                                        <div class="stats">
                                            <h4><?= $value->total_goals_scored ?></h4>
                                            <p>
                                                Goals Scored
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="footer">
                                    <button class="btn btn-simple" onclick="rotateCard(this)">
                                        <i class="fa fa-mail-forward"></i> View details
                                    </button>
                                </div>
                            </div>
                        </div> <!-- end front panel -->
                        <div class="back">
                            <div class="player-details">
                                <?php
                                foreach ($additionalData as $key => $value) {

                                    echo '<div>
                                        <i class="fa fa-futbol-o" aria-hidden="true"></i>
                                        <label>' . $value->playerName . '</label>
                                        <span>' . $value->total_points . '</span>
                                    </div>';
                                }
                                ?>
                            </div>
                            <div class="footer">
                                <button class="btn btn-simple" rel="tooltip" title="Flip Card" onclick="rotateCard(this)">
                                    <i class="fa fa-reply"></i> Back
                                </button>
                            </div>
                        </div> <!-- end back panel -->
                    </div> <!-- end card -->
                </div> <!-- end card-container -->
            </div> <!-- end col sm 3 -->

        <?php } ?>
        </div> <!-- end col-sm-10 -->
    </div> <!-- end row -->
</div>
