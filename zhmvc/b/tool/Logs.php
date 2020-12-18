<?php
namespace ZHMVC\B\TOOL;
class Logs
{

    private $log_path = ZH_PATH . '/logs/';
    private $_threshold = 3;
    private $_date_fmt = 'Y-m-d H:i:s:ms';
    private $_enabled = TRUE;
    private $_levels = array(
        'ERROR' => '1',
        'DEBUG' => '2',
        'INFO' => '3',
        'ALL' => '4'
    );

    public function __construct()
    {
        if (! is_dir($this->log_path)) {
            mkdir($this->log_path, 0777);
        }
    }

    function write_log($level = 'error', $msg, $php_error = FALSE)
    {
        if ($this->_enabled === FALSE) {
            return FALSE;
        }
        
        $level = strtoupper($level);
        
        if (! isset($this->_levels[$level]) or ($this->_levels[$level] > $this->_threshold)) {
            return FALSE;
        }
        
        $filepath = $this->log_path . 'log-' . date('Y-m-d-H') . LOG;
        $message = "\r";
        
        if (! $fp = @fopen($filepath, 'a+')) {
            return FALSE;
        }
        
        $message .= $level . ' ' . (($level == 'INFO') ? ' -' : '-') . ' ' . date($this->_date_fmt) . ' --> ' . $msg . "\n";
        
        flock($fp, LOCK_EX);
        fwrite($fp, $message);
        flock($fp, LOCK_UN);
        fclose($fp);
        
        @chmod($filepath, 0666);
        return TRUE;
    }

    public function __destruct()
    {}
}
