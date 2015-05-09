<?php

/**
 * Builds a download page based on the generated builds.
 * @author Calclavia
 */
class DownloadBuilder
{
    private $versions = array();
    private $downloadPHP;

    public function __construct(array $versions)
    {
        $this->versions = $versions;
        $this->downloadPHP = get_option('maven_viewer_download_file');;
    }

    /**
     * Creates an HTMl table consisting of all versions.
     * @return string - An HTML string of the built table.
     */
    public function build()
    {
        //TODO: Reorganize the format
        $html = "";
        $html .= '<div><table id="maven-build-table" class="table table-hover maven-build-table">';
        $html .= "<thead><tr><td>#</td><td>Dependency</td><td>Artifacts</td></tr></thead>";

        for ($i = 0; $i < count($this->versions); $i++)
        {
            $version = $this->versions[$i];
            /**
             * Main build info.
             */
            $html .= "<tr>";
            $html .= "<td data-order='" . $i . "'>";
            if ($version->changes())
                $html .= "<a href='#' tabindex='0' class='build-popup' role='button' data-toggle='popover' data-trigger='focus' data-content='" . $this->generateUnorderedList($version->changes()) . "'>" . $version->number() . "</a>";
            else
                $html .= "<a href='#' tabindex='0' class='build-popup'>" . $version->number() . "</a>";
            $html .= "</td>";
            $html .= "<td>";
            $html .= $this->generateUnorderedList($version->dependencies());
            $html .= "</ul>";
            $html .= "</td>";
            $html .= "<td>";

            $artifactURLs = array();

            foreach ($version->artifacts() as $artifact)
            {
                $rawURL = $version->url() . "/" . $artifact;

                if ($this->downloadPHP)
                    $url = $this->downloadPHP . "?name=" . rawurlencode($artifact) . "&r=" . rawurlencode($rawURL);
                else
                    $url = $rawURL;

                $artifactURLs[] = "<a href='$url' target='_blank'>" . $artifact . "</a>";
            }

            $html .= $this->generateUnorderedList($artifactURLs);
            $html .= "</td>";
            $html .= "</tr>";
        }

        $html .= "</table></div>";
        return $html;
    }

    private function generateUnorderedList(array $arr)
    {
        $list = "<ul>";

        foreach ($arr as $element)
            $list .= "<li>" . $element . "</li>";

        $list .= "</ul>";

        return $list;
    }
} 