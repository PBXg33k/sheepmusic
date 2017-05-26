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
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\Finder\SplFileInfo;
use org\bovigo\vfs;

class MediaImporterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MediaImporter
     */
    protected $mediaImporter;

    /**
     * @var vfs\vfsStreamDirectory
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

        $this->testDirectory = vfs\vfsStream::setup('music');
        $this->testDirectory->addChild((new vfs\vfsStreamFile('test-file.flac')));
    }

    /**
     * @test
     */
    public function gatherFilesReturnListOfFileInfo()
    {
        $files = $this->mediaImporter->gatherFiles($this->testDirectory->url());

        $this->assertEquals(1, $files->files()->count());
        $this->assertArrayHasKey('vfs://music/test-file.flac', $files->getIterator());
        $this->assertInstanceOf(SplFileInfo::class, $files->getIterator()->current());
    }

    public function testImport()
    {
        $this->songImporterMock
            ->expects($this->once())
            ->method('importSong')
            ->with(
                $this->mediaImporter->gatherFiles($this->testDirectory->url())->files()->getIterator()->current()
            );

        $this->mediaImporter->import($this->testDirectory->url());
    }
}
