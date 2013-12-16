<?php
/**
 * PackageItem
 *
 * Model for an element or module definition with DocBlock for Clipper Package Manager
 *
 * @property name string name of element/module
 * @property desc description field (incoming may include version as HTML tag)
 * @property code string Code of item to be stored as the item's content
 * @property code string Code of item to be stored as the item's content
 * @property definition string text content to be written as file defining the item for package manager
 * @parameter versionDefault string Sets version number if item does not have one. Package manager REQUIRES version
 * @property tags array tags applying to current item
 * @property stdTagNames array list of tags to be converted to @tag format
 * @property intTagNames array list of tags to be converted to @internal @tag format
 * @method Package Create DocBlock for item, incorporating all tags
 * @method Write Save the definition as a file. Returns message as string to advise of save
 */

class PackageItem {
    public $name;
    public $desc;
    public $code;
    public $definition;
    public $tags = array();

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

    function __construct($cat, $name, $desc, $versionDefault = '0.1') {
        $this->tags['category'] = $cat;
        $this->name = $name;

// extract version umber, if it exists, from HTML STRONG tag
        preg_match("#<strong>(.*?)</strong>(.*)#si", $desc, $match);

        if ($match) {
            $desc = trim($match[2]);
            $version = $match[1];
        } else {
            $desc = trim($desc);
            $version = $versionDefault;
        }

        $this->desc = $desc;
        $this->tags['version'] = $version;
    }

    function Package ($padding = '    ') {
        $docBlock = array();

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
        $compDocBlock .= ' * ' . $this->desc . "\n * \n";

        foreach ($docBlock as $dBlockLine) {
            $prefix = (!empty($dBlockLine)) ? ' * @' : ' * ';
            $compDocBlock .= $prefix . $dBlockLine . "\n";
        }
        $compDocBlock .= " */\n\n";

        $this->definition = $compDocBlock . $this->code;
    }

    function Write($ext='.tpl') {
        global $packageDir;

        $fPath = $packageDir . $this->stores[$this->tags['category']] . '/';

        if (!file_exists($fPath)) {
            $old_umask = umask(0);
            mkdir($fPath, 0777, true) or die('no can mkdir! ' . $fpath);
            umask($old_umask);
        }

        $fPath .= preg_replace('#[^a-z_A-Z\-0-9\s\.]#',"", $this->name . $ext);

        file_put_contents($fPath, $this->definition);

        $msg = ucfirst($this->tags['category']) . ' ' . $this->name . ' saved as ' . "$fPath <br />\n";

        return $msg;
    }

// sanitize package name into folder name
    public static function SetPackageDirName($pkgName) {
        $dir = preg_replace('#[^a-zA-Z0-9\s\-]#',"",$pkgName);
        $dir = trim(strtolower($dir));
        $dir = preg_replace('#\s+#','-',$dir);
        return $dir;
    }

    /**
     * Recursively copy files from one directory to another
     *
     * http://ben.lobaugh.net/blog/864/php-5-recursively-move-or-copy-files
     *
     * @param String $src - Source of files being moved
     * @param String $dest - Destination of files being moved
     */
    public static function rCopy($src, $dest) {

        // If source is not a directory stop processing
        if(!is_dir($src)) return false;

        // If the destination directory does not exist create it
        if(!is_dir($dest)) {
            if(!mkdir($dest, 0777, true)) {
            // If the destination directory could not be created stop processing
                return false;
            }
        }

        // Open the source directory to read in files
        $i = new DirectoryIterator($src);

        foreach($i as $f) {

            if($f->isFile()) {
                // KP: preserve modification time
                $t = $f->getMTime();
                $d = "$dest/" . $f->getFilename();
                copy($f->getRealPath(), $d);
                touch($d, $t);
                }
            else if(!$f->isDot() && $f->isDir()) {
                self::rCopy($f->getRealPath(), "$dest/$f");
            }
        }
    }
}
?>
