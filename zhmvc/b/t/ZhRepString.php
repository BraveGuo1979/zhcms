<?php
namespace ZHMVC\B\T;
class ZhRepString
{

    function __construct()
    {
        
    }
    
    public function getRepString($searchString,$reString,$sourceString)
    {
        $reS="";
        //对$restring进行判断
        if(substr_count($reString,".")>0)
        {
            //含有.
        }
        elseif(substr_count($reString,"+")>0)
        {
            
        }
        elseif(substr_count($reString,"-")>0)
        {
        
        }
        elseif(substr_count($reString,"*")>0)
        {
        
        }
        else
        {
            $reS=str_replace($searchString,$reString,$sourceString);
        }
        return $reS;
    }
    
}
