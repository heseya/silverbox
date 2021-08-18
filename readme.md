# Silverbox
📦 Simple file server powered by Lumen.

[![Codacy](https://img.shields.io/codacy/grade/f5c11a249e7940c8bc3fa5b0aa64774a?style=flat-square)](https://app.codacy.com/gh/heseya/silverbox/dashboard)
[![StyleCI](https://github.styleci.io/repos/202558567/shield?branch=master)](https://github.styleci.io/repos/202558567)
[![license](https://img.shields.io/github/license/bvlinsky/cdn?color=blue&style=flat-square)](https://github.com/heseya/silverbox/blob/master/LICENSE)

## Requirements
SSH access to a server with **PHP 8.0+** and [Composer](https://getcomposer.org/).

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
