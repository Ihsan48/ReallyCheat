<?php
/**
 *  Copyright (c) 2022 hachkingtohach1
 *
 *  Permission is hereby granted, free of charge, to any person obtaining a copy
 *  of this software and associated documentation files (the "Software"), to deal
 *  in the Software without restriction, including without limitation the rights
 *  to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 *  copies of the Software, and to permit persons to whom the Software is
 *  furnished to do so, subject to the following conditions:
 *
 *  The above copyright notice and this permission notice shall be included in all
 *  copies or substantial portions of the Software.
 *
 *  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 *  FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 *  AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 *  LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 *  OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 *  SOFTWARE.
 */
namespace hachkingtohach1\reallycheat\checks\badpackets;

use hachkingtohach1\reallycheat\checks\Check;
use hachkingtohach1\reallycheat\player\RCPlayerAPI;
use pocketmine\network\mcpe\protocol\DataPacket;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\network\mcpe\protocol\types\LevelSoundEvent;

class BadPacketsH extends Check{

    public function getName() :string{
        return "AutoClick";
    }

    public function getSubType() :string{
        return "B";
    }

    public function enable() :bool{
        return true;
    }

    public function ban() :bool{
        return false;
    }

    public function transfer() :bool{
        return true;
    }

    public function flag() :bool{
        return false;
    }

    public function captcha() :bool{
        return false;
    }

    public function maxViolations() :int{
        return 1;
    }

    public function check(DataPacket $packet, RCPlayerAPI $player) :void{
        if($player->getPlacingTicks() < 100){
            return;
        }
        $ticks = $player->getExternalData("clicksTicks2");
        $lastClick = $player->getExternalData("lastClick");
        if($packet instanceof LevelSoundEventPacket){
            if($packet->sound === LevelSoundEvent::ATTACK_NODAMAGE){
                if($ticks !== null && $lastClick !== null){
                    $diff = microtime(true) - $lastClick;
                    if($diff > 2){
                        if($ticks >= 25){
                            $this->failed($player);
                        }
                        $player->unsetExternalData("clicksTicks2");
                        $player->unsetExternalData("lastClick");
                    }else{
                        $player->setExternalData("clicksTicks2", $ticks + 1);
                    }
                }else{
                    $player->setExternalData("clicksTicks2", 0);
                    $player->setExternalData("lastClick", microtime(true));
                }
            }
        }
    }

}