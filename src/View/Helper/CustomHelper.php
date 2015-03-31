<?php
namespace GintonicCMS\View\Helper;

use Cake\View\Helper;
use Cake\Filesystem\File;

class CustomHelper extends Helper 
{
    
    public $helpers = array('Html');
    
    public function getActiveClass($controller, $actions = ['index']) 
    {
        $curAction = $this->request->params['action'];
        $curController = $this->request->params['controller'];
        if (!empty($curController) && $curController == $controller) {
            foreach($actions as $action){
                if ($curAction == $action) {
                    return 'active open';
                }
            }
        }
        return '';
    }
    
    public function getFileUrl($fileName = null,$defaultFile = DEFAULT_ADMIN_IMAGE_URL){
        if(!empty($fileName)){
            $file = new File(WWW_ROOT . '/files/uploads/' . $fileName);
            if($file->exists()){
                return '/files/uploads/' . $fileName;
            }
        }
        return $defaultFile;
    }
    
}

?>