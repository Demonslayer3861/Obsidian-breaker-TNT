<?php

namespace Saksham\KingObsidian;

use pocketmine\{
    block\BlockFactory, item\Item, plugin\PluginBase, utils\Config
};

use Saksham\KingObsidian\obsidian\ObsidianBlock;

class KingObsidian extends PluginBase{

    /** @var Config $config */
    private $config;
    /** @var Config $db */
    private $db;

    public function onEnable(){
        $this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML, [
            "[NOTE] THE ITEM TO CHECK HOW MANY MORE DURABILITY THE OBSIDIAN HAS, YOU DON'T NEED TO SET DAMAGE" => ":)",
            "obsidianDurabilityCheckItem" => [
                "id" => Item::POTATO,
                "damage" => 0
            ],
            "[NOTE] SETS HOW MUCH DURABILITY THE OBSIDIAN HAS, 1 = 1 tnt explode" => ":)",
            "obsidianDurability" => 7
        ]);

        $this->db = new Config($this->getDataFolder() . "db.json", Config::JSON);

        $this->initObsidianBlock();

        $this->getServer()->getPluginManager()->registerEvents(new ObsidianListener($this), $this);
    }

    private function initObsidianBlock(): void{
        $class = new ObsidianBlock($this);
        BlockFactory::registerBlock($class, true);
    }

    public function onDisable(){
        $this->getConfig()->save();
        $this->getDB()->save();
    }

    /**
     * @return Config
     */
    public function getConfig(): Config{
        return $this->config;
    }

    /**
     * @return Config
     */
    public function getDB(): Config{
        return $this->db;
    }
}
