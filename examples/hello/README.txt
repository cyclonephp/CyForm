Post-installation steps:

Set up the following route in your index.php BEFORE the default route:

req\Route::set('cyform', 'cyform(/<controller>(/<action>))')
        ->defaults(array(
        'controller' => 'hellocyform',
        'action' => 'index',
        'namespace' => 'app\\controller\\cyform'
    ));

Point your browser to http://localhost/cyclonephp/cyform
To browse to source read the app/classes/app/controller/cyform/HellocyformController.php file.