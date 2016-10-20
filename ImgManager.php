<?php


class ImgManager
{
    const IMG_GIF = 1;
    const IMG_JPEG = 2;
    const IMG_PNG = 3;

    /**
     * @param $file
     * @return true
     * @throws Exception
     */
    private function checkImage($file)
    {
        if ($imageInfo = @getimagesize($file)) {
            switch ($imageInfo[2]) {
                case self::IMG_GIF:
                    if (imagecreatefromgif($file)) {
                        return true;
                    } else {
                        throw new \Exception('Invalid image gif format');
                    }
                case self::IMG_JPEG:
                    if (imagecreatefromjpeg($file)) {
                        return true;
                    } else {
                        throw new \Exception('Invalid image jpeg format');
                    }
                case self::IMG_PNG:
                    if (imagecreatefrompng($file)) {
                        return true;
                    } else {
                        throw new \Exception('Invalid image png format');
                    }
                default:
                    throw new \Exception('Not supported image format');
            }
        } else {
            throw new \Exception('Invalid image file');
        }
    }

    /**
     * @param $localPath
     * @param $name
     * @throws Exception
     */
    private function checkDir($localPath, $name)
    {
        if (!file_exists($localPath)) {
            mkdir($localPath, 0777, true);
        }elseif(file_exists($localPath . '/' . $name)){
            throw new \Exception('File with the same name already exist');
        }
    }

    /**
     * @param $file
     * @param $localPath
     * @param $name
     */
    private function curlSaveImg($file, $localPath, $name)
    {
        $ch = curl_init($file);
        $fp = fopen($localPath . '/' . $name, 'wb');
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);
    }

    /**
     * @param $file
     * @param $localPath
     * @throws Exception
     */
    public function save($file, $localPath)
    {
        if(!empty($file)) {
            $name = basename($file);
            $this->checkImage($file);
            $this->checkDir($localPath, $name);
            $this->curlSaveImg($file, $localPath, $name);
        } else {
            throw new \Exception('Invalid image format for save');
        }
    }
}