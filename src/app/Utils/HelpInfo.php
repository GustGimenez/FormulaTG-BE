<?php

namespace FormulaTG\Utils;

class HelpInfo
{
    const GENERAL_HELP = 'Use the command "--help" command to learn how to play: ' . PHP_EOL . 
    '--help [create|list|positions|start|overview|overtake|finish|history]';
    
    const CREATE_HELP = 'Use the "--create" command to create entities: ' . PHP_EOL . 
    '--create pilot name="<pilot_name>" age=<pilot_age> ' . PHP_EOL .
    '--create car pilot=<pilot_id> color="<color_name>" equip="<equipe_name>" ' . PHP_EOL . 
    '--create race name="<race_name>"';

    const LIST_HELP = 'Use "--list [pilot|car|race]" command to see a list of all the entities';

    const POSITION_HELP = 'Use the "--positions" command to prepare the cars before the race starts' . PHP_EOL . 
    '--positions race=<race_id> cars=<car_id>,<car_id>,<car_id>,...';

    const START_HELP = 'Use the "--start <race_id>" command to start the race';

    const OVERVIEW_HELP = 'Use the "--overview" command to take a look at the cars\' positions during the ongoing race';

    const OVERTAKE_HELP = 'Use the "--overtake" command to change cars positions during the race' . PHP_EOL . 
    '--overtake overtaking=<car_id> overtaken=<car_id>';

    const FINISH_HELP = 'Use the "--finish" command to finish an ongoing race';

    const HISTORY_HELP = 'Use the "--history <race_id>" to see the overtake history of the race';
}
