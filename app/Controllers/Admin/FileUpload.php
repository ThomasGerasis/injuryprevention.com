<?php

namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use CodeIgniter\Files\File;
use App\Models\Image;

class FileUpload extends BaseController
{
    protected $helpers = ['form'];
    
    public function do_upload_image($size = 'channel')
    {
        try {
            $validationRule = [
                'file_to_upload' => [
                    'label' => 'File',
                    'rules' => 'uploaded[file_to_upload]'
                        . '|mime_in[file_to_upload,image/jpg,image/jpeg,image/gif,image/png,image/webp,image/svg,image/svg+xml]'
                        . '|max_size[file_to_upload,3072]', // Adjust max_size if needed
                ],
            ];

            if (!$this->validate($validationRule)) {
                throw new \RuntimeException(json_encode($this->validator->getErrors()));
            }

            $file = $this->request->getFile('file_to_upload');
            $original_name = $file->getClientName();
            $temp_name = pathinfo($original_name, PATHINFO_FILENAME);
            $ext = pathinfo($original_name, PATHINFO_EXTENSION);

            $fn = str_replace([' ', '\''], ['_', ''], $temp_name);

            $uploadPath = config("ImagesConfig")->site_upload_path;
            $tmpDir = $uploadPath . DIRECTORY_SEPARATOR . 'tmp';
            $targetFolder = $uploadPath . DIRECTORY_SEPARATOR . 'original_images';

            // Ensure the temporary directory exists
            if (!is_dir($tmpDir) && !mkdir($tmpDir, 0755, true)) {
                throw new \RuntimeException('Folder tmp not found or cannot be created!');
            }

            // Ensure the target directory exists
            if (!is_dir($targetFolder) && !mkdir($targetFolder, 0755, true)) {
                throw new \RuntimeException('Cannot create folder original_images');
            }

            $imgModel = model(Image::class);
            $filename = false;
            $cc = 0;
            $oname = get_permalink($fn);

            while ($filename === false) {
                $filename = $imgModel->check_random_name($ext, $oname . ($cc > 0 ? '_' . $cc : ''));
                $cc++;
            }

            // Move the uploaded file to the temporary directory
            if (!$file->move($tmpDir, $filename . '.' . $ext)) {
                throw new \RuntimeException($file->getErrorString());
            }

            $source_file = $tmpDir . DIRECTORY_SEPARATOR . $filename . '.' . $ext;
            $mimetype = $file->getClientMimeType();

            $width = null;
            $height = null;

            if (strpos($mimetype, 'image/') === 0) {
                // It's an image file
                if ($ext === 'svg') {
                    $xmlget = simplexml_load_string(file_get_contents($source_file));
                    $xmlattributes = $xmlget->attributes();
                    $width = (string) $xmlattributes->width;
                    $height = (string) $xmlattributes->height;
                } else {
                    try {
                        $info = \Config\Services::image()
                            ->withFile($source_file)
                            ->getFile()
                            ->getProperties(true);
                        $width = $info['width'] ?? null;
                        $height = $info['height'] ?? null;
                    } catch (\CodeIgniter\Images\Exceptions\ImageException $e) {
                        // Handle the exception if the file is not a supported image type
                        throw new \RuntimeException('The uploaded file is not a supported image type.');
                    }
                }
            }

            // Prepare data for database insertion
            $new_image_data = [
                'title' => $original_name,
                'file_name' => $filename . '.' . $ext,
                'mimetype' => $mimetype,
                'extension' => $ext,
                'width' => $width,
                'height' => $height,
                'added_by' => $this->session->get('loggedUser')['id'],
            ];

            $image_id = $imgModel->insert($new_image_data);
            if (empty($image_id)) {
                throw new \RuntimeException('An error occurred while saving the image data. Please try again later.');
            }

            // Rename and move the file to the final directory
            if (!rename($source_file, $targetFolder . DIRECTORY_SEPARATOR . $filename . '.' . $ext)) {
                throw new \RuntimeException('An error occurred while moving the uploaded file.');
            }

            $resp = [
                'resp' => 'ok',
                'image_id' => $image_id,
                'image_id_fl' => $filename . '.' . $ext,
                'file_name' => get_image_url($filename . '.' . $ext, $size),
            ];
        } catch (\Exception $e) {
            $resp = [
                'resp' => 'error',
                'error' => $e->getMessage(),
            ];
        }

        echo json_encode($resp);
    }
}

