<?php
/**
 * Backend-frontend application behavior
 */
class WebApplicationEndBehavior extends CBehavior
{
    // Web application end's name.
    private $_endName;

    private $availableEnds=array('frontend','backend');
    // Getter.
    // Allows to get the current -end's name
    // this way: Yii::app()->endName;
    public function getEndName()
    {
        return $this->_endName;
    }

    // Run application's end.
    public function runEnd($name)
    {
        if(!in_array($name, $this->availableEnds)) {
            throw new CException('Wrong app');
        }
        date_default_timezone_set('UTC');

        $this->_endName = $name;

        // Attach the changeModulePaths event handler and raise it.
        $this->onModuleCreate = array($this, 'changeModulePaths');
        $this->onModuleCreate(new CEvent($this->owner));

        $this->owner->run(); // Run application.
    }

    // This event should be raised when CWebApplication
    // or CWebModule instances are being initialized.
    public function onModuleCreate($event)
    {
        $this->raiseEvent('onModuleCreate', $event);
    }

    // onModuleCreate event handler.
    // A sender must have controllerPath and viewPath properties.
    protected function changeModulePaths($event)
    {
        $event->sender->controllerPath .= DIRECTORY_SEPARATOR.$this->_endName;
        $event->sender->viewPath .= DIRECTORY_SEPARATOR.$this->_endName;
    }
}
