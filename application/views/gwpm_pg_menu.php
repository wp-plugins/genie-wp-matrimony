<?php if( is_user_logged_in() && !$this->isOwnPage() ) { ?>
<div class="gwpm-menu">
	<ul >
	<li ><a href='<?php $this->get_gwpm_formated_url('page=profile&action=view') ?>' >Account</a></li>
	<li ><a href='<?php $this->get_gwpm_formated_url('page=activity&action=view') ?>' >Activity</a></li>
	<li ><a href='<?php $this->get_gwpm_formated_url('page=messages&action=view') ?>' >Messages</a></li>
	<li ><a href='<?php $this->get_gwpm_formated_url('page=gallery&action=view') ?>' >Gallery</a></li>
<!--	<?php if(current_user_can('level_10')) { ?>
		<li ><a href='<?php $this->get_gwpm_formated_url('page=admin', false) ?>' >Admin</a></li>
	<?php } ?>  -->
	</ul>
</div><br />
<?php } ?>