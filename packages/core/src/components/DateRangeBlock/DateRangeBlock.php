<?php
namespace Doubleedesign\Comet\Core;
use DateTime, IntlDateFormatter;
use OpenPsa\Ranger\Ranger;

/**
 * DateRangeBlock component
 *
 * @package Doubleedesign\Comet\Core
 * @version 1.0.0
 * @description Display a date range.
 */
#[AllowedTags([Tag::TIME])]
#[DefaultTag(Tag::TIME)]
class DateRangeBlock extends DateComponent {
    /**
     * @var DateTime|string|null $startDate
     * @description The start date to be displayed; can be passed in via $attributes as either as a DateTime object, Unix timestamp, or a string in YYYY-MM-DD format.
     */
    protected DateTime|string|null $startDate;

    /**
     * @var DateTime|string|null $endDate
     * @description The start date to be displayed; can be passed in via $attributes as either as a DateTime object, Unix timestamp, or a string in YYYY-MM-DD format.
     */
    protected DateTime|string|null $endDate;
    private Ranger $ranger;

    public function __construct(array $attributes) {
        parent::__construct($attributes, 'components.DateRangeBlock.date-range-block');
        $this->startDate = $this->convert_date($attributes['startDate'] ?? $attributes['start_date']) ?? null;
        $this->endDate = $this->convert_date($attributes['endDate'] ?? $attributes['end_date']) ?? null;
        $this->ranger = new Ranger($this->locale);
    }

    public function get_accessible_date_string(): string {
        if ($this->startDate === null || $this->endDate === null) {
            return '';
        }

        $sameMonth = $this->startDate->format('F') === $this->endDate->format('F') && $this->startDate->format('Y') === $this->endDate->format('Y');

        $this->ranger->setDateType(IntlDateFormatter::LONG);
        $this->ranger->setTimeType(IntlDateFormatter::NONE);
        $this->ranger->setRangeSeparator('-');

        // Day, date, and month
        if ($this->showDay) {
            $this->ranger->setDateType(IntlDateFormatter::FULL);

            // Day, date, and month
            if (!$this->showYear) {
                $default = $this->ranger->format($this->startDate, $this->endDate);

                // Remove years from the string
                return preg_replace('/\s\d{4}/', '', $default);
            }

            // Day, date, month, and year
            return $this->ranger->format($this->startDate, $this->endDate);
        }

        // Default - Date, month, and year
        $default = $this->ranger->format($this->startDate, $this->endDate);

        if ($sameMonth) {
            $transformed = preg_replace('/\s*-\s*/', '-', $default);
        }

        if (!$this->showYear) {
            $transformed = preg_replace('/\s\d{4}/', '', $transformed ?? $default);
        }

        return $transformed ?? $default;
    }

    private function get_day_range(): string {
        if ($this->startDate === null || $this->endDate === null) {
            return '';
        }

        $startDay = $this->startDate->format('l');
        $endDay = $this->endDate->format('l');

        if ($startDay === $endDay) {
            $startDay = substr($startDay, 0, 3);
        }

        $shortStartDay = substr($startDay, 0, 3);
        $shortEndDay = substr($endDay, 0, 3);

        return "$shortStartDay-$shortEndDay";
    }

    private function get_date_range(): string {
        if ($this->startDate === null || $this->endDate === null) {
            return '';
        }

        $startDate = $this->startDate->format('j');
        $endDate = $this->endDate->format('j');

        if ($startDate === $endDate) {
            return $startDate;
        }

        return "$startDate-$endDate";
    }

    private function get_month_range(): string {
        if ($this->startDate === null || $this->endDate === null) {
            return '';
        }

        $startMonth = $this->startDate->format('M');
        $endMonth = $this->endDate->format('M');

        if ($startMonth === $endMonth) {
            return $startMonth;
        }

        return "$startMonth-$endMonth";
    }

    private function get_year_range(): string {
        if ($this->startDate === null || $this->endDate === null) {
            return '';
        }

        if ($this->startDate->format('Y') === $this->endDate->format('Y')) {
            return $this->startDate->format('Y');
        }

        $startYear = $this->startDate->format('Y');
        $endYear = $this->endDate->format('yy');

        return "$startYear-$endYear";
    }

    protected function get_html_attributes(): array {
        $attrs = parent::get_html_attributes();

        if ($this->startDate) {
            $attrs['datetime'] = $this->startDate->format('Y-m-d');
        }

        return $attrs;
    }

    public function render(): void {
        $blade = BladeService::getInstance();

        echo $blade->make($this->bladeFile, [
            'classes'    => implode(' ', $this->get_filtered_classes()),
            'attributes' => $this->get_html_attributes(),
            'days'       => $this->showDay ? $this->get_day_range() : null,
            'dates'      => $this->get_date_range(),
            'month'      => $this->get_month_range(),
            'year'       => $this->showYear ? $this->get_year_range() : null,
        ])->render();
    }
}
