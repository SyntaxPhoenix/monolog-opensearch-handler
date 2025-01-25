[![Contributors][contributors-shield]][contributors-url]
[![Stargazers][stars-shield]][stars-url]
[![Issues][issues-shield]][issues-url]
[![GPLv3 License][license-shield]][license-url]
 



<!-- PROJECT LOGO -->
<br />
<p align="center">
  <a href="https://github.com/SyntaxPhoenix/monolog-opensearch-handler">
    <img src="https://cdn.syntaxphoenix.com/images/logo.png" alt="Logo" width="192" height="192"/>
  </a>

  <h3 align="center">SyntaxPhoenix OpenSearchHandler for Monolog</h3>
</p>



<!-- TABLE OF CONTENTS -->
<details open="open">
  <summary><h2 style="display: inline-block">Table of Contents</h2></summary>
  <ol>
    <li>
      <a href="#about-the-project">About The Project</a>
      <ul>
        <li><a href="#built-with">Built With</a></li>
      </ul>
    </li>
    <li>
      <a href="#usage">Usage</a>
      <ul>
        <li><a href="#installation">Installation</a></li> 
        <li><a href="#monolog-versions">Monolog versions</a></li> 
      </ul>
    </li>
    <li><a href="#roadmap">Roadmap</a></li>
    <li><a href="#contributing">Contributing</a></li>
    <li><a href="#license">License</a></li>
    <li><a href="#contact">Contact</a></li>
  </ol>
</details>



<!-- ABOUT THE PROJECT -->
## About The Project
The OpenSearchHandler for [monolog](https://github.com/Seldaek/monolog) adds a handler, that is able to send logs to a [OpenSearch](https://opensearch.org/) instance. If needed it can also get integrated with Symfony. The initial version is forked from [geangontijo/monolog-opensearch-handler](https://github.com/geangontijo/monolog-opensearch-handler).


### Built With

* [geangontijo/monolog-opensearch-handler](https://github.com/geangontijo/monolog-opensearch-handler)


<!-- GETTING STARTED -->
## Usage

```php
$logger = new \Monolog\Logger('application');
$logger->pushHandler(new \SyntaxPhoenix\MonologOpenSearchHandler\OpenSearchHandler(
    'http://localhost:9200',
    'username',
    'password',
    'index_name',
    Level::Debug,
    \OpenSearch\ClientBuilder::create()->setHosts([
        'http://localhost:9200'
    ])->build()
));

$logger->info('Hello World');
```

For usage with Symfony configure the handler within your services.yaml:

```yaml
    SyntaxPhoenix\MonologOpenSearchHandler\OpenSearchHandler:
        arguments:
            $endpoint: 'opensearch:9200'
            $index: "index"
            $username: 'username'
            $password: 'password'
            $bubble: true
```

The handler can then be used within monolog.yaml:

```yaml
monolog:
    handlers:
        main:
            handler: opensearchhandler
        opensearchhandler:
            type: service
            id: SyntaxPhoenix\MonologOpenSearchHandler\OpenSearchHandler
```

### Installation

```
composer require syntaxphoenix/monolog-opensearch-handler
```


## Monolog versions

|                                   | **Monolog v1** | **Monolog v2** | **Monolog v3** |
|-----------------------------------|----------------|----------------|----------------|
| **monolog-opensearch-handler v1** |                |         ✓      |                |
| **monolog-opensearch-handler v2** |                |                |         ✓      |

<!-- ROADMAP -->
## Roadmap

See the [open issues](https://github.com/SyntaxPhoenix/monolog-opensearch-handler/issues) for a list of proposed features (and known issues).



<!-- CONTRIBUTING -->
## Contributing

Contributions are what make the open source community such an amazing place to be learn, inspire, and create. Any contributions you make are **greatly appreciated**.

1. Fork the Project
2. Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the Branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request



<!-- LICENSE -->
## License

Distributed under the GPLv3 License. See `LICENSE` for more information.



<!-- CONTACT -->
## Contact

[@SyntaxPhoenix](https://twitter.com/SyntaxPhoenix) - support@syntaxphoenix.com

Project Link: [https://github.com/SyntaxPhoenix/monolog-opensearch-handler](https://github.com/SyntaxPhoenix/monolog-opensearch-handler)





<!-- MARKDOWN LINKS & IMAGES -->
<!-- https://www.markdownguide.org/basic-syntax/#reference-style-links -->
[contributors-shield]: https://img.shields.io/github/contributors/SyntaxPhoenix/monolog-opensearch-handler.svg?style=flat-square
[contributors-url]: https://github.com/SyntaxPhoenix/monolog-opensearch-handler/graphs/contributors
[stars-shield]: https://img.shields.io/github/stars/SyntaxPhoenix/monolog-opensearch-handler.svg?style=flat-square
[stars-url]: https://github.com/SyntaxPhoenix/monolog-opensearch-handler/stargazers
[issues-shield]: https://img.shields.io/github/issues/SyntaxPhoenix/monolog-opensearch-handler.svg?style=flat-square
[issues-url]: https://github.com/SyntaxPhoenix/monolog-opensearch-handler/issues
[license-shield]: https://img.shields.io/badge/License-GPLv3-blue.svg?style=flat-square
[license-url]: https://www.gnu.org/licenses/gpl-3.0