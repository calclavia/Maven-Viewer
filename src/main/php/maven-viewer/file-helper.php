<?php

/**
 * A class to generally help with files.
 * @Calclavia
 */
class FileHelper
{
    public static function getFilesInDir($path)
    {
        $files = array();

        if ($handle = opendir($path))
        {
            while (false !== ($entry = readdir($handle)))
            {
                if ($entry != "." && $entry != "..")
                {
                    $folder = $path . $entry;

                    if (!is_link($folder))
                    {
                        $files[] = $entry;
                    }
                }
            }
        }
        closedir($handle);

        return $files;
    }

    public static function parseProperties($txtProperties)
    {
        $result = array();

        $lines = explode("\n", $txtProperties);
        $key = "";

        $isWaitingOtherLine = false;
        foreach ($lines as $i => $line)
        {
            if (empty($line) || (!$isWaitingOtherLine && strpos($line, "#") === 0))
                continue;

            $value = "";

            if (!$isWaitingOtherLine)
            {
                $key = substr($line, 0, strpos($line, '='));
                $value = substr($line, strpos($line, '=') + 1, strlen($line));
            }
            else
            {
                $value .= $line;
            }

            /* Check if ends with single '\' */
            if (strrpos($value, "\\") === strlen($value) - strlen("\\"))
            {
                $value = substr($value, 0, strlen($value) - 1) . "\n";
                $isWaitingOtherLine = true;
            }
            else
            {
                $isWaitingOtherLine = false;
            }

            $result[$key] = $value;
            unset($lines[$i]);
        }

        return $result;
    }
} 