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
namespace hachkingtohach1\reallycheat\checks\velocity;

use hachkingtohach1\reallycheat\checks\Check;
use hachkingtohach1\reallycheat\player\RCPlayerAPI;
use hachkingtohach1\reallycheat\utils\MathUtil;
use pocketmine\event\Event;
use pocketmine\event\entity\EntityDamageByEntityEvent;

class Velocity extends Check{

    public function getName() :string{
        return "Velocity";
    }

    public function getSubType() :string{
        return "A";
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
        return 5;
    }

    public function checkJustEvent(Event $event) :void{
        if($event instanceof EntityDamageByEntityEvent){
            $entity = $event->getEntity();
            if($entity instanceof RCPlayerAPI){
                $location = $entity->getLocation();
                $lastLocation = $entity->getExternalData("lastLocationV");
                if($lastLocation !== null){
                    if(!$event->isCancelled() && $entity->isOnGround() && !$entity->isInWeb() && !$entity->isUnderBlock() && !$entity->isInBoxBlock()){
                        $velocity = MathUtil::distance($location->asVector3(), $lastLocation->asVector3());
                        if($velocity < 0.6 && $entity->getPing() < self::getData(self::PING_LAGGING)){
                            $this->failed($entity);
                        }
                    }
                    $entity->unsetExternalData("lastLocationV");
                }else{
                    $entity->setExternalData("lastLocationV", $location);
                }
            }
        }
    }

}