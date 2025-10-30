<?php

namespace NewCo\FileGateway\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FileGatewayController
{
    /**
     * Accept an image, and save it to the images/ directory on disk.
     */
    public function save(Request $request): Response
    {
        // Get the uploaded file from the request
        $uploadedFile = $request->files->get('image');

        // Some basic validation
        if (!$uploadedFile) {
            return new Response('No file uploaded.');
        }

        // Move the uploaded file to the images/ directory
        $destinationPath = __DIR__ . '/../../images/';
        $uploadedFile->move($destinationPath, $uploadedFile->getClientOriginalName());

        // Return a success response
        return new Response('File saved successfully.');
    }

}
