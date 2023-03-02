<?php
require dirname(__DIR__).'/vendor/autoload.php';

use Calendar\Pdf\Renderer\Renderer\CalendarRenderer;
use Calendar\Pdf\Renderer\Renderer\LandscapeYear;
use Calendar\Pdf\Renderer\Renderer\PdfRenderer;
use Calendar\Pdf\Renderer\Renderer\RenderRequest;

/**
 * Create a render request defining the type and the start from where to render
 */
$renderRequest = new RenderRequest(
    LandscapeYear::class,
    new DateTime('2021-01')
);

/**
 * Render the request!
 * Default output is the root directory with a filename "calendar.pdf"
 */
$renderer = new CalendarRenderer(new PdfRenderer());
$renderer->renderCalendar($renderRequest);
