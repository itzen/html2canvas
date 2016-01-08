<?php

/**
 * MediaElement
 *
 * This ext allow you to add HTML5 audio and video player using mediaElement JS library to your Yii project.
 *
 * @version 1.0
 * @author Shiv Charan Panjeta <shiv@toxsl.com> <shivcharan.panjeta@outlook.com>
 */
/**
 *
 */

Yii::import('zii.widgets.CPortlet');

class Html2CanvasPortlet extends CPortlet
{
    public $linkName = 'Eksportuj';
    public $selector = 'body';
    public $htmlOptions = array();

    public $scriptUrl = null;
    public $scriptFile = array('html2canvas.min.js');

    protected function registerScriptFile($fileName, $position = CClientScript::POS_HEAD)
    {
        Yii::app()->clientScript->registerScriptFile($this->scriptUrl . '/' . $fileName, $position);
    }


    protected function resolvePackagePath()
    {
        if ($this->scriptUrl === null) {
            $basePath = __DIR__ . '/assets';
            $baseUrl = Yii::app()->getAssetManager()->publish($basePath);
            if ($this->scriptUrl === null)
                $this->scriptUrl = $baseUrl . '';
        }
    }

    protected function registerCoreScripts()
    {
        $cs = Yii::app()->getClientScript();
        $cs->registerCoreScript('jquery');

        if (is_string($this->scriptFile))
            $this->registerScriptFile($this->scriptFile);
        else if (is_array($this->scriptFile)) {
            foreach ($this->scriptFile as $scriptFile)
                $this->registerScriptFile($scriptFile);
        }
    }

    public function init()
    {
        $this->resolvePackagePath();
        $this->registerCoreScripts();
    }


    public function run()
    {
        if (isset($this->htmlOptions['id']))
            $this->id = $this->htmlOptions['id'];
        else
            $this->htmlOptions['id'] = $this->id;

        echo CHtml::tag('a',
            $this->htmlOptions,
            $this->linkName
        );

        $js = <<<JS
        $('body').on('click', '#'.{$this->id}', function(){
            html2canvas({$this->selector}).then(function(canvas) {
                document.body.appendChild(canvas);
            });
        });
JS;

        Yii::app()->clientScript->registerScript('html2canvas', $js);

    }

}