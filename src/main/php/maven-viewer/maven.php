<?php

/**
 * An object that represents a Maven repository.
 * @author Calclavia
 * @date 7/15/2014
 */
class Maven
{
    /** @var  The URL of the Maven root in the server with no trailing slash. */
    private $url;
    /** @var  The local URL of the Maven root in the server with no trailing slash. */
    private $local;
    /** @var  The group of the Maven configuration in package format. */
    private $group;
    /** @var  The artifactID of the Maven configuration. */
    private $artifactID;

    public $error = "";

    private $versions = array();

    /**
     * @var string - The base directory of the Maven repo with trailing slash.
     */
    private $baseDir;
    private $externalURL;

    public function __construct($url, $local, $group, $artifactID)
    {
        $this->url = $url;
        $this->local = $local;
        $this->group = $group;
        $this->artifactID = $artifactID;
        $this->baseDir = $this->local . "/" . (str_replace(".", "/", $this->group)) . "/" . $this->artifactID . "/";
        $this->externalURL = $this->url . "/" . (str_replace(".", "/", $this->group)) . "/" . $this->artifactID . "/";
    }

    /**
     * Searches the file structure and generate a build array
     */
    public function cache()
    {
        $mavenXmlUrl = $this->baseDir . "maven-metadata.xml";
        $mavenXml = simplexml_load_file($mavenXmlUrl);

        if ($mavenXml)
        {
            foreach ($mavenXml->versioning->versions->version as $version)
            {
                $this->versions[] = new Version($this, (string)$version);

            }

            $this->versions = array_reverse($this->versions);
            return;
        }

        $this->error = "Cannot find Maven XML metadata: " . ($this->baseDir . "maven-metadata.xml");
    }

    public function getBuilds($from = -10, $to = 10)
    {
        //TODO: Add pagination
        $lastVersions = $this->versions; //array_slice($this->versions, $from, $to);

        foreach ($lastVersions as $version)
        {
            /**
             * Generate a build
             */
            $version->cache();
        }

        return $lastVersions;
    }

    public function getDirectory()
    {
        return $this->baseDir;
    }

    public function group()
    {
        return $this->group;
    }

    public function artifactID()
    {
        return $this->artifactID;
    }

    public function externalURL()
    {
        return $this->externalURL;
    }
}

?>