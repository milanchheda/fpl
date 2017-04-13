        <div class="footer">
            <footer>
                <center>
                    <div class="devunit">
                       Made with <span class="love"><i class="glyphicon glyphicon-heart"></i></span>  by <a href="//milanchheda.com" target="_BLANK">Milan Chheda</a>
                    </div>
                </center>
            </footer>
        </div>
    </div><!-- close class="wrapper" -->
    <script type="text/javascript" src="<?php echo Config::get('URL'); ?>js/jquery-3.1.1.min.js"></script>
    <script type="text/javascript" src="<?php echo Config::get('URL'); ?>js/nprogress.js"></script>
    <script type="text/javascript" src="<?php echo Config::get('URL'); ?>js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo Config::get('URL'); ?>js/bootstrap-typeahead.js"></script>
    <script type="text/javascript" src="<?php echo Config::get('URL'); ?>js/html2canvas.js"></script>
    <script type="text/javascript" src="<?php echo Config::get('URL'); ?>js/fileSaver.js"></script>
    <script type="text/javascript" src="<?php echo Config::get('URL'); ?>js/jquery.lbslider.js"></script>
    <script type="text/javascript" src="<?php echo Config::get('URL'); ?>js/players.js"></script>
    <?php
    if (View::checkForActiveController($filename, "players")) {
    ?>
        <!-- <script type="text/javascript" src="<?php echo Config::get('URL'); ?>js/players.js" async></script> -->
    <?php }
    ?>
    <script>
    function rotateCard(btn){
        var $card = $(btn).closest('.card-container');
        console.log($card);
        if($card.hasClass('hover')){
            $card.removeClass('hover');
        } else {
            $card.addClass('hover');
        }
    }
    </script>
</body>
</html>
