<?php

namespace SuperSimpleLogging;

use Psr\Log\AbstractLogger;

class FileLogger extends AbstractLogger
{
    private $file;

    public function __construct($path)
    {
        $this->file = new \SplFileObject($path, "a");
    }

    public function log($level, $message, array $context = array())
    {
        $type = strtoupper($level);
        $time = date("T:y-m-d\TH:i:s");
        $content = $this->interpolate($message, $context);
        $this->file->fwrite(
           "[$type] - [$time] - $content" . PHP_EOL
        );
    }

    private function interpolate($message, array $context)
    {
        $replace = array(
            PHP_EOL => " [n] "
        );
        foreach ($context as $key => $val) {
            if ($this->canBeStringified($val)) {
                $replace['{'.$key.'}'] = $val;
            }
        }
        return strtr($message, $replace);
    }

    private function canBeStringified($val)
    {
        return !is_array($val) && (!is_object($val) || method_exists($val, '__toString'));
    }
}
