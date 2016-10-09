<?php

namespace MainBundle\Services;

use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\File\UploadedFile;

//Services: http://symfony.com/doc/current/service_container.html

class UrlUploader
{
    public function getImageFromUrl($url, $imageName, $maxSizeKo) {
        // One solution : http://stackoverflow.com/questions/32077772/manually-creating-an-symfony-uploadedfile
        // Check type : http://stackoverflow.com/questions/676949/best-way-to-determine-if-a-url-is-an-image-in-php
        // Down img: http://stackoverflow.com/questions/6476212/save-image-from-url-with-curl-php

        //Check size of the file before download it
        $fileSize = $this->curlGetFileSize($url);

        if($fileSize == -1) { throw new Exception('Invalid url format. Tries to upload the image from your desktop.'); }
        if($fileSize > $maxSizeKo*1024) { throw new Exception('The file is too large. Allowed maximum size is '.$maxSizeKo.' kB'); }

        //Save image in a temp folder
        $fileRaw = file_get_contents($url);
        file_put_contents('library/tmp/'.$imageName, $fileRaw);

        //Create a new uploaded file from the temp file
        $file = new UploadedFile('library/tmp/'.$imageName, $imageName, null,null,null,true);

        return $file;
    }

    /**
     * Returns the size of a file without downloading it, or -1 if the file
     * size could not be determined.
     * /!\ Doesn't work if content-Length is not defined
     *
     * Source: http://stackoverflow.com/questions/2602612/php-remote-file-size-without-downloading-file
     *
     * @param $url - The location of the remote file to download. Cannot
     * be null or empty.
     *
     * @return int The size of the file referenced by $url, or -1 if the size
     * could not be determined.
     */
    private function curlGetFileSize($url) {
        // Assume failure.
        $result = -1;

        $curl = curl_init($url);

        // Issue a HEAD request and follow any redirects.
        curl_setopt($curl, CURLOPT_NOBODY, true);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);

        $data = curl_exec($curl);
        curl_close($curl);

        if($data) {
            $content_length = "unknown";
            $status = "unknown";

            if( preg_match( "/^HTTP\/1\.[01] (\d\d\d)/", $data, $matches ) ) {
                $status = (int)$matches[1];
            }

            if( preg_match( "/Content-Length: (\d+)/", $data, $matches ) ) {
                $content_length = (int)$matches[1];
            }

            // http://en.wikipedia.org/wiki/List_of_HTTP_status_codes
            if( $status == 200 || ($status > 300 && $status <= 308) ) {
                $result = $content_length;
            }
        }

        return $result;
    }
}