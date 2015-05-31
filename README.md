<h1> OER Quality Assurance Mediawiki Extension </h1>

Mediawiki extension for judging the quality of OER based on a subset of the [TIPS framework 2.0](http://cemca.org.in/ckfinder/userfiles/files/TIPS%20Framework_Version%202_0_Low.pdf) . The extension allows one to enable a comprehensive review for the quality of an OER (it can be present on a wiki page itself or the wiki page can be used only for QA with a link to the OER) by adding a simple tag to the page.

##Usage

Simply add the tag:

<code>&lt;qa&gt;&lt;/qa&gt;</code>
	
to the wiki page where the content of the OER is present. 

##Download and Installation

### Step 1

Get the latest extension by (Run the following command in the extensions folder of your Mediawiki installation):

<code> git clone https://github.com/akashagarwal/QualityAssuranceMWExtension QualityAssurance </code>

### Step 2

Install the databases required by the extension. Specified in table.sql. The databases can also be installed automatically when you run the [update script](https://www.mediawiki.org/wiki/Manual:Upgrading#Run_the_update_script)

### Step 3

At the end of the [LocalSettings.php](http://www.mediawiki.org/wiki/Manual:LocalSettings.php) file (but above the PHP end-of-code delimiter, *"?>"*, if present), the following line should be added:

<pre> require_once "$IP/extensions/QualityAssurance/QualityAssurance.php";</pre>>

### Step 4

In case you have downloaded the extension at a different folder than the default, change the value of the configuration variable *$wgHomedirPath* located towards the end of the *QualityAssurance.php* file.


## License

<a rel="license" href="http://creativecommons.org/licenses/by-sa/3.0/"><img alt="Creative Commons License" style="border-width:0" src="https://i.creativecommons.org/l/by-sa/3.0/88x31.png" /></a><br />This work is licensed under a <a rel="license" href="http://creativecommons.org/licenses/by-sa/3.0/">Creative Commons Attribution-ShareAlike 3.0 Unported License</a>.

© CEMCA 2015