<?php

namespace Behat\FlexibleMink\PseudoInterface;

/**
 * Provides functionality for working with downloaded files via a web browser.
 */
trait WebDownloadContextInterface
{
    /**
     * Downloads the file references by the link and stores the content under the given key in the store.
     *
     * @param string $locator The id|label of the link.
     * @param string $key     The key to store the content under, defaulting to "Download".
     * @param string $headers These are headers that may be needed depending on the item being downloaded.
     */
    abstract public function downloadViaLink($locator, $key = 'Download', $headers = '');

    /**
     * Downloads the specified file and stores the content under the given key in the store.
     *
     * @param string $file          The URL for the file to download.
     * @param string $key           The key to store the content under, defaulting to "Download".
     * @param string $headersString Headers to pass with the curl request.
     */
    abstract public function download($file, $key = 'Download', $headersString = '');

    /**
     * Generates a url with a base url.  If none is specified, current url is used.
     *
     * @param  string               $link url string to determine url with base for.
     * @throws ExpectationException If Base url could not be generated.
     * @return string
     */
    abstract public function getBaseUrl($link);
}
