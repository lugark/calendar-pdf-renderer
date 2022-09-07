<?php
require dirname(__DIR__).'/vendor/autoload.php';

use Calendar\Pdf\Renderer\Renderer\CalendarRenderer;
use Calendar\Pdf\Renderer\Renderer\RenderRequest;
use Calendar\Pdf\Renderer\Renderer\RenderRequest\RequestTypes;

/**
 * Create a render request defining the type and the start from where to render
 */
$renderRequest = new RenderRequest(
    RequestTypes::LANDSCAPE_YEAR,
    new DateTime('2021-01')
);

/**
 * Render the request!
 * Default output is the root directory with a filename "calendar.pdf"
 */
$renderer = new CalendarRenderer();
$renderer->renderCalendar($renderRequest);
