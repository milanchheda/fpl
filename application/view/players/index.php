<div class="container">
    <div class="row">
        <ul class="clubList" role="menu">
            <?php $clubs = $this->getClubs();
                foreach($clubs as $club) {
                    echo "<li>
                            <span class='club clickable ".$club->short_name."' data-club='".$club->short_name."'></span>
                        </li>";
                }
            ?>
        </ul>
    </div>
    <div class="row">
     <div class="col-sm-12 col-sm-offset-1" id="listOfPlayers">
        <?php
        if($this->players) {
            foreach($this->players as $key => $value) {
                echo $this->generateCard($value);
            }
        } ?>
        </div> <!-- end col-sm-10 -->
    </div> <!-- end row -->
</div>
