<?php

// Adds meta data information to the HTML header for common reference managers
add_action('wp_head', 'citationMeta');

function citationMeta(){

// Code is only executed on posts, not pages
if(is_single()):

// Add possible subtitle to the title (adjust custom field (get_field('subline')) if necessary)
	$subTitle = get_field('subline');	
	$citationTitle = get_the_title();
	if($subTitle) :
		$citationTitle = $citationTitle . ': ' . $subTitle;
	endif;

// Strip HTML tags from excerpt
	$citationAbstract = strip_tags( get_the_excerpt() );

// Get date of post, name of blog and link to post
	$citationPublicationDate = get_the_date('F j, Y'); 
	$citationJournalTitle = get_bloginfo('name');
	$citationFulltextHtmlUrl = get_permalink();
	
// Get the tags of the post	
	$citationKeywords = get_the_tags();
	if ($citationKeywords) :
  		foreach($citationKeywords as $tag) {			
			$citationKeywords = $tag->name  . '; ' . $citationKeywords; 
  		}
	
		// Special code for the Völkerrechtsblog, removing the language codes for tags	
		$citationKeywords = str_replace('[:en]','',$citationKeywords);
		$citationKeywords = str_replace('[:de]','',$citationKeywords);
		$citationKeywords = str_replace('[:]','',$citationKeywords);
	endif;

// Retrieve the authors (adjust custom field or function to retrieve the array of authors correctly)
	$citationAuthors = get_coauthors();

// Retrieve DOI if it exists (adjust custom field (get_field('doi')) if necessary)
	$doi = get_field('doi');

// Retrieve the PDF by intr2dok if cooperation exists	
	if($doi) :
		$doiJSON = file_get_contents('https://dx.doi.org/api/handles/'.$doi);
		$doiData = json_decode($doiJSON, true);
		$intr2dokURL = $doiData['values'][1]['data']['value'];
		
		// Check if DOI links to intr2dok
		if (str_contains($intr2dokURL, 'https://intr2dok.vifa-recht.de/receive/')) :
			$intr2dokID = str_replace('https://intr2dok.vifa-recht.de/receive/', '', $intr2dokURL);
			$intr2dokXML = simplexml_load_file('https://intr2dok.vifa-recht.de/api/v2/objects/'.$intr2dokID.'/derivates');
			$intr2dokPDFtitle = $intr2dokXML->derobject->maindoc[0];
			$intr2dokPDFid =  $intr2dokXML->derobject->attributes('http://www.w3.org/1999/xlink')['href'];
			$intr2dokPDFurl = 'https://intr2dok.vifa-recht.de/servlets/MCRFileNodeServlet/'.$intr2dokPDFid.'/'.$intr2dokPDFtitle;
		endif;	
	endif;
?>


<!-- Meta Data for Reference Managers: Highwire Press tags -->
	<meta name="citation_title" content="<?php echo $citationTitle ?>">
	<meta name="citation_abstract" content="<?php echo $citationAbstract ?>">
	<meta name="citation_publication_date" content="<?php echo $citationPublicationDate ?>">
	<meta name="citation_journal_title" content="<?php echo $citationJournalTitle ?>">
	<meta name="citation_fulltext_html_url" content="<?php echo $citationFulltextHtmlUrl ?>">
	<meta name="citation_keywords" content="<?php echo $citationKeywords; ?>">
<?php foreach($citationAuthors as $author ): 
	// Adjust "display_name" if necessary 
	?>
	<meta name="citation_author" content="<?php echo $author->display_name; ?>">	
<?php endforeach; ?>		
<?php if($doi): ?>	
	<meta name="citation_doi" content="<?php echo $doi; ?>">
<?php endif; ?>	
<?php if($intr2dokPDFurl): ?>	
	<meta name="citation_pdf_url" content="<?php echo $intr2dokPDFurl; ?>">
<?php endif; ?>

<!-- Meta Data for Reference Managers: Dublin Core tags -->
	<meta name="DC.type" content="blogPost">
	<meta name="DC.title" content="<?php echo $citationTitle ?>">
	<meta name="DC.abstract" content="<?php echo $citationAbstract ?>">
	<meta name="DC.date" content="<?php echo $citationPublicationDate ?>">
	<meta name="DC.source" content="<?php echo $citationJournalTitle ?>">
	<meta name="DC.source" content="<?php echo $citationFulltextHtmlUrl ?>">
	<meta name="DC.subject" content="<?php echo $citationKeywords; ?>">
<?php foreach($citationAuthors as $author ): 
	// Adjust "display_name" if necessary 
	?>
	<meta name="DC.creator" content="<?php echo $author->display_name; ?>">	
<?php endforeach; ?>		
<?php if($doi): ?>	
	<meta name="DC.identifier" content="https://doi.org/<?php echo $doi; ?>">
<?php endif; ?>	
<?php if($intr2dokPDFurl): ?>	
	<meta name="DC.identifier" content="<?php echo $intr2dokPDFurl; ?>">
<?php endif;
endif;
}
