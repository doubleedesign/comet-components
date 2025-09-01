<?php
use Doubleedesign\CometCanvas\Classic\Frontend;

$fields = Frontend::get_contact_details_fields();
$address = array_intersect_key($fields, array_flip(['address', 'suburb', 'state', 'postcode']));
$other_fields = array_diff_key($fields, array_flip(['address', 'suburb', 'state', 'postcode']));

// Format address as single line
$address = $address['address'] . ', ' . implode(' ', array_slice($address, 1));
// Sort other fields so phone comes before email
ksort($other_fields);
$other_fields = array_reverse($other_fields);

$icons = array(
    'phone'        => 'fa-phone-volume',
    'email'        => 'fa-envelope',
);
$icons = apply_filters('comet_canvas_classic_contact_details_icons', $icons);
?>

<address class="contact-details" aria-label="Contact details for <?php echo get_bloginfo('name'); ?>">
	<?php
    if ($address) {
        echo <<<HTML
			<span class="contact-details__address" aria-label="Address: $address">
				<i role="presentation" class="fa-solid fa-location-dot"></i>
				$address
			</span>
		HTML;
    } ?>
	<?php
    foreach ($other_fields as $key => $value) {
        $icon = isset($icons[$key]) ? "<i role=\"presentation\" class=\"fa-solid {$icons[$key]}\"></i>" : '';
        $accessible_label = ($key === 'email') ? 'Email address' : str_replace("_", ' ', ucfirst($key));
        echo <<<HTML
			<span class="contact-details__{$key}" aria-label="$accessible_label: $value">
				$icon
				$value
			</span>
		HTML;
    }
?>
</address>
