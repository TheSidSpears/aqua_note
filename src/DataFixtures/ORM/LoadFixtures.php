<?php

namespace App\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Nelmio\Alice\Fixtures;

class LoadFixtures implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        Fixtures::load(
            __DIR__ . '/fixtures.yml',
            $manager,
            [
                'providers' => [$this]
            ]
        );
    }

    public function genus()
    {
        $genera = [
            'Octopus',
            'Balaena',
            'Orcinus',
            'Hippocampus',
            'Asterias',
            'Amphiprion',
            'Carcharodon',
            'Aurelia',
            'Cucumaria',
            'Balistoides',
            'Paralithodes',
            'Chelonia',
            'Trichechus',
            'Eumetopias'
        ];
        $key = array_rand($genera);
        return $genera[$key];
    }

    public function subFamily()
    {
        $subFamilies = [
            'Scombridae',
            'Catostomidae',
            'Poeciliidae',
            'Alosinae',
            'Cynodontinae',
            'Leuciscinae',
            'Myxinidae',
            'Petromyzontidae',
            'Setarchidae',
            'Neosebastidae',
            'Scorpaenidae',
            'Apistidae',
            'Tetrarogidae',
            'Synanceiidae'
        ];
        $key = array_rand($subFamilies);
        return $subFamilies[$key];
    }
}