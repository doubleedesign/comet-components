<?php
namespace Doubleedesign\Comet\Core;
use DateTime;
use Exception;

abstract class DateComponent extends Renderable {
	use ColorTheme;

	/**
	 * @var string
	 * @description The locale to be used for formatting the date.
	 */
	protected string $locale = 'en_AU';
	/**
	 * @var bool $showDay
	 * @description Whether to show the day of the week.
	 */
	protected bool $showDay = false;
	/**
	 * @var bool $showYear
	 * @description Whether to show the year.
	 */
	protected bool $showYear = true;

	function __construct(array $attributes, string $bladeFile) {
		parent::__construct($attributes, $bladeFile);
		$this->set_color_theme_from_attrs($attributes, ThemeColor::PRIMARY);
		$this->locale = $attributes['locale'] ?? $this->locale;
		$this->showDay = $attributes['showDay'] ?? $this->showDay;
		$this->showYear = $attributes['showYear'] ?? $this->showYear;
	}

	protected function convert_date($date): ?DateTime {
		if($date instanceof DateTime) {
			return $date;
		}

		try {
			if(is_numeric($date)) {
				return new DateTime('@' . $date);
			}
			elseif(is_string($date)) {
				// Check for YYYY-MM-DD format
				if(preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
					$date = DateTime::createFromFormat('Y-m-d', $date);
					if($date !== false) {
						return $date;
					}
				}

				throw new Exception('Invalid date format. Expected YYYY-MM-DD or timestamp.');
			}
		}
		catch(Exception $e) {
			error_log('DateBlock: ' . $e->getMessage());
			return null;
		}

		return null;
	}

	protected function get_html_attributes(): array {
		$attrs = parent::get_html_attributes();

		$attrs['aria-label'] = $this->get_accessible_date_string();
		$attrs['data-color-theme'] = $this->colorTheme->value;

		return $attrs;
	}

	public abstract function get_accessible_date_string();
}
