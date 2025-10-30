<?php

namespace NewCo\UserService\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use GuzzleHttp\Client as Client;
use NewCo\UserService\repos\FileRepository as FileRepository;
use Photo;

class UploadController
{
    private FileRepository $fileRepository;


    /**
     * Accept an image upload, extract data from the upload, and pass the image to
     * localhost:4000 for storage.
     */
    public function upload(Request $request): Response
    {
        // Get the uploaded file from the request
        $uploadedFile = $request->files->get('image');

        // Some basic validation
        if (!$uploadedFile) {
            return new Response('No file uploaded.');
        }

        // Grab the file data from the image
        $pathname = $uploadedFile->getPathname();
        $filename = $uploadedFile->getClientOriginalName();
        $exifData = exif_read_data($pathname); // Read metadata

        // connect to DB using the usual PDO prepare/execute approach, save the file info. Note that the DB schema is in database/schema.sql.
        $this->fileRepository = new FileRepository();
        $photo = new Photo();
        $photo->setFilename($filename);
        $photo->setMetadata(json_encode($exifData));
        $this->fileRepository->save($photo);

        // use Guzzle to send a request from the user service to the file service

        $client = new Client();
        $client->post('http://localhost:4000/save', [
            'multipart' => [
                [
                    'name' => 'image',
                    'contents' => fopen($pathname, 'r'),
                    'filename' => $uploadedFile->getClientOriginalName(), // original name
                ],
            ],
        ]);
        
        // Return the response
        return new Response('File uploaded successfully with ID: ' . $photo->getId());
    }

}
