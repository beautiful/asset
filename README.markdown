# Beautiful Asset

Asset management for Kohana.

## A warning

This module has not been tested, nor even run at all. I only wrote it
as an outline, as a todo for the near future. If you want to test it
and get it working, feel free. Contribute by forking and sending pull
requests. You will get full credit it if you request it.

## The idea

The idea involves `Asset_Group`s, `Asset`s and `Asset_Filter`s. An
`Asset` represents one file that needs to be included in an output. I
left that previous sentence fairly abstract on purpose. An `Asset` is
simply a resource to be grouped into an `Asset_Group`. That group can
then have various `Asset_Filter`s applied to it when it is rendered.

## Asset Types

These Asset types will be shipped when complete:

 - JavaScript
 - Cascading StyleSheets
 - Less
 - CoffeeScript

## Asset Filters

This might be slightly over-zealous:

 - Concat (for joining assets together)
 - Less (for compiling Less into CSS)
 - JSMin (for minifying JS)
 - Coffee (for compiling Coffee to JS)
 - JSHint (for linting your JS)
 
## Author

Luke Morton

## License

MIT