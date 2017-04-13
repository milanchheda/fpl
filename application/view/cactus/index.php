<?php
	require Config::get('PATH_LIBRARIES') . 'php-excel-reader/excel_reader2.php';
	require Config::get('PATH_LIBRARIES') . 'SpreadsheetReader.php';
	date_default_timezone_set('UTC');

	try
	{
		$Spreadsheet = new SpreadsheetReader(Config::get('PATH_LIBRARIES') . 'uploads/IPL.xlsx');
		$BaseMem = memory_get_usage();
        $payersArray = $matchesArray = [];
		$Sheets = $Spreadsheet -> Sheets();

		foreach ($Sheets as $Index => $Name)
		{
			$Time = microtime(true);

			$Spreadsheet -> ChangeSheet($Index);
			foreach ($Spreadsheet as $Key => $Row)
			{
                // echo $Key;
                // print_r($Row);
                if($Key == 0)
                    continue;

                if($Key == 7) {
                    foreach ($Row as $kr => $kv) {
                        if($kr == 0 || $kr == 1) {
                            continue;
                        }
                        if(trim($kv) != '')
                            $winninTeams[$kr] = $kr.'-'.$kv;
                    }
                }
                if($Key < 6) {
                    foreach ($Row as $kr => $kv) {
                        if($kr == 0 || $kr == 1) {
                            continue;
                        }
                        switch ($Row[0]) {
                            case 'Time (IST)':
                                $keyShouldbe = 'time';
                                break;
                            case 'Date':
                                $keyShouldbe = 'date';
                                break;
                            case 'Match No.':
                                $keyShouldbe = 'match_no';
                                break;
                            case 'Teams':
                                $keyShouldbe = 'teams';
                                break;
                            case 'Venue':
                                $keyShouldbe = 'venue';
                                break;
                            default:
                                # code...
                                break;
                        }
                        if(trim($kv) != '')
                            $matchesArray[$keyShouldbe][$kr] = $kv;
                    }
                } else if($Key >= 8 && $Key <= 21){
                    foreach ($Row as $kr => $kv) {
                        $Row[0] = str_replace(' ', '_', $Row[0]);
                        if(trim($kv) != '' && $kr != 0)
                            $payersArray[$Row[0]]['bet_on'][$kr] = $kv;
                    }
                } else if($Key >= 39 && $Key <= 62){
                    foreach ($Row as $kr => $kv) {
                        $Row[0] = str_replace(' ', '_', $Row[0]);
                        if(trim($kv) != '' && $kr != 0 && $kv != 0)
                            $payersArray[$Row[0]]['amount'][$kr] = round($kv);
                    }
                }
			}
		}

        $teamsHTML = '';
        // print_r($winninTeams);
        // print_r($matchesArray);
        foreach($matchesArray as $k => $v) {
            // print_r($v);
            foreach ($v as $kk => $vv) {
                // echo "---" . $kk . "---";
                $matchNumber = $matchesArray['match_no'][$kk];
                $venue = $matchesArray['venue'][$kk];
                $time = $matchesArray['time'][$kk];
                $date = explode('-',$matchesArray['date'][$kk]);
                $teams = explode(' v ', $matchesArray['teams'][$kk]);
                if (strpos($matchesArray['teams'][$kk], ' vs ') !== false) {
                    $teams = explode(' vs ', $matchesArray['teams'][$kk]);
                }
                $winningTeam = '';
                // echo "Match numer: " . $matchNumber;
                if(isset($winninTeams[$kk]))
                    $winningTeam = $winninTeams[$kk];
                $teamsHTML .= '<li class="matches" id="'.$winningTeam.'">
                                <div class="date-card"><ul><li><label>'.$date[1].'</label><label>'.$date[0].'</label></li></ul></div>
                                <div class="date-match" id="match_no_'.$matchNumber.'">
                                    <label class="matchDate">Match ' . $matchNumber. ', ' . $venue . '</label>
                                        <ul class="actualMatches">
                                            <li>
                                                <span class="flag"></span>
                                                <label>'.$teams[0].'</label>
                                                <span class="vs">Vs</span>
                                            </li>
                                            <li>
                                                <span class="flag"></span>
                                                <label>'.$teams[1].'</label>
                                            </li>
                                        </ul>
                                </div>
                                <p class="timeMatch">'.$time.'</p>
                            </li>';
            }
        }
        $chartHtml = '<div class="container-fluid">
                        <div class="row">
	                       <div class="col-xs-12 col-md-12 someContainer">';
        $count = 0;
        foreach ($payersArray as $key => $value) {
            $dataPoints = [];
            foreach($value['amount'] as $k => $v) {
                $dataPoints[] = array("y" => $v, "label" => $k);
            }
            $chartValues = json_encode($dataPoints, JSON_NUMERIC_CHECK);
            $betOn = '';
            if(isset($value['bet_on']))
                $betOn = implode_with_key($value['bet_on'],'-', ' ');

            $json = json_encode($value['amount']);
            $name = $fullName = str_replace('_', ' ', $key);
            $name .= " <br />(Rs." . number_format($value['amount'][count($value['amount'])], 2) . ")";
            if($count % 4 == 0)
                $chartHtml .= '<div class="col-sm-12">';

            $chartHtml .= "<div class='col-md-3 col-sm-2 blockContainer' data-name='".$fullName."' data-json='".$chartValues."'>
                            <div class='panel panel-default chart-container'>
                                <div class='panel-heading ".$betOn." losers'>" . ucwords($name) . "</div>
                            </div>
                        </div>";

            $count++;
            if($count % 4 == 0)
                $chartHtml .= '</div>';
        }
        $chartHtml .= '</div></div></div>';
	}
	catch (Exception $E)
	{
		echo $E -> getMessage();
	}


    function implode_with_key($assoc, $inglue = '>', $outglue = ',') {
        $return = '';

        foreach ($assoc as $tk => $tv) {
            $return .= $outglue . $tk . $inglue . $tv;
        }

        return substr($return, strlen($outglue));
    }
?>
<?php
    echo '<div class="slider"><ul class="iplMatches">' . $teamsHTML . '</ul></div><a href="#" class="slider-arrow sa-left">&lt;</a><a href="#" class="slider-arrow sa-right">&gt;</a>';
?>
<div class="container">
    <div class="row">
        <?php
            echo $chartHtml;
        ?>
    </div>
</div>

<!-- Modal -->
<div id="myModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><button class="close" type="button" data-dismiss="modal">×</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <div id="chartContainer" style="height: 400px; width: 100%;"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-default" type="button" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
