<?php

namespace App\Vatri\GoogleDriveBundle\Tests\Service;

use Google_Service_Drive;
use Google_Service_Drive_FileList;
use PHPUnit\Exception;
use PHPUnit\Framework\TestCase;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

use App\Vatri\GoogleDriveBundle\Service\DriveApiService;

class DriveApiServiceTest extends TestCase
{

    private $valid_access_token;

    /**
     * @var Google_Service_Drive
     */
    private $mock_drive;

    /**
     * @var SessionInterface
     */
    private $session_mock;

    /**
     * @var ParameterBagInterface
     */
    private $parameters_mock;

    public function setUp()
    {

        $this->valid_access_token = [
            'access_token' => '123',
            'refresh_token' => '456',
            'created' => time() - (3600 + 10 * 60 + 1),
            'expires_in' => 3600
        ];

        $this->session_mock = $this->createMock(SessionInterface::class);
        $this->parameters_mock = $this->createMock(ParameterBagInterface::class);

        // Generate mock drive
        $this->session_mock->method('get')->willReturn($this->valid_access_token);

        $service = new DriveApiService($this->session_mock, $this->parameters_mock);
        $drive = $this->createMock(\Google_Service_Drive::class);

        $driveFile = $this->createMock(\Google_Service_Drive_DriveFile::class);
        $driveFile->method('getId')->willReturn('123');

        $drive->files = $this->createMock(\Google_Service_Drive_Resource_Files::class);
        $drive->files->method('copy')->willReturn($driveFile);

        $this->mock_drive = $drive;
    }

    public function testInitializationSuccess()
    {
        $service = new DriveApiService($this->session_mock, $this->parameters_mock);
        $this->assertTrue($service instanceof DriveApiService);
    }

    //   public function testInitializationSetDriver()
    //   {
    // $service = new DriveApiService($this->session_mock, $this->parameters_mock);
    //       $this->assertTrue($service->getDrive() != null);
    //   }

    public function testIsTokenExpired()
    {

        $service = new DriveApiService($this->session_mock, $this->parameters_mock);
        // Expired token:
        $this->session_mock->method('get')->willReturn($this->valid_access_token);
        $this->assertTrue($service->isTokenExpired());

        // Valid token:
        $this->session_mock->method('get')->willReturn([
            'access_token' => '123',
            'refresh_token' => '456',
            'created' => time() - 10, // secs ago.
            'expires_in' => 3600
        ]);
        $this->assertTrue($service->isTokenExpired());

        // Token not set:
        $this->session_mock->method('get')->willReturn([
            // 'access_token'  => '123',
            'refresh_token' => '456',
            'created' => time() - 10, // secs ago.
            'expires_in' => 3600
        ]);
        $this->assertTrue($service->isTokenExpired());

        // Refresh token not set:
        $this->session_mock->method('get')->willReturn([
            'access_token' => '123',
            // 'refresh_token' => '456',
            'created' => time() - 10, // secs ago.
            'expires_in' => 3600
        ]);
        $this->assertTrue($service->isTokenExpired());

    }

    public function testAuthRouteCorrect()
    {
        $service = new DriveApiService($this->session_mock, $this->parameters_mock);

        $this->assertEquals($service->getAuthRouteName(), 'vatri_google_drive_auth');
    }

    public function testSettingRedirectAfterAuthPath()
    {
        $service = new DriveApiService($this->session_mock, $this->parameters_mock);

        $res = $service->setRedirectPathAfterAuth('path/sub-path');

        $this->assertNull($res);
    }

    public function testCopyMethod()
    {
        $this->session_mock->method('get')->willReturn($this->valid_access_token);

        $service = new DriveApiService($this->session_mock, $this->parameters_mock);
        $drive = $this->createMock(\Google_Service_Drive::class);

        $driveFile = $this->createMock(\Google_Service_Drive_DriveFile::class);
        $driveFile->method('getId')->willReturn('123');

        $drive->files = $this->createMock(\Google_Service_Drive_Resource_Files::class);
        $drive->files->method('copy')->willReturn($driveFile);

        $service->setDrive($drive);

        $res = $service->copyFile('456', '123');

        $this->assertEquals( '123', $res['fileId']  );
        $this->assertEquals( '', $res['error'] );
    }

    public function testSetStarredFile()
    {
        $service = new DriveApiService($this->session_mock, $this->parameters_mock);

        $driveFile = $this->createMock(\Google_Service_Drive_DriveFile::class);
        $driveFile->method('getId')->willReturn('123');

        $files = $this->createMock(\Google_Service_Drive_Resource_Files::class);
        $files->method('update')->willReturn($driveFile);
        $this->mock_drive->files = $files;

        $service->setDrive($this->mock_drive);

        $this->assertTrue($service->setStarred('123', true));

        $files = $this->createMock(\Google_Service_Drive_Resource_Files::class);
        $files->method('update')->willThrowException(new \Exception());
        $this->mock_drive->files = $files;

        $this->assertFalse($service->setStarred('123', true));

    }


}
