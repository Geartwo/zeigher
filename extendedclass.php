<?php
//Extension class
class ExtendClass {
    public function __call($method, $args)
    {
        if(isset($this->$method)):
            $func = $this->$method;
            return call_user_func_array($func, $args);
        elseif(isset($this->standard)):
            $func = $this->standard;
            return call_user_func_array($func, $args);
        endif;
    }
}
//Extend StdClass class
class ExtendStdClass {
    public function __get($key){
        if(isset($this->$key)):
                return $this->$key;
        elseif(isset($this->standard)):
                return $this->standard;
        else:
                return "NoDefault";
        endif;
    }
}
