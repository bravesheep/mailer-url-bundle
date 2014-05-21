# BravesheepMailerUrlBundle
A Symfony2 bundle for parsing the contents of a url that specifies which mailer to use.

## Installation and configuration
Using [Composer][composer] add the bundle to your dependencies using the require command:
`composer require bravesheep/mailer-url-bundle:dev-master`.

### Add the bundle to your AppKernel
Add the bundle in your `app/AppKernel.php`. **Note**: in order for the parameters defined by this bundle to be picked
up by Swiftmailer, you need to include this bundle before including the 
`Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle` bundle.

```php
public function registerBundles()
{
    return array(
        // ...
        new Bravesheep\MailerUrlBundle\BravesheepMailerUrlBundle(),
        // ...
    );
}
```

### Configure which urls should be rewritten to parameters
For this bundle to work you need to specify which urls need to be rewritten to basic parameters. This bundle can handle
any number of urls by configuring the correct properties under `bravesheep_mailer_url.urls`. Take a look at this
example configuration:

```yaml
bravesheep_mailer_url:
    urls:
        default:
            url: %mailer_url%
            prefix: mailer_
```

In this case we take the value of the `mailer_url` parameter and create parameters from it prefixed with `mailer_`.

## Usage
Take a look at this `parameters.yml.dist` which is distributed by the Symfony2 Standard Edition:

```yaml
parameters:
    database_driver: pdo_mysql
    database_host: 127.0.0.1
    database_port: ~
    database_name: symfony
    database_user: root
    database_password: ~

    mailer_transport: smtp
    mailer_host: 127.0.0.1
    mailer_user: ~
    mailer_password: ~

    locale: en
    secret: ThisTokenIsNotSoSecretChangeIt

    debug_toolbar: true
    debug_redirects: false
    use_assetic_controller: true
```

As you can see we need 4 parameters to specify the smtp settings, and that doesn't even include setting the port, 
encryption or authentication methods in the case of SMTP (which might actually vary on different environments). It 
would be nice if we could reduce the number of parameters required and specify which mailer to use by specifying a 
single URL:

```yaml
parameters:
    database_driver: pdo_mysql
    database_host: 127.0.0.1
    database_port: ~
    database_name: symfony
    database_user: root
    database_password: ~

    mailer_url: smtp://127.0.0.1

    locale: en
    secret: ThisTokenIsNotSoSecretChangeIt

    debug_toolbar: true
    debug_redirects: false
    use_assetic_controller: true
```

Still easily readable, but a lot more concise. The BravesheepMailerUrlBundle can do exactly this. Given the
configuration as shown in the previous section and this configuration the bundle uses `mailer_url` to create the 
`mailer_transport` and `mailer_host` with the correct data.

In general this bundle takes any valid mailer url and creates the following parameters, prefixed with the specified
prefix: `transport`, `host`, `port`, `user`, `password`, `encryption` and `auth_mode`.

### Accepted URLs
URLs are generally formatted in `scheme://user:password@host:port` format. The following schemes are understood:

* `smtp` for basic SMTP
* `smtp+ssl` or `ssl+smtp` for SMTP with SSL encryption
* `smtp+tls` or `tls+smtp` for SMTP with TLS encryption
* `gmail` for the GMail mail transport provided by Symfony
* `mail` for using the internal `mail()` function in PHP
* `sendmail` for using the sendmail binary your system provides

The `encryption` (besides using the scheme, which is preferred) and `auth_mode` parameters can be specified via query 
parameters, for example: `smtp://user:pass@localhost/?encryption=tls&auth_mode=plain`. Valid values for `auth_mode` are
`plain`, `login` and `cram-md5`.

Gmail URLs may be specified by leaving the everything after the authentication string out, for example:
`gmail://user@gmail.com:password`. For mail and sendmail you can use: `mail://` and `mail`, and `sendmail://` and 
`sendmail` respectively.
