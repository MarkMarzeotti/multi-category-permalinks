# Multi Category Permalinks

Allow a varying number of categories in the post permalink structure.

This plugin solves a problem I come across often when working with SEO teams. It will allow multiple categories in the permalink structure instead of being locked into just one plus the post name. I'm not a fan of the custom permalink type plugins as it requires a lot of discipline to properly silo your content. When working on a site with a team that may include designers, SEOs, content writers, editors, marketers, or other devs, it can be hard to keep track of the content strategy. If anyone posting a new post also has to determine the url structure, things can slip through the cracks.

After building this plugin my posts had the following URLs:

```
https://example.com/snippets/this-is-a-post/
https://example.com/snippets/javascript/this-is-another-post/
https://example.com/snippets/javascript/es6/this-is-a-third-post/
```

This did not get in the way of pages and building URLs with a parent-child relationship or any other URLs built by WordPress (like feeds or archives).

This plugin does edit the post permalink using the [post_link](https://developer.wordpress.org/reference/hooks/post_link/) filter, so it does not take the permalink settings in `Settings > Permalinks` in the WordPress admin into account. (I would like to eventually add that functionality however.) This plugin should function as described regardless of what is set on the Permalink Settings page.