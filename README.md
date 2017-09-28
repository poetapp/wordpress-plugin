# wordpress-plugin

This is our initial work on a plugin for WordPress.

The desired functionality is:

1. A configuration page to provide your Po.et keys for interacting with the network
1. Profile management of the information about the publisher (your blog, in this case)
1. Automatic publishing mode for works - whenever a post is published (or edited), that will be broadcast to the Po.et network
1. A shortcode `[poet-badge]` that will provide the "Verified on Po.et" badge

## Status

This is work in progress, and not currently usable.  The configuration has been started, and the hooks around article publishing put together.  Some difficulty was encountered dealing with the `/claims` API, and so some work is being done to make that easier to reason about.  At that point, this work will be resumed.

## License

The Po.et Wordpress plugin is licensed under the terms of the GPL Open Source license and is available for free.

