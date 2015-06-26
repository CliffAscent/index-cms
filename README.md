
# Index CMS

Index CMS is designed for rapid deployment of marked-up content or to serve as a basic website. The application is very light-weight, robust, and easy to modify.

The current version of Index CMS is **0.8.0** which is meant for *beta testing*.


## Requirements

The only requirements are a server running PHP that can read `.htaccess` or otherwise re-route all requests into `index.php`.


## Installation

The recommended installation method is to clone the application directly from the [GitHub repository] (https://github.com/CliffAscent/index-cms.git).
+ `cd /your/app/dir/`
+ `git clone https://github.com/CliffAscent/index-cms.git .`

Another method is to [download the zip package from GitHhub] (https://github.com/CliffAscent/index-cms/archive/master.zip) and unpack it into your application directory.


## Usage

### Routing and Files
The CMS will route all requests to the correct file without requiring the file extension. Supported file formats, in order of precedence, are; `.php`, `.html`, `.htm`, and `.tpl`. The request can also be routed to a method, which has precedence over displaying the file directly. This setup allows the developer to easily drop in their work and navigate to their marked-up content.

### Methods and Data
The CMS also provides several means of exposing data to the templates, such as a custom method, directly inside the template, or a combination of both. A method can expose data to `.php` templates by passing an array to `$this->display()` which will iterate through it and expose the data using the keys as variable names. Any `.php` template loaded also has access to the IndexCMS object through `$this` and the application path through the constant `BASEPATH`.

### Post Requests
Post requests will be routed to the proper method to accommodate for Ajax requests, etc. If the requested method is not located `json_encode(array('status' => 'error', 'message' => 'the message'))` will be returned and the application terminated.

### Blocked Requests
Requests to `header` or `footer` will be ignored so they can be used as partial template file includes. Requests to any of the core methods, such as `route()` or `display()`, will be sent to the `notFound()` method.

### Directories
If a directory path is provided, such as `dom.com/dir/test/`, the router will look in the `dir` directory for the file `test` and also look for the `dir_test()` method.

### Learn More
The application is very small and well documented. It's recommended that you browse through the codes commenting to get a better understanding of the possibilities.


## Modification

The application is fork and modify friendly, but provides the systems necessary to be modified externally. The application will look for, and if found, include `app.php` and not initialize the `$IndexCMS` object directly. A sample `_app.php` file is provided, which includes home and 404 method overrides and a custom test method.

It's recommended that you *do not edit* `index.php`, but instead copy and renamed `_app.php` to `app.php` and use it for all custom methods and modifications. This will allow you to update the main application and example `_app.php` file without merge conflicts.

The `header.php`, `footer.php`, `home.php`, and `404.php` are provided for your convenience. You can safely modify or remove these files, because they won't be updated.


## Support

The current version of Index CMS is **0.8.0** which is meant for *beta testing*. Versions are separated into three release groups: **major.minor.hot-fix**

Support requests and bug reports can be directed to the [GitHub issue tracker] (https://github.com/CliffAscent/index-cms/issues).

Code contributions are encouraged and can be done by cloning the repository, making your changes, and then submitting a **detailed** [pull request] (https://github.com/CliffAscent/codeigniter/pulls).
