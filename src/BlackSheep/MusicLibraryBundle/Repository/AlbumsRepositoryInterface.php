<?php

namespace BlackSheep\MusicLibraryBundle\Repository;

use BlackSheep\MusicLibraryBundle\Entity\ArtistsEntity;
use BlackSheep\MusicLibraryBundle\Model\AlbumInterface;

/**
 * Interface AlbumsRepositoryInterface.
 */
interface AlbumsRepositoryInterface extends AbstractRepositoryInterface
{
    /**
     * @param ArtistsEntity $artists
     * @param               $albumName
     * @param               $extraInfo
     *
     * @return AlbumInterface|null
     */
    public function addOrUpdateByArtistAndName(ArtistsEntity $artists, $albumName, $extraInfo);

    /**
     * @param ArtistsEntity $artist
     * @param               $albumName
     *
     * @return null|AlbumInterface
     */
    public function getArtistAlbumByName(ArtistsEntity $artist, $albumName);

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getRecentAlbums();
}
