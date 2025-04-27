<?php
namespace Doubleedesign\Comet\Core;
use DateTime, IntlDateFormatter;

/**
 * DateBlock component
 *
 * @package Doubleedesign\Comet\Core
 * @version 1.0.0
 * @description Display a date.
 */
#[AllowedTags([Tag::TIME])]
#[DefaultTag(Tag::TIME)]
class DateBlock extends DateComponent {
	/**
	 * @var DateTime|null $date
	 * @description The date to be displayed; can be passed in via $attributes as either as a DateTime object, Unix timestamp, or a string in YYYY-MM-DD format.
	 */
	protected DateTime|null $date;

	function __construct(array $attributes) {
		parent::__construct($attributes, 'components.DateBlock.date-block');
		$this->date = $this->convert_date($attributes['date']) ?? null;
	}

	/**
	 * Return a string representation of the date in a suitable format for the aria-label
	 * @return string
	 */
	public function get_accessible_date_string(): string {
		if($this->date === null) return '';

		// Day Date Month Year
		if($this->showDay && $this->showYear) {
			$formatter = new IntlDateFormatter(
				$this->locale,
				IntlDateFormatter::FULL,
				IntlDateFormatter::NONE
			);
			$formatter->setPattern('EEEE, d MMMM y'); // Day, date, month, year
		}
		// Day Date Month
		else if($this->showDay && !$this->showYear) {
			$formatter = new IntlDateFormatter(
				$this->locale,
				IntlDateFormatter::MEDIUM,
				IntlDateFormatter::NONE
			);
			$formatter->setPattern('EEEE, d MMMM'); // Day, date, month
		}
		// Date Month Year
		else if($this->showYear && !$this->showDay) {
			$formatter = new IntlDateFormatter(
				$this->locale,
				IntlDateFormatter::LONG,
				IntlDateFormatter::NONE
			);
			$formatter->setPattern('d MMMM y'); // Date, month, year
		}
		// Fallback: Just date and month
		else {
			$formatter = new IntlDateFormatter(
				$this->locale,
				IntlDateFormatter::LONG,
				IntlDateFormatter::NONE
			);
			$formatter->setPattern('d MMMM'); // Just date and month
		}

		return $formatter->format($this->date);
	}

	protected function get_html_attributes(): array {
		$attrs = parent::get_html_attributes();

		$attrs['datetime'] = $this->date->format('Y-m-d');

		return $attrs;
	}

	function render(): void {
		$blade = BladeService::getInstance();

		echo $blade->make($this->bladeFile, [
			'classes'    => implode(' ', $this->get_filtered_classes()),
			'attributes' => $this->get_html_attributes(),
			'date'       => $this->date,
			'showDay'    => $this->showDay,
			'showYear'   => $this->showYear,
		])->render();
	}
}
