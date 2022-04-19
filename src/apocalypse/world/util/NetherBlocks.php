<?php

namespace apocalypse\world\util;

use ipad54\netherblocks\blocks\Basalt;
use ipad54\netherblocks\blocks\PolishedBasalt;
use ipad54\netherblocks\utils\CustomIds;
use pocketmine\block\Block;
use pocketmine\block\BlockFactory;
use pocketmine\block\Opaque;
use pocketmine\utils\CloningRegistryTrait;

/**
 * @generate-registry-docblock
 *
 * @method static Opaque ANCIENT_DEBRIS_BLOCK()
 * @method static Basalt BASALT_BLOCK()
 * @method static PolishedBasalt POLISHED_BASALT_BLOCK()
 */
class NetherBlocks {
    use CloningRegistryTrait;

    private function __construct(){
        //NOOP
    }

    protected static function register(string $name, Block $block) : void{
        self::_registryRegister($name, $block);
    }

    /**
     * @return Block[]
     */
    public static function getAll() : array{
        //phpstan doesn't support generic traits yet :(
        /** @var Block[] $result */
        $result = self::_registryGetAll();
        return $result;
    }

    protected static function setup() : void {
        $factory = BlockFactory::getInstance();
        self::register("ancient_debris_block", $factory->get(CustomIds::ANCIENT_DEBRIS_BLOCK, 0));
        self::register("basalt_block", $factory->get(CustomIds::BASALT_BLOCK, 0));
        self::register("polished_basalt_block", $factory->get(CustomIds::POLISHED_BASALT_BLOCK, 0));
    }
}