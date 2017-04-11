<?php
/*
 * Copyright (c) 2017 Raphaël Davaillaud
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ImageOrientationFixer;

/**
 * Class ImageOrientationFixer
 * @author Raphaël Davaillaud rdavaillaud@hbs-research.com
 */
class ImageOrientationFixer
{
    const IMG_FLIP_HORIZONTAL = 1;
    const IMG_FLIP_VERTICAL = 2;
    const IMG_FLIP_BOTH = 3;
    private $image;
    private $filePathOutput;
    private $resourceImage;
    private $resourceImageFixed;

    public function __construct()
    {
    }

    /**
     * Function manager to fix orientation image
     * @return bool
     * @throws Exception
     */
    public function fix($filePathInput, $filePathOutput = false)
    {
        try {
            $this->image = new Image($filePathInput);
            $this->setFilePathOutput($filePathOutput);

            // If we don't get any exif data at all, then we may as well stop now
            if (!$this->image->getExifData() && !$filePathOutput) {
                return false;
            }

            if ($this->image->getOrientation() == 1 && !$filePathOutput) {
                return true;
            }

            // Set the GD image resource for loaded image
            $this->setResourceImage();
            // If it failed to load a resource, give up
            if (is_null($this->getResourceImage())) {
                throw new \Exception('Unable load resource image');
            }

            // Set the GD image resource fixed
            $this->setResourceImageFixed();
            if (is_null($this->getResourceImageFixed())) {
                throw new \Exception('Unable fix image');
            }

            // Save the image fixed
            return $this->saveFix();

        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Set the GD image resource for loaded image
     */
    private function setResourceImage()
    {
        $this->resourceImage = null;
        switch ($this->image->getExtension()) {
            case "png":
                $this->resourceImage = imagecreatefrompng($this->image->getFilePathInput());
                break;
            case "jpg":
            case "jpeg":
                $this->resourceImage = imagecreatefromjpeg($this->image->getFilePathInput());
                break;
            case "gif":
                $this->resourceImage = imagecreatefromgif($this->image->getFilePathInput());
                break;
        }
    }

    /**
     * @return mixed
     */
    public function getResourceImage()
    {
        return $this->resourceImage;
    }

    /**
     * Set the resource image fixed
     */
    private function setResourceImageFixed()
    {
        $this->resourceImageFixed = null;
        switch ($this->image->getOrientation()) {
            // nothing to do
            case 1:
            case null:
            case false:
                $this->resourceImageFixed = $this->getResourceImage();
                break;

            // horizontal flip
            case 2:
                $this->resourceImageFixed = $this->executeImageFlip($this->getResourceImage(), self::IMG_FLIP_HORIZONTAL);
                break;

            // 180 rotate left
            case 3:
                $this->resourceImageFixed = imagerotate($this->getResourceImage(), 180, 0);;
                break;

            // vertical flip
            case 4:
                $this->resourceImageFixed = $this->executeImageFlip($this->getResourceImage(), self::IMG_FLIP_VERTICAL);
                break;

            // vertical flip + 90 rotate right
            case 5:
                $this->resourceImageFixed = $this->executeImageFlip($this->getResourceImage(), self::IMG_FLIP_VERTICAL);
                $this->resourceImageFixed = imagerotate($this->resourceImageFixed, -90, 0);
                break;

            // 90 rotate right
            case 6:
                $this->resourceImageFixed = imagerotate($this->getResourceImage(), -90, 0);
                break;

            // horizontal flip + 90 rotate right
            case 7:
                $this->resourceImageFixed = $this->executeImageFlip($this->getResourceImage(), self::IMG_FLIP_HORIZONTAL);
                $this->resourceImageFixed = imagerotate($this->resourceImageFixed, -90, 0);
                break;

            // 90 rotate left
            case 8:
                $this->resourceImageFixed = imagerotate($this->getResourceImage(), 90, 0);
                break;
        }
    }

    /**
     * @return mixed
     */
    public function getResourceImageFixed()
    {
        return $this->resourceImageFixed;
    }

    /**
     * @param $resourceImage
     * @param int $mode - possible parameters: self::IMG_FLIP_HORIZONTAL || self::IMG_FLIP_VERTICAL || self::IMG_FLIP_BOTH
     * @return resource
     */
    private function executeImageFlip($resourceImage, $mode)
    {
        if (function_exists('imageflip')) {
            //only php >= 5.5
            imageflip($resourceImage, $mode);
        } else {
            if ($mode == self::IMG_FLIP_VERTICAL || $mode == self::IMG_FLIP_BOTH) {
                $resourceImage = $this->flipVertical($resourceImage);
            }
            if ($mode == self::IMG_FLIP_HORIZONTAL || $mode == self::IMG_FLIP_BOTH) {
                $resourceImage = $this->flipHorizontal($resourceImage);
            }
        }

        return $resourceImage;
    }

    /**
     * Flip vertical
     * @param $resourceImage
     * @return resource
     * @throws Exception
     */
    private function flipVertical($resourceImage)
    {
        $size_x = imagesx($resourceImage);
        $size_y = imagesy($resourceImage);
        $temp = imagecreatetruecolor($size_x, $size_y);
        $x = imagecopyresampled($temp, $resourceImage, 0, 0, 0, ($size_y - 1), $size_x, $size_y, $size_x, 0 - $size_y);
        if ($x) {
            return $temp;
        } else {
            throw new \Exception('Unable to flip vertical image');
        }
    }

    /**
     * Flip horizontal
     * @param $resourceImage
     * @return resource
     * @throws Exception
     */
    private function flipHorizontal($resourceImage)
    {
        $size_x = imagesx($resourceImage);
        $size_y = imagesy($resourceImage);
        $temp = imagecreatetruecolor($size_x, $size_y);
        $x = imagecopyresampled($temp, $resourceImage, 0, 0, ($size_x - 1), 0, $size_x, $size_y, 0 - $size_x, $size_y);
        if ($x) {
            return $temp;
        } else {
            throw new \Exception('Unable to flip horizontal image');
        }
    }

    /**
     * Save the new image fixed
     * @return bool
     */
    private function saveFix()
    {
        //if isset file path output the location is file path output otherwise override exist file
        $location = $this->getFilePathOutput() ? $this->getFilePathOutput() : $this->image->getFilePathInput();

        $success = false;
        switch ($this->image->getExtension()) {
            case "png":
                $success = imagepng($this->getResourceImageFixed(), $location);
                break;
            case "jpg":
            case "jpeg":
                $success = imagejpeg($this->getResourceImageFixed(), $location);
                break;
            case "gif":
                $success = imagegif($this->getResourceImageFixed(), $location);
                break;
        }

        return $success;
    }

    /**
     * @param $filePathOutput
     * @throws Exception
     */
    public function setFilePathOutput($filePathOutput = false)
    {
        $this->filePathOutput = $filePathOutput;
    }

    /**
     * @return mixed
     */
    public function getFilePathOutput()
    {
        return $this->filePathOutput;
    }


}
