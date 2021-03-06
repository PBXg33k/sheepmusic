<?php

namespace BlackSheep\MusicLibraryBundle\Entity;

use BlackSheep\MusicLibraryBundle\Model\ArtistInterface;
use BlackSheep\MusicLibraryBundle\Model\PlaylistInterface;
use BlackSheep\MusicLibraryBundle\Model\Song;
use BlackSheep\MusicLibraryBundle\Model\SongInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(indexes={
 *     @ORM\Index(name="album_index", columns={"album_id"} ),
 *      @ORM\Index(name="index_import", columns={"m_time"} ),
 *     @ORM\Index(name="index_create", columns={"created_at"}),
 *     @ORM\Index(name="index_update", columns={"updated_at"})
 * }))
 * @ORM\Entity(repositoryClass="BlackSheep\MusicLibraryBundle\Repository\SongsRepository")
 */
class SongEntity extends Song implements SongInterface
{
    use BaseEntity;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $track;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $title;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    protected $length;

    /**
     * @ORM\Column(type="bigint")
     */
    protected $mTime;

    /**
     * @ORM\Column(type="text",length=4096)
     */
    protected $path;

    /**
     * @ORM\ManyToOne(targetEntity="AlbumEntity", inversedBy="songs")
     */
    protected $album;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $playCount;

    /**
     * @ORM\ManyToMany(targetEntity="PlaylistEntity", mappedBy="songs" , fetch="EXTRA_LAZY",cascade={"persist"})
     */
    protected $playlists;

    /**
     * @ORM\ManyToMany(targetEntity="ArtistsEntity", inversedBy="songs", fetch="EXTRA_LAZY")
     * @ORM\JoinTable(
     *     name="ArtistSongs",
     *     joinColumns={@ORM\JoinColumn(name="songs_id", referencedColumnName="id", nullable=true)},
     *     inverseJoinColumns={@ORM\JoinColumn(name="artists_id", referencedColumnName="id", nullable=true)}
     * )
     */
    protected $artists;

    /**
     * Compose a song (pun intented).
     */
    public function __construct()
    {
        $this->artists = new ArrayCollection();
        $this->playlists = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function addArtist(ArtistInterface $artist)
    {
        if ($this->artists->contains($artist) === false) {
            $this->artists->add($artist);
        }
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addPlaylist(PlaylistInterface $playlist)
    {
        if ($this->playlists->contains($playlist) === false) {
            $this->playlists->add($playlist);
        }
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removePlaylist(PlaylistInterface $playlist)
    {
        if ($this->playlists->contains($playlist)) {
            $this->playlists->removeElement($playlist);
        }
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public static function createFromArray($songInfo)
    {
        $song = new static();
        $song->setTrack($songInfo['track']);
        $song->setTitle($songInfo['title']);
        $song->setLength($songInfo['length']);
        $song->setMTime($songInfo['mTime']);
        $song->setPath($songInfo['path']);

        return $song;
    }
}
