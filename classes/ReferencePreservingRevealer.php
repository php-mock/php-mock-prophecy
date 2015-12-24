<?php

namespace phpmock\prophecy;

use phpmock\generator\MockFunctionGenerator;
use Prophecy\Prophecy\RevealerInterface;

/**
 * Revealer proxy which keeps references.
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK Donations
 * @license http://www.wtfpl.net/txt/copying/ WTFPL
 * @internal
 */
final class ReferencePreservingRevealer implements RevealerInterface
{
    
    /**
     * @var RevealerInterface The subject.
     */
    private $revealer;
    
    /**
     * Sets the subject.
     *
     * @param RevealerInterface $revealer proxied revealer
     */
    public function __construct(RevealerInterface $revealer)
    {
        $this->revealer = $revealer;
    }

    /**
     * @SuppressWarnings(PHPMD)
     */
    public function reveal($value)
    {
        if (is_array($value)) {
            MockFunctionGenerator::removeDefaultArguments($value);
            foreach ($value as &$item) {
                $item = $this->revealer->reveal($item);
            }
            return $value;

        } else {
            return $this->revealer->reveal($value);
        }
    }
}
