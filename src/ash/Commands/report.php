<?php

namespace ash\Commands;

use ash\UI\reportUI;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class report extends Command{

    public function __construct(string $name, string $description = "", string $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if($sender instanceof Player){
            reportUI::open($sender);
        }else{
            $sender->sendMessage("Commande à utiliser en jeu");
        }
    }
}