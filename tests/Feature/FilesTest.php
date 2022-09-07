<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Files;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;

class FilesTest extends TestCase
{
    use RefreshDatabase;

    public function test_file_is_pdf()
    {
        $filePath = public_path(Config::get('const.files.tests'));
        $sizeInBytes = 2097152;
        $document = UploadedFile::fake()->create($filePath.'/test.pdf', $sizeInBytes);
        $this->assertEquals('pdf',$document->getClientOriginalExtension());
    }

    public function test_file_is_file_size_is_less_than_2_mega_bytes(){
        $filePath = public_path(Config::get('const.files.tests'));
        $sizeInBytes = 2097152;
        $document = UploadedFile::fake()->create($filePath.'/test.pdf', $sizeInBytes);
        $this->assertGreaterThanOrEqual($sizeInBytes,$document->getSize()/1024);
    }

    public function test_file_is_file_exists(){
        $filePath = public_path(Config::get('const.files.tests'));
        $sizeInBytes = 2097152;
        UploadedFile::fake()->create($filePath.'/test.pdf', $sizeInBytes);
        $this->assertFileExists($filePath.'/test.pdf');
    }
}
