/**
 * Packager
 * Tools for creating Clipper Package Manager filesets
 * 
 * @category    module
 * @version    0.4
 * @internal @clpr_category    Manager and Admin
 * @internal @guid    a0697d6405c01c513d330fad0ee79cd9
 * @internal @properties    &packageName=Package name;string; &nameFormat=Auto name format;string;%y%m%d-%H%M &packageVersion=Version ;string;0.1a &exportDir=Export to;string;assets/site/
 */

require $modx->config['base_path'] . 'assets/modules/packager/packageItem.class.inc.php';
if (file_exists($modx->config['base_path'] . 'assets/modules/packager/ignore.xml')) {
    $ignoreList = file_get_contents($modx->config['base_path'] . 'assets/modules/packager/ignore.xml');
}

if (empty($exportDir)) {
    $exportDir = 'assets/site/';
}

if (empty($nameFormat)) {
    $nameFormat = '%y-%m-%d-%H%M';
}

if (empty($packageName)) {
    $packageName = 'Pkg-' . strftime($nameFormat);
} 

require $modx->config['base_path'] . 'assets/modules/packager/prepack.inc.php';
echo $output;
