<?php
namespace ZHMVC\B\T;
class Smarty
{
    private $smarty;
    
    function __construct()
    {
        include(ZH_PATH.DS."mod".DS."smarty".DS."Smarty.class".ZH);
        $smarty = new \Smarty();
        $smarty->setTemplateDir(ZH_PATH.'/s');
        $smarty->setCompileDir(ZH_PATH.'/smartycompile/');
        $smarty->setCacheDir(ZH_PATH.'/cache/');
        $smarty->caching = false;
        $smarty->cache_lifetime = 300;
        $smarty->left_delimiter  = '{@#';
        $smarty->right_delimiter = '#@}';
    }
    
    /**
     * @return $smarty
     */
    public function getSmarty()
    {
        return $this->smarty;
    }

    public function setSmarty($smarty)
    {
        $this->smarty = $smarty;
    }

    function __destruct()
    {}
}