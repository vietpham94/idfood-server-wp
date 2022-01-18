<?php

namespace WPDesk\FCF\Free\Field\Type;

/**
 * {@inheritdoc}
 */
class HeadingType extends TypeAbstract {

	const FIELD_TYPE = 'heading';

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
		return __( 'Headline', 'flexible-checkout-fields' );
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_field_type_icon(): string {
		return 'icon-heading';
	}

	/**
	 * {@inheritdoc}
	 */
	public function is_available(): bool {
		return false;
	}
}
