<?php

namespace App\Service;

use App\Interface\UniqIdentifierGeneratorInterface;
use Error;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class PlaceholderImageService {
    private string $saveDirectory;
    private UniqIdentifierGeneratorInterface $filenameGenerator;
    private string $placeholderServiceProviderUrl = "https://via.placeholder.com/";
    private int $minimumImageWidth = 150;
    private int $minimumImageHeight = 150;
    private  Hashids $hashids;

    public function __construct(Hashids $hashids) {
        $this->hashids = $hashids;
    }

    #[Required]
    public UniqIdentifierGeneratorInterface $generator;

    /*public function __construct(UniqIdentifierGeneratorInterface $generator) {
        $this->filenameGenerator = $generator;
        $this->saveDirectory = $container->get("upload.directory");
    }*/

    public function getNewImageStream(int $imageWidth, int $imageHeight): string {
        if($imageWidth < $this->minimumImageWidth || $imageHeight < $this->minimumImageHeight) {
            throw new Error("The requested image format is too small, please provide us a larger format");
        }
        $contents = file_get_contents("{$this->placeholderServiceProviderUrl}/{$imageWidth}x{$imageHeight}");
        if(!$contents) {
            throw new \Error("Placeholder image cannot be downloaded");
        }
        return $contents;
    }

    public function getNewImageAndSave(int $imageWidth, int $imageHeight, string $filename): bool {
        $file = $this->saveDirectory .$this->generator->generate();
        $contents = $this->getNewImageStream($imageWidth, $imageHeight);
        $bytes = file_put_contents($file, $contents);
        return file_exists($file) && $bytes;
    }

    #[Required]
    public function setUploadDirectory(ParameterBagInterface|string $directory): void {
        if($directory instanceof ParameterBagInterface) {
            $this->saveDirectory = $directory->get("upload.directory");
        }
        else {
            $this->saveDirectory = $directory;
        }
    }
}