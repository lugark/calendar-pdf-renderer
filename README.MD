# CalendarGenerator
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%208.2-8892BF.svg)](https://php.net/)
[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=lugark_calendar-pdf-renderer&metric=alert_status)](https://sonarcloud.io/summary/new_code?id=lugark_calendar-pdf-renderer)
[![Code Smells](https://sonarcloud.io/api/project_badges/measure?project=lugark_calendar-pdf-renderer&metric=code_smells)](https://sonarcloud.io/summary/new_code?id=lugark_calendar-pdf-renderer)
[![Bugs](https://sonarcloud.io/api/project_badges/measure?project=lugark_calendar-pdf-renderer&metric=bugs)](https://sonarcloud.io/summary/new_code?id=lugark_calendar-pdf-renderer)
![CI](https://github.com/lugark/calendar-pdf-renderer/workflows/CI/badge.svg)

This library aims to have a flexible way to render a calendar

## Dependencies
- PHP 8.1 or higher
- Symfony 6.4
- [nesbot/carbon](https://github.com/briannesbitt/carbon) to iterate/calculate calendar data
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
        LandscapeYear::class,
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
