<?php

namespace WPDesk\FCF\Free\Field\Type;

/**
 * {@inheritdoc}
 */
class ColorType extends TypeAbstract {

	const FIELD_TYPE = 'colorpicker';

	/**
	 * {@inheritdoc}
	 */
	public function get_field_type(): string {
		return self::FIELD_TYPE;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_field_type_label(): string {
		return __( 'Color Picker', 'flexible-checkout-fields' );
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_field_type_icon(): string {
		return 'icon-palette';
	}

	/**
	 * {@inheritdoc}
	 */
	public function is_available(): bool {
		return false;
	}
}
