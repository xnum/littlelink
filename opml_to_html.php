#!/usr/bin/php

<?php
# From: https://gist.github.com/anthonygelibert/6e533280534ccafb74c2a99fc35f06ea
# Forked and edit to fit tiny tiny rss exported opml.
function usage()
{
    return "Usage: php convert.php /path/to/opml_file.opml > output.html\r\n";
}

# Print usage if need be.
if(count($argv) < 2) die(usage());

# Grab the file path.
$f = $argv[1];

# Load it into a SimpleXML.
$xml = simplexml_load_file($f);

# Output buffer
$out = '';
$isFirstSection = true;

# Run through the nodes in the OPML and buffer the Netscape output
foreach($xml->xpath("//outline") as $outline )
{
    $title = htmlspecialchars($outline['text'], ENT_QUOTES);
    $feed = htmlspecialchars($outline['xmlUrl'] ?? "");
    $blog = htmlspecialchars($outline['htmlUrl'] ?? "");

	if(strpos($feed, "kill-the-newsletter.com") !== false) {
		continue
	}

    // remove if contains kill the news letter because of personal link.
    if($feed && $blog)
    {
        // $out .= "\n\t" . '<DT><A HREF="' . $feed . '">' . $title . '</A>';
        $out .= sprintf("\n\t<dt><a href='%s'>[RSS]</a> <a href='%s'>%s</a>", $feed, $blog, $title);
    }
    else
    {
        if ($isFirstSection) {
            $isFirstSection = false;
        } else {
            $out .= "</dl>\n";
        }
        $out .= "<dt><h3>$title</h3><dl>";
    }
}

$out .= "\n"

?>

<!DOCTYPE html>
<HTML>
<head>

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-VP6W3LC8BL"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-VP6W3LC8BL');
</script>


<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
<link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
<TITLE>My RSS Feed List</TITLE>
</head>

<H1>My RSS Feed List</H1>
<a href="./rss.opml" download>下載rss.opml</a>
<p>Updated At: 
<?php
date_default_timezone_set('Asia/Taipei'); // 設定時區為台北
echo date('Y-m-d H:i:s');
?>
</p>
<?php echo $out; ?>
