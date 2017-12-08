<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class StylesPlayCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('styles:play');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $style = new SymfonyStyle($input, $output);
        $style->title('Welcome to SymfonyStyle');
        $style->section('Wow, look at this text section');
        $style->text('Lorem ipsum dolor, Lorem ipsum dolor, Lorem ipsum dolor, Lorem ipsum dolor, Lorem ipsum dolor, Lorem ipsum dolor, Lorem ipsum dolor, Lorem ipsum dolor, Lorem ipsum dolor, Lorem ipsum dolor, Lorem ipsum dolor, Lorem ipsum dolor, Lorem ipsum dolor, Lorem ipsum dolor, Lorem ipsum dolor, Lorem ipsum dolor, ');
        $style->note('Make sure you write some *real* text eventually');
        $style->comment('Lorem ipsum is just Latin garbage');
        $style->comment('So don\'t overuse it');
        $style->section('How about some BIG messages?');
        $style->success('I <3 lorem ipsum');
        $style->warning('You should *maybe* not use Lorem ipsum');
        $style->error('You should stop using lorem ipsum');
        $style->caution('STOP USING IT SRSLY!');
        $style->table(
            ['User', 'Birthday'],
            [
                ['weaverryan', 'June 5th'],
                ['leannapelham', 'All February']
            ]
        );
        $style->text('Ryan\'s favorite things:');
        $style->listing([
            'Running',
            'Pizza',
            'Watching Leanna tease Jordi Boggiano'
        ]);
    }
}
