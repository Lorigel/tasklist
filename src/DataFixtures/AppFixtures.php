<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Location;
use App\Entity\Auditor;
use app\Entity\Job;
use Symfony\Component\String\ByteString;

class AppFixtures extends Fixture
{
    /**
     * Inserts demo data into the database
     */
    public function load(ObjectManager $manager): void
    {
        //insert locations and one user for each location and jobs
        foreach ($this->locations() as $item) {
            $location = new Location();
            $location->setName($item['name']);
            $location->setSlug($item['slug']);
            $location->setTimezone($item['timezone']);
            $manager->persist($location);

            $manager->flush();

            //user
            $auditor = new Auditor();
            $auditor->setName(ByteString::fromRandom(6)->toString());
            $auditor->setLocation($location);
            $manager->persist($auditor);

            //jobs
            for ($i = 0; $i < 3; $i++) {
                $job = new Job();
                $job->setLocationId($location);
                $job->setTitle('Job_' . ByteString::fromRandom(6)->toString());
                $job->setDescription(ByteString::fromRandom(40)->toString());
                $job->setScheduledDate((new \DateTime())->setTimezone(new \DateTimeZone('UTC'))->modify('+ ' . $i . ' hours'));

                $manager->persist($job);
            }
        }

        $manager->flush();
    }

    /**
     * Locations data
     */
    private function locations()
    {
        return [
            [
                'name' => 'Madrid',
                'slug' => 'madrid',
                'timezone' => 'Europe/Madrid'
            ],
            [
                'name' => 'Mexico City',
                'slug' => 'mexico_city',
                'timezone' => 'America/Mexico_City'
            ],
            [
                'name' => 'United Kingdom',
                'slug' => 'united_kingdom',
                'timezone' => 'Europe/London'
            ],
        ];
    }
}