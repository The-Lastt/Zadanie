<?php

class FormGenerator {
	private $action;
	private $fields = [];

	public function __construct($action) {
		$this->action = esc_url($action);
	}

	public function addField($type, $name, $label = '', $required = false, $class = '') {
		$this->fields[] = [
			'type' => $type,
			'name' => $name,
			'label' => $label,
			'required' => $required,
			'class' => $class
		];
	}

	public function render() {
		ob_start();
		?>
		<form method="POST" action="<?= $this->action ?>" class="generated-form">
			<?php foreach ($this->fields as $field): ?>
				<div class="form-group">
					<?php if ($field['label']): ?>
						<label for="<?= esc_attr($field['name']) ?>"><?= esc_html($field['label']) ?></label>
					<?php endif; ?>

					<?php if ($field['type'] === 'textarea'): ?>
						<textarea name="<?= esc_attr($field['name']) ?>" 
								  id="<?= esc_attr($field['name']) ?>" 
								  class="<?= esc_attr($field['class']) ?>" 
								  <?= $field['required'] ? 'required' : '' ?>></textarea>
					<?php else: ?>
						<input type="<?= esc_attr($field['type']) ?>" 
							   name="<?= esc_attr($field['name']) ?>" 
							   id="<?= esc_attr($field['name']) ?>" 
							   class="<?= esc_attr($field['class']) ?>" 
							   <?= $field['required'] ? 'required' : '' ?>>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>

			<input type="submit" value="Wyślij" class="submit-btn">
		</form>
		<div id="form-response"></div>
		<?php
		return ob_get_clean();
	}
}
