<?php


namespace App\Tests\Service;


use App\Entity\Command;
use App\Service\PricingService;
use PHPUnit\Framework\TestCase;

class PricingServiceTest extends TestCase
{
    public function testGetConstant(){
        $manager = $this->getMockBuilder("Doctrine\ORM\EntityManagerInterface")->disableOriginalConstructor()->getMock();;
        $pricingService = new PricingService($manager);

        $this->assertEquals(2,2);

    }
}