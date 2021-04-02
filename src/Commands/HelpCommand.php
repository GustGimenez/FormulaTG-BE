<?php

namespace FormulaTG\Commands;

use Exception;
use FormulaTG\Utils\HelpInfo;
use FormulaTG\Validators\Command\CountParams;

class HelpCommand extends Command
{
    protected function validate(): void
    {
        if (count($this->params) === 0) {
            return;
        }

        $validateParamsQuantity = new CountParams('help', ['command']);
        $validateParamsQuantity->validate($this->params);
    }

    public function execute(): string
    {
        try {
            $this->validate();
        } catch (Exception $e) {
            return $e->getMessage();
        }

        if (count($this->params) === 0) {
            return PHP_EOL . HelpInfo::GENERAL_HELP . PHP_EOL;
        }
        
        $helpInfo = '';

        switch ($this->params[0]) {
            case 'create':
                $helpInfo = HelpInfo::CREATE_HELP;
                break;
            
            case 'list':
                $helpInfo = HelpInfo::LIST_HELP;
                break;

            case 'positions':
                $helpInfo = HelpInfo::POSITION_HELP;
                break;
            
            case 'start':
                $helpInfo = HelpInfo::START_HELP;
                break;

            case 'overview':
                $helpInfo = HelpInfo::OVERVIEW_HELP;
                break;

            case 'overtake':
                $helpInfo = HelpInfo::OVERTAKE_HELP;
                break;

            case 'finish':
                $helpInfo = HelpInfo::FINISH_HELP;
                break;

            case 'history':
                $helpInfo = HelpInfo::HISTORY_HELP;
                break;

            default:
                $helpInfo = 'Help command invalid';
                break;
        }

        return PHP_EOL . $helpInfo . PHP_EOL;
    }
}
