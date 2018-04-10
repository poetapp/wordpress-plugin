# Po.et WordPress Plugin
The Po.et WordPress Plugin allows you to automatically submit your blog posts to Frost. All you need to do is install and configure it, then every time you post a new blog entry it'll automatically be posted to Frost.

## How to Install

### From source

You will need Phing and Git installed to build from source.

To complete the following steps, you will need to open a command line terminal.

Clone the Github repository:  

`git clone https://github.com/poetapp/wordpress-plugin.git`

From within the checked out repository, run:  

`phing package`

The packaged zip file will be available under the build directory which is created under your locally cloned git repository.

### From this repository
Go to the [releases](https://github.com/poetapp/wordpress-plugin/releases) section of the repository and download the most recent release.

Then, from your WordPress administration panel, go to `Plugins > Add New` and click the `Upload Plugin` button on the top of the page.

### From the WordPress Plugin Directory
The Official Po.et WordPress Plugin can be found at https://wordpress.org/plugins/po-et/.

## How to Use
From your WordPress administration panel go to `Plugins > Installed Plugins` and scroll down until you find `Po.et`. You'll need to activate it first. Then click con `Settings` to configure it.

## Badge
Place the shortcode `[poet-badge]` anywhere in your article to display the Po.et Badge as proof that the article was timestamped.

## Configuration

### Author Name
This field will be used to set the `author` field on the Po.et work. Set it to your name, pen name, blog's name, etc.

### API URL
The URL of the Frost API. It'll default to Frost's URL once it's released.

### Token
The API token provided to you by Frost upon registry. This is what ties the published works to your Frost account.

### Post articles to API automatically on insert or update?	
Use this checkbox to enable or disable automatic posting.

## License

The Po.et Wordpress plugin is licensed under the terms of the MIT License and is available for free. See [LICENSE](LICENSE).

