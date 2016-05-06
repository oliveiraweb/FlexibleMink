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
     *
     * @When I download a file via the :locator link
     * @When I download a file to :key via the :locator link
     */
    public function downloadViaLink($locator, $key = 'Download')
    {
        $this->download($this->assertVisibleLink($locator)->getAttribute('href'), $key);
    }

    /**
     * {@inheritdoc}
     *
     * @When I download the file :file
     * @When I download the file :file to :key
     */
    public function download($file, $key = 'Download')
    {
        $data = $this->getSession()->evaluateScript(
<<<JS
            (function() {
                var out;
                $.ajax({
                    'async' : false,
                    'url' : '$file',
                    'success' : function(data, status, xhr) {
                        out = data;
                    }
                });

                return out;
            })();
JS
        );

        $this->put($data, $key);
    }
}
