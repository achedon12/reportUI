<?php

namespace ash;

use ash\Commands\report;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase {


    private static $instance;

    public function onEnable(){

        self::$instance = $this;
        @mkdir($this->getDataFolder());

        $this->saveResource("config.yml");

        $this->db = new Config($this->getDataFolder() . "config.yml" . Config::YAML);



        $this->getLogger()->info("Plugin activé !");
        if(self::config()->getNested("WebHook.Url") == ""){
            $this->getServer()->getPluginManager()->disablePlugin($this);
            $this->getLogger()->info("Plugin désactivé ==> manque url du webhook");

        }else{
            $this->getServer()->getCommandMap()->registerAll('Commands',[
                new report("report","report un joueur","/report")
            ]);
        }

    }





    public static function config(){
        return new Config(self::$instance->getDataFolder() . "config.yml", Config::YAML);
    }


    public static function getInstance()
    {
        return self::$instance;
    }

}
