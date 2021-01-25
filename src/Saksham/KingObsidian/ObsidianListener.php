<?php

namespace kirito\KingObsidian;

use pocketmine\{
    entity\object\PrimedTNT, event\entity\EntityExplodeEvent, event\Listener
};

use Saksham\KingObsidian\obsidian\ObsidianBlock;

class ObsidianListener implements Listener{

    /** @var KingObsidian $plugin */
    private $plugin;

    public function __construct(KingObsidian $plugin){
        $this->plugin = $plugin;
    }

    /**
     * @param EntityExplodeEvent $ev
     * @priority HIGHEST
     */
    public function onEntityExplode(EntityExplodeEvent $ev): void{
        $entity = $ev->getEntity();

        if(!$entity instanceof PrimedTNT) return;

        $bList = $ev->getBlockList();

        $db = $this->plugin->getDB()->getAll();

        foreach($bList as $i => $block){
            if($block instanceof ObsidianBlock){
                $index = ($block->getX() . ":" . $block->getY() . ":" . $block->getZ() . ":" . $block->getLevel()->getName());
                if(!isset($db["obsidians"][$index])){
                    $db["obsidians"][$index] = 1;
                }else{
                    $db["obsidians"][$index] += 1;
                }

                if($db["obsidians"][$index] >= $this->plugin->getConfig()->get("obsidianDurability")){
                    unset($db["obsidians"][$index]);
                }else{
                    unset($bList[$i]);
                }
            }
        }
        $ev->setBlockList($bList);

        $this->plugin->getDB()->setAll($db);
    }
}
