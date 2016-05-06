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
     */
    abstract public function downloadViaLink($locator, $key = 'Download');

    /**
     * Downloads the specified file and stores the content under the given key in the store.
     *
     * @param string $file the URL for the file to download.
     * @param string $key  The key to store the content under, defaulting to "Download".
     */
    abstract public function download($file, $key = 'Download');
}
