<?php

namespace BlackSheep\MusicScannerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Commando: music import.
 */
class ImportMusicCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('music_scanner:import_music_command')
            ->setDescription('Hello PhpStorm')
            ->addArgument('path', InputArgument::REQUIRED, 'Mediapath, ie: /Volumes/Data/Stack/Music')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $importer = $this->getContainer()->get('black_sheep_music_scanner.services.media_importer');
        $importer->setOutputInterface($output, true);
        $importer->import($input->getArgument('path'));
    }
}
