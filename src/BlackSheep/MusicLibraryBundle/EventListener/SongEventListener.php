<?php
namespace BlackSheep\MusicLibraryBundle\EventListener;

use BlackSheep\MusicLibraryBundle\Events\SongEventInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

interface SongEventListener extends EventSubscriberInterface
{
    /**
     * @param SongEventInterface $songEvent;
     * @return void
     */
    public function playedSong(SongEventInterface $songEvent);

    /**
     * @param SongEventInterface $songEvent;
     * @return void
     */
    public function lovedSong(SongEventInterface $songEvent);

    /**
     * @param SongEventInterface $songEvent;
     * @return void
     */
    public function ratedSong(SongEventInterface $songEvent);
}
