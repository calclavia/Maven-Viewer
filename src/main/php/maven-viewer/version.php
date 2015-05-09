<?php

/**
 * An object that represents a Maven repository.
 * @author Calclavia
 * @date 7/15/2014
 */
class Version
{
    /**
     * @var Maven - THe reference to the Maven object.
     */
    private $maven;
    private $dir;
    private $url;

    /**
     * The version number.
     */
    private $number = "";

    /**
     * @var array - The list of URLs to the artifacts
     */
    private $artifacts = array();

    /**
     * @var string - The dependencies of this artifact
     */
    private $dependencies = array();

    /**
     * @var string - The list of changes in this build version.
     */
    private $changes = array();

    private $propInterpret;

    public $ignoreExtensions = array(".md5", ".sha1", ".pom", ".properties");

    private $isCached = false;

    public function __construct(Maven $maven, $version)
    {
        $this->maven = $maven;
        $this->number = $version;
        $this->dir = $this->maven->getDirectory() . $this->number;
        $this->url = $this->maven->externalURL() . $this->number;
        $this->propInterpret = get_option('maven_viewer_build_properties');
    }

    public function number()
    {
        return $this->number;
    }

    public function changes()
    {
        return $this->changes;
    }

    public function dependencies()
    {
        return $this->dependencies;
    }

    public function artifacts()
    {
        return $this->artifacts;
    }

    public function dir()
    {
        return $this->dir;
    }

    public function url()
    {
        return $this->url;
    }

    /**
     * Searches the file structure and generate the build info
     */
    public function cache()
    {
        if (!$this->isCached)
        {
            $interpretations = explode(";", $this->propInterpret);

            $prefix = $this->maven->artifactID() . "-" . $this->number . "-";

            $artifacts = FileHelper::getFilesInDir($this->dir);


            /**
             * Cache artifact files
             */
            foreach ($artifacts as $artifact)
            {
                if ($artifact == $prefix . "build.properties" && !empty($this->propInterpret))
                {
                    /**
                     * Read build properties file.
                     */

                    $properties = FileHelper::parseProperties(file_get_contents($this->dir . "/" . $artifact));

                    if ($properties)
                    {

                        /**
                         * Look through the properties interpreter an generate a file
                         */
                        foreach ($interpretations as $interpretation)
                        {
                            if (!empty($interpretation))
                            {
                                $interpretation = explode("=", $interpretation, 2);

                                if (isset($properties[$interpretation[0]]))
                                {
                                    $this->dependencies[] = str_replace("%1%", $properties[$interpretation[0]], $interpretation[1]);
                                }
                            }
                        }
                    }
                }
                else if ($artifact == $prefix . "changelog.xml")
                {
                    /**
                     * Generate description and changelog
                     */
                    $changelogXML = simplexml_load_file($this->dir . "/" . $artifact);

                    foreach ($changelogXML->change as $change)
                    {
                        $this->changes[] = htmlentities(trim((string)$change->comment), ENT_QUOTES);
                    }
                }
                else
                {
                    $isValidFile = true;

                    foreach ($this->ignoreExtensions as $ignore)
                        $isValidFile &= !strstr($artifact, $ignore);

                    if ($isValidFile)
                    {
                        /**
                         * Read the rest of the artifacts
                         */
                        $this->artifacts[] = $artifact;
                    }
                }
            }

            $this->isCached = true;
        }
    }
}