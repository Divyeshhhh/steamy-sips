# steamy-sips ☕

[![Run tests](https://github.com/creme332/steamy-sips/actions/workflows/test.yml/badge.svg)](https://github.com/creme332/steamy-sips/actions/workflows/test.yml)
![Linux](https://img.shields.io/badge/Linux-FCC624?style=for-the-badge&logo=linux&logoColor=black)
![Apache](https://img.shields.io/badge/apache-%23D42029.svg?style=for-the-badge&logo=apache&logoColor=white)
![MySQL](https://img.shields.io/badge/mysql-4479A1.svg?style=for-the-badge&logo=mysql&logoColor=white)
![PHP](https://img.shields.io/badge/php-%23777BB4.svg?style=for-the-badge&logo=php&logoColor=white)

A fully-functional coffee shop website, inspired by Starbucks.
It was built from scratch without any off-the-shelf PHP framework. A high-level summary of the functionalities
includes:

- User registration and authentication
- Product browsing and search
- Product details and reviews
- Shopping cart management
- Order history and tracking
- User account management
- Administrative functions

 For more details, see the [software requirements specification](docs/SOFTWARE_SPECS.md).

The code for the admin website is found in a separate repository.

## Main features

- MVC pattern
- Dynamic routing
- Password reset with email notification
- Email notification on order
- Unit testing
- Mobile-responsive website
- Product review system with nested comments
- REST API

## Documentation

All documentation (installation instructions, usage guide, ...) is available in the [`docs`](docs) folder.

## License

The file structure of this project is an adaptation of the [`php-pds/skeleton`](https://github.com/php-pds/skeleton)
filesystem, which is
licensed under the Creative Commons
Attribution-ShareAlike
4.0 International License. Please see the [LICENSE](LICENSE) file for details on the original license.

## References

1. Product images come from https://www.starbucks.com/.
2. Icons come from https://tabler.io/icons.
3. Resources used for MVC pattern:
    - https://youtu.be/q0JhJBYi4sw?si=cTdEzzGijlG41ix8
    - https://github.com/kevinisaac/php-mvc
4. The following resources helped in defining the directory structure of the project:
    - https://github.com/php-pds/sklseleton
5. Additional references are included within the code itself.
