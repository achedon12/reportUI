<?php

namespace ash\UI;

use ash\API\discordAPI\Embed;
use ash\API\discordAPI\Message;
use ash\API\discordAPI\Webhook;
use ash\API\FormAPI\CustomForm;
use ash\API\FormAPI\SimpleForm;
use ash\Main;
use pocketmine\Player;
use pocketmine\Server;

class reportUI extends SimpleForm {
    public static function open(Player $player){
        $cfg = Main::config();

        foreach (Server::getInstance()->getOnlinePlayers() as $p){
            $allPlayer[] = $p->getName();
        }
        $form = new CustomForm(function (Player $player, array $data = null) use ( $allPlayer,$cfg) {
            if(is_null($data)){
                return true;
            }else{
                $target = $allPlayer[$data[1]];
                $reason = $data[2];
                $player->sendMessage(str_replace("{playerName}",$target,$cfg->getNested("Message.ConfirmMessage")));
                Server::getInstance()->broadcastMessage(str_replace(["{TargetPlayerName}","{playerName}","{reason}"],[$target,$player->getName(),$reason],$cfg->getNested("Message.ServerMessage")));

                $webhook = new Webhook($cfg->getNested("WebHook.Url"));
                $msg = new Message();
                $embed = new Embed();
                $embed->setTitle($cfg->getNested("WebHook.Title"));
                $embed->setColor($cfg->getNested("WebHook.Color"));
                $embed->setDescription($cfg->getNested("WebHook.Description"));
                $embed->setImage($cfg->getNested("WebHook.ImageUrl"));
                $embed->setFooter($cfg->getNested("WebHook.Footer"));
                $embed->addField($cfg->getNested("WebHook.Field.1"),$target);
                $embed->addField($cfg->getNested("WebHook.Field.2"),$player->getName());
                $embed->addField($cfg->getNested("WebHook.Field.3"),$reason);
                $msg->addEmbed($embed);
                $webhook->send($msg);
            }

        }
        );
        $form->setTitle($cfg->getNested("Form.Title"));
        $form->addLabel($cfg->getNested("Form.Content"));
        $form->addDropdown("§eJoueurs",$allPlayer);
        $form->addInput($cfg->getNested("Form.Input"),"raison du report");
        $player->sendForm($form);
    }
}

