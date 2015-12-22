<!-- Piwik -->
<script type="text/javascript">
  var _paq = _paq || [];
<?php
if (isset($userid)) {
     echo sprintf("_paq.push(['setUserId', '%s']);", $userid);
}
?>
  _paq.push(['trackPageView']);
  _paq.push(['enableLinkTracking']);
  (function() {
    var ID="<?php echo $settings->piwikID;?>";
    var u="<?php echo $settings->piwik;?>";
    _paq.push(['setTrackerUrl', u+'.php']);
    _paq.push(['setSiteId', ID]);
    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
    g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'.js'; s.parentNode.insertBefore(g,s);
  })();
</script>
<noscript><p><img src="<?php echo $settings->piwik;?>.php?idsite=<?php echo $settings->piwikID;?>" style="border:0;" alt="" /></p></noscript>
<!-- End Piwik Code -->
