<?php

/**
 * PlayersModel
 */
class PlayersModel
{
    public static function getAllPlayersSummary($getMore = 0)
    {
        $offset = (isset($getMore) && $getMore != '') ? $getMore : 0;
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT t.short_name as teamName, concat(p.`first_name`, ' ', p.`second_name`) as playerName, et.`name` position, p.*
                FROM players p
                JOIN teams t on t.`code` = p.`team_code`
                JOIN `element_types` et on et.`id` = p.`element_type`
                ORDER BY p.total_points DESC
                LIMIT $offset, 50";
        $query = $database->prepare($sql);
        $query->execute();
        return $query->fetchAll();
    }

    public static function getAllGameweeksData(){
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT sum(l.total_points) as total_points,
        sum(l.assists) as total_assists,
        sum(bonus) as total_bonus,
        sum(l.clean_sheets) as total_clean_sheets,
        sum(l.yellow_cards) as total_yellow_cards,
        sum(l.red_cards) as total_red_cards,
        sum(l.goals_scored) as total_goals_scored,
        gameweek_number
		from live l
		join players p on p.id = l.player_id
		group by gameweek_number
		ORDER By gameweek_number DESC;";
        $query = $database->prepare($sql);
        $query->execute();
        return $query->fetchAll();
    }
}
