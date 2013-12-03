<?php
/**
 * PackageItem
 *
 * Model for an element or module definition with DocBlock for Clipper Package Manager
 *
 * @property stdTags array list of tags to be converted to @tag format
 * @property intTags array list of tags to be converted to @internal @tag format
 * @property code string Code of item to be stored as theitem's content
 * @method write save the defiition as a file
 */
class PackageItem {
    public $name;
    public $desc;
    public $code;
    public $definition;

    public $stdTagNames = array(
            'category',
            'version',
            'author',
            'license');

    public $intTagNames = array(
            'clpr_category',
            'guid',
            'properties',
            'events',
            'input_type',
            'caption',
            'input_options',
            'input_default',
            'output_widget',
            'output_widget_params',
            'template_assignments');

    public $stores = array(
            'chunk' => 'chunks',
            'module' => 'modules',
            'plugin' => 'plugins',
            'snippet' => 'snippets',
            'template' => 'templates',
            'tv' => 'tvs');

    function Package ($padding = '    ', $versionDefault = '0.1x') {
        $docBlock = array();

        preg_match("#<strong>(.*?)</strong>(.*)#si", $this->desc, $match);

        if ($match) {
            $desc = trim($match[2]);
            $version = $match[1];
        } else {
            $desc = trim($this->desc);
            $this->tags['version'] = $versionDefault;
            $version = $versionDefault;
        }

        $this->tags['version'] = $version;

        foreach ($this->tags as $tagName => $sTag) {
            if (!empty($sTag)) {
                if (in_array($tagName, $this->stdTagNames)) {
                    $docBlock[] = $tagName . $padding . $sTag;
                }
                else if (in_array($tagName, $this->intTagNames)) {
                    $docBlock[] = 'internal @' . $tagName . $padding . $sTag;
                } else {
                    $docBlock[] = $sTag;
                }
            }
        }

        $compDocBlock = "/**\n";
        $compDocBlock .= ' * ' . $this->name . "\n";
        $compDocBlock .= ' * ' . $desc . "\n * \n";

        foreach ($docBlock as $dBlockLine) {
            $prefix = (!empty($dBlockLine)) ? ' * @' : ' * ';
            $compDocBlock .= $prefix . $dBlockLine . "\n";
        }
        $compDocBlock .= " */\n\n";

        $this->definition = $compDocBlock . $this->code;
    }


    function Write($ext='.tpl') {
        global $exportDir;

        $fPath = $exportDir . $this->stores[$this->tags['category']] . '/';

        if (!file_exists($fPath)) {
            mkdir($fPath) or die('no can mkdir! ' . $fpath);
        }

        $fPath .= preg_replace('#[^a-z_A-Z\-0-9\s\.]#',"", $this->name . $ext);

        file_put_contents($fPath, $this->definition);

        $msg = ucfirst($this->cat) . ' ' . $this->name . ' saved as ' . "$fPath <br />\n";

        return $msg;
    }
}

?>