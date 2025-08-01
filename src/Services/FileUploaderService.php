<?php

namespace App\Services;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

readonly class FileUploaderService
{

    /**
     * FileUploader constructor.
     * @param string $uploadDir
     * @param string $pathPublicDir
     * @param string $pathPublicUploadsDir complete path to default directory of uploaded files
     */
    public function __construct(
        private string $uploadDir,
        private string $pathPublicDir,
        private string $pathPublicUploadsDir,
    )
    {
    }

    /**
     * @param UploadedFile $uploadedFile the file to upload on the server
     * @param string $namespace the dir location to upload the file
     * @return string
     */
    public function uploadFile(UploadedFile $uploadedFile, string $namespace = ''): string
    {
        // définit le chemin de destination avec le namespace (sous-dossier)
        $destination = $this->pathPublicUploadsDir . $namespace;
        // récupère le nom original du fichier (sans l'extension)
        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        // génère un nouveau nom de fichier unique pour éviter les collisions et ajoute l'extension
        $newFilename = $originalFilename . '-' . uniqid() . '.' . $uploadedFile->guessExtension();
        // déplace le fichier vers le dossier de destination avec le nouveau nom
        $uploadedFile->move($destination, $newFilename);
        // retourne le chemin relatif du fichier
        return $this->uploadDir . $namespace . '/' . $newFilename;
    }

    public function deleteFileFromDisk(?string $file): void
    {
        if ($file === null) return;

        // crée une instance de Filesystem pour manipuler le système de fichiers
        $fs = new Filesystem();
        // construit le chemin absolu du fichier à supprimer
        $targetDir = $this->pathPublicDir . $file;
        // supprime le fichier
        $fs->remove($targetDir);
    }

}
