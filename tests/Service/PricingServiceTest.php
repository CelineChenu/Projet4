<?php


namespace App\Tests\Service;


use App\Entity\Command;
use App\Entity\Tariff;
use App\Repository\TariffRepository;
use App\Service\PricingService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class PricingServiceTest extends TestCase
{

    private function createTarif($constant,$min, $max, $value)
    {
        $tarif = new Tariff();
        $tarif->setConstant($constant);
        $tarif->setMinVal($min);
        $tarif->setMaxVal($max);
        $tarif->setValue($value);

        return $tarif;

    }


    public function testGetConstant()
    {
        //$manager = $this->getMockBuilder(EntityManagerInterface::class)->disableOriginalConstructor()->getMock();

        $manager = $this->createMock(EntityManagerInterface::class);

        $tarifRepo = $this->createMock(TariffRepository::class);

        $manager->method('getRepository')->willReturn($tarifRepo);

        $tarifRepo->method('findAll')->willReturn([
            $this->createTarif("FREE_PRICE",0,4,8.0),
            $this->createTarif("CHILD_PRICE",4,12,8.0),
            $this->createTarif("ADULT_PRICE",12,60,16.0),
            $this->createTarif("SENIOR_PRICE",60,200,12.0),
        ]);

        $pricingService = new PricingService($manager);
        
        $this->assertEquals($pricingService->getConstant(3), "FREE_PRICE");
        $this->assertEquals($pricingService->getConstant(4), "CHILD_PRICE");
        $this->assertEquals($pricingService->getConstant(12), "ADULT_PRICE");
        $this->assertEquals($pricingService->getConstant(60), "SENIOR_PRICE");

    }
}