<?php
/**
 * Created by PhpStorm.
 * User: PBX_g33k
 * Date: 5/26/2017
 * Time: 12:37 PM
 */

namespace Tests\BlackSheep\MusicScannerBundle\Services;

use BlackSheep\MusicScannerBundle\Services\MediaImporter;
use BlackSheep\MusicScannerBundle\Services\SongImporter;
use League\Flysystem\Filesystem;
use League\Flysystem\Memory\MemoryAdapter;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\Finder\SplFileInfo;
use Twistor\FlysystemStreamWrapper;

class MediaImporterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MediaImporter
     */
    protected $mediaImporter;

    /**
     * @var string
     */
    protected $testDirectory;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $managerRepositoryMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $songImporterMock;

    protected function setUp()
    {
        $this->managerRepositoryMock = $managerRepositoryMock = $this->getMockBuilder(ManagerRegistry::class)->disableOriginalConstructor()->getMock();
        $this->songImporterMock = $songImporterMock = $this->getMockBuilder(SongImporter::class)->disableOriginalConstructor()->getMock();

        $this->mediaImporter = new MediaImporter($managerRepositoryMock, $songImporterMock);

        $this->testDirectory = __DIR__.'/../../../';
    }

    /**
     * @test
     */
    public function gatherFilesReturnListOfFileInfo()
    {
        $files = $this->mediaImporter->gatherFiles($this->testDirectory);

        $this->assertEquals(1, $files->files()->count());

        $this->assertArrayHasKey(__DIR__.'/../../../flac-file.flac', $files->getIterator());
        $this->assertInstanceOf(SplFileInfo::class, $files->getIterator()->current());
    }

    public function testImport()
    {
        $this->songImporterMock
            ->expects($this->once())
            ->method('importSong')
            ->with(
                $this->mediaImporter->gatherFiles($this->testDirectory)->files()->getIterator()->current()
            );

        $this->mediaImporter->import($this->testDirectory);
    }

    public function testFlySystemAdapter()
    {
        $filesystem = new Filesystem(new MemoryAdapter());
        $filesystem->put('memory-flac.flac', file_get_contents($this->testDirectory . 'flac-file.flac'));

        FlysystemStreamWrapper::register('fsotf', $filesystem);

        $this->songImporterMock
            ->expects($this->once())
            ->method('importSong')
            ->with(
                $this->mediaImporter->gatherFiles('fsotf://')->files()->getIterator()->current()
            );

        $this->mediaImporter->import($filesystem);
    }
}