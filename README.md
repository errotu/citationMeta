# citationMeta
This repository contains a function to improve the quality of the metadata shown in the website’s HTML head of a Wordpress blog.

Such improvement is of particular importance for scientific blogs. The additional metadata is displayed as [Highwire Press tags](https://www.zotero.org/support/dev/exposing_metadata#using_an_open_standard_for_exposing_metadata) (inter alia [recommend by Google Scholar](https://scholar.google.com/intl/en/scholar/inclusion.html#indexing) as well as the [Dublin Core tags](https://www.dublincore.org/specifications/dublin-core/dcmi-terms/). When used, common reference management software, such as Zotero, can easily import the articles.

## How to use

Simply add the code of the citationMeta.php to the bottom of your Wordpress theme’s functions.php (without the leading “<?php”).

Please be aware that the code probably needs to be adjusted concerning the output of authors. As Wordpress allows only one author per post, there are many different solutions in the wild on how to add the possibility of having multiple authors per post. The current code is working with the popular [CoAuthors Plus plugin](https://wordpress.org/plugins/co-authors-plus/).

Also, the code was created for blogs cooperating with the [intr2dok service](https://intr2dok.vifa-recht.de). To provide a PDF, it retrieves the link of the PDF created by intr2dok. If you’re not cooperating with intr2dok, the code shouldn’t throw out any errors, but will – of course – not be able to provide any PDF.
