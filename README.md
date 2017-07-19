Packager v0.5 
========
13 Dec 2013

Tools to help create files and packages for the Clipper CMS Package Manager

Packager is a module for Clipper CMS. It creates files that define Clipper elements (templates, template variables, chunks, snippets and plugins) and modules, in the format expected by the Package Manager that has been part of Clipper since v1.2.5. 

Packager scans related assets subfolders (assets/modules, assets/plugins, assets/snippets, assets/templates and assets/tvs) for custom files and copies them, with full paths, to the output "files" folder.

The ignore.xml file lists elements, modules and folders that should not be included in the output. The supplied file specifies those is in the standard Clipper distribution.

You can install Packager from the Package Manager, or by manually copying the files and creating a new Module from the body of the packager.tpl file. 

When you run the module, you can supply:
- a name for your package (normal text and punctuation are OK), 
- a location to store the output under a folder with name based on the package name
-- leave blank to create the package under assets/site
-- start name with assets/<folder> and full path will be automatically created ** FOLDER MUST EXIST **
-- provide a full pathname to an existing folder to store elsewhere
- a version number. This will be added to elements that don't have one (Package Manager requires a version number for ALL elements and modules)

Click Proceed, and off you go.

To complete your package, add a README or README.txt file to the root, update the changelog.txt, add the install.php file if there is one, and copy in subsidiary folders and files not copied automatically. Add contents of the package (not the package folder itself) to a Zip file, upload to the repository and wait for the compliments, offers of money, marriage and free dinners to roll in.
