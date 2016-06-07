<?php

namespace Behat\FlexibleMink\Context;

use Behat\FlexibleMink\PseudoInterface\FlexibleContextInterface;
use Behat\FlexibleMink\PseudoInterface\StoreContextInterface;
use Behat\FlexibleMink\PseudoInterface\WebDownloadContextInterface;

/**
 * {@inheritdoc}
 */
trait WebDownloadContext
{
    // Implements
    use WebDownloadContextInterface;

    // Depends
    use FlexibleContextInterface;
    use StoreContextInterface;

    /**
     * {@inheritdoc}
     */
    public function downloadViaLink($locator, $key = 'Download', $headers = '')
    {
        $url = $this->assertVisibleLink($locator)->getAttribute('href');

        // If the path does not contain a base_url, prepend the one specified in the configuration file.
        if (!preg_match('/^(http(s|):[\/]{2}|)(www\.|)[a-zA-Z0-9]+\.[a-zA-Z]{3}/', $url)) {
            $url = $this->getMinkParameter('base_url') . $url;
        }

        $this->download($url, $key, $headers);
    }

    /**
     * {@inheritdoc}
     *
     * @When I download the file :file
     * @When I download the file :file to :key
     */
    public function download($file, $key = 'Download', $headersString = '')
    {
        $ch = curl_init($file);
        $headers[] = $headersString;

        curl_setopt_array($ch, [
            CURLOPT_HEADER         => 0,
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_BINARYTRANSFER => 1,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
        ]);

        $response = curl_exec($ch);

        // Put response into object store.
        $this->put($response, $key);

        return $response;
    }
}
