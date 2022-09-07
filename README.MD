# CalendarGenerator
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.4-8892BF.svg)](https://php.net/)
This library aims to have a flexible way to render a calendar

## Dependencies
- PHP 7.4 or higher
- Symfony 5.4
- [aeon-php/calendar](https://github.com/aeon-php/calendar) to iterate/calculate calendar
- [mpdf/mpdf](https://github.com/mpdf/mpdf) to generate the PDF

## Installaion
```
composer require lugark/calendar-pdf-renderer-bundle
```

## Usage
Examples can be found in the examples directory.

### Rendering the Calendar
To render a calendar you need to create a render request object. This contains all required information.
- Type of the calendar to render
- Startdate of the calendar (DateTime)
```
$renderRequest = new RenderRequest(
    RequestTypes::LANDSCAPE_YEAR,
    new DateTime('2021-01')
);
```

The request is passed to the render service that creates the pdf:
```
/**
 * Render the request!
 * Default output is the root directory with a filename "calendar.pdf"
 */
$renderer = new CalendarRenderer();
$renderer->renderCalendar($renderRequest);
```
<img width="400" height="auto" src="docs/images/Calendar.png" alt="Generated calendar" />
