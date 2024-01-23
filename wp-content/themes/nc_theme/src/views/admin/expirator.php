<?php
/**
 * @var bool $enabled
 * @var string $date
 */
?>

<div style="margin: 1em 0;">
    <p>
        <input type="checkbox" name="nc_expirator_enabled" id="nc_expirator_enabled" value="1" <?= $enabled ? 'checked' : ''; ?>>
        <label for="nc_expirator_enabled">Archiver automatiquement ce contenu</label>
    </p>
    <input type="datetime-local" id="nc_expirator_date" name="nc_expirator_date" value="<?= $date; ?>">
</div>
