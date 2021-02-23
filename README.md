# Welcome to @koromerzhin/labstag 👋

![Version](https://img.shields.io/badge/version-1.0.0-blue.svg?cacheSeconds=2592000)

![Documentation](https://img.shields.io/badge/documentation-yes-brightgreen.svg)

[![Maintenance](https://img.shields.io/badge/Maintained%3F-yes-green.svg)](https://github.com/koromerzhin/labstag/graphs/commit-activity)

<!-- ALL-CONTRIBUTORS-BADGE:START - Do not remove or modify this section -->

[![All Contributors](https://img.shields.io/badge/all_contributors-3-orange.svg?style=flat-square)](#contributors)

<!-- ALL-CONTRIBUTORS-BADGE:END -->

![Continuous Integration](https://github.com/koromerzhin/labstag/workflows/Continuous%20Integration/badge.svg?branch=develop)

> Site Internet sous Symfony

## 🏠 [Homepage](https://github.com/koromerzhin/labstag#readme)

### ✨ [Demo](https://www.letoullec.fr)

## Need

Software:

- make
- npm
- docker
- repository koromerzhin/traefikproxy

Config:

docker swarm

Hosts:

- labstag.traefik.me
- labstag.traefik.me
- mailhog-labstag.traefik.me
- mercure-labstag.traefik.me
- phpmyadmin-labstag.traefik.me
- phpldapadmin-labstag.traefik.me

## Install

```sh
make install dev
```

## after git add

```sh
make git check
```

## Replace git commit

```sh
make git commit
```

## help

```sh
make help
```

## Users

| Username   | Password | Email               | enable | Check | Lost  |
| ---------- | -------- | ------------------- | ------ | ----- | ----- |
| disable    | password | disable@email.fr    | FALSE  | TRUE  | FALSE |
| unverif    | password | unverif@email.fr    | FALSE  | FALSE | FALSE |
| lost       | password | lost@email.fr       | FALSE  | TRUE  | TRUE  |
| admin      | password | admin@email.fr      | TRUE   | TRUE  | FALSE |
| superadmin | password | superadmin@email.fr | TRUE   | TRUE  | FALSE |

## Author

👤 **Koromerzhin**

- Website: [https://www.letoullec.fr](https://www.letoullec.fr)
- Twitter: [@koromerzhin](https://twitter.com/koromerzhin)
- Github: [@koromerzhin](https://github.com/koromerzhin)
- LinkedIn: [@koromerzhin](https://linkedin.com/in/koromerzhin)

## 🤝 Contributing

Contributions, issues and feature requests are welcome! Feel free to check
[issues page](https://github.com/koromerzhin/labstag/issues). You can also take
a look at the
[contributing guide](https://github.com/koromerzhin/labstag/blob/develop/CONTRIBUTING.md).

## Show your support

Give a ⭐️ if this project helped you!

## 📝 License

Copyright © 2020 [Koromerzhin](https://github.com/koromerzhin).

This project is
[MIT](https://github.com/koromerzhin/labstag/blob/develop/LICENSE) licensed.

## ✨ Contributors

Thanks goes to these wonderful people
([emoji key](https://allcontributors.org/docs/en/emoji-key)):

<!-- ALL-CONTRIBUTORS-LIST:START - Do not remove or modify this section -->
<!-- prettier-ignore-start -->
<!-- markdownlint-disable -->
<table>
  <tr>
    <td align="center"><a href="https://github.com/koromerzhin"><img src="https://avatars0.githubusercontent.com/u/308012?v=4" width="100px;" alt=""/><br /><sub><b>Le TOULLEC Martial</b></sub></a></td>
    <td align="center"><a href="https://renovatebot.com"><img src="https://avatars0.githubusercontent.com/u/25180681?v=4" width="100px;" alt=""/><br /><sub><b>Renovate Bot</b></sub></a></td>
    <td align="center"><a href="https://github.com/apps/renovate"><img src="https://avatars1.githubusercontent.com/in/2740?v=4" width="100px;" alt=""/><br /><sub><b>renovate[bot]</b></sub></a></td>
  </tr>
</table>

<!-- markdownlint-restore -->
<!-- prettier-ignore-end -->

<!-- ALL-CONTRIBUTORS-LIST:END -->

This project follows the
[all-contributors](https://github.com/all-contributors/all-contributors)
specification. Contributions of any kind welcome!

---

_This README was generated with ❤️ by
[readme-md-generator](https://github.com/kefranabg/readme-md-generator)_
