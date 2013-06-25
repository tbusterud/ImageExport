<?php
/**
 * Created by JetBrains PhpStorm.
 * User: trond.busterud
 * Date: 6/24/13
 * Time: 2:27 PM
 * To change this template use File | Settings | File Templates.
 */

namespace ImageExport;


class Utilities {

    const ERROR_FILE_OPEN = "FILE: unable to write to '%s'.";

    /**
     * Persist content to disk
     * @param $content
     * @param $storage_folder
     * @param string $filename
     * @return string
     * @throws \ErrorException
     */
    public static function persistContent($content, $storage_folder, $filename = null)
    {
        if(is_dir($storage_folder) && is_writable($storage_folder))
        {
            if(empty($filename)) $filename = md5(time());

            $output_path = $storage_folder . DIRECTORY_SEPARATOR . $filename;

            if(($file_handle = fopen($output_path, 'w')))
            {
                fwrite($file_handle, $content);
                fclose($file_handle);
                return $output_path;
            }
            else{
                throw new \ErrorException(sprintf(self::ERROR_FILE_OPEN, $output_path));
            }
        }
    }

}