<div class="container">
    <div class="row">
     <div class="col-sm-12 col-sm-offset-1" id="listOfTeams">
        <?php
        if($this->teams) {
            foreach($this->teams as $key => $value) {
                echo $this->generateTeamCard($value);
            }
        } ?>
        </div> <!-- end col-sm-10 -->
    </div> <!-- end row -->
</div>
