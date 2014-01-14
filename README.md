CakePHP-FirstTimeSetup
======================

A Plugin to assist with deploying CakePHP apps.

Currently all it does is check whether the app is in production mode (debug set to 0),generate a salt and cipherseed using ```openssl_random_pseudo_bytes``` and replace the default ones.


To enable it, put it in ```app/Plugin/CakephpFirstTimeSetup`` and add this line to the appropriate section of ```app/Config/bootstrap.php```:
```
CakePlugin::load('FirstTimeSetup', array('bootstrap' => true));
```

TODO:
- [ ] add database configuration
- [ ] run migrations
- [ ] add authentication
- [ ] add timezone selection
