<?php

namespace WPDesk\FCF\Free\Settings\Option;

/**
 * {@inheritdoc}
 */
class RequiredHiddenOption extends RequiredOption {

	/**
	 * {@inheritdoc}
	 */
	public function is_readonly(): bool {
		return true;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_label_tooltip(): string {
		return __( 'Requirement of this field is controlled by WooCommerce and cannot be changed.', 'flexible-checkout-fields' );
	}
}
