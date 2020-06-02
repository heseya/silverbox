# Silverbox
Simple file server powered by Lumen.

[![Codacy](https://img.shields.io/codacy/grade/2c764b31cfb4487d87b4000a995c54af?style=flat-square)](https://app.codacy.com/project/bvlinsky/cdn/dashboard)
[![StyleCI](https://github.styleci.io/repos/202558567/shield?branch=master)](https://github.styleci.io/repos/202558567)
[![gitmoji badge](https://img.shields.io/badge/gitmoji-%20😜%20😍-FFDD67.svg?style=flat-square)](https://github.com/carloscuesta/gitmoji)
[![license](https://img.shields.io/github/license/bvlinsky/cdn?color=blue&style=flat-square)](https://github.com/bvlinsky/cdn/blob/master/LICENSE)

## Requirements
SSH access to a server with **PHP 7.3+** and [Composer](https://getcomposer.org/).

## Instalation
```
composer create-project heseya/silverbox -s dev
```

Create API client
```
php silverbox clients:add {clientName}
```

Show list of all clients
```
php silverbox clients:show
```

## License
Silverbox is licensed under the [MIT License](https://github.com/heseya/silverbox/blob/master/LICENSE).
