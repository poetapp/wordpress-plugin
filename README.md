# Po.et WordPress Plugin

[![Join the chat at https://gitter.im/poetapp/Lobby](https://badges.gitter.im/poetapp/Lobby.svg)](https://gitter.im/poetapp/Lobby)

The Po.et WordPress Plugin allows you to automatically submit your blog posts to Po.et. Once you install and configure it, every time you post a new blog entry it will automatically be posted through Po.et's Frost API to the Po.et Network.

## How to Install

### From the WordPress Plugin Directory

The Official Po.et WordPress Plugin can be found here: https://wordpress.org/plugins/po-et/

### From this repository

Go to the [releases](https://github.com/poetapp/wordpress-plugin/releases) section of the repository and download the most recent release.

Then, from your WordPress administration panel, go to `Plugins > Add New` and click the `Upload Plugin` button at the top of the page.

### From source

You will need Phing and Git installed to build from source. To complete the following steps, you will need to open a command line terminal.

Clone the Github repository:  

`git clone https://github.com/poetapp/wordpress-plugin.git`

From within the checked out repository, run:  

`phing package`

The packaged zip file will be available under the build directory which is created under your locally cloned git repository.

## How to Use

From your WordPress administration panel go to `Plugins > Installed Plugins` and scroll down until you find `Po.et`. You will need to activate it first, then click on `Settings` to configure it.

### Configuration

#### Author Name

This field will be used to set the `author` field on the Po.et Claim that is written to the Po.et Network. Set it to your name, pen name, blog's name, etc.

#### API URL

The URL of the Frost API, which is: https://api.frost.po.et/works

#### Token

You will need an API token, which you can obtain here: https://frost.po.et

#### Post articles to API automatically on insert or update?	

Use this checkbox to enable or disable automatic posting.

## Badge

Place the shortcode `[poet-badge]` anywhere in your article to display the Po.et Badge as proof that the article was timestamped on the Po.et Network and anchored to the Bitcoin blockchain.

## Developers

The Po.et WordPress plugin welcomes additions from developers. To ensure code is consistent and that all developers can focus on building great features rather than deciphering various coding styles, the Po.et WordPress Plugin adheres to the WordPress coding standards.

To ensure your code is formatted the WordPress way, you can run the PHP code sniffer using the WordPress coding ruleset. There are various ways to install both PHPCS and the WordPress coding ruleset, but the easiest method is to install it globally using Composer. Assuming you have Composer and PHPCS installed and available from the command line, run the following from a terminal (assuming you are on Linux or a *nix derivative):

```
composer global require wp-coding-standards/wpcs
phpcs --config-set installed_paths ~/.composer/vendor/wp-coding-standards/wpc
```

It is also assumed you have Composer installed in your home directory, so you may need to adjust the `installed_paths` directive to match your Composer installation directory.
