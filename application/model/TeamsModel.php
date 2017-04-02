<?php

/**
 * PlayersModel
 */
class TeamsModel
{
    public static function getAllTeamsSummary($getMore = 0)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT
        sum(ph.total_points) as total_points,
        sum(bonus) as bonus,
        sum(ph.minutes) as minutes,
        sum(`big_chances_missed`) as big_chances_missed,
        sum(ph.yellow_cards) as yellow_cards,
        sum(ph.red_cards) as red_cards,
        sum(ph.penalties_missed) as penalties_missed,
        sum(ph.penalties_saved) as penalties_saved,
        sum(`key_passes`) as key_passes,
        sum(`fouls`) as fouls,
        sum(`offside`) as offside,
        sum(`target_missed`) as target_missed,
        sum(winning_goals) as winning_goals,
        sum(tackles) as tackles,
        sum(completed_passes) as completed_passes,
        sum(dribbles) as dribbles,
        sum(errors_leading_to_goal) as errors_leading_to_goal,
        sum(ph.`goals_scored`) as goals_scored,
        sum(ph.`assists`) as assists,
        sum(ph.`clean_sheets`) as clean_sheets,
        sum(ph.`big_chances_created`) as big_chances_created,
        t.`short_name`
        from `players_history` ph
        join players p on p.id = ph.`player_id`
        join teams t on p.`team_code` = t.code
        group by t.`short_name`
        ORDER BY total_points desc";
        $query = $database->prepare($sql);
        $query->execute();
        return $query->fetchAll();
    }

}
