<?php

namespace App\DataFixtures;

use App\Enum\SensorType;
use App\Entity\Module;
use App\Entity\Sensor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Finder\Finder;

class AppFixtures extends Fixture
{
    /**
     * Load fixtures.
     *
     * @param ObjectManager $objectManager
     * @return void
     */
    public function load(ObjectManager $objectManager): void
    {

        $dataFilePath = $this->getDataFilePath();

        if (!$dataFilePath) return;

        $jsonData = file_get_contents($dataFilePath);

        $data = json_decode($jsonData, true);

        $modules = $data['modules'];
        $sensors = $data['sensors'];

        for ($i = 0; $i < count($modules); $i++) {

            $module = $this->createModule($modules[$i]);

            $objectManager->persist($module);

            for ($j = 0; $j < count($sensors); $j++) {

                $sensor = $this->createSensor($sensors[$j], $module);

                $objectManager->persist($sensor);
            }
        }

        $objectManager->flush();
    }

    /**
     * Gets the `data.json` file path.
     *
     * @return string|false
     */
    private function getDataFilePath(): string|false
    {
        $files = Finder::create()->files()->name('data.json')->in(__DIR__);

        if (!$files->hasResults() || $files->count() !== 1) {
            return false;
        }

        foreach ($files as $file) {
            $absoluteFilePath = $file->getRealPath();
        }

        return $absoluteFilePath;
    }


    /**
     * Creates a `Module` from module data.
     *
     * @param [moduleData] $module
     * @return Module
     */
    private function createModule($module): Module
    {
        return (new Module())
            ->setName($module["name"])
            ->setCreatedAt(new \DateTimeImmutable($module["createdAt"]));
    }

    /**
     * Creates a `Sensor` from sensor data.
     *
     * @param [sensorData] $sensor
     * @return Sensor
     */
    private function createSensor($sensor, Module $module): Sensor
    {
        return (new Sensor())
            ->setModule($module)
            ->setUnit($sensor["unit"])
            ->setUptime(new \DateTimeImmutable($sensor["uptime"]))
            ->setDataSentCount(mt_rand(0, 100000))
            ->setStatus($sensor["status"])
            ->setCreatedAt(new \DateTimeImmutable($sensor["createdAt"]))
            ->setName(SensorType::tryFrom($sensor["type"])->value . "_" . $module->getName())
            ->setType(SensorType::tryFrom($sensor["type"]))
            ->setRandomValue();
    }
}
